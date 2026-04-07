<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
//use Livewire\WithPagination;
//use Illuminate\Pagination\LengthAwarePaginator;

class ItemsSalesByBranch extends Component
{
//    use WithPagination;

//    protected $paginationTheme = 'tailwind';

    //   public $page = 1;
    //   public $perPage = 20;
    public $start_date;
    public $end_date;
    public $dept_id = ['dept_all'];
    public $group_type = 'groups_all';
    public $cat_type = [];
    public $sp_type = [];
    public $vendor_type ;
    public $search_type;
    public $product_code;
    public $marketing_type = [];
    public $customer_type='customer_all';
    public $emps_type ='employees_all';
    public $sortBy = 'ItemCode';
    public $sortDir = 'ASC';
    public $sap_codes = [];
    public $group_results;
    public $sap_results;
    public $totalSalesByItem;
    public $show_msg = false;
    public $branches = [];
    public $vendor_list = [];
    public $customer_list = [];
    public $emps = null;
    public $currentGroup = null, $currentItemName = null,
        $itemGroup_item_total = 0, $itemGroup_cost_total = 0, $itemGroup_gross_total = 0, $itemGroup_quantity_total = 0, $itemGroup_trans_total = 0,
        $itemGroup_item_subtotal = 0, $itemGroup_cost_subtotal = 0, $itemGroup_gross_subtotal = 0, $itemGroup_quantity_subtotal = 0, $itemGroup_trans_subtotal = 0,
        $itemGroup_itemName_subtotal = 0, $itemGroup_costName_subtotal = 0, $itemGroup_grossName_subtotal = 0;

    public $grouped;
    public $totalsByBranch;
    public $empKey;
    public $allBranches = [];
    public $salesIndex;
    public $employees = [];
    public $counter = 0,


        $item_code = "*",
        $item_group_code = "*",
        $item_group_itemCode_code = "*",
        $speciality_code = "*",
        $marketing_type_code = "*",
        $vendor_code = "*";
    public  $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' => '5', '0104' => '6', '0105' => '7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' => '16', '0203' => '17'];
    public $expanded = [];
    public     $branchOptions = [

        '10' => [['0102', 'جدة']],
        '7'  => [['0103', 'الرياض']],
        '13' => [['0104', 'وادي الدواسر']],
        '4'  => [['0105', 'الجوف']],
        '6'  => [['0106', 'الدمام']],
        '5'  => [['0107', 'الخرج']],
        '12' => [['0108', 'نجران']],
        '11' => [['0109', 'حائل']],
        '9'  => [['0110', 'تبوك']],
        '8'  => [['0111', 'القصيم']],
        '505'=> [['0112', 'ساجر']],
        '14'=>'0201',
        '16'=>['0202'],
        '3' => [
            ['0101', 'الاحساء'],
            ['0201', 'مزرعة الدالوة'],
            ['0202', 'مزرعة الفضول'],
            ['0203', 'مزرعة الدلم'],
            ['0001', 'المركز الرئيسي'],

        ],
    ];

//    public function gotoPage($page)
//    {
//        $this->page = $page;
//
//    }
    public $all_option;

//    public $itemGroup_item_subtotal = 0, $itemGroup_cost_subtotal = 0, $itemGroup_gross_subtotal = 0, $itemGroup_quantity_subtotal = 0, $itemGroup_trans_subtotal = 0;

    protected $rules = [
        'start_date' => 'required',
        'end_date' => 'required',
        'dept_id' => 'required',
        'product_code' => 'required_if:search_type,item_code_search',

    ];

    protected $messages = [
        'start_date.required' => ' مطلوب',
        'end_date.required' => ' مطلوب',
        'dept_id.required' => ' مطلوب',
        'product_code.required_if' => 'مطلوب'

    ];
    public $query;

    public function mount(){

        $this->empKey =null;
        $this->product_lists();
        $this->employees();
        $this->vendors();
        $this->customers();
        $this->query = User::where('id', Auth::id())->first();
        $this->branches = json_decode($this->query->branches);
        $this->all_option = 'dept_id';
    }

    public function generateReport()
    {
        $this->branches = [];
        $this->expanded = [];


        $this->resetExpandedData();
        foreach ($this->expanded as $itemCode => $expandedItems) {
            if ($expandedItems) {
                $this->loadBranches($itemCode);
            }
        }
//        $sql = '';        // RESET
//        $bindings = [];   // RESET
        // Now all values are AVAILABLE
//        dd([
//            $this->start_date,
//            $this->end_date,
//            $this->dept_id,
//            $this->group_type,
//            $this->cat_type,
//        ]);



        $this->create_report(
            $this->start_date,
            $this->end_date,
            $this->dept_id,

            $this->group_type ,
            $this->cat_type ,
            $this->sp_type ,
            $this->vendor_type ,
//            $this->report_type,
            $this->search_type,
            $this->product_code,
            $this->marketing_type,
            $this->customer_type,
            $this->emps_type
        );
    }

//    public function paginatedResults()
//    {
//        $perPage = 10;
//        $page = request()->get('page', 1);
//
//        $items = collect($this->group_results);
//
//        return new LengthAwarePaginator(
//            $items->forPage($page, $perPage),
//            $items->count(),
//            $perPage,
//            $page,
//            ['path' => request()->url()]
//        );
//    }
    public function updatedSortDir($value)
    {
//        dd($value);
        logger('SORT:', [$this->sortDir]);
        $this->generateReport();
    }

    public function updatedSortBy($value)
    {
        logger('SORT:', [$this->sortBy]);
        $this->generateReport();
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
    // Component property

    protected function resetExpandedData()
    {
        $this->branches = [];
        $this->expanded = [];

    }



    public function render()
    {
        return view('livewire.items.items-sales-by-branch')->layout('layouts.dashboard');
    }

//    public function create_report(){
    public function resetProductCodes()
    {
        foreach ([
                     'product_codes',
                     'group_codes',
                     'category_codes',
                     'speciality_codes',
                     'vendor_codes',
                     'marketing_codes',
                     'selected_products'
                 ] as $property) {

            if (property_exists($this, $property)) {
                $this->{$property} = [];
            }
        }
    }

    public function create_report($start_date, $end_date, $dept_id, $group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type, $customer_type, $emps_type) {


//        dd($this->vendor_type);
        $report_type ='byDepartment';

//        dd('here?');

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->validate();
        $this->show_msg = false;
        $this->resetProductCodes();
        //    $this->report_type = $report_type;
        //  $this->scribes_results = [];
        $this->sap_results = [];
        $this->group_results = [];

//        $this->productCodes('commerce',['cat_all'], ['sp_all'], 'vendor_all', 'advanced_search', null, ['marketing_all']);
        $this->productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type);

        if (is_null($start_date) == false && is_null($end_date) == false) {


            $this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type);

            logger('SAP COUNT: ' . count($this->sap_results));
//            dd($this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type));

        }

        else {
            dd('coco');
        }

//        dd($this->sap_results);
        $sap_collection = collect($this->sap_results);
        $fullSap = collect($this->sap_results);

//
        $this->group_results = collect($this->sap_results)
            ->groupBy('ItemCode')
            ->map(function ($itemRows) use ($fullSap) {

                $itemCode = $itemRows->first()['ItemCode'];

                // 🔒 total never affected
                $totalQuantitySale = $itemRows->first()['TotalQuantitySale'];

                // ⭐ FIXED BEST BRANCH (from FULL data, not filtered)
                $fixedBestBranch = $fullSap
                    ->where('ItemCode', $itemCode)
                    ->firstWhere('IsBestBranch', 'Y');

                return [
                    'ItemCode'   => $itemRows->first()['ItemCode'],
                    'ItemName'   => $itemRows->first()['ItemName'],
                    'VendorName' => $itemRows->first()['VendorName'],
                    'Unit'       => $itemRows->first()['Unit'],
                    'mrkt_type'  => $itemRows->first()['mrkt_type'],
                    'Speciality' => $itemRows->first()['Speciality'],
                    'ItemGroup'  => $itemRows->first()['ItemGroup'],

                    // ✅ unchanged
                    'TotalQuantitySale' => $totalQuantitySale,
                    'branches' => $itemRows->groupBy('BPLName'),

                    // 🆕 added (SAFE)
                    'fixedBestBranch' => $fixedBestBranch,
                ];
            })
            ->values();


        $this->group_results = collect($this->sap_results)
            ->groupBy('ItemCode')
            ->map(function ($itemRows) {



                // 1️⃣ TOTALS — NEVER FILTERED
                $totalQuantitySale = $itemRows->first()['TotalQuantitySale'];

                // 2️⃣ BEST BRANCH — FROM FULL DATA
                $bestBranchRow = $itemRows->firstWhere('IsBestBranch', 'Y');

                $itemMeta = $itemRows;

                // Separate best branch
                $branches = $itemRows->groupBy('BPLName');
//                dd($branches->flatten(1));
                $bestBranch = $branches->flatten(1)->firstWhere('IsBestBranch', 'Y'); // flatten(1) merge all branch employees into one collection
                $otherBranches = $branches->reject(function ($b) {
                    return $b->first()['IsBestBranch'] === 'Y';
                });
//                dd($bestBranch);
//                dd($branches->first()->where('IsBestBranch', 'Y')->first());
                return [
                    'ItemCode'   => $itemMeta->first()['ItemCode'],
                    'ItemName'   => $itemMeta->first()['ItemName'],
                    'VendorName' => $itemMeta->first()['VendorName'],
                    'Unit' => $itemMeta->first()['Unit'],
                    'mrkt_type' => $itemMeta->first()['mrkt_type'],
                    'Speciality' => $itemMeta->first()['Speciality'],
                    'ItemGroup' => $itemMeta->first()['ItemGroup'],
                    'TotalQuantitySale' =>$itemMeta->first()['TotalQuantitySale'],
                    'bestBranch' => $bestBranch ? $bestBranch : null,
                    'branches' => $itemRows
                        ->groupBy('BPLName'),


                ];
            })
            ->values();

