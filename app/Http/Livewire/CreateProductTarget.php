<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\ProductMast;
use App\Models\ProductTarget;
use App\Models\ProductTargetLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProductTarget extends Component
{
    public $dept_id = -1;
    public $selected_month;
    public $filter_type = "vendor";
    public $vendor_list = [];
    public $vendor_id = -1;
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

    protected $listeners = ['targets-entered' => 'test'];

//    protected $rules = [
//        'dept_id' => 'required|not_in:-1',
//        'selected_month' => 'required',
//        'prod_id' => 'required',
//    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
        'prod_id.required' => "مطلوب",
        'vendor_id.required' => "مطلوب",
        'vendor_id.not_in' => "مطلوب",

    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }


        if (Auth::user()->user_group->write_product_target == '1' || Auth::user()->user_group->write_product_target == '2'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {

        $this->query = User::where('id', Auth::id())->first();

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
    }

    public function render()
    {
//        $this->selected_month = Carbon::parse(Carbon::now())->format('Y-m');


//        $fromDate = Carbon::now();
//        $toDate = Carbon::parse("2023-06-30");
//        dd($toDate);
//        dd($fromDate->lt($toDate));
//
//        $months = $fromDate->diffInMonths($toDate, false);
//        dd($months);

        $branches = json_decode($this->query->branches);


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
    public function generateReport()
    {
//        dd($this->vendor_id);
        $this->reset('target');
        if ($this->filter_type == 'vendor') {
            $this->validate([
                'dept_id' => 'required|not_in:-1',
                'selected_month' => 'required',
                'vendor_id' => 'required|not_in:-1'
            ]);
        }
        else if ($this->filter_type == 'product') {
            $this->validate([
                'dept_id' => 'required|not_in:-1',
                'selected_month' => 'required',
                'prod_id' => 'required'
            ]);
        }

        $this->emit('show-container');
        $this->btn_generate = false;
        $this->btn_save = true;

        if ($this->query->user_group->write_product_target == '2') {
            $this->emps = User::join('user_groups', 'users.group', 'user_groups.id')
                ->where('branches', 'LIKE' ,'%"'.$this->dept_id.'"%')
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

        // for all products
//        $month_stmt .= " FROM (
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, SIDate as 'voucher_date', sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[SInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and SIDate>='" . $start_of_period . "' and  SIDate<='" . $end_of_period . " 23:59:59'
//            and Department = " . $this->dept_id .
//            " group by ProductNo, NodeNo, Code, Arabic_Name, SIDate
//            union all
//            SELECT ProductNo, NodeNo, Code, Arabic_Name, PIDate as 'voucher_date', -sum(ActualQty) as svalue
//              FROM [AccountsC5].[dbo].[PInvoice], accmast
//            where partyno=nodeno and accmast.[type]=10
//            and PIDate>='" . $start_of_period . "' and  PIDate<='" . $end_of_period . " 23:59:59'
//            and Department = " . $this->dept_id .
//            " group by ProductNo, NodeNo, Code, Arabic_Name, PIDate
//            ) as tbl
//            group by ProductNo) as tbl2
//            RIGHT JOIN ProductMast
//            on tbl2.ProductNo = ProductMast.NodeNo
//            WHERE ProductMast.Pricelist = 1) as tbl3
//            LEFT JOIN accmast ON tbl3.VendorNo = accmast.NodeNo
//            ORDER BY VendorNo";

        if ($this->filter_type == 'vendor') {

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
            WHERE VendorNo = '" . $this->vendor_id . "'
            ORDER BY VendorNo";
        }
        else if ($this->filter_type == 'product') {
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
            WHERE ProductCode = '" . $this->prod_id . "'
            ORDER BY VendorNo";
        }

//        dd($month_stmt);

        $query = DB::connection('sqlsrv')->select($month_stmt);
//        dd($query);
        $fetch_query = json_decode(json_encode($query), true);

//        dd($fetch_query);
        array_push($this->results, $fetch_query);

//        dd($this->results);

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
    public function test($targets) {
//        dd($targets);

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
                    ->where('branch', $this->dept_id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($fetch) {
                    $record = ProductTarget::where('product_id', $product_code)
                        ->where('month', $target_month)
                        ->where('year', $target_year)
                        ->where('branch', $this->dept_id)
                        ->where('user_id', $user->id)
                        ->update(['target' => $target_num]);
                } else {
                    $record = ProductTarget::create([
                        'product_id' => $product_code,
                        'month' => $target_month,
                        'year' => $target_year,
                        'branch' => $this->dept_id,
                        'user_id' => $user->id,
                        'target' => $target_num
                    ]);
                }

                $record = ProductTargetLog::create([
                    'product_id' => $product_code,
                    'month' => $target_month,
                    'year' => $target_year,
                    'branch' => $this->dept_id,
                    'user_id' => $user->id,
                    'target' => $target_num
                ]);

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

}
