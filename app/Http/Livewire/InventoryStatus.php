<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InventoryStatus extends Component
{
    public $dept_id = ["dept_all"];
    public $vendor_list = [];
    public $products_codes = [];
    public $show_msg = false;
    public $report_type = 'byItem';
    public $sap_results = [];

    protected $listeners = ['create-report' => 'create_report'];

    public function booted() {


        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('inventory-status-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {
        $this->product_lists();
        $this->vendors();
    }

    public function render()
    {
        return view('livewire.inventory-status')
            ->layout('layouts.dashboard');
    }

    public function create_report($search_type, $product_code, $vendor_code, $departments) {

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
//        $this->report_type = $report_type;
        $this->sap_results = [];

        $this->sapQuery($search_type, $product_code, $vendor_code, $departments);


//        dd($this->sap_results);
        $this->show_msg = true;
        $this->emit('finished');
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
            $productQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode", T0."ItemName", T0."SalUnitMsr" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';

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

    public function sapQuery($search_type, $product_code, $vendor_code, $departments) {
        $sql = '';
//            dd($search_type);

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

//                if ($search_type == 'item_code_search') {
//
//                }
//                elseif ($search_type == 'vendor_search') {
            $sql = 'SELECT
	T3."CardCode",
	T3."CardName",
    T0."ItemCode" AS "ItemCode",
    T1."ItemName" AS "ItemName",
    T1."OnHand",
    CASE
		WHEN T1."QryGroup1" = \'Y\' THEN \'0\'
		WHEN T1."QryGroup2" = \'Y\' THEN \'1\'
		WHEN T1."QryGroup3" = \'Y\' THEN \'2\'
		ELSE \'\'
	END AS "Speciality",
	T1."SalUnitMsr",
    T2."WhsCode" AS "WarehouseCode",
    T2."WhsName" AS "WarehouseName",
    SUM(T0."OnHand") AS "QuantityOnHand"
FROM
    AL_YASEEN_AGRI_PLIVE.OITW T0
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.OITM T1 ON T0."ItemCode" = T1."ItemCode"
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.OWHS T2 ON T0."WhsCode" = T2."WhsCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."CardCode" = T3."CardCode"
WHERE T1."ItmsGrpCod" != 180
AND T2."WhsCode" != \'01\'';

            if ($search_type == 'vendor_search' && $vendor_code != 'vendor_all') {
                $sql .= 'AND T3."CardCode" = \''.$vendor_code.'\'';
            }
            elseif ($search_type == 'item_code_search') {
                $sql .= 'AND T0."ItemCode" = \''.$product_code.'\'';
            }

            if (in_array('dept_all', $departments) == false) {
                $sql .= 'AND T2."WhsCode" IN ('.implode(', ', $departments).')';
            }

            $sql .= ' GROUP BY
    	T1."QryGroup1",T1."QryGroup2",T1."QryGroup3",T1."SalUnitMsr",T3."CardCode",T3."CardName", T0."ItemCode", T1."ItemName", T1."OnHand", T2."WhsCode", T2."WhsName"
ORDER BY
    T3."CardCode", T0."ItemCode", T2."WhsCode"';



//                }

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

//                    dd($this->sap_results);

            }
            odbc_close($conn);
        }

    }
}
