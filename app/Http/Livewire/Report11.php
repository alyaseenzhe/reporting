<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report11 extends Component
{
    public $dept_id = ["dept_all"];
    public $itemGrp = [];
    public $vendor_list = [];
    public $categories = [];
    public $show_msg = false;
    public $report_type = 'byItem';

    public $scribes_results = [];
    public $sap_results = [];
    public $group_results = [];

    public $scribes_codes = [];
    public $sap_codes = [];

    protected $listeners = ['item-category' => 'item_category', 'create-report' => 'create_report'];

    public function render()
    {

        $this->itemGroups();
        $this->vendors();
        return view('livewire.report11')
            ->layout('layouts.dashboard');
    }

    public function create_report($start_date, $end_date, $dept_id, $group_type, $cat_type, $sp_type, $vendor_type, $report_type) {

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
        $this->report_type = $report_type;
        $this->scribes_results = [];
        $this->sap_results = [];
        $this->group_results = [];

        $this->productCodes($group_type, $cat_type, $sp_type, $vendor_type);
//        $this->scribesQuery($start_date, $end_date, $dept_id, $sp_type);
//        $this->sapQuery($start_date, $end_date, $dept_id);
//
//        $scribes_collection = collect($this->scribes_results);
//        $sap_collection = collect($this->sap_results);


        // dates
        if (is_null($start_date) == false && is_null($end_date) == false) {

            if ($start_date >= '2011-07-01' && $end_date <= '2023-12-31') {
                $this->scribesQuery($start_date, $end_date, $dept_id, $sp_type);
            }
            elseif ($start_date >= '2011-07-01' && $end_date > '2023-12-31') {
                $this->scribesQuery($start_date, '2023-12-31', $dept_id, $sp_type);
                $this->sapQuery('2024-01-01', $end_date, $dept_id);
            }
            elseif ($start_date > '2023-12-31' && $end_date > '2023-12-31') {
                $this->sapQuery($start_date, $end_date, $dept_id);
            }

        }
        // end dates

        if (count($this->scribes_results) > 0 || count($this->sap_results) > 0) {
            $scribes_collection = collect($this->scribes_results);
            $sap_collection = collect($this->sap_results);

            $merged_results = $scribes_collection->merge($sap_collection);
//        dd($merged_results->pluck('ItemCode'));
//        dd($merged_results);


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
                        'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                        'Cost' => $row->sum('Cost'),
                        'GrossProfit' => $row->sum('GrossProfit'),
                        'GrossProfitPer' => ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                    ];
                });
            }
            else if ($this->report_type == "byDepartment") {
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
//                        $x =[
////                'OldCode' => $row->first()['OldCode'],
//                            'OldCode' => count($this->scribes_results) > 0 ? $row->first()->OldCode : $row->first()['OldCode'],
//                            'ItemName' => count($this->scribes_results) > 0 ? $row->first()->ItemName : $row->first()['ItemName'],
//                            'SalUnitMsr' => count($this->scribes_results) > 0 ? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
//                            'Speciality' => count($this->scribes_results) > 0 ? $row->first()->Speciality : $row->first()['Speciality'],
//                            'VendorName' => count($this->scribes_results) > 0 ? $row->first()->VendorName : $row->first()['VendorCode'],
//                            'Department' => count($this->scribes_results) > 0 ? $row->first()->Department : $row->first()['Department'],
//                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
//                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
//                            'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
////                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
//                            'Cost' => $row->sum('Cost'),
//                            'GrossProfit' => $row->sum('GrossProfit'),
//                            'GrossProfitPer' => ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
////                'GrossProfitPer' => $row->sum('GrossProfitPer'),
//                        ];
//                        dd($x);
                        return [
//                'OldCode' => $row->first()['OldCode'],
                            'OldCode' => count($this->scribes_results) > 0 ? $row->first()->OldCode : $row->first()['OldCode'],
                            'ItemName' => count($this->scribes_results) > 0 ? $row->first()->ItemName : $row->first()['ItemName'],
                            'SalUnitMsr' => count($this->scribes_results) > 0 ? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
                            'Speciality' => count($this->scribes_results) > 0 ? $row->first()->Speciality : $row->first()['Speciality'],
                            'VendorName' => count($this->scribes_results) > 0 ? $row->first()->VendorName : $row->first()['VendorCode'],
                            'Department' => count($this->scribes_results) > 0 ? $row->first()->Department : $row->first()['Department'],
                            'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
                            'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
                            'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
//                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
                            'Cost' => $row->sum('Cost'),
                            'GrossProfit' => $row->sum('GrossProfit'),
                            'GrossProfitPer' => ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
//                'GrossProfitPer' => $row->sum('GrossProfitPer'),
                        ];
                    });

