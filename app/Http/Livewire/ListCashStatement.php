<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListCashStatement extends Component
{
//    public $area_id = -1;
    public $start_date;
    public $end_date;
    public $results = [];
    public $customer_code = '';

    protected $rules = [
//        'area_id' => 'required|not_in:-1',
        'start_date' => 'required',
        'end_date' => 'required',
        'customer_code' => 'required'
    ];

    protected $messages = [
//        'area_id.required' => "مطلوب",
//        'area_id.not_in' => "مطلوب",
        'start_date.required' => "مطلوب",
        'end_date.required' => "مطلوب",
        'customer_code.required' => "مطلوب",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }


        if ((Auth::user()->user_group && in_array('list.customer-cash-statement', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.list-cash-statement')
            ->layout('layouts.dashboard');
    }

    public function generateReport() {

        $this->validate();
//        $this->emit('show-container');
        $this->proccess_report();
        $this->emit('show-container');
    }

    public function proccess_report() {

        $start_date = date($this->start_date . ' 00:00:00');
        $end_date = $this->end_date . ' 23:59:59';

        $query = DB::connection('sqlsrv')->select("SELECT Code, Name, VoucherNo, VoucherDate, Value, customer_code, emp_name FROM (
select distinct code,name, PaymentMethodDetails.*
,sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue
from PaymentMethodDetails,sinvoice
,accmast where sinvoiceno=voucherno and
partyno=nodeno and accmast.[type]=10
and PaymentMethodDetails.type in (1,2,3,4)
and voucherdate>=:start_date1 and  voucherdate<=:end_date1
  group by code,name, voucherno,accountno,PaymentMethodDetails.value,PaymentMethodDetails.type,
voucherdate,PaymentMethodDetails.ActualVoucherPrefix
union all
select distinct code,name, PaymentMethodDetails.*
,sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue
from PaymentMethodDetails,pinvoice
,accmast where pinvoiceno=voucherno and
partyno=nodeno and accmast.[type]=10
and PaymentMethodDetails.type in (1,2,3,4)
and voucherdate>=:start_date2 and  voucherdate<=:end_date2
  group by code,name, voucherno,accountno,PaymentMethodDetails.value,PaymentMethodDetails.type,
voucherdate,PaymentMethodDetails.ActualVoucherPrefix) as cash_tbl
LEFT JOIN (select accmast.Code as customer_code, StudentMast.Arabic_Name as emp_name from accmast, WarrentyInfo, StudentMast
where accmast.NodeNo = WarrentyInfo.AccountNo
and WarrentyInfo.SalesEmployee = StudentMast.NodeNo) as manager_tbl
on cash_tbl.code = manager_tbl.customer_code
where Code =:customer_code
order by VoucherDate asc", [

            'start_date1' => $start_date,
            'start_date2' => $start_date,
            'end_date1' => $end_date,
            'end_date2' => $end_date,
            'customer_code' => $this->customer_code,
//            'voucherno1' => $this->search,
//            'voucherno2' => $this->search,
        ]);

        $this->results = json_decode(json_encode($query), true);

        return $this->results;
//        $this->emit('show-container');

    }
}
