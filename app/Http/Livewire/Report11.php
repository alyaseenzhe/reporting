<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report11 extends Component
{
    public $dept_id = ["dept_all"];
    public $branches = [];
    public $itemGrp = [];
    public $vendor_list = [];
    public $customer_list = [];
    public $categories = [];
    public $show_msg = false;
    public $report_type = 'byItem';
    public $emps = null;

    public $group_type = "xx";

    public $scribes_results = [];
    public $sap_results = [];
    public $group_results = [];

    public $scribes_codes = [];
    public $sap_codes = [];
    public $totalSalesByItem;

    public $products_codes = [];
    public $warehouse = ['1' => "0001", '3' => "0101", '7' => "0103", '10' => "0102", '13' => "0104", '4' => "0105", '6' => "0106", '5' => "0107", '12' => "0108", '11' => "0109", '9' => "0110", '8' => "0111", '505' => "0112"];
    public $warehouse_id = ["0001" => '1', "0101" => '3', "0103" => '7', "0102" => '10', "0104" => '13', "0105" => '4', "0106" => '6', "0107" => '5', "0108" => '12', "0109" => '11', "0110" => '9', "0111" => '8', "0112" => '505', '0201' => '15', '0202' =>  '16', '0203' => '17'];
    //publioc $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' =>'5', '0104' =>'6', '0105' =>'7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' =>  '16', '0203' => '17'];

    protected $listeners = ['item-category' => 'item_category', 'create-report' => 'create_report', 'change-group-type' => 'changeGroupType','setCatType'];

    public array $groupedResults = [];
    public array $tableRows = [];

    public $currentGroup = null, $currentItemName = null,
            $itemGroup_item_total = 0, $itemGroup_cost_total = 0, $itemGroup_gross_total = 0, $itemGroup_quantity_total = 0, $itemGroup_trans_total = 0,
            $itemGroup_item_subtotal = 0, $itemGroup_cost_subtotal = 0, $itemGroup_gross_subtotal = 0, $itemGroup_quantity_subtotal = 0, $itemGroup_trans_subtotal = 0,
            $itemGroup_itemName_subtotal = 0, $itemGroup_costName_subtotal = 0, $itemGroup_grossName_subtotal = 0;



//    public $sortBy = "GroupTotalSales";
    public $sortBy = "code";
    public $sortDir = 'ASC';
    public $toggleDirection = false;

//    public function updatedSortBy()
//    {
//        // When user selects a new column → reset to ASC
//        $this->sortDir = 'asc';
//    }
//
//    public function updatedToggleDirection()
//    {
//        // Flip direction when checkbox changes
//        $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
//
//        // Reset checkbox so next click toggles again
//        $this->toggleDirection = false;
//    }



//    protected $listeners = ['setCatType'];




//    public function sortBy($field)
//    {
//        if ($this->sortBy === $field) {
//            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
//        } else {
//            $this->sortBy = $field;
//            $this->sortDir = 'asc';
//        }
//    }



            public function booted() {


        if (Auth::user()->is_active == '0'){

            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('report-11', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {
        $this->product_lists();
        $this->employees();

        $this->query = User::where('id', Auth::id())->first();
        $this->branches = json_decode($this->query->branches);
//        dd($this->branches);

//        $this->groupedResults = $this->buildGroups($this->report_type);


    }

    public function buildGroups(string $report_type)
    {

        if (empty($this->group_results)) {
            $this->tableRows = [];
            return;
        }
        $rows = $this->group_results;


        // Decide which field to group by based on report type
        $groupField = match($report_type) {
            'byItemGroup'   => 'ItemGroup"',
            'bySpeciality'  => 'Speciality',
            'byVendor'      => 'VendorName',
            'byCustomer'      => 'VendorCustomer',
            'byEmployee'      => 'VendEmployee',
            default         => null,
        };

        $tableRows = [];
        $currentGroup = null;
        $subtotals = [
            'item' => 0,
            'cost' => 0,
            'gross' => 0,
            'quantity' => 0,
            'trans' => 0,
        ];

        foreach ($rows as $row) {
            // New group
            if ($groupField && $currentGroup !== $row[$groupField]) {
                if ($currentGroup !== null) {
                    $this->tableRows[] = [
                        'type' => 'group_subtotal',
                        'group_name' => $currentGroup,
                        'totals' => $subtotals,
                    ];
                }
                $currentGroup = $row[$groupField];
                $subtotals = array_map(fn($v) => 0, $subtotals);
            }

            // Push record row
            $tableRows[] = [
                'type' => 'record',
                'data' => $row,
            ];

            // Accumulate totals
            $subtotals['item']     += $row['TotalSalesAmount'];
            $subtotals['cost']     += $row['Cost'];
            $subtotals['gross']    += $row['GrossProfit'];
            $subtotals['quantity'] += $row['TotalQuantitySold'];
            $subtotals['trans']    += $row['TransCount'];
        }

        // Push last group subtotal
        if ($currentGroup !== null && $groupField) {
            $tableRows[] = [
                'type' => 'group_subtotal',
                'group_name' => $currentGroup,
                'totals' => $subtotals,
            ];
        }

        return $tableRows;
    }

    public function render()
    {
        $this->itemGroups();
        $this->vendors();
        $this->customers();

        return view('livewire.report11.report11')
            ->layout('layouts.dashboard');
    }

    public function create_report($start_date, $end_date, $dept_id, $group_type, $cat_type, $sp_type, $vendor_type, $report_type, $search_type, $product_code, $marketing_type, $customer_type, $emps_type) {
//        $this->reset();
//        $this->reset(['scribes_results', 'sap_results', 'group_results', 'report_type', 'show_msg']);

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
        $this->report_type = $report_type;
        $this->scribes_results = [];
        $this->sap_results = [];
        $this->group_results = [];

        $this->productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type);
//        dd($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type);
//        $this->scribesQuery($start_date, $end_date, $dept_id, $sp_type);
//        $this->sapQuery($start_date, $end_date, $dept_id);
//
//        $scribes_collection = collect($this->scribes_results);
//        $sap_collection = collect($this->sap_results);

        // dates
        if (is_null($start_date) == false && is_null($end_date) == false) {

            if ($start_date >= '2011-07-01' && $end_date <= '2023-12-31') {
                $this->scribesQuery($start_date, $end_date, $dept_id, $sp_type, $customer_type);
            }
            elseif ($start_date > '2023-12-31' && $end_date > '2023-12-31') {
                $this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type);
            }
            elseif ($start_date >= '2011-07-01' && $end_date > '2023-12-31') {
                $this->scribesQuery($start_date, '2023-12-31', $dept_id, $sp_type, $customer_type);
                $this->sapQuery('2024-01-01', $end_date, $dept_id, $customer_type, $emps_type);
            }


        }
        else {
            dd('coco');
        }
        // end dates

        if (count($this->scribes_results) > 0 || count($this->sap_results) > 0) {
            $scribes_collection = collect($this->scribes_results);
            $sap_collection = collect($this->sap_results);

            $merged_results = $scribes_collection->merge($sap_collection);
//        dd($merged_results->pluck('ItemCode'));


            if ($report_type == "byItem") {
                $groups = $merged_results->groupBy('OldCode');
                $this->group_results = $groups->map(function ($row) {

                    return [
//                'OldCode' => $row->first()['OldCode'],
                        'OldCode' => count($this->scribes_results) > 0 ? $row->first()->OldCode : $row->first()['OldCode'],
                        'ItemName' => count($this->scribes_results) > 0 ? $row->first()->ItemName : $row->first()['ItemName'],
                        'SalUnitMsr' => count($this->scribes_results) > 0 ? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                        'Speciality' => count($this->scribes_results) > 0 ? $row->first()->Speciality : $row->first()['Speciality'],
                        'VendorName' => count($this->scribes_results) > 0 ? $row->first()->VendorName : $row->first()['VendorCode'],
                        'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                        'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                        'AverageUnitPrice' => $row->sum('TotalQuantitySold') == 0 ? 0 : $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                        'Cost' => $row->sum('Cost'),
                        'GrossProfit' => $row->sum('GrossProfit'),
                        'GrossProfitPer' => $row->sum('Cost') == 0 ? 0 : ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                    ];
                });
//
//                $this->group_results = $this->group_results
//                    ->sortBy('totalSales')
//                    ->values(); // reset keys
            }


            else if ($this->report_type == "byDepartmentX") {
                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//                $groups = $merged_results->groupBy('OldCode', 'Department');
//                $groups = $merged_results->groupBy('OldCode')->groupBy('Department');
//                dd($groups);

                $this->group_results = $groups->map(function ($outer_row) {
//                    dd($row);
                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorCode'],
                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
                            'mrkt_type' => $row->first()['mrkt_type'],
                            'ItemGroup' => $row->first()['ItemGroup'],
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });


                });
//                ->sortBy(['OldCode', 'Department']);
            }
            else if ($this->report_type == "byDepartment") {
                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//

                $this->group_results = $groups->map(function ($outer_row) {

                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorName'],
                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
                            'mrkt_type' => gettype($row->first()) == "object"? $row->first()->mrkt_type : $row->first()['mrkt_type'],
                            'ItemGroup' => gettype($row->first()) == "object"? $row->first()->group_item : $row->first()['ItemGroup'],
                            'TransCount' => $row->sum('TransCount'),
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });

                });
//                ->sortBy(['OldCode', 'Department']);

                // Step 1: Flatten all sub-collections into a single collection
                $flattened = collect($this->group_results)->flatMap->values();
//                ->sortBy(['OldCode', 'Department']);
                $this->group_results = $flattened;
                // Step 2: Group by OldCode
                $groupedByItemName = $flattened->groupBy('OldCode');
                // Step 3: Calculate total sales amount for each group
                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'),
                        $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
                })->toArray();

            }
            else if ($this->report_type == "byItemGroup") {
//                dd($merged_results);
//                dd($this->sap_results);
                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? 'Dept' : $item['Department'];
//                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//

                $this->group_results = $groups->map(function ($outer_row) {

                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorName'],
                            'Department' => gettype($row->first()) == "object"? '0001' : $row->first()['Department'],
//                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
                            'mrkt_type' => gettype($row->first()) == "object"? $row->first()->mrkt_type : $row->first()['mrkt_type'],
                            'ItemGroup' => gettype($row->first()) == "object"? $row->first()->group_item : $row->first()['ItemGroup'],
                            'TransCount' => $row->sum('TransCount'),
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });

                })->sortBy(['OldCode', 'Department']);

                // Step 1: Flatten all sub-collections into a single collection
                $flattened = collect($this->group_results)->flatMap->values();
