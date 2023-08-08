<?php

namespace App\Http\Livewire;

use App\Models\Billwise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListPostponedByCustomer extends Component
{

    public $area_id = -1;
    public $postponed = [];
    public $posponed_due_amount = [];
    public $employees = [];

    public $load_data_flage = true;

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

    public function init()
    {
        $this->load_data(120);
        if ($this->load_data_flage == false) {
            $this->emit('show-container');
        }

    }
    public function render()
    {
        return view('livewire.list-postponed-by-customer')
            ->layout('layouts.dashboard');
    }

    public function load_data($days) {

        $end_date = Carbon::now()->format('Y-m-d');
        $day = $days;

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

        $this->postponed = DB::connection('sqlsrv')->select("select EmpCode, Code, Arabic_Name, SUM(DueAmount) as due_amount from (
select *, DATEDIFF(day, VoucherDate, :end_date_time1) as days from (
select isnull((select top 1 StudentMast.Arabic_Name from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
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
group by EmpCode, Code, Arabic_Name",
            [
                'end_date_time1' => $end_date . " 23:59:23",
                'end_date_time2' => $end_date . " 23:59:23",
                'end_date_time3' => $end_date . " 23:59:23",
                'end_date_time4' => $end_date . " 23:59:23",
                'end_date_time5' => $end_date . " 23:59:23",
                'end_date_time6' => $end_date . " 23:59:23",
                'end_date_time7' => $end_date . " 23:59:23",
            ]);

        $postponed_due = DB::connection('sqlsrv')->select("select Code, SUM(DueAmount) as due_amount from (
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
) as tbl2
group by Code",
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