        $results = collect($sap_collection);

        $fullSap = collect($this->sap_results);

        $this->group_results = collect($this->sap_results)
            ->groupBy('ItemCode')
            ->map(function ($itemRows) use ($fullSap) {

                $itemCode = $itemRows->first()['ItemCode'];

                // 🔒 TOTAL NEVER AFFECTED
                $totalQuantitySale = $itemRows->first()['TotalQuantitySale'];

                // ⭐ FIXED BEST BRANCH (FROM FULL DATA)
                $fixedBestBranch = $fullSap
                    ->where('ItemCode', $itemCode)
                    ->firstWhere('IsBestBranch', 'Y');

                // 🔁 YOUR EXISTING LOGIC (UNCHANGED)
                $branches = $itemRows->groupBy('BPLName');
                $bestBranch = $branches->flatten(1)->firstWhere('IsBestBranch', 'Y');

                return [
                    'ItemCode'   => $itemRows->first()['ItemCode'],
                    'ItemName'   => $itemRows->first()['ItemName'],
                    'VendorName' => $itemRows->first()['VendorName'],
                    'Unit'       => $itemRows->first()['Unit'],
                    'mrkt_type'  => $itemRows->first()['mrkt_type'],
                    'Speciality' => $itemRows->first()['Speciality'],
                    'ItemGroup'  => $itemRows->first()['ItemGroup'],
                    'TransCount' => $itemRows->sum('EmployeeTransCount'),

                    // ✅ unchanged
                    'TotalQuantitySale' => $totalQuantitySale,
                    'branches' => $branches,
                    'bestBranch' => $bestBranch ?: null,

                    // 🆕 SAFE ADDITION
                    'fixedBestBranch' => $fixedBestBranch,
                ];
            })
            ->values();

        $this->group_results = $this->group_results->map(function ($item) {
            $itemCode = $item['ItemCode'];

            // Build all unique branches for this item
            $itemSales = collect($this->sap_results)
                ->where('ItemCode', $itemCode)
                ->groupBy('BPLId');

            $allBranches = collect($this->sap_results)
                ->where('ItemCode', $itemCode)
                ->unique('BPLId')
                ->map(fn($r) => [
                    'BPLId'   => $r['BPLId'],
                    'BPLName' => $r['BPLName'],
                ])
                ->values();

            $totalQuantity = $itemSales->flatten(1)->sum('EmployeeTotalQty');

            $branches = $allBranches->map(function ($branch) use ($itemSales, $totalQuantity) {
                $branchRows = $itemSales->get($branch['BPLId'], collect());

                $employees = $branchRows
                    ->groupBy('SlpCode')
                    ->map(fn($empRows) => [
                        'EmployeeCode' => $empRows->first()['SlpCode'],
                        'EmployeeName' => $empRows->first()['SlpName'],
                        'Quantity'     => $empRows->sum('EmpQty'),
                        'TransCount'   => $empRows->sum('EmployeeTransCount'),
                    ])
                    ->values();

                return [
                    'BranchId' => $branch['BPLId'],
                    'BranchName' => $branch['BPLName'],
                    'TransCount' => $branchRows->sum('EmployeeTransCount'),
                    'TotalQuantitySaleByBranch' => $branchRows->sum('EmployeeTotalQty'),
                    'TotalSalesPer' => $totalQuantity ? $branchRows->sum('EmployeeTotalQty') / $totalQuantity * 100 : 0,
                    'employees_loaded' => true,
                    'employees' => $employees,
                ];
            })
                ->sortByDesc('TotalQuantitySaleByBranch')
                ->values();

            $item['branches'] = $branches;

            return $item;
        });


//                $this->group_results = collect($sap_collection)
//            ->groupBy('ItemCode')
//            ->map(function ($itemRows) {
//
//
//                $itemMeta = $itemRows->first();
//
//                return [
//                    'ItemCode'   => $itemMeta['ItemCode'],
//                    'ItemName'   => $itemMeta['ItemName'],
//                    'VendorName' => $itemMeta['VendorName'],
//                    'Unit' => $itemMeta['Unit'],
////                    'CardName'   => $itemMeta['cardName'],
////                    'SlpName'    => $itemMeta['SlpName'] ,   // ✅ works now
////                    'BranchName' => $itemMeta['branchname'],
//                    'mrkt_type' => $itemMeta['mrkt_type'],
//                    'TotalQuantitySale' => $itemMeta['TotalQuantitySale'],
//                    'TotalQuantitySaleByBranch' => $itemMeta['TotalQuantitySaleByBranch'],
//                    'TotalSalesPer' => $itemMeta['TotalSalesPer'],
//                    'EmployeeName'    =>  $itemMeta['SlpName'] ,
//                    'ItemGroup' => $itemMeta['ItemGroup'],
//                    'Speciality' => $itemMeta['Speciality'],
//
//                    'branches' => $itemRows
//                        ->groupBy('BranchName')
//                        ->map(function ($branchRows, $branchName) {
//                            return [
//                                'BranchName' => $branchName,
//                                'TotalQuantitySaleByBranch' =>
//                                    $branchRows->sum('TotalQuantitySaleByBranch'),
//
//                                'TotalQuantitySale' => $branchRows->first()['TotalQuantitySale'],
//                                'TotalSalesPer' =>
//                                    $branchRows->sum('TotalSalesPer'),
//                                'EmployeeQuantity' =>$branchRows->first()['TotalQuantitySaleByBranch'],
//                                'employees' => $branchRows
////                                    ->unique()
//                                    ->groupBy('EmployeeName')
////                                    ->map(function ($row)
////                                    return [
////                                        'EmployeeName' => $row->first()['EmployeeName'],
//////                                        'Quantity'  =>  $row->sum('TotalQuantitySaleByBranch'),
////                                    ];
//                                        ->map(function ($empRows, $empName) {
//                                        return [
//                                            'EmployeeName' => $empName,
//                                            'Quantity' => $empRows->first()['TotalQuantitySaleByBranch'], // summed per employee
//                                        ];
//                                    })
//                                ->values()
//                                    ->values(),
//                                'BranchTotal' => $branchRows->sum('TotalQuantitySaleByBranch'),
//                            ];
//                        })
//                        ->values()
//                ];
//            })
//            ->values();

//dd($this->group_results);
        $this->show_msg = true;
        $this->emit('finished');
    }

    public function customers()
    {

        $this->customer_list = [];
//        if (empty($this->branches)){
//            $this->branches =['0101','0102','0103','0104','0105','0106','0107','0108','0109','0110','0111','0112','0001','0202','0201','0203'];
//            }

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
//            dd($this->branches);
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
FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\' --AND ('.$query.')
ORDER BY "CardCode"';

//            dd($customerQuery);

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

//    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type)
//    {
//        $this->sap_codes = [];
//
//        if (!extension_loaded('odbc')) {
//            die('ODBC extension not enabled / loaded');
//        }
//
//        $driver   = env('DB_CONNECTION_FOURTH');
//        $host     = env('DB_HOST_FOURTH');
//        $db_name  = env('DB_DATABASE_FOURTH');
//        $username = env('DB_USERNAME_FOURTH');
//        $password = env('DB_PASSWORD_FOURTH');
//
//        $conn = odbc_connect(
//            "Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;",
//            $username,
//            $password,
//            SQL_CUR_USE_ODBC
//        );
//
//        if (!$conn) {
//            dd("ODBC Connection failed: " . odbc_errormsg());
//        }
//
//        // -----------------------------
//        // 1) Build Base Query
//        // -----------------------------
//        $categoryQuery = '
//        SELECT DISTINCT T0."ItemCode"
//        FROM AL_YASEEN_AGRI_PLIVE."OITM" T0
//        JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1
//            ON T0."ItmsGrpCod" = T1."ItmsGrpCod"
//        WHERE 1 = 1
//    ';
//
//        // -----------------------------
//        // 2) Search by Item Code
//        // -----------------------------
//        if ($search_type === "item_code_search" && !empty($product_code)) {
//
//            if (is_array($product_code)) {
//                $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $product_code);
//                $categoryQuery .= ' AND T0."ItemCode" IN (' . implode(',', $escaped) . ')';
//            } else {
//                $safe = "'" . str_replace("'", "''", $product_code) . "'";
//                $categoryQuery .= " AND T0.\"ItemCode\" = $safe";
//            }
//        }
//
//        // -----------------------------
//        // 3) Advanced Search
//        // -----------------------------
//        if ($search_type === "advanced_search") {
//
//            // Group type
//            if ($group_type === "commerce") {
//                $categoryQuery .= ' AND (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\'
//                                    OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\'
//                                    OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\'
//                                    OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
//            } elseif ($group_type === "farms") {
//                $categoryQuery .= ' AND T0."ItemCode" LIKE \'30%\'';
//            } elseif ($group_type === "sundries") {
//                $categoryQuery .= ' AND T0."ItemCode" LIKE \'99%\'';
//            } elseif ($group_type === "groups_all") {
//                $categoryQuery .= ' AND (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\'
//                                    OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\'
//                                    OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\'
//                                    OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\'
//                                    OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
//            }
//
//            // Category filter
//            if (!empty($cat_type) && !in_array('cat_all', $cat_type)) {
//                $categoryQuery .= ' AND T0."ItmsGrpCod" IN (' . implode(',', $cat_type) . ')';
//            }
//
//            // Speciality filter
//            if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
//                $categoryQuery .= ' AND (';
//                $conditions = [];
//                foreach ($sp_type as $s) {
//                    $conditions[] = 'T0."QryGroup' . (intval($s) + 1) . '" = \'Y\'';
//                }
//                $categoryQuery .= implode(' OR ', $conditions) . ')';
//            }
//
//            // Marketing type
//            if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type)) {
//                $categoryQuery .= ' AND (';
//                $conditions = [];
//                foreach ($marketing_type as $m) {
//                    $conditions[] = 'T0."QryGroup' . intval($m) . '" = \'Y\'';
//                }
//                $categoryQuery .= implode(' OR ', $conditions) . ')';
//            }
//
//            // Vendor filter
//            if (!empty($vendor_type) && $vendor_type !== 'vendor_all') {
//                $safeVendor = "'" . str_replace("'", "''", $vendor_type[0]) . "'";
//                $categoryQuery .= " AND T0.\"CardCode\" = $safeVendor";
//            }
//        }
//
//
//        // -----------------------------
//        // 4) Execute Query
//        // -----------------------------
//        $result = odbc_exec($conn, $categoryQuery);
//
//        while ($row = odbc_fetch_array($result)) {
//            $this->sap_codes[] = "'" . $row['ItemCode'] . "'";
//        }
//
//        odbc_close($conn);
//    }


    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type)
    {
        // Clean filters
        $cat_type       = array_filter((array) $cat_type);
        $sp_type        = array_filter((array) $sp_type);
        $vendor_type    = array_filter((array) $vendor_type);
        $marketing_type = array_filter((array) $marketing_type);
        $group_type= array_filter((array) $group_type);
//        $product_code= array_filter((array) $product_code);
        $marketing_type= array_filter((array) $marketing_type);


        $this->sap_codes = [];

        $driver   = env('DB_CONNECTION_FOURTH');
        $host     = env('DB_HOST_FOURTH');
        $db_name  = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect(
            "Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;",
            $username,
            $password,
            SQL_CUR_USE_ODBC
        );

        if (!$conn) {
            dd("ODBC Connection failed: " . odbc_errormsg());
        }

        // Base
        $categoryQuery = '
        SELECT DISTINCT T0."ItemCode"
        FROM AL_YASEEN_AGRI_PLIVE."OITM" T0
        JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1
            ON T0."ItmsGrpCod" = T1."ItmsGrpCod"
        WHERE 1=1
    ';

        // Conditions array
        $conditions = [];

        // Search by item code
        if ($search_type === "item_code_search" && !empty($product_code)) {

            if (is_array($product_code)) {
                $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $product_code);
                $conditions[] = 'T0."ItemCode" IN (' . implode(',', $escaped) . ')';
            } else {
                $safe = "'" . str_replace("'", "''", $product_code) . "'";
                $conditions[] = "T0.\"ItemCode\" = $safe";
            }
        }

        // Advanced search
        if ($search_type === "advanced_search") {

            // Group type
            if ($group_type === "commerce") {
                $conditions[] = '(T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\'
                              OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\'
                              OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\'
                              OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
            }

            if (!empty($cat_type) && !in_array('cat_all', $cat_type)) {
                $conditions[] = 'T0."ItmsGrpCod" IN (' . implode(',', $cat_type) . ')';
            }

            if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
                $sp = array_map(fn($s) => 'T0."QryGroup' . (intval($s) + 1) . '" = \'Y\'', $sp_type);
                $conditions[] = '(' . implode(' OR ', $sp) . ')';
            }

            if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type)) {
                $mk = array_map(fn($m) => 'T0."QryGroup' . intval($m) . '" = \'Y\'', $marketing_type);
                $conditions[] = '(' . implode(' OR ', $mk) . ')';
            }

            if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type)) {
                $safeVendor = "'" . str_replace("'", "''", $vendor_type[0]) . "'";
                $conditions[] = "T0.\"CardCode\" = $safeVendor";
            }
        }

        // Add conditions to query
        if (count($conditions) > 0) {
            $categoryQuery .= ' AND ' . implode(' AND ', $conditions);
        }

