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
    public $items = [];
    public $employee_ids_in_my_branch = [];

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

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.my-product-target', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {
        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
    }
//    public function boot() {
//        $branches = json_decode(Auth::user()->branches);
//        if (count($branches) == 1) {
//            $this->dept_id = $branches[0];
//        }
//    }
    public function render()
    {
        $branches = json_decode(Auth::user()->branches);

        foreach ($branches as $branch) {
            $emps = User::join('user_groups', 'user_groups.id', 'users.group')
                ->where('branches', 'like', '%"'.$branch.'"%')
                ->whereIn('write_product_target', ['1', '2'])
                ->select('users.id')
                ->get();
            foreach ($emps as $emp) {
                array_push($this->employee_ids_in_my_branch, $emp->id);
            }
        }

//        dd($branches);
//        dd($emps);

        return view('livewire.list-my-product-target')
            ->layout('layouts.dashboard');
    }

    public function updatedDeptId($value) {
        $this->reset(['user_id', 'selected_month', 'show_msg']);

//        dd('xxxx');
        $branches = json_decode(Auth::user()->branches);
//        if (count($branches) == 1) {
//            $this->dept_id = $branches[0];
//        }
//
//        if (Auth::user()->user_group->read_type == '1') {
////            $this->user_id =
//        }

//        if ($value == "all") {
//
//            $emp_codes = [];
//
//            $branches = json_decode(Auth::user()->branches);
//
//            foreach ($branches as $branch) {
//                $emps = User::join('user_groups', 'user_groups.id', 'users.group')
//                    ->whereIn('write_product_target', ['1', '2'])
//                    ->where('branches', 'like', '%"'.$branch.'"%')->get();
//                foreach ($emps as $emp) {
//                    array_push($emp_codes, $emp->emp_code);
//                }
//            }
//
//            $emp_codes = array_unique($emp_codes);
////            dd($emp_codes);
//
//            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
//                ->whereIn('write_product_target', ['1', '2'])
//                ->select('users.id', 'users.name')
//                ->whereNotNull('group')
//                ->where('role', 'u')
////                ->whereNotIn('users.id', [1,13,14,15,16,18,21,38])
//                ->whereIn('emp_code', $emp_codes)
//                ->distinct()
//                ->get();
//
//        }
//        else {
            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$value.'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'users.emp_code')
                ->distinct()
                ->get();
//        }

//        dd($this->users);
    }

    public function updatedUserId($value) {
        $this->reset(['selected_month', 'show_msg']);
        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
    }

    public function updatedSelectedMonth($value) {
        $this->reset(['show_msg']);
    }

    public function generateReport() {
        $this->validate();
        $this->emit('show-container');

        $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
            ->where('branches', 'LIKE' ,'%"'.$this->dept_id.'"%')
            ->whereNotNull('group')
            ->where('role', 'u')
            ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
            ->select('users.id', 'users.name', 'users.emp_code')
            ->distinct()
            ->get();

//        dd($this->users);

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

        $this->keys = array_keys($this->list);
//            if ($this->dept_id == "all") {
//                // all department
//                if ($this->user_id == "all") {
//                    // all employees
//                    // if 2 year
//                    if (count($this->keys) == 1) {
//                        $this->new_targets = ProductTarget::whereIn('user_id', $this->employee_ids_in_my_branch)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->selectRaw('product_id, month, year, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year')
//                            ->get();
//                    }
//                    elseif (count($this->keys) > 1) {
//                        $this->new_targets = ProductTarget::whereIn('user_id', $this->employee_ids_in_my_branch)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->orWhere(function ($query) {
//                                $query->where('year', $this->keys[1])
////                            ->where('branch', $this->dept_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[1]]));
//                            })
//                            ->selectRaw('product_id, month, year, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year')
//                            ->get();
////                    dd($this->new_targets);
//                    }
//                }
//                else {
//                    // specific employee
////                    dd('rtt');
//                    if (count($this->keys) == 1) {
//                        $this->new_targets = ProductTarget::where('user_id', $this->user_id)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->selectRaw('product_id, month, year, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year')
//                            ->get();
//                    }
//                    elseif (count($this->keys) > 1) {
//                        $this->new_targets = ProductTarget::where('user_id', $this->user_id)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->orWhere(function ($query) {
//                                $query->where('year', $this->keys[1])
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[1]]));
//                            })
//                            ->selectRaw('product_id, month, year, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year')
//                            ->get();
////                    dd("kk");
//                    }
//                }
//            }
//            else {
//                // for specific department
//                if ($this->user_id == "all") {
//                    // all employees
//                    $emp_ids = [];
//
//                    $branches = json_decode(Auth::user()->branches);
//
//                    foreach ($branches as $branch) {
//                        $emps = User::where('branches', 'like', '%"'.$branch.'"%')->get();
//                        foreach ($emps as $emp) {
//                            array_push($emp_ids, $emp->id);
//                        }
//                    }
//
//                    $emp_ids = array_unique($emp_ids);
//
//                    if (count($this->keys) == 1) {
//                        //                    dd('dd');
////                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                        $this->new_targets = ProductTarget::where('user_id', $emp_ids)
////                ->where('user_id', Auth::id())
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('branch', $this->dept_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year', 'branch')
//                            ->get();
//                    }
//                    elseif (count($this->keys) > 1) {
//                        //                    dd('dd');
////                    $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                        $this->new_targets = ProductTarget::where('user_id', $emp_ids)
////                ->where('user_id', Auth::id())
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('branch', $this->dept_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->orWhere(function ($query) {
//                                $query->where('year', $this->keys[1])
//                                    ->where('branch', $this->dept_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[1]]));
//                            })
//                            ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year', 'branch')
//                            ->get();
//                    }
//                }
//                else {
//                    // specific employees
////                    dd('ss');
//
//                    if (count($this->keys) == 1) {
//                        $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                            ->where('user_id', $this->user_id)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('branch', $this->dept_id)
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year', 'branch')
//                            ->get();
////                    dd($this->new_targets);
//                    }
//                    elseif (count($this->keys) > 1) {
//                        $this->new_targets = ProductTarget::where('branch', $this->dept_id)
//                            ->where('user_id', $this->user_id)
//                            ->where(function ($query) {
//                                $query->where('year', $this->keys[0])
//                                    ->where('branch', $this->dept_id)
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                            })
//                            ->orWhere(function ($query) {
//                                $query->where('year', $this->keys[1])
//                                    ->where('branch', $this->dept_id)
//                                    ->where('user_id', $this->user_id)
//                                    ->whereIn('month', array_values($this->list[$this->keys[1]]));
//                            })
//                            ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                            ->groupBy('product_id', 'month', 'year', 'branch')
//                            ->get();
////                    dd($this->new_targets);
//                    }
//
//                }
//            }

        if (count($this->keys) == 1) {
//            $this->new_targets = ProductTarget::where('branch', $this->dept_id)
////                ->where('user_id', $this->user_id)
//                ->where(function ($query) {
//                    $query->where('year', $this->keys[0])
//                        ->where('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
//                        ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                })
//                ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                ->groupBy('product_id', 'month', 'year', 'branch')
//                ->get();

            $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                ->where('branch', $this->dept_id)
//                ->where('user_id', $this->user_id)
                ->where(function ($query) {
                    $query->where('year', $this->keys[0])
                        ->where('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                        ->whereIn('month', array_values($this->list[$this->keys[0]]));
                })
                ->selectRaw('product_id, month, year, branch, user_id, emp_code, SUM(target) as target')
                ->groupBy('product_id', 'month', 'year', 'branch', 'user_id', 'emp_code')
                ->get();
//                    dd($this->new_targets);
        }
        elseif (count($this->keys) > 1) {
            $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                ->where('branch', $this->dept_id)
//                ->where('user_id', $this->user_id)
                ->where(function ($query) {
                    $query->where('year', $this->keys[0])
                        ->where('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                        ->whereIn('month', array_values($this->list[$this->keys[0]]));
                })
                ->orWhere(function ($query) {
                    $query->where('year', $this->keys[1])
                        ->where('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                        ->whereIn('month', array_values($this->list[$this->keys[1]]));
                })
                ->selectRaw('product_id, month, year, branch, user_id, emp_code, SUM(target) as target')
                ->groupBy('product_id', 'month', 'year', 'branch', 'user_id', 'emp_code')
                ->get();
//                    dd($this->new_targets->groupBy('product_id')->keys());

//            dd($this->new_targets->where('product_id', '140889')->where('month', '1')->where('year', '2024')->where('branch', '6')->where('emp_code', '10148')->first());
//            dd($this->new_targets->where('product_id', '140889')->where('month', '1')->where('year', '2024')->first());
        }

        $arr_new_targets = $this->new_targets->toArray();
        $product_codes = "";
        foreach ($arr_new_targets as $key => $product) {
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

        $month_counter = 1;

        /* Query Statement */
        $month_stmt = "SELECT SalesEmployee, EmpCode, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount FROM (
            SELECT SalesEmployee, EmpCode, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, WholeSale, MaxDiscount  FROM (
            SELECT ProductNo, SalesEmployee, EmpCode";


        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
                $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                $month_counter++;
            }
        }

        $month_stmt .= " FROM (
            SELECT ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate as 'voucher_date', WarrentyInfo.SalesEmployee, StudentMast.Code as EmpCode, SInvoice.SInvoiceNo, sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast, WarrentyInfo, StudentMast
            where partyno=accmast.NodeNo and accmast.[type]=10
            and SInvoice.PartyNo = WarrentyInfo.AccountNo
            and WarrentyInfo.SalesEmployee = StudentMast.NodeNo
            and SIDate>='". $start_of_period."' and  SIDate<='".$end_of_period." 23:59:59'";
        if ($this->dept_id != "all") {
            $month_stmt .="and Department = ".$this->dept_id;
        }
            $month_stmt .=" group by ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate, WarrentyInfo.SalesEmployee, StudentMast.Code, SInvoice.SInvoiceNo
            union all
            SELECT ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate as 'voucher_date', WarrentyInfo.SalesEmployee, StudentMast.Code as EmpCode, PInvoice.PInvoiceNo, -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast, WarrentyInfo, StudentMast
            where partyno=accmast.NodeNo and accmast.[type]=10
			and PInvoice.PartyNo = WarrentyInfo.AccountNo
			and WarrentyInfo.SalesEmployee = StudentMast.NodeNo
            and PIDate>='". $start_of_period ."' and  PIDate<='".$end_of_period." 23:59:59'";
        if ($this->dept_id != "all") {
            $month_stmt .="and Department = ".$this->dept_id;
        }
            $month_stmt .= " group by ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate, WarrentyInfo.SalesEmployee, StudentMast.Code, PInvoice.PInvoiceNo
            ) as tbl
            group by ProductNo, SalesEmployee, EmpCode) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode in " . $product_codes .
            "ORDER BY VendorNo";

//        dd($month_stmt);

//        $test = '';
        if (count($arr_new_targets) > 0) {
            $query = DB::connection('sqlsrv')->select($month_stmt);
            $this->results = $query;
//            $test = $query;
//
//            $fetch_query = json_decode(json_encode($query), true);
//
//            array_push($this->results, $fetch_query);
        }

        $item_stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount
                    FROM ProductMast, accmast
                    WHERE ProductMast.VendorNo = accmast.NodeNo
                    and ProductMast.Code in ". $product_codes;
        $item_query = DB::connection('sqlsrv')->select($item_stmt);
        $fetch_item_query = json_decode(json_encode($item_query), true);
        array_push($this->items, $fetch_item_query);

//        dd($this->items);


        $this->show_msg = true;


        $this->results = collect($this->results);

//        dd($this->results);
//        dd($this->results->where('ProductCode', '220004')->first()->ProductName);
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
