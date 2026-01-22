<?php

namespace App\Http\Livewire\Items;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemsSalesByBranch extends Component
{
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
    public $counter = 0,

$item_code = "*",
$item_group_code = "*",
$item_group_itemCode_code = "*",
$speciality_code = "*",
$marketing_type_code = "*",
$vendor_code = "*";
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
        '3' => [
            ['0101', 'الاحساء'],
            ['0201', 'مزرعة الدالوة'],
            ['0202', 'مزرعة الفضول'],
            ['0203', 'مزرعة الدلم'],
            ['0001', 'المركز الرئيسي'],
        ],
    ];

    public $all_option;

//    public $itemGroup_item_subtotal = 0, $itemGroup_cost_subtotal = 0, $itemGroup_gross_subtotal = 0, $itemGroup_quantity_subtotal = 0, $itemGroup_trans_subtotal = 0;

    protected $rules = [
        'start_date' => 'required',
        'end_date' => 'required',
//        'dept_id' => 'required',
//        'group_type'=> 'required',
//        'marketing_type' =>'required',
//        'cat_type' =>'required',
//        'customer_type'=>'required',
//        'vendor_type'=>'required',
//        'sp_type'=>'required',
//        'emps_type'=>'required'
    ];

    protected $messages = [
        'start_date.required' => ' مطلوب',
        'end_date.required' => ' مطلوب',
//        'dept_id.required' => ' مطلوب',
//        'group_type.required' => ' مطلوب',
//        'marketing_type.required' => ' مطلوب',
//        'cat_type.required' => ' مطلوب',
//        'customer_type.required' => 'طلوب',
//        'vendor_type.required'=> 'مطلوب',
//        'sp_type.required'=> 'مطلوب',
//        'emps_type.required'=> 'مطلوب'
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
        $sql = '';        // 🔥 RESET
        $bindings = [];   // 🔥 RESET
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

    public function render()
    {
        return view('livewire.items.items-sales-by-branch')->layout('layouts.dashboard');
    }

//    public function create_report(){
    public function create_report($start_date, $end_date, $dept_id, $group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type, $customer_type, $emps_type) {

//        dd($this->vendor_type);
        $report_type ='byDepartment';

//        dd('here?');

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->validate();
        $this->show_msg = false;
    //    $this->report_type = $report_type;
      //  $this->scribes_results = [];
        $this->sap_results = [];
        $this->group_results = [];

//        $this->productCodes('commerce',['cat_all'], ['sp_all'], 'vendor_all', 'advanced_search', null, ['marketing_all']);
        $this->productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type);

        if (is_null($start_date) == false && is_null($end_date) == false) {


             $this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type);

//            dd($this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type));

        }

        else {
            dd('coco');
        }

//        dd($this->sap_results);
        $sap_collection = collect($this->sap_results);

//        dd( $sap_collection->lazy() );


//
//        $groups = $sap_collection->groupBy('OldCode');
//        $this->group_results = $groups->map(function ($row) {
//
//            return [
////                'OldCode' => $row->first()['OldCode'],
//                'OldCode' => $row->first()['OldCode'],
//                'ItemName' => $row->first()['ItemName'],
//                'SalUnitMsr' =>   $row->first()['SalUnitMsr'],
//                'Speciality' =>   $row->first()['Speciality'],
//                'VendorName' =>   $row->first()['VendorCode'],
//                'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
//                'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
//                'AverageUnitPrice' => $row->sum('TotalQuantitySold') == 0 ? 0 : $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
////                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
//                'Cost' => $row->sum('Cost'),
//                'GrossProfit' => $row->sum('GrossProfit'),
//                'GrossProfitPer' => $row->sum('Cost') == 0 ? 0 : ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
////                'GrossProfitPer' => $row->sum('GrossProfitPer'),
//            ];
//        });