//        dd($categoryQuery);

        // Execute
        $result = odbc_exec($conn, $categoryQuery);

        while ($row = odbc_fetch_array($result)) {
            $this->sap_codes[] = "'" . $row['ItemCode'] . "'";
        }

        odbc_close($conn);
    }

//    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type)
//    {
//
////        dd($cat_type);
//
//        $this->scribes_codes = [];
//        $this->sap_codes = [];
//
//        if (!extension_loaded('odbc')) {
//            die('ODBC extension not enabled / loaded');
//        }
//
//        $driver = env('DB_CONNECTION_FOURTH');
//        $host = env('DB_HOST_FOURTH');
//        $db_name = env('DB_DATABASE_FOURTH');
//        $username = env('DB_USERNAME_FOURTH');
//        $password = env('DB_PASSWORD_FOURTH');
//
//        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);
//
//        if (!$conn) {
//            echo "Connection failed.\n";
//            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
//        } else {
//            $categoryQuery = '';
//
//            if ($search_type == "item_code_search") {
////                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" = \'' . $product_code . '\' OR T0."U_UDF1" = \'' . $product_code . '\'';
//                if (!empty($product_code)) {
//
//                    if (is_array($product_code)) {
//                        $escaped = array_map(fn($v) =>
//                            "'" . str_replace("'", "''", $v) . "'",
//                            $product_code
//                        );
//
//                        $categoryQuery = '
//            SELECT DISTINCT
//                T0."ItemCode",
//                T0."U_UDF1" AS "ScribeCode"
//            FROM AL_YASEEN_AGRI_PLIVE."OITM" T0
//            JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1
//                ON T0."ItmsGrpCod" = T1."ItmsGrpCod"
//            WHERE T0."ItemCode" IN ('.implode(',', $escaped).')
//               OR T0."U_UDF1" IN ('.implode(',', $escaped).')
//        ';
//                    }
//                }
//
//
//            }
//
//            else if ($search_type == "advanced_search") {
//
//                if ($group_type == "commerce") {
//                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
//                } elseif ($group_type == "farms") {
//                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
//                } elseif ($group_type == "sundries") {
//                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
//                } elseif ($group_type == "groups_all") {
//                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
//                }
//
//                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
//
//                    $categoryQuery .= ' AND T0."ItmsGrpCod" IN (' . implode(", ", $cat_type) . ')';
//                }
//
//                if ($sp_type != null && in_array('sp_all', $sp_type) == false && count($sp_type) != 0) {
////                $categoryQuery .= ' AND (T0."QryGroup1" = \''. (in_array('0', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup2" = \''. (in_array('1', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup3" = \''. (in_array('2', $sp_type) ? 'Y': 'N') .'\')';
////                dd($sp_type);
//                    if (count($sp_type) == 3) {
//                        $categoryQuery .= ' AND (T0."QryGroup1" = \'' . (in_array('0', $sp_type) ? 'Y' : 'N') . '\' OR T0."QryGroup2" = \'' . (in_array('1', $sp_type) ? 'Y' : 'N') . '\' OR T0."QryGroup3" = \'' . (in_array('2', $sp_type) ? 'Y' : 'N') . '\')';
//                    } else {
//                        //dd( $categoryQuery);
//                        $categoryQuery .= ' AND (';
//                        foreach ($sp_type as $key => $s) {
//                            if ($key === array_key_first($sp_type)) {
//                                $categoryQuery .= 'T0."QryGroup' . intval($s) + 1 . '" = \'Y\'';
//                            } else {
//                                $categoryQuery .= ' OR T0."QryGroup' . intval($s) + 1 . '" = \'Y\'';
//                            }
//                        }
//                        $categoryQuery .= ')';
//
//                        $difference = array_diff(['0', '1', '2'], $sp_type);
////                    dd($difference);
//
//                        if (count($difference) > 0) {
//
//                            $categoryQuery .= ' AND (';
//                            foreach ($difference as $key => $s) {
//                                if ($key === array_key_first($difference)) {
//                                    $categoryQuery .= 'T0."QryGroup' . intval($s) + 1 . '" = \'N\'';
//                                } else {
//                                    $categoryQuery .= ' OR T0."QryGroup' . intval($s) + 1 . '" = \'N\'';
//                                }
//                            }
//                            $categoryQuery .= ')';
//
//                        }
//
//                    }
////                    dd($categoryQuery);
//                }
//
////                dd($marketing_type);
//                if ($marketing_type != null && in_array('marketing_all', $marketing_type) == false && count($marketing_type) != 0) {
//
//                    if (count($marketing_type) == 9) {
//
//                        $categoryQuery .= ' AND (T0."QryGroup30" = \'' . (in_array('30', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup31" = \'' . (in_array('31', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup32" = \'' . (in_array('32', $marketing_type) ? 'Y' : 'N') . '\'  OR T0."QryGroup40" = \'' . (in_array('40', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup41" = \'' . (in_array('41', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup50" = \'' . (in_array('50', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup51" = \'' . (in_array('51', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup52" = \'' . (in_array('52', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup53" = \'' . (in_array('53', $marketing_type) ? 'Y' : 'N') . '\')';
//                    } else {
//                        $categoryQuery .= ' AND (';
//                        foreach ($marketing_type as $key => $s) {
//                            if ($key === array_key_first($marketing_type)) {
//                                $categoryQuery .= 'T0."QryGroup' . intval($s) . '" = \'Y\'';
//                            } else {
//                                $categoryQuery .= ' OR T0."QryGroup' . intval($s) . '" = \'Y\'';
//                            }
//                        }
//                        $categoryQuery .= ')';
//
//                        $difference = array_diff(['30', '31', '32', '40', '41', '50', '51', '52', '53'], $marketing_type);
////                    dd($difference);
//
//                        if (count($difference) > 0) {
//
//                            $categoryQuery .= ' AND (';
//                            foreach ($difference as $key => $s) {
//                                if ($key === array_key_first($difference)) {
//                                    $categoryQuery .= 'T0."QryGroup' . intval($s) . '" = \'N\'';
//                                } else {
//                                    $categoryQuery .= ' OR T0."QryGroup' . intval($s) . '" = \'N\'';
//                                }
//                            }
//                            $categoryQuery .= ')';
//
//                        }
//
//                    }
//                }
////dd($vendor_type[0]);
//
//                if ($vendor_type != 'vendor_all' && $vendor_type != null) {
//                    $categoryQuery .= ' AND (T0."CardCode" = \'' . $vendor_type[0] . '\')';
//                }
//            }
//
//
////            dd($categoryQuery);
//
//            $result = odbc_exec($conn, $categoryQuery);
//            if (!$result) {
//                echo "Error while sending SQL statement to the database server.\n";
//                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
//            } else {
//                while ($row = odbc_fetch_array($result)) {
//                    array_push($this->sap_codes, "'" . $row['ItemCode'] . "'");
//                    array_push($this->scribes_codes, "'" . $row['ScribeCode'] . "'");
//                }
//            }
//
////            dd($this->vendor_list);
//            odbc_close($conn);
//        }
//    }
    public function employees()
    {

        $this->emps = User::join('user_groups', 'user_groups.id', 'users.group')
//            ->where('branches', 'like', '%"'.$branch.'"%')
            ->where('sales_dept_code', '<>', '')
            ->where('is_active', '1')
            ->whereIn('write_product_target', ['1', '2'])
            ->select('users.id', 'users.emp_code', 'users.name', 'users.sales_dept_code')
            ->get();
        return $this->emps;
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
//            $vendorQuery = 'SELECT "CardCode" AS "VendorCode", "CardName" AS "VendorName" FROM AL_YASEEN_AGRI_PLIVE.OCRD WHERE "CardType" = \'S\'';
            $vendorQuery = 'SELECT
                          "CardCode" AS "VendorCode",
                           "CardName" AS "VendorName"
                            FROM AL_YASEEN_AGRI_PLIVE.OCRD
                            WHERE "CardType" = \'S\'
                             AND "CardCode" LIKE \'99%\'';

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
    public function sapQuery($start_date, $end_date, $departments, $customer_type, $emps_type)
    {


//        $from = ($this->page - 1) * $this->perPage + 1;
//        $to   = $this->page * $this->perPage;

        $sortBy = $this->sortBy;

        $direction = $this->sortDir;

        if (count($this->sap_codes) > 0) {

            $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' => '5', '0104' => '6', '0105' => '7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14'];
            $sap_depts = [];

            $scribe_depts = ['0001' => '2', '0101' => '3', '0102' => '10', '0103' => '7', '0104' => '13', '0105' => '4', '0106' => '6', '0107' => '5', '0108' => '12', '0109' => '11', '0110' => '9', '0111' => '8', '0112' => '505', '0201' => '15', '0202' => '500', '0203' => '504'];
            $user_depts = [];

            foreach ($this->branches as $value) {
                $user_depts = array_merge($user_depts, array_keys($scribe_depts, $value));
            }

//            dd($user_depts);


            if (in_array('dept_all', $departments)) {
//                $sap_depts = $depts;
//                dd('here');
                $sap_depts = array_intersect_key($depts, array_flip($user_depts));
                $sap_depts = ['0001' => '1', '0101' => '3', '0102' => '4','0103' => '5', '0104' => '6', '0105' => '7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' => '16', '0203' => '17'];
//                dd($sap_depts);
            } else {

                foreach ($departments as $department) {
                    array_push($sap_depts, $depts[$department]);
                }
//                dd($sap_depts);
            }

            if (!extension_loaded('odbc')) {
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

            if (!$conn) {
                // Try to get a meaningful error if the connection fails
                echo "Connection failed.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            } else {
//
//                dd($this->sp_type[0]);
////                dd($this->vendor_type[0]);
                $sql = '';        // 🔥 RESET
                $bindings = [];   // 🔥 RESET

//_____________________________________________________________________________________________________
                $cat_type = $this->cat_type;
//                $marketing_type = $this->marketing_type;
//                dd($this->marketing_type);
                $marketing_type = array_unique((array) $this->marketing_type);
                $sp_type        = array_unique((array) $this->sp_type);
                $vendor_type    = array_unique((array) $this->vendor_type);
//                $sp_type = $this->sp_type;
//                $sql = 'SELECT
//    X."ItemCode"                              AS "ItemCode",
//    X."ItemName"                              AS "ItemName",
//    --X."UgpEntry"                             AS "Unit",
//    X."VendorName"                            AS "VendorName",
//    X."CardName"                              AS "CardName" ,
//    X."SlpName"                               AS "SlpName" ,
//    V."ItemGroup",
//    V."UoMGroup" AS "Unit",
//
//  --  X."QryGroup1"                             AS "مميز0 ",
//  --  X."QryGroup2"                             AS "مميز 1",
//   -- X."QryGroup3"                             AS "مميز 2",
//
//  CASE
//WHEN X."QryGroup1" = \'Y\' THEN \'0\'
//WHEN X."QryGroup2" = \'Y\' THEN \'1\'
//WHEN X."QryGroup3" = \'Y\' THEN \'2\'
//ELSE \'\'
//END AS "Speciality",
//
//    CASE
//WHEN X."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
//WHEN X."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
//WHEN X."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
//WHEN X."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
//WHEN X."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
//WHEN X."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
//WHEN X."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
//WHEN X."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
//WHEN X."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
//ELSE \'general\'
//END AS "mrkt_type",
//    CASE
//    WHEN
//        ROW_NUMBER() OVER (
//            PARTITION BY X."ItemCode"
//            ORDER BY X."BranchQty" DESC
//        ) = 1
//    THEN \'Y\'
//    ELSE \'N\'
//END AS "IsBestBranch",
//
//
//CASE
//    WHEN ROW_NUMBER() OVER (
//        PARTITION BY X."ItemCode"
//        ORDER BY X."BranchQty" DESC
//    ) = 1
//    THEN \'Y\'
//    ELSE \'N\'
//END AS "IsBestEmployee",
//
//    SUM(X."BranchQty")
//        OVER (PARTITION BY X."ItemCode")      AS "TotalQuantitySale",
//
//
//    X."BPLId"                                 AS "BranchId",
//    X."BPLName"                               AS "BranchName",
//    X."BranchQty"                             AS "TotalQuantitySaleByBranch",
//
//    ROW_NUMBER() OVER (
//    PARTITION BY X."ItemCode"
//    ORDER BY X."BranchQty" DESC
//) AS "BranchRank",
//
//
//ROW_NUMBER() OVER (
//    PARTITION BY X."ItemCode"
//    ORDER BY X."BranchQty" DESC
//) AS "EmployeeRank",
//
//    ROUND(
//        (X."BranchQty" * 100.0) /
//        NULLIF(
//            SUM(X."BranchQty")
//                OVER (PARTITION BY X."ItemCode"),
//        0),
//    2)                                        AS "TotalSalesPer"
//
//FROM
//(
//    SELECT
//        Z."ItemCode",
//        Z."ItemName",
//        Z."UgpEntry",
//        Z."VendorName",
//        Z."CardName",
//        Z."SlpName",
//        Z."BPLName",
//        Z."BPLId",
//
//        Z."QryGroup1",
//        Z."QryGroup2",
//        Z."QryGroup3",
//        Z."QryGroup30",
//        Z."QryGroup31",
//        Z."QryGroup32",
//        Z."QryGroup40",
//        Z."QryGroup41",
//        Z."QryGroup50",
//        Z."QryGroup51",
//        Z."QryGroup52",
//        Z."QryGroup53",
//
//        SUM(Z."Qty") AS "BranchQty"
//    FROM
//    (
//        /* =======================
//           فواتير المبيعات
//        ======================== */
//        SELECT
//            T0."ItemCode",
//            T2."UgpEntry",
//            T2."ItemName",
//            V."CardName"        AS "VendorName",
//            C."CardName"        AS "CardName",
//            S."SlpName"         AS "SlpName",
//            B."BPLName",
//            B."BPLId",
//
//            T2."QryGroup1",
//            T2."QryGroup2",
//            T2."QryGroup3",
//            T2."QryGroup30",
//            T2."QryGroup31",
//            T2."QryGroup32",
//            T2."QryGroup40",
//            T2."QryGroup41",
//            T2."QryGroup50",
//            T2."QryGroup51",
//            T2."QryGroup52",
//            T2."QryGroup53",
//
//            SUM(T0."Quantity")  AS "Qty"
//
//        FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD C  ON T1."CardCode" = C."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD V  ON T2."CardCode" = V."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OSLP S  ON C."SlpCode"   = S."SlpCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL B  ON T1."BPLId"    = B."BPLId"
//
//        WHERE
//            T1."CANCELED" = \'N\'
//            AND T1."DocDate"  BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
//
//
//
//                if($this->product_code){
//                    $sql .= ' AND T0."ItemCode" = \'' . $this->product_code . '\' ';
//
//                }
//// -------- Employee Filter --------
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
//                }
//
//// -------- Customer Filter --------
//// Ensure $customer_type is set
//                $customer_type = $this->customer_type ?? 'customer_all';
//
//                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
//                    // Apply filter for a single value
//                    $sql .= ' AND C."CardCode" = \''.$customer_type.'\'' ;
//
//                }
//
//
//// -------- Vendor Filter (multi) --------
//
//                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
//                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
//                    $sql .= ' AND T2."CardCode" IN (' . implode(',', $escaped) . ') ';
//                }
//
//
//// -------- Speciality Filter (multi) --------
//                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
//                    $spConditions = [];
//                    if (in_array('0', $sp_type)) $spConditions[] = 'T2."QryGroup1" = \'Y\'';
//                    if (in_array('1', $sp_type)) $spConditions[] = 'T2."QryGroup2" = \'Y\'';
//                    if (in_array('2', $sp_type)) $spConditions[] = 'T2."QryGroup3" = \'Y\'';
//                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
//                }
//
//// -------- Marketing Type Filter (multi) --------
////                $marketing_type = (array) ($this->marketing_type ?? []);
//
//                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
//                    $mrktConditions = [];
//
//                    $map = [
//                        '30' => 'T2."QryGroup30"',
//                        '31' => 'T2."QryGroup31"',
//                        '32' => 'T2."QryGroup32"',
//                        '40' => 'T2."QryGroup40"',
//                        '41' => 'T2."QryGroup41"',
//                        '50' => 'T2."QryGroup50"',
//                        '51' => 'T2."QryGroup51"',
//                        '52' => 'T2."QryGroup52"',
//                        '53' => 'T2."QryGroup53"',
//                    ];
//
//                    foreach ($marketing_type as $val) {
//                        if (isset($map[$val])) {
//                            $mrktConditions[] = $map[$val] . " = 'Y'";
//                        }
//                    }
//
//                    if (!empty($mrktConditions)) {
//                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
//                    }
//                }
//// -------- Marketing Type Filter (multi) --------
//
//                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
//                    $sql.= ' AND T2."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
//                }
//
//
//                if ($this->vendor_type != 'vendor_all' && $this->vendor_type != null) {
//                    $sql .= ' AND (T2."CardCode" = \'' . $this->vendor_type[0] . '\')';
//                }
//                $sql .= ' AND T1."BPLId" IN ('. implode(', ', $sap_depts).')
//
//        GROUP BY
//            T0."ItemCode",
//            T2."ItemName",
//            T2."UgpEntry",
//            V."CardName",
//            C."CardName",
//            S."SlpName",
//            B."BPLName",
//            B."BPLId",
//            T2."QryGroup1",
//            T2."QryGroup2",
//            T2."QryGroup3",
//            T2."QryGroup30",
//            T2."QryGroup31",
//            T2."QryGroup32",
//            T2."QryGroup40",
//            T2."QryGroup41",
//            T2."QryGroup50",
//            T2."QryGroup51",
//            T2."QryGroup52",
//            T2."QryGroup53"
//
//        UNION ALL
//
//        /* =======================
//           مرتجعات المبيعات
//           بحركة مخزون فقط
//        ======================== */
//        SELECT
//            T0."ItemCode",
//            T2."UgpEntry",
//            T2."ItemName",
//            V."CardName"        AS "VendorName",
//            C."CardName"        AS "CardName",
//            S."SlpName"         AS "SlpName",
//            B."BPLName",
//            B."BPLId",
//
//            T2."QryGroup1",
//            T2."QryGroup2",
//            T2."QryGroup3",
//            T2."QryGroup30",
//            T2."QryGroup31",
//            T2."QryGroup32",
//            T2."QryGroup40",
//            T2."QryGroup41",
//            T2."QryGroup50",
//            T2."QryGroup51",
//            T2."QryGroup52",
//            T2."QryGroup53",
//
//            SUM(T0."Quantity") * -1 AS "Qty"
//
//        FROM AL_YASEEN_AGRI_PLIVE.RIN1 T0
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.ORIN T1 ON T0."DocEntry" = T1."DocEntry"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD C  ON T1."CardCode" = C."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD V  ON T2."CardCode" = V."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OSLP S  ON C."SlpCode"   = S."SlpCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL B  ON T1."BPLId"    = B."BPLId"
//
//        WHERE
//            T1."CANCELED" = \'N\'
//            AND T0."NoInvtryMv" = \'N\'
//            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
//
//
//                if($this->product_code){
//                    $sql .= ' AND T0."ItemCode" = \'' . $this->product_code . '\' ';
//
//                }
//// -------- Employee Filter --------
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
//                }
//
//// -------- Customer Filter --------
//// Ensure $customer_type is set
//                $customer_type = $this->customer_type ?? 'customer_all';
//
//                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
//                    // Apply filter for a single value
//                    $sql .= ' AND C."CardCode" = \''.$customer_type.'\'' ;
//
//                }
//
//
//// -------- Vendor Filter (multi) --------
//
//                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
//                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
//                    $sql .= ' AND T2."CardCode" IN (' . implode(',', $escaped) . ') ';
//                }
//
//
//// -------- Speciality Filter (multi) --------
//                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
//                    $spConditions = [];
//                    if (in_array('0', $sp_type)) $spConditions[] = 'T2."QryGroup1" = \'Y\'';
//                    if (in_array('1', $sp_type)) $spConditions[] = 'T2."QryGroup2" = \'Y\'';
//                    if (in_array('2', $sp_type)) $spConditions[] = 'T2."QryGroup3" = \'Y\'';
//                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
//                }
//
//// -------- Marketing Type Filter (multi) --------
////
//
//                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
//                    $mrktConditions = [];
//
//                    $map = [
//                        '30' => 'T2."QryGroup30"',
//                        '31' => 'T2."QryGroup31"',
//                        '32' => 'T2."QryGroup32"',
//                        '40' => 'T2."QryGroup40"',
//                        '41' => 'T2."QryGroup41"',
//                        '50' => 'T2."QryGroup50"',
//                        '51' => 'T2."QryGroup51"',
//                        '52' => 'T2."QryGroup52"',
//                        '53' => 'T2."QryGroup53"',
//                    ];
//
//                    foreach ($marketing_type as $val) {
//                        if (isset($map[$val])) {
//                            $mrktConditions[] = $map[$val] . " = 'Y'";
//                        }
//                    }
//
//                    if (!empty($mrktConditions)) {
//                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
//                    }
//                }
//// -------- Marketing Type Filter (multi) --------
//
//                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
//                    $sql.= ' AND T2."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
//                }
//
//
//                     $sql .= ' AND T1."BPLId" IN ('. implode(', ', $sap_depts).')
//
//
//
//        GROUP BY
//            T0."ItemCode",
//            T2."ItemName",
//            T2."UgpEntry",
//            V."CardName",                $customer_type = $this->customer_type ?? 'customer_all';
//
//
//
//                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
//
//                    // Apply filter for a single value
//
//                    $sql .= ' AND c."CardCode" = \''.$customer_type.'\'' ;
//            C."CardName",
//            S."SlpName",
//            B."BPLName",
//            B."BPLId",
//            T2."QryGroup1",
//            T2."QryGroup2",
//            T2."QryGroup3",
//            T2."QryGroup30",
//            T2."QryGroup31",
//            T2."QryGroup32",
//            T2."QryGroup40",
//            T2."QryGroup41",
//            T2."QryGroup50",
//            T2."QryGroup51",
//            T2."QryGroup52",
//            T2."QryGroup53"
//    ) Z
//    GROUP BY
//        Z."ItemCode",
//        Z."ItemName",
//        Z."UgpEntry",
//        Z."VendorName",
//        Z."CardName",
//        Z."SlpName",
//        Z."BPLName",
//        Z."BPLId",
//        Z."QryGroup1",
//        Z."QryGroup2",
//        Z."QryGroup3",
//        Z."QryGroup30",
//        Z."QryGroup31",
//        Z."QryGroup32",
//        Z."QryGroup40",
//        Z."QryGroup41",
//        Z."QryGroup50",
//        Z."QryGroup51",
//        Z."QryGroup52",
//        Z."QryGroup53"
//) X
//   LEFT JOIN "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery" V
//   ON X."ItemCode" = V."ItemCode"';
//
//                $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . ', "BranchRank" ,  "EmployeeRank" ASC, X."SlpName" ,X."ItemCode"';
//




                $sql='

WITH SalesAgg AS (
    SELECT
        Z."ItemCode",
        Z."ItemName",
        Z."UgpEntry",
        Z."VendorName",
        Z."CardName",
        Z."SlpName",
        Z."BPLName",
        Z."BPLId",

        Z."QryGroup1",
        Z."QryGroup2",
        Z."QryGroup3",
        Z."QryGroup30",
        Z."QryGroup31",
        Z."QryGroup32",
        Z."QryGroup40",
        Z."QryGroup41",
        Z."QryGroup50",
        Z."QryGroup51",
        Z."QryGroup52",
        Z."QryGroup53",

        SUM(Z."Qty") AS "BranchQty"
    FROM
    (
        /* =======================
           فواتير المبيعات
        ======================== */
        SELECT
            T0."ItemCode",
            T2."UgpEntry",
            T2."ItemName",
            V."CardName"        AS "VendorName",
            C."CardName"        AS "CardName",
            S."SlpName"         AS "SlpName",
            B."BPLName",
            B."BPLId",

            T2."QryGroup1",
            T2."QryGroup2",
            T2."QryGroup3",
            T2."QryGroup30",
            T2."QryGroup31",
            T2."QryGroup32",
            T2."QryGroup40",
            T2."QryGroup41",
            T2."QryGroup50",
            T2."QryGroup51",
            T2."QryGroup52",
            T2."QryGroup53",

            SUM(T0."Quantity")  AS "Qty"

        FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD C  ON T1."CardCode" = C."CardCode"
        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD V  ON T2."CardCode" = V."CardCode"
        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OSLP S  ON C."SlpCode"   = S."SlpCode"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL B  ON T1."BPLId"    = B."BPLId"

        WHERE
            T1."CANCELED" = \'N\'
            --AND T1."DocDate"  BETWEEN \'2026-01-01\' AND  \'2026-01-27\' AND T1."BPLId" IN (1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17)

  AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
//

                if($this->product_code){
                    $sql .= ' AND T0."ItemCode" IN ( \'' . implode($this->product_code) . '\') ';

                }
// -------- Employee Filter --------
                if ($emps_type != 'employees_all') {
                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
                }

// -------- Customer Filter --------
// Ensure $customer_type is set
                $customer_type = $this->customer_type ?? 'customer_all';

                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
                    // Apply filter for a single value
                    $sql .= ' AND c."CardCode" = \''.$customer_type.'\'' ;

                }


// -------- Vendor Filter (multi) --------

                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
                    $sql .= ' AND T2."CardCode" IN (' . implode(',', $escaped) . ') ';
                }


// -------- Speciality Filter (multi) --------
                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
                    $spConditions = [];
                    if (in_array('0', $sp_type)) $spConditions[] = 'T2."QryGroup1" = \'Y\'';
                    if (in_array('1', $sp_type)) $spConditions[] = 'T2."QryGroup2" = \'Y\'';
                    if (in_array('2', $sp_type)) $spConditions[] = 'T2."QryGroup3" = \'Y\'';
                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
                }

// -------- Marketing Type Filter (multi) --------
//

                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
                    $mrktConditions = [];

                    $map = [
                        '30' => 'T2."QryGroup30"',
                        '31' => 'T2."QryGroup31"',
                        '32' => 'T2."QryGroup32"',
                        '40' => 'T2."QryGroup40"',
                        '41' => 'T2."QryGroup41"',
                        '50' => 'T2."QryGroup50"',
                        '51' => 'T2."QryGroup51"',
                        '52' => 'T2."QryGroup52"',
                        '53' => 'T2."QryGroup53"',
                    ];

                    foreach ($marketing_type as $val) {
                        if (isset($map[$val])) {
                            $mrktConditions[] = $map[$val] . " = 'Y'";
                        }
                    }

                    if (!empty($mrktConditions)) {
                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
                    }
                }
// -------- Marketing Type Filter (multi) --------

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
                    $sql.= ' AND T2."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
                }


                $sql .= ' AND T1."BPLId" IN ('. implode(', ', $sap_depts).')


        GROUP BY
            T0."ItemCode",
            T2."ItemName",
            T2."UgpEntry",
            V."CardName",
            C."CardName",
            S."SlpName",
            B."BPLName",
            B."BPLId",
            T2."QryGroup1",
            T2."QryGroup2",
            T2."QryGroup3",
            T2."QryGroup30",
            T2."QryGroup31",
            T2."QryGroup32",
            T2."QryGroup40",
            T2."QryGroup41",
            T2."QryGroup50",
            T2."QryGroup51",
            T2."QryGroup52",
            T2."QryGroup53"

        UNION ALL

        /* =======================
           مرتجعات المبيعات
           بحركة مخزون فقط
        ======================== */
        SELECT
            T0."ItemCode",
            T2."UgpEntry",
            T2."ItemName",
            V."CardName"        AS "VendorName",
            C."CardName"        AS "CardName",
            S."SlpName"         AS "SlpName",
            B."BPLName",
            B."BPLId",

            T2."QryGroup1",
            T2."QryGroup2",
            T2."QryGroup3",
            T2."QryGroup30",
            T2."QryGroup31",
            T2."QryGroup32",
            T2."QryGroup40",
            T2."QryGroup41",
            T2."QryGroup50",
            T2."QryGroup51",
            T2."QryGroup52",
            T2."QryGroup53",

            SUM(T0."Quantity") * -1 AS "Qty"

        FROM AL_YASEEN_AGRI_PLIVE.RIN1 T0
        INNER JOIN AL_YASEEN_AGRI_PLIVE.ORIN T1 ON T0."DocEntry" = T1."DocEntry"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD C  ON T1."CardCode" = C."CardCode"
        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD V  ON T2."CardCode" = V."CardCode"
        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OSLP S  ON C."SlpCode"   = S."SlpCode"
        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL B  ON T1."BPLId"    = B."BPLId"

        WHERE
            T1."CANCELED" = \'N\'
            AND T0."NoInvtryMv" = \'N\'
          --  AND T1."DocDate" BETWEEN \'2026-01-01\' AND  \'2026-01-27\' AND T1."BPLId" IN (1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17)

  AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';


                if($this->product_code){
                    $sql .= ' AND T0."ItemCode" IN ( \'' . implode($this->product_code) . '\') ';

                }
// -------- Employee Filter --------
                if ($emps_type != 'employees_all') {
                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
                }

// -------- Customer Filter --------
// Ensure $customer_type is set
                $customer_type = $this->customer_type ?? 'customer_all';

                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
                    // Apply filter for a single value
                    $sql .= ' AND C."CardCode" = \''.$customer_type.'\'' ;

                }


// -------- Vendor Filter (multi) --------

                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
                    $sql .= ' AND T2."CardCode" IN (' . implode(',', $escaped) . ') ';
                }


