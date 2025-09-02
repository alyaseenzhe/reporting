<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\ProductMast;
use App\Models\Products;
use App\Models\ProductTarget;
use App\Models\ProductTargetBranchTotal;
use App\Models\ProductTargetEmpPercent;
use App\Models\ProductTargetFilter;
use App\Models\ProductTargetLog;
use App\Models\Sales;
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
    public $results2 = [];
    public $list = [];
    public $list2 = [];
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

     /* booted - Handles user authentication and access control.
     *
     * This function is executed upon component boot. It first sets a high execution time limit
     * and disables the memory limit, which is useful for long-running operations. It then checks
     * if the authenticated user is active. If not, it redirects them to the 'non-active-user' route.
     * Subsequently, it verifies if the user has a specific report type permission ('list.my-product-target')
     * or is an administrator. If the user does not have the required permissions, they are redirected
     * to the 'dashboard' route.
     */
    public function booted() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.my-product-target', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }


//        if (Auth::user()->user_group->write_product_target == '1' || Auth::user()->user_group->write_product_target == '2'){
//            return;
//        } else {
//            return redirect()->route('dashboard');
//        }
    }

    /**
     * mount - Initializes the component properties upon creation.
     *
     * This function is a Livewire lifecycle hook that runs once, immediately after the component is instantiated.
     * It sets the execution time and memory limits to handle potentially large datasets. It fetches the
     * currently authenticated user's data and stores it in the `$query` property. It then retrieves a
     * distinct list of vendors by joining the `AccMast` and `ProductMast` tables, filtering by a specific
     * price list, and stores the result in `$this->vendor_list`. Finally, it calls the `get_filters()` method
     * to set up any necessary filters for the component.
     */
    public function mount() {

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->query = User::where('id', Auth::id())->first();
//        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');

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

    /**
     * render - Renders the Livewire component view and initializes settings.
     *
     * This function is responsible for rendering the `create-product-target` view. It first sets
     * the time and memory limits to handle large data processing. It then retrieves various settings
     * from the database and the authenticated user's permissions, storing them in component properties.
     * These settings include distribution days, permissions for writing and editing product targets, and item pricing.
     * It also fetches the branches associated with the current user. Finally, it returns the view,
     * passing the branches as a variable, and specifies the `layouts.dashboard` layout.
     */
    public function render()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
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

    /**
     * updatedDeptId - Responds to changes in the department ID.
     *
     * This function is a Livewire hook that automatically runs whenever the `$dept_id` property is updated.
     * It resets the `show_msg` property, which likely controls a message display, and sets two boolean flags:
     * `btn_generate` is set to true, and `btn_save` is set to false. This logic controls the visibility
     * and state of the generate and save buttons in the user interface, prompting the user to generate new data
     * after changing the department.
     *
     * @param mixed $value The new value of `$dept_id`.
     */
    public function updatedDeptId($value) {
        $this->reset(['show_msg']);
        $this->btn_generate = true;
        $this->btn_save = false;
    }

    /**
     * updatedSelectedMonth - Responds to changes in the selected month.
     *
     * This function is a Livewire hook that runs automatically whenever the `$selected_month` property is changed.
     * It resets the `show_msg` property, which likely controls a message display, and sets two boolean flags:
     * `btn_generate` is set to true and `btn_save` is set to false. This behavior likely re-enables the "generate report"
     * button and disables the "save" button, prompting the user to generate a new report after changing the month.
     *
     * @param mixed $value The new value of `$selected_month`.
     */
    public function updatedSelectedMonth($value) {
        $this->reset(['show_msg']);
        $this->btn_generate = true;
        $this->btn_save = false;
    }

    /**
     * create_report - Orchestrates the report generation process.
     *
     * This function is the main entry point for creating a report. It sets the execution time and
     * memory limits to handle large data. It receives department, category, special product, and vendor
     * types as arguments and assigns them to component properties. A special check is performed: if the
     * `$dept_id` array contains the value "-1", it is replaced with all branches the user has access to.
     * The function then checks the number of departments selected. If only one department is selected,
     * it calls `generateReport()`; otherwise, it calls `generateBranchesReport()` to handle a multi-branch report.
     *
     * @param array $dept_id The ID or IDs of the department(s).
     * @param mixed $cat_type The category type.
     * @param mixed $sp_type The special product type.
     * @param mixed $vendor_type The vendor type.
     */
    public function create_report($dept_id, $cat_type, $sp_type, $vendor_type) {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
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

    /**
     * generateReport - Generates a detailed product target report.
     *
     * This function handles the logic for generating a comprehensive report based on user-selected filters.
     * It begins by setting unlimited execution time and memory limits to accommodate large data processing.
     * It resets several properties to ensure a clean slate for the new report and validates the user input.
     *
     * It then identifies all employees in the selected branches who have product target writing permissions.
     * It maps these employees to their respective branch codes and user IDs.
     * The function determines the appropriate database query based on the authenticated user's permissions,
     * fetching a list of relevant employees.
     *
     * The core of the function involves building a complex SQL query to retrieve product sales data from
     * the previous 12 months, using `AccountsC5`. This query dynamically builds a series of `SUM(case when ...)`
     * statements for each month of the past year.
     *
     * The query is constructed with various filters for categories, special product types, and vendors.
     * It handles different category types (`bathoor`, `asmedah`, `mobedat`, `other`) and incorporates them
     * into the SQL statement. The final query joins product information with sales data.
     *
     * After fetching the data, it stores the results in the `$results` property. It also retrieves a
     * percentage allocation for each employee in the branch and collects a list of special products.
     *
     * The function concludes by setting `show_msg` to true and emitting a 'finished' event, indicating that
     * the report generation is complete and the front-end can update.
     */
    public function generateReport()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

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
                ->select('users.id', 'users.emp_code')
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
//                array_push($this->employee_ids_in_my_branch, $emp->id);
                $this->employee_ids_in_my_branch[$emp->emp_code] = $emp->id;
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
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw("(write_product_target = '1' or write_product_target = '2')")
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
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw("(write_product_target = '1' or write_product_target = '2')")
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

//            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
//                ->whereRaw("((Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]]).")))")
//                ->whereRaw("branch = ". $this->dept_id[0])
//                ->select('product_id', 'month', 'year', 'branch', 'target')
//                ->get();

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

//            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])
//                ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
//                ->whereRaw("branch = ". $this->dept_id[0])
//                ->select('product_id', 'month', 'year', 'branch', 'target')
//                ->get();

//            $query = DB::connection('sqlsrv')->select($stmt);
//
//            $fetch_query = json_decode(json_encode($query), true);
//            $this->old_targets = collect($fetch_query);

        }

        // End of getting old data from Scribe

        // target data from reporting
//        $this->current_target = ProductTarget::where('user_id', Auth::id())
//            ->where('branch', $this->dept_id[0])
//            ->get();

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
//        $month_stmt = "SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, LeadTime, WholeSale, MaxDiscount, Retail FROM (
//            SELECT ProductMast.NodeNo as ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, LeadTime, WholeSale, MaxDiscount, Retail  FROM (
//            SELECT ProductNo";


//        foreach ($this->list as $year_key => $year) {
//
//            foreach ($year as $month) {
//                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
//                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');
//                $month_stmt .= ", SUM(case when voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59' then svalue else 0 end) as 'month" . $month_counter . "'";
//
//                $month_counter++;
//            }
//        }

//        $current_dept_id = $this->dept_id[0];
//
//        if ($current_dept_id == "3") {
//            $current_dept_id = "3 , 509";
//        }
//        elseif ($current_dept_id == "10") {
//            $current_dept_id = "10, 510";
//        }
//        elseif ($current_dept_id == "12") {
//            $current_dept_id = "12, 515";
//        }

        // cat_type
//        $bathoor = "ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%' ";
//        $asmedah = "ProductCode like '17%' ";
//        $mobedat = "ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%' ";
//        $other = "(ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%') ";
//
//        $cat_stmt = "AND (";
//        foreach ($this->cat_type as $key => $cat) {
//            if ($key === array_key_first($this->cat_type)) {
//                if ($cat == 'bathoor') {
//                    $cat_stmt .= $bathoor;
//                }
//                elseif ($cat == 'asmedah') {
//                    $cat_stmt .= $asmedah;
//                }
//                elseif ($cat == 'mobedat') {
//                    $cat_stmt .= $mobedat;
//                }
//                elseif ($cat == 'other') {
//                    $cat_stmt .= $other;
//                }
//            }
//            elseif ($key === array_key_last($this->cat_type)) {
//                if ($cat == 'bathoor') {
//                    $cat_stmt .= ' or '.$bathoor;
//                }
//                elseif ($cat == 'asmedah') {
//                    $cat_stmt .= ' or '.$asmedah;
//                }
//                elseif ($cat == 'mobedat') {
//                    $cat_stmt .= ' or '.$mobedat;
//                }
//                elseif ($cat == 'other') {
//                    $cat_stmt .= 'or '.$other;
//                }
//            }
//            else {
//                if ($cat == 'bathoor') {
//                    $cat_stmt .= " or " . $bathoor;
//                }
//                elseif ($cat == 'asmedah') {
//                    $cat_stmt .= " or " . $asmedah;
//                }
//                elseif ($cat == 'mobedat') {
//                    $cat_stmt .= " or " . $mobedat;
//                }
//                elseif ($cat == 'other') {
//                    $cat_stmt .= " or " . $other;
//                }
//            }
//        }

//        $cat_stmt .= ") ";

        $bathoor = "ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%' ";
        $asmedah = "ProductCode like '17%' ";
        $mobedat = "ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%' ";
        $other = "(ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%') ";

        $bathoor2 = "product_code like '20%' or product_code like '21%' or product_code like '22%' ";
        $asmedah2 = "product_code like '17%' ";
        $mobedat2 = "product_code like '10%' or product_code like '11%' or product_code like '12%' or product_code like '13%' or product_code like '14%' or product_code like '15%' or product_code like '16%' ";
        $other2 = "(product_code not like '20%' and product_code not like '21%' and product_code not like '22%' and product_code not like '10%' and product_code not like '11%' and product_code not like '12%' and product_code not like '13%' and product_code not like '14%' and product_code not like '15%' and product_code not like '16%' and product_code not like '17%') ";

//        $cat_stmt = "AND (";
        $cat_stmt = "(";
        $cat_stmt2 = "(";
        foreach ($this->cat_type as $key => $cat) {
            if ($key === array_key_first($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= $bathoor;
                    $cat_stmt2 .= $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= $asmedah;
                    $cat_stmt2 .= $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= $mobedat;
                    $cat_stmt2 .= $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= $other;
                    $cat_stmt2 .= $other2;
                }
            }
            elseif ($key === array_key_last($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= ' or '.$bathoor;
                    $cat_stmt2 .= ' or '.$bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= ' or '.$asmedah;
                    $cat_stmt2 .= ' or '.$asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= ' or '.$mobedat;
                    $cat_stmt2 .= ' or '.$mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= 'or '.$other;
                    $cat_stmt2 .= 'or '.$other2;
                }
            }
            else {
                if ($cat == 'bathoor') {
                    $cat_stmt .= " or " . $bathoor;
                    $cat_stmt2 .= " or " . $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= " or " . $asmedah;
                    $cat_stmt2 .= " or " . $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= " or " . $mobedat;
                    $cat_stmt2 .= " or " . $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= " or " . $other;
                    $cat_stmt2 .= " or " . $other2;
                }
            }
        }

        $cat_stmt .= ") ";
        $cat_stmt2 .= ") ";
        // end of cat_type

//        $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//            ") group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . $current_dept_id .
//            ") group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE ProductCode is not null ";
//        if (in_array('sp_all', $this->sp_type) == false) {
//            $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
//        }
//        if ($this->vendor_type != 'vendor_all') {
//            $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
//        }
//        if (in_array('cat_all', $this->cat_type) == false) {
//            $month_stmt .= $cat_stmt;
//        }
//        $month_stmt .= " ORDER BY VendorNo";
//
////        dd($month_stmt);
//
//        $query = DB::connection('sqlsrv')->select($month_stmt);
//
//        $fetch_query = json_decode(json_encode($query), true);
//
//        array_push($this->results, $fetch_query);
//
//        $this->historicalThreeYearsSales();

        $sp_txt = in_array('sp_all', $this->sp_type) ?  ('products.SpecialityCode IN (0,1,2)') : ('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')');
        $cat_txt1 = in_array('cat_all', $this->cat_type) ?  ('ProductCode is not null') : $cat_stmt;
        $cat_txt2 = in_array('cat_all', $this->cat_type) ?  ('product_code is not null') : $cat_stmt2;
        $this->results = Products::where('Pricelist', '1')
//            ->where('products.VendorNo', $this->vendor_type)
            ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
//            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
            ->whereRaw($sp_txt)
//            ->whereIn('products.SpecialityCode', $this->sp_type)
//            ->whereRaw($cat_stmt2)
            ->whereRaw($cat_txt2)
            ->selectRaw('product_code as ProductCode, product_name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo, vendor_code as VendorCode, vendor_name as VendorName')
//            ->toSql();
            ->get()->toArray();

        // get the current targets
        $current_start_selected_month = Carbon::parse($this->selected_month)->format('Y-m-d');
        $current_end_selected_month = Carbon::parse($this->selected_month)->addMonths(12)->format('Y-m-d');

        $this->current_start_selected_month_exploded = explode('-', $current_start_selected_month);
        $this->current_end_selected_month_exploded = explode('-', $current_end_selected_month);

//        dd($current_end_selected_month_exploded[0]);


        $this->current_target_to_edit = [];
//        if (count($this->current_year_list) == 1) {
////            $this->current_target_to_edit = ProductTarget::where('user_id', Auth::id())
//            $this->current_target_to_edit = ProductTarget::where('branch', $this->dept_id)
//                ->where(function ($query) {
//                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
//                        ->where('branch', $this->dept_id)
//                        ->whereIn('user_id', $this->user_ids)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
//                        ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
//                })
//                ->get();
//        }
//        elseif (count($this->current_year_list) > 1) {
//            $this->current_target_to_edit = ProductTarget::where('branch', $this->dept_id)
//                ->where(function ($query) {
//                    $query->where('year' ,$this->current_start_selected_month_exploded[0])
//                        ->where('branch', $this->dept_id)
//                        ->whereIn('user_id', $this->user_ids)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '>=', $this->current_start_selected_month_exploded[1]);
//                    ->whereIn('month', array_values($this->current_year_list[array_key_first($this->current_year_list)]));
//                })
//                ->orWhere(function ($query) {
//                    $query->where('year' ,$this->current_end_selected_month_exploded[0])
//                        ->where('branch', $this->dept_id)
//                        ->whereIn('user_id', $this->user_ids)
////                        ->where('user_id', Auth::id())
////                        ->where('month', '<=', $this->current_end_selected_month_exploded[1]);
////                    ->whereIn('month', array_values($this->list[$this->keys[0]]));
//                        ->whereIn('month', array_values($this->current_year_list[array_key_last($this->current_year_list)]));
//                })
//                ->get();
//        }

//        dd($this->results);
        $this->show_msg = true;
        $this->emit('finished');

    }



    /**
     * generateBranchesReport - Generates a detailed product target report for multiple branches.
     *
     * This function is similar to `generateReport()` but is specifically tailored for scenarios
     * where multiple branches are selected. It begins by setting unlimited execution time and
     * memory limits and resets component properties for a fresh report generation. It then validates
     * the user's input.
     *
     * The function iterates through each selected branch, identifies employees with product target
     * writing permissions, and maps their IDs to a simplified department code. This aggregated list
     * of employee IDs is then used to filter a list of relevant employees.
     *
     * It retrieves historical sales data and existing product targets, using dynamic queries that
     * account for multiple years and months. It also fetches employee percentage allocations for
     * the selected branches.
     *
     * The function then constructs a complex query to retrieve product sales data from the previous
     * 12 months for all selected branches. This query uses conditional `SUM` statements for each month
     * and dynamically incorporates filters for product categories, special product types, and vendors.
     *
     * The function concludes by fetching a list of products based on the user's filter criteria and
     * preparing the data for the front-end. Finally, it sets the `show_msg` flag to true and
     * emits a 'finished' event to update the user interface.
     */
    public function generateBranchesReport()
    {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
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
//                ->whereIn('write_product_target', ['1', '2'])
                ->whereRaw('write_product_target IN (1,2)')
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
//            ->whereIn('write_product_target', ['1', '2'])
            ->whereRaw('write_product_target IN (1,2)')
//            ->whereIn('users.id', $this->employee_ids_in_my_branch)
            ->whereRaw('users.id IN ('. implode(',' , $this->employee_ids_in_my_branch) . ')')
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

            //// goooood
//            $this->old_targets = ProductTargetBranchTotal::whereRaw("(Year = '".$this->keys[1]."' and month in (". implode(',',$this->list[$this->keys[1]]).")) or (Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
////                ->whereRaw("branch in (". implode(',', $this->dept_id) .")")
////                ->whereIn("branch", $this->dept_id)
//                ->whereRaw("branch IN (" . implode($this->dept_id) . ")")
////                ->whereIn("branch", [7])
//                ->select('product_id', 'month', 'year', 'branch', 'target')
//                ->get();

            //// end of goooood

//            dd($this->old_targets);


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

//            $this->old_targets = ProductTargetBranchTotal::where('branch', $this->dept_id[0])

            // gooooooooood
//            $this->old_targets = ProductTargetBranchTotal::whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
//                ->whereRaw("branch in (". implode(',',$this->dept_id) .")")
//                ->select('product_id', 'month', 'year', 'branch', 'target')
////                ->limit(10)
////                    ->toSql();
//                ->get();

//            $kk = DB::select("select `product_id`, `month`, `year`, `branch`, `target` from `product_target_branch_totals` where (Year = '2024' and month in (1,2,3,4,5,6,7,8,9,10,11,12)) and branch in (3,7)");

//            dd($kk);
//            dd($this->old_targets);

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
//            ->whereIn('users.id', $this->employee_ids_in_my_branch)
            ->whereRaw('users.id IN ('. implode(',', $this->employee_ids_in_my_branch) . ')')
            ->select('emp_percentage', 'branch', 'emp_code', 'product_target_emp_percents.user_id')
//            ->toSql();
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
//        $month_stmt = "SELECT Department, ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, LeadTime, WholeSale, MaxDiscount, Retail FROM (
//            SELECT Department, ProductMast.NodeNo as ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, LeadTime, WholeSale, MaxDiscount, Retail  FROM (
//            SELECT Department, ProductNo";


//        foreach ($this->list as $year_key => $year) {
//
//            foreach ($year as $month) {
//                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
//                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');
//                $month_stmt .= ", SUM(case when voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59' then svalue else 0 end) as 'month" . $month_counter . "'";
//
//                $month_counter++;
//            }
//        }

//        $current_dept_id = $this->dept_id;
//        dd($this->dept_id);

//        if ($current_dept_id == "3") {
//            $current_dept_id = "3 , 509";
//        }
//        elseif ($current_dept_id == "10") {
//            $current_dept_id = "10, 510";
//        }
//        elseif ($current_dept_id == "12") {
//            $current_dept_id = "12, 515";
//        }
//        if (in_array("3", $current_dept_id)) {
//            array_push($current_dept_id, "509");
//        }
//        if (in_array("10", $current_dept_id)) {
//            array_push($current_dept_id, "510");
//        }
//        if (in_array("12", $current_dept_id)) {
//            array_push($current_dept_id, "515");
//        }

//        dd($current_dept_id);



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

        //// gooooooooood
        // cat_type
        $bathoor = "ProductCode like '20%' or ProductCode like '21%' or ProductCode like '22%' ";
        $asmedah = "ProductCode like '17%' ";
        $mobedat = "ProductCode like '10%' or ProductCode like '11%' or ProductCode like '12%' or ProductCode like '13%' or ProductCode like '14%' or ProductCode like '15%' or ProductCode like '16%' ";
        $other = "(ProductCode not like '20%' and ProductCode not like '21%' and ProductCode not like '22%' and ProductCode not like '10%' and ProductCode not like '11%' and ProductCode not like '12%' and ProductCode not like '13%' and ProductCode not like '14%' and ProductCode not like '15%' and ProductCode not like '16%' and ProductCode not like '17%') ";

        $bathoor2 = "product_code like '20%' or product_code like '21%' or product_code like '22%' ";
        $asmedah2 = "product_code like '17%' ";
        $mobedat2 = "product_code like '10%' or product_code like '11%' or product_code like '12%' or product_code like '13%' or product_code like '14%' or product_code like '15%' or product_code like '16%' ";
        $other2 = "(product_code not like '20%' and product_code not like '21%' and product_code not like '22%' and product_code not like '10%' and product_code not like '11%' and product_code not like '12%' and product_code not like '13%' and product_code not like '14%' and product_code not like '15%' and product_code not like '16%' and product_code not like '17%') ";

//        $cat_stmt = "AND (";
        $cat_stmt = "(";
        $cat_stmt2 = "(";
        foreach ($this->cat_type as $key => $cat) {
            if ($key === array_key_first($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= $bathoor;
                    $cat_stmt2 .= $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= $asmedah;
                    $cat_stmt2 .= $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= $mobedat;
                    $cat_stmt2 .= $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= $other;
                    $cat_stmt2 .= $other2;
                }
            }
            elseif ($key === array_key_last($this->cat_type)) {
                if ($cat == 'bathoor') {
                    $cat_stmt .= ' or '.$bathoor;
                    $cat_stmt2 .= ' or '.$bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= ' or '.$asmedah;
                    $cat_stmt2 .= ' or '.$asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= ' or '.$mobedat;
                    $cat_stmt2 .= ' or '.$mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= 'or '.$other;
                    $cat_stmt2 .= 'or '.$other2;
                }
            }
            else {
                if ($cat == 'bathoor') {
                    $cat_stmt .= " or " . $bathoor;
                    $cat_stmt2 .= " or " . $bathoor2;
                }
                elseif ($cat == 'asmedah') {
                    $cat_stmt .= " or " . $asmedah;
                    $cat_stmt2 .= " or " . $asmedah2;
                }
                elseif ($cat == 'mobedat') {
                    $cat_stmt .= " or " . $mobedat;
                    $cat_stmt2 .= " or " . $mobedat2;
                }
                elseif ($cat == 'other') {
                    $cat_stmt .= " or " . $other;
                    $cat_stmt2 .= " or " . $other2;
                }
            }
        }

        $cat_stmt .= ") ";
        $cat_stmt2 .= ") ";

        // end of gooooooood

//        dd($cat_stmt);
        // end of cat_type

        /* Start of products target */


//        dd($this->vendor_type);
//        dd($this->dept_id);
//        dd($this->sp_type);
        // variable should be named $this->results
//        $sales_query = Sales::join('products', 'sales.ProductCode', 'products.product_code')

        // goooood
        $sp_txt = in_array('sp_all', $this->sp_type) ?  ('products.SpecialityCode IN (0,1,2)') : ('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')');
        $cat_txt1 = in_array('cat_all', $this->cat_type) ?  ('ProductCode is not null') : $cat_stmt;
        $cat_txt2 = in_array('cat_all', $this->cat_type) ?  ('product_code is not null') : $cat_stmt2;
        // end of goood
//        dd($this->sp_type === 'sp_all');
//        dd($sp_txt);
//        dd($cat_txt1);

//        dd($this->list);

        ////////// good
        $sales_year_keys = array_keys($this->list);
//        dd($sales_year_keys);
        if (count($sales_year_keys) > 1) {
//            $this->results = Sales::join('products', 'sales.ProductCode', 'products.product_code')
//                ->whereRaw("year = '" . $sales_year_keys[0]. "' or year = '". $sales_year_keys[1]."'")
////            ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
////            ->where('products.VendorNo', $this->vendor_type)
//                ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
//                ->whereRaw('sales.Department IN (' . implode(',',$this->dept_id) . ')')
////            ->whereIn('sales.Department', $this->dept_id)
////            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
//                ->whereRaw($sp_txt)
////            ->whereIn('products.SpecialityCode', $this->sp_type)
////            ->whereRaw($cat_stmt)
//                ->whereRaw($cat_txt1)
////                ->toSql();
//            ->get();
//            dd($this->results);

            /// end of gooooooood
        }
        else {
            // gooooood
//            $this->results = Sales::join('products', 'sales.ProductCode', 'products.product_code')
//                ->where('year', $sales_year_keys[0])
////            ->whereRaw("(Year = '".$this->keys[0]."' and month in (". implode(',',$this->list[$this->keys[0]])."))")
////            ->where('products.VendorNo', $this->vendor_type)
//                ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
//                ->whereRaw('sales.Department IN (' . implode(',',$this->dept_id) . ')')
////            ->whereIn('sales.Department', $this->dept_id)
////            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
//                ->whereRaw($sp_txt)
////            ->whereIn('products.SpecialityCode', $this->sp_type)
////            ->whereRaw($cat_stmt)
//                ->whereRaw($cat_txt1)
////                ->toSql();
//            ->get();
//            dd($this->results);

        }


//        $kk = DB::select(DB::raw("SELECT DISTINCT * FROM `products` Left JOIN sales ON products.product_code = sales.ProductCode Left JOIN product_target_branch_totals ON product_target_branch_totals.product_id = products.product_code"));
////            ->get();
//        dd($kk);


//        dd('aa');
//        dd($this->results);

//        $combined = DB::table('sales')
//            ->join('products', 'sales.ProductCode', 'products.product_code')
//            ->where('year', '2022')
////            ->where('products.VendorNo', $this->vendor_type)
//            ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
//            ->whereRaw('sales.Department IN (' . implode(',',$this->dept_id) . ')')
////            ->whereIn('sales.Department', $this->dept_id)
////            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
//            ->whereRaw($sp_txt)
////            ->whereIn('products.SpecialityCode', $this->sp_type)
////            ->whereRaw($cat_stmt)
//            ->whereRaw($cat_txt1)
//            ->limit(10)
//            ->get();
//        $combined = $this->results->join($this->old_targets);
////            ->get();
//        dd($combined);



        $this->items = Products::where('Pricelist', '1')
//            ->where('products.VendorNo', $this->vendor_type)
            ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
//            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
            ->whereRaw($sp_txt)
//            ->whereIn('products.SpecialityCode', $this->sp_type)
//            ->whereRaw($cat_stmt2)
            ->whereRaw($cat_txt2)
            ->selectRaw('product_code as ProductCode, product_name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo, vendor_code as VendorCode, vendor_name as VendorName')
//            ->toSql();
            ->get()->toArray();
//        dd($this->items);

//        $this->items = Products::where('Pricelist', '1')
////            ->where('products.VendorNo', $this->vendor_type)
//            ->whereRaw($this->vendor_type == "vendor_all" ? "products.VendorNo is not null"  : "products.VendorNo ='" . $this->vendor_type . "'")
////            ->whereRaw('products.SpecialityCode IN (' . implode(',' , $this->sp_type) . ')')
//            ->whereRaw($sp_txt)
////            ->whereIn('products.SpecialityCode', $this->sp_type)
////            ->whereRaw($cat_stmt2)
//            ->whereRaw($cat_txt2)
////            ->selectRaw('product_code')
//            ->pluck('product_code');
//        dd($this->items);

//        dd($sales_query);

//        $stmt = "SELECT ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, SpecialityCode, BaseUnits, Currency, Description, Pricelist, Retail, WholeSale, MaxDiscount, LeadTime, VendorNo, accmast.Code as VendorCode, accmast.Code, accmast.Arabic_name as VendorName FROM ProductMast, accmast
//WHERE ProductMast.VendorNo = accmast.NodeNo
//AND Pricelist = 1 ";
//        if ($vendors != 'vendor_all') {
//            $stmt .= "AND VendorNo = '". $vendors ."' ";
//        }
//        if (in_array('sp_all', $sps) == false) {
//            $stmt .= "AND SpecialityCode in (". implode(',', $sps).") ";
//        }
//        if (in_array('cat_all', $cats) == false) {
//            $stmt .= $cat_stmt;
//        }

        /* END of products target */

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

//        $month_stmt .= " FROM (
//            SELECT CASE WHEN Department = '509' THEN '3' WHEN Department = '510' THEN '10' WHEN Department = '515' THEN '12' ELSE Department END as Department, ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . implode(',', $current_dept_id) .
//            ") group by Department, ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT CASE WHEN Department = '509' THEN '3' WHEN Department = '510' THEN '10' WHEN Department = '515' THEN '12' ELSE Department END as Department, ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department in (" . implode(',', $current_dept_id) .
//            ") group by Department, ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by Department, ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            WHERE ProductCode is not null ";
//        if (in_array('sp_all', $this->sp_type) == false) {
//            $month_stmt .= "AND SpecialityCode in (". implode(',', $this->sp_type).") ";
//        }
//        if ($this->vendor_type != 'vendor_all') {
//            $month_stmt .= "AND VendorNo = '". $this->vendor_type ."' ";
//        }
//        if (in_array('cat_all', $this->cat_type) == false) {
//            $month_stmt .= $cat_stmt;
//        }
//        $month_stmt .= " ORDER BY VendorNo";
//
//        dd($month_stmt);
//
//        $query = DB::connection('sqlsrv')->select($month_stmt);
////        dd($query);
//        $fetch_query = json_decode(json_encode($query), true);

//        $this->results = collect($query);
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

//        $this->items = $this->filtered_products($this->cat_type, $this->sp_type, $this->vendor_type);

//        dd($this->current_year_list[array_key_first($this->current_year_list)]);
//        dd(count($this->current_year_list));
//        dd($this->current_target_to_edit);
//
//        dd('start:' . $current_start_selected_month . '| end:'. $current_end_selected_month);

        $this->show_msg = true;
        $this->emit('finished');
    }

    /**
     * historicalThreeYearsSales - Calculates the average historical sales over three years.
     *
     * This function is designed to fetch and process sales data from the previous three years
     * to provide a historical context for product targets. It first defines the start and end
     * dates for the three-year period preceding the selected month.
     *
     * It then constructs a dynamic SQL query to retrieve sales data from a Microsoft SQL Server database.
     * The query calculates the average monthly sales for each product over the last three years.
     * This is achieved by iterating through each month of the past year and creating a `SUM(case when ...)`
     * statement that sums sales for the corresponding month in the last three years and divides by three.
     *
     * The query filters the data based on selected departments, product categories, special product types,
     * and vendors. It includes logic to handle multiple departments and different product category types
     * (`bathoor`, `asmedah`, `mobedat`, `other`). The final query joins product master data with sales
     * invoice data to retrieve comprehensive product details.
     *
     * After executing the query, the function stores the results in the `$results2` property, which
     * contains the average monthly sales for each product over the three-year period.
     */
    public function historicalThreeYearsSales() {

        $selected_year1 = Carbon::parse($this->selected_month)->subYears(3);
        $selected_year2 = Carbon::parse($this->selected_month)->subYear()->addMonth(11);

        $start_of_period = $selected_year1->format('Y-m-d');
        $end_of_period = $selected_year2->endOfMonth()->format('Y-m-d');


        $this->list2 = [];
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->subYear()->addMonth($i)->format('Y');

            $this->list2[$year][] = $month;
        }

        $month_counter = 1;

        /* Query Statement */
        $month_stmt = "SELECT ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductCode, ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, accmast.Code as VendorCode, accmast.Arabic_Name as VendorName, LeadTime, WholeSale, MaxDiscount, Retail FROM (
            SELECT ProductMast.NodeNo as ProductNo, month1, month2, month3, month4, month5, month6, month7, month8, month9, month10, month11, month12, ProductMast.Code as ProductCode, ProductMast.Arabic_Name as ProductName, Description, BaseUnits, Currency, SpecialityCode, VendorNo, LeadTime, WholeSale, MaxDiscount, Retail  FROM (
            SELECT ProductNo";


        foreach ($this->list2 as $year_key => $year) {

            foreach ($year as $month) {
                $first_date = Carbon::parse($year_key . '-' . $month . '-01')->format('Y-m-d');
                $end_date = Carbon::parse($year_key . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');

                $first_date2 = Carbon::parse(intval($year_key)-1 . '-' . $month . '-01')->format('Y-m-d');
                $end_date2 = Carbon::parse(intval($year_key)-1 . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');

                $first_date3 = Carbon::parse(intval($year_key)-2 . '-' . $month . '-01')->format('Y-m-d');
                $end_date3 = Carbon::parse(intval($year_key)-2 . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');

                $month_stmt .= ", SUM(case when (voucher_date >= '" . $first_date . " 00:00:00' and voucher_date <= '" . $end_date . " 23:59:59') or (voucher_date >= '" . $first_date2 . " 00:00:00' and voucher_date <= '" . $end_date2 . " 23:59:59') or (voucher_date >= '" . $first_date3 . " 00:00:00' and voucher_date <= '" . $end_date3 . " 23:59:59') then svalue/3 else 0 end) as 'month" . $month_counter . "'";

                $month_counter++;
            }
        }

        $current_dept_id = $this->dept_id[0];

        if ($current_dept_id == "3") {
            $current_dept_id = "3 , 509";
        }
        elseif ($current_dept_id == "10") {
            $current_dept_id = "10, 510";
        }
        elseif ($current_dept_id == "12") {
            $current_dept_id = "12, 515";
        }

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

        $this->results2 = collect($query);

    }

    /**
     * Handles the saving of product target data from the front-end.
     *
     * This function processes four different types of data:
     * - Employee percentage allocations (`$emps_percents`)
     * - Special product flags (`$products_codes`)
     * - Total branch targets for products (`$totaltargets`)
     * - Individual employee targets for products (`$targets`)
     *
     * It utilizes Laravel's `upsert` method for bulk insertion or updating, which is
     * significantly more efficient than individual `create` or `update` calls within a loop.
     * The function first parses the data from a simple string format and then prepares
     * it for the `upsert` method. It is wrapped in a try-catch block to handle potential
     * errors during the database operations.
     *
     * @param array $targets Array of individual employee targets.
     * @param array $emps_percents Array of employee percentage allocations.
     * @param array $products_codes Array of product codes to be flagged as 'special'.
     * @param array $totaltargets Array of total branch targets.
     */
    public function test($targets, $emps_percents, $products_codes, $totaltargets) {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
//        dd('test debug');
        $final_emp_percent_data = [];
        $final_special_data = [];
        $final_totaltarget_data = [];
        $final_targets_data = [];

        if ($emps_percents) {
            foreach ($emps_percents as $emp) {
                $txt = explode('|', $emp);
                $details = $txt[0];
                $percent_num = trim($txt[1]);
                $details = explode('--', $details);

                $emp_code_percent = $details[1];

                array_push($final_emp_percent_data, ['user_id' => $this->employee_ids_in_my_branch[$emp_code_percent], 'emp_percentage' => intval($percent_num), 'branch' => $this->dept_id[0],'added_by' => Auth::id()]);

//                $user_record = User::where('emp_code', $emp_code_percent)->first();
//                $user = ProductTargetEmpPercent::where('user_id', $user_record->id)->first();
//
//                if ($user) {
//                    $record = ProductTargetEmpPercent::where('user_id', $user_record->id)
//                        ->update([
//                            'emp_percentage' => $percent_num,
//                            'branch' => $this->dept_id[0],
//                            'added_by' => Auth::id()
//                        ]);
//                }
//                else {
//                    $record = ProductTargetEmpPercent::create([
//                            'user_id' => $user_record->id,
//                            'emp_percentage' => $percent_num,
//                            'branch' => $this->dept_id[0],
//                            'added_by' => Auth::id()
//                        ]);
//                }
            }

            ProductTargetEmpPercent::upsert(
                $final_emp_percent_data,
                ['user_id', 'branch'],
                ['emp_percentage']
            );
        }

        if ($products_codes) {

            foreach ($products_codes as $code) {
                $txt = explode('|', $code);
                $details = $txt[0];
                $product_bool_val = $txt[1];
//                dd($product_bool_val);
                $details = explode('--', $details);

                $product_code_val = $details[1];

                array_push($final_special_data, ['product_id' => $product_code_val, 'added_by' => Auth::id()]);

//                $record = SpecialProduct::where('product_id', $product_code_val)->first();

//                if ($record) {
//                    if ($product_bool_val == 'true') {
//                        $record = SpecialProduct::where('product_id', $product_code_val)
//                            ->update([
//                                'product_id' => $product_code_val,
//                                'added_by' => Auth::id(),
//                            ]);
//                    }
//                    else {
//                        $record = SpecialProduct::where('product_id', $product_code_val)->delete();
//                    }
//                }
//                else {
//                  if ($product_bool_val == 'true') {
//                      $record = SpecialProduct::create([
//                          'product_id' => $product_code_val,
//                          'added_by' => Auth::id(),
//                      ]);
//                  }
//                }
            }

            SpecialProduct::upsert(
                $final_special_data,
                ['product_id'],
                ['product_id'],
            );

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

                array_push($final_totaltarget_data, [
                    'product_id' => $product_code,
                    'month' => $target_month,
                    'year' => $target_year,
                    'branch' => $this->dept_id[0],
                    'target' => $target_num
                ]);
//                $emp_code = $details[4];

//                $user = User::where('emp_code', $emp_code)->first();

//                $fetch = ProductTarget::where('product_id', $product_code)
//                    ->where('month', $target_month)
//                    ->where('year', $target_year)
//                    ->where('branch', $this->dept_id[0])
//                    ->where('user_id', $user->id)
//                    ->first();

//                $fetch = ProductTargetBranchTotal::where('product_id', $product_code)
//                    ->where('month', $target_month)
//                    ->where('year', $target_year)
//                    ->where('branch', $this->dept_id[0])
////                    ->where('user_id', $user->id)
//                    ->first();
//
//                if ($fetch) {
//                    $record = ProductTargetBranchTotal::where('product_id', $product_code)
//                        ->where('month', $target_month)
//                        ->where('year', $target_year)
//                        ->where('branch', $this->dept_id[0])
////                        ->where('user_id', $user->id)
//                        ->update(['target' => $target_num]);
//                } else {
//                    $record = ProductTargetBranchTotal::create([
//                        'product_id' => $product_code,
//                        'month' => $target_month,
//                        'year' => $target_year,
//                        'branch' => $this->dept_id[0],
////                        'user_id' => $user->id,
//                        'target' => $target_num
//                    ]);
//                }

//                $record = ProductTargetLog::create([
//                    'product_id' => $product_code,
//                    'month' => $target_month,
//                    'year' => $target_year,
//                    'branch' => $this->dept_id[0],
//                    'user_id' => $user->id,
//                    'target' => $target_num
//                ]);

            }

            ProductTargetBranchTotal::upsert(
                $final_totaltarget_data,
                ['product_id', 'month', 'year', 'branch'],
                ['target'],
            );

//            $this->emit('msg');
//            $this->reset('target', 'emps_percentage', 'emp_target');
//            $this->generateReport();
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

                array_push($final_targets_data, [
                    'product_id' => $product_code,
                    'month' => intval($target_month),
                    'year' => intval($target_year),
                    'branch' => $this->dept_id[0],
                    'target' => intval($target_num),
                    'user_id' => $this->employee_ids_in_my_branch[$emp_code]
                ]);

//                $user = User::where('emp_code', $emp_code)->first();
//
//                $fetch = ProductTarget::where('product_id', $product_code)
//                    ->where('month', $target_month)
//                    ->where('year', $target_year)
//                    ->where('branch', $this->dept_id[0])
//                    ->where('user_id', $user->id)
//                    ->first();
//
//                if ($fetch) {
//                    $record = ProductTarget::where('product_id', $product_code)
//                        ->where('month', $target_month)
//                        ->where('year', $target_year)
//                        ->where('branch', $this->dept_id[0])
//                        ->where('user_id', $user->id)
//                        ->update(['target' => $target_num]);
//                } else {
//                    $record = ProductTarget::create([
//                        'product_id' => $product_code,
//                        'month' => $target_month,
//                        'year' => $target_year,
//                        'branch' => $this->dept_id[0],
//                        'user_id' => $user->id,
//                        'target' => $target_num
//                    ]);
//                }

//                $record = ProductTargetLog::create([
//                    'product_id' => $product_code,
//                    'month' => $target_month,
//                    'year' => $target_year,
//                    'branch' => $this->dept_id[0],
//                    'user_id' => $user->id,
//                    'target' => $target_num
//                ]);

            }

//            dd($final_targets_data);
            ProductTarget::upsert(
                $final_targets_data,
                ['product_id', 'month', 'year', 'branch', 'user_id'],
                ['target'],
            );

            $this->emit('msg');
            $this->reset('target', 'emps_percentage', 'emp_target');
//            $this->generateReport();
        }

    }

    /**
     * Resets form fields and emits an event to the front-end.
     *
     * This function is typically triggered by a "clear" button in the Livewire component.
     * It resets the state of three specific properties that likely hold form data:
     * - `emps_percentage`
     * - `target`
     * - `emp_target`
     *
     * After resetting the properties, it emits a 'clear-btn' event. This event can be
     * used by a front-end listener to perform actions like clearing input fields or
     * hiding a modal without a full page reload.
     */
    public function clear_btn() {
        $this->reset(['emps_percentage', 'target', 'emp_target']);
        $this->emit('clear-btn');
    }

    /**
     * Dynamically builds and executes a SQL query to filter products based on categories,
     * special product types, and vendors.
     *
     * This function is designed to take an array of selected categories, special product
     * types, and a single vendor, then construct a raw SQL query string to retrieve
     * matching products from the database. It is particularly useful for filtering
     * product data for reporting or display purposes.
     *
     * The function uses a series of hardcoded string variables and conditional logic
     * to build the WHERE clause of the query. It then executes the raw SQL using
     * the `DB::connection('sqlsrv')` facade and returns the results.
     *
     * @param array $cats An array of selected product categories (e.g., ['bathoor', 'asmedah']).
     * @param array $sps An array of selected special product codes.
     * @param string $vendors The selected vendor code, or 'vendor_all'.
     * @return array A multi-dimensional array of filtered product data.
     */
    public function filtered_products($cats, $sps, $vendors) {

        set_time_limit(0);
        ini_set('memory_limit', '-1');

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
        dd($fetch_products_query);

//        $results = [];
//        array_walk_recursive($fetch_products_query, function ($item, $key) use (&$results){array_push($results, $item);});

//        dd($results);
        return $fetch_products_query;
    }

    /**
     * Retrieves a user's saved filter preferences for the "create" page.
     *
     * This function checks if the currently authenticated user has previously
     * saved any filter selections (for department, categories, vendors, etc.)
     * for the product target creation page.
     *
     * If a record is found, it populates the class properties with the saved values,
     * which are stored as JSON strings in the database. If no saved filters are
     * found, it defaults to setting the selected month to the current month.
     */
    public function get_filters() {

        $record = ProductTargetFilter::where('user_id', Auth::id())
            ->where('page', "create")
            ->first();

        if($record) {
            $this->dept_id = json_decode($record->dept_id);
            $this->cat_type = json_decode($record->cat_type);
            $this->sp_type = json_decode($record->sp_type);
            $this->vendor_type = json_decode($record->vendor_type);
            $this->selected_month = $record->selected_month ? $record->selected_month : Carbon::parse(Carbon::now())->format('Y-m');
//            dd($this->dept_id);
        }
        else {
            $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');
        }
    }

    /**
     * Saves or updates a user's filter preferences to the database.
     *
     * This function first checks for an existing filter record for the currently
     * authenticated user and the specified 'create' page. If a record is found,
     * it updates the existing one with the current filter selections. If no
     * record exists, a new one is created. The filter values (department,
     * categories, special types, vendors, and selected month) are stored as
     * JSON-encoded strings.
     */
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