//
//        $this->group_results = $sap_collection
//            ->groupBy(['ItemCode', 'BranchName'])
//            ->map(function ($branches, $itemCode) {
//                return [
//                    'ItemCode' => $itemCode,
//                    'ItemName' => $branches->first()->first()['ItemName'],
//                    'VendorName' =>   $branches->first()->first()['VendorName'],
//                    'branches' => $branches->map(function ($rows, $branchName) {
//                        return [
//                            'BranchName' => $branchName,
//                            'TotalQuantitySaleByBranch' => $rows->sum('TotalQuantitySaleByBranch'),
//                            'TotalSalesPer' => $rows->sum('TotalSalesPer'),
//
//                            'TotalQuantitySale' => $rows->first()['TotalQuantitySale'],
////                            'Speciality' =>   $rows->first()['Speciality'],
//                            'employees' => $rows->map(function ($row)
//                            {
//                                return [
//                                'EmployeeName' => $row['SlpName'],
//                                ];
//                            })
//                        ];
//
//                    })->values()
//                ];
//            })
//            ->values();

        $this->group_results = collect($this->sap_results)
            ->groupBy('ItemCode')
            ->map(function ($itemRows) {

                $itemMeta = $itemRows->first();

                return [
                    'ItemCode'   => $itemMeta['ItemCode'],
                    'ItemName'   => $itemMeta['ItemName'],
                    'VendorName' => $itemMeta['VendorName'],
                    'Unit' => $itemMeta['Unit'],
                    'mrkt_type' => $itemMeta['mrkt_type'],
                    'Speciality' => $itemMeta['Speciality'],
                    'ItemGroup' => $itemMeta['ItemGroup'],
                    'TotalQuantitySale' =>$itemMeta['TotalQuantitySale'],
                    'branches' => $itemRows
                        ->groupBy('BranchName')
                        ->map(function ($branchRows, $branchName) {

                            // Group employees inside this branch
                            $employees = $branchRows
                                ->groupBy('SlpName')  // group by employee
                                ->map(function ($empRows, $empName) {
                                    return [
                                        'EmployeeName' => $empName,
                                        'Quantity'     => $empRows->sum('TotalQuantitySaleByBranch'), // sum multiple rows
                                        'EmployeePer' =>$empRows->sum('TotalSalesPer'),
                                    ];
                                })
                                ->values();

                            return [
                                'BranchName' => $branchName,
                                'BranchTotal' => $employees->sum('Quantity'), // optional: total branch quantity
                                'employees' => $employees,
                                'TotalQuantitySaleByBranch' =>
                                    $branchRows->sum('TotalQuantitySaleByBranch'),

//                                'TotalQuantitySale' => $branchRows->first()['TotalQuantitySale'],
                                'TotalSalesPer' =>
                                    $branchRows->sum('TotalSalesPer'),
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();

//        $this->group_results = collect($sap_collection)
//            ->groupBy('ItemCode')
//            ->map(function ($itemRows) {
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
////                    'TotalQuantitySale' => $itemMeta['totalquantitysale'],
////                    'TotalQuantitySaleByBranch' => $itemMeta['totalquantitysalebybranch'],
////                    'TotalSalesPer' => $itemMeta['totalsalesper'],
//                    'EmployeeName'    =>  $itemMeta['SlpName'] ,
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




//        $groups = $sap_collection->groupBy('ItemCode');
//        $this->group_results = $groups->map(function ($row) {
//
//            return [
////                'OldCode' => $row->first()['OldCode'],
//                'ItemCode' => $row->first()['ItemCode'],
//                'ItemName' => $row->first()['ItemName'],
//                'BranchName' => $row->first()['BranchName'],
////                'SalUnitMsr' =>   $row->first()['SalUnitMsr'],
////                'Speciality' =>   $row->first()['Speciality'],
//                'VendorName' =>   $row->first()['VendorName'],
//                'TotalQuantitySale' => $row->sum('TotalQuantitySale'),
//                'TotalQuantitySaleByBranch' => $row->sum('TotalQuantitySaleByBranch'),
//                'TotalSalesPer' => $row->sum('TotalSalesPer'),
//
//            ];
//        });
////        dd(collect($this->group_results));
//
//        // Step 1: Flatten all sub-collections into a single collection
//        $this->group_results = collect($this->group_results);
//
//        $flattened = $this->group_results;
////        $flattened = collect($this->group_results)->flatMap->values();
////                ->sortBy(['OldCode', 'Department']);
//        $this->group_results = $flattened;
//
////        dd($this->group_results);
//        // Step 2: Group by OldCode
//        $groupedByItemName = $flattened->groupBy('ItemCode');
//        // Step 3: Calculate total sales amount for each group
//        $this->totalSalesByItem = $groupedByItemName->map(function ($group) {
//            return [$group->sum('TotalSalesAmount'), $group->sum('Cost'), $group->sum('GrossProfit'),
//                $group->sum('TotalQuantitySold'), $group->sum('TransCount') ];
//        })->toArray();

        $results = collect($sap_collection);

// Per item → per branch rows (keep as-is)
        $byItem = $results->groupBy('ItemCode');

// Example usage
//        foreach ($byItem as $itemCode => $rows) {
//            $totalQty = $rows->first()['TotalQuantitySale'];
//
//            foreach ($rows as $branchRow) {
//                $branch = $branchRow['BranchName'];
//                $branchQty = $branchRow['TotalQuantitySaleByBranch'];
//            }
//        }
//
//        $this->grouped = $results->groupBy('ItemCode')->map(function ($rows) {
//            return [
//                'ItemCode' => $rows->first()['ItemCode'],
//                'ItemName' => $rows->first()['ItemName'],
//                'VendorName' => $rows->first()['VendorName'],
//                'TotalQuantitySale' => $rows->first()['TotalQuantitySale'],
//                'Branches' => $rows->map(function ($r) {
//                    return [
//                        'BranchName' => $r['BranchName'],
//                        'Qty' => $r['TotalQuantitySaleByBranch'],
//                        'Percent' => $r['TotalSalesPer'],
//                    ];
//                })->values(),
//            ];
//        })->values();
//        $this->branchTotals = $sap_collection
//            ->groupBy('BranchCode')
//            ->map(fn ($rows) => $rows->sum('TotalQuantitySaleByBranch'));
//
//        $this->grouped= $results->groupBy('ItemCode')->map(function ($rows) {
//            return [
//                'ItemCode' => $rows->first()['ItemCode'],
//                'ItemName' => $rows->first()['ItemName'],
//                'VendorName' => $rows->first()['VendorName'],
//                'Branches' => $rows->map(function ($r) {
//                    return [
//                        'BranchName' => $r['BranchName'],
//                        'Qty' => $r['TotalQuantitySaleByBranch'],
//                    ];
//                })->values(),
//            ];
//        })->values();


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


    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $marketing_type)
    {
//        dd($cat_type);

        $this->scribes_codes = [];
        $this->sap_codes = [];

        if (!extension_loaded('odbc')) {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn) {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        } else {
            $categoryQuery = '';

            if ($search_type == "item_code_search") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" = \'' . $product_code . '\' OR T0."U_UDF1" = \'' . $product_code . '\'';
            } else if ($search_type == "advanced_search") {

                if ($group_type == "commerce") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
                } elseif ($group_type == "farms") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
                } elseif ($group_type == "sundries") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
                } elseif ($group_type == "groups_all") {
                    $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
                }

                if ($cat_type != null && in_array('cat_all', $cat_type) == false && count($cat_type) != 0) {

                    $categoryQuery .= ' AND T0."ItmsGrpCod" IN (' . implode(", ", $cat_type) . ')';
                }

                if ($sp_type != null && in_array('sp_all', $sp_type) == false && count($sp_type) != 0) {
//                $categoryQuery .= ' AND (T0."QryGroup1" = \''. (in_array('0', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup2" = \''. (in_array('1', $sp_type) ? 'Y': 'N') .'\' OR T0."QryGroup3" = \''. (in_array('2', $sp_type) ? 'Y': 'N') .'\')';
//                dd($sp_type);
                    if (count($sp_type) == 3) {
                        $categoryQuery .= ' AND (T0."QryGroup1" = \'' . (in_array('0', $sp_type) ? 'Y' : 'N') . '\' OR T0."QryGroup2" = \'' . (in_array('1', $sp_type) ? 'Y' : 'N') . '\' OR T0."QryGroup3" = \'' . (in_array('2', $sp_type) ? 'Y' : 'N') . '\')';
                    } else {
                        //dd( $categoryQuery);
                        $categoryQuery .= ' AND (';
                        foreach ($sp_type as $key => $s) {
                            if ($key === array_key_first($sp_type)) {
                                $categoryQuery .= 'T0."QryGroup' . intval($s) + 1 . '" = \'Y\'';
                            } else {
                                $categoryQuery .= ' OR T0."QryGroup' . intval($s) + 1 . '" = \'Y\'';
                            }
                        }
                        $categoryQuery .= ')';

                        $difference = array_diff(['0', '1', '2'], $sp_type);
//                    dd($difference);

                        if (count($difference) > 0) {

                            $categoryQuery .= ' AND (';
                            foreach ($difference as $key => $s) {
                                if ($key === array_key_first($difference)) {
                                    $categoryQuery .= 'T0."QryGroup' . intval($s) + 1 . '" = \'N\'';
                                } else {
                                    $categoryQuery .= ' OR T0."QryGroup' . intval($s) + 1 . '" = \'N\'';
                                }
                            }
                            $categoryQuery .= ')';

                        }

                    }
//                    dd($categoryQuery);
                }

//                dd($marketing_type);
                if ($marketing_type != null && in_array('marketing_all', $marketing_type) == false && count($marketing_type) != 0) {

                    if (count($marketing_type) == 9) {

                        $categoryQuery .= ' AND (T0."QryGroup30" = \'' . (in_array('30', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup31" = \'' . (in_array('31', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup32" = \'' . (in_array('32', $marketing_type) ? 'Y' : 'N') . '\'  OR T0."QryGroup40" = \'' . (in_array('40', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup41" = \'' . (in_array('41', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup50" = \'' . (in_array('50', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup51" = \'' . (in_array('51', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup52" = \'' . (in_array('52', $marketing_type) ? 'Y' : 'N') . '\' OR T0."QryGroup53" = \'' . (in_array('53', $marketing_type) ? 'Y' : 'N') . '\')';
                    } else {
                        $categoryQuery .= ' AND (';
                        foreach ($marketing_type as $key => $s) {
                            if ($key === array_key_first($marketing_type)) {
                                $categoryQuery .= 'T0."QryGroup' . intval($s) . '" = \'Y\'';
                            } else {
                                $categoryQuery .= ' OR T0."QryGroup' . intval($s) . '" = \'Y\'';
                            }
                        }
                        $categoryQuery .= ')';

                        $difference = array_diff(['30', '31', '32', '40', '41', '50', '51', '52', '53'], $marketing_type);
//                    dd($difference);

                        if (count($difference) > 0) {

                            $categoryQuery .= ' AND (';
                            foreach ($difference as $key => $s) {
                                if ($key === array_key_first($difference)) {
                                    $categoryQuery .= 'T0."QryGroup' . intval($s) . '" = \'N\'';
                                } else {
                                    $categoryQuery .= ' OR T0."QryGroup' . intval($s) . '" = \'N\'';
                                }
                            }
                            $categoryQuery .= ')';

                        }

                    }
                }
//dd($vendor_type[0]);

                if ($vendor_type != 'vendor_all' && $vendor_type != null) {
                    $categoryQuery .= ' AND (T0."CardCode" = \'' . $vendor_type[0] . '\')';
                }
            }


//            dd($categoryQuery);

            $result = odbc_exec($conn, $categoryQuery);
            if (!$result) {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            } else {
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_codes, "'" . $row['ItemCode'] . "'");
                    array_push($this->scribes_codes, "'" . $row['ScribeCode'] . "'");
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }
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
    public function sapQuery($start_date, $end_date, $departments, $customer_type, $emps_type)
    {


        $sortBy = $this->sortBy;

        $direction = $this->sortDir;

        if (count($this->sap_codes) > 0) {

            $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' => '5', '0104' => '6', '0105' => '7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' => '16', '0203' => '17'];
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
//                $sqlTest = ' AND "BranchCode" IN (\'' . implode("','", $sap_depts) . '\')';
//                dd(strlen($sqlTest));

////
//
//                $sql = 'SELECT
//	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
//	"ItemCode",
//    "ItemDescription" AS "ItemName",
//    "ItemGroup",
//    SUM("TransCount") AS "TransCount",
//    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
//    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
//    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
//    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
//    SUM("GrossProfitLC") as "GrossProfit",
//   SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
// (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
//
//     "Speciality",
//	"SalUnitMsr",
//"OldCode",
//"VendorCode",
//"VendorName",
//"mrkt_type",
//"IsInventoryItem",
//
//SUM(SUM("NetSalesAmountLC"))
//OVER (PARTITION BY "ItemCode") AS "GroupTotalSales",
//
//SUM(SUM("GrossProfitLC"))
//OVER (PARTITION BY "ItemCode") AS "GroupGrossProfit"
//
//       --  SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
//        --OVER (PARTITION BY "ItemCode") AS "GroupGrossProfitPer"
//
//
//FROM (
//
//SELECT *, CASE
//		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
//		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
//		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
//		ELSE \'\'
//	END AS "Speciality",
//	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
//	T2."CardCode" AS "VendorCode",
//"DefaultPreferredVendor" AS "VendorName",
//--
//CASE
//WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
//WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
//WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
//WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
//WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
//WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
//WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
//WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
//WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
//ELSE \'general\'
//END AS "mrkt_type",
//"InvntItem" AS "IsInventoryItem"
//--
//FROM (
//Select "BranchName", "BranchCode", "BranchRegistrationNumber",
//"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
//"CancellationStatus", "DocumentDate",
//"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
//"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
//"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
//CASE
//	WHEN "DocumentTypeCode" = 13 THEN 1
//	WHEN "DocumentTypeCode" = 14 THEN -1
//	ELSE 0
//END AS "TransCount",
//SUM("GrossProfitSC") AS "GrossProfitSC",
//SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
//SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
//SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
//SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"
//
//FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
//WHERE "DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\'
//AND "DocumentTypeCode" != \'17\'
//AND "DocumentTypeCode" != \'15\'';
//                if ($customer_type != 'customer_all') {
//                    $sql .= ' AND "BusinessPartnerCode" = \'' . $customer_type . '\'';
//                }
////                $sql .= ' AND "BranchCode" IN (\'' . implode("','", $sap_depts) . '\')
//
//               $sql .= ' AND "BranchCode" IN (' . implode(', ', $sap_depts) . ')
//
//AND "ItemCode" IN (' . implode(', ', $this->sap_codes) . ')
//
//GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
//"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
//"CancellationStatus", "DocumentDate",
//"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
//"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
//"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
//) T1
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
//RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
//ON T1."ItemCode2" = T2."ItemCode"
//WHERE T2."ItemCode" IN (' . implode(', ', $this->sap_codes) . ')';
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND TS."Memo" = \'' . $emps_type . '\'';
//                }
//                $sql .= ')
//WHERE "BranchName" IS NOT NULL
//
//GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
//    "ItemDescription",
//    "ItemGroup",
//    "Speciality",
//	"SalUnitMsr",
//"OldCode",
//"VendorCode",
//"VendorName",
//---
//"mrkt_type",
//"IsInventoryItem"';
//
////order By "TotalSalesAmount"';
//
//                if ($sortBy == 'code') {
//                    $sql .= 'ORDER BY "ItemCode" ' . $direction . '';
//                } else {
//
//                    $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
//                }
//                    dd($sql);
////
//                $sql = 'SELECT
//	"BranchName" AS "Branch", "BranchCode","BranchRegistrationNumber" AS "Department",
//	"ItemCode",
//    "ItemDescription" AS "ItemName",
//    "ItemGroup",
//    SUM("TransCount") AS "TransCount",
//    SUM("QuantityInInventoryUoM") AS "TotalQuantitySold",
//    SUM("NetSalesAmountLC") AS "TotalSalesAmount",
//    AVG("NetSalesAmountLC"/"QuantityInInventoryUoM") AS "AverageUnitPrice",
//    COUNT(DISTINCT "DocumentNumber") AS "NumberOfInvoices",
//    SUM("GrossProfitLC") as "GrossProfit",
//   SUM("NetSalesAmountLC")-SUM("GrossProfitLC") as "Cost",
// (SUM("GrossProfitLC")/ NULLIF(SUM("NetSalesAmountLC"), 0))*100 as "GrossProfitPer",
//
//     "Speciality",
//	"SalUnitMsr",
//"OldCode",
//"VendorCode",
//"VendorName",
//"mrkt_type",
//"IsInventoryItem",
//
//SUM(SUM("NetSalesAmountLC"))
//OVER (PARTITION BY "ItemCode") AS "GroupTotalSales",
//
//SUM(SUM("GrossProfitLC"))
//OVER (PARTITION BY "ItemCode") AS "GroupGrossProfit"
//
//       --  SUM((SUM("GrossProfitLC") / NULLIF(SUM("NetSalesAmountLC"), 0))*100)
//        --OVER (PARTITION BY "ItemCode") AS "GroupGrossProfitPer"
//
//
//FROM (
//
//SELECT *, CASE
//		WHEN T2."QryGroup1" = \'Y\' THEN \'0\'
//		WHEN T2."QryGroup2" = \'Y\' THEN \'1\'
//		WHEN T2."QryGroup3" = \'Y\' THEN \'2\'
//		ELSE \'\'
//	END AS "Speciality",
//	CASE WHEN T2."U_UDF1" IS NULL THEN "ItemCode" ELSE T2."U_UDF1" END AS "OldCode",
//	T2."CardCode" AS "VendorCode",
//"DefaultPreferredVendor" AS "VendorName",
//--
//CASE
//WHEN T2."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
//WHEN T2."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
//WHEN T2."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
//WHEN T2."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
//WHEN T2."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
//WHEN T2."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
//WHEN T2."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
//WHEN T2."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
//WHEN T2."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
//ELSE \'general\'
//END AS "mrkt_type",
//"InvntItem" AS "IsInventoryItem"
//--
//FROM (
//Select "BranchName", "BranchCode", "BranchRegistrationNumber",
//"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
//"CancellationStatus", "DocumentDate",
//"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
//"ItemGroup", "DefaultPreferredVendor", "ItemCode" as "ItemCode2", "ItemDescription",
//"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
//CASE
//	WHEN "DocumentTypeCode" = 13 THEN 1
//	WHEN "DocumentTypeCode" = 14 THEN -1
//	ELSE 0
//END AS "TransCount",
//SUM("GrossProfitSC") AS "GrossProfitSC",
//SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
//SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
//SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
//SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"
//
//FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
//WHERE "DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\'
//AND "DocumentTypeCode" != \'17\'
//AND "DocumentTypeCode" != \'15\'';
//                if ($customer_type != 'customer_all') {
//                    $sql .= ' AND "BusinessPartnerCode" = \'' . $customer_type . '\'';
//                }
////                $sql .= ' AND "BranchCode" IN (\'' . implode("','", $sap_depts) . '\')
//
//               $sql .= ' AND "BranchCode" IN (' . implode(', ', $sap_depts) . ')
//
//AND "ItemCode" IN (' . implode(', ', $this->sap_codes) . ')
//
//GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
//"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
//"CancellationStatus", "DocumentDate",
//"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
//"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
//"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
//) T1
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TX ON T1."BusinessPartnerCode" = TX."CardCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TX."SlpCode" = TS."SlpCode"
//RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
//ON T1."ItemCode2" = T2."ItemCode"
//WHERE T2."ItemCode" IN (' . implode(', ', $this->sap_codes) . ')';
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND TS."Memo" = \'' . $emps_type . '\'';
//                }
//                $sql .= ')
//WHERE "BranchName" IS NOT NULL
//
//GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber", "ItemCode",
//    "ItemDescription",
//    "ItemGroup",
//    "Speciality",
//	"SalUnitMsr",
//"OldCode",
//"VendorCode",
//"VendorName",
//---
//"mrkt_type",
//"IsInventoryItem"';
//
////order By "TotalSalesAmount"';
//
//                if ($sortBy == 'code') {
//                    $sql .= 'ORDER BY "ItemCode" ' . $direction . '';
//                } else {
//
//                    $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . '';
//                }
////                    dd($sql);
////

//                dd($sql);


//
//                $sql = 'SELECT
//    X."ItemCode"                              AS "ItemCode",
//    X."ItemName"                              AS "ItemName",
//    X."VendorName"                            AS "VendorName",
//    SUM(X."BranchQty")
//        OVER (PARTITION BY X."ItemCode")      AS "TotalQuantitySale",
//    X."BPLName"                               AS "BranchName",
//    X."BranchQty"                             AS "TotalQuantitySaleByBranch",
//    ROUND(
//        (X."BranchQty" * 100.0) /
//        NULLIF(
//            SUM(X."BranchQty")
//                OVER (PARTITION BY X."ItemCode"),
//        0),
//    2)                                        AS "TotalSalesPer"
//FROM
//(
//    SELECT
//        Z."ItemCode",
//        Z."ItemName",
//        Z."VendorName",
//        Z."BPLName",
//        SUM(Z."Qty") AS "BranchQty"
//    FROM
//    (
//        /* =======================
//           فواتير المبيعات
//        ======================== */
//        SELECT
//            T0."ItemCode",
//            T2."ItemName",
//            T4."CardName"          AS "VendorName",
//            T3."BPLName",
//            SUM(T0."Quantity")     AS "Qty"
//        FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T1."BPLId" = T3."BPLId"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD T4 ON T2."CardCode" = T4."CardCode"
//        WHERE
//            T1."CANCELED" = \'N\'
//            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'
//             GROUP BY
//            T0."ItemCode",
//            T2."ItemName",
//            T4."CardName",
//            T3."BPLName"
//
//        UNION ALL
//
//        /* =======================
//           مرتجعات المبيعات
//           فقط التي لها حركة مخزون
//        ======================== */
//        SELECT
//            T0."ItemCode",
//            T2."ItemName",
//            T4."CardName"          AS "VendorName",
//            T3."BPLName",
//            SUM(T0."Quantity") * -1 AS "Qty"
//        FROM AL_YASEEN_AGRI_PLIVE.RIN1 T0
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.ORIN T1 ON T0."DocEntry" = T1."DocEntry"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T1."BPLId" = T3."BPLId"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD T4 ON T2."CardCode" = T4."CardCode"
//        WHERE
//            T1."CANCELED" = \'N\'
//            AND T0."NoInvtryMv" = \'N\'
//            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'
//                    GROUP BY
//            T0."ItemCode",
//            T2."ItemName",
//            T4."CardName",
//            T3."BPLName"
//    ) Z
//    GROUP BY
//        Z."ItemCode",
//        Z."ItemName",
//        Z."VendorName",
//        Z."BPLName"
//) X
//ORDER BY
//    X."ItemCode",
//    X."BPLName"';

//dd($sortBy , $direction);
//dd($emps_type);
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
                $sql = 'SELECT
    X."ItemCode"                              AS "ItemCode",
    X."ItemName"                              AS "ItemName",
    X."UgpEntry"                             AS "Unit",
    X."VendorName"                            AS "VendorName",
    X."CardName"                              AS "CardName" ,
    X."SlpName"                               AS "SlpName" ,
    V."ItemGroup",
  --  X."QryGroup1"                             AS "مميز0 ",
  --  X."QryGroup2"                             AS "مميز 1",
   -- X."QryGroup3"                             AS "مميز 2",

  CASE
WHEN X."QryGroup1" = \'Y\' THEN \'0\'
WHEN X."QryGroup2" = \'Y\' THEN \'1\'
WHEN X."QryGroup3" = \'Y\' THEN \'2\'
ELSE \'\'
END AS "Speciality",

    CASE
WHEN X."QryGroup30" = \'Y\' THEN \'fan - asmedah 1\'
WHEN X."QryGroup31" = \'Y\' THEN \'fan - mobedat 1\'
WHEN X."QryGroup32" = \'Y\' THEN \'fan - bathoor 1\'
WHEN X."QryGroup40" = \'Y\' THEN \'tasweeg - sehah\'
WHEN X."QryGroup41" = \'Y\' THEN \'tasweeg - mokafahh\'
WHEN X."QryGroup50" = \'Y\' THEN \'aleyat - aleyat\'
WHEN X."QryGroup51" = \'Y\' THEN \'aleyat - ray\'
WHEN X."QryGroup52" = \'Y\' THEN \'aleyat - ray matary\'
WHEN X."QryGroup53" = \'Y\' THEN \'aleyat - khadamat\'
ELSE \'general\'
END AS "mrkt_type",
  --  X."QryGroup30"                            AS "ادارة فنية - الاسمدة م1",
  --  X."QryGroup31"                            AS "ادارة فنية - المبيدات م1",
  --  X."QryGroup32"                            AS "ادارة فنية - البذور م1",
  --  X."QryGroup40"                            AS "اقسام تسويقية - الحدائق و الصحة العامة",
  --  X."QryGroup41"                            AS "اقسام تسويقية - المكافحة المتكاملة",
  --  X."QryGroup50"                            AS "الاليات و الري - الاليات",
  --  X."QryGroup51"                            AS "الاليات و الري - الري",
  --  X."QryGroup52"                            AS "الاليات و الري - نترا",
  --  X."QryGroup53"                            AS "الاليات و الري - الخدمات",

    SUM(X."BranchQty")
        OVER (PARTITION BY X."ItemCode")      AS "TotalQuantitySale",


    X."BPLName"                               AS "BranchName",
    X."BranchQty"                             AS "TotalQuantitySaleByBranch",

    ROUND(
        (X."BranchQty" * 100.0) /
        NULLIF(
            SUM(X."BranchQty")
                OVER (PARTITION BY X."ItemCode"),
        0),
    2)                                        AS "TotalSalesPer"

FROM
(
    SELECT
        Z."ItemCode",
        Z."ItemName",
        Z."UgpEntry",
        Z."VendorName",
        Z."CardName",
        Z."SlpName",
        Z."BPLName",

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
            AND T1."DocDate"  BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';



                if($this->product_code){
                    $sql .= ' AND T0."ItemCode" = \'' . $this->product_code . '\' ';

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
//                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type)) {
//                    $placeholders = implode(',', array_fill(0, count($vendor_type), '?'));
//                    $sql .= ' AND T2."CardCode" IN (' . $placeholders . ') ';
//                    $bindings = array_merge($bindings, $vendor_type);
//                }
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
//                $marketing_type = (array) ($this->marketing_type ?? []);

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


//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND S."Memo" = \''.$emps_type[0].'\'';
//                }
//
//
//
//                if ($this->sp_type != 'sp_all') {
//                    foreach ($this->sp_type as $speciality){
//
//                        $sql .= ' AND (
//    (\''.$speciality.'\' = 0 AND T2."QryGroup1" = \'Y\')
// OR (\''.$speciality.'\' = 1 AND T2."QryGroup2" = \'Y\')
// OR (\''.$speciality.'\' = 2 AND T2."QryGroup3" = \'Y\')
//)
//';
//                    }
//
//                }

                //// -------- Marketing Type Filter (multi) --------
//                $marketing_type = (array) ($this->marketing_type ?? []);

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

//
//
//                if ($this->sp_type !== 'sp_all' && !empty($this->sp_type)) {
//
//                    $conditions = [];
//
//                    if (in_array('0', $this->sp_type, true)) {
//                        $conditions[] = 'T2."QryGroup1" = \'Y\'';
//                    }
//
//                    if (in_array('1', $this->sp_type, true)) {
//                        $conditions[] = 'T2."QryGroup2" = \'Y\'';
//                    }
//
//                    if (in_array('2', $this->sp_type, true)) {
//                        $conditions[] = 'T2."QryGroup3" = \'Y\'';
//                    }
//
//                    if (!empty($conditions)) {
//                        $sql .= ' AND ( ' . implode(' OR ', $conditions) . ' ) ';
//                    }
//                }



//                if (!empty($this->marketing_type) && !in_array('marketing_all', $this->marketing_type, true)) {
//
//                    $mrktConditions = [];
//
//                    if (in_array('fan_asmedah', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup30" = \'Y\'';
//                    }
//
//                    if (in_array('fan_mobedat', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup31" = \'Y\'';
//                    }
//
//                    if (in_array('fan_bathoor', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup32" = \'Y\'';
//                    }
//
//                    if (in_array('tasweeg_sehah', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup40" = \'Y\'';
//                    }
//
//                    if (in_array('tasweeg_mokafahh', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup41" = \'Y\'';
//                    }
//
//                    if (in_array('aleyat_aleyat', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup50" = \'Y\'';
//                    }
//
//                    if (in_array('aleyat_ray', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup51" = \'Y\'';
//                    }
//
//                    if (in_array('aleyat_ray_matary', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup52" = \'Y\'';
//                    }
//
//                    if (in_array('aleyat_khadamat', $this->marketing_type, true)) {
//                        $mrktConditions[] = 'T2."QryGroup53" = \'Y\'';
//                    }
//
//                    if (!empty($mrktConditions)) {
//                        $sql .= ' AND ( ' . implode(' OR ', $mrktConditions) . ' ) ';
//                    }
//                }


//                if (!in_array('all', $this->marketing_type, true)) {
//                    $types = array_map(fn ($t) => "'".$t."'", $this->marketing_type);
//                    $sql .= ' AND T0."MarketingType" IN ('.implode(',', $types).') ';
//                }

//
//                if ($this->sp_type != 'sp_all') {
//                    $sql .= 'AND (
//    (\''.$this->sp_type[0].'\' = \'0\' AND T2."QryGroup1" = \'Y\')
// OR (\''.$this->sp_type[0].'\' = \'1\' AND T2."QryGroup2" = \'Y\')
// OR (\''.$this->sp_type[0].'\' = \'2\' AND T2."QryGroup3" = \'Y\')
// OR (\''.$this->sp_type[0].'\' IS NULL)
//)
//';
//                }
//                dd($this->vendor_type );
                if ($this->vendor_type != 'vendor_all' && $this->vendor_type != null) {
                    $sql .= ' AND (T2."CardCode" = \'' . $this->vendor_type[0] . '\')';
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
            AND T1."DocDate" BETWEEN \'' . $start_date . '\' AND  \'' . $end_date . '\'';
//                if ($emps_type != 'employees_all') {
//                    $sql .= ' AND S."Memo" = \''.$emps_type[0].'\'';
//
//                }

//                if ($this->vendor_type != 'vendor_all' && $this->vendor_type != null) {
//                    $sql .= ' AND (T2."CardCode" = \'' . $this->vendor_type[0] . '\')';
//                }

                if($this->product_code){
                    $sql .= ' AND T0."ItemCode" = \'' . $this->product_code . '\' ';

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
//                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type)) {
//                    $placeholders = implode(',', array_fill(0, count($vendor_type), '?'));
//                    $sql .= ' AND T2."CardCode" IN (' . $placeholders . ') ';
//                    $bindings = array_merge($bindings, $vendor_type);
//                }
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
//                $marketing_type = (array) ($this->marketing_type ?? []);

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
) X
   LEFT JOIN "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery" V
   ON X."ItemCode" = V."ItemCode"';

                $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . ',X."BPLName"';

//dd($sql);


//                $sql='
//// Base SELECT
//    $sql = '';        // 🔥 RESET
    $bindings = [];   // 🔥 RESET
// $vendor_type = $this->vendor_type;
//                dd($this->cat_type);
//_____________________________________________________________________________________________________
//                $cat_type = $this->cat_type;
////                $marketing_type = $this->marketing_type;
////                dd($this->marketing_type);
//                $marketing_type = array_unique((array) $this->marketing_type);
//                $sp_type        = array_unique((array) $this->sp_type);
//                $vendor_type    = array_unique((array) $this->vendor_type);
////                $sp_type = $this->sp_type;
//$sql = '
//SELECT
//    X."ItemCode",
//    X."ItemName",
//    X."UgpEntry" AS "Unit",
//    X."VendorName",
//    X."CardName",
//    X."SlpName",
//    V."ItemGroup",
//    CASE
//        WHEN X."QryGroup1" = \'Y\' THEN \'0\'
//        WHEN X."QryGroup2" = \'Y\' THEN \'1\'
//        WHEN X."QryGroup3" = \'Y\' THEN \'2\'
//        ELSE \'\'
//    END AS "Speciality",
//    CASE
//        WHEN X."QryGroup30" = \'Y\' THEN \'fan_asmedah\'
//        WHEN X."QryGroup31" = \'Y\' THEN \'fan_mobedat\'
//        WHEN X."QryGroup32" = \'Y\' THEN \'fan_bathoor\'
//        WHEN X."QryGroup40" = \'Y\' THEN \'tasweeg_sehah\'
//        WHEN X."QryGroup41" = \'Y\' THEN \'tasweeg_mokafahh\'
//        WHEN X."QryGroup50" = \'Y\' THEN \'aleyat_aleyat\'
//        WHEN X."QryGroup51" = \'Y\' THEN \'aleyat_ray\'
//        WHEN X."QryGroup52" = \'Y\' THEN \'aleyat_ray_matary\'
//        WHEN X."QryGroup53" = \'Y\' THEN \'aleyat_khadamat\'
//        ELSE \'general\'
//    END AS "mrkt_type",
//    SUM(X."BranchQty") OVER (PARTITION BY X."ItemCode") AS "TotalQuantitySale",
//    X."BPLName" AS "BranchName",
//    X."BranchQty" AS "TotalQuantitySaleByBranch",
//    ROUND(
//        (X."BranchQty" * 100.0) /
//        NULLIF(SUM(X."BranchQty") OVER (PARTITION BY X."ItemCode"), 0),
//    2) AS "TotalSalesPer"
//FROM (
//    SELECT
//        Z."ItemCode",
//        Z."ItemName",
//        Z."UgpEntry",
//        Z."VendorName",
//        Z."CardName",
//        Z."SlpName",
//        Z."BPLName",
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
//        SUM(Z."Qty") AS "BranchQty"
//    FROM (
//        /* =======================
//           SALES INVOICES
//        ======================= */
//        SELECT
//            T0."ItemCode",
//            T2."ItemName",
//            T2."UgpEntry",
//            V."CardName" AS "VendorName",
//            C."CardName" AS "CardName",
//            S."SlpName",
//            B."BPLName",
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
//            SUM(T0."Quantity") AS "Qty"
//        FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD C  ON T1."CardCode" = C."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OCRD V  ON T2."CardCode" = V."CardCode"
//        LEFT  JOIN AL_YASEEN_AGRI_PLIVE.OSLP S  ON C."SlpCode" = S."SlpCode"
//        INNER JOIN AL_YASEEN_AGRI_PLIVE.OBPL B  ON T1."BPLId" = B."BPLId"
//        WHERE T1."CANCELED" = \'N\'
//        AND T1."DocDate" BETWEEN  \'' . $start_date . '\' AND  \'' . $end_date . '\'
//        AND T1."BPLId" IN (' . implode(',', $sap_depts) . ')
//';
//
//if($this->product_code){
//    $sql .= ' AND T0."ItemCode" = \'' . $this->product_code . '\' ';
//
//}
//// -------- Employee Filter --------
//if ($emps_type != 'employees_all') {
//    $sql .= ' AND S."Memo" = \'' . $emps_type[0] . '\' ';
//}
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
////                if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type)) {
////                    $placeholders = implode(',', array_fill(0, count($vendor_type), '?'));
////                    $sql .= ' AND T2."CardCode" IN (' . $placeholders . ') ';
////                    $bindings = array_merge($bindings, $vendor_type);
////                }
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
//$sql .= '
//        GROUP BY
//            T0."ItemCode",
//            T2."ItemName",
//            T2."UgpEntry",
//            V."CardName",
//            C."CardName",
//            S."SlpName",
//            B."BPLName",
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
//        LEFT JOIN "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery" V
//        ON X."ItemCode" = V."ItemCode"
//--ORDER BY "ItemCode" ASC
//';
//                $sql .= ' ORDER BY "' . $sortBy . '" ' . $direction . ', X."BPLName"';

//dd($sql);


//                --ORDER BY
//                --"TotalQuantitySale" DESC
//                --"ItemCode"
//                --  X."BPLName",
//  --  X."CardName"
//                dd($sql);
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