// -------- Speciality Filter (multi) --------
                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
                    $spConditions = [];
                    if (in_array('0', $sp_type)) $spConditions[] = 'T2."QryGroup1" = \'Y\'';
                    if (in_array('1', $sp_type)) $spConditions[] = 'T2."QryGroup2" = \'Y\'';
                    if (in_array('2', $sp_type)) $spConditions[] = 'T2."QryGroup3" = \'Y\'';
                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
                }

// -------- Marketing Type Filter (multi) --------
//

                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
                    $mrktConditions = [];

                    $map = [
                        '30' => 'T2."QryGroup30"',
                        '31' => 'T2."QryGroup31"',
                        '32' => 'T2."QryGroup32"',
                        '40' => 'T2."QryGroup40"',
                        '41' => 'T2."QryGroup41"',
                        '50' => 'T2."QryGroup50"',
                        '51' => 'T2."QryGroup51"',
                        '52' => 'T2."QryGroup52"',
                        '53' => 'T2."QryGroup53"',
                    ];

                    foreach ($marketing_type as $val) {
                        if (isset($map[$val])) {
                            $mrktConditions[] = $map[$val] . " = 'Y'";
                        }
                    }

                    if (!empty($mrktConditions)) {
                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
                    }
                }
// -------- Marketing Type Filter (multi) --------

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
                    $sql.= ' AND T2."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
                }


                $sql .= ' AND T1."BPLId" IN ('. implode(', ', $sap_depts).')


        GROUP BY
            T0."ItemCode",
            T2."ItemName",
            T2."UgpEntry",
            V."CardName",
            C."CardName",
            S."SlpName",
            B."BPLName",
            B."BPLId",
            T2."QryGroup1",
            T2."QryGroup2",
            T2."QryGroup3",
            T2."QryGroup30",
            T2."QryGroup31",
            T2."QryGroup32",
            T2."QryGroup40",
            T2."QryGroup41",
            T2."QryGroup50",
            T2."QryGroup51",
            T2."QryGroup52",
            T2."QryGroup53"
    ) Z
    GROUP BY
        Z."ItemCode",
        Z."ItemName",
        Z."UgpEntry",
        Z."VendorName",
        Z."CardName",
        Z."SlpName",
        Z."BPLName",
        Z."BPLId",
        Z."QryGroup1",
        Z."QryGroup2",
        Z."QryGroup3",
        Z."QryGroup30",
        Z."QryGroup31",
        Z."QryGroup32",
        Z."QryGroup40",
        Z."QryGroup41",
        Z."QryGroup50",
        Z."QryGroup51",
        Z."QryGroup52",
        Z."QryGroup53"

)
SELECT
    I."ItemCode",
    I."ItemName",
    I."UgpEntry",
        --I."VendorName",
    COALESCE(S."VendorName", \'\') AS "VendorName",
    COALESCE(S."CardName", \'\')   AS "CardName",
    COALESCE(S."SlpName", \'\')    AS "SlpName",

    G."ItmsGrpNam" AS "ItemGroup",
    UG."UgpName"   AS "Unit",

    --V."ItemGroup",
    --V."UoMGroup" AS "Unit",

    -- speciality & market type (unchanged)
    CASE
        WHEN I."QryGroup1" = \'Y\' THEN \'0\'
        WHEN I."QryGroup2" = \'Y\' THEN \'1\'
        WHEN I."QryGroup3" = \'Y\' THEN \'2\'
        ELSE \'\'
    END AS "Speciality",

    CASE
        WHEN I."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
        WHEN I."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
        WHEN I."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
        WHEN I."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
        WHEN I."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
        WHEN I."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
        WHEN I."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
        WHEN I."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
        WHEN I."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
        ELSE \'general\'
    END AS "mrkt_type",

    B."BPLId"   AS "BranchId",
    B."BPLName" AS "BranchName",

    COALESCE(S."BranchQty", 0) AS "TotalQuantitySaleByBranch",

    SUM(COALESCE(S."BranchQty", 0))
        OVER (PARTITION BY I."ItemCode") AS "TotalQuantitySale",

    ROUND(
        COALESCE(S."BranchQty", 0) * 100.0 /
        NULLIF(
            SUM(COALESCE(S."BranchQty", 0))
                OVER (PARTITION BY I."ItemCode"),
        0),
    2) AS "TotalSalesPer",

    ROW_NUMBER() OVER (
        PARTITION BY I."ItemCode"
        ORDER BY COALESCE(S."BranchQty", 0) DESC
    ) AS "BranchRank",

    CASE
        WHEN ROW_NUMBER() OVER (
            PARTITION BY I."ItemCode"
            ORDER BY COALESCE(S."BranchQty", 0) DESC
        ) = 1 THEN \'Y\'
        ELSE \'N\'
    END AS "IsBestBranch"
--FROM (
--SELECT DISTINCT "ItemCode", "ItemName", "VendorName", "CardName", "SlpName", "QryGroup1","QryGroup2","QryGroup3", "QryGroup30","QryGroup31","QryGroup32", "QryGroup40","QryGroup41", "QryGroup50","QryGroup51","QryGroup52","QryGroup53"
--FROM SalesAgg ) I
FROM AL_YASEEN_AGRI_PLIVE.OITM I
JOIN AL_YASEEN_AGRI_PLIVE.OITB G  ON I."ItmsGrpCod" = G."ItmsGrpCod"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OUGP UG ON I."UgpEntry" = UG."UgpEntry"
CROSS JOIN AL_YASEEN_AGRI_PLIVE.OBPL B
LEFT JOIN SalesAgg S
    ON S."ItemCode" = I."ItemCode"
   AND S."BPLId"   = B."BPLId"

--LEFT JOIN "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery" V
  --  ON I."ItemCode" = V."ItemCode"

--WHERE B."BPLId" IN (1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17)
 WHERE I."ItemType" = \'I\'
 AND I."validFor" = \'Y\'';

                if(!empty($this->product_code)){
                    $codes = array_map(fn($c) => "'" . str_replace("'", "''", $c) . "'", $this->product_code);
                    $sql .= ' AND T0."ItemCode" = \'IN (' . implode($codes ). '\') ';

                }
// -------- Employee Filter --------
                if ($emps_type != 'employees_all') {
                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
                }

// -------- Customer Filter --------
// Ensure $customer_type is set
                $customer_type = $this->customer_type ?? null;

                if (!empty($customer_type) && $customer_type !== 'customer_all') {
                    // Apply filter for a single value
                    $sql .= ' AND C."CardCode" = \'' . str_replace("'", "''", $customer_type) . '\' ';
                }


// -------- Vendor Filter (multi) --------

                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
                    $sql .= ' AND T2."CardCode" IN (' . implode(',', $escaped) . ') ';
                }


// -------- Speciality Filter (multi) --------
                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
                    $spConditions = [];
                    if (in_array('0', $sp_type)) $spConditions[] = 'I."QryGroup1" = \'Y\'';
                    if (in_array('1', $sp_type)) $spConditions[] = 'I."QryGroup2" = \'Y\'';
                    if (in_array('2', $sp_type)) $spConditions[] = 'I."QryGroup3" = \'Y\'';
                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
                }

// -------- Marketing Type Filter (multi) --------
//

                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
                    $mrktConditions = [];

                    $map = [
                        '30' => 'I."QryGroup30"',
                        '31' => 'I."QryGroup31"',
                        '32' => 'I."QryGroup32"',
                        '40' => 'I."QryGroup40"',
                        '41' => 'I."QryGroup41"',
                        '50' => 'I."QryGroup50"',
                        '51' => 'I."QryGroup51"',
                        '52' => 'I."QryGroup52"',
                        '53' => 'I."QryGroup53"',
                    ];

                    foreach ($marketing_type as $val) {
                        if (isset($map[$val])) {
                            $mrktConditions[] = $map[$val] . " = 'Y'";
                        }
                    }

                    if (!empty($mrktConditions)) {
                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
                    }
                }
