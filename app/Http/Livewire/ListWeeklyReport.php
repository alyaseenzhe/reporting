<?php

namespace App\Http\Livewire;

use App\Mail\WeeklyReport;
use App\Models\AccMast;
use App\Models\FAExtra;
use App\Models\PInvoice;
use App\Models\SInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ListWeeklyReport extends Component
{
    public $area_id = -1;
    public $final_results = [];
//    public $test = [];
    public $customer_purchased = [];
    public $category_qty = [];
    public $category_qty_total = [];

    public $emp_codes = [];
    public $visits = [];
    public $emp_total = [];
    public $start_date;
    public $end_date;
    public $branch_postponed_due_amount_grand_total = 0;

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

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.weekly-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
//        $test = [];
//
//        if (array_key_exists('0444')) {
//            $test["0444"] = array_key_exists('sp0', $test["0444"]) ? $test["0444"]['sp0'] += 115 :  ['sp0' => 511];
//        }
//        else {
//            $test["0444"] = ['sp0' => 5];
//        }
////        array_push($test, $test["0444"]);
//        dd($test);

        return view('livewire.list-weekly-report')
            ->layout('layouts.dashboard');
    }

    public function proccess_report() {

//        $this->categorize(['0100000'], '2023-10-01', '2023-10-02');
//        $this->categorize(['0300264'], '2023-09-30', '2023-10-05');
//        $this->categorize(['0300264'], '2023-09-30', '2023-10-05');
//        $this->cash2(['0300264'], '2023-09-30', '2023-10-05');
//        dd($this->postponed_sales2(['0600008'], '2023-10-21', '2023-10-27'));
//        $this->categorize(['0100550'], '2023-09-30', '2023-10-06');
//        $this->categorize(['0100660'], '2023-09-30', '2023-10-06');
//        $this->postponed_sales2(['0100584'], '2023-09-30', '2023-10-06');
//        $this->postponed_sales2(['0100660'], '2023-09-30', '2023-10-06');

//        $this->postponed_sales2('0100986', '27');
//        $this->categorize_speciality(['0600522'], '2023-10-1', '2023-10-7');

        set_time_limit(2000);
        $customers_code = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
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

//        $this->category_qty_total = $this->categorizeQtyTotal($this->start_date, $this->end_date);

        $customer_details = $this->customer_details();
        $this->emp_codes = $this->emp_codes();
        $this->customer_purchased = $this->customer_purchased($this->start_date, $this->end_date);
//        $oldest_voucher = $this->oldest_voucher('2023-10-12', '10079');

        $this->visits = $this->visits(array_keys($this->emp_codes), $this->start_date, $this->end_date);
        $collected = $this->collected2($customers_code, $this->start_date, $this->end_date);
        $cash = $this->cash2($customers_code, $this->start_date, $this->end_date);
        $postponed_sales = $this->postponed_sales2($customers_code, $this->start_date, $this->end_date);
        $postponed_amount = $this->postponed_amount2($customers_code, $this->end_date);
//        $this->test = $postponed_amount;
//        dd($postponed_amount[array_search("0100563", array_column($this->test, 'Code'))]['DueAmount']);
//        dd(array_search("0100563", array_column($this->test, 'Code')));
//        dd($this->test);
        $postponed_due_amount = $this->postponed_due_amount2($customers_code, $this->end_date, 120);
        $category_amount = $this->categorize($customers_code, $this->start_date, $this->end_date);
        $speciality_amount = $this->categorize_speciality($customers_code, $this->start_date, $this->end_date);
        $this->category_qty = $this->categorizeQty($customers_code, $this->start_date, $this->end_date);
        $this->category_qty_total = $this->categorizeQtyTotal($this->start_date, $this->end_date);
//        dd($this->category_qty);

//        dd($customer_details);
//        $this->branch_postponed_due_amount_grand_total = array_sum(array_column($postponed_due_amount, 'DueAmount'));
        $this->branch_postponed_due_amount_grand_total = 0;

//        dd($branch_postponed_due_amount_grand_total);

//        dd($postponed_due_amount);
//        $category_amount = $this->categorize(["0300316"], $this->start_date, $this->end_date);
//        $category_amount = $this->categorize(["0300184"], $this->start_date, $this->end_date);

//        dd(array_key_exists('bathoor', $category_amount['0300238']));
//        dd($category_amount);

//        dd($customers_code);
//        dd($cash);

        $this->emp_total = [];

        //////////////////// TESTING /////////////////////////
//        $emp_total = [];

//        if ($customer_details) {
//            $emp_code =
//        }

        foreach ($customer_details as $customer) {
            ////////////////// TEST ///////////////

            /// collected
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('collected', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $collected)) {
                        $this->emp_total[$customer->emp_code]['collected'] += $collected[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['collected'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $collected)) {
                        $this->emp_total[$customer->emp_code]['collected'] = $collected[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['collected'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['collected' => array_key_exists($customer->customer_code, $collected) ? $collected[$customer->customer_code] : 0];
            }

            // cash
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('cash', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $cash)) {
                        $this->emp_total[$customer->emp_code]['cash'] += $cash[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['cash'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $cash)) {
                        $this->emp_total[$customer->emp_code]['cash'] = $cash[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['cash'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['cash' => array_key_exists($customer->customer_code, $cash) ? $cash[$customer->customer_code] : 0];
            }

            // postponed_sales
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('postponed_sales', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $postponed_sales)) {
                        $this->emp_total[$customer->emp_code]['postponed_sales'] += $postponed_sales[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_sales'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $postponed_sales)) {
                        $this->emp_total[$customer->emp_code]['postponed_sales'] = $postponed_sales[$customer->customer_code];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_sales'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['postponed_sales' => array_key_exists($customer->customer_code, $postponed_sales) ? $postponed_sales[$customer->customer_code] : 0];
            }

            // postponed_amount
            // good
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('postponed_amount', $this->emp_total[$customer->emp_code])) {
                    $postponed_amount_index = array_search($customer->customer_code, array_column($postponed_amount, 'Code'));
                    if ($postponed_amount_index) {
                        $this->emp_total[$customer->emp_code]['postponed_amount'] += $postponed_amount[$postponed_amount_index]['DueAmount'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_amount'] += 0;
                    }
                }
                else {
                    $postponed_amount_index = array_search($customer->customer_code, array_column($postponed_amount, 'Code'));
                    if ($postponed_amount_index) {
                        $this->emp_total[$customer->emp_code]['postponed_amount'] = $postponed_amount[$postponed_amount_index]['DueAmount'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_amount'] = 0;

                    }
                }
            }
            else {
                $postponed_amount_index = array_search($customer->customer_code, array_column($postponed_amount, 'Code'));
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['postponed_amount' => $postponed_amount_index ? $postponed_amount[$postponed_amount_index]['DueAmount'] : 0];
            }

            // end of good



            // postponed_due_amount

            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('postponed_due_amount', $this->emp_total[$customer->emp_code])) {
                    $postponed_due_amount_index = array_search($customer->customer_code, array_column($postponed_due_amount, 'Code'));
                    if ($postponed_due_amount_index) {
                        $this->emp_total[$customer->emp_code]['postponed_due_amount'] += $postponed_due_amount[$postponed_due_amount_index]['DueAmount'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_due_amount'] += 0;
                    }
                }
                else {
                    $postponed_due_amount_index = array_search($customer->customer_code, array_column($postponed_due_amount, 'Code'));
                    if ($postponed_due_amount_index) {
                        $this->emp_total[$customer->emp_code]['postponed_due_amount'] = $postponed_due_amount[$postponed_due_amount_index]['DueAmount'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['postponed_due_amount'] = 0;

                    }
                }
            }
            else {
                $postponed_due_amount_index = array_search($customer->customer_code, array_column($postponed_due_amount, 'Code'));
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['postponed_due_amount' => $postponed_due_amount_index ? $postponed_due_amount[$postponed_due_amount_index]['DueAmount'] : 0];
            }

            // bathoor
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('bathoor', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('bathoor', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['bathoor'] += $category_amount[$customer->customer_code]['bathoor'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['bathoor'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('bathoor', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['bathoor'] = $category_amount[$customer->customer_code]['bathoor'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['bathoor'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['bathoor' => array_key_exists($customer->customer_code, $category_amount) && array_key_exists('bathoor', $category_amount[$customer->customer_code]) ? $category_amount[$customer->customer_code]['bathoor'] : 0];
            }

            // mobedat
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('mobedat', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('mobedat', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['mobedat'] += $category_amount[$customer->customer_code]['mobedat'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['mobedat'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('mobedat', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['mobedat'] = $category_amount[$customer->customer_code]['mobedat'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['mobedat'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['mobedat' => array_key_exists($customer->customer_code, $category_amount) && array_key_exists('mobedat', $category_amount[$customer->customer_code]) ? $category_amount[$customer->customer_code]['mobedat'] : 0];
            }

            // asmedah
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('asmedah', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('asmedah', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['asmedah'] += $category_amount[$customer->customer_code]['asmedah'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['asmedah'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('asmedah', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['asmedah'] = $category_amount[$customer->customer_code]['asmedah'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['asmedah'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['asmedah' => array_key_exists($customer->customer_code, $category_amount) && array_key_exists('asmedah', $category_amount[$customer->customer_code]) ? $category_amount[$customer->customer_code]['asmedah'] : 0];
            }

            // other
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('other', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('other', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['other'] += $category_amount[$customer->customer_code]['other'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['other'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('other', $category_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['other'] = $category_amount[$customer->customer_code]['other'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['other'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['other' => array_key_exists($customer->customer_code, $category_amount) && array_key_exists('other', $category_amount[$customer->customer_code]) ? $category_amount[$customer->customer_code]['other'] : 0];
            }

            // speciality0
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('speciality0', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality0', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality0'] += $speciality_amount[$customer->customer_code]['speciality0'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality0'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality0', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality0'] = $speciality_amount[$customer->customer_code]['speciality0'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality0'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['speciality0' => array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality0', $speciality_amount[$customer->customer_code]) ? $speciality_amount[$customer->customer_code]['speciality0'] : 0];
            }

            // speciality1
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('speciality1', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality1', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality1'] += $speciality_amount[$customer->customer_code]['speciality1'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality1'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality1', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality1'] = $speciality_amount[$customer->customer_code]['speciality1'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality1'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['speciality1' => array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality1', $speciality_amount[$customer->customer_code]) ? $speciality_amount[$customer->customer_code]['speciality1'] : 0];
            }

            // speciality2
            if (array_key_exists($customer->emp_code, $this->emp_total)) {
                if (array_key_exists('speciality2', $this->emp_total[$customer->emp_code])) {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality2', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality2'] += $speciality_amount[$customer->customer_code]['speciality2'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality2'] += 0;
                    }
                }
                else {
                    if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality2', $speciality_amount[$customer->customer_code])) {
                        $this->emp_total[$customer->emp_code]['speciality2'] = $speciality_amount[$customer->customer_code]['speciality2'];
                    }
                    else {
                        $this->emp_total[$customer->emp_code]['speciality2'] = 0;

                    }
                }
            }
            else {
                $this->emp_total[$customer->emp_code] = ['emp_name' => $customer->emp_name];
                $this->emp_total[$customer->emp_code] = ['speciality2' => array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality2', $speciality_amount[$customer->customer_code]) ? $speciality_amount[$customer->customer_code]['speciality2'] : 0];
            }


//            dd($this->emp_total);
            //////////////////////////////// good old
//            $record = [];
//
//            $record['customer_code'] = $customer->customer_code;
//            $record['customer_name'] = $customer->customer_name;
//            $record['emp_code'] = $customer->emp_code;
//            $record['emp_name'] = $customer->emp_name;
//
//            $collected_index = array_key_exists($customer->customer_code, $collected);
//            if ($collected_index) {
//                $record['collected'] = $collected[$customer->customer_code];
//            }
//            else {
//                $record['collected'] = 0;
//            }
//
//            $cash_index = array_key_exists($customer->customer_code, $cash);
//            if ($cash_index) {
//                $record['cash'] = $cash[$customer->customer_code];
//            }
//            else {
//                $record['cash'] = 0;
//            }
//
//            $postponed_sales_index = array_key_exists($customer->customer_code, $postponed_sales);
//
//            if ($postponed_sales_index) {
//                $record['postponed_sales'] = $postponed_sales[$customer->customer_code];
//            }
//            else {
//                $record['postponed_sales'] = 0;
//            }
//
//            $postponed_amount_index = array_search($customer->customer_code, array_column($postponed_amount, 'Code'));
//            if ($postponed_amount_index) {
//                $record['postponed_amount'] = $postponed_amount[$postponed_amount_index]['DueAmount'];
//            }
//            else {
//                $record['postponed_amount'] = 0;
//            }
//
//            $postponed_due_amount_index = array_search($customer->customer_code, array_column($postponed_due_amount, 'Code'));
//
//            if ($postponed_due_amount_index) {
//                $record['postponed_due_amount'] = $postponed_due_amount[$postponed_due_amount_index]['DueAmount'];
//            }
//            else {
//                $record['postponed_due_amount'] = 0;
//            }
//
//            ////  category
//            if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('bathoor', $category_amount[$customer->customer_code])) {
//                $record['bathoor'] = $category_amount[$customer->customer_code]['bathoor'];
//            }
//            else {
//                $record['bathoor'] = 0;
//            }
//
//            if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('mobedat', $category_amount[$customer->customer_code])) {
//                $record['mobedat'] = $category_amount[$customer->customer_code]['mobedat'];
//            }
//            else {
//                $record['mobedat'] = 0;
//            }
//
//            if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('asmedah', $category_amount[$customer->customer_code])) {
//                $record['asmedah'] = $category_amount[$customer->customer_code]['asmedah'];
//            }
//            else {
//                $record['asmedah'] = 0;
//            }
//
//            if (array_key_exists($customer->customer_code, $category_amount) && array_key_exists('other', $category_amount[$customer->customer_code])) {
//                $record['other'] = $category_amount[$customer->customer_code]['other'];
//            }
//            else {
//                $record['other'] = 0;
//            }
//
//            ////  speciality
//
//            if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality0', $speciality_amount[$customer->customer_code])) {
//                $record['speciality0'] = $speciality_amount[$customer->customer_code]['speciality0'];
//            }
//            else {
//                $record['speciality0'] = 0;
//            }
//
//            if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality1', $speciality_amount[$customer->customer_code])) {
//                $record['speciality1'] = $speciality_amount[$customer->customer_code]['speciality1'];
//            }
//            else {
//                $record['speciality1'] = 0;
//            }
//
//            if (array_key_exists($customer->customer_code, $speciality_amount) && array_key_exists('speciality2', $speciality_amount[$customer->customer_code])) {
//                $record['speciality2'] = $speciality_amount[$customer->customer_code]['speciality2'];
//            }
//            else {
//                $record['speciality2'] = 0;
//            }
//
//
//            array_push($this->final_results, $record);
        }

//        return $this->final_results;
//        dd($this->emp_total);
//        foreach ($this->emp_total as $post) {
//            $this->branch_postponed_due_amount_grand_total += floatval($post['postponed_amount']);
//        }

//        dd($this->emp_total);
        return $this->emp_total;
    }

    public function sendReport() {

        $ahsa_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'amir.saleh@alyaseenagri.com', 'sales.ahsa@alyaseenagri.com', 'gamil.mohamed@alyaseenagri.com', 'ahmed.wahd@alyaseenagri.com'];
        $jeddah_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'ibrahim.talat@alyaseenagri.com', 'walid.nigm@alyaseenagri.com', 'sales.jeddah@alyaseenagri.com'];
        $riyadh_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mohammed.samy@alyaseenagri.com', 'mohamed.elseify@alyaseenagri.com', 'mohammed.fawzy@alyaseenagri.com', 'sales.riyadh@alyaseenagri.com'];
        $wadi_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mohammed.salem@alyaseenagri.com', 'omar.elhelefy@alyaseenagri.com', 'sales.wadi@alyaseenagri.com', 'ahmed.ibrahim@alyaseenagri.com'];
        $jouf_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'alsaid.saad@alyaseenagri.com', 'abdelwahab.hegazy@alyaseenagri.com', 'sales.aljouf@alyaseenagri.com'];
        $dammam_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'sales.dammam@alyaseenagri.com', 'atef.ibrahem@alyaseenagri.com'];
        $kharaj_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'amir.fawzy@alyaseenagri.com', 'radwan.hussen@alyaseenagri.com', 'sales.alkharj@alyaseenagri.com', 'ahmed.nassef@alyaseenagri.com'];
        $najran_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'wael.badr@alyaseenagri.com', 'omar.mohammed@alyaseenagri.com', 'sales.najran@alyaseenagri.com'];
        $hail_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'ahmed.elzekely@alyaseenagri.com', 'mohammed.majdi@alyaseenagri.com'];
        $tabouk_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mosaad.dahshan@alyaseenagri.com', 'mahmoud.hashem@alyaseenagri.com', 'ahmed.khaled@alyaseenagri.com', 'sales.tabuk@alyaseenagri.com'];
        $qaseem_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'yasser.salah@alyaseenagri.com', 'sales.qaseem@alyaseenagri.com', 'hisham.najeh@alyaseenagri.com'];
        $sajer_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'sales.sajer@alyaseenagri.com', 'abdulaziz.sharqawi@alyaseenagri.com'];

//        Mail::to(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));

        if ($this->area_id == '01') {
            Mail::to($ahsa_branch)->cc(['mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '02') {
            Mail::to($jeddah_branch)->cc(['mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '03') {
            Mail::to($riyadh_branch)->cc(['mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '04') {
            Mail::to($wadi_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '05') {
            Mail::to($jouf_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '06') {
            Mail::to($dammam_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '07') {
            Mail::to($kharaj_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '08') {
            Mail::to($najran_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '09') {
            Mail::to($hail_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '10') {
            Mail::to($tabouk_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '11') {
            Mail::to($qaseem_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }
        elseif ($this->area_id == '12') {
            Mail::to($sajer_branch)->cc(['bader.albladi@alyaseenagri.com', 'mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
        }

        return 0;
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
            ->orderBy('StudentMast.Code')
            ->get();

        return $records;
    }
    public function customer_purchased($start_date, $end_date) {

        set_time_limit(2000);

        $start_date = date($start_date . ' 00:00:00');
        $end_date = $end_date . ' 23:59:59';

        $records = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->join('SInvoice', 'SInvoice.PartyNo', 'accmast.NodeNo')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
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
            ->where('SInvoice.SInvoiceNo', 'like',  '210-%')
            ->where('SInvoice.SIDate', '>=', $start_date)
            ->where('SInvoice.SIDate', '<=', $end_date)
            ->whereNotIn('accmast.Code' , ['0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000'])
//            ->select('accmast.NodeNo as customer_nodeno', 'accmast.Code as customer_code', 'accmast.Arabic_Name as customer_name', 'StudentMast.Code as emp_code','StudentMast.Arabic_Name as emp_name', 'SInvoice.SInvoiceNo', 'SInvoice.PartyNo', 'SInvoice.SIDate')
            ->selectRaw('StudentMast.Code as emp_code, COUNT(DISTINCT(accmast.Code)) as num')
            ->groupBy('StudentMast.Code')
            ->orderBy('StudentMast.Code')
            ->pluck('num', 'emp_code')
            ->toArray();

        $general_records = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->join('SInvoice', 'SInvoice.PartyNo', 'accmast.NodeNo')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
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
            ->where('SInvoice.SInvoiceNo', 'like',  '210-%')
            ->where('SInvoice.SIDate', '>=', $start_date)
            ->where('SInvoice.SIDate', '<=', $end_date)
            ->whereIn('accmast.Code' , ['0000000', '0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000'])
//            ->select('accmast.NodeNo as customer_nodeno', 'accmast.Code as customer_code', 'accmast.Arabic_Name as customer_name', 'StudentMast.Code as emp_code','StudentMast.Arabic_Name as emp_name', 'SInvoice.SInvoiceNo', 'SInvoice.PartyNo', 'SInvoice.SIDate')
            ->selectRaw('StudentMast.Code as emp_code, COUNT(accmast.Code) as num')
            ->groupBy('StudentMast.Code')
            ->orderBy('StudentMast.Code')
            ->pluck('num', 'emp_code')
//            ->first();
            ->toArray();

//        dd(array_values($general_records)[0]); // 168
//        dd(array_key_first($general_records));
        if (count($general_records) > 0) {
            if (array_key_exists(array_key_first($general_records), $records)) {
                $records[array_key_first($general_records)] += intval(array_values($general_records)[0]);
            }
            else {
                $records[array_key_first($general_records)] = intval(array_values($general_records)[0]);
            }
        }

//        return $general_records;
        return $records;
    }

    public function emp_codes() {

        set_time_limit(2000);
        $records = AccMast::join('WarrentyInfo', 'accmast.NodeNo','WarrentyInfo.AccountNo')
            ->join('StudentMast', 'StudentMast.NodeNo', 'WarrentyInfo.SalesEmployee')
            ->where('WarrentyInfo.AccountStatus', 'عملاء نشيطين لدى الفرع')
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
            ->select('StudentMast.Code as emp_code','StudentMast.Arabic_Name as emp_name')
            ->orderBy('StudentMast.Code')
            ->distinct()
            ->pluck('emp_name', 'emp_code');

        return $records->toArray();
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
        $end_date = $end_date . ' 23:59:25';

//        dd($end_date);

        $collected_vouchers = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
            ->join('accmast', 'FAExtra.AccountNo', 'accmast.NodeNo')
            ->whereIn('accmast.Code', $customer_code)
            ->where(function ($query) {
                $query->orWhere('FAExtra.VoucherNo', 'like',  '030%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '040%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '050%');
            })
//            ->whereBetween('FAExtra.VoucherDate', [$start_date, $end_date])
            ->where('FAExtra.VoucherDate', '>=', $start_date)
            ->where('FAExtra.VoucherDate', '<=', $end_date)

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
        $end_date = $end_date . ' 23:59:25';

        $pinvoice_query = PInvoice::join('PaymentMethodDetails', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->where('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethodDetails', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
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
        $end_date = $end_date . ' 23:59:25';

        $pinvoice_query = PInvoice::join('PaymentMethodDetails', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->where(function ($query) {
//                $query->where('PaymentMethodDetails.VoucherNo', 'not like', '212-%')
                $query->where('PaymentMethodDetails.VoucherNo', 'not like', '310-%')
                    ->where('PaymentMethodDetails.VoucherNo', 'not like', '320-%');

            })
            ->whereIn('code', $customer_code)
            ->where('voucherdate' ,'>=', $start_date)
            ->where('voucherdate' ,'<=', $end_date)
            ->selectRaw('distinct code,name, PaymentMethodDetails.VoucherNo, PaymentMethodDetails.AccountNo, PaymentMethodDetails.Value, PaymentMethodDetails.Type, PaymentMethodDetails.VoucherDate, PaymentMethodDetails.ActualVoucherPrefix, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethodDetails.value', 'PaymentMethodDetails.type', 'voucherdate','PaymentMethodDetails.ActualVoucherPrefix');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethodDetails', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->where(function ($query) {
//                $query->where('PaymentMethodDetails.VoucherNo', 'not like', '212-%')
                $query->where('PaymentMethodDetails.VoucherNo', 'not like', '310-%')
                    ->where('PaymentMethodDetails.VoucherNo', 'not like', '320-%');

            })
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
            return $item->sum('Value')/(1+0.15);
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
        $end_date = $end_date . ' 23:59:25';

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
        $end_date = $end_date . ' 23:59:25';

        $pinvoice_query = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->where('PaymentMethod.VoucherNo', 'not like', '212-%')
            ->whereIn('code', $customer_code)
            ->where('PIDate' ,'>=', $start_date)
            ->where('PIDate' ,'<=', $end_date)
            ->selectRaw("distinct code,name,voucherno, CASE WHEN voucherno like '212-%' or voucherno like '211-%' THEN -PaymentMethod.Credit ELSE PaymentMethod.Credit END as Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue")
            ->groupBy('code','name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
//            ->get();

        $sinvoice_query = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->whereIn('accmast.type', [9, 10])
            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->where('PaymentMethod.VoucherNo', 'not like', '212-%')
            ->whereIn('code', $customer_code)
            ->where('SIDate' ,'>=', $start_date)
            ->where('SIDate' ,'<=', $end_date)
            ->selectRaw("distinct code,name,voucherno, CASE WHEN voucherno like '212-%' or voucherno like '211-%' THEN -PaymentMethod.Credit ELSE PaymentMethod.Credit END as Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue")
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
//            dd($item);
            return $item->sum('Credit')/(1+0.15);
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
            'end_date_time1' => $end_date . ' 23:59:25',
            'end_date_time2' => $end_date . ' 23:59:25',
            'end_date_time3' => $end_date . ' 23:59:25',
            'end_date_time4' => $end_date . ' 23:59:25',
            'end_date_time5' => $end_date . ' 23:59:25',
            'end_date_time6' => $end_date . ' 23:59:25',
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
and accmast.[Type] in (9, 10)
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
group by Code, Name having SUM(DueAmount) > 0", [
            'end_date_time1' => $end_date . ' 23:59:25',
            'end_date_time2' => $end_date . ' 23:59:25',
            'end_date_time3' => $end_date . ' 23:59:25',
            'end_date_time4' => $end_date . ' 23:59:25',
            'end_date_time5' => $end_date . ' 23:59:25',
            'end_date_time6' => $end_date . ' 23:59:25',
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
and accmast.[Type] in (9, 10)
and voucherdate <=:end_date_time6
--and areamast.nodeno=@area
and (accmast.Code like '0%' or accmast.Code like '1%')
--and voucherno='210-01021-1300'
) as tbl
--where EmpCode = '10259'
where Code = :customer_code1
and DATEDIFF(day, VoucherDate, :end_date_time7) > :day
group by Code, Name", [
            'end_date_time1' => $end_date . ' 23:59:25',
            'end_date_time2' => $end_date . ' 23:59:25',
            'end_date_time3' => $end_date . ' 23:59:25',
            'end_date_time4' => $end_date . ' 23:59:25',
            'end_date_time5' => $end_date . ' 23:59:25',
            'end_date_time6' => $end_date . ' 23:59:25',
            'end_date_time7' => $end_date . ' 23:59:25',
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
and accmast.[Type] in (9, 10)
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
            'end_date_time1' => $end_date . ' 23:59:25',
            'end_date_time2' => $end_date . ' 23:59:25',
            'end_date_time3' => $end_date . ' 23:59:25',
            'end_date_time4' => $end_date . ' 23:59:25',
            'end_date_time5' => $end_date . ' 23:59:25',
            'end_date_time6' => $end_date . ' 23:59:25',
            'end_date_time7' => $end_date . ' 23:59:25',
            'day' => $day,
        ]);

        $result = json_decode(json_encode($query), true);

//        dd($result);

        return $result;
    }

    public function categorize($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:25';

        $bathoor = 0;
        $mobedat = 0;
        $asmedah = 0;
        $other = 0;

        ///////// cash //////////////

        // bathoor
        $pinvoice_query_bathoor = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
            $query->orWhere('ProductMast.code', 'like',  '20%')
                ->orWhere('ProductMast.code', 'like',  '21%')
                ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_bathoor = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '20%')
                    ->orWhere('ProductMast.code', 'like',  '21%')
                    ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_bathoor)
            ->get();




        // mobedat
        $pinvoice_query_mobedat = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_mobedat = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_mobedat)
            ->get();

//        dd($sinvoice_query_mobedat);

        // asmedah
        $pinvoice_query_asmedah = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            //->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_asmedah = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_asmedah)
            ->get();

//        dd($sinvoice_query_asmedah);


        // other
        $pinvoice_query_other = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_other = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_other)
            ->get();

//        dd($sinvoice_query_other);

        $results_bathoor = collect($sinvoice_query_bathoor)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $results_mobedat = collect($sinvoice_query_mobedat)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $results_asmedah = collect($sinvoice_query_asmedah)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $results_other = collect($sinvoice_query_other)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $result_cat = [];
        foreach ($results_bathoor->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['bathoor'] = $value;
            }
            else {
                $result_cat[$key] = ['bathoor' => $value];
            }
        }

        foreach ($results_mobedat->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['mobedat'] = $value;
            }
            else {
                $result_cat[$key] = ['mobedat' => $value];
            }
        }

        foreach ($results_asmedah->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['asmedah'] = $value;
            }
            else {
                $result_cat[$key] = ['asmedah' => $value];
            }
        }

        foreach ($results_other->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['other'] = $value;
            }
            else {
                $result_cat[$key] = ['other' => $value];
            }
        }

//        dd($result_cat);
////        dd($results_bathoor->toArray());
//
//        $bathoor += $results_bathoor->sum();
//        $mobedat += $results_mobedat->sum();
//        $asmedah += $results_asmedah->sum();
//        $other += $results_other->sum();




        // postponed_sales

        // bathoor
//        $pinvoice_query_p_bathoor = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_bathoor = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_bathoor) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////            dd($sinvoice_query_p_bathoor);
//
//        // mobedat
//        $pinvoice_query_p_mobedat = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_mobedat = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_mobedat) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
//        dd($sinvoice_query_p_mobedat);
//
//        // asmedah
//        $pinvoice_query_p_asmedah = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_asmedah = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_asmedah) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_asmedah);
//
//
//        // other
//        $pinvoice_query_p_other = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_other = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_other) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_other);
//
//
////        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
////            return $item->sum('Credit');
////        });
//
//        $results_p_bathoor = collect($sinvoice_query_p_bathoor)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//
//        $results_p_mobedat = collect($sinvoice_query_p_mobedat)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_asmedah = collect($sinvoice_query_p_asmedah)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_other = collect($sinvoice_query_p_other)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
////        dd($results_p_other);
//
////        dd($sinvoice_query_p_asmedah);
////        dd($results_other);
////        dd($results_p_asmedah);
////        dd($results_p_mobedat);
////        dd($results_p_bathoor);
//
//        foreach ($results_p_bathoor->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//            if ($customer_index && array_key_exists('bathoor', $result_cat[$key])) {
//                $result_cat[$key]['bathoor'] += abs($value);
////                dd('here');
//            }
//            else {
//                $result_cat[$key]['bathoor'] = abs($value); //['bathoor' => abs($value)];
////                dd($key);
////                dd($result_cat[$key]);
////                dd('there');
//            }
//        }
//
//
//        foreach ($results_p_mobedat->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('mobedat', $result_cat[$key])) {
//                $result_cat[$key]['mobedat'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['mobedat'] = abs($value);//['mobedat' => abs($value)];
//            }
//        }
//
////        dd($result_cat);
//
//        foreach ($results_p_asmedah->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('asmedah', $result_cat[$key])) {
//                $result_cat[$key]['asmedah'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['asmedah'] = abs($value); // ['asmedah' => abs($value)];
//            }
//        }
//
//        foreach ($results_p_other->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('other', $result_cat[$key])) {
//                $result_cat[$key]['other'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['other'] = abs($value); //['other' => abs($value)];
//            }
//        }
//
//        $bathoor += $results_p_bathoor->sum();
//        $mobedat += $results_p_mobedat->sum();
//        $asmedah += $results_p_asmedah->sum();
//        $other += $results_p_other->sum();

//        dd(['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other]);
//
//        dd($result_cat);
//        return ['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other];

        return $result_cat;

//        return $results_bathoor->toArray();

    }
    public function categorizeQty($customer_code, $start_date, $end_date) {

//        $customer_code = ["0100590", "0100961"];
        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:25';

        $real_area = '3';
        $a = $this->area_id;
        if ($a == '01') { // hasa
            $real_area = '3';
        }
        elseif ($a == '02') { // jeddah
            $real_area = '10';
        }
        elseif ($a == '03') { // riyadh
            $real_area = '7';
        }
        elseif ($a == '04') { // wadi
            $real_area = '13';
        }
        elseif ($a == '05') { // jouf
            $real_area = '4';
        }
        elseif ($a == '06') { // dammam
            $real_area = '6';
        }
        elseif ($a == '07') { // Kharaj --
            $real_area = '5';
        }
        elseif ($a == '08') { // Najran
            $real_area = '12';
        }
        elseif ($a == '09') { // Hail
            $real_area = '11';
        }
        elseif ($a == '10') { // tabouk
            $real_area = '9';
        }
        elseif ($a == '11') { // qassim
            $real_area = '8';
        }
        elseif ($a == '12') { // sajer
            $real_area = '505';
        }

        $bathoor = 0;
        $mobedat = 0;
        $asmedah = 0;
        $other = 0;

        ///////// cash //////////////

        // bathoor
        $pinvoice_query_bathoor = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '20%')
                    ->orWhere('ProductMast.code', 'like',  '21%')
                    ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, -COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code');

        $sinvoice_query_bathoor = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '20%')
                    ->orWhere('ProductMast.code', 'like',  '21%')
                    ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code')
//            ->unionAll($pinvoice_query_bathoor)
            ->get();

//        dd($sinvoice_query_bathoor);

        // mobedat
        $pinvoice_query_mobedat = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, -COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code');

        $sinvoice_query_mobedat = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, COUNT(ProductNo) as product_num')
            ->selectRaw('distinct StudentMast.Code as emp_code, COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_mobedat)
            ->get();

//        dd($sinvoice_query_mobedat);

        // asmedah
        $pinvoice_query_asmedah = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            //->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, -COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code');

        $sinvoice_query_asmedah = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_asmedah)
            ->get();

//        dd($sinvoice_query_asmedah);


        // other
        $pinvoice_query_other = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, -COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code');

        $sinvoice_query_other = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, COUNT(DISTINCT ProductNo) as product_num')
            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_other)
            ->get();

//        dd($sinvoice_query_other);

        $results_bathoor = collect($sinvoice_query_bathoor)->groupBy('emp_code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//            return floatval($item->sum('product_num'));
            return floatval($item->sum('product_num'));
        });

        $results_mobedat = collect($sinvoice_query_mobedat)->groupBy('emp_code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//            return floatval($item->sum('product_num'));
            return floatval($item->sum('product_num'));
        });

        $results_asmedah = collect($sinvoice_query_asmedah)->groupBy('emp_code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//            return floatval($item->sum('product_num'));
            return floatval($item->sum('product_num'));
        });

        $results_other = collect($sinvoice_query_other)->groupBy('emp_code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('product_num'));
        });

//        dd($sinvoice_query_asmedah);

        $result_cat = [];
        foreach ($results_bathoor->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['bathoor'] = $value;
            }
            else {
                $result_cat[$key] = ['bathoor' => $value];
            }
        }

        foreach ($results_mobedat->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['mobedat'] = $value;
            }
            else {
                $result_cat[$key] = ['mobedat' => $value];
            }
        }

        foreach ($results_asmedah->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['asmedah'] = $value;
            }
            else {
                $result_cat[$key] = ['asmedah' => $value];
            }
        }

        foreach ($results_other->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['other'] = $value;
            }
            else {
                $result_cat[$key] = ['other' => $value];
            }
        }

//        dd($result_cat);
////        dd($results_bathoor->toArray());
//
//        $bathoor += $results_bathoor->sum();
//        $mobedat += $results_mobedat->sum();
//        $asmedah += $results_asmedah->sum();
//        $other += $results_other->sum();




        // postponed_sales

        // bathoor
//        $pinvoice_query_p_bathoor = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_bathoor = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_bathoor) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////            dd($sinvoice_query_p_bathoor);
//
//        // mobedat
//        $pinvoice_query_p_mobedat = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_mobedat = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_mobedat) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
//        dd($sinvoice_query_p_mobedat);
//
//        // asmedah
//        $pinvoice_query_p_asmedah = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_asmedah = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_asmedah) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_asmedah);
//
//
//        // other
//        $pinvoice_query_p_other = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_other = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_other) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_other);
//
//
////        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
////            return $item->sum('Credit');
////        });
//
//        $results_p_bathoor = collect($sinvoice_query_p_bathoor)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//
//        $results_p_mobedat = collect($sinvoice_query_p_mobedat)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_asmedah = collect($sinvoice_query_p_asmedah)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_other = collect($sinvoice_query_p_other)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
////        dd($results_p_other);
//
////        dd($sinvoice_query_p_asmedah);
////        dd($results_other);
////        dd($results_p_asmedah);
////        dd($results_p_mobedat);
////        dd($results_p_bathoor);
//
//        foreach ($results_p_bathoor->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//            if ($customer_index && array_key_exists('bathoor', $result_cat[$key])) {
//                $result_cat[$key]['bathoor'] += abs($value);
////                dd('here');
//            }
//            else {
//                $result_cat[$key]['bathoor'] = abs($value); //['bathoor' => abs($value)];
////                dd($key);
////                dd($result_cat[$key]);
////                dd('there');
//            }
//        }
//
//
//        foreach ($results_p_mobedat->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('mobedat', $result_cat[$key])) {
//                $result_cat[$key]['mobedat'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['mobedat'] = abs($value);//['mobedat' => abs($value)];
//            }
//        }
//
////        dd($result_cat);
//
//        foreach ($results_p_asmedah->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('asmedah', $result_cat[$key])) {
//                $result_cat[$key]['asmedah'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['asmedah'] = abs($value); // ['asmedah' => abs($value)];
//            }
//        }
//
//        foreach ($results_p_other->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('other', $result_cat[$key])) {
//                $result_cat[$key]['other'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['other'] = abs($value); //['other' => abs($value)];
//            }
//        }
//
//        $bathoor += $results_p_bathoor->sum();
//        $mobedat += $results_p_mobedat->sum();
//        $asmedah += $results_p_asmedah->sum();
//        $other += $results_p_other->sum();

//        dd(['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other]);
//
//        dd($result_cat);
//        return ['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other];

        return $result_cat;

//        return $results_bathoor->toArray();

    }

    public function categorizeQtyTotal($start_date, $end_date) {

//        $customer_code = ["0100590", "0100961"];
        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:25';

        $real_area = '3';
        $a = $this->area_id;
        if ($a == '01') { // hasa
            $real_area = '3';
        }
        elseif ($a == '02') { // jeddah
            $real_area = '10';
        }
        elseif ($a == '03') { // riyadh
            $real_area = '7';
        }
        elseif ($a == '04') { // wadi
            $real_area = '13';
        }
        elseif ($a == '05') { // jouf
            $real_area = '4';
        }
        elseif ($a == '06') { // dammam
            $real_area = '6';
        }
        elseif ($a == '07') { // Kharaj --
            $real_area = '5';
        }
        elseif ($a == '08') { // Najran
            $real_area = '12';
        }
        elseif ($a == '09') { // Hail
            $real_area = '11';
        }
        elseif ($a == '10') { // tabouk
            $real_area = '9';
        }
        elseif ($a == '11') { // qassim
            $real_area = '8';
        }
        elseif ($a == '12') { // sajer
            $real_area = '505';
        }

        $bathoor = 0;
        $mobedat = 0;
        $asmedah = 0;
        $other = 0;
        $all_asmedah_products = [];
        $all_bathoor_products = [];
        $all_mobedat_products = [];
        $all_other_products = [];

        ///////// cash //////////////

        // bathoor
        $pinvoice_query_bathoor = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '20%')
                    ->orWhere('ProductMast.code', 'like',  '21%')
                    ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, ProductNo');
//            ->groupBy('StudentMast.Code');

        $sinvoice_query_bathoor = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '20%')
                    ->orWhere('ProductMast.code', 'like',  '21%')
                    ->orWhere('ProductMast.code', 'like',  '22%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code,ProductNo')
//            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_bathoor)
            ->pluck('ProductNo')->toArray();
//            ->get();

//        dd($sinvoice_query_bathoor);

        // mobedat
        $pinvoice_query_mobedat = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, ProductNo');
//            ->groupBy('StudentMast.Code');

        $sinvoice_query_mobedat = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '10%')
                    ->orWhere('ProductMast.code', 'like',  '11%')
                    ->orWhere('ProductMast.code', 'like',  '12%')
                    ->orWhere('ProductMast.code', 'like',  '13%')
                    ->orWhere('ProductMast.code', 'like',  '14%')
                    ->orWhere('ProductMast.code', 'like',  '15%')
                    ->orWhere('ProductMast.code', 'like',  '16%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, COUNT(ProductNo) as product_num')
            ->selectRaw('distinct StudentMast.Code as emp_code,ProductNo')
//            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_mobedat)
            ->pluck('ProductNo')->toArray();

//        dd($sinvoice_query_mobedat);

        // asmedah
        $pinvoice_query_asmedah = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            //->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, ProductNo');
//            ->groupBy('StudentMast.Code');

        $sinvoice_query_asmedah = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->orWhere('ProductMast.code', 'like',  '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code,ProductNo')
//            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_asmedah)
            ->pluck('ProductNo')->toArray();

//        dd($sinvoice_query_asmedah);


        // other
        $pinvoice_query_other = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'PInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code, ProductNo');
//            ->groupBy('StudentMast.Code');

        $sinvoice_query_other = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->join('StudentMast', 'SInvoice.Student', 'StudentMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('accmast.Accmast_Department', $real_area)
            ->where(function ($query) {
                $query->where('ProductMast.code', 'not like', '20%')
                    ->where('ProductMast.code', 'not like', '21%')
                    ->where('ProductMast.code', 'not like', '22%')
                    ->where('ProductMast.code', 'not like', '10%')
                    ->where('ProductMast.code', 'not like', '11%')
                    ->where('ProductMast.code', 'not like', '12%')
                    ->where('ProductMast.code', 'not like', '13%')
                    ->where('ProductMast.code', 'not like', '14%')
                    ->where('ProductMast.code', 'not like', '15%')
                    ->where('ProductMast.code', 'not like', '16%')
                    ->where('ProductMast.code', 'not like', '17%');
            })
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
//            ->whereIn('PaymentMethodDetails.type', [1,2,3,4])
//            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->selectRaw('distinct StudentMast.Code as emp_code,ProductNo')
//            ->groupBy('StudentMast.Code')
            ->unionAll($pinvoice_query_other)
            ->pluck('ProductNo')->toArray();
//        dd($sinvoice_query_other);

        array_push($all_bathoor_products, $sinvoice_query_bathoor);
        array_push($all_mobedat_products, $sinvoice_query_mobedat);
        array_push($all_asmedah_products, $sinvoice_query_asmedah);
        array_push($all_other_products, $sinvoice_query_other);

        $all_bathoor_products = array_unique($all_bathoor_products[0]);
        $all_mobedat_products = array_unique($all_mobedat_products[0]);
        $all_asmedah_products = array_unique($all_asmedah_products[0]);
        $all_other_products = array_unique($all_other_products[0]);
//        dd($all_bathoor_products);
//        dd(array_unique($all_bathoor_products[0]));

//        dd($flattened_array);


//        dd($sinvoice_query_other);

//        $results_bathoor = collect($sinvoice_query_bathoor)->groupBy('emp_code')->map(function ($item) {
////            return floatval($item->sum('svalue'))*1.15;
////            return floatval($item->sum('product_num'));
//            return floatval($item->sum('product_num'));
//        });
//
//        $results_mobedat = collect($sinvoice_query_mobedat)->groupBy('emp_code')->map(function ($item) {
////            return floatval($item->sum('svalue'))*1.15;
////            return floatval($item->sum('product_num'));
//            return floatval($item->sum('product_num'));
//        });
//
//        $results_asmedah = collect($sinvoice_query_asmedah)->groupBy('emp_code')->map(function ($item) {
////            return floatval($item->sum('svalue'))*1.15;
////            return floatval($item->sum('product_num'));
//            return floatval($item->sum('product_num'));
//        });
//
//        $results_other = collect($sinvoice_query_other)->groupBy('emp_code')->map(function ($item) {
////            return floatval($item->sum('svalue'))*1.15;
//            return floatval($item->sum('product_num'));
//        });
//
////        dd($sinvoice_query_asmedah);
//
//        $result_cat = [];
//        foreach ($results_bathoor->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index) {
//                $result_cat[$key]['bathoor'] = $value;
//            }
//            else {
//                $result_cat[$key] = ['bathoor' => $value];
//            }
//        }
//
//        foreach ($results_mobedat->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index) {
//                $result_cat[$key]['mobedat'] = $value;
//            }
//            else {
//                $result_cat[$key] = ['mobedat' => $value];
//            }
//        }
//
//        foreach ($results_asmedah->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index) {
//                $result_cat[$key]['asmedah'] = $value;
//            }
//            else {
//                $result_cat[$key] = ['asmedah' => $value];
//            }
//        }
//
//        foreach ($results_other->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index) {
//                $result_cat[$key]['other'] = $value;
//            }
//            else {
//                $result_cat[$key] = ['other' => $value];
//            }
//        }

//        dd($result_cat);
////        dd($results_bathoor->toArray());
//
//        $bathoor += $results_bathoor->sum();
//        $mobedat += $results_mobedat->sum();
//        $asmedah += $results_asmedah->sum();
//        $other += $results_other->sum();




        // postponed_sales

        // bathoor
//        $pinvoice_query_p_bathoor = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_bathoor = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '20%')
//                    ->orWhere('ProductMast.code', 'like',  '21%')
//                    ->orWhere('ProductMast.code', 'like',  '22%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_bathoor) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////            dd($sinvoice_query_p_bathoor);
//
//        // mobedat
//        $pinvoice_query_p_mobedat = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_mobedat = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->orWhere('ProductMast.code', 'like',  '10%')
//                    ->orWhere('ProductMast.code', 'like',  '11%')
//                    ->orWhere('ProductMast.code', 'like',  '12%')
//                    ->orWhere('ProductMast.code', 'like',  '13%')
//                    ->orWhere('ProductMast.code', 'like',  '14%')
//                    ->orWhere('ProductMast.code', 'like',  '15%')
//                    ->orWhere('ProductMast.code', 'like',  '16%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_mobedat) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
//        dd($sinvoice_query_p_mobedat);
//
//        // asmedah
//        $pinvoice_query_p_asmedah = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_asmedah = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where('ProductMast.code', 'like',  '17%')
////            ->where(function ($query) {
////                $query->orWhere('ProductMast.code', 'like',  '10%')
////                    ->orWhere('ProductMast.code', 'like',  '11%')
////                    ->orWhere('ProductMast.code', 'like',  '12%')
////                    ->orWhere('ProductMast.code', 'like',  '13%')
////                    ->orWhere('ProductMast.code', 'like',  '14%')
////                    ->orWhere('ProductMast.code', 'like',  '15%')
////                    ->orWhere('ProductMast.code', 'like',  '16%');
////            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_asmedah) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_asmedah);
//
//
//        // other
//        $pinvoice_query_p_other = PInvoice::join('PaymentMethod', 'pinvoiceno', 'voucherno')
//            ->join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('PIDate' ,'>=', $start_date)
//            ->where('PIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(PInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa');
////            ->get();
//
//        $sinvoice_query_p_other = SInvoice::join('PaymentMethod', 'sinvoiceno', 'voucherno')
//            ->join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
//            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
//            ->where('accmast.type', 10)
//            ->where(function ($query) {
//                $query->where('ProductMast.code', 'not like', '20%')
//                    ->where('ProductMast.code', 'not like', '21%')
//                    ->where('ProductMast.code', 'not like', '22%')
//                    ->where('ProductMast.code', 'not like', '10%')
//                    ->where('ProductMast.code', 'not like', '11%')
//                    ->where('ProductMast.code', 'not like', '12%')
//                    ->where('ProductMast.code', 'not like', '13%')
//                    ->where('ProductMast.code', 'not like', '14%')
//                    ->where('ProductMast.code', 'not like', '15%')
//                    ->where('ProductMast.code', 'not like', '16%')
//                    ->where('ProductMast.code', 'not like', '17%');
//            })
//            ->where('PaymentMethod.Credit', '<>' , 0)
//            ->whereIn('accmast.code', $customer_code)
//            ->where('SIDate' ,'>=', $start_date)
//            ->where('SIDate' ,'<=', $end_date)
//            ->selectRaw('distinct accmast.code,accmast.name, voucherno, accountno, PaymentMethod.Credit, Cash, Visa,-sum(SInvoice.Value*exchangerate+extrafieldstotal) as svalue')
//            ->groupBy('accmast.code','accmast.name', 'voucherno', 'accountno', 'PaymentMethod.Credit', 'PaymentMethod.Cash', 'PaymentMethod.visa')
//            ->unionAll($pinvoice_query_p_other) //
////            ->sum('svalue');
////            ->sum('svalue');
//            ->get();
//
////        dd($sinvoice_query_p_other);
//
//
////        $results = collect($sinvoice_query)->groupBy('code')->map(function ($item) {
////            return $item->sum('Credit');
////        });
//
//        $results_p_bathoor = collect($sinvoice_query_p_bathoor)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//
//        $results_p_mobedat = collect($sinvoice_query_p_mobedat)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_asmedah = collect($sinvoice_query_p_asmedah)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
//
//        $results_p_other = collect($sinvoice_query_p_other)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
//        });
////        dd($results_p_other);
//
////        dd($sinvoice_query_p_asmedah);
////        dd($results_other);
////        dd($results_p_asmedah);
////        dd($results_p_mobedat);
////        dd($results_p_bathoor);
//
//        foreach ($results_p_bathoor->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//            if ($customer_index && array_key_exists('bathoor', $result_cat[$key])) {
//                $result_cat[$key]['bathoor'] += abs($value);
////                dd('here');
//            }
//            else {
//                $result_cat[$key]['bathoor'] = abs($value); //['bathoor' => abs($value)];
////                dd($key);
////                dd($result_cat[$key]);
////                dd('there');
//            }
//        }
//
//
//        foreach ($results_p_mobedat->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('mobedat', $result_cat[$key])) {
//                $result_cat[$key]['mobedat'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['mobedat'] = abs($value);//['mobedat' => abs($value)];
//            }
//        }
//
////        dd($result_cat);
//
//        foreach ($results_p_asmedah->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('asmedah', $result_cat[$key])) {
//                $result_cat[$key]['asmedah'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['asmedah'] = abs($value); // ['asmedah' => abs($value)];
//            }
//        }
//
//        foreach ($results_p_other->toArray() as $key => $value) {
//            $customer_index = array_key_exists($key, $result_cat);
//
//            if ($customer_index && array_key_exists('other', $result_cat[$key])) {
//                $result_cat[$key]['other'] += abs($value);
//            }
//            else {
//                $result_cat[$key]['other'] = abs($value); //['other' => abs($value)];
//            }
//        }
//
//        $bathoor += $results_p_bathoor->sum();
//        $mobedat += $results_p_mobedat->sum();
//        $asmedah += $results_p_asmedah->sum();
//        $other += $results_p_other->sum();

//        dd(['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other]);
//
//        dd($result_cat);
//        return ['bathoor' => $bathoor, 'mobedat' => $mobedat, 'asmedah' => $asmedah, 'other' => $other];

//        dd($all_products);
//        return $result_cat;
//        dd(['bathoor' => $all_bathoor_products, 'asmedah' => $all_asmedah_products, 'mobedat' => $all_mobedat_products, 'other' => $all_other_products]);
        return ['bathoor' => count($all_bathoor_products), 'asmedah' => count($all_asmedah_products), 'mobedat' => count($all_mobedat_products), 'other' => count($all_other_products)];

//        return $results_bathoor->toArray();

    }


    public function categorize_speciality($customer_code, $start_date, $end_date) {

        set_time_limit(2000);
        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:25';

        // speciality 0
        $pinvoice_query_speciality0 = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '0')
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_speciality0 = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '0')
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_speciality0)
            ->get();

        // speciality 1
        $pinvoice_query_speciality1 = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '1')
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_speciality1 = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '1')
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_speciality1)
            ->get();

        // speciality 2
        $pinvoice_query_speciality2 = PInvoice::join('accmast', 'pinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'pinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '2')
            ->where(function ($query) {
                $query->where('PInvoiceNo', 'not like', '310-%')
                    ->where('PInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('pidate' ,'>=', $start_date)
            ->where('pidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, -sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name');

        $sinvoice_query_speciality2 = SInvoice::join('accmast', 'sinvoice.partyno', 'accmast.nodeno')
            ->join('ProductMast', 'sinvoice.ProductNo', 'ProductMast.NodeNo')
            ->whereIn('accmast.type', [9, 10])
            ->where('ProductMast.SpecialityCode', '2')
            ->where(function ($query) {
                $query->where('SInvoiceNo', 'not like', '310-%')
                    ->where('SInvoiceNo', 'not like', '320-%');
            })
            ->whereIn('accmast.code', $customer_code)
            ->where('sidate' ,'>=', $start_date)
            ->where('sidate' ,'<=', $end_date)
            ->selectRaw('distinct accmast.code,accmast.name, sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue')
            ->groupBy('accmast.code','accmast.name')
            ->unionAll($pinvoice_query_speciality2)
            ->get();

        $results_speciality0 = collect($sinvoice_query_speciality0)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $results_speciality1 = collect($sinvoice_query_speciality1)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });

        $results_speciality2 = collect($sinvoice_query_speciality2)->groupBy('code')->map(function ($item) {
//            return floatval($item->sum('svalue'))*1.15;
            return floatval($item->sum('svalue'));
        });


        $result_cat = [];
        foreach ($results_speciality0->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['speciality0'] = $value;
            }
            else {
                $result_cat[$key] = ['speciality0' => $value];
            }
        }

        foreach ($results_speciality1->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['speciality1'] = $value;
            }
            else {
                $result_cat[$key] = ['speciality1' => $value];
            }
        }

        foreach ($results_speciality2->toArray() as $key => $value) {
            $customer_index = array_key_exists($key, $result_cat);

            if ($customer_index) {
                $result_cat[$key]['speciality2'] = $value;
            }
            else {
                $result_cat[$key] = ['speciality2' => $value];
            }
        }

//        dd($result_cat);
        return $result_cat;
    }

    public function visits($emp_codes, $start_date, $end_date) {

        $visits = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('author', $emp_codes)
            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date. ' 23:59:25'])
//            ->where('is_branch_visit', '!=', 'N')
//            ->where('type','a-00')
            ->where('added_using', 'W')
            ->whereNotIn('customer_id', ['0000000', '0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000', '0109999'])
//            ->whereIn('place_id',['0101', '01011', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
            ->whereIn('place_id',['0001', '0020', '0099', '0101', '01011', '0102', '01021', '01023', '0103', '01031', '0104', '0105', '0106', '0107', '0108', '01081', '0109', '0110', '0111', '0112', '0202', '0203'])
            ->select('author', DB::raw('COUNT(DISTINCT customer_id) as num_of_visits'))
            ->groupBy('author')
            ->pluck('num_of_visits', 'author')->toArray();

        $general_visits = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('author', $emp_codes)
            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date. ' 23:59:25'])
//            ->where('is_branch_visit', '!=', 'N')
//            ->where('type','a-00')
            ->where('added_using', 'W')
            ->whereIn('customer_id', ['0000000', '0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000', '0109999'])
            ->whereIn('place_id',['0001', '0020', '0099', '0101', '01011', '0102', '01021', '01023', '0103', '01031', '0104', '0105', '0106', '0107', '0108', '01081', '0109', '0110', '0111', '0112', '0202', '0203'])
//            ->whereIn('place_id',['0101', '01011', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
            ->select('author', DB::raw('COUNT(customer_id) as num_of_visits'))
            ->groupBy('author')
            ->pluck('num_of_visits', 'author')->toArray();

        foreach ($visits as $key => $visit) {
            if (array_key_exists($key, $general_visits)) {
                $visits[$key] += $general_visits[$key];
            }
            else {
                $visits[$key] += 0;
//                $visits[$key] = $general_visits[$key];
//                $visits = [$key => $general_visits[$key]];
            }
        }

        return $visits;
    }

//    public function oldest_voucher($end_date, $emp_code) {
//
//        set_time_limit(2000);
//        $query = DB::connection('sqlsrv')->select("select min(VoucherDate) as postponed_amount from (
//select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
//,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:voucher_date1),0.00) as paid,
//total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:voucher_date2),0.00) as DueAmount
//,:voucher_date3 as cutdate,Area
//from billwise ,accmast,areamast, WarrentyInfo
//where customerno=accmast.nodeno and areamast.nodeno=area
//and WarrentyInfo.AccountNo = accmast.NodeNo
//and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
//and billwise.[type]='N'
//and (total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:voucher_date4),0.00) >=1
//or total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:voucher_date5),0.00) <=-1)
//and accmast.[Type]=10
//and voucherdate <='2023-10-12'
//and (accmast.Code like '0%' or accmast.Code like '1%' or accmast.Code like '1-%')
//) as tbl
//where DATEDIFF(day, VoucherDate, :voucher_date6) > 120
//and EmpCode = :emp_code1", [
//            'voucher_date1' => $end_date . ' 23:59:25',
//            'voucher_date2' => $end_date . ' 23:59:25',
//            'voucher_date3' => $end_date . ' 23:59:25',
//            'voucher_date4' => $end_date . ' 23:59:25',
//            'voucher_date5' => $end_date . ' 23:59:25',
//            'emp_code1' => $emp_code,
//        ]);
//
//        $result = json_decode(json_encode($query), true);
//        dd($result);
////        return $result? round($result[0]['DueAmount'], 2) : 0;
//    }

}
