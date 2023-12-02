<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\ProductMast;
use App\Models\ProductTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListMyProductTarget extends Component
{
    public $dept_id = ["dept_all"];
    public $user_id = -1;
    public $selected_month;
    public $users;
    public $user_branches;
    public $cat_type = ["cat_all"];
    public $sp_type = ["sp_all"];
    public $vendor_list = [];
    public $vendor_type = "vendor_all";
    public $show_msg = false;
    public $results = [];
    public $list = [];
    public $current_year_list = [];
    public $target;
    public $diff;
    public $keys = [];
    public $new_targets = [];
    public $items = [];
    public $products_items = [];
    public $month_stmt;
    public $employee_ids_in_my_branch = [];
    public $loading = false;

    protected $listeners = ['create-report' => 'create_report'];

    protected $rules = [
        'dept_id' => 'required|not_in:-1',
        'selected_month' => 'required',
        'cat_type' => 'required|not_in:-1',
        'sp_type' => 'required|not_in:-1',
        'vendor_type' => 'required|not_in:-1',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
        'cat_type.required' => "مطلوب",
        'cat_type.not_in' => "مطلوب",
        'sp_type.required' => "مطلوب",
        'sp_type.not_in' => "مطلوب",
        'vendor_type.required' => "مطلوب",
        'vendor_type.not_in' => "مطلوب",
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
        $this->key = now();

        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
            ->where('ProductMast.PriceList', 1)
            ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
            ->get();

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
        $this->user_branches = $branches;

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

//    public function updatedDeptId($value) {
//        $this->reset(['show_msg', 'results', 'items']);
//
////        dd('xxxx');
////        $branches = json_decode(Auth::user()->branches);
////        if (count($branches) == 1) {
////            $this->dept_id = $branches[0];
////        }
////
////        if (Auth::user()->user_group->read_type == '1') {
//////            $this->user_id =
////        }
//
////        if ($value == "all") {
////
////            $emp_codes = [];
////
////            $branches = json_decode(Auth::user()->branches);
////
////            foreach ($branches as $branch) {
////                $emps = User::join('user_groups', 'user_groups.id', 'users.group')
////                    ->whereIn('write_product_target', ['1', '2'])
////                    ->where('branches', 'like', '%"'.$branch.'"%')->get();
////                foreach ($emps as $emp) {
////                    array_push($emp_codes, $emp->emp_code);
////                }
////            }
////
////            $emp_codes = array_unique($emp_codes);
//////            dd($emp_codes);
////
////            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
////                ->whereIn('write_product_target', ['1', '2'])
////                ->select('users.id', 'users.name')
////                ->whereNotNull('group')
////                ->where('role', 'u')
//////                ->whereNotIn('users.id', [1,13,14,15,16,18,21,38])
////                ->whereIn('emp_code', $emp_codes)
////                ->distinct()
////                ->get();
////
////        }
////        else {
////        if (count($this->dept_id) == 1) {
////            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
////                ->where('branches', 'LIKE' ,'%"'.$value[0].'"%')
////                ->whereNotNull('group')
////                ->where('role', 'u')
////                ->whereIn('write_product_target', ['1', '2'])
//////                ->where('group', '!=', 4)
//////                ->where('group', '!=', 5)
//////                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
////                ->select('users.id', 'users.name', 'users.emp_code')
////                ->distinct()
////                ->get();
////        }
////        }
//
////        dd($this->users);
//
////        $this->emit('re-initialize-select2');
//
//    }

//    public function updatedUserId($value) {
//        $this->reset(['selected_month', 'show_msg']);
//        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
//        $this->emit('re-initialize-select2');
//    }

    public function updatedSelectedMonth($value) {
        $this->reset(['show_msg']);
        $this->emit('re-initialize-select2');
    }

    public function create_report($dept_id, $cat_type, $sp_type, $vendor_type) {
        $this->dept_id = $dept_id;
        $this->cat_type = $cat_type;
        $this->sp_type = $sp_type;
        $this->vendor_type = $vendor_type;

        $this->generateReport();
    }

    public function generateReport() {
        $this->validate();


        if (in_array('dept_all', $this->dept_id)) {
            $this->dept_id = $this->user_branches;
        }

        if (count($this->dept_id) == 1) {
            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'users.emp_code')
                ->distinct()
                ->get();
        }

        $this->emit('show-container');
//        $this->emit('re-initialize-select2');
        $this->items = [];
        $month_stmt = null;
        $this->results = [];
        $this->products_items = [];


        if (count($this->dept_id) == 1) {
            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'users.emp_code')
                ->distinct()
                ->get();
        }

//        dd($this->users);

        $month_stmt = '';
        $this->list = [];

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

        if (count($this->keys) == 1) {

            if (count($this->dept_id) == 1) {
                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                    ->where('branch', $this->dept_id[0])
//                ->where('user_id', $this->user_id)
                    ->where(function ($query) {
                        $query->where('year', $this->keys[0])
                            ->where('branch', $this->dept_id[0])
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                    ->selectRaw('product_id, month, year, branch, user_id, emp_code, SUM(target) as target')
                    ->groupBy('product_id', 'month', 'year', 'branch', 'user_id', 'emp_code')
                    ->get();
            }
            elseif (count($this->dept_id) > 1) {
                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                    ->whereIn('branch', $this->dept_id)
//                ->where('user_id', $this->user_id)
                    ->where(function ($query) {
                        $query->where('year', $this->keys[0])
                            ->whereIn('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                    ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                    ->groupBy('product_id', 'month', 'year', 'branch')
                    ->get();
            }
        }
        elseif (count($this->keys) > 1) {

            if (count($this->dept_id) == 1) {
                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                    ->where('branch', $this->dept_id[0])
//                ->where('user_id', $this->user_id)
                    ->where(function ($query) {
                        $query->where('year', $this->keys[0])
                            ->where('branch', $this->dept_id[0])
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                    ->orWhere(function ($query) {
                        $query->where('year', $this->keys[1])
                            ->where('branch', $this->dept_id[0])
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[1]]));
                    })
                    ->selectRaw('product_id, month, year, branch, user_id, emp_code, SUM(target) as target')
                    ->groupBy('product_id', 'month', 'year', 'branch', 'user_id', 'emp_code')
                    ->get();
            }
            if (count($this->dept_id) > 1) {
                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
                    ->whereIn('branch', $this->dept_id)
//                ->where('user_id', $this->user_id)
                    ->where(function ($query) {
                        $query->where('year', $this->keys[0])
                            ->whereIn('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
                    })
                    ->orWhere(function ($query) {
                        $query->where('year', $this->keys[1])
                            ->whereIn('branch', $this->dept_id)
//                        ->where('user_id', $this->user_id)
                            ->whereIn('month', array_values($this->list[$this->keys[1]]));
                    })
//                    ->selectRaw('product_id, month, year, branch, SUM(target) as target')
                    ->select('product_id', 'month', 'year', 'branch', DB::raw('SUM(target) as target'))
                    ->groupBy('product_id', 'month', 'year', 'branch')
                    ->get();
            }
        }
//        dd($this->new_targets);

        $arr_new_targets = $this->new_targets->toArray();
//        dd($arr_new_targets);
        $product_codes = "";
        foreach ($arr_new_targets as $key => $product) {
            array_push($this->products_items, $product['product_id']);
        }

        $this->products_items = array_unique($this->products_items);
//        dd($this->products_items);
        $this->products_items = $this->filtered_products($this->products_items, $this->cat_type, $this->sp_type, $this->vendor_type);

        foreach ($this->products_items as $key => $product) {
            if (count($this->products_items) > 1) {
                if ($key === array_key_first($this->products_items)) {
                    $product_codes .= "('".$product."', ";
                }
                elseif ($key === array_key_last($this->products_items)) {
                    $product_codes .= "'".$product."')";
                }
                else {
                    $product_codes .= "'".$product."', ";
                }
            }
            else {
                $product_codes .= "('".$product."')";
            }

        }

        $month_counter = 1;

        $merged_dept = $this->dept_id;
//            dd($this->dept_id);
        // merge two depts
        if (in_array('3', $this->dept_id)) {
            array_push($merged_dept, "509");
        }
        elseif (in_array('10', $this->dept_id)) {
            array_push($merged_dept, "510");
        }
        elseif (in_array('12', $this->dept_id)) {
            array_push($merged_dept, "515");
        }

        if (count($this->products_items) > 0) {
            // for single dept.
            if (count($this->dept_id) == 1) {

                /* Sales Query Statement */
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
                    $month_stmt .="and Department in (".implode(',',$merged_dept).")";
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
                    $month_stmt .="and Department in ( ".implode(',',$merged_dept) . ")";
                }
                $month_stmt .= " group by ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate, WarrentyInfo.SalesEmployee, StudentMast.Code, PInvoice.PInvoiceNo
            ) as tbl
            group by ProductNo, SalesEmployee, EmpCode) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode in " . $product_codes .
                    " ORDER BY VendorNo";

//        dd($month_stmt);
//        dd($product_codes);

//        $test = '';

                if (count($this->products_items) > 0) {
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
            }
            elseif (count($this->dept_id) > 1) {
                /* Sales Query Statement */
                $month_stmt = "SELECT  Department, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount FROM (
            SELECT Department, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, WholeSale, MaxDiscount  FROM (
			SELECT (CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END) as Department, ProductNo";


                foreach ($this->list as $year_key => $year) {

                    foreach ($year as $month) {
                        $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
                        $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
                        $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                        $month_counter++;
                    }
                }

                $month_stmt .= " FROM (
            SELECT Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate as 'voucher_date', SInvoice.SInvoiceNo, sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=accmast.NodeNo and accmast.[type]=10
            and SIDate>='". $start_of_period."' and  SIDate<='".$end_of_period." 23:59:59'";
                if ($this->dept_id != "all") {
                    $month_stmt .="and Department in (".implode(',',$merged_dept).")";
                }
                $month_stmt .=" group by Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate, SInvoice.SInvoiceNo
            union all
            SELECT Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate as 'voucher_date', PInvoice.PInvoiceNo, -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=accmast.NodeNo and accmast.[type]=10
            and PIDate>='". $start_of_period ."' and  PIDate<='".$end_of_period." 23:59:59'";
                if ($this->dept_id != "all") {
                    $month_stmt .="and Department in (". implode(',',$merged_dept) . ")";
                }
                $month_stmt .= " group by Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate, PInvoice.PInvoiceNo
            ) as tbl
            group by (CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END), ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode in " . $product_codes .
                    " ORDER BY VendorNo";

//        dd($month_stmt);
//        dd($product_codes);

//        $test = '';

                if (count($this->products_items) > 0) {
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
            }
        }
        else {
            $this->results = [];
        }

//        dd($month_stmt);

//        $item_query = DB::connection('sqlsrv')->select($month_stmt);
//        $fetch_item_query = json_decode(json_encode($item_query), true);
//        array_push($this->results, $fetch_item_query);

//        dd($this->items);
//        var_dump($this->results);
//        dd($this->results);


        $this->show_msg = true;
//        dd($this->results);

        $this->results = collect($this->results);
//        dd($this->results);
//        dd($this->new_targets);

//        dd($this->results);
//        dd($this->results->where('ProductCode', '220004')->first()->ProductName);
//        dd($this->results);

    }

    public function filtered_products($products, $cats, $sps, $vendors) {

        $bathoor = "Code like '20%' or Code like '21%' or Code like '22%' ";
        $asmedah = "Code like '17%' ";
        $mobedat = "Code like '10%' or Code like '11%' or Code like '12%' or Code like '13%' or Code like '14%' or Code like '15%' or Code like '16%' ";
        $other = "(Code not like '20%' and Code not like '21%' and Code not like '22%' and Code not like '10%' and Code not like '11%' and Code not like '12%' and Code not like '13%' and Code not like '14%' and Code not like '15%' and Code not like '16%' and Code not like '17%') ";

        $cat_stmt = "AND (";
        foreach ($cats as $key => $cat) {
            if ($key === array_key_first($cats)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= $bathoor;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= $asmedah;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= $mobedat;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= $other;
                }
            }
            elseif ($key === array_key_last($cats)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= ' or '.$bathoor;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= ' or '.$asmedah;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= ' or '.$mobedat;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= 'or '.$other;
                }
            }
            else {
                if ($cat == 'bathoor') {
                    $cat_stmt .= " or " . $bathoor;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= " or " . $asmedah;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= " or " . $mobedat;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= " or " . $other;
                }
            }
        }

        $cat_stmt .= ") ";

        $stmt = "SELECT Code FROM ProductMast WHERE Pricelist = 1 AND Code in (". implode(',', $products).") ";
        if ($vendors != 'vendor_all') {
            $stmt .= "AND VendorNo = '". $vendors ."' ";
        }
        if (in_array('sp_all', $sps) == false) {
            $stmt .= "AND SpecialityCode in (". implode(',', $sps).") ";
        }
        if (in_array('cat_all', $cats) == false) {
            $stmt .= $cat_stmt;
        }

        $products_query = DB::connection('sqlsrv')->select($stmt);
        $fetch_products_query = json_decode(json_encode($products_query), true);
//        array_push($this->items, $fetch_item_query);
//        dd($fetch_products_query);

        $results = [];
        array_walk_recursive($fetch_products_query, function ($item, $key) use (&$results){array_push($results, $item);});

//        dd($results);
        return $results;
    }
}