// -------- Marketing Type Filter (multi) --------

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
                    $sql.= ' AND I."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
                }


                $sql .= ' AND B."BPLId" IN ('. implode(', ', $sap_depts).')
ORDER BY I."ItemCode", "BranchRank"


                ';


//dd($sql);

                $customerCondition = '';

                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
                    $customerCondition = ' AND T1."CardCode" = \''.$customer_type.'\' ';
                }

//
                $sql = 'WITH SalesAgg AS (
    SELECT
        X."ItemCode",
        X."BPLId",
        X."SlpCode",
        SUM(X."Qty") AS "EmpQty",
        SUM(X."TransCount") AS "EmpTransCount"
    FROM (
        -- Invoices
        SELECT
            T0."ItemCode",
            T1."BPLId",
            T1."SlpCode",
            SUM(T0."Quantity") AS "Qty",
            COUNT(DISTINCT T1."DocEntry") AS "TransCount"
        FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
        JOIN AL_YASEEN_AGRI_PLIVE.OINV T1
            ON T0."DocEntry" = T1."DocEntry"
        WHERE
            T1."CANCELED" = \'N\'
            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
                    $sql.= ' AND T1."CardCode" = \''.$customer_type.'\' ';
                }

//                -- AND T1."BPLId" IN (1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17)
//           -- AND T1."BPLId" IN ('. implode(', ', $sap_depts).')
                $sql.= 'GROUP BY
            T0."ItemCode",
            T1."BPLId",
            T1."SlpCode"

        UNION ALL

        -- Returns
        SELECT
            T0."ItemCode",
            T1."BPLId",
            T1."SlpCode",
            SUM(T0."Quantity") * -1 AS "Qty",
            COUNT(DISTINCT T1."DocEntry") * -1 AS "TransCount"
        FROM AL_YASEEN_AGRI_PLIVE.RIN1 T0
        JOIN AL_YASEEN_AGRI_PLIVE.ORIN T1
            ON T0."DocEntry" = T1."DocEntry"
        WHERE
            T1."CANCELED" = \'N\'
            AND T0."NoInvtryMv" = \'N\'
            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
                $customer_type = $this->customer_type ?? null;

                if (!empty($customer_type) && $customer_type !== 'customer_all') {
                    // Apply filter for a single value
                    $sql .= ' AND T1."CardCode" = \'' . str_replace("'", "''", $customer_type) . '\' ';
                }
