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

    public function booted() {
        if ((Auth::user()->user_group && in_array('list.non-paid-vouchers', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }
    public function render()
    {

        // حسب العميل
        $records = Billwise::join('accmast', 'billwise.customerno', 'accmast.nodeno')
            ->join('WarrentyInfo', 'WarrentyInfo.AccountNo' , 'accmast.NodeNo')
            ->join('SInvoice', 'BillWise.VoucherNo' , 'SInvoice.SInvoiceNo')
            ->join('StudentMast', 'WarrentyInfo.SalesEmployee', 'StudentMast.NodeNo')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
            ->where('billwise.type', 'N')
            ->where(DB::raw('(total+paid)'), '>', 0.01)
            ->where('VoucherNo' , 'like', '210%')
            ->where(DB::raw('DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'), '>=', 240)
            ->whereIn('accmast.Type', [9, 10])
            ->select(DB::raw('accmast.Code as customer_code, accmast.Arabic_Name as customer_name, voucherno, WarrentyInfo.SalesEmployee as employee_code, StudentMast.Arabic_Name as employee_name, voucherdate, Total, paid, (total+paid) as DueAmount, DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1 AS [days]'))
            ->groupBy(DB::raw('accmast.Code, accmast.Arabic_Name, voucherno, WarrentyInfo.SalesEmployee, StudentMast.Arabic_Name, voucherdate, Total, paid, (total+paid), DATEDIFF(day, VoucherDate, CAST(GETDATE() AS Date))+1'))
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
        return view('livewire.list-billwise', compact('records'))
            ->layout('layouts.dashboard');
    }
}
