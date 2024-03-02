<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\ProductMast;
use App\Models\Products;
use App\Models\ProductTarget;
use App\Models\ProductTargetBranchTotal;
use App\Models\ProductTargetFilter;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Symfony\Component\Console\Input\Input;

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
    public $item_price;

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

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.my-product-target', json_decode(Auth::user()->user_group->report_type))) || (Auth::user()->user_group && in_array('list.my-product-target-only', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

//        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
        $this->key = now();

        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
            ->where('ProductMast.PriceList', 1)
            ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
            ->get();

        $this->get_filters();
//        dd($this->selected_month);

    }

    public function render()
    {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $branches = json_decode(Auth::user()->branches);
        $this->user_branches = $branches;

        $settings_record = Setting::first();
        $this->item_price = $settings_record->item_price;

        foreach ($branches as $branch) {
            $emps = User::join('user_groups', 'user_groups.id', 'users.group')
                ->where('branches', 'like', '%"'.$branch.'"%')
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw('write_product_target IN (1,2)')
                ->select('users.id')
                ->get();
            foreach ($emps as $emp) {
                array_push($this->employee_ids_in_my_branch, $emp->id);
            }
        }

        return view('livewire.list-my-product-target')
            ->layout('layouts.dashboard');
    }

    public function updatedSelectedMonth($value) {
        $this->reset(['show_msg']);
        $this->emit('re-initialize-select2');
    }

    public function create_report($dept_id, $cat_type, $sp_type, $vendor_type) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->dept_id = $dept_id;
        $this->cat_type = $cat_type;
        $this->sp_type = $sp_type;
        $this->vendor_type = $vendor_type;

        $this->generateReport();
    }

    public function generateReport() {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
        $this->validate();

        $this->save_filters();

        if (in_array('dept_all', $this->dept_id)) {
            $this->dept_id = $this->user_branches;
        }

        $this->emit('show-container');
//        $this->items = [];
        $month_stmt = null;
        $this->results = [];
//        $this->products_items = [];

//        $query = DB::connection('sqlsrv')->select($month_stmt);
//        $this->results = $query;


        if (count($this->dept_id) == 1) {
            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw('write_product_target IN (1,2)')
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'users.emp_code')
                ->distinct()
                ->get();
        }

//        $fetch_item_query = json_decode(json_encode($item_query), true);

        $month_stmt = $this->getSales();
//        dd($month_stmt);

        if ($month_stmt != "") {
            $query = DB::connection('sqlsrv')->select($month_stmt);
            $this->results = $query;
        }

        $this->show_msg = true;

        $this->emit('finished');
//        $this->results = collect($this->results);


    }
    public function generateReport_old() {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
        $this->validate();

        $this->save_filters();

        if (in_array('dept_all', $this->dept_id)) {
            $this->dept_id = $this->user_branches;
        }

//        $this->getProducts();
//        $this->getSales();

//        if (count($this->dept_id) == 1) {
//            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
//                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
//                ->whereNotNull('group')
//                ->where('role', 'u')
////                ->whereIn('write_product_target', ['1', '2'])
//                ->whereRaw('write_product_target IN (1,2)')
////                ->where('group', '!=', 4)
////                ->where('group', '!=', 5)
////                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
//                ->select('users.id', 'users.name', 'users.emp_code')
//                ->distinct()
//                ->get();
//        }

        $this->emit('show-container');
        $this->items = [];
        $month_stmt = null;
        $this->results = [];
        $this->products_items = [];


        if (count($this->dept_id) == 1) {
            $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw('write_product_target IN (1,2)')
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'users.emp_code')
                ->distinct()
                ->get();
        }

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

//                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
//                    ->whereIn('branch', $this->dept_id)
////                ->where('user_id', $this->user_id)
//                    ->where(function ($query) {
//                        $query->where('year', $this->keys[0])
//                            ->whereIn('branch', $this->dept_id)
////                        ->where('user_id', $this->user_id)
//                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                    })
//                    ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                    ->groupBy('product_id', 'month', 'year', 'branch')
//                    ->get();

                $this->new_targets = ProductTargetBranchTotal::whereIn('branch', $this->dept_id)
                    ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
