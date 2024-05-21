<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report21 extends Component
{
    public $customer_list = [];
    public $customer_id;

    public $scribes_results = [];
    public $sap_results = [];

    protected $listeners = ['create-report' => 'create_report'];

    public function mount() {

    }

    public function render()
    {
        $this->getCustomers();;

        return view('livewire.report21')
            ->layout('layouts.dashboard');
    }

    public function getCustomers()
    {

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
            $customerQuery = 'SELECT T0."CardCode", T0."CardName" FROM AL_YASEEN_TEST.OCRD T0 WHERE T0."CardType" = \'C\'';

//            $sql = 'SELECT T0."CardName" FROM AL_YASEEN_TEST.OCRD T0 WHERE T0."CardCode"= \'0100465\'';

//            $result = odbc_exec($conn, $sql);
            $result = odbc_exec($conn, $customerQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
//                $aa = odbc_result_all($result, "border=1");
//                $aa = odbc_result_all($result, "border=1");
//                $a = odbc_result($result, "CardName");
//                $a= odbc_result_all($result);
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->customer_list, $row);
                }

//                dd($this->customer_list);
//                $this->customer_list = $result;
//                dd($a);
            }
            odbc_close($conn);
        }

    }

    public function create_report($customer_id) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->customer_id = $customer_id;

        $this->generateReport();
    }

    public function generateReport() {



        $this->scribesQuery();;
        $this->emit('finished');

//        dd($this->scribes_results);


    }

    public function scribesQuery() {

        $scribesStmt = "SELECT Account1_Code as 'CardCode', VNo as 'TransId',  VDate as 'RefDate', Narration as 'LineMemo', Debit, Credit, CumulativeBalance FROM (
SELECT Account1_Code, VDate, VNo, Narration, Debit, Credit, SUM(Debit-Credit) OVER (ORDER BY VNo) as CumulativeBalance
FROM (
Select
	'1-1-04' As VDate,
	'' As  VNo,
	'OPB' As Narration,
	sum(round(AmountDr*PurchaseData.ExchangeRate,2)) as Debit,
	sum(round(AmountCr*PurchaseData.ExchangeRate,2))  as Credit,
	AccMast.Code As Account1_Code
	From AccountsC5.dbo.PurchaseData,AccountsC5.dbo.AccMast
	Where   (AccountDr = AccMast.NodeNo) And ((PurchaseData.VoucherNo Like 'OPB-%%') OR (VoucherDate < '07/01/2011')) And (AccMast.Code = '".$this->customer_id."') And (PDC = 'N')  And (DoNotUpdateAccounts=0)
	Group By AccMast.Code
UNION ALL
Select
	VoucherDate As VDate,
	PurchaseData.VoucherNo As  VNo,
	PurchaseData.Narration,
	SUM(round(AmountDr*ExchangeRate,2)) as Debit,
	SUM(round(AmountCr*ExchangeRate,2))  as Credit,
	AccMast.Code As Account1_Code
	From AccountsC5.dbo.PurchaseData,AccountsC5.dbo.AccMast
	Where  PurchaseData.VoucherNo Not Like 'OPB-%%'
	And VoucherDate >= '07/01/2011'
	And VoucherDate <= '12/31/2023 23:59:25'
	And AccMast.Code = '".$this->customer_id."'
	And AccMast.NodeNo = AccountDr
	And (PDC = 'N')
	And (DoNotUpdateAccounts=0)
	GROUP BY VoucherDate, PurchaseData.VoucherNo, PurchaseData.Narration, AccMast.Code
	) as tbl
	) as tbl2
	ORDER BY TransId";

        $query = DB::connection('sqlsrv')->select($scribesStmt);
        $this->scribes_results = $query;

    }

    public function sapQuery() {

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }
        // else {
        //     echo 'good';
        // }

        $driver = env('DB_CONNECTION_FOURTH');
//        $driver = 'HDBODBC';
        // $driver = 'HDBODBC32';

// Host
// Note: I am hosting it on the Amazon AWS, so my host looks like this. Put whatever your system administrator gave you
        $host = env('DB_HOST_FOURTH');
//        $host = "SAPHANA-CT90006.cloudtaktiks.com:30015";

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

            /*
             * Typical errors include
             *
             * Error code: S1000
             * General error;416 user is locked; try again later: lock time is 1440
             * Too many unsuccessful login attempts
             * Solution: wait and try again with other credentials
             *
             * Error code: 08S01
             * Communication link failure;-10709 Connection failed (RTE:[89006] Syste, SQL state 08S01 in SQLConnect
             * Solution: check your connection details, host, port.
             */
        }
        else
        {
            // Do a basic select from DUMMY with is basically a synonym for SYS.DUMMY
            //$sql = 'SELECT * FROM DUMMY';
            // $sql = 'SELECT "CardName" FROM AL_YASEEN_TEST."OCRD"';
            //  $sql = "SELECT TABLE_NAME FROM TABLES WHERE SCHEMA_NAME = 'AL_YASEEN_TEST'";
            //  $sql = "SELECT CardName FROM TABLES WHERE SCHEMA_NAME = 'AL_YASEEN_TEST'";
            // $sql = "SELECT \"CardName\", \"CardCode\" FROM AL_YASEEN_TEST.OCRD WHERE \"CardCode\" = '0100465'";
            // $sql = "SELECT ".'"CardName", '.'"CardCode" '."FROM AL_YASEEN_TEST.OCRD WHERE ".'"CardCode"'." = '0100465'";
            $sql = 'SELECT * FROM AL_YASEEN_TEST.OCRD T0 WHERE T0."CardCode"= \'0100465\'';
            // $columns = odbc_columns($conn, 'CardName', 'CardCode');
            // while (($row = odbc_fetch_array($columns))) {
            //     print_r($row);
            //     break; // further rows omitted for brevity
            // }

            //  var_dump($columns);

            $result = odbc_exec($conn, $sql);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                // echo odbc_num_rows($result);
                // var_dump(odbc_fetch_row($result));
                $aa = odbc_result_all($result, "border=1");

                var_dump($aa);

                // var_dump($result);
                // while ($row = odbc_fetch_object($result))
                // {
                //     // Should output one row containing the string 'X'
                //     var_dump($row['CardNameXX']);
                // }
            }
            odbc_close($conn);
        }
    }
}