//                    ->sortBy(['ItemGroup','mrkt_type', 'Speciality', 'OldCode', 'Department']);
                $this->group_results = $flattened;
                // Step 2: Group by OldCode
                $groupedByItemName = $flattened->groupBy('OldCode');
                // Step 3: Calculate total sales amount for each group
                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'), $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
                })->toArray();
            }
            else if ($this->report_type == "bySpeciality") {
//                dd($merged_results);
                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//

                $this->group_results = $groups->map(function ($outer_row) {

                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorName'],
                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
                            'mrkt_type' => gettype($row->first()) == "object"? $row->first()->mrkt_type : $row->first()['mrkt_type'],
                            'ItemGroup' => gettype($row->first()) == "object"? $row->first()->group_item : $row->first()['ItemGroup'],
                            'TransCount' => $row->sum('TransCount'),
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });

                })->sortBy(['OldCode', 'Department']);

                // Step 1: Flatten all sub-collections into a single collection
                $flattened = collect($this->group_results)->flatMap->values();
//                ->sortBy(['Speciality', 'OldCode', 'Department']);
                $this->group_results = $flattened;

                // Step 2: Group by OldCode
                $groupedByItemName = $flattened->groupBy('OldCode');
                // Step 3: Calculate total sales amount for each group
                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'), $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
                })->toArray();
            }
            else if ($this->report_type == "byMarketingType") {
//                dd($merged_results);
                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//

                $this->group_results = $groups->map(function ($outer_row) {

                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorName'],
                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
                            'mrkt_type' => gettype($row->first()) == "object"? $row->first()->mrkt_type : $row->first()['mrkt_type'],
                            'ItemGroup' => gettype($row->first()) == "object"? $row->first()->group_item : $row->first()['ItemGroup'],
                            'TransCount' => $row->sum('TransCount'),
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });

                })->sortBy(['mrkt_type'/*, 'Speciality', 'OldCode', 'Department'*/]);




                // Step 1: Flatten all sub-collections into a single collection
                $flattened = collect($this->group_results)->flatMap->values();
//                    ->sortBy(['mrkt_type', 'Speciality', 'OldCode', 'Department']);
                $this->group_results = $flattened;

                // Step 2: Group by OldCode
                $groupedByItemName = $flattened->groupBy('OldCode');
                // Step 3: Calculate total sales amount for each group
                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'), $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
                })->toArray();
            }
            else if ($this->report_type == "byVendor") { // bug

                $groups = $merged_results->groupBy(['OldCode', function ($item) {
//                    dd(gettype($item));
                    return gettype($item) == "object"? $item->Department : $item['Department'];
//                    return $item['OldCode'];
                }], true);
//                dd($groups);
//

                $this->group_results = $groups->map(function ($outer_row) {
                    return $outer_row->map(function ($row) {

                        return [

                            'OldCode' => gettype($row->first()) == "object" ? $row->first()->OldCode : $row->first()['OldCode'],
//                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => gettype($row->first()) == "object" ? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => gettype($row->first()) == "object" ? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => gettype($row->first()) == "object" ? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => gettype($row->first()) == "object" ? $row->first()->VendorName : $row->first()['VendorName'],
                            'Department' => gettype($row->first()) == "object" ? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0 ? $row->sum('TotalSalesAmount') / $row->sum('TotalQuantitySold') : 0,
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0 ? (($row->sum('GrossProfit') / $row->sum('Cost')) * 100) : 0,
                            'mrkt_type' => gettype($row->first()) == "object" ? $row->first()->mrkt_type : $row->first()['mrkt_type'],
                            'ItemGroup' => gettype($row->first()) == "object" ? $row->first()->group_item : $row->first()['ItemGroup'],
                            'TransCount' => $row->sum('TransCount'),
                            'GroupTotalSales'=> $row->sum('GroupTotalSales')
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });
                });
//                    ->sortBy('GroupTotalSales');
//                })->sortBy(['OldCode', 'Department']);

                // Step 1: Flatten all sub-collections into a single collection
//                $flattened = collect($this->group_results)->flatMap->values()->sortBy(['VendorName', 'Speciality', 'OldCode', 'Department']);
                $flattened = collect($this->group_results)->flatMap->values();
//                    ->sortBy(['GroupTotalSales']);
                $this->group_results = $flattened;

                // Step 2: Group by OldCode
                $groupedByItemName = $flattened->groupBy('OldCode');
                // Step 3: Calculate total sales amount for each group
                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'), $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
                })