//                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
//                    $sql.= ' AND T1."CardCode" = \''.$customer_type.'\'';
//                }
                $sql.=' -- AND T1."BPLId" IN (1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17)
          -- AND T1."BPLId" IN ('. implode(', ', $sap_depts).')
        GROUP BY
            T0."ItemCode",
            T1."BPLId",
            T1."SlpCode"
    ) X
    GROUP BY
        X."ItemCode",
        X."BPLId",
        X."SlpCode"
        ),

   BranchAgg AS (
    -- 2) تجميع الفرع (كل الموظفين)
    SELECT
        "ItemCode",
        "BPLId",
        SUM("EmpQty") AS "BranchQty",
        SUM("EmpTransCount") AS "BranchTransCount"
    FROM SalesAgg
    GROUP BY
        "ItemCode",
        "BPLId"
),


BranchRanked AS (
    -- 3) ترتيب الفروع
    SELECT
        *,
        ROW_NUMBER() OVER (
            PARTITION BY "ItemCode"
            ORDER BY "BranchQty" DESC
        ) AS "BranchRank"
    FROM BranchAgg
),

TotalPerItem AS (
    SELECT
        "ItemCode",
        SUM("EmpQty") AS "TotalQuantitySale",
        SUM("EmpTransCount") AS "TotalTransCount"
    FROM SalesAgg
    GROUP BY "ItemCode"
),
 ItemList AS (
    SELECT DISTINCT "ItemCode"
    FROM SalesAgg
),
ItemBranches AS (
    SELECT
        I."ItemCode",
        B."BPLId",
        B."BPLName"
    FROM ItemList I
    CROSS JOIN AL_YASEEN_AGRI_PLIVE.OBPL B
    WHERE B."BPLId" IN (3,4,5,6,7,8,9,10,11,12,13,14)
)
--ItemPaged AS (
 --   SELECT "ItemCode"
  --  FROM (
   --     SELECT
     --       I."ItemCode",
     --       ROW_NUMBER() OVER (ORDER BY I."ItemCode") AS rn
     --   FROM AL_YASEEN_AGRI_PLIVE.OITM I
    --    WHERE
   -- I."ItemType" = \'I\'
  --  AND I."validFor" = \'Y\'
  --  )
    --WHERE rn BETWEEN 10 AND 20
--)
--TotalPerItem AS (
--    SELECT
 --       "ItemCode",
 --       SUM("BranchQty") AS "TotalQuantitySale"
 --   FROM SalesAgg
  --  GROUP BY "ItemCode"
--)
SELECT
    I."ItemCode",
    I."ItemName",
    I."UgpEntry",
    V."CardName" AS "VendorName",
    G."ItmsGrpNam" AS "ItemGroup",
    UG."UgpName"   AS "Unit",

    B."BPLId",
    B."BPLName",
    T."TotalQuantitySale",
    T."TotalTransCount",
    E."SlpName",
    E."SlpCode",
    COALESCE(S."EmpQty", 0) AS "EmployeeTotalQty",
    COALESCE(S."EmpTransCount", 0) AS "EmployeeTransCount",
    ROUND(
    COALESCE(S."EmpQty", 0) * 100.0 /
    NULLIF(BR."BranchQty", 0),
2) AS "EmployeePerBranch",
   -- speciality & market type (unchanged)
    CASE
        WHEN I."QryGroup1" = \'Y\' THEN \'0\'
        WHEN I."QryGroup2" = \'Y\' THEN \'1\'
        WHEN I."QryGroup3" = \'Y\' THEN \'2\'
        ELSE \'\'
    END AS "Speciality",

    CASE
        WHEN I."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
        WHEN I."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
        WHEN I."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
        WHEN I."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
        WHEN I."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
        WHEN I."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
        WHEN I."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
        WHEN I."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
        WHEN I."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
        ELSE \'general\'
    END AS "mrkt_type",
    --COALESCE(S."BranchQty", 0) AS "TotalQuantitySaleByBranch",
    COALESCE(BR."BranchQty", 0) AS "TotalQuantitySaleByBranch",
    COALESCE(BR."BranchTransCount", 0) AS "TransCount",