//                    return [
////                'OldCode' => $row->first()['OldCode'],
//                        'OldCode' => count($this->scribes_results) > 0 ? $row->first()->OldCode : $row->first()['OldCode'],
//                        'ItemName' => count($this->scribes_results) > 0 ? $row->first()->ItemName : $row->first()['ItemName'],
//                        'SalUnitMsr' => count($this->scribes_results) > 0 ? $row->first()->SalUnitMsr : $row->first()['SalUnitMsr'],
//                        'Speciality' => count($this->scribes_results) > 0 ? $row->first()->Speciality : $row->first()['Speciality'],
//                        'VendorName' => count($this->scribes_results) > 0 ? $row->first()->VendorName : $row->first()['VendorCode'],
//                        'Department' => count($this->scribes_results) > 0 ? $row->first()->Department : $row->first()['Department'],
//                        'TotalQuantitySold' => $row->sum('TotalQuantitySold'),
//                        'TotalSalesAmount' => $row->sum('TotalSalesAmount'),
//                        'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold'),
////                'AverageUnitPrice' => $row->sum('TotalSalesAmount')/$row->sum('TotalQuantitySold')$row->avg('AverageUnitPrice'),
//                        'Cost' => $row->sum('Cost'),
//                        'GrossProfit' => $row->sum('GrossProfit'),
//                        'GrossProfitPer' => ($row->sum('GrossProfit')/$row->sum('Cost'))*100,
////                'GrossProfitPer' => $row->sum('GrossProfitPer'),
//                    ];
                })->sortBy(['OldCode', 'Department']);
            }
//        dd($groupResults);
        }

