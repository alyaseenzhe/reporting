<?php

namespace App\Http\Livewire;

use App\Models\ProductTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowMyProductsTargets extends Component
{
    public $dept_id = -1;
    public $selected_month;
    public $show_msg = false;
    public $results = [];
    public $list = [];
    public $current_year_list = [];
    public $target;
    public $diff;
    public $keys = [];
    public $new_targets = [];

    protected $rules = [
        'dept_id' => 'required|not_in:-1',
        'selected_month' => 'required',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
    ];

    public function updatedDeptId($value) {
        $this->reset('show_msg');
    }

    public function render()
    {
        $query = User::where('id', Auth::id())->first();
        $branches = json_decode($query->branches);

        return view('livewire.show-my-products-targets', compact('branches'))
            ->layout('layouts.dashboard');
    }

    public function generateReport() {
        $this->validate();
        $this->emit('show-container');

        $month_stmt = '';
        $this->list = [];
        $this->results = [];
        $arr_new_targets = [];

        $years = [];

//        $selected_year1 = Carbon::parse($this->selected_month)->subYear();
//        $selected_year2 = Carbon::parse($this->selected_month)->subYear()->addMonth(11);

        $selected_year1 = Carbon::parse($this->selected_month);
        $selected_year2 = Carbon::parse($this->selected_month)->addMonth(11);


        $start_of_period = $selected_year1->format('Y-m-d');
        $end_of_period = $selected_year2->endOfMonth()->format('Y-m-d');



        $this->list = [];
        for ($i = 0; $i < 12; $i++) {

            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');

//            $month = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('n');
//            $year = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('Y');

//            $month = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('n');
//            $year = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('Y');
            $this->list[$year][] = $month;
        }

//        dd(array_keys($this->list));
//        dd($this->list);

//        dd(Auth::id());
        $this->keys = array_keys($this->list);
        if (count($this->keys) > 1) {
            $this->new_targets = ProductTarget::where('branch', $this->dept_id)
                ->where('user_id', Auth::id())
                ->where(function ($query) {
                    $query->where('year', $this->keys[0])
                        ->where('user_id', Auth::id())
                        ->where('branch', $this->dept_id)
                        ->whereIn('month', array_values($this->list[$this->keys[0]]));
                })
                ->orWhere(function ($query) {
                    $query->where('year', $this->keys[1])
                        ->where('user_id', Auth::id())
                        ->where('branch', $this->dept_id)
                        ->whereIn('month', array_values($this->list[$this->keys[1]]));
                })->get();
//            dd($this->new_targets);
        }
        else {
            $this->new_targets = ProductTarget::where('branch', $this->dept_id)
                ->where('user_id', Auth::id())
                ->where('year', $this->keys[0])
                ->whereIn('month', array_values($this->list[$this->keys[0]]))
                ->get();
        }

        $arr_new_targets = $this->new_targets->toArray();
        $product_codes = "";
        foreach ($arr_new_targets as $key => $product) {
//            dd($product_code);
            if (count($arr_new_targets) > 1) {
                if ($key === array_key_first($arr_new_targets)) {
                    $product_codes .= "('".$product['product_id']."', ";
                }
                elseif ($key === array_key_last($arr_new_targets)) {
                    $product_codes .= "'".$product['product_id']."')";
                }
                else {
                    $product_codes .= "'".$product['product_id']."', ";
                }
            }
            else {
                $product_codes .= "('".$product['product_id']."')";
            }

        }

//        dd($product_codes);



//        dd(array_keys($this->list));
//        dd($this->list);


//        $this->current_year_list = [];
//        for ($i = 0; $i < 12; $i++) {
////            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
////            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');
//
//            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
//            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');
//            $this->current_year_list[$year][] = $month;
//        }



//        dd($this->current_year_list);

        $month_counter = 1;

        /* Query Statement */
        $month_stmt = "SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount FROM (
            SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, WholeSale, MaxDiscount  FROM (
            SELECT ProductNo";


        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
                $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                $month_counter++;
            }
        }

        $month_stmt .= " FROM (
            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='". $start_of_period."' and  SIDate<='".$end_of_period." 23:59:59'
            and Department = ".$this->dept_id.
            " group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='". $start_of_period ."' and  PIDate<='".$end_of_period." 23:59:59'
            and Department = ".$this->dept_id.
            " group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode in " . $product_codes .
            "ORDER BY VendorNo";

//        dd($month_stmt);

        if (count($arr_new_targets) > 0) {
            $query = DB::connection('sqlsrv')->select($month_stmt);
//        dd($query);
            $fetch_query = json_decode(json_encode($query), true);

//        dd($fetch_query);
            array_push($this->results, $fetch_query);
        }
        $this->show_msg = true;

//        dd($this->results);

    }

    public function updatedTarget($value, $key) {
//        dd($key);
        $diff_key = "diff.".$key;
        $product_code = explode('.', $key)[0];
        $month = intval(explode('.', $key)[2]);

//        dd($product_code);
        $search_key = array_search($product_code, array_column($this->results[0], 'ProductCode'));
//        dd(floatval($value)/floatval($this->results[0][$search_key]['month'.$month])*100);
        $diff_value = 100;
        if (floatval($this->results[0][$search_key]['month'.$month]) > 0 ){
            $diff_value = number_format(floatval($value)/floatval($this->results[0][$search_key]['month'.$month])*100, 2);
        }

        $diff_key = str_replace('.', '--', $diff_key);
//        dd($diff_key);

        $this->emit('diff-update', [$diff_key, $diff_value]);
    }
}