ROUND(
    BR."BranchQty" * 100.0 /
    NULLIF(T."TotalQuantitySale", 0),
2) AS "TotalSalesPer",

CASE
    WHEN BR."BranchRank" = 1 THEN \'Y\'
   ELSE \'N\'
END AS "IsBestBranch",

BR."BranchRank"

    --SUM(COALESCE(S."BranchQty", 0))
     --   OVER (PARTITION BY I."ItemCode") AS "TotalQuantitySale",

  --  ROUND(
   --     COALESCE(S."BranchQty", 0) * 100.0 /
   --     NULLIF(
   --         SUM(COALESCE(S."BranchQty", 0))
    --            OVER (PARTITION BY I."ItemCode"),
    --    0),
   -- 2) AS "TotalSalesPer",

   -- ROW_NUMBER() OVER (
   --     PARTITION BY I."ItemCode"
    --    ORDER BY COALESCE(S."BranchQty", 0) DESC
    --) AS "BranchRank",

   -- CASE
   --     WHEN ROW_NUMBER() OVER (
    --        PARTITION BY I."ItemCode"
     --       ORDER BY COALESCE(S."BranchQty", 0) DESC
      --  ) = 1 THEN \'Y\'
     --   ELSE \'N\'
   -- END AS "IsBestBranch"