//                    ->whereRaw("branch = ". $this->dept_id[0])
                    ->select('product_id', 'month', 'year', 'branch', 'target')
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
//                $this->new_targets = ProductTarget::join('users', 'user_id', 'users.id')
//                    ->whereIn('branch', $this->dept_id)
////                ->where('user_id', $this->user_id)
//                    ->where(function ($query) {
//                        $query->where('year', $this->keys[0])
//                            ->whereIn('branch', $this->dept_id)
////                        ->where('user_id', $this->user_id)
//                            ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                    })
//                    ->orWhere(function ($query) {
//                        $query->where('year', $this->keys[1])
//                            ->whereIn('branch', $this->dept_id)
////                        ->where('user_id', $this->user_id)
//                            ->whereIn('month', array_values($this->list[$this->keys[1]]));
//                    })
////                    ->selectRaw('product_id, month, year, branch, SUM(target) as target')
//                    ->select('product_id', 'month', 'year', 'branch', DB::raw('SUM(target) as target'))
//                    ->groupBy('product_id', 'month', 'year', 'branch')
//                    ->get();

                $this->new_targets = ProductTargetBranchTotal::whereIn('branch', $this->dept_id)
                    ->whereRaw("((Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]]).")))")
//                    ->whereRaw("branch = ". $this->dept_id[0])
                    ->select('product_id', 'month', 'year', 'branch', 'target')
                    ->get();
            }
        }

        $arr_new_targets = $this->new_targets->toArray();
//        dd($arr_new_targets);
        $product_codes = "";
        foreach ($arr_new_targets as $key => $product) {
            array_push($this->products_items, $product['product_id']);
        }

        $this->products_items = array_unique($this->products_items);
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
        // merge two depts
        if (in_array('3', $this->dept_id)) {
            array_push($merged_dept, "509");
        }
        if (in_array('10', $this->dept_id)) {
            array_push($merged_dept, "510");
        }
        if (in_array('12', $this->dept_id)) {
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

                dd($month_stmt);
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
                    $item_stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount, Retail
                    FROM ProductMast, accmast
                    WHERE ProductMast.VendorNo = accmast.NodeNo
                    and ProductMast.Code in ". $product_codes;
                    $item_query = DB::connection('sqlsrv')->select($item_stmt);
                    $fetch_item_query = json_decode(json_encode($item_query), true);
                    array_push($this->items, $fetch_item_query);
                }

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

                dd($month_stmt);
//        dd($product_codes);

//        $test = '';

                if (count($this->products_items) > 0) {
                    $query = DB::connection('sqlsrv')->select($month_stmt);
                    $this->results = $query;

//                    dd($this->results);
//            $test = $query;
//
//            $fetch_query = json_decode(json_encode($query), true);
//
//            array_push($this->results, $fetch_query);
//                    $item_stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, WholeSale, MaxDiscount, Retail
//                    FROM ProductMast, accmast
//                    WHERE ProductMast.VendorNo = accmast.NodeNo
//                    and ProductMast.Code in ". $product_codes;
//                    $item_query = DB::connection('sqlsrv')->select($item_stmt);
//                    $fetch_item_query = json_decode(json_encode($item_query), true);
//                    array_push($this->items, $fetch_item_query);
                }

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

        $this->emit('finished');
        $this->results = collect($this->results);

//        dd($this->results);
//        dd($this->new_targets);

