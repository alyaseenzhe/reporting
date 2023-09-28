<?php

namespace App\Http\Livewire;

use App\Models\ProductTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListMyProductTarget extends Component
{
    public $dept_id = -1;
    public $user_id = -1;
    public $selected_month;
    public $users;
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
        'user_id' => 'required|not_in:-1',
        'selected_month' => 'required',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'user_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
    ];

    public function render()
    {
//        $this->users = User::where('role', 'u')
//            ->where('is_active', 1)
//            ->orderBy('name')
//            ->select('id','name')
//            ->get();

//        dd($users);
        return view('livewire.list-my-product-target')
            ->layout('layouts.dashboard');
    }

    public function updatedDeptId($value) {
        $this->reset(['user_id', 'selected_month', 'show_msg']);

        if ($value == "all") {
            $this->users = User::select('id', 'name')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->distinct()
                ->get();
        }
        else {
            $this->users = User::where('branches', 'LIKE' ,'%"'.$value.'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('id', 'name')
                ->distinct()
                ->get();
        }

//        dd($this->users);
    }

    public function updatedUserId($value) {
        $this->reset(['selected_month', 'show_msg']);
    }

    public function generateReport() {
        $this->validate();
        $this->emit('show-container');


        $month_stmt = '';
        $this->list = [];
        $this->results = [];

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

        $this->keys = array_keys($this->list);
        if (count($this->keys) > 1) {
            if ($this->dept_id == "all") {
                // all department
                if ($this->user_id == "all") {
                    // all employees
                    $this->new_targets = ProductTarget::where(function ($query) {
                        $query->where('year', $this->keys[0])
//                            ->where('branch', $this->dept_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                        ->orWhere(function ($query) {
                            $query->where('year', $this->keys[1])
//                            ->where('branch', $this->dept_id)
                                ->whereIn('month', array_values($this->list[$this->keys[1]]));
                        })
                        ->selectRaw('product_id, month, year, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year')
                        ->get();
//                    dd($this->new_targets);
                }
                else {
                    // specific employee

                    $this->new_targets = ProductTarget::where('user_id', $this->user_id)
                        ->where(function ($query) {
                        $query->where('year', $this->keys[0])
                            ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                        ->orWhere(function ($query) {
                            $query->where('year', $this->keys[1])
                                ->where('user_id', $this->user_id)
                                ->whereIn('month', array_values($this->list[$this->keys[1]]));
                        })
                        ->selectRaw('product_id, month, year, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year')
                        ->get();
//                    dd("kk");
                }
            }
            else {
                // for specific department
                if ($this->user_id == "all") {
                    // all employees
//                    dd('dd');
                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                ->where('user_id', Auth::id())
                        ->where(function ($query) {
                            $query->where('year', $this->keys[0])
                                ->where('branch', $this->dept_id)
                                ->whereIn('month', array_values($this->list[$this->keys[0]]));
                        })
                        ->orWhere(function ($query) {
                            $query->where('year', $this->keys[1])
                                ->where('branch', $this->dept_id)
                                ->whereIn('month', array_values($this->list[$this->keys[1]]));
                        })
                        ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year', 'branch')
                        ->get();

                }
                else {
                    // specific employees
//                    dd('ss');
                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
                        ->where('user_id', $this->user_id)
                        ->where(function ($query) {
                            $query->where('year', $this->keys[0])
                                ->where('branch', $this->dept_id)
                                ->where('user_id', $this->user_id)
                                ->whereIn('month', array_values($this->list[$this->keys[0]]));
                        })
                        ->orWhere(function ($query) {
                            $query->where('year', $this->keys[1])
                                ->where('branch', $this->dept_id)
                                ->where('user_id', $this->user_id)
                                ->whereIn('month', array_values($this->list[$this->keys[1]]));
                        })
                        ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year', 'branch')
                        ->get();
//                    dd($this->new_targets);
                }
            }
        }
        else {

            if ($this->dept_id == "all") {

                if ($this->user_id == "all") {
//                    dd('bb');
                    $this->new_targets = ProductTarget::where('year', $this->keys[0])
                        ->whereIn('month', array_values($this->list[$this->keys[0]]))
                        ->selectRaw('product_id, month, year, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year')
                        ->get();
                }
                else {
//                    dd('nn');
                    $this->new_targets = ProductTarget::where('year', $this->keys[0])
                        ->where('user_id', $this->user_id)
                        ->whereIn('month', array_values($this->list[$this->keys[0]]))
                        ->selectRaw('product_id, month, year, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year')
                        ->get();
                }
            }
            else {
                if ($this->user_id == "all") {
//                    dd('ppp');
                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                ->where('user_id', Auth::id())
                        ->where('year', $this->keys[0])
                        ->whereIn('month', array_values($this->list[$this->keys[0]]))
                        ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year', 'branch')
                        ->get();
                }
                else {
//                    dd('vvv');
                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
                        ->where('user_id', $this->user_id)
                        ->where('year', $this->keys[0])
                        ->whereIn('month', array_values($this->list[$this->keys[0]]))
                        ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                        ->groupBy('product_id', 'month', 'year', 'branch')
                        ->get();
                }
            }
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
            and SIDate>='". $start_of_period."' and  SIDate<='".$end_of_period." 23:59:59'";
        if ($this->dept_id != "all") {
            $month_stmt .="and Department = ".$this->dept_id;
        }
            $month_stmt .=" group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='". $start_of_period ."' and  PIDate<='".$end_of_period." 23:59:59'";
        if ($this->dept_id != "all") {
            $month_stmt .="and Department = ".$this->dept_id;
        }
            $month_stmt .= " group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
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