//                    ->sortBy('GroupTotalSales')
                    ->toArray();

            }


            else if ($this->report_type == "byCustomer") { // bug
                $groupedByBP = $merged_results->groupBy(function ($item) {
                    return gettype($item) === 'object' ? $item->BusinessPartnerCode : $item['BusinessPartnerCode'];
                });

                $this->group_results = $groupedByBP->map(function ($itemsGroup) {
                    // Now group by OldCode within each BusinessPartnerCode
                    $groupedByItem = $itemsGroup->groupBy(function ($item) {
                        return gettype($item) === 'object' ? $item->OldCode : $item['OldCode'];
                    });

                    // Process each item group
                    return $groupedByItem->map(function ($row) {
                        $first = $row->first();
                        $isObject = gettype($first) === 'object';

                        return [
                            'OldCode' => $isObject ? $first->OldCode : $first['OldCode'],
                            'ItemName' => $isObject ? $first->ItemName : $first['ItemName'],
                            'SalUnitMsr' => $isObject ? $first->SalUnitMsr : $first['SalUnitMsr'],
                            'Speciality' => $isObject ? $first->Speciality : $first['Speciality'],
                            'VendorName' => $isObject ? $first->VendorName : $first['VendorName'],
                            'Department' => $isObject ? $first->Department : $first['Department'],
                            'mrkt_type' => $isObject ? $first->mrkt_type : $first['mrkt_type'],
                            'ItemGroup' => $isObject ? ($first->group_item ?? $first->ItemGroup) : ($first['group_item'] ?? $first['ItemGroup']),
                            'BusinessPartnerCode' => $isObject ? $first->BusinessPartnerCode : $first['BusinessPartnerCode'],
                            'BusinessPartnerName' => $isObject ? $first->BusinessPartnerName : $first['BusinessPartnerName'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0
                                ? $row->sum('TotalSalesAmount') / $row->sum('TotalQuantitySold') : 0,
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0
                                ? (($row->sum('GrossProfit') / $row->sum('Cost')) * 100) : 0,
                            'TransCount' => $row->sum('TransCount'),
                        ];
                    });
                });


                $this->group_results = $this->group_results->map(function ($items) {
                    return $items->values(); // Convert inner maps to arrays
                });

//                dd($this->group_results);
                // Step: Flatten all items across partners to group by OldCode
                $flattenedItems = $this->group_results->flatMap(function ($items) {
                    return $items;
                });

                $groupedByPartnerAndItem = $flattenedItems
                    ->groupBy('BusinessPartnerCode')
                    ->map(function ($itemsGroup) {
                        return $itemsGroup->groupBy('OldCode')
                            ->map(function ($itemGroup) {
                                return [
                                    $itemGroup->sum('TotalSalesAmount'),
                                    $itemGroup->sum('Cost'),
                                    $itemGroup->sum('GrossProfit'),
                                    $itemGroup->sum('TotalQuantitySold'),
                                    $itemGroup->sum('TransCount'),
                                ];
                            });
                    });

                $this->group_results = $this->group_results
//                    ->sortBy(['totalSales','GroupGrossProfit'])
                    ->values(); // reset keys
// Step 2: Convert to array if needed
                $this->totalSalesByItem = $groupedByPartnerAndItem->toArray();
//                dd($this->totalSalesByItem);
            }
            else if ($this->report_type == "byEmployee") { // bug

                $groupedByBP = $merged_results->groupBy(function ($item) {
                    return gettype($item) === 'object' ? $item->SlpCode : $item['SlpCode'];
                });

                $this->group_results = $groupedByBP->map(function ($itemsGroup) {
                    // Now group by OldCode within each BusinessPartnerCode
                    $groupedByItem = $itemsGroup->groupBy(function ($item) {
                        return gettype($item) === 'object' ? $item->OldCode : $item['OldCode'];
                    });
                    //dd($this->group_results);

                    // Process each item group
                    return $groupedByItem->map(function ($row) {
                        $first = $row->first();
                        $isObject = gettype($first) === 'object';

                        return [
                            'OldCode' => $isObject ? $first->OldCode : $first['OldCode'],
                            'ItemName' => $isObject ? $first->ItemName : $first['ItemName'],
                            'SalUnitMsr' => $isObject ? $first->SalUnitMsr : $first['SalUnitMsr'],
                            'Speciality' => $isObject ? $first->Speciality : $first['Speciality'],
                            'VendorName' => $isObject ? $first->VendorName : $first['VendorName'],
                            'Department' => $isObject ? $first->Department : $first['Department'],
                            'mrkt_type' => $isObject ? $first->mrkt_type : $first['mrkt_type'],
                            'ItemGroup' => $isObject ? ($first->group_item ?? $first->ItemGroup) : ($first['group_item'] ?? $first['ItemGroup']),
                            'BusinessPartnerCode' => $isObject ? $first->BusinessPartnerCode : $first['BusinessPartnerCode'],
                            'BusinessPartnerName' => $isObject ? $first->BusinessPartnerName : $first['BusinessPartnerName'],
                            'EmployeeCode' => $isObject ? $first->EmployeeCode : $first['SlpCode'],
                            'EmployeeName' => $isObject ? $first->EmployeeName : $first['SlpName'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0
                                ? $row->sum('TotalSalesAmount') / $row->sum('TotalQuantitySold') : 0,
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => $row->sum('Cost') != 0
                                ? (($row->sum('GrossProfit') / $row->sum('Cost')) * 100) : 0,
                            'TransCount' => $row->sum('TransCount'),
                            'EmployeeTotalSales' => $row->sum('EmployeeTotalSales'),
//                            'items' => $items,

                            'subtotal' => [
                                'sales' => $row->sum('TotalSalesAmount'),
                                'quantity' => $row->sum('TotalQuantitySold'),
                                'cost' => $row->sum('Cost'),
                                'gross' => $row->sum('GrossProfit'),
                                'trans' => $row->sum('TransCount'),
                            ],


                        ];
                    });

                });

// Flattening not needed here since you want nested result
// You now have:
// [
//   BusinessPartnerCode1 => [
//       OldCode1 => [item info...],
//       OldCode2 => [item info...],
//       ...
//   ],
//   BusinessPartnerCode2 => [...],
//   ...
// ]

// If you want the structure to be: BusinessPartnerCode => [ [ item1 ], [ item2 ], ... ]

//
                $this->group_results = $this->group_results
                    ->sortBy('totalSales')
                    ->values(); // reset keys
//
//                $this->group_results = $this->group_results
//                    ->sortBy('totalSales')
//                    ->values(); // reset keys

                $this->group_results = $this->group_results->map(function ($items) {
                    return $items->values(); // Convert inner maps to arrays
                });

//                dd($this->group_results);
                // Step: Flatten all items across partners to group by OldCode
                $flattenedItems = $this->group_results->flatMap(function ($items) {
                    return $items;
                });



                // Step 1: Group by BusinessPartnerCode, then by OldCode


                $groupedByEmployeeAndItem = $flattenedItems
                    ->groupBy('EmployeeCode')
//                    ->sortBy("EmployeeTotalSales")
                    ->map(function ($itemsGroup) {
                        return $itemsGroup->groupBy('OldCode')
                            ->map(function ($itemGroup) {
                                return [
                                    $itemGroup->sum('TotalSalesAmount'),
                                    $itemGroup->sum('Cost'),
                                    $itemGroup->sum('GrossProfit'),
                                    $itemGroup->sum('TotalQuantitySold'),
                                    $itemGroup->sum('TransCount'),
                                ];
                            });
                    });

// Step 2: Convert to array if needed
//                $this->totalSalesByItem = $groupedByEmployeeAndItem->toArray();
                $this->totalSalesByItem = $groupedByEmployeeAndItem->toArray();
//                dd($this->group_results);

//                dd($this->totalSalesByItem);
            }
//            else if ($this->report_type == "byCustomerX") { // bug
//                $groups = $merged_results->groupBy(['BusinessPartnerCode', function ($item) {
////                    dd(gettype($item));
//                    return gettype($item) == "object"? $item->Department : $item['BusinessPartnerCode'];
////                    return $item['OldCode'];
//                }], true)->sortBy(['BusinessPartnerCode', 'BusinessPartnerName', 'OldCode', 'Department']);
//
////                dd($groups);
////
//
//                $this->group_results = $groups->map(function ($outer_row, $key) {
//
//                    return $outer_row->map(function ($row) {
//
//                        return [
//                            'BusinessPartnerCode' => $row->first()['BusinessPartnerCode'],
//                            'BusinessPartnerName' => $row->first()['BusinessPartnerName'],
//                            'OldCode' => gettype($row->first()) == "object"? $row->first()->OldCode : $row->first()['OldCode'],
////                            'OldCode' => (count($this->scribes_results) > 0) ? $row->first()->OldCode : $row->first()['OldCode'],
//                            'ItemName' => gettype($row->first()) == "object"? $row->first()->ItemName : $row->first()['ItemName'],
//                            'SalUnitMsr' => gettype($row->first()) == "object"? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
//                            'Speciality' => gettype($row->first()) == "object"? $row->first()->Speciality : $row->first()['Speciality'],
//                            'VendorName' => gettype($row->first()) == "object"? $row->first()->VendorName : $row->first()['VendorName'],
//                            'Department' => gettype($row->first()) == "object"? $row->first()->Department : $row->first()['Department'],
////                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
////                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
////                            'AverageUnitPrice' => $row->sum('TotalQuantitySold') != 0? $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold') : 0,
//////                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
////                            'Cost' => $row->sum('Cost'),
////                            'GrossProfit' => $row->sum('GrossProfit'),
////                            'GrossProfitPer' => $row->sum('Cost') != 0? (($row->sum('GrossProfit')/$row->sum('Cost'))*100) : 0,
//                            'mrkt_type' => gettype($row->first()) == "object"? $row->first()->mrkt_type : $row->first()['mrkt_type'],
//                            'ItemGroup' => gettype($row->first()) == "object"? $row->first()->group_item : $row->first()['ItemGroup'],
//                            'TransCount' => $row->sum('TransCount'),
////                'GrossProfitPer' => $row->sum('GrossProfitPer'),
//                        ];
//                    });
//
//                })->sortBy(['BusinessPartnerCode', 'Department']);
////                dd($this->group_results);
//                $this->group_results = $this->group_results->sortBy(['BusinessPartnerCode', 'BusinessPartnerName', 'OldCode', 'Department']);
//
//                // Step 1: Flatten all sub-collections into a single collection
//                $flattened = collect($this->group_results)->flatMap->values();
//
////                dd($flattened->sortBy(['BusinessPartnerCode', 'BusinessPartnerName', 'OldCode', 'Department']));
//                $flattened = $flattened->sortBy(['BusinessPartnerCode', 'BusinessPartnerName', 'OldCode', 'Department']);
//                // Step 2: Group by OldCode
//                $groupedByItemName = $flattened->groupBy(['BusinessPartnerCode']);
////                dd($groupedByItemName);
//                // Step 3: Calculate total sales amount for each group
//                $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
//                    return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'), $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
//                })->toArray();
//
////                dd($this->totalSalesByItem);
////                dd($this->group_results);
////
//
//            }

        }


        $this->show_msg = true;
        $this->emit('finished');
    }

    public function itemGroups() {

        $this->itemGrp = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $itemGroupQuery = 'SELECT "ItmsGrpCod" AS "ItemGroupCode","ItmsGrpNam" AS "ItemGroupName" FROM AL_YASEEN_AGRI_PLIVE.OITB WHERE "ItmsGrpCod" NOT IN (100,101,102,103,180)';

            $result = odbc_exec($conn, $itemGroupQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->itemGrp, $row);
                }
            }

