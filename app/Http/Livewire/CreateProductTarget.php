<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\ProductMast;
use App\Models\ProductTarget;
use App\Models\ProductTargetBranchTotal;
use App\Models\ProductTargetEmpPercent;
use App\Models\ProductTargetFilter;
use App\Models\ProductTargetLog;
use App\Models\ScribeProductTarget;
use App\Models\Setting;
use App\Models\SpecialProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProductTarget extends Component
{
    public $dept_id = ["-1"];
    public $selected_month;
    public $employee_ids_in_my_branch = [];
    public $employee_branch_names = [];
    public $user_branches;
    public $old_targets = [];
    public $cat_type = ["cat_all"];
    public $sp_type = ["sp_all"];
    public $vendor_list = [];
    public $vendor_type = "vendor_all";
    public $prod_id = null;

    public $results = [];
    public $list = [];
    public $current_year_list = [];
    public $target;
    public $emp_target;
    public $diff;
    public $show_msg = false;
    public $current_target = [];
    public $current_target_to_edit = [];
    public $current_start_selected_month_exploded;
    public $current_end_selected_month_exploded;
    public $btn_generate = true;
    public $btn_save = false;

    public $query;
    public $emps = [];
    public $user_ids = [];
    public $emps_percentage;
    public $dist_days;
    public $write_product_target;
    public $special_product_id;
    public $choose_special_product;
    public $edit_special_product;
    public $items;
    public $item_price;

    protected $listeners = ['targets-entered' => 'test', 'create-report' => 'create_report'];

    protected $rules = [
        'dept_id' => 'required|array|min:1|not_in:-1',
        'selected_month' => 'required',
        'cat_type' => 'required|not_in:-1',
        'sp_type' => 'required|not_in:-1',
        'vendor_type' => 'required|not_in:-1',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'dept_id.min' => "مطلوب",
        'selected_month.required' => "مطلوب",
        'prod_id.required' => "مطلوب",
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


//        if (Auth::user()->user_group->write_product_target == '1' || Auth::user()->user_group->write_product_target == '2'){
//            return;
//        } else {
//            return redirect()->route('dashboard');
//        }
    }

    public function mount() {

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->query = User::where('id', Auth::id())->first();
        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');

//        dd($this->users);

        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
        ->where('ProductMast.PriceList', 1)
//        ->select('accmast.NodeNo as nodeno', 'accmast.Arabic_Name as arabic_name')
        ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
//        ->distinct()
        ->get();
//        dd($this->vendor_list);
//        foreach ($this->vendor_list as $list) {
//            dd($list->Arabic_Name);
//        }

//        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
//        ->where('ProductMast.PriceList', 1)
////        ->select('accmast.NodeNo as nodeno', 'accmast.Arabic_Name as arabic_name')
//        ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
////        ->distinct()
//        ->get();

//        $this->vendor_list = ProductMast::where('ProductMast.PriceList', 1)
////        ->select('accmast.NodeNo as nodeno', 'accmast.Arabic_Name as arabic_name')
////            ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
////        ->distinct()
//            ->get();
//
//        $dd = $this->vendor_list;

//        $this->vendor_list = User::all();
//        dd($this->vendor_list);

        $this->get_filters();

    }

    public function render()
    {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
//        $test = ScribeProductTarget::where('Department', $this->dept_id)
//            ->where('PriceList', '1')
//            ->where('Year', '2024')
//            ->where('month', '10')
//            ->where('ProductNo', '867')
//            ->where(function ($query) {
//                $query->where('Taget', '<>', 0)
//                    ->orWhere('Revision', '<>', 0);
//            })
//            ->orderBy('Date', 'desc')
//            ->get();
//

//        dd($this->old_targets);


//        dd($test ? $test->Taget : null);
//        $now = Carbon::now();
//        $month_days = Carbon::parse('2024-02-01')->daysInMonth;
//        dd($month_days);
//        dd($now->year."-".$now->month."-01");
//        dd(Carbon::parse($now->year."-".$now->month."-01")->subDay()->diffInDays('2023-12-30', false));
//        dd(Carbon::parse($now->year."-".$now->month."-01")->diffInMonths('2024-02-'.$month_days, false)+1);
//        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');


//        $fromDate = Carbon::now();
//        $toDate = Carbon::parse("2023-06-30");
//        dd($toDate);
//        dd($fromDate->lt($toDate));
//
//        $months = $fromDate->diffInMonths($toDate, false);
//        dd($months);
        $settings_record = Setting::first();
        $this->dist_days = $settings_record->dist_days;
        $this->write_product_target = Auth::user()->user_group->write_product_target;
        $this->choose_special_product = Auth::user()->user_group->choose_special_product;
        $this->edit_special_product = Auth::user()->user_group->edit_special_product;
        $this->item_price = $settings_record->item_price;

        $branches = json_decode($this->query->branches);
//        $branches = json_decode(Auth::user()->branches);
        $this->user_branches = $branches;




        return view('livewire.create-product-target', compact('branches'))
            ->layout('layouts.dashboard');
    }

    public function updatedDeptId($value) {
        $this->reset(['show_msg']);
        $this->btn_generate = true;
        $this->btn_save = false;
    }

    public function updatedSelectedMonth($value) {
        $this->reset(['show_msg']);
        $this->btn_generate = true;
        $this->btn_save = false;
    }

    public function create_report($dept_id, $cat_type, $sp_type, $vendor_type) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
        $this->dept_id = $dept_id;
        $this->cat_type = $cat_type;
        $this->sp_type = $sp_type;
        $this->vendor_type = $vendor_type;

        if (in_array("-1", $this->dept_id)) {
            $this->dept_id = $this->user_branches;
        }
        if (count($this->dept_id) == 1) {
            $this->generateReport();
        }
        else {
            $this->generateBranchesReport();
        }
    }

    public function generateReport()
    {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->emps = [];
        $this->employee_branch_names = [];
//        $this->resetExcept(['branches', 'dept_id', 'filter_type', 'vendor_id']);
//        dd($this->vendor_id);
        $this->reset('target', 'results', 'current_year_list', 'current_target', 'current_target_to_edit', 'old_targets');
        $this->validate();
//        $this->results = [];
//        $current_year_list = [];
        $this->save_filters();

        $this->employee_ids_in_my_branch = [];
        foreach ($this->dept_id as $branch) {
            $emps = User::join('user_groups', 'user_groups.id', 'users.group')
                ->where('branches', 'like', '%"'.$branch.'"%')
                ->whereIn('write_product_target', ['1', '2'])
                ->select('users.id')
                ->get();

            $dept_code = "";

            if ($branch == "3") {
                $dept_code = "0101";
            }
            elseif ($branch == "6") {
                $dept_code = "0106";
            }
            elseif ($branch == "4") {
                $dept_code = "0105";
            }
            elseif ($branch == "11") {
                $dept_code = "0109";
            }
            elseif ($branch == "8") {
                $dept_code = "0111";
            }
            elseif ($branch == "505") {
                $dept_code = "0112";
            }
            elseif ($branch == "5") {
                $dept_code = "0107";
            }
            elseif ($branch == "7") {
                $dept_code = "0103";
            }
            elseif ($branch == "9") {
                $dept_code = "0110";
            }
            elseif ($branch == "10") {
                $dept_code = "0102";
            }
            elseif ($branch == "12") {
                $dept_code = "0108";
            }
            elseif ($branch == "13") {
                $dept_code = "0104";
            }
            else {
                $dept_code = "0001";
            }


            foreach ($emps as $emp) {
                array_push($this->employee_ids_in_my_branch, $emp->id);
                if (!array_key_exists($dept_code, $this->employee_branch_names)) {
//                    $this->employee_branch_names[$dept_code] = [$dept_code => $emp->id];
                    $this->employee_branch_names[$dept_code] = [$emp->id];
                }
                else {
                    $this->employee_branch_names[$dept_code][] = $emp->id;
//                    $this->employee_ids_in_my_branch[$branch][] = $emp->id;
//                    array_push($this->employee_ids_in_my_branch[$branch][], $emp->id);
                }
            }
        }

//        dd($this->employee_branch_names);
//        dd($this->employee_ids_in_my_branch);

        if (count($this->dept_id) == 1 && $this->dept_id[0] == "-1") {
            $this->dept_id = $this->user_branches;
        }

//        if ($this->filter_type == 'vendor') {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//                'vendor_id' => 'required|not_in:-1'
//            ]);
//        }
//        else if ($this->filter_type == 'product') {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//                'prod_id' => 'required'
//            ]);
//        }
//        else {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//            ]);
//        }

        $this->emit('show-container');
        $this->btn_generate = false;
        $this->btn_save = true;

        if ($this->query->user_group->write_product_target == '2' || $this->query->user_group->write_product_target == '3' || $this->query->user_group->write_product_target == '0') {
            $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
//                ->where('branches', 'LIKE' ,'%"'.$this->dept_id.'"%')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'emp_code')
                ->distinct()
                ->get();

            foreach ($this->emps as $emp) {
                $e[$emp->emp_code] = $emp->id;
                array_push($this->user_ids, [$emp->emp_code => $emp->id]);
            }
//            $this->user_ids = array_merge(...array_values($this->user_ids));

            $results = [];
            array_walk_recursive($this->user_ids, function ($item, $key) use (&$results){$results[$key] = $item;});
            $this->user_ids = $results;
//            dd($this->user_ids);
        }
        if ($this->query->user_group->write_product_target == '1') {
            $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
                ->whereNotNull('group')
                ->where('role', 'u')
                ->where('users.id', Auth::id())
                ->whereIn('write_product_target', ['1', '2'])
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
                ->select('users.id', 'users.name', 'emp_code')
                ->distinct()
                ->get();

            foreach ($this->emps as $emp) {
                $e[$emp->emp_code] = $emp->id;
                array_push($this->user_ids, [$emp->emp_code => $emp->id]);
            }
//            $this->user_ids = array_merge(...array_values($this->user_ids));

            $results = [];
            array_walk_recursive($this->user_ids, function ($item, $key) use (&$results){$results[$key] = $item;});
            $this->user_ids = $results;
//            dd($this->user_ids);
        }

        // old data from Scribe
        $this->list = [];
        $this->keys = [];
        $this->old_targets = [];

        for ($i = 0; $i < 12; $i++) {

            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');

            $this->list[$year][] = $month;
        }
//        dd($this->list);

        $this->keys = array_keys($this->list);
        if (count($this->keys) > 1) {

//            $stmt = "SELECT [Date] ,[Department] ,[month] ,[Year] ,ProductMast.[Pricelist] ,[ProductNo], ProductMast.Code,[Taget] ,[Revision]
//                        FROM ProductsTarget, ProductMast
//                        WHERE ProductMast.NodeNo = ProductsTarget.ProductNo
//                        and ((Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]]).")))
//                        and Department = ". $this->dept_id[0] ."
//                        and ProductMast.[Pricelist] = 1
//                        and (Taget <> 0 or Revision <> 0)
//                        order by Date desc";


            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
                ->whereRaw("((Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]]).")))")
                ->whereRaw("branch = ". $this->dept_id[0])
                ->select('product_id', 'month', 'year', 'branch', 'target')
                ->get();