--FROM SalesAgg S
--JOIN AL_YASEEN_AGRI_PLIVE.OITM I
--FROM AL_YASEEN_AGRI_PLIVE.OITM I
--LEFT JOIN SalesAgg S
--    ON I."ItemCode" = S."ItemCode"
-- JOIN AL_YASEEN_AGRI_PLIVE.OBPL B
 --   ON B."BPLId" = S."BPLId"
--LEFT JOIN TotalPerItem T
 --   ON T."ItemCode" = I."ItemCode"

--JOIN AL_YASEEN_AGRI_PLIVE.OITB G
 --   ON I."ItmsGrpCod" = G."ItmsGrpCod"
--LEFT JOIN AL_YASEEN_AGRI_PLIVE.OUGP UG
 --   ON I."UgpEntry" = UG."UgpEntry"

--LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD V
--    ON I."CardCode" = V."CardCode"
--LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP E
--    ON E."SlpCode" = S."SlpCode"

--FROM  BranchRanked BR

--JOIN AL_YASEEN_AGRI_PLIVE.OITM I
--   ON I."ItemCode" = BR."ItemCode"

--FROM ItemPaged IP

--JOIN AL_YASEEN_AGRI_PLIVE.OITM I
--   ON I."ItemCode" = IP."ItemCode"

--JOIN BranchRanked BR
 --  ON BR."ItemCode" = I."ItemCode"

 --JOIN AL_YASEEN_AGRI_PLIVE.OBPL B
   -- ON B."BPLId" = BR."BPLId"
   -- LEFT JOIN SalesAgg S
  -- ON S."ItemCode" = BR."ItemCode"
 --  AND S."BPLId"   = BR."BPLId"
--LEFT JOIN TotalPerItem T
 --   ON T."ItemCode" = I."ItemCode"

FROM ItemBranches B
JOIN AL_YASEEN_AGRI_PLIVE.OITM I
    ON I."ItemCode" = B."ItemCode"

LEFT JOIN SalesAgg S
    ON S."ItemCode" = B."ItemCode"
   AND S."BPLId"   = B."BPLId"

LEFT JOIN BranchRanked BR
    ON BR."ItemCode" = B."ItemCode"
   AND BR."BPLId"    = B."BPLId"

LEFT JOIN TotalPerItem T
    ON T."ItemCode" = B."ItemCode"


JOIN AL_YASEEN_AGRI_PLIVE.OITB G
    ON I."ItmsGrpCod" = G."ItmsGrpCod"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OUGP UG
    ON I."UgpEntry" = UG."UgpEntry"

LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD V
    ON I."CardCode" = V."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP E
    ON E."SlpCode" = S."SlpCode"

WHERE
    I."ItemType" = \'I\'
    AND I."validFor" = \'Y\'';
                if(!empty($this->product_code)){
                    $codes = array_map(fn($c) => "'" . str_replace("'", "''", $c) . "'", $this->product_code);
                    $sql .= ' AND I."ItemCode" IN (' . implode(',',$codes ). ') ';

                }
// -------- Employee Filter --------
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
//                }

// -------- Customer Filter --------
// Ensure $customer_type is set
//                $customer_type = $this->customer_type ?? 'customer_all';
//
//                if ($customer_type !== 'customer_all' && !empty($customer_type)) {
//                    // Apply filter for a single value
//                    $sql .= ' AND T1."CardCode" = \''.$customer_type.'\'' ;
//
//                }


// -------- Vendor Filter (multi) --------

                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
                    $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $vendor_type);
                    $sql .= ' AND I."CardCode" IN (' . implode(',', $escaped) . ') ';
                }


// -------- Speciality Filter (multi) --------
                if (!empty($sp_type) && !in_array('sp_all', $sp_type)) {
                    $spConditions = [];
                    if (in_array('0', $sp_type)) $spConditions[] = 'I."QryGroup1" = \'Y\'';
                    if (in_array('1', $sp_type)) $spConditions[] = 'I."QryGroup2" = \'Y\'';
                    if (in_array('2', $sp_type)) $spConditions[] = 'I."QryGroup3" = \'Y\'';
                    if ($spConditions) $sql .= ' AND (' . implode(' OR ', $spConditions) . ') ';
                }

// -------- Marketing Type Filter (multi) --------
//

                if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
                    $mrktConditions = [];

                    $map = [
                        '30' => 'I."QryGroup30"',
                        '31' => 'I."QryGroup31"',
                        '32' => 'I."QryGroup32"',
                        '40' => 'I."QryGroup40"',
                        '41' => 'I."QryGroup41"',
                        '50' => 'I."QryGroup50"',
                        '51' => 'I."QryGroup51"',
                        '52' => 'I."QryGroup52"',
                        '53' => 'I."QryGroup53"',
                    ];

                    foreach ($marketing_type as $val) {
                        if (isset($map[$val])) {
                            $mrktConditions[] = $map[$val] . " = 'Y'";
                        }
                    }

                    if (!empty($mrktConditions)) {
                        $sql .= ' AND (' . implode(' OR ', $mrktConditions) . ') ';
                    }
                }
// -------- Marketing Type Filter (multi) --------

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {
                    $sql.= ' AND I."ItmsGrpCod" IN ('.implode(", ", $cat_type).')';
                }

                if (!empty($sap_depts)) {
                    $sql .= ' AND B."BPLId" IN (' . implode(', ', $sap_depts) . ')';
                }
                $sql .= '

--ORDER BY I."ItemCode",  BR."BranchRank",COALESCE(S."EmpQty", 0) DESC
    --S."EmpQty" DESC
    --LIMIT 25 OFFSET 0

    ORDER BY
  --  IR."ItemRank",              -- best items first
    I."ItemCode",
    BR."BranchRank",            -- best branch first per item
    BR."BranchQty" DESC,        -- safety
    COALESCE(S."EmpQty", 0) DESC,
    E."SlpCode"

';
                // dd($sql);

                $result = odbc_exec($conn, $sql);
                if (!$result) {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                } else {
//                dd(odbc_fetch_array($result));

//                    dd($result);
                    while ($row = odbc_fetch_array($result)) {
                        array_push($this->sap_results, $row);
                    }
//                    dd($this->sap_results);

                }
                odbc_close($conn);
//                dd($this->sap_results);
            }
        }

    }


}