//            dd($this->itemGrp);
            odbc_close($conn);
        }
    }

    public function vendors() {

        $this->vendor_list = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $vendorQuery = 'SELECT "CardCode" AS "VendorCode", "CardName" AS "VendorName" FROM AL_YASEEN_AGRI_PLIVE.OCRD WHERE "CardType" = \'S\'';

            $result = odbc_exec($conn, $vendorQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->vendor_list, $row);
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }
    public function customers() {

        $this->customer_list = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $customer_depts = ['2' => '01%', '3' => '01%', '10' => '02%', '7' => '03%', '13' => '04%', '4' => '05%' , '6' => '06%', '5' => '07%', '12' => '08%', '11' => '09%', '9' => '10%', '8' => '11%', '505' => '12%', '15' => '01%', '500' => '01%', '504' => '01%'];
            $customer_codes = array_intersect_key($customer_depts, array_flip($this->branches));


            $query = implode(' OR ', array_map(function ($value) {
                return 'T0."CardCode" LIKE \'' . $value . '\'';
            }, $customer_codes));

            $customerQuery = 'SELECT T0."CardCode", T0."CardName",
       CASE
	WHEN "CardCode" LIKE \'01%\' THEN \'0101\'
	WHEN "CardCode" LIKE \'02%\' THEN \'0102\'
	WHEN "CardCode" LIKE \'03%\' THEN \'0103\'
	WHEN "CardCode" LIKE \'04%\' THEN \'0104\'
	WHEN "CardCode" LIKE \'05%\' THEN \'0105\'
	WHEN "CardCode" LIKE \'06%\' THEN \'0106\'
	WHEN "CardCode" LIKE \'07%\' THEN \'0107\'
	WHEN "CardCode" LIKE \'08%\' THEN \'0108\'
	WHEN "CardCode" LIKE \'09%\' THEN \'0109\'
	WHEN "CardCode" LIKE \'10%\' THEN \'0110\'
	WHEN "CardCode" LIKE \'11%\' THEN \'0111\'
	WHEN "CardCode" LIKE \'12%\' THEN \'0112\'
END AS "Dept"
FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\' AND ('.$query.')
ORDER BY "CardCode"';

            $result = odbc_exec($conn, $customerQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->customer_list, $row);
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }

    public function item_category($group_type) {

        $this->categories = [];

        if ($group_type != 'select_group') {
            if (! extension_loaded('odbc'))
            {
                die('ODBC extension not enabled / loaded');
            }

            $driver = env('DB_CONNECTION_FOURTH');
            $host = env('DB_HOST_FOURTH');
            $db_name = env('DB_DATABASE_FOURTH');
            $username = env('DB_USERNAME_FOURTH');
            $password = env('DB_PASSWORD_FOURTH');

            $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

            if (!$conn)
            {
                echo "Connection failed.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                $categoryQuery = '';

                if ($group_type == "commerce") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
                }
                elseif ($group_type == "farms") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
                }
                elseif ($group_type == "sundries") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
                }
                elseif ($group_type == "groups_all") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
                }

                $result = odbc_exec($conn, $categoryQuery);
                if (!$result)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {

                    while ($row = odbc_fetch_array($result)) {
                        array_push($this->categories, $row);
                    }
                }