//        dd($this->results);
//        dd($this->results->where('ProductCode', '220004')->first()->ProductName);
//        dd($this->results);

    }

    public function filtered_products($products, $cats, $sps, $vendors) {

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

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

        $results = [];

        if (count($products) > 0) {
            $products_query = DB::connection('sqlsrv')->select($stmt);
            $fetch_products_query = json_decode(json_encode($products_query), true);
//        array_push($this->items, $fetch_item_query);
//        dd($fetch_products_query);


            array_walk_recursive($fetch_products_query, function ($item, $key) use (&$results){array_push($results, $item);});
        }

//        dd($results);
        return $results;
    }


    public function get_filters() {

        $record = ProductTargetFilter::where('user_id', Auth::id())
            ->where('page', "list")
            ->first();

        if($record) {
            $this->dept_id = json_decode($record->dept_id);
            $this->cat_type = json_decode($record->cat_type);
            $this->sp_type = json_decode($record->sp_type);
            $this->vendor_type = json_decode($record->vendor_type);
            $this->selected_month = $record->selected_month ? $record->selected_month : Carbon::parse(Carbon::now())->format('Y-m');

//            dd($record->selected_month);
//            dd($this->selected_month);
//            dd($this->dept_id);
        }
        else {
            $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
        }

//        dd($this->selected_month);

    }

    public function save_filters() {

        $record = ProductTargetFilter::where('user_id', Auth::id())
            ->where('page', "list")
            ->first();

        if($record) {
            $a = ProductTargetFilter::where('user_id', Auth::id())
                ->where('page', "list")
                ->update([
                    'dept_id' => json_encode($this->dept_id),
                    'cat_type' => json_encode($this->cat_type),
                    'sp_type' => json_encode($this->sp_type),
                    'vendor_type' => json_encode($this->vendor_type),
                    'selected_month' => $this->selected_month,
                ]);
        }
        else {
            $a = ProductTargetFilter::create([
                'dept_id' => json_encode($this->dept_id),
                'cat_type' => json_encode($this->cat_type),
                'sp_type' => json_encode($this->sp_type),
                'vendor_type' => json_encode($this->vendor_type),
                'selected_month' => $this->selected_month,
                'user_id' => Auth::id(),
                'page' => 'list',
            ]);
        }
    }

    public function getProducts() {

        $bathoor = "Code like '20%' or Code like '21%' or Code like '22%' ";
        $asmedah = "Code like '17%' ";
        $mobedat = "Code like '10%' or Code like '11%' or Code like '12%' or Code like '13%' or Code like '14%' or Code like '15%' or Code like '16%' ";
        $other = "(Code not like '20%' and Code not like '21%' and Code not like '22%' and Code not like '10%' and Code not like '11%' and Code not like '12%' and Code not like '13%' and Code not like '14%' and Code not like '15%' and Code not like '16%' and Code not like '17%') ";

//        $bathoor2 = "product_code like '20%' or product_code like '21%' or product_code like '22%' ";
//        $asmedah2 = "product_code like '17%' ";
//        $mobedat2 = "product_code like '10%' or product_code like '11%' or product_code like '12%' or product_code like '13%' or product_code like '14%' or product_code like '15%' or product_code like '16%' ";
//        $other2 = "(product_code not like '20%' and product_code not like '21%' and product_code not like '22%' and product_code not like '10%' and product_code not like '11%' and product_code not like '12%' and product_code not like '13%' and product_code not like '14%' and product_code not like '15%' and product_code not like '16%' and product_code not like '17%') ";

//        $cat_stmt = "AND (";
        $cat_stmt = "(";
//        $cat_stmt2 = "(";
        foreach ($this->cat_type as $key => $cat) {
            if ($key === array_key_first($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= $bathoor;
//                    $cat_stmt2 .= $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= $asmedah;
//                    $cat_stmt2 .= $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= $mobedat;
//                    $cat_stmt2 .= $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= $other;
//                    $cat_stmt2 .= $other2;
                }
            }
            elseif ($key === array_key_last($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= ' or '.$bathoor;
//                    $cat_stmt2 .= ' or '.$bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= ' or '.$asmedah;
//                    $cat_stmt2 .= ' or '.$asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= ' or '.$mobedat;
//                    $cat_stmt2 .= ' or '.$mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= 'or '.$other;
//                    $cat_stmt2 .= 'or '.$other2;
                }
            }
            else {
                if ($cat == 'bathoor') {
                    $cat_stmt .= " or " . $bathoor;
//                    $cat_stmt2 .= " or " . $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= " or " . $asmedah;
//                    $cat_stmt2 .= " or " . $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= " or " . $mobedat;
//                    $cat_stmt2 .= " or " . $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= " or " . $other;
//                    $cat_stmt2 .= " or " . $other2;
                }
            }
        }

        $cat_stmt .= ") ";
//        $cat_stmt2 .= ") ";

        $sp_txt = in_array('sp_all', $this->sp_type) ?  ('SpecialityCode IN (0,1,2)') : ('SpecialityCode IN (' . implode(',' , $this->sp_type) . ')');
        $cat_txt1 = in_array('cat_all', $this->cat_type) ?  ('Code is not null') : $cat_stmt;
//        $cat_txt2 = in_array('cat_all', $this->cat_type) ?  ('product_code is not null') : $cat_stmt2;

//        $sales_year_keys = array_keys($this->list);

//        $stmt = "SELECT NodeNo FROM ProductMast
//                WHERE Pricelist = 1
//                AND " . ($this->vendor_type == "vendor_all" ? "VendorNo is not null"  : "VendorNo ='" . $this->vendor_type . "'")
//                . " AND " . $sp_txt . " AND " . $cat_txt1;

//        dd($stmt);


//        $this->items = Products::where('Pricelist', '1')
////            ->where('products.VendorNo', $this->vendor_type)
//            ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
////            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
//            ->whereRaw($sp_txt)
////            ->whereIn('products.SpecialityCode', $this->sp_type)
////            ->whereRaw($cat_stmt2)
////            ->whereRaw($cat_txt2)
//            ->whereRaw($cat_txt1)
//            ->selectRaw('product_code as ProductCode, product_name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo, vendor_code as VendorCode, vendor_name as VendorName')
////            ->toSql();
//            ->get()->toArray();

//        $this->items = ProductMast::where('Pricelist', '1')
        $items = ProductMast::where('Pricelist', '1')
//            ->where('products.VendorNo', $this->vendor_type)
            ->whereRaw($this->vendor_type == "vendor_all" ? "ProductMast.VendorNo is not null"  : "ProductMast.VendorNo ='" . $this->vendor_type . "'")
//            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
            ->whereRaw($sp_txt)
//            ->whereIn('products.SpecialityCode', $this->sp_type)
//            ->whereRaw($cat_stmt2)
//            ->whereRaw($cat_txt2)
            ->whereRaw($cat_txt1)
//            ->selectRaw('Code as ProductCode, Arabic_Name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo')
            ->pluck('NodeNo')
            ->toArray();
//            ->toSql();
//            ->get()->toArray();

        return $items;
//        dd(implode(',', $items));
//        dd($items);

    }

    public function getSales() {

//        dd($this->dept_id);
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

        /* Sales Query Statement */

        $products = $this->getProducts();

        $month_counter = 1;
        $month_stmt = "";
        $months_txt = "";
        $sum_txt = "";
        $stock_txt = "";

        if (count($products) > 0) {
            $selected_year1 = Carbon::parse($this->selected_month);
            $selected_year2 = Carbon::parse($this->selected_month)->addMonth(11);


            $start_of_period = $selected_year1->format('Y-m-d');
            $end_of_period = $selected_year2->endOfMonth()->format('Y-m-d');

            $merged_dept = $this->dept_id;
            // merge two depts
            if (in_array('3', $this->dept_id)) {
                array_push($merged_dept, "509");
            }
            if (in_array('10', $this->dept_id)) {
                array_push($merged_dept, "510");
            }
            if (in_array('12', $this->dept_id)) {
                array_push($merged_dept, "515");
            }

//        $month_stmt = "";
//        $months_txt = "";
//        $sum_txt = "";
//        $stock_txt = "";

            if (count($this->dept_id) == 1) {

                $this->users = User::join('user_groups', 'users.group', 'user_groups.id')
                    ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                    ->whereNotNull('group')
                    ->where('role', 'u')
                    ->whereRaw('write_product_target IN (1,2)')
                    ->select('users.id','users.emp_code', 'users.name')
                    ->distinct()
//                ->pluck('users.emp_code')
//                ->toArray();
                    ->get();


                $user_ids = User::join('user_groups', 'users.group', 'user_groups.id')
                    ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                    ->whereNotNull('group')
                    ->where('role', 'u')
                    ->whereRaw('write_product_target IN (1,2)')
//                ->select('users.emp_code')
                    ->distinct()
                    ->pluck('users.emp_code')
                    ->toArray();

//                ->get();
                array_walk($user_ids, function (&$value, $key) {
                    $value="'"."$value"."'";
                });

                $month_stmt = "SELECT * FROM (
                            SELECT
                            ProductNo,";

                foreach ($this->list as $year_key => $year) {

                    foreach ($year as $month_key => $month) {
                        $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
                        $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
//                $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                        $sum_txt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                        foreach ($user_ids as $user_key => $user_id) {
//                        if ($user_key === array_key_last($user_ids) && $month_key === array_key_last($year)) {
                            $months_txt .= "MAX(CASE WHEN EmpCode = ". $user_id ." THEN month".$month_counter." END) as 'month".$month_counter."_".trim($user_id, "'")."', ";
//                        }
//                        else {
//                            $months_txt .= "MAX(CASE WHEN EmpCode = ". $user_id ." THEN month".$month_counter." END) as 'month".$month_counter."_".trim($user_id, "'")."', ";
//                        }
                        }


                        $month_counter++;
                    }
                }

                $months_txt = rtrim($months_txt, ', ');

//             $trimmed_months = rtrim($months_txt, ', ');
//            dd($trimmed);
//            dd($months_txt);



                $month_stmt .= $months_txt;
                $month_stmt .= "FROM (

SELECT productMast.NodeNo, VendorNo, accmast.Arabic_Name as VendorName, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, BaseUnits, SpecialityCode, WholeSale, Retail, MaxDiscount FROM ProductMast, accmast
where VendorNo = accmast.NodeNo
and ProductMast.NodeNo in (". implode(',', $products).")) as product
LEFT JOIN
(SELECT ProductNo, SalesEmployee, EmpCode";

//            foreach ($this->list as $year_key => $year) {
//
//                foreach ($year as $month) {
//                    $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
//                    $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
//                    $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";
//
//                    $month_counter++;
//                }
//            }

                $month_stmt .= $sum_txt;
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
                $month_stmt .=" and ProductNo in (".implode(',', $products).") group by ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate, WarrentyInfo.SalesEmployee, StudentMast.Code, SInvoice.SInvoiceNo
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
                $month_stmt .= " and ProductNo in (".implode(',', $products).") group by ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate, WarrentyInfo.SalesEmployee, StudentMast.Code, PInvoice.PInvoiceNo
            ) as tbl
			where EmpCode in (". implode(',', $user_ids) .")
            group by ProductNo, SalesEmployee, EmpCode) as sales_tbl
			ON sales_tbl.ProductNo = product.NodeNo
			group by ProductNo
	HAVING ProductNo is not null) as sales_tbl2
	LEFT JOIN (


		SELECT * FROM (
 select * from (
 SELECT NodeNo as NodeNo_stock, Code, Department, SUM(Qty_In-Qty_out+Qty_in2) as stock FROM (
 SELECT Distinct
NodeNo,
Code,
Arabic_Name,
BaseUnits,
VendorNo,
Vendor_Code,
Vendor_ArName,
(CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END) as Department,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<=GETDATE()  And Department = V.Department   Group By ProductNo) as Qty_In ,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<=GETDATE() And Department = V.Department    Group By ProductNo) as Qty_Out,
case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) else 0 end  as Qty_in2
FROM (Select distinct
NodeNo ,
Code ,
Arabic_Name,
(CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END) as Department,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
-Sum(TotalCost) as Cost
from SInvoice ,productmast
Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
And  Department in ( ".implode(',',$merged_dept).")
AND ProductNo in (".implode(',', $products).")
And (SIDate <= GETDATE() )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
union all
Select distinct
NodeNo ,
Code,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
And  Department in (".implode(',', $merged_dept).")
AND ProductNo in (".implode(',', $products).")
And (PIDate <= GETDATE())
group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName, Department
) as tbl0
group by NodeNo, Code, Department) as dept_stock
LEFT JOIN (
 SELECT NodeNo as NodeNo_total, SUM(Qty_In-Qty_out+Qty_in2) as stock_total FROM (
 SELECT Distinct
NodeNo,
Code,
Arabic_Name,
BaseUnits,
VendorNo,
Vendor_Code,
Vendor_ArName,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<=GETDATE()  And Department = V.Department   Group By ProductNo) as Qty_In ,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<=GETDATE() And Department = V.Department    Group By ProductNo) as Qty_Out,
case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) else 0 end  as Qty_in2
FROM (Select distinct
NodeNo ,
Code ,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
-Sum(TotalCost) as Cost
from SInvoice ,productmast
Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
AND ProductNo in (".implode(',', $products).")
And (SIDate <= GETDATE() )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
union all
Select distinct
NodeNo ,
Code,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
AND ProductNo in (".implode(',', $products).")
And (PIDate <= GETDATE())
group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName
) as tbl0
group by NodeNo) as full_stock
ON dept_stock.NodeNo_stock = full_stock.NodeNo_total) as full_stock_details

