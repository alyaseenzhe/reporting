<?php

namespace App\Http\Livewire;

use App\Models\Billwise;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListPostponedByCustomer extends Component
{

    // Public properties to hold the report's state and data
    public $area_id = -1;
    public $postponed = [];
    public $posponed_due_amount = [];
    public $employees = [];

    public $load_data_flage = true;

    /**
     * A Livewire lifecycle hook that runs on every request. It acts as a security check,
     * ensuring the user is active and has the required permissions ('list.postponed-by-customers')
     * to view this report. If the checks fail, the user is redirected.
     */
    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.postponed-by-customers', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * This method initializes the data loading process, typically triggered by `wire:init` in the view.
     * It calls the main `load_data` function with a default aging period of 120 days. Once the data
     * is loaded, it emits an event to the frontend to make the results container visible.
     */
    public function init()
    {
        $this->load_data(120);
        if ($this->load_data_flage == false) {
            $this->emit('show-container');
        }

    }

    /**
     * The standard Livewire method that renders the component's Blade view and sets the master
     * dashboard layout.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.list-postponed-by-customer')
            ->layout('layouts.dashboard');
    }

    /**
     * This is the core data retrieval function for the report. It executes a series of queries to
     * build a comprehensive list of postponed (overdue) payments, filtered by the branches the
     * current user is authorized to view.
     *
     * @param int $days The number of days an invoice must be overdue to be considered "postponed".
     */
    public function load_data($days) {

        $end_date = Carbon::now()->format('Y-m-d');
        $day = $days;

        $emp_codes = [];

        $branches = json_decode(Auth::user()->branches);

        foreach ($branches as $branch) {
            $emps = User::where('branches', 'like', '%"'.$branch.'"%')->get();
            foreach ($emps as $emp) {
                array_push($emp_codes, $emp->emp_code);
            }
        }

        $emp_codes = array_unique($emp_codes);

        $this->employees = Billwise::join('accmast', 'billwise.customerno', 'accmast.nodeno')
            ->join('WarrentyInfo', 'WarrentyInfo.AccountNo', 'accmast.NodeNo')
            ->join('SInvoice', 'BillWise.VoucherNo', 'SInvoice.SInvoiceNo')
            ->join('StudentMast', 'WarrentyInfo.SalesEmployee', 'StudentMast.NodeNo')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
            ->where('billwise.type', 'N')
            ->where(DB::raw('(total+paid)'), '>', 0.01)
            ->where('VoucherNo', 'like', '210%')
            ->where(DB::raw('DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'), '>=', 0)
            ->whereIn('accmast.Type', [9, 10])
            ->select(DB::raw('accmast.Code as customer_code, accmast.Arabic_Name as customer_name, WarrentyInfo.SalesEmployee as employee_code, StudentMast.Arabic_Name as employee_name'))
            ->groupBy(DB::raw('accmast.Code, accmast.Arabic_Name, voucherno, WarrentyInfo.SalesEmployee, StudentMast.Arabic_Name'))
            ->orderBy('accmast.Code')
            ->pluck('employee_name', 'customer_code')
            ->toArray();

        $postponed_stmt = "select EmpCode, EmpName, Code, Arabic_Name, SUM(DueAmount) as due_amount from (
select *, DATEDIFF(day, VoucherDate, :end_date_time1) as days from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode,
       isnull((select top 1 StudentMast.Arabic_Name from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpName
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time3),0.00) as DueAmount
,:end_date_time4 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع')
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time6),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time7
and (accmast.Code like '0%' or accmast.Code like '1%')
) as tbl
) as tbl2
group by EmpCode, EmpName, Code, Arabic_Name";

        $postponed_stmt .= " having EmpCode in ";
        foreach ($emp_codes as $key => $emp_code) {
            if ($key === array_key_first($emp_codes)) {
                $postponed_stmt .= "('".$emp_code."', ";
            }
            elseif ($key === array_key_last($emp_codes)) {
                $postponed_stmt .= "'".$emp_code."')";
            }
            else {
                $postponed_stmt .= "'".$emp_code."',";
            }
        }

        $this->postponed = DB::connection('sqlsrv')->select($postponed_stmt,
            [
                'end_date_time1' => $end_date . " 23:59:23",
                'end_date_time2' => $end_date . " 23:59:23",
                'end_date_time3' => $end_date . " 23:59:23",
                'end_date_time4' => $end_date . " 23:59:23",
                'end_date_time5' => $end_date . " 23:59:23",
                'end_date_time6' => $end_date . " 23:59:23",
                'end_date_time7' => $end_date . " 23:59:23",
            ]);


        $postponed_due_stmt = "select Code, SUM(DueAmount) as due_amount from (
select *, DATEDIFF(day, VoucherDate, :end_date_time1) as days from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time3),0.00) as DueAmount
,:end_date_time4 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time6),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time7
and (accmast.Code like '0%' or accmast.Code like '1%')
) as tbl
where DATEDIFF(day, VoucherDate, :end_date_time8) >= :day
and EmpCode in ";

        foreach ($emp_codes as $key => $emp_code) {
            if ($key === array_key_first($emp_codes)) {
                $postponed_due_stmt .= "('".$emp_code."', ";
            }
            elseif ($key === array_key_last($emp_codes)) {
                $postponed_due_stmt .= "'".$emp_code."')";
            }
            else {
                $postponed_due_stmt .= "'".$emp_code."',";
            }
        }

        $postponed_due_stmt .= ") as tbl2 group by Code";

        $postponed_due = DB::connection('sqlsrv')->select($postponed_due_stmt,
            [
                'end_date_time1' => $end_date . " 23:59:23",
                'end_date_time2' => $end_date . " 23:59:23",
                'end_date_time3' => $end_date . " 23:59:23",
                'end_date_time4' => $end_date . " 23:59:23",
                'end_date_time5' => $end_date . " 23:59:23",
                'end_date_time6' => $end_date . " 23:59:23",
                'end_date_time7' => $end_date . " 23:59:23",
                'end_date_time8' => $end_date . " 23:59:23",
                'day' => $day
            ]);

        $this->posponed_due_amount = json_decode(json_encode($postponed_due), true);

        $this->load_data_flage = false;
    }

}
