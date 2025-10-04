<?php

namespace App\Http\Livewire;

use App\Models\Billwise;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListBillwise extends Component
{
    use WithPagination;

    public $loadData = false;
    public $records = [];

    /**
     * This method initializes the data loading process for the report. It sets the PHP memory limit
     * to handle potentially large datasets and then immediately calls the `load_data()` function
     * to begin fetching records from the database.
     */
    public function init()
    {
        ini_set('memory_limit', '128M');

//        $this->loadData = true;
        $this->load_data();

    }

    /**
     * This is a Livewire lifecycle hook that runs on every request. It serves as a security gatekeeper for the component.
     * It checks if the currently logged-in user is active and has the specific permission ('list.non-paid-vouchers')
     * required to view this report, either through their assigned user group or an administrator role. If the user
     * is not active or lacks permission, they are redirected away from the page.
     */
    public function booted() {
        ini_set('memory_limit', '128M');

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.non-paid-vouchers', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * This is the standard Livewire method responsible for rendering the component's user interface.
     * It returns the specified Blade view file (`list-billwise.blade.php`) and embeds it within the main
     * `layouts.dashboard` template, ensuring a consistent look and feel across the application.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {

        // حسب العميل

        return view('livewire.list-billwise'/*, compact('records')*/)
            ->layout('layouts.dashboard');
    }

    /**
     * This is the core data retrieval function for the report. It constructs and executes a complex database query
     * to fetch a list of non-paid vouchers (bills). The query joins multiple tables to gather customer, invoice,
     * and salesperson information. It applies several filters to narrow down the results, such as checking for active
     * customers, ensuring the bill has a balance due, and only including customers from the branches the logged-in user
     * is authorized to see. Finally, it calculates the due amount and the age of the invoice in days.
     */
    public function load_data() {

        $branches = json_decode(Auth::user()->branches);


        $this->records = Billwise::join('accmast', 'billwise.customerno', 'accmast.nodeno')
            ->join('WarrentyInfo', 'WarrentyInfo.AccountNo', 'accmast.NodeNo')
            ->join('SInvoice', 'BillWise.VoucherNo', 'SInvoice.SInvoiceNo')
            ->join('StudentMast', 'WarrentyInfo.SalesEmployee', 'StudentMast.NodeNo')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
            ->where('billwise.type', 'N')
            ->whereIn('accmast.Accmast_Department', $branches)
            ->where(DB::raw('(total+paid)'), '>', 0.01)
            ->where('VoucherNo', 'like', '210%')
            ->where(DB::raw('DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'), '>=', 0)
            ->whereIn('accmast.Type', [9, 10])
//            ->select(DB::raw('accmast.Code as customer_code, accmast.Arabic_Name as customer_name, voucherno, WarrentyInfo.SalesEmployee as employee_code, StudentMast.Arabic_Name as employee_name, voucherdate, Total, paid, (total+paid) as DueAmount, DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1 AS [days]'))
            ->select(DB::raw('accmast.Code as customer_code, accmast.Arabic_Name as customer_name, voucherno, StudentMast.Code as employee_code, StudentMast.Arabic_Name as employee_name, voucherdate, Total, paid, (total+paid) as DueAmount, DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1 AS [days]'))
            ->groupBy(DB::raw('accmast.Code, accmast.Arabic_Name, voucherno, StudentMast.Code, StudentMast.Arabic_Name, voucherdate, Total, paid, (total+paid), DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'))
            ->orderBy('accmast.Code')
            ->get();




        // حسب الفاتورة
//        $records = Billwise::join('accmast', 'billwise.customerno', 'accmast.nodeno')
//            ->join('WarrentyInfo', 'WarrentyInfo.AccountNo' , 'accmast.NodeNo')
//            ->join('SInvoice', 'BillWise.VoucherNo' , 'SInvoice.SInvoiceNo')
//            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
//            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
//            ->where('billwise.type', 'N')
//            ->where(DB::raw('(total+paid)'), '>', 0.01)
//            ->where('VoucherNo' , 'like', '210%')
//            ->where(DB::raw('DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'), '>=', 240)
//            ->whereIn('accmast.Type', [9, 10])
//            ->select(DB::raw('accmast.Code as customer_code, accmast.Arabic_Name as customer_name, voucherno, StudentMast.Code as employee_code, StudentMast.Arabic_Name as employee_name, voucherdate, Total, paid, (total+paid) as DueAmount, DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1 AS [days]'))
//            ->groupBy(DB::raw('accmast.Code, accmast.Arabic_Name, voucherno, StudentMast.Code, StudentMast.Arabic_Name, voucherdate, Total, paid, (total+paid), DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'))
//            ->orderBy('accmast.Code')
//            ->get();


//        dd($records);

        $this->emit('show-data');

    }
}