//            dd($this->vendor_list);
                odbc_close($conn);
            }
        }

        $this->emit('finished-categories', $this->categories);
    }

    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type) {

        $this->scribes_codes = [];
        $this->sap_codes = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $categoryQuery = '';

            if ($search_type == "item_code_search") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" = \''.$product_code.'\' OR T0."U_UDF1" = \''.$product_code.'\'';
            }
            else if($search_type == "advanced_search") {

                if ($group_type == "commerce") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
                }
                elseif ($group_type == "farms") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
                }
                elseif ($group_type == "sundries") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
                }
                elseif ($group_type == "groups_all") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
                }

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
                    $categoryQuery .= ' AND T0."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
                }

                if ($sp_type != null && in_array('sp_all', $sp_type) == false && count($sp_type) != 0) {
//                $categoryQuery .= ' AND (T0."QryGroup1" = \''. (in_array('0', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup2" = \''. (in_array('1', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup3" = \''. (in_array('2', $sp_type) ? 'Y': 'N') .'\')';
//                dd($sp_type);
                    if (count($sp_type) == 3) {
                        $categoryQuery .= ' AND (T0."QryGroup1" = \''. (in_array('0', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup2" = \''. (in_array('1', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup3" = \''. (in_array('2', $sp_type) ? 'Y': 'N') .'\')';
                    }
                    else {
                        $categoryQuery .= ' AND (';
                        foreach ($sp_type as $key => $s) {
                            if ($key === array_key_first($sp_type)) {
                                $categoryQuery .= 'T0."QryGroup'. intval($s)+1 .'" = \'Y\'';
                            }
                            else {
                                $categoryQuery .= ' OR T0."QryGroup'. intval($s)+1 .'" = \'Y\'';
                            }
                        }
                        $categoryQuery .= ')';

                        $difference=array_diff(['0', '1', '2'], $sp_type);
//                    dd($difference);

                        if (count($difference) > 0) {

                            $categoryQuery .= ' AND (';
                            foreach ($difference as $key => $s) {
                                if ($key === array_key_first($difference)) {
                                    $categoryQuery .= 'T0."QryGroup'. intval($s)+1 .'" = \'N\'';
                                }
                                else {
                                    $categoryQuery .= ' OR T0."QryGroup'. intval($s)+1 .'" = \'N\'';
                                }
                            }
                            $categoryQuery .= ')';

                        }

                    }
                }

                if ($marketing_type != null && in_array('marketing_all', $marketing_type) == false && count($marketing_type) != 0) {

                    if (count($marketing_type) == 9) {

                        $categoryQuery .= ' AND (T0."QryGroup30" = \''. (in_array('30', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup31" = \''. (in_array('31', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup32" = \''. (in_array('32', $marketing_type) ? 'Y': 'N') .'\'  OR T0."QryGroup40" = \''. (in_array('40', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup41" = \''. (in_array('41', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup50" = \''. (in_array('50', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup51" = \''. (in_array('51', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup52" = \''. (in_array('52', $marketing_type) ? 'Y': 'N') .'\' OR T0."QryGroup53" = \''. (in_array('53', $marketing_type) ? 'Y': 'N') .'\')';
                    }
                    else {
                        $categoryQuery .= ' AND (';
                        foreach ($marketing_type as $key => $s) {
                            if ($key === array_key_first($marketing_type)) {
                                $categoryQuery .= 'T0."QryGroup'. intval($s) .'" = \'Y\'';
                            }
                            else {
                                $categoryQuery .= ' OR T0."QryGroup'. intval($s) .'" = \'Y\'';
                            }
                        }
                        $categoryQuery .= ')';

                        $difference=array_diff(['30', '31', '32', '40', '41', '50', '51', '52', '53'], $marketing_type);
//                    dd($difference);

                        if (count($difference) > 0) {

                            $categoryQuery .= ' AND (';
                            foreach ($difference as $key => $s) {
                                if ($key === array_key_first($difference)) {
                                    $categoryQuery .= 'T0."QryGroup'. intval($s) .'" = \'N\'';
                                }
                                else {
                                    $categoryQuery .= ' OR T0."QryGroup'. intval($s) .'" = \'N\'';
                                }
                            }
                            $categoryQuery .= ')';

                        }

                    }
                }
//            else {
//                if (count($sp_type) == 3) {
//                    dd('aaa');
//                    $categoryQuery .= ' AND (T0."QryGroup1" = \''. (in_array('0', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup2" = \''. (in_array('1', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup3" = \''. (in_array('2', $sp_type) ? 'Y': 'N') .'\')';
//                }
//                else {
//                    dd('bb');
//                    $categoryQuery .= ' AND (';
//                    foreach ($sp_type as $key => $s) {
//                        if ($key === array_key_first($sp_type)) {
//                            $categoryQuery .= 'T0."QryGroup'. intval($s)+1 .'" = \'Y\'';
//                        }
//                        else {
//                            $categoryQuery .= ' OR T0."QryGroup'. intval($s)+1 .'" = \'Y\'';
//                        }
//                    }
//                    $categoryQuery .= ')';
//
//                    $difference=array_diff($sp_type,['0', '1', '2']);
//
//                    if (count($difference) > 0) {
//
//                        $categoryQuery .= ' AND (';
//                        foreach ($difference as $key => $s) {
//                            if ($key === array_key_first($difference)) {
//                                $categoryQuery .= 'T0."QryGroup'. intval($s)+1 .'" = \'N\'';
//                            }
//                            else {
//                                $categoryQuery .= ' OR T0."QryGroup'. intval($s)+1 .'" = \'N\'';
//                            }
//                        }
//                        $categoryQuery .= ')';
//
//                    }
//
//                }
//            }

                if ($vendor_type != 'vendor_all' && $vendor_type != null) {
                    $categoryQuery .= ' AND (T0."CardCode" = \''.$vendor_type.'\')';
                }
            }

//            dd($categoryQuery);

            $result = odbc_exec($conn, $categoryQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_codes, "'". $row['ItemCode']."'");
                    array_push($this->scribes_codes, "'". $row['ScribeCode']."'");
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }

        $this->emit('finished-categories', $this->categories);
    }

    public function scribesQuery($start_date, $end_date, $departments, $sps, $customer_type) {

        if (count($this->scribes_codes) > 0) {

            if (in_array('dept_all', $departments)) {
//                $departments = ["0001", "0101","0102","0103","0104","0105","0106","0107","0108","0109","0110","0111","0112","0201","0202","0203"];
                $departments = $this->branches;
            }

            if ($sps == null || in_array('sp_all', $sps)) {
                $sps = ["'0'", "'1'","'2'"];
            }

            foreach ($departments as &$value) {
                $value = "'" . $value . "'";
            }

            if ($this->report_type == "byItem") {

                $scribesStmt = "select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo ,sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo ,SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ."  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,-sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ."  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName";

            }
            else if ($this->report_type == "byDepartment") {

                $scribesStmt = "SELECT * FROM (
select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department
) as tblx
left join ProductSAP on tblx.OldCode = ProductSAP.Code
order by OldCode, VendorName";
            }
            else if ($this->report_type == "bySpeciality") {

                $scribesStmt = "SELECT * FROM (
select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department
) as tblx
left join ProductSAP on tblx.OldCode = ProductSAP.Code
order by Speciality,OldCode, VendorName";
            }
            else if ($this->report_type == "byMarketingType") {

                $scribesStmt = "SELECT * FROM (
select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department
) as tblx
left join ProductSAP on tblx.OldCode = ProductSAP.Code
order by mrkt_type, Speciality,OldCode, VendorName";
            }
            else if ($this->report_type == "byItemGroup") {

                $scribesStmt = "SELECT * FROM (
select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department
) as tblx
left join ProductSAP on tblx.OldCode = ProductSAP.Code
order by group_item, mrkt_type, Speciality,OldCode, VendorName";
            }
            else if ($this->report_type == "byVendor") {

                $scribesStmt = "SELECT * FROM (
select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And PIDate>='" . $start_date . "'And PIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (" . implode(", ", $this->scribes_codes) . ")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (" . implode(', ', $departments) . ")) And (SpecialityCode in (" . implode(", ", $sps) . ")) And SIDate>='" . $start_date . "'And SIDate <='" . $end_date . " 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department
) as tblx
left join ProductSAP on tblx.OldCode = ProductSAP.Code
order by VendorNo,OldCode";
            }
            else {

                $scribesStmt = "select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo ,sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/nullif(SUM(SalesTotalCost),0))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo ,SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,-sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/nullif(sum(ActualQty+FreeQty),0)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N' ". ($customer_type != 'customer_all' ? " And PartyNo = (SELECT NodeNo FROM AccMast WHERE Code = '".$customer_type."') " : "") ." And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName";

            }


//        dd($scribesStmt);

            $query = DB::connection('sqlsrv')->select($scribesStmt);

//            dd($query);

            $this->scribes_results = $query;
        }
    }

    public function sapQuery($start_date, $end_date, $departments, $customer_type, $emps_type) {

        $sortBy = $this->sortBy;
//            ? $this->sortBy
//            : 'EmployeeTotalSales';

        $direction = $this->sortDir;

        if (count($this->sap_codes) > 0) {
            $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' =>'5', '0104' =>'6', '0105' =>'7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' =>  '16', '0203' => '17'];
            $sap_depts = [];

            $scribe_depts = ['0001' => '2', '0101' => '3', '0102' => '10', '0103' => '7', '0104' => '13', '0105' => '4' , '0106' => '6', '0107' => '5', '0108' => '12', '0109' => '11', '0110' => '9', '0111' => '8', '0112' => '505', '0201' => '15', '0202' => '500', '0203' => '504'];
            $user_depts = [];

            foreach ($this->branches as $value) {
                $user_depts = array_merge($user_depts, array_keys($scribe_depts, $value));
            }

//            dd($user_depts);

            if (in_array('dept_all', $departments)) {
//                $sap_depts = $depts;
                $sap_depts = array_intersect_key($depts, array_flip($user_depts));
//                dd($sap_depts);
            }
            else {
                foreach ($departments as $department) {
                    array_push($sap_depts, $depts[$department]);
                }
//                dd($department);
            }

            if (! extension_loaded('odbc'))
            {
                die('ODBC extension not enabled / loaded');
            }

            $driver = env('DB_CONNECTION_FOURTH');

// Host
// Note: I am hosting it on the Amazon AWS, so my host looks like this. Put whatever your system administrator gave you
            $host = env('DB_HOST_FOURTH');

// Default name of your hana instance
            $db_name = env('DB_DATABASE_FOURTH');
            $username = env('DB_USERNAME_FOURTH');
            $password = env('DB_PASSWORD_FOURTH');

// Try to connect
            $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

            if (!$conn)
            {
                // Try to get a meaningful error if the connection fails
                echo "Connection failed.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                if ($this->report_type == "byItem") {

//                    $sql = 'SELECT * FROM (
//SELECT
//    T0."ItemCode" AS "ItemCode",
//    T1."ItemName" AS "ItemName",
//    SUM(T0."Quantity") AS "TotalQuantitySold",
//    SUM(T0."LineTotal") AS "TotalSalesAmount",
//    AVG(T0."Price") AS "AverageUnitPrice",
//    COUNT(DISTINCT T0."DocEntry") AS "NumberOfInvoices",
//    SUM(T0."GrssProfit") as "GrossProfit",
//    SUM(T0."GPTtlBasPr") as "Cost",
//    (SUM(T0."GrssProfit")/ SUM(T0."GPTtlBasPr"))*100 as "GrossProfitPer",
//    T2."BPLName" AS "Branch",
//    CASE
//		WHEN T1."QryGroup1" = \'Y\' THEN \'0\'
//		WHEN T1."QryGroup2" = \'Y\' THEN \'1\'
//		WHEN T1."QryGroup3" = \'Y\' THEN \'2\'
//		ELSE \'\'
//	END AS "Speciality",
//	T1."SalUnitMsr",
//	T1."U_UDF1" as "OldCode",
//	T4."CardCode" AS "VendorCode",
//    T4."CardName" AS "VendorName"
//FROM
//    AL_YASEEN_AGRI_PLIVE.INV1 T0
//JOIN
//    AL_YASEEN_AGRI_PLIVE.OITM T1 ON T0."ItemCode" = T1."ItemCode"
//JOIN
//    AL_YASEEN_AGRI_PLIVE.OINV T3 ON T0."DocEntry" = T3."DocEntry"
//JOIN
//    AL_YASEEN_AGRI_PLIVE.OBPL T2 ON T3."BPLId" = T2."BPLId"
//JOIN
//	AL_YASEEN_AGRI_PLIVE.OCRD T4 ON T1."CardCode" = T4."CardCode"
//WHERE
//    T3."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
//	AND T2."BPLId" IN ('. implode(', ', $sap_depts).')
//	AND T0."ItemCode" IN ('. implode(', ', $this->sap_codes).')
//GROUP BY
//	T0."ItemCode", T1."ItemName", T2."BPLName", T1."QryGroup1", T1."QryGroup2", T1."QryGroup3", T1."SalUnitMsr", T1."U_UDF1", T4."CardCode", T4."CardName"
//ORDER BY
//    "TotalSalesAmount" DESC
//    ) as tbl1';

                    $sql = 'SELECT
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount" ,
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
          SUM(SUM("NetSalesAmountLC"))
        OVER (PARTITION BY "ItemCode") AS "GroupTotalSales",
             SUM(SUM("GrossProfitLC"))
        OVER (PARTITION BY "ItemCode") AS "GroupGrossProfit"

FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName"
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';
                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')

WHERE "ItemDescription" IS NOT NULL

GROUP BY "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName"

--ORDER BY "ItemCode"';

                    $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . ', "ItemCode"';

//                    dd($sql);


                }
                else if ($this->report_type == "byDepartment") {
//

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
   SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
 (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",

     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",

SUM(SUM("NetSalesAmountLC"))
OVER (PARTITION BY "ItemCode") AS "GroupTotalSales",

SUM(SUM("GrossProfitLC"))
OVER (PARTITION BY "ItemCode") AS "GroupGrossProfit",

       --  SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
        --OVER (PARTITION BY "ItemCode") AS "GroupGrossProfitPer"

    (
    SUM(SUM("GrossProfitLC"))
    OVER (PARTITION BY "ItemCode")
    /
    NULLIF(
        SUM(SUM("NetSalesAmountLC"))
        OVER (PARTITION BY "ItemCode"),
        0
    )
) * 100 AS "GroupGrossProfitPer"

FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
	WHEN "DocumentTypeCode" = 13 THEN 1
	WHEN "DocumentTypeCode" = 14 THEN -1
	ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';
                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';

//order By "TotalSalesAmount"';

                    if($sortBy == 'code'){
                        $sql .='ORDER BY "ItemCode" ' . $direction . '';
                    }
                    else {

                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }
//                    dd($sql);

                }

                else if ($this->report_type == "byItemGroup") {

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",

SUM(SUM("NetSalesAmountLC"))
OVER (PARTITION BY "ItemGroup") AS "GroupTotalSales",

SUM(SUM("GrossProfitLC"))
OVER (PARTITION BY "ItemGroup") AS "GroupGrossProfit",


         SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
        OVER (PARTITION BY "ItemGroup") AS "GroupGrossProfitPer"

FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';
                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';

                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }

                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';

                    if($sortBy == 'code'){

                        $sql .='ORDER BY "ItemGroup" ' . $direction . ',"ItemCode" ';
                    }

//ORDER BY "ItemGroup","ItemCode"';
                    else {
                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }
//                    dd($sql);
                }
                else if ($this->report_type == "bySpeciality") {

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",
SUM(SUM("NetSalesAmountLC"))
OVER (PARTITION BY  "Speciality") AS "GroupTotalSales",

SUM(SUM("GrossProfitLC"))
OVER (PARTITION BY  "Speciality") AS "GroupGrossProfit"

FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';
                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';

                    if($sortBy =='code'){
                        $sql .= 'ORDER BY "Speciality" ' . $direction . ',"ItemCode"';

                    }

//ORDER BY "Speciality","ItemCode"';
                    else {

                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }
//                    dd($sql);

                }




                else if ($this->report_type == "byMarketingType") {

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",

    SUM(SUM("NetSalesAmountLC"))
  --  OVER (PARTITION BY "ItemGroup", "mrkt_type") AS "GroupTotalSales",
    OVER (PARTITION BY  "mrkt_type") AS "GroupTotalSales",

    SUM(SUM("GrossProfitLC"))
    OVER (PARTITION BY  "mrkt_type") AS "GroupGrossProfit",

         SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
        OVER (PARTITION BY "mrkt_type") AS "GroupGrossProfitPer"


FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';
                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';


                    if($sortBy =='code') {

                        $sql .='ORDER BY "mrkt_type","ItemCode"';
                    }
                    else {

//                    dd($sql);

                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }
                }
                else if ($this->report_type == "byVendor") {

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",
          SUM(SUM("NetSalesAmountLC"))
        OVER (PARTITION BY "VendorCode") AS "GroupTotalSales",
             SUM(SUM("GrossProfitLC"))
        OVER (PARTITION BY "VendorCode") AS "GroupGrossProfit",

         SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
        OVER (PARTITION BY "VendorCode") AS "GroupGrossProfitPer"
FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';

                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';

                    if($sortBy == 'code'){

                        $sql .='ORDER BY "VendorCode" ' . $direction . ',"ItemCode"';
                    }

                    else {
                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }

//---
//
//ORDER BY "VendorCode","ItemCode"';
//                    dd($sql);
                }
                else if ($this->report_type == "byCustomer") {

                    $sql = 'SELECT
	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
	"BusinessPartnerName", "BusinessPartnerCode",
	"ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") as "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
    (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
     "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
"mrkt_type",
"IsInventoryItem",
          SUM(SUM("NetSalesAmountLC"))
        OVER (PARTITION BY "BusinessPartnerCode") AS "GroupTotalSales",
             SUM(SUM("GrossProfitLC"))
        OVER (PARTITION BY "BusinessPartnerCode") AS "GroupGrossProfit",

       SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
       OVER (PARTITION BY "BusinessPartnerCode") AS "GroupGrossProfitPer"
FROM (

SELECT *, CASE
		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
	T2."CardCode" AS "VendorCode",
"DefaultPreferredVendor" AS "VendorName",
--
CASE
WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN T2."QryGroup31" = \'Y\' THEN \'fan - 0mobedat 1\'
WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
"InvntItem" AS "IsInventoryItem"
--
FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
CASE
WHEN "DocumentTypeCode" = 13 THEN 1
WHEN "DocumentTypeCode" = 14 THEN -1
ELSE 0
END AS "TransCount",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBy0SalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'';

                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }
                    $sql .= ' AND "BranchCode" IN ('. implode(', ', $sap_depts).')
AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
) T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode2" = T2."ItemCode"
WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
                    if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                    $sql .= ')
WHERE "BranchName" IS NOT NULL

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "BusinessPartnerName", "BusinessPartnerCode", "ItemCode",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
	"SalUnitMsr",
"OldCode",
"VendorCode",
"VendorName",
---
"mrkt_type",
"IsInventoryItem"';


                    if($sortBy =='code') {


                        $sql .= 'ORDER BY "BusinessPartnerCode" ' . $direction . ',"ItemCode"';
                    }
                    else {
                        $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
                    }
//                    dd($sql);
                }
                else if ($this->report_type == "byEmployee") {

                    $sql= 'SELECT
	"SlpCode", -- Added
	"Memo", -- Added
    "SlpName", -- Added
    "BranchName" AS "Branch",
    "BranchCode",
    "BranchRegistrationNumber" AS "Department",
    "BusinessPartnerName",
    "BusinessPartnerCode",
    "ItemCode2" AS "ItemCode",
    "ItemDescription" AS "ItemName",
    "ItemGroup",
    SUM("TransCount") AS "TransCount",
    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
    SUM("NetSalesAmountLC")  AS "TotalSalesAmount" ,
    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
    SUM("GrossProfitLC") AS "GrossProfit",
    SUM("NetSalesAmountLC")-SUM("GrossProfitLC") AS "Cost",
    (SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100 AS "GrossProfitPer",

    "Speciality",
    "SalUnitMsr",
    "OldCode",
    "VendorCode",
    "VendorName",
    "mrkt_type",
    "IsInventoryItem",
          SUM(SUM("NetSalesAmountLC"))
        OVER (PARTITION BY "SlpCode") AS "GroupTotalSales",
             SUM(SUM("GrossProfitLC"))
        OVER (PARTITION BY "SlpCode") AS "GroupGrossProfit",

--SUM("GrossProfitLC") OVER (PARTITION BY "SlpCode")/NULLIF(SUM("NetSalesAmountLC") OVER (PARTITION BY "SlpCode"), 0) * 100 AS "GroupGrossProfitPer"


         SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
        OVER (PARTITION BY "SlpCode") AS "GroupGrossProfitPer"

FROM (
    SELECT
        T1.*,
        TS."SlpCode",
        TS."SlpName", -- Added
        TS."Memo",    -- Added
        T2."SalUnitMsr",
        CASE
            WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
            WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
            WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
            ELSE \'\'
        END AS "Speciality",
        CASE WHEN T2."U_UDF1" IS NULL THEN T1."ItemCode2" ELSE T2."U_UDF1" END AS "OldCode",
        T2."CardCode" AS "VendorCode",
        T1."DefaultPreferredVendor" AS "VendorName",
        CASE
            WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
            WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
            WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
            WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
            WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
            WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
            WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
            WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
            WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
            ELSE \'general\'
        END AS "mrkt_type",
        T2."InvntItem" AS "IsInventoryItem"
    FROM (
        SELECT
            "BranchName", "BranchCode", "BranchRegistrationNumber",
            "BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName", "BusinessPartnerName", "BusinessPartnerCode",
            "CancellationStatus", "DocumentDate",
            "DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
            "ItemGroup", "DefaultPreferredVendor", "ItemCode" AS "ItemCode2", "ItemDescription",
            "SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
            CASE
                WHEN "DocumentTypeCode" = 13 THEN 1
                WHEN "DocumentTypeCode" = 14 THEN -1
                ELSE 0
            END AS "TransCount",
            SUM("GrossProfitSC") AS "GrossProfitSC",
            SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
            SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
            SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
            SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"
        FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
       WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
            AND "DocumentTypeCode" != \'17\'
            AND "DocumentTypeCode" != \'15\' ';


                    if ($customer_type != 'customer_all') {
                        $sql .= ' AND "BusinessPartnerCode" = \''.$customer_type.'\'';
                    }


                    $sql .='AND "BranchCode" IN ('. implode(', ', $sap_depts).')
            AND "ItemCode" IN ('. implode(', ', $this->sap_codes).')
        GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
            "BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName", "BusinessPartnerName", "BusinessPartnerCode",
            "CancellationStatus", "DocumentDate",
            "DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
            "ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
            "SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
    ) T1
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
    RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T1."ItemCode2" = T2."ItemCode"
    WHERE T2."ItemCode" IN ('. implode(', ', $this->sap_codes).')';
         if ($emps_type != 'employees_all') {
                        $sql .= ' AND TS."Memo" = \''.$emps_type.'\'';
                    }
                $sql .= '
            --AND TS."Memo" = \'10036\'
    ) AS FinalData
WHERE
    "BranchName" IS NOT NULL
GROUP BY
    "BranchName", "BranchCode", "BranchRegistrationNumber", "BusinessPartnerName", "BusinessPartnerCode", "ItemCode2",
    "ItemDescription",
    "ItemGroup",
    "Speciality",
    "SalUnitMsr",
    "OldCode",
    "VendorCode",
    "VendorName",
    "mrkt_type",
    "IsInventoryItem",
    "SlpCode",
    "SlpName", -- Added
    "Memo"';
// ORDER BY
// "EmployeeTotalSales" \''.$this->orderByTotalSales.'\'
// --"SlpCode",
//    ,"ItemCode2" ';
//
//         if($this->sortDir == 'DESC') {
//             $sql .=' ORDER BY
//              --\''.$this->sortBy.'\' DESC,
//                    "EmployeeTotalSales" DESC,
//                   --"EmployeeGrossProfit" DESC,
//                  --"GroupGrossProfitPer" DESC,
//                        "ItemCode2"';
//                             }
//
//             else{
//              $sql .=' ORDER BY
//            --  \''.$this->sortBy.'\' ASC,
//                     "EmployeeTotalSales" ASC,
//                       -- "EmployeeGrossProfit" ASC,
//                       --"GroupGrossProfitPer" ASC,
//                        "ItemCode2"';
//                 }

               if($sortBy == 'code')
               {
                   $sql .=' ORDER BY "SlpCode" ' . $direction . ', "BusinessPartnerCode", "ItemCode2"';
               }

               else {
                   $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . ', "ItemCode2"';

               }
                }

//                dd($sql);

                $result = odbc_exec($conn, $sql);
                if (!$result)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {
//                dd(odbc_fetch_array($result));

                    while ($row = odbc_fetch_array($result)) {
                        array_push($this->sap_results, $row);
                    }

                }
                odbc_close($conn);
//                dd($this->sap_results);
            }
        }

    }

    public function product_lists() {

        $this->products_codes = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $productQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode", T0."ItemName" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';

            $result = odbc_exec($conn, $productQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->products_codes, $row);
                }
            }

//            dd($this->itemGrp);
            odbc_close($conn);
        }
    }

    public function changeGroupType($data) {
//        dd($data);
        $this->group_type = $data;
        $this->emit('finished-categories2');
    }

    public function employees() {

        $this->emps = User::join('user_groups', 'user_groups.id', 'users.group')
//            ->where('branches', 'like', '%"'.$branch.'"%')
            ->where('sales_dept_code', '<>', '')
            ->where('is_active', '1')
            ->whereIn('write_product_target', ['1', '2'])
            ->select('users.id', 'users.emp_code', 'users.name', 'users.sales_dept_code')
            ->get();

        /*
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
        */

        return $this->emps;
    }
}