LEFT JOIN (
select ProductMast.NodeNo, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, accmast.NodeNo as VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, BaseUnits, SpecialityCode, Retail, WholeSale, MaxDiscount from ProductMast, accmast
where VendorNo = accmast.NodeNo
and Pricelist = 1
and ProductMast.NodeNo in (".implode(',', $products).")) as prods_details
ON full_stock_details.NodeNo_stock = prods_details.NodeNo

	) as prods
	ON  sales_tbl2.ProductNo = prods.NodeNo";


            }
            elseif (count($this->dept_id) > 1) {

                foreach ($this->dept_id as $dept_key => $dept_id) {
//                if ($dept_key === array_key_last($this->dept_id)) {
                    $stock_txt .= "SUM(CASE WHEN Department = '".$dept_id."' THEN stock END) as 'stock_".$dept_id."', MAX(CASE WHEN Department = '".$dept_id."' THEN stock_total END) as 'stocktotal_".$dept_id."', ";
//                }
//                else {
//                    $stock_txt .= "MAX(CASE WHEN Department = '".$dept_id."' THEN stock END) as 'stock_".$dept_id."', MAX(CASE WHEN Department = '".$dept_id."' THEN stock_total END) as 'stocktotal_".$dept_id."', ";
//                }
                }

                $stock_txt = rtrim($stock_txt, ', ');
                foreach ($this->list as $year_key => $year) {


                    foreach ($year as $month_key => $month) {
                        $first_date = Carbon::parse($year_key.'-'.$month.'-01')->format('Y-m-d');
                        $end_date = Carbon::parse($year_key.'-'.$month.'-01')->endOfMonth()->format('Y-m-d');
//                $month_stmt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";
                        $sum_txt .= ", SUM(case when voucher_date >= '".$first_date." 00:00:00' and voucher_date <= '".$end_date." 23:59:59' then svalue else 0 end) as 'month".$month_counter."'";

                        foreach ($this->dept_id as $dept_key => $dept_id) {
//                        if ($dept_key === array_key_last($this->dept_id) && $month_key === array_key_last($year)) {
                            $months_txt .= "MAX(CASE WHEN Department = ". $dept_id ." THEN month".$month_counter." END) as 'month".$month_counter."_".$dept_id."', ";
//                        }
//                        else {
//                            $months_txt .= "MAX(CASE WHEN Department = ". $dept_id ." THEN month".$month_counter." END) as 'month".$month_counter."_".$dept_id."',";
//                        }

                        }

                        $month_counter++;
                    }
                }

                $months_txt = rtrim($months_txt, ', ');

//            $month_stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, ProductMast.BaseUnits, ProductMast.SpecialityCode, ProductMast.WholeSale, ProductMast.Retail, ProductMast.MaxDiscount, sales_tbl.* FROM ProductMast , accmast,";
                $month_stmt = "SELECT prods.*, sales_tbl.* FROM (


		SELECT * FROM (
 select NodeNo_stock, ".$stock_txt." from (
 SELECT NodeNo as NodeNo_stock, Code, (CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END) as Department, (Qty_In-Qty_out+Qty_in2) as stock FROM (
 SELECT Distinct
NodeNo,
Code,
Arabic_Name,
BaseUnits,
VendorNo,
Vendor_Code,
Vendor_ArName,
Department,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<=GETDATE()  And Department = V.Department   Group By ProductNo) as Qty_In ,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<=GETDATE() And Department = V.Department    Group By ProductNo) as Qty_Out,
case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) else 0 end  as Qty_in2
FROM (Select distinct
NodeNo ,
Code ,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
-Sum(TotalCost) as Cost
from SInvoice ,productmast
Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
And  Department in ( ".implode(',',$merged_dept).")
AND ProductNo in (".implode(',', $products).")
And (SIDate <= GETDATE() )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
union all
Select distinct
NodeNo ,
Code,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
And  Department in (".implode(',', $merged_dept).")
AND ProductNo in (".implode(',', $products).")
And (PIDate <= GETDATE())
group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName, Department
) as tbl0) as dept_stock
LEFT JOIN (
 SELECT NodeNo as NodeNo_total, SUM(Qty_In-Qty_out+Qty_in2) as stock_total FROM (
 SELECT Distinct
NodeNo,
Code,
Arabic_Name,
BaseUnits,
VendorNo,
Vendor_Code,
Vendor_ArName,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<=GETDATE()  And Department = V.Department   Group By ProductNo) as Qty_In ,
(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<=GETDATE() And Department = V.Department    Group By ProductNo) as Qty_Out,
case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= GETDATE()  )  ) else 0 end  as Qty_in2
FROM (Select distinct
NodeNo ,
Code ,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
-Sum(TotalCost) as Cost
from SInvoice ,productmast
Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
AND ProductNo in (".implode(',', $products).")
And (SIDate <= GETDATE() )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
union all
Select distinct
NodeNo ,
Code,
Arabic_Name,
Department ,
BaseUnits ,
VendorNo,
case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
AND ProductNo in (".implode(',', $products).")
And (PIDate <= GETDATE())
group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName
) as tbl0
group by NodeNo) as full_stock
ON dept_stock.NodeNo_stock = full_stock.NodeNo_total
group by NodeNo_stock
) as full_stock_details

