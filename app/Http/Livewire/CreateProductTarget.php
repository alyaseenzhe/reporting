<?php

namespace App\Http\Livewire;

use App\Models\ProductTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProductTarget extends Component
{
    public $dept_id = -1;
    public $selected_month;
    public $results = [];
    public $list = [];
    public $current_year_list = [];
    public $target;
    public $diff;
    public $current_target = [];

    protected $rules = [
        'dept_id' => 'required|not_in:-1',
        'selected_month' => 'required',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
    ];

    public function render()
    {

//        $fromDate = Carbon::now();
//        $toDate = Carbon::parse("2021-08-20");
//
//        $months = $fromDate->diffInMonths($toDate, false);
//        dd($months);
        $query = User::where('id', Auth::id())->first();
        $branches = json_decode($query->branches);


        return view('livewire.create-product-target', compact('branches'))
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        $this->reset('target');
        $this->validate();
        $this->emit('show-container');

        // target data from reporting
        $this->current_target = ProductTarget::where('user_id', Auth::id())
            ->where('branch', $this->dept_id)
            ->get();

        $month_stmt = '';
        $this->list = [];
        $this->results = [];

        $years = [];

        $selected_year1 = Carbon::parse($this->selected_month)->subYear();
        $selected_year2 = Carbon::parse($this->selected_month)->subYear()->addMonth(11);


        $start_of_period = $selected_year1->format('Y-m-d');
        $end_of_period = $selected_year2->endOfMonth()->format('Y-m-d');


        $this->list = [];
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('Y');

//            $month = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('n');
//            $year = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('Y');
            $this->list[$year][] = $month;
        }

        $this->current_year_list = [];
        for ($i = 0; $i < 12; $i++) {
//            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
//            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');

            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');
            $this->current_year_list[$year][] = $month;
        }


//        dd($this->current_year_list);

        $month_counter = 1;

        /* Query Statement */
        $month_stmt = "SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Arabic_Name as VendorName, WholeSale FROM (
            SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, WholeSale  FROM (
            SELECT ProductNo";


        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');
                $month_stmt .= ", SUM(case when voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59' then svalue else 0 end) as 'month" . $month_counter . "'";

                $month_counter++;
            }
        }

        $month_stmt .= " FROM (
            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
            and Department = " . $this->dept_id .
            " group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
            and Department = " . $this->dept_id .
            " group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            ORDER BY VendorNo";

//        dd($month_stmt);

        $query = DB::connection('sqlsrv')->select($month_stmt);
//        dd($query);
        $fetch_query = json_decode(json_encode($query), true);

//        dd($fetch_query);
        array_push($this->results, $fetch_query);

//        dd($this->results);

    }

    public function processData()
    {

//        dd($this->target);

//        $hasTargetSavedForUser = ProductTarget::query()
//            ->where('', $request->input('date'))
//            ->where('user_id', $request->input('user_id'))
//            ->exists();
//
//        if ($hasTargetSavedForUser) {
//            return back()->withErrors([
//                'date' => 'Expense already saved for this user on this date'
//            ]);
//        }


        if ($this->target) {
            foreach ($this->target as $product_key => $target) {
                foreach ($target as $monthyear_key => $monthyear) {
                    $str = explode('-', $monthyear_key);
                    $year = $str[0];
                    $month = $str[1];

                    foreach ($monthyear as $target) {
                        if ($target != "") {
                            $fetch = ProductTarget::where('product_id', $product_key)
                                ->where('month', $month)
                                ->where('year', $year)
                                ->where('branch', $this->dept_id)
                                ->where('user_id', Auth::id())
                                ->first();

                            if ($fetch) {
                                $record = ProductTarget::where('product_id', $product_key)
                                    ->where('month', $month)
                                    ->where('year', $year)
                                    ->where('branch', $this->dept_id)
                                    ->where('user_id', Auth::id())
                                    ->update(['target' => $target]);
                            } else {
                                $record = ProductTarget::create([
                                    'product_id' => $product_key,
                                    'month' => $month,
                                    'year' => $year,
                                    'branch' => $this->dept_id,
                                    'user_id' => Auth::id(),
                                    'target' => $target
                                ]);
                            }
                        }
                    }
                }
            }

            $this->emit('msg');
            $this->reset('target');
        }

    }

    public function updatedTarget($value, $key)
    {
//        dd($key);
        $diff_key = "diff." . $key;
        $product_code = explode('.', $key)[0];
        $month = intval(explode('.', $key)[2]);
//        dd($month);

//        dd($product_code);
        $search_key = array_search($product_code, array_column($this->results[0], 'ProductCode'));
//        dd($this->results[0][$search_key]);
//        dd(floatval($value)/floatval($this->results[0][$search_key]['month'.$month])*100);
//        $diff_value = 100;

//        dd($this->results[0][$search_key]['month' . $month]);
//        dd('month' . $month);
        $diff_value = 0;
        if (floatval($this->results[0][$search_key]['month' . $month]) > 0) {
            $diff_value = number_format(floatval($value) / floatval($this->results[0][$search_key]['month' . $month]) * 100, 2);
        }

        $diff_key = str_replace('.', '--', $diff_key);
//        dd($diff_key);

        $this->emit('diff-update', [$diff_key, $diff_value]);
    }
}
