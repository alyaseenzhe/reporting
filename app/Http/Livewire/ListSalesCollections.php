<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\FAExtra;
use App\Models\PInvoice;
use App\Models\SInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListSalesCollections extends Component
{
    public $area_id = -1;
    public $final_results = [];
    public $start_date;
    public $end_date;

    protected $rules = [
        'area_id' => 'required|not_in:-1',
        'start_date' => 'required',
        'end_date' => 'required',
    ];

    protected $messages = [
        'area_id.required' => "مطلوب",
        'area_id.not_in' => "مطلوب",
        'start_date.required' => "مطلوب",
        'end_date.required' => "مطلوب",
    ];

    public function booted() {
        if ((Auth::user()->user_group && in_array('list.sales-collections', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {

//        dd($this->collected2(['1200016'], date('2023-01-01 00:00:00'), date('2023-31-31 23:59:59')));
        return view('livewire.list-sales-collections')
            ->layout('layouts.dashboard');
    }

    public function proccess_report() {

//        $this->postponed_sales2('0100986', '27');

        set_time_limit(2000);
        $customers_code = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
//            ->where('accmast.Blocked', 'N')
//            ->where(function ($query) {
//                $query->orWhere('accmast.Accmast_Department', '3')
//                    ->orWhere('accmast.Accmast_Department', '10')
//                    ->orWhere('accmast.Accmast_Department', '7')
//                    ->orWhere('accmast.Accmast_Department', '13')
//                    ->orWhere('accmast.Accmast_Department', '4')
//                    ->orWhere('accmast.Accmast_Department', '6')
//                    ->orWhere('accmast.Accmast_Department', '5')
//                    ->orWhere('accmast.Accmast_Department', '12')
//                    ->orWhere('accmast.Accmast_Department', '11')
//                    ->orWhere('accmast.Accmast_Department', '9')
//                    ->orWhere('accmast.Accmast_Department', '8')
//                    ->orWhere('accmast.Accmast_Department', '505');
//            })
            ->where(function ($query) {
                $query->orWhere('accmast.Code', 'like',  '01%')
                    ->orWhere('accmast.Code', 'like',  '02%')
                    ->orWhere('accmast.Code', 'like',  '03%')
                    ->orWhere('accmast.Code', 'like',  '04%')
                    ->orWhere('accmast.Code', 'like',  '05%')
                    ->orWhere('accmast.Code', 'like',  '06%')
                    ->orWhere('accmast.Code', 'like',  '07%')
                    ->orWhere('accmast.Code', 'like',  '08%')
                    ->orWhere('accmast.Code', 'like',  '09%')
                    ->orWhere('accmast.Code', 'like',  '10%')
                    ->orWhere('accmast.Code', 'like',  '11%')
                    ->orWhere('accmast.Code', 'like',  '12%')
                    ->orWhere('accmast.Code', 'like',  '1-01%')
                    ->orWhere('accmast.Code', 'like',  '1-02%')
                    ->orWhere('accmast.Code', 'like',  '1-03%')
                    ->orWhere('accmast.Code', 'like',  '1-04%')
                    ->orWhere('accmast.Code', 'like',  '1-05%')
                    ->orWhere('accmast.Code', 'like',  '1-06%')
                    ->orWhere('accmast.Code', 'like',  '1-07%')
                    ->orWhere('accmast.Code', 'like',  '1-08%')
                    ->orWhere('accmast.Code', 'like',  '1-09%')
                    ->orWhere('accmast.Code', 'like',  '1-10%')
                    ->orWhere('accmast.Code', 'like',  '1-11%')
                    ->orWhere('accmast.Code', 'like',  '1-12%');
            })
            ->whereRaw('LEN(accmast.Code) > 3')
            ->where('accmast.Code', 'like',  $this->area_id.'%')
            ->pluck('accmast.Code as customer_code');

        $customer_details = $this->customer_details();
        $collected = $this->collected2($customers_code, $this->start_date, $this->end_date);
        $cash = $this->cash2($customers_code, $this->start_date, $this->end_date);
        $postponed_sales = $this->postponed_sales2($customers_code, $this->start_date, $this->end_date);
        $postponed_amount = $this->postponed_amount2($customers_code, $this->end_date);
        $postponed_due_amount = $this->postponed_due_amount2($customers_code, $this->end_date, 120);

//        dd($customers_code);
//        dd($cash);

        $this->final_results = [];

        foreach ($customer_details as $customer) {
            $record = [];

            $record['customer_code'] = $customer->customer_code;
            $record['customer_name'] = $customer->customer_name;
            $record['emp_code'] = $customer->emp_code;
            $record['emp_name'] = $customer->emp_name;

//            $collected_index = array_search($customer->customer_code, array_column($collected, 'Code'));
            $collected_index = array_key_exists($customer->customer_code, $collected);
//            dd($record['customer_code']."--->".$collected_index);
            if ($collected_index) {
//                dd($collected_index);
                $record['collected'] = $collected[$customer->customer_code];
//                dd($record['collected']);
//                $record['collected'] += $collected[$collected_index]['Value'];
            }
            else {
                $record['collected'] = 0;
            }

//            dd($record['collected']);

            $cash_index = array_key_exists($customer->customer_code, $cash);
            if ($cash_index) {
                $record['cash'] = $cash[$customer->customer_code];
            }
            else {
                $record['cash'] = 0;
            }

//            $postponed_sales_index = array_search($customer->customer_code, array_column($postponed_sales->toArray(), 'Code'));
//            $postponed_sales_index = array_key_exists("0100986", $postponed_sales);
            $postponed_sales_index = array_key_exists($customer->customer_code, $postponed_sales);

            if ($postponed_sales_index) {
                $record['postponed_sales'] = $postponed_sales[$customer->customer_code];
            }
            else {
                $record['postponed_sales'] = 0;
            }

            $postponed_amount_index = array_search($customer->customer_code, array_column($postponed_amount, 'Code'));
            if ($postponed_amount_index) {
                $record['postponed_amount'] = $postponed_amount[$postponed_amount_index]['DueAmount'];
            }
            else {
                $record['postponed_amount'] = 0;
            }

            $postponed_due_amount_index = array_search($customer->customer_code, array_column($postponed_due_amount, 'Code'));
//            dd($postponed_due_amount);
//            $postponed_due_amount_index = array_search('0300345', array_column($postponed_due_amount, 'Code'));

            if ($postponed_due_amount_index) {
                $record['postponed_due_amount'] = $postponed_due_amount[$postponed_due_amount_index]['DueAmount'];
            }
            else {
                $record['postponed_due_amount'] = 0;
            }

            array_push($this->final_results, $record);
        }

//        dd($this->final_results);
//        foreach ($this->final_results as $a) {
//            if ($a['customer_code'] == "0300345") {
//                dd($a);
//            }
//        }
        return $this->final_results;
    }

    public function generateReport() {

        set_time_limit(2000);

        $this->validate();
        $this->emit('show-container');

        $this->proccess_report();
    }

    public function customer_details() {

        set_time_limit(2000);
        $records = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
//            ->where('accmast.Blocked', 'N')
//            ->where(function ($query) {
//                $query->orWhere('accmast.Accmast_Department', '3')
//                    ->orWhere('accmast.Accmast_Department', '10')
//                    ->orWhere('accmast.Accmast_Department', '7')
//                    ->orWhere('accmast.Accmast_Department', '13')
//                    ->orWhere('accmast.Accmast_Department', '4')
//                    ->orWhere('accmast.Accmast_Department', '6')
//                    ->orWhere('accmast.Accmast_Department', '5')
//                    ->orWhere('accmast.Accmast_Department', '12')
//                    ->orWhere('accmast.Accmast_Department', '11')
//                    ->orWhere('accmast.Accmast_Department', '9')
//                    ->orWhere('accmast.Accmast_Department', '8')
//                    ->orWhere('accmast.Accmast_Department', '505');
//            })
            ->where(function ($query) {
                $query->orWhere('accmast.Code', 'like',  '01%')
                    ->orWhere('accmast.Code', 'like',  '02%')
                    ->orWhere('accmast.Code', 'like',  '03%')
                    ->orWhere('accmast.Code', 'like',  '04%')
                    ->orWhere('accmast.Code', 'like',  '05%')
                    ->orWhere('accmast.Code', 'like',  '06%')
                    ->orWhere('accmast.Code', 'like',  '07%')
                    ->orWhere('accmast.Code', 'like',  '08%')
                    ->orWhere('accmast.Code', 'like',  '09%')
                    ->orWhere('accmast.Code', 'like',  '10%')
                    ->orWhere('accmast.Code', 'like',  '11%')
                    ->orWhere('accmast.Code', 'like',  '12%')
                    ->orWhere('accmast.Code', 'like',  '1-01%')
                    ->orWhere('accmast.Code', 'like',  '1-02%')
                    ->orWhere('accmast.Code', 'like',  '1-03%')
                    ->orWhere('accmast.Code', 'like',  '1-04%')
                    ->orWhere('accmast.Code', 'like',  '1-05%')
                    ->orWhere('accmast.Code', 'like',  '1-06%')
                    ->orWhere('accmast.Code', 'like',  '1-07%')
                    ->orWhere('accmast.Code', 'like',  '1-08%')
                    ->orWhere('accmast.Code', 'like',  '1-09%')
                    ->orWhere('accmast.Code', 'like',  '1-10%')
                    ->orWhere('accmast.Code', 'like',  '1-11%')
                    ->orWhere('accmast.Code', 'like',  '1-12%');
            })
            ->whereRaw('LEN(accmast.Code) > 3')
            ->where('accmast.Code', 'like',  $this->area_id.'%')
            ->select('accmast.NodeNo as customer_nodeno', 'accmast.Code as customer_code', 'accmast.Arabic_Name as customer_name', 'StudentMast.Code as emp_code','StudentMast.Arabic_Name as emp_name')
            ->orderBy('accmast.Code')
            ->get();

        return $records;
    }

    public function collected($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = date($start_date . ' 00:00:00');
        $end_date = $end_date . ' 23:59:59';

//        dd($start_date);

        $collected_vouchers = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
            ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
            ->where('accmast.Code', $customer_code)
//            ->where('AccountNo', $customer_no)
//            ->where('StudentMast.Code', $emp_code)
            ->where(function ($query) {
                $query->orWhere('FAExtra.VoucherNo', 'like',  '030%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '040%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '050%');
            })
            ->where('FAExtra.VoucherDate', '>=', $start_date)
            ->where('FAExtra.VoucherDate', '<=', $end_date)

            ->get('VoucherNo')->toArray();
//        dd($collected_vouchers);

        $collectedReverse = [];
        if (count($collected_vouchers) > 0) {
            $collectedReverse = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->where('accmast.Code', $customer_code)
//                ->where('AccountNo', $customer_no)
//                ->where('StudentMast.Code', $emp_code)
                ->where(function ($query) {
                    $query->orWhere('VoucherNo', 'like', '031%')
                        ->orWhere('VoucherNo', 'like', '041%')
                        ->orWhere('VoucherNo', 'like', '051%');
                })
                ->whereIn('VField9', [$collected_vouchers])
                ->get('VField9')->toArray();
//        dd($collectedReverse);
        }


        $collected = [];
        // if there is a reverse
        if (count($collectedReverse) > 0) {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->where('accmast.Code', $customer_code)
//                ->where('AccountNo', $customer_no)
//                ->where('StudentMast.Code', $emp_code)
                ->whereNotIn('VoucherNo', [$collectedReverse])
                ->whereNotIn('VField9', [$collectedReverse])
//                ->where(function ($query) use ($collectedReverse){
//                    $query->whereNotIn('VoucherNo', [$collectedReverse])
//                        ->whereNotIn('VField9', [$collectedReverse]);
//                })
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
//                ->select('VoucherNo', 'VoucherDate', 'Value', 'VField9', 'Area', 'AccountNo')
                ->sum('Value');
//            ->get();
//            dd($collected);
        }
        else {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->where('accmast.Code', $customer_code)
//                ->where('AccountNo', $customer_no)
//                ->where('StudentMast.Code', $emp_code)
//                ->where(function ($query) use ($collectedReverse){
//                    $query->whereNotIn('VoucherNo', [$collectedReverse])
//                        ->whereNotIn('VField9', [$collectedReverse]);
//                })
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
//                ->select('VoucherNo', 'VoucherDate', 'Value', 'VField9', 'Area', 'AccountNo')
                ->sum('Value');
//            ->get();
//            dd($collected .' - '. $customer_no);
        }

        return $collected;


    }

    public function collected2($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = date($start_date . ' 00:00:00');
        $end_date = $end_date . ' 23:59:59';

//        dd($end_date);

        $collected_vouchers = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
            ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
            ->whereIn('accmast.Code', $customer_code)
            ->where(function ($query) {
                $query->orWhere('FAExtra.VoucherNo', 'like',  '030%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '040%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '050%');
            })
            ->whereBetween('FAExtra.VoucherDate', [$start_date, $end_date])
//            ->where('FAExtra.VoucherDate', '>=', $start_date)
//            ->where('FAExtra.VoucherDate', '<=', $end_date)

            ->get('VoucherNo')->toArray();
//        dd($collected_vouchers);

        $collectedReverse = [];
        if (count($collected_vouchers) > 0) {
            $collectedReverse = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->whereIn('accmast.Code', $customer_code)
                ->where(function ($query) {
                    $query->orWhere('VoucherNo', 'like', '031%')
                        ->orWhere('VoucherNo', 'like', '041%')
                        ->orWhere('VoucherNo', 'like', '051%');
                })
                ->whereIn('VField9', [$collected_vouchers])
                ->get('VField9')->toArray();
        }


        $collected = [];
        // if there is a reverse
        if (count($collectedReverse) > 0) {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->whereIn('accmast.Code', $customer_code)
                ->whereNotIn('VoucherNo', [$collectedReverse])
                ->whereNotIn('VField9', [$collectedReverse])
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
//                ->selectRaw('AccountNo,accmast.Code, SUM(Value)') //
//                ->groupBy('AccountNo', 'accmast.Code') //
                ->select('VoucherNo', 'VoucherDate', 'Value', 'VField9', 'Area', 'AccountNo', 'accmast.Code')
                ->get();
        }
        else {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
                ->whereIn('accmast.Code', $customer_code)
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
//                ->selectRaw('AccountNo,accmast.Code, SUM(Value)') //
//                ->groupBy('AccountNo', 'accmast.Code') //
                ->select('VoucherNo', 'VoucherDate', 'Value', 'VField9', 'Area', 'AccountNo', 'accmast.Code')
                ->get();
        }

//        dd($collected->groupBy('Code')->map(function ($row) {
//            return $row->sum('Value');
//        })->toArray());
        return $collected->groupBy('Code')->map(function ($row) {
            return $row->sum('Value');
        })->toArray();
//        return $collected->toArray();
    }

    public function cash($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';

        $pinvoice_query = PInvoice::join('PaymentMethodDetails', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->where('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethodDetails', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->where('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix')
            ->unionAll($pinvoice_query) //
            ->sum('svalue');
//            ->get();

        return $sinvoice_query;

    }

    public function cash2($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';

        $pinvoice_query = PInvoice::join('PaymentMethodDetails', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethodDetails', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix')
//            ->sum('svalue');
            ->unionAll($pinvoice_query) //
//                ->groupBy('Code')
//            ->select('code')
//            ->sum('svalue');
            ->get();

//        dd($sinvoice_query);

        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
            return $item->sum('Value');
        });

//        dd($results);

//        dd(collect($sinvoice_query)->groupBy('code')->map(function ($item) {
//            return $item->sum('svalue');
//        }));
//        dd($sinvoice_query->groupBy('code')->sum('svalue'));
//        return $sinvoice_query;
//        dd($results);
        return $results->toArray();

    }

    public function postponed_sales($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';

        $pinvoice_query = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->where('PaymentMethod.Credit', '<>' , 0)
            ->where('code', $customer_code)
            ->where('PIDate' ,'>=', $start_date)
            ->where('PIDate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->where('PaymentMethod.Credit', '<>' , 0)
            ->where('code', $customer_code)
            ->where('SIDate' ,'>=', $start_date)
            ->where('SIDate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
            ->unionAll($pinvoice_query) //
            ->sum('svalue');
//            ->sum('svalue');
//            ->get();

        return $sinvoice_query;

    }

    public function postponed_sales2($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';

        $pinvoice_query = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->where('PaymentMethod.Credit', '<>' , 0)
            ->whereIn('code', $customer_code)
            ->where('PIDate' ,'>=', $start_date)
            ->where('PIDate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->where('accmast.type', 10)
            ->where('PaymentMethod.Credit', '<>' , 0)
            ->whereIn('code', $customer_code)
            ->where('SIDate' ,'>=', $start_date)
            ->where('SIDate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
            ->unionAll($pinvoice_query) //
//            ->sum('svalue');
//            ->sum('svalue');
            ->get();

//        dd($sinvoice_query);

//        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
//            return $item->sum('svalue');
//        });

        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
            return $item->sum('Credit');
        });

//        dd($results);

//        return $sinvoice_query;
        return $results->toArray();

    }

    public function postponed_amount($customer_code, $end_date) {

        set_time_limit(2000);
        $query = DB::connection('sqlsrv')->select("select Code, Name, SUM(DueAmount) as DueAmount from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time1),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as DueAmount
,:end_date_time3 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
--and accmast.code='0100973'
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time4),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time6
--and areamast.nodeno=@area
and (accmast.Code like '0%' or accmast.Code like '1%')
--and voucherno='210-01021-1300'
) as tbl
--where EmpCode = '10259'
where Code = :customer_code1
group by Code, Name", [
            'end_date_time1' => $end_date . ' 23:59:59',
            'end_date_time2' => $end_date . ' 23:59:59',
            'end_date_time3' => $end_date . ' 23:59:59',
            'end_date_time4' => $end_date . ' 23:59:59',
            'end_date_time5' => $end_date . ' 23:59:59',
            'end_date_time6' => $end_date . ' 23:59:59',
            'customer_code1' => $customer_code,
        ]);

        $result = json_decode(json_encode($query), true);
        return $result? round($result[0]['DueAmount'], 2) : 0;
    }

    public function postponed_amount2($customer_code, $end_date) {

        $customer_code = $customer_code->toArray();

        $cus_code = "";
        foreach ($customer_code as $key => $code) {
            if ($key === array_key_first($customer_code)) {
                $cus_code .= "(accmast.Code='".$code."' ";
            }
            elseif ($key === array_key_last($customer_code)) {
                $cus_code .= "or accmast.Code='".$code."')";
            }
            else {
                $cus_code .= "or accmast.Code='".$code."' ";
            }
        }

        set_time_limit(2000);
        $query = DB::connection('sqlsrv')->select("select Code, Name, SUM(DueAmount) as DueAmount from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time1),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as DueAmount
,:end_date_time3 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
--and accmast.code='0100973'
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time4),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time6
--and areamast.nodeno=@area
and (accmast.Code like '0%' or accmast.Code like '1%')
--and accmast.Code in (['12110007','12090397','12090407','1200018','1200007','1200000','1200015','1200027','1200028','1200031','1200004','1200005','1200021','1200025','1200030','1200029','1200016','1200008','1200009','1200022','1200012','1200010','1200032','1200013','1200026','1200014','1200023','1200019','1200001','1200002','1200024'])
--and accmast.Code in (:customer_code1)
and ". $cus_code ."
--and accmast.Code in ('0100412', '12090397')
--and voucherno='210-01021-1300'
) as tbl
--where EmpCode = '10259'
--where Code in (:customer_code1)
group by Code, Name", [
            'end_date_time1' => $end_date . ' 23:59:59',
            'end_date_time2' => $end_date . ' 23:59:59',
            'end_date_time3' => $end_date . ' 23:59:59',
            'end_date_time4' => $end_date . ' 23:59:59',
            'end_date_time5' => $end_date . ' 23:59:59',
            'end_date_time6' => $end_date . ' 23:59:59',
        ]);

        $result = json_decode(json_encode($query), true);

        return $result;
    }

    public function postponed_due_amount($customer_code, $end_date, $day) {

        set_time_limit(2000);
        $query = DB::connection('sqlsrv')->select("select Code, Name, SUM(DueAmount) as DueAmount from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time1),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as DueAmount
,:end_date_time3 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
--and accmast.code='0100973'
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time4),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time6
--and areamast.nodeno=@area
and (accmast.Code like '0%' or accmast.Code like '1%')
--and voucherno='210-01021-1300'
) as tbl
--where EmpCode = '10259'
where Code = :customer_code1
and DATEDIFF(day, VoucherDate, :end_date_time7) > :day
group by Code, Name", [
            'end_date_time1' => $end_date . ' 23:59:59',
            'end_date_time2' => $end_date . ' 23:59:59',
            'end_date_time3' => $end_date . ' 23:59:59',
            'end_date_time4' => $end_date . ' 23:59:59',
            'end_date_time5' => $end_date . ' 23:59:59',
            'end_date_time6' => $end_date . ' 23:59:59',
            'end_date_time7' => $end_date . ' 23:59:59',
            'day' => $day,
            'customer_code1' => $customer_code,
        ]);

        $result = json_decode(json_encode($query), true);
        return $result? round($result[0]['DueAmount'], 2) : 0;
    }

    public function postponed_due_amount2($customer_code, $end_date, $day) {

        $customer_code = $customer_code->toArray();

        $cus_code = "";
        foreach ($customer_code as $key => $code) {
            if ($key === array_key_first($customer_code)) {
                $cus_code .= "(accmast.Code='".$code."' ";
            }
            elseif ($key === array_key_last($customer_code)) {
                $cus_code .= "or accmast.Code='".$code."')";
            }
            else {
                $cus_code .= "or accmast.Code='".$code."' ";
            }
        }

        set_time_limit(2000);

        $query = DB::connection('sqlsrv')->select("select Code, Name, SUM(DueAmount) as DueAmount from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time1),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as DueAmount
,:end_date_time3 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
--and accmast.code='0100973'
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time4),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time6
--and areamast.nodeno=@area
and (accmast.Code like '0%' or accmast.Code like '1%')
and ". $cus_code ."
--and voucherno='210-01021-1300'
) as tbl
--where EmpCode = '10259'
--where Code = :customer_code1
where DATEDIFF(day, VoucherDate, :end_date_time7) > :day
group by Code, Name", [
            'end_date_time1' => $end_date . ' 23:59:59',
            'end_date_time2' => $end_date . ' 23:59:59',
            'end_date_time3' => $end_date . ' 23:59:59',
            'end_date_time4' => $end_date . ' 23:59:59',
            'end_date_time5' => $end_date . ' 23:59:59',
            'end_date_time6' => $end_date . ' 23:59:59',
            'end_date_time7' => $end_date . ' 23:59:59',
            'day' => $day,
        ]);

        $result = json_decode(json_encode($query), true);

        return $result;
    }

}