//            dd($this->old_targets);


//            dd($stmt);
//
//            $query = DB::connection('sqlsrv')->select($stmt);
//
//            $fetch_query = json_decode(json_encode($query), true);
//            $this->old_targets = collect($fetch_query);



        }
        else {

//            $stmt = "SELECT [Date] ,[Department] ,[month] ,[Year] ,ProductMast.[Pricelist] ,[ProductNo], ProductMast.Code,[Taget] ,[Revision]
//                        FROM ProductsTarget, ProductMast
//                        WHERE ProductMast.NodeNo = ProductsTarget.ProductNo
//                        and (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))
//                        and Department = ". $this->dept_id[0] ."
//                        and ProductMast.[Pricelist] = 1
//                        and (Taget <> 0 or Revision <> 0)
//                        order by Date desc";
//            dd($stmt);

            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
                ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
                ->whereRaw("branch = ". $this->dept_id[0])
                ->select('product_id', 'month', 'year', 'branch', 'target')
                ->get();

//            $query = DB::connection('sqlsrv')->select($stmt);
//
//            $fetch_query = json_decode(json_encode($query), true);
//            $this->old_targets = collect($fetch_query);

        }

        // End of getting old data from Scribe

        // target data from reporting
        $this->current_target = ProductTarget::where('user_id', Auth::id())
            ->where('branch', $this->dept_id[0])
            ->get();