LEFT JOIN (
select ProductMast.NodeNo, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, accmast.NodeNo as VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, BaseUnits, SpecialityCode, Retail, WholeSale, MaxDiscount from ProductMast, accmast
where VendorNo = accmast.NodeNo
and Pricelist = 1
and ProductMast.NodeNo in (".implode(',', $products).")) as prods_details
ON full_stock_details.NodeNo_stock = prods_details.NodeNo




	) as prods,";
                $month_stmt .= "(SELECT
                        ProductNo, ";
                $month_stmt .= $months_txt;
                $month_stmt .= "FROM ProductMast as prod_tbl

                    LEFT JOIN (";
                $month_stmt .= "SELECT (CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END) as Department, ProductNo";
                $month_stmt .= $sum_txt;

                $month_stmt .= " FROM (
            SELECT Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate as 'voucher_date', SInvoice.SInvoiceNo, sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=accmast.NodeNo and accmast.[type]=10
            and SIDate>='". $start_of_period."' and  SIDate<='".$end_of_period." 23:59:59'";
//                if ($this->dept_id != "all") {
                if (!in_array('dept_all', $this->dept_id)) {
                    $month_stmt .="and Department in (".implode(',',$merged_dept).")";
                }
                $month_stmt .=" and ProductNo in (". implode(',', $products) .")" . " group by Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, SIDate, SInvoice.SInvoiceNo
            union all
            SELECT Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate as 'voucher_date', PInvoice.PInvoiceNo, -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=accmast.NodeNo and accmast.[type]=10
            and PIDate>='". $start_of_period ."' and  PIDate<='".$end_of_period." 23:59:59'";
//                if ($this->dept_id != "all") {
                if (!in_array('dept_all', $this->dept_id)) {
                    $month_stmt .="and Department in (". implode(',',$merged_dept) . ")";
                }
                $month_stmt .= " and ProductNo in (". implode(',', $products) .")" ." group by Department, ProductNo, accmast.NodeNo, accmast.Code, accmast.Arabic_Name, PIDate, PInvoice.PInvoiceNo
            ) as tbl
            group by (CASE WHEN Department = 509 THEN 3 WHEN Department = 510 THEN 10 WHEN Department = 515 THEN 12 ELSE Department END), ProductNo) as a
            ON prod_tbl.NodeNo = a.ProductNo
	        group by ProductNo) sales_tbl
	        --WHERE sales_tbl.ProductNo = ProductMast.NodeNo
	        WHERE sales_tbl.ProductNo = prods.NodeNo
			--AND VendorNo = accmast.NodeNo";
            }

        }

//        dd($month_stmt);
        return $month_stmt;
    }
}