//        dd($this->group_results);
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
            $itemGroupQuery = 'SELECT "ItmsGrpCod" AS "ItemGroupCode","ItmsGrpNam" AS "ItemGroupName" FROM AL_YASEEN_TEST.OITB WHERE "ItmsGrpCod" NOT IN (100,101,102,103,180)';

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
            $vendorQuery = 'SELECT "CardCode" AS "VendorCode", "CardName" AS "VendorName" FROM AL_YASEEN_TEST.OCRD WHERE "CardType" = \'S\'';

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

    public function item_category($group_type) {

        $this->categories = [];

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
                $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
            }
            elseif ($group_type == "farms") {
                $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
            }
            elseif ($group_type == "sundries") {
                $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
            }
            elseif ($group_type == "groups_all") {
                $categoryQuery = 'SELECT DISTINCT T0."ItmsGrpCod", T1."ItmsGrpNam" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
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

        $this->emit('finished-categories', $this->categories);
    }

    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type) {

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

            if ($group_type == "commerce") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
            }
            elseif ($group_type == "farms") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'30%\'';
            }
            elseif ($group_type == "sundries") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE T0."ItemCode" LIKE \'99%\'';
            }
            elseif ($group_type == "groups_all") {
                $categoryQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode" FROM AL_YASEEN_TEST."OITM" T0 JOIN AL_YASEEN_TEST."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';
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

    public function scribesQuery($start_date, $end_date, $departments, $sps) {

        if (count($this->scribes_codes) > 0) {

            if (in_array('dept_all', $departments)) {
                $departments = ["0001", "0101","0102","0103","0104","0105","0106","0107","0108","0109","0110","0111","0112","0201","0202","0203"];
            }

            if ($sps == null || in_array('sp_all', $sps)) {
                $sps = ["'0'", "'1'","'2'"];
            }

            foreach ($departments as &$value) {
                $value = "'" . $value . "'";
            }

            if ($this->report_type == "byItem") {

                $scribesStmt = "select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo ,sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/SUM(SalesTotalCost))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo ,SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,-sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo  union all select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName";

            }
            else if ($this->report_type == "byDepartment") {

                $scribesStmt = "select code as OldCode,BaseUnits as SalUnitMsr,Name,Arabic_Name as ItemName,productNo,SpecialityCode as Speciality, VendorNo , (select top 1 code from DeptMast where NodeNo= Department) as Department , sum(SalesQty) as TotalQuantitySold,sum(Srate) as Srate ,sum(Svalue) as TotalSalesAmount,sum(AVGPrice) as AverageUnitPrice,sum(cost) as cost2,sum(SalesTotalCost) as Cost , (SUM(Svalue)- SUM(SalesTotalCost)) as GrossProfit, (((SUM(Svalue)- SUM(SalesTotalCost))/SUM(SalesTotalCost))*100) as GrossProfitPer, sum(SExtrafieldsTotal) as SExtrafieldsTotal,sum(Spartybalance) as Spartybalance,sum(PurchaseQty) as PurchaseQty,sum(Prate) as Prate ,sum(Pvalue) as Pvalue,sum(PurchasePrice) as PurchasePrice,sum(PExtrafieldsTotal) as PExtrafieldsTotal,sum(Ppartybalance) as Ppartybalance,sum(PurchaseTotalCost) as PurchaseTotalCost, VendorName from (
select tbl1.code,tbl1.BaseUnits,tbl1.Name,tbl1.Arabic_Name,tbl1.productNo,tbl1.SpecialityCode, tbl1.VendorNo , Department, SalesQty,Srate,Svalue,AVGPrice,cost,SalesTotalCost ,SExtrafieldsTotal,Spartybalance,PurchaseQty,Prate,Pvalue,PurchasePrice,PExtrafieldsTotal,Ppartybalance,PurchaseTotalCost, AccMast.Arabic_Name as VendorName from (select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, sum(ActualQty+FreeQty) as SalesQty,sum(Rate) as Srate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],(sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as AVGPrice,sum(AvgRate*(ActualQty+FreeQty)) as cost,sum(totalcost) as SalesTotalCost ,sum(ExtraFieldsTotal) as SExtrafieldsTotal,sum(partybalance) as Spartybalance,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance,0 as PurchaseTotalCost from sinvoice,Productmast
where nodeno=productno  and ActualVoucherPrefix='SIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From
DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department  union all select
code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, -sum(ActualQty+FreeQty) as SalesQty,-sum(Rate) as Srate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Svalue],0 as  AVGPrice,-sum(AvgRate*(ActualQty+FreeQty)) as cost,-sum(totalcost) as SalesTotalCost,-sum(ExtraFieldsTotal) as
SExtrafieldsTotal,sum(partybalance) as Spartybalance ,0 as PurchaseQty,0 as Prate,0 as Pvalue,0 as PurchasePrice,0 as PExtrafieldsTotal,0 as Ppartybalance ,0 as PurchaseTotalCost from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='SRT-' And (Select Name From DeptMast Where NodeNo =
Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode = '1' Or SpecialityCode =
'2') And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department,0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0
as Spartybalance, sum(ActualQty+FreeQty) as PurchaseQty ,(sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as Prate,sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue], (sum([Value]*exchangeRate+ExtraFieldsTotal)/sum(ActualQty+FreeQty)) as PurchasePrice, sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,sum(totalcost) as PurchaseTotalCost  from Pinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PIV-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And PIDate>='".$start_date."'And PIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name,BaseUnits,SpecialityCode, VendorNo, Department
union all
select code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo, Department, 0 as SalesQty,0 as Srate,0 as [Svalue],0 as AVGPrice,0 as cost,0 as SalesTotalCost ,0 as SExtrafieldsTotal,0 as Spartybalance,-sum(ActualQty+FreeQty) as PurchaseQty,-sum(Rate) as Prate,-sum([Value]*exchangeRate+ExtraFieldsTotal) as [Pvalue],0 as  PurchasePrice,-sum(ExtraFieldsTotal) as PExtrafieldsTotal,-sum(partybalance) as Ppartybalance,-sum(totalcost) as PurchaseTotalCost from Sinvoice,Productmast where nodeno=productno  and ActualVoucherPrefix='PRT-' And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And Blocked='N'  And ProductNo in (select NodeNo from ProductMast where Code in (".implode(", ", $this->scribes_codes).")) And (Select Name from deptmast where Nodeno=department) Not In (Select DeptName From DeptRights Where UserName ='su') And Department in (select NodeNo from DeptMast where Code in (". implode(', ', $departments). ")) And (SpecialityCode in (".implode(", ", $sps).")) And SIDate>='".$start_date."'And SIDate <='".$end_date." 23:59:25' group by productno,code,Name,Arabic_Name ,BaseUnits,SpecialityCode, VendorNo, Department) as tbl1
left join AccMast on tbl1.VendorNo = AccMast.NodeNo
) as tbl2
group by code,BaseUnits,Name,Arabic_Name,productNo,SpecialityCode, VendorNo ,VendorName, Department";
            }


//        dd($scribesStmt);

            $query = DB::connection('sqlsrv')->select($scribesStmt);

//            dd($query);

            $this->scribes_results = $query;
        }
    }

    public function sapQuery($start_date, $end_date, $departments) {

        if (count($this->sap_codes) > 0) {
            $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' =>'5', '0104' =>'6', '0105' =>'7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' =>  '16', '0203' => '17'];
            $sap_depts = [];
            foreach ($departments as $department) {
                array_push($sap_depts, $depts[$department]);
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

                    $sql = 'SELECT * FROM (
SELECT
    T0."ItemCode" AS "ItemCode",
    T1."ItemName" AS "ItemName",
    SUM(T0."Quantity") AS "TotalQuantitySold",
    SUM(T0."LineTotal") AS "TotalSalesAmount",
    AVG(T0."Price") AS "AverageUnitPrice",
    COUNT(DISTINCT T0."DocEntry") AS "NumberOfInvoices",
    SUM(T0."GrssProfit") as "GrossProfit",
    SUM(T0."GPTtlBasPr") as "Cost",
    (SUM(T0."GrssProfit")/ SUM(T0."GPTtlBasPr"))*100 as "GrossProfitPer",
    T2."BPLName" AS "Branch",
    CASE
		WHEN T1."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T1."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T1."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	T1."SalUnitMsr",
	T1."U_UDF1" as "OldCode",
	T4."CardCode" AS "VendorCode",
    T4."CardName" AS "VendorName"
FROM
    AL_YASEEN_TEST.INV1 T0
JOIN
    AL_YASEEN_TEST.OITM T1 ON T0."ItemCode" = T1."ItemCode"
JOIN
    AL_YASEEN_TEST.OINV T3 ON T0."DocEntry" = T3."DocEntry"
JOIN
    AL_YASEEN_TEST.OBPL T2 ON T3."BPLId" = T2."BPLId"
JOIN
	AL_YASEEN_TEST.OCRD T4 ON T1."CardCode" = T4."CardCode"
WHERE
    T3."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
	AND T2."BPLId" IN ('. implode(', ', $sap_depts).')
	AND T0."ItemCode" IN ('. implode(', ', $this->sap_codes).')
GROUP BY
	T0."ItemCode", T1."ItemName", T2."BPLName", T1."QryGroup1", T1."QryGroup2", T1."QryGroup3", T1."SalUnitMsr", T1."U_UDF1", T4."CardCode", T4."CardName"
ORDER BY
    "TotalSalesAmount" DESC
    ) as tbl1';

                }
                else if ($this->report_type == "byDepartment") {
                    $sql = 'SELECT * FROM (
SELECT
    T0."ItemCode" AS "ItemCode",
    T1."ItemName" AS "ItemName",
    SUM(T0."Quantity") AS "TotalQuantitySold",
    SUM(T0."LineTotal") AS "TotalSalesAmount",
    AVG(T0."Price") AS "AverageUnitPrice",
    COUNT(DISTINCT T0."DocEntry") AS "NumberOfInvoices",
    SUM(T0."GrssProfit") as "GrossProfit",
    SUM(T0."GPTtlBasPr") as "Cost",
    (SUM(T0."GrssProfit")/ SUM(T0."GPTtlBasPr"))*100 as "GrossProfitPer",
    T2."BPLName" AS "Branch",
    T2."TaxIdNum" AS "Department",
    CASE
		WHEN T1."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T1."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T1."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	T1."SalUnitMsr",
	T1."U_UDF1" as "OldCode",
	T4."CardCode" AS "VendorCode",
    T4."CardName" AS "VendorName"
FROM
    AL_YASEEN_TEST.INV1 T0
JOIN
    AL_YASEEN_TEST.OITM T1 ON T0."ItemCode" = T1."ItemCode"
JOIN
    AL_YASEEN_TEST.OINV T3 ON T0."DocEntry" = T3."DocEntry"
JOIN
    AL_YASEEN_TEST.OBPL T2 ON T3."BPLId" = T2."BPLId"
JOIN
	AL_YASEEN_TEST.OCRD T4 ON T1."CardCode" = T4."CardCode"
WHERE
    T3."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
	AND T2."BPLId" IN ('. implode(', ', $sap_depts).')
	AND T0."ItemCode" IN ('. implode(', ', $this->sap_codes).')
GROUP BY
	T0."ItemCode", T1."ItemName", T2."BPLName", T2."TaxIdNum", T1."QryGroup1", T1."QryGroup2", T1."QryGroup3", T1."SalUnitMsr", T1."U_UDF1", T4."CardCode", T4."CardName"
ORDER BY
    "TotalSalesAmount" DESC
    ) as tbl1';
                }


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
            }
        }

    }
}