//        $this->emps_percentage = ProductTargetEmpPercent::join('users', 'product_target_emp_percents.user_id', 'users.id')
////            ->where('branch', $this->dept_id[0])
//            ->whereIn('users.id', $this->employee_ids_in_my_branch)
//            ->select('emp_percentage', 'branch', 'emp_code', 'product_target_emp_percents.user_id')
//            ->get();
        $this->emps_percentage = ProductTargetEmpPercent::join('users', 'product_target_emp_percents.user_id', 'users.id')
            ->whereIn('users.id', $this->employee_ids_in_my_branch)
            ->select('emp_percentage', 'branch', 'emp_code', 'product_target_emp_percents.user_id')
            ->get();
//        dd($this->employee_ids_in_my_branch);
//        dd($this->emps_percentage);

        $this->special_product_id = SpecialProduct::all();

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
        $month_stmt = "SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, LeadTime, WholeSale, MaxDiscount, Retail FROM (
            SELECT ProductMast.NodeNo as ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, LeadTime, WholeSale, MaxDiscount, Retail  FROM (
            SELECT ProductNo";


        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');
                $month_stmt .= ", SUM(case when voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59' then svalue else 0 end) as 'month" . $month_counter . "'";

                $month_counter++;
            }
        }

        $current_dept_id = $this->dept_id[0];

        if ($this->dept_id == "3") {
            $current_dept_id = "3 , 509";
        }
        elseif ($this->dept_id == "10") {
            $current_dept_id = "10, 510";
        }
        elseif ($this->dept_id == "12") {
            $current_dept_id = "12, 515";
        }



        /*$month_stmt .= " FROM (
            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE VendorNo = '" . $this->vendor_id . "'
            ORDER BY VendorNo";*/

        // cat_type
        $bathoor = "ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%' ";
        $asmedah = "ProductCode like '17%' ";
        $mobedat = "ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%' ";
        $other = "(ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%') ";

        $cat_stmt = "AND (";
        foreach ($this->cat_type as $key => $cat) {
            if ($key === array_key_first($this->cat_type)) {
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
            elseif ($key === array_key_last($this->cat_type)) {
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
        // end of cat_type

//        if ($this->filter_type == 'vendor') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE VendorNo = '" . $this->vendor_id . "' ";
//            if (in_array('sp_all', $this->sp_type) == false) {
//                $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
//            }
//            if ($this->vendor_type != 'vendor_all') {
//                $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
//            }
//            if (in_array('cat_all', $this->cat_type) == false) {
//                $month_stmt .= $cat_stmt;
//            }
//            $month_stmt .= " ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'product') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE ProductCode = '" . $this->prod_id . "'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp0') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '0'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp1') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '1'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp2') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '2'
//            ORDER BY VendorNo";
//        }
//        else if($this->filter_type == 'bathoor') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'mobedat') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'asmedah') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '17%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'other') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%')
//            ORDER BY VendorNo";
//        }
//        else if($this->filter_type == "all") {
//            // for all products
//            $month_stmt .= " FROM (
//                SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//                  FROM [AccountsC5].[dbo].[SInvoice], accmast
//                where partyno=nodeno and accmast.[type]=10
//                and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//                and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//                union all
//                SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//                  FROM [AccountsC5].[dbo].[PInvoice], accmast
//                where partyno=nodeno and accmast.[type]=10
//                and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//                and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//                ) as tbl
//                group by ProductNo) as tbl2
//                RIGHT JOIN ProductMast
//                on tbl2.ProductNo = ProductMast.NodeNo
//                WHERE ProductMast.Pricelist = 1) as tbl3
//                LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//                ORDER BY VendorNo";
//        }

        $month_stmt .= " FROM (
            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode is not null ";
        if (in_array('sp_all', $this->sp_type) == false) {
            $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
        }
        if ($this->vendor_type != 'vendor_all') {
            $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
        }
        if (in_array('cat_all', $this->cat_type) == false) {
            $month_stmt .= $cat_stmt;
        }
        $month_stmt .= " ORDER BY VendorNo";

//        dd($month_stmt);

        $query = DB::connection('sqlsrv')->select($month_stmt);
//        dd($query);
        $fetch_query = json_decode(json_encode($query), true);

//        dd($fetch_query);
        array_push($this->results, $fetch_query);

//        dd($this->results);
//        dd(array_values($this->results));


        // get the current targets
        $current_start_selected_month = Carbon::parse($this->selected_month)->format('Y-m-d');
        $current_end_selected_month = Carbon::parse($this->selected_month)->addMonths(12)->format('Y-m-d');

        $this->current_start_selected_month_exploded = explode('-', $current_start_selected_month);
        $this->current_end_selected_month_exploded = explode('-', $current_end_selected_month);

//        dd($current_end_selected_month_exploded[0]);


        $this->current_target_to_edit = [];
        if (count($this->current_year_list) == 1) {
//            $this->current_target_to_edit = ProductTarget::where('user_id', Auth::id())
            $this->current_target_to_edit = ProductTarget::where('branch', $this->dept_id)
                ->where(function ($query) {
                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
                        ->where('branch', $this->dept_id)
                        ->whereIn('user_id', $this->user_ids)
//                        ->where('user_id', Auth::id())
//                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
                })
                ->get();
        }
        elseif (count($this->current_year_list) > 1) {
            $this->current_target_to_edit = ProductTarget::where('branch', $this->dept_id)
                ->where(function ($query) {
                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
                        ->where('branch', $this->dept_id)
                        ->whereIn('user_id', $this->user_ids)
//                        ->where('user_id', Auth::id())
//                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
                    ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
                })
                ->orWhere(function ($query) {
                    $query->where('year' ,$this->current_end_selected_month_exploded[0])
                        ->where('branch', $this->dept_id)
                        ->whereIn('user_id', $this->user_ids)
//                        ->where('user_id', Auth::id())
//                        ->where('month', '<=', $this->current_end_selected_month_exploded[1]);
//                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
                        ->whereIn('month', array_values($this->current_year_list[array_key_last($this->current_year_list)]));
                })
                ->get();
        }

//        dd($this->current_year_list[array_key_first($this->current_year_list)]);
//        dd(count($this->current_year_list));
//        dd($this->current_target_to_edit);
//
//        dd('start:' . $current_start_selected_month . '| end:'. $current_end_selected_month);

        $this->show_msg = true;
        $this->emit('finished');

    }

    public function generateBranchesReport()
    {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
        $this->emps = [];
        $this->employee_branch_names = [];
//        $this->resetExcept(['branches', 'dept_id', 'filter_type', 'vendor_id']);
//        dd($this->vendor_id);
        $this->reset('target');
        $this->validate();

        $this->save_filters();

        $this->employee_ids_in_my_branch = [];
        foreach ($this->dept_id as $branch) {
            $emps = User::join('user_groups', 'user_groups.id', 'users.group')
                ->where('branches', 'like', '%"'.$branch.'"%')
                ->whereIn('write_product_target', ['1', '2'])
                ->select('users.id')
                ->get();

            $dept_code = "";

            if ($branch == "3") {
                $dept_code = "0101";
            }
            elseif ($branch == "6") {
                $dept_code = "0106";
            }
            elseif ($branch == "4") {
                $dept_code = "0105";
            }
            elseif ($branch == "11") {
                $dept_code = "0109";
            }
            elseif ($branch == "8") {
                $dept_code = "0111";
            }
            elseif ($branch == "505") {
                $dept_code = "0112";
            }
            elseif ($branch == "5") {
                $dept_code = "0107";
            }
            elseif ($branch == "7") {
                $dept_code = "0103";
            }
            elseif ($branch == "9") {
                $dept_code = "0110";
            }
            elseif ($branch == "10") {
                $dept_code = "0102";
            }
            elseif ($branch == "12") {
                $dept_code = "0108";
            }
            elseif ($branch == "13") {
                $dept_code = "0104";
            }
            else {
                $dept_code = "0001";
            }


            foreach ($emps as $emp) {
                array_push($this->employee_ids_in_my_branch, $emp->id);
                if (!array_key_exists($dept_code, $this->employee_branch_names)) {
//                    $this->employee_branch_names[$dept_code] = [$dept_code => $emp->id];
                    $this->employee_branch_names[$dept_code] = [$emp->id];
                }
                else {
                    $this->employee_branch_names[$dept_code][] = $emp->id;
//                    $this->employee_ids_in_my_branch[$branch][] = $emp->id;
//                    array_push($this->employee_ids_in_my_branch[$branch][], $emp->id);
                }
            }
        }

//        dd($this->employee_branch_names);
//        dd($this->employee_ids_in_my_branch);


//        if ($this->filter_type == 'vendor') {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//                'vendor_id' => 'required|not_in:-1'
//            ]);
//        }
//        else if ($this->filter_type == 'product') {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//                'prod_id' => 'required'
//            ]);
//        }
//        else {
//            $this->validate([
//                'dept_id' => 'required|not_in:-1',
//                'selected_month' => 'required',
//            ]);
//        }

        $this->emit('show-container');
        $this->btn_generate = false;
        $this->btn_save = true;

//        if ($this->query->user_group->write_product_target == '2' || $this->query->user_group->write_product_target == '3' || $this->query->user_group->write_product_target == '0') {
//            $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
////                ->where('branches', 'LIKE' ,'%"'.$this->dept_id.'"%')
//                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
//                ->whereNotNull('group')
//                ->where('role', 'u')
//                ->whereIn('write_product_target', ['1', '2'])
////                ->where('group', '!=', 4)
////                ->where('group', '!=', 5)
////                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
//                ->select('users.id', 'users.name', 'emp_code')
//                ->distinct()
//                ->get();
//
//            foreach ($this->emps as $emp) {
//                $e[$emp->emp_code] = $emp->id;
//                array_push($this->user_ids, [$emp->emp_code => $emp->id]);
//            }
////            $this->user_ids = array_merge(...array_values($this->user_ids));
//
//            $results = [];
//            array_walk_recursive($this->user_ids, function ($item, $key) use (&$results){$results[$key] = $item;});
//            $this->user_ids = $results;
////            dd($this->user_ids);
//        }
//        if ($this->query->user_group->write_product_target == '1') {
//            $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
//                ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
//                ->whereNotNull('group')
//                ->where('role', 'u')
//                ->where('users.id', Auth::id())
//                ->whereIn('write_product_target', ['1', '2'])
////                ->where('group', '!=', 4)
////                ->where('group', '!=', 5)
////                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
//                ->select('users.id', 'users.name', 'emp_code')
//                ->distinct()
//                ->get();
//
//            foreach ($this->emps as $emp) {
//                $e[$emp->emp_code] = $emp->id;
//                array_push($this->user_ids, [$emp->emp_code => $emp->id]);
//            }
////            $this->user_ids = array_merge(...array_values($this->user_ids));
//
//            $results = [];
//            array_walk_recursive($this->user_ids, function ($item, $key) use (&$results){$results[$key] = $item;});
//            $this->user_ids = $results;
////            dd($this->user_ids);
//        }


        $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
//                ->where('branches', 'LIKE' ,'%"'.$this->dept_id.'"%')
//            ->where('branches', 'LIKE' ,'%"'.$this->dept_id[0].'"%')
            ->whereNotNull('group')
            ->where('role', 'u')
            ->whereIn('write_product_target', ['1', '2'])
            ->whereIn('users.id', $this->employee_ids_in_my_branch)
//                ->where('group', '!=', 4)
//                ->where('group', '!=', 5)
//                ->whereNotIn('id', [1,13,14,15,16,18,21,38])
            ->select('users.id', 'users.name', 'emp_code')
            ->distinct()
            ->get();

        // old data from Scribe
        $this->list = [];
        $this->keys = [];
        $this->old_targets = [];

        for ($i = 0; $i < 12; $i++) {

            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');

            $this->list[$year][] = $month;
        }
//        dd($this->list);

        $this->keys = array_keys($this->list);
        if (count($this->keys) > 1) {

//            $stmt = "SELECT [Date] ,[Department] ,[month] ,[Year] ,ProductMast.[Pricelist] ,[ProductNo], ProductMast.Code,[Taget] ,[Revision]
//                        FROM ProductsTarget, ProductMast
//                        WHERE ProductMast.NodeNo = ProductsTarget.ProductNo
//                        and ((Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]]).")))
//                        and Department in (". implode(',', $this->dept_id) .")
//                        and ProductMast.[Pricelist] = 1
//                        and (Taget <> 0 or Revision <> 0)
//                        order by Date desc";

//            dd($stmt);

//            dd(implode(',', $this->dept_id));
//            dd("(Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))");
//            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
            $this->old_targets = ProductTargetBranchTotal::whereRaw("(Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
//                ->whereRaw("branch in (". implode(',', $this->dept_id) .")")
                ->whereIn("branch", $this->dept_id)
//                ->whereIn("branch", [7])
                ->select('product_id', 'month', 'year', 'branch', 'target')
                ->get();


//            dd($this->old_targets->where('product_id', '170458')->where("month", '1')->where('year', '2024')->where('branch', '7')->first());
//            dd($this->old_targets);

//            $query = DB::connection('sqlsrv')->select($stmt);
//
//            $fetch_query = json_decode(json_encode($query), true);
//            $this->old_targets = collect($fetch_query);

        }
        else {

//            $stmt = "SELECT [Date] ,[Department] ,[month] ,[Year] ,ProductMast.[Pricelist] ,[ProductNo], ProductMast.Code,[Taget] ,[Revision]
//                        FROM ProductsTarget, ProductMast
//                        WHERE ProductMast.NodeNo = ProductsTarget.ProductNo
//                        and (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))
//                        and Department in (". implode(',',$this->dept_id) .")
//                        and ProductMast.[Pricelist] = 1
//                        and (Taget <> 0 or Revision <> 0)
//                        order by Date desc";
//            dd($stmt);

            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
                ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
                ->whereRaw("branch in (". implode(',',$this->dept_id) .")")
                ->select('product_id', 'month', 'year', 'branch', 'target')
                ->get();

//            $query = DB::connection('sqlsrv')->select($stmt);
//
//            $fetch_query = json_decode(json_encode($query), true);
//            $this->old_targets = collect($fetch_query);

        }

        // End of getting old data from Scribe

        // target data from reporting
        // good
//        $this->current_target = ProductTarget::whereIn('user_id', $this->employee_ids_in_my_branch)
//            ->get();

        $this->emps_percentage = ProductTargetEmpPercent::join('users', 'product_target_emp_percents.user_id', 'users.id')
            ->whereIn('users.id', $this->employee_ids_in_my_branch)
            ->select('emp_percentage', 'branch', 'emp_code', 'product_target_emp_percents.user_id')
            ->get();

//        dd($this->emps_percentage);

        $this->special_product_id = SpecialProduct::all();

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
        $month_stmt = "SELECT Department, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, LeadTime, WholeSale, MaxDiscount, Retail FROM (
            SELECT Department, ProductMast.NodeNo as ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, LeadTime, WholeSale, MaxDiscount, Retail  FROM (
            SELECT Department, ProductNo";


        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');
                $month_stmt .= ", SUM(case when voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59' then svalue else 0 end) as 'month" . $month_counter . "'";

                $month_counter++;
            }
        }

        $current_dept_id = $this->dept_id[0];

        if ($this->dept_id == "3") {
            $current_dept_id = "3 , 509";
        }
        elseif ($this->dept_id == "10") {
            $current_dept_id = "10, 510";
        }
        elseif ($this->dept_id == "12") {
            $current_dept_id = "12, 515";
        }



        /*$month_stmt .= " FROM (
            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . $current_dept_id .
            ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE VendorNo = '" . $this->vendor_id . "'
            ORDER BY VendorNo";*/

        // cat_type
        $bathoor = "ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%' ";
        $asmedah = "ProductCode like '17%' ";
        $mobedat = "ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%' ";
        $other = "(ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%') ";

        $cat_stmt = "AND (";
        foreach ($this->cat_type as $key => $cat) {
            if ($key === array_key_first($this->cat_type)) {
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
            elseif ($key === array_key_last($this->cat_type)) {
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
        // end of cat_type

//        if ($this->filter_type == 'vendor') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE VendorNo = '" . $this->vendor_id . "' ";
//            if (in_array('sp_all', $this->sp_type) == false) {
//                $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
//            }
//            if ($this->vendor_type != 'vendor_all') {
//                $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
//            }
//            if (in_array('cat_all', $this->cat_type) == false) {
//                $month_stmt .= $cat_stmt;
//            }
//            $month_stmt .= " ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'product') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE ProductCode = '" . $this->prod_id . "'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp0') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '0'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp1') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '1'
//            ORDER BY VendorNo";
//        }
//        else if ($this->filter_type == 'sp2') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE SpecialityCode = '2'
//            ORDER BY VendorNo";
//        }
//        else if($this->filter_type == 'bathoor') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'mobedat') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'asmedah') {
//
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode like '17%')
//            ORDER BY VendorNo";
//
//        }
//        else if($this->filter_type == 'other') {
//            $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE (ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%')
//            ORDER BY VendorNo";
//        }
//        else if($this->filter_type == "all") {
//            // for all products
//            $month_stmt .= " FROM (
//                SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//                  FROM [AccountsC5].[dbo].[SInvoice], accmast
//                where partyno=nodeno and accmast.[type]=10
//                and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//                and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//                union all
//                SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//                  FROM [AccountsC5].[dbo].[PInvoice], accmast
//                where partyno=nodeno and accmast.[type]=10
//                and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//                and Department in (" . $current_dept_id .
//                ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//                ) as tbl
//                group by ProductNo) as tbl2
//                RIGHT JOIN ProductMast
//                on tbl2.ProductNo = ProductMast.NodeNo
//                WHERE ProductMast.Pricelist = 1) as tbl3
//                LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//                ORDER BY VendorNo";
//        }

        $month_stmt .= " FROM (
            SELECT Department, ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[SInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . implode(',', $this->dept_id) .
            ") group by Department, ProductNo, NodeNo, Code, Arabic_Name, SIDate
            union all
            SELECT Department, ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
              FROM [AccountsC5].[dbo].[PInvoice], accmast
            where partyno=nodeno and accmast.[type]=10
            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
            and Department in (" . implode(',', $this->dept_id) .
            ") group by Department, ProductNo, NodeNo, Code, Arabic_Name, PIDate
            ) as tbl
            group by Department, ProductNo) as tbl2
            RIGHT JOIN ProductMast
            on tbl2.ProductNo = ProductMast.NodeNo
            WHERE ProductMast.Pricelist = 1) as tbl3
            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
            WHERE ProductCode is not null ";
        if (in_array('sp_all', $this->sp_type) == false) {
            $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
        }
        if ($this->vendor_type != 'vendor_all') {
            $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
        }
        if (in_array('cat_all', $this->cat_type) == false) {
            $month_stmt .= $cat_stmt;
        }
        $month_stmt .= " ORDER BY VendorNo";

//        dd($month_stmt);

        $query = DB::connection('sqlsrv')->select($month_stmt);
//        dd($query);
//        $fetch_query = json_decode(json_encode($query), true);

        $this->results = collect($query);
//        dd($query);
//        array_push($this->results, $fetch_query);

//        dd($this->results);
//        dd(array_values($this->results));


        // get the current targets
//        $current_start_selected_month = Carbon::parse($this->selected_month)->format('Y-m-d');
//        $current_end_selected_month = Carbon::parse($this->selected_month)->addMonths(12)->format('Y-m-d');
//
//        $this->current_start_selected_month_exploded = explode('-', $current_start_selected_month);
//        $this->current_end_selected_month_exploded = explode('-', $current_end_selected_month);
//
////        dd($current_end_selected_month_exploded[0]);
//
//
//        $this->current_target_to_edit = [];
//        if (count($this->current_year_list) == 1) {
//// good
////            $this->current_target_to_edit = ProductTarget::whereIn('branch', $this->dept_id)
////                ->where(function ($query) {
////                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
////                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
//////                        ->where('user_id', Auth::id())
//////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
////                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
////                })
////                ->select('product_id', 'month', 'year', 'branch', DB::raw("SUM(target) as target"))
////                ->groupBy('product_id', 'month', 'year', 'branch')
////                ->get();
//
//            $this->current_target_to_edit = ProductTargetBranchTotal::whereIn('branch', $this->dept_id)
//                ->where(function ($query) {
//                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
//                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
//                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
//                })
////                ->select('product_id', 'month', 'year', 'branch', DB::raw("SUM(target) as target"))
//                ->select('product_id', 'month', 'year', 'branch', 'target')
////                ->groupBy('product_id', 'month', 'year', 'branch')
//                ->get();
//        }
//        elseif (count($this->current_year_list) > 1) {
////            $this->current_target_to_edit = ProductTarget::whereIn('branch', $this->dept_id)
////                ->where(function ($query) {
////                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
////                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
//////                        ->where('user_id', Auth::id())
//////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
////                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
////                })
////                ->orWhere(function ($query) {
////                    $query->where('year' ,$this->current_end_selected_month_exploded[0])
////                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
//////                        ->where('user_id', Auth::id())
//////                        ->where('month', '<=', $this->current_end_selected_month_exploded[1]);
//////                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
////                        ->whereIn('month', array_values($this->current_year_list[array_key_last($this->current_year_list)]));
////                })
////                ->select('product_id', 'month', 'year', 'branch', DB::raw("SUM(target) as target"))
////                ->groupBy('product_id', 'month', 'year', 'branch')
////                ->get();
//
//            $this->current_target_to_edit = ProductTargetBranchTotal::whereIn('branch', $this->dept_id)
//                ->where(function ($query) {
//                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
//                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
//                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
//                })
//                ->orWhere(function ($query) {
//                    $query->where('year' ,$this->current_end_selected_month_exploded[0])
//                        ->whereIn('branch', $this->dept_id)
////                        ->whereIn('user_id', $this->employee_ids_in_my_branch)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '<=', $this->current_end_selected_month_exploded[1]);
////                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                        ->whereIn('month', array_values($this->current_year_list[array_key_last($this->current_year_list)]));
//                })
////                ->select('product_id', 'month', 'year', 'branch', DB::raw("SUM(target) as target"))
//                ->select('product_id', 'month', 'year', 'branch', 'target')
////                ->groupBy('product_id', 'month', 'year', 'branch')
//                ->get();
//
//        }

        $this->items = $this->filtered_products($this->cat_type, $this->sp_type, $this->vendor_type);

//        dd($this->current_year_list[array_key_first($this->current_year_list)]);
//        dd(count($this->current_year_list));
//        dd($this->current_target_to_edit);
//
//        dd('start:' . $current_start_selected_month . '| end:'. $current_end_selected_month);

        $this->show_msg = true;
        $this->emit('finished');
    }

    public function processData()
    {
        dd($this->emp_target);
        dd($this->target);

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

                            $record = ProductTargetLog::create([
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

            $this->emit('msg');
            $this->reset('target');
            $this->generateReport();
        }

    }

//    public function updatedTarget($value, $key)
//    {
////        dd($key);
//        $diff_key = "diff." . $key;
//        $emp_key = "target." . $key;
//        $product_code = explode('.', $key)[0];
//        $month = intval(explode('.', $key)[2]);
////        dd($month);
//
////        dd($product_code);
//        $search_key = array_search($product_code, array_column($this->results[0], 'ProductCode'));
////        dd($this->results[0][$search_key]);
////        dd(floatval($value)/floatval($this->results[0][$search_key]['month'.$month])*100);
////        $diff_value = 100;
//
////        dd($this->results[0][$search_key]['month' . $month]);
////        dd('month' . $month);
//        $diff_value = 0;
//        if (floatval($this->results[0][$search_key]['month' . $month]) > 0) {
//            $diff_value = number_format(floatval($value) / floatval($this->results[0][$search_key]['month' . $month]) * 100, 2);
//        }
//        elseif (number_format(floatval($this->results[0][$search_key]['month' . $month])) == "0") {
//            $diff_value = 100;
//        }
//
//        $diff_key = str_replace('.', '--', $diff_key);
//        $emp_key = str_replace('.', '--', $emp_key);
////        dd($diff_key);
//
//        $this->emit('diff-update', [$diff_key, $diff_value, $emp_key, $value]);
//    }

    public function updatedEmpsPercentage($value, $key) {
//        dd($key);
//        $this->emps_percentage;

//        dd($this->emps_percentage);
//        $total = array_sum($this->emps_percentage) - end($this->emps_percentage);
//        dd($total);
    }

    // function to save the data
    public function test($targets, $emps_percents, $products_codes, $totaltargets) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');
//        dd($totaltargets);

//        dd($this->dept_id);
//        dd($products_codes);
//        dd($emps_percents);
//        dd($this->emps_percentage);
//        dd($targets);

//        dd($this->emps_percentage);

        if ($emps_percents) {
            foreach ($emps_percents as $emp) {
                $txt = explode('|', $emp);
                $details = $txt[0];
                $percent_num = trim($txt[1]);
                $details = explode('--', $details);

                $emp_code_percent = $details[1];



                $user_record = User::where('emp_code', $emp_code_percent)->first();
                $user = ProductTargetEmpPercent::where('user_id', $user_record->id)->first();

                if ($user) {
                    $record = ProductTargetEmpPercent::where('user_id', $user_record->id)
                        ->update([
                            'emp_percentage' => $percent_num,
                            'branch' => $this->dept_id[0],
                            'added_by' => Auth::id()
                        ]);
                }
                else {
                    $record = ProductTargetEmpPercent::create([
                            'user_id' => $user_record->id,
                            'emp_percentage' => $percent_num,
                            'branch' => $this->dept_id[0],
                            'added_by' => Auth::id()
                        ]);
                }
            }
        }

        if ($products_codes) {

            foreach ($products_codes as $code) {
                $txt = explode('|', $code);
                $details = $txt[0];
                $product_bool_val = $txt[1];
//                dd($product_bool_val);
                $details = explode('--', $details);

                $product_code_val = $details[1];

                $record = SpecialProduct::where('product_id', $product_code_val)->first();

                if ($record) {
                    if ($product_bool_val == 'true') {
                        $record = SpecialProduct::where('product_id', $product_code_val)
                            ->update([
                                'product_id' => $product_code_val,
                                'added_by' => Auth::id(),
                            ]);
                    }
                    else {
                        $record = SpecialProduct::where('product_id', $product_code_val)->delete();
                    }
                }
                else {
                  if ($product_bool_val == 'true') {
                      $record = SpecialProduct::create([
                          'product_id' => $product_code_val,
                          'added_by' => Auth::id(),
                      ]);
                  }
                }
            }
        }

        if ($totaltargets) {

            foreach ($totaltargets as $target) {
                $txt = explode('|', $target);
                $details = $txt[0];
                $target_num = $txt[1];
                $details = explode('--', $details);

                $product_code = $details[1];
                $target_date = explode('-', $details[2]);
                $target_year = $target_date[0];
                $target_month = $target_date[1];
//                $emp_code = $details[4];

//                $user = User::where('emp_code', $emp_code)->first();

//                $fetch = ProductTarget::where('product_id', $product_code)
//                    ->where('month', $target_month)
//                    ->where('year', $target_year)
//                    ->where('branch', $this->dept_id[0])
//                    ->where('user_id', $user->id)
//                    ->first();

                $fetch = ProductTargetBranchTotal::where('product_id', $product_code)
                    ->where('month', $target_month)
                    ->where('year', $target_year)
                    ->where('branch', $this->dept_id[0])
//                    ->where('user_id', $user->id)
                    ->first();

                if ($fetch) {
                    $record = ProductTargetBranchTotal::where('product_id', $product_code)
                        ->where('month', $target_month)
                        ->where('year', $target_year)
                        ->where('branch', $this->dept_id[0])
//                        ->where('user_id', $user->id)
                        ->update(['target' => $target_num]);
                } else {
                    $record = ProductTargetBranchTotal::create([
                        'product_id' => $product_code,
                        'month' => $target_month,
                        'year' => $target_year,
                        'branch' => $this->dept_id[0],
//                        'user_id' => $user->id,
                        'target' => $target_num
                    ]);
                }

//                $record = ProductTargetLog::create([
//                    'product_id' => $product_code,
//                    'month' => $target_month,
//                    'year' => $target_year,
//                    'branch' => $this->dept_id[0],
//                    'user_id' => $user->id,
//                    'target' => $target_num
//                ]);

            }

            $this->emit('msg');
            $this->reset('target', 'emps_percentage', 'emp_target');
            $this->generateReport();
        }

        if ($targets) {

            foreach ($targets as $target) {
                $txt = explode('|', $target);
                $details = $txt[0];
                $target_num = $txt[1];
                $details = explode('--', $details);

                $product_code = $details[1];
                $target_date = explode('-', $details[2]);
                $target_year = $target_date[0];
                $target_month = $target_date[1];
                $emp_code = $details[4];

                $user = User::where('emp_code', $emp_code)->first();

                $fetch = ProductTarget::where('product_id', $product_code)
                    ->where('month', $target_month)
                    ->where('year', $target_year)
                    ->where('branch', $this->dept_id[0])
                    ->where('user_id', $user->id)
                    ->first();

                if ($fetch) {
                    $record = ProductTarget::where('product_id', $product_code)
                        ->where('month', $target_month)
                        ->where('year', $target_year)
                        ->where('branch', $this->dept_id[0])
                        ->where('user_id', $user->id)
                        ->update(['target' => $target_num]);
                } else {
                    $record = ProductTarget::create([
                        'product_id' => $product_code,
                        'month' => $target_month,
                        'year' => $target_year,
                        'branch' => $this->dept_id[0],
                        'user_id' => $user->id,
                        'target' => $target_num
                    ]);
                }

//                $record = ProductTargetLog::create([
//                    'product_id' => $product_code,
//                    'month' => $target_month,
//                    'year' => $target_year,
//                    'branch' => $this->dept_id[0],
//                    'user_id' => $user->id,
//                    'target' => $target_num
//                ]);

            }

            $this->emit('msg');
            $this->reset('target', 'emps_percentage', 'emp_target');
            $this->generateReport();
        }

    }

    public function clear_btn() {
        $this->reset(['emps_percentage', 'target', 'emp_target']);
        $this->emit('clear-btn');
    }

    public function filtered_products($cats, $sps, $vendors) {

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

        $stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo, accmast.Code as VendorCode, accmast.Code, accmast.Arabic_name as VendorName FROM ProductMast, accmast
WHERE ProductMast.VendorNo = accmast.NodeNo
AND Pricelist = 1 ";
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

//        $results = [];
//        array_walk_recursive($fetch_products_query, function ($item, $key) use (&$results){array_push($results, $item);});

//        dd($results);
        return $fetch_products_query;
    }

    public function get_filters() {

        $record = ProductTargetFilter::where('user_id', Auth::id())
            ->where('page', "create")
            ->first();

        if($record) {
            $this->dept_id = json_decode($record->dept_id);
            $this->cat_type = json_decode($record->cat_type);
            $this->sp_type = json_decode($record->sp_type);
            $this->vendor_type = json_decode($record->vendor_type);
            $this->selected_month = $record->selected_month ? $this->selected_month : Carbon::parse(Carbon::now())->format('Y-m');
//            dd($this->dept_id);
        }
    }

    public function save_filters() {

        $record = ProductTargetFilter::where('user_id', Auth::id())
            ->where('page', "create")
            ->first();

        if($record) {
            $a = ProductTargetFilter::where('user_id', Auth::id())
                ->where('page', "create")
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
                'page' => 'create',
            ]);
        }
    }

}
