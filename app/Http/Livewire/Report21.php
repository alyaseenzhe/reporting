<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report21 extends Component
{
    public $customer_list = [];
    public $customer_id;

    public $scribes_results = [];
    public $sap_results = [];
    public $start_date;
    public $end_date;
    public $show_msg = false;

    protected $listeners = ['create-report' => 'create_report'];


    public function booted() {


        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('report-21', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {

    }

    public function render()
    {
        $this->getCustomers();

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
            $branches = json_decode(Auth::user()->branches);
            $cust_code = ["3" => "01%", "7" => "03%", "10" => "02%", "13" => "04%", "4" => "05%", "6" => "06%", "5" => "07%", "12" => "08%", "11" => "09%", "9" => "10%", "8" => "11%", "505" => "12%"];
            $stmt = '';

            foreach ($branches as $key => $branch) {

                if (count($branches) > 1) {
                    if ($key === array_key_first($branches)) {
                        $stmt .= ' AND (T0."CardCode" LIKE \''.$cust_code[$branch].'\' OR ';
                    }
                    elseif ($key === array_key_last($branches)) {
                        $stmt .= 'T0."CardCode" LIKE \''.$cust_code[$branch].'\')';
                    }
                    else {
                        $stmt .= 'T0."CardCode" LIKE \''.$cust_code[$branch].'\' OR ';
                    }
                }
                else {
                    $stmt .= ' AND (T0."CardCode" LIKE \''.$cust_code[$branch].'\')';
                }
            }

            $customerQuery = 'SELECT T0."CardCode", T0."CardName" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\' ' . $stmt;

//            $sql = 'SELECT T0."CardName" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardCode"= \'0100465\'';

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

    public function create_report($customer_id, $start_date, $end_date) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->customer_id = $customer_id;
        $this->start_date = $start_date == ''? null : $start_date;
        $this->end_date = $end_date == ''? null : $end_date;

        $this->scribes_results = [];
        $this->sap_results = [];

        $this->generateReport();
    }

    public function generateReport() {

        $this->show_msg = false;

        if (is_null($this->start_date) == false && is_null($this->end_date) == false) {

            if ($this->start_date >= '2011-07-01' && $this->end_date <= '2023-12-31') {
                $this->scribesQuery($this->start_date, $this->end_date);
            }
            elseif ($this->start_date >= '2011-07-01' && $this->end_date > '2023-12-31') {
                $this->scribesQuery($this->start_date, '2023-12-31');
                $this->sapQuery('2024-01-01', $this->end_date);
            }
            elseif ($this->start_date > '2023-12-31' && $this->end_date > '2023-12-31') {
                $this->sapQuery($this->start_date, $this->end_date);
            }

        }
        else {


//            $this->start_date = is_null($this->start_date) ? '2011-07-01' : $this->start_date;
//            $this->end_date = is_null($this->end_date) ? null :

            if (is_null($this->start_date) && is_null($this->end_date) == false) {
                if ($this->end_date <= '2023-12-31') {
                    $this->scribesQuery('2011-07-01', $this->end_date);
                }
                else {
                    $this->scribesQuery('2011-07-01', '2023-12-31');
                    $this->sapQuery('2024-01-01', $this->end_date);
                }
            }
            elseif (is_null($this->start_date) == false && is_null($this->end_date)) {

                if ($this->start_date <= '2023-12-31') {
                    $this->scribesQuery($this->start_date, '2023-12-31');
                    $this->sapQuery('2024-01-01', Carbon::today()->format('Y-m-d'));
                }
                else {
                    $this->sapQuery($this->start_date, Carbon::today()->format('Y-m-d'));
                }
            }
            elseif (is_null($this->start_date) && is_null($this->end_date)) {
                $this->scribesQuery('2011-07-01', '2023-12-31');
                $this->sapQuery('2024-01-01', Carbon::today()->format('Y-m-d'));
            }
        }

        $this->show_msg = true;
        $this->emit('finished');

    }

    public function scribesQuery($start_date, $end_date) {

        $scribesStmt = "SELECT Account1_Code as 'CardCode', VNo as 'TransId',  VDate as 'RefDate', Narration as 'LineMemo', Debit, Credit, CumulativeBalance FROM (
SELECT Account1_Code, VDate, VNo, Narration, Debit, Credit, SUM(Debit-Credit) OVER (ORDER BY VDate, VNo) as CumulativeBalance
FROM (
Select
	'7-1-11' As VDate,
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
	WHERE (tbl2.VDate >= '".$start_date."' and tbl2.VDate <= '".$end_date."')
	ORDER BY RefDate, TransId";

        $query = DB::connection('sqlsrv')->select($scribesStmt);
        $this->scribes_results = $query;

    }

    public function sapQuery($start_date, $end_date) {

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
            // $sql = 'SELECT "CardName" FROM AL_YASEEN_AGRI_PLIVE."OCRD"';
            //  $sql = "SELECT TABLE_NAME FROM TABLES WHERE SCHEMA_NAME = 'AL_YASEEN_AGRI_PLIVE'";
            //  $sql = "SELECT CardName FROM TABLES WHERE SCHEMA_NAME = 'AL_YASEEN_AGRI_PLIVE'";
            // $sql = "SELECT \"CardName\", \"CardCode\" FROM AL_YASEEN_AGRI_PLIVE.OCRD WHERE \"CardCode\" = '0100465'";
            // $sql = "SELECT ".'"CardName", '.'"CardCode" '."FROM AL_YASEEN_AGRI_PLIVE.OCRD WHERE ".'"CardCode"'." = '0100465'";
//            $sql = 'SELECT * FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardCode"= \'0100465\'';
            $sql = 'SELECT * FROM (
SELECT T3."CardCode", T0."TransId", T0."RefDate", T1."LineMemo", T1."Debit", T1."Credit",
       SUM(T1."Debit" - T1."Credit") OVER (PARTITION BY T1."Account" ORDER BY T0."RefDate", T0."TransId") AS "CumulativeBalance"
FROM AL_YASEEN_AGRI_PLIVE.OJDT T0
INNER JOIN AL_YASEEN_AGRI_PLIVE.JDT1 T1 ON T0."TransId" = T1."TransId"
INNER JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T1."Account" = T2."AcctCode"
INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."ShortName" = T3."CardCode"
WHERE T0."RefDate" >= \'20230101\'
AND T3."CardCode" = \''.$this->customer_id.'\'
ORDER BY T0."TaxDate", T0."TransId") as "tbl1"
WHERE ("RefDate" >= \''.$start_date.'\' AND "RefDate" <= \''.$end_date.'\')';



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
//                $aa = odbc_result_all($result, "border=1");
//                $x = odbc_fetch_object($result);
//                $this->sap_results
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
                }

//                var_dump($this->sap_results);

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
