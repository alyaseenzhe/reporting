<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\PDO;
use Livewire\Component;
use function PHPUnit\Framework\isNull;

class CommissionReport extends Component
{
    public $area_id = -1;
    public $result;
    public $result_tbl2 = [];
    public $sap_results = [];
    public $sap_results2 = [];
    public $selected_date;
    public $first_date;
    public $last_date;
    public $total_grossProfit = 0;
    public $cost_center = ['3' => '0101', '4' => '0102', '5' => '0103', '6' => '0104', '7' => '0105', '8' => '0106', '9' => '0107', '12' => '0110', '11' => '0109', '10' => '0108', '13' => '0111', '14' => '0112'];
    public $area_commission = ['3' => 11.46, '4' => 10.5, '5' => 11.46, '6' => 11.46, '7' => 10.5, '8' => 10.5, '9' => 11.46, '10' => 10.5, '11' => 10.5, '12' => 11.46, '13' => 10.5, '14' => 10.5];
    public $area_loss_profit = [
        '2024-08' => ['3' => 252507, '4' => 268936, '5' => 334426, '6' => 74377, '7' => 287371, '8' =>  192702, '9' => 166642, '10' => 171551, '11' => 148537, '12' => 933995, '13' => 238114, '14' => 121138],
        '2024-09' => ['3' => 298927 , '4' =>    205518, '5' => 318544, '6' => 219056, '7' => 320238, '8' => 256024, '9' => 353061, '10' => 278410, '11' => 251469, '12' => 789377, '13' => 286491, '14' => 113507],
    ];
    public $emp_position = [
'10046' =>	'sales_manager',
/*'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',
'10046' =>	'sales_manager',*/
'10035' =>	'area_manager',
'10041' =>	'area_manager',
'10175' =>	'area_manager',
'10051' =>	'area_manager',
'10057' =>	'area_manager',
'10214' =>	'area_manager',
'10066' =>	'area_manager',
'10074' =>	'area_manager',
'10078' =>	'area_manager',
'10058' =>	'mat_dev_manager2',
'10088' =>	'area_manager',
'10036' =>	'store_manager',
'10262' =>	'store_manager',
'10268' =>	'store_manager',
'10227' =>	'store_manager',
'10219' =>	'store_manager',
'10148' =>	'store_manager',
'10203' =>	'store_manager',
'10285' =>	'store_manager',
'10253' =>	'store_manager',
'10264' =>	'store_manager',
'10248' =>	'store_manager',
'10042' =>	'store_manager',
'10159' =>	'mat_dev_manager1',
'10261' =>	'mat_dev_manager1',
'10295' =>	'mat_dev_manager2',
'10182' =>	'mat_dev_manager1',
'10059' =>	'mat_dev_manager1',
'10279' =>	'mat_dev_manager1',
'10068' =>	'mat_dev_manager1',
'10190' =>	'mat_dev_manager1',
'10079' =>	'mat_dev_manager1',
'10263' =>	'mat_dev_manager1',
'10286' =>	'mat_dev_manager1',
'10266' =>	'mat_dev_manager1',
'10232' =>	'mat_dev_manager2',
'10272' =>	'mat_dev_manager2',
'10261' =>	'mat_dev_manager2',
'10083' =>	'mat_dev_manager2',
'10313' =>	'mat_dev_manager2',
'10239' =>	'mat_dev_manager1',
'10309' =>	'mat_dev_manager2',
'10299' =>	'mat_dev_manager2',
'10276' =>	'store_manager',
'10297' =>	'store_manager',

    ];
    public $position_commission = [
        "sales_manager" => ["sales_manager" => 0,	"area_manager" => 0, "store_manager"=>	0, "mat_dev_manager1" =>	0, "mat_dev_manager2" => 0	],
        "area_manager" => ["sales_manager" => 5,	"area_manager" => 90, "store_manager"=>	5, "mat_dev_manager1" =>	0, "mat_dev_manager2" => 0	],
        "store_manager" => ["sales_manager" => 5,	"area_manager" => 25, "store_manager"=>	70, "mat_dev_manager1" =>	0, "mat_dev_manager2" => 0	],
        "mat_dev_manager1" => ["sales_manager" => 5,	"area_manager" => 25, "store_manager"=>	5, "mat_dev_manager1" =>	65, "mat_dev_manager2" => 0	],
        "mat_dev_manager2" => ["sales_manager" => 5,	"area_manager" => 25, "store_manager"=>	5, "mat_dev_manager1" =>	0, "mat_dev_manager2" => 65]
    ];

    public $customer = [];
    public $customer_code = [];
    public $customer_balance = [];
    public $slp_code = [];
    public $slp_aging = [];
    public $slp_balance = [];
    public $branch_balance = 0;
    public $profitAndLoss = 0;

    protected $rules = [
        'area_id' => 'required|not_in:-1',
        'selected_date' => 'required',
    ];

    protected $messages = [
        'area_id.required' => "مطلوب",
        'area_id.not_in' => "مطلوب",
        'selected_date.required' => "مطلوب",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('commission-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $result = 0;
        return view('livewire.commission-report')
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        set_time_limit(2000);
        $this->validate();
        $this->emit('show-container');

        $this->first_date = date('Y-m-01', strtotime($this->selected_date));
        $this->last_date = date('Y-m-t', strtotime($this->selected_date));


        $this->sapQuery($this->first_date, $this->last_date);



        /*
        $start_of_day = Carbon::parse($this->first_date)->subMonths(3);
        $end_of_day = Carbon::parse($this->last_date);
        $days = intval($end_of_day->diffInDays($start_of_day) + 1);
        */

    }

    public function sapQuery($start_date, $end_date) {

        $this->sap_results = [];
        $this->sap_results2 = [];
        $this->slp_aging = [];
        $this->slp_code = [];

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

            /*
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

            */

            $loss_profit_sql = 'SELECT SUM("Credit Amount")-SUM("Debit Amount") AS "ProfitAndLoss" FROM (
--SELECT * FROM (

SELECT
OACT."AcctCode" AS "Account Code",
OACT."AcctName" AS "Account Name",
JDT1."RefDate" AS "Transaction Date",
OJDT."Memo" AS "Transaction Description",
JDT1."Debit" AS "Debit Amount",
JDT1."Credit" AS "Credit Amount",
--JDT1."LocTotal" AS "Local Total Amount",
JDT1."ProfitCode" AS "Cost Center"
FROM
AL_YASEEN_AGRI_PLIVE.JDT1
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OJDT ON JDT1."TransId" = OJDT."TransId"
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OACT ON JDT1."Account" = OACT."AcctCode"
WHERE
JDT1."RefDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
AND JDT1."ProfitCode" = \''.$this->cost_center[$this->area_id].'\'
ORDER
BY JDT1."RefDate"
)

WHERE
"Account Code" LIKE \'4%\'
OR "Account Code" LIKE \'5%\'
OR "Account Code" LIKE \'6%\'
OR "Account Code" LIKE \'7%\'
OR "Account Code" LIKE \'8%\'
';




            $sql = '
Select * from (
SELECT "BPLId",
 "BPLName",
SUM("total") as "Outstanding_Receivable", SUM("total_120") as "Outstanding_Receivable_120" FROM (
SELECT

\'Receivables\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit") as "total",
SUM(
CASE WHEN DAYS_BETWEEN(T0."DueDate",\''.$end_date.'\') >= 120 THEN (
(CASE WHEN T0."DebCred" = \'D\' THEN (T0."Debit"-T0."Credit")-ifnull(T4."ReconSum",0)
WHEN T0."DebCred" = \'C\' THEN -((T0."Credit"-T0."Debit")-ifnull(T4."ReconSum",0)) END)) ELSE 0 END) as "total_120"
,0,0,0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode" AND T2."CardType" = \'C\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"
LEFT JOIN (SELECT SUM("ReconSum") AS "ReconSum",SUM("ReconSumSC") AS "ReconSumSC",SUM("ReconSumFC") AS "ReconSumFC","TransRowId","TransId" FROM AL_YASEEN_AGRI_PLIVE.ITR1 T0 JOIN AL_YASEEN_AGRI_PLIVE.OITR T1 ON T0."ReconNum" = T1."ReconNum" AND T1."ReconDate" <= \''.$end_date.'\'
GROUP BY "TransRowId","TransId") T4 ON T0."TransId" = T4."TransId" AND T0."Line_ID" = T4."TransRowId"

WHERE T1."RefDate" <= \''.$end_date.'\'
AND T3."BPLId" = '.$this->area_id.'

GROUP BY

T3."BPLId",
T0."DebCred",
T3."BPLName",
T3."GlblLocNum"
)
GROUP BY "BPLId",
 "BPLName") outstanding_tbl

 LEFT JOIN (
 SELECT "BranchName", "BranchCode", "BranchRegistrationNumber", SUM("GrossProfitLC") AS "GrossProfitLC" FROM (
SELECT "BranchName", "BranchCode", "BranchRegistrationNumber", "DocumentDate", "GrossProfitLC"
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "WarehouseBranchCode" = '.$this->area_id.'
AND "DocumentDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\')
GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber"
 ) gross_tbl ON gross_tbl."BranchCode" = outstanding_tbl."BPLId"
 LEFT JOIN (

SELECT T1."BPLId", T0."Warehouse", Sum(T0."TransValue") "TransVal",
Sum(T0."CogsVal") "COGS" from AL_YASEEN_AGRI_PLIVE.oinm T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OBPL T1 ON T0."Warehouse" = T1."DflWhs"
WHERE T0."DocDate" <= \''.$end_date.'\'
AND T1."BPLId" = '.$this->area_id.'
Group By T1."BPLId", T0."Warehouse"
 ) stock_tbl ON stock_tbl."BPLId" = outstanding_tbl."BPLId"';


            $sql2 = '
SELECT *, (SELECT "Memo" FROM AL_YASEEN_AGRI_PLIVE.OSLP WHERE "SlpCode" = "SalesEmployeeOrBuyerNumber") as "OldCode" FROM (
SELECT
	"BPLId",
    "SlpCode",
	MAX(CASE WHEN "Aging Period" = \'121+ Days\' THEN "Invoice Date" END) AS "Oldest_Invoice",
    SUM(CASE WHEN "Aging Period" = \'121+ Days\' THEN "Outstanding Amount" END) AS "Outstanding_Amount_120",
	SUM("Outstanding Amount") AS "Total_Outstanding_Amount"

FROM (
SELECT
	T1."BPLId",
    T0."CardCode" AS "Customer Code",
    T0."CardName" AS "Customer Name",
    T0."SlpCode",
    T1."DocDate" AS "Invoice Date",
    T1."DocDueDate" AS "Due Date",
    T1."DocTotal" - T1."PaidToDate" AS "Outstanding Amount",
    CASE
        WHEN DAYS_BETWEEN(T1."DocDate", \''.$end_date.'\') <= 30 THEN \'0-30 Days\'
        WHEN DAYS_BETWEEN(T1."DocDate", \''.$end_date.'\') BETWEEN 31 AND 60 THEN \'31-60 Days\'
        WHEN DAYS_BETWEEN(T1."DocDate", \''.$end_date.'\') BETWEEN 61 AND 90 THEN \'61-90 Days\'
        WHEN DAYS_BETWEEN(T1."DocDate", \''.$end_date.'\') BETWEEN 91 AND 120 THEN \'91-120 Days\'
        ELSE \'121+ Days\'
    END AS "Aging Period"
FROM
    AL_YASEEN_AGRI_PLIVE.OCRD T0
    INNER JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."CardCode" = T1."CardCode"
WHERE
    T1."DocStatus" = \'O\'
    AND T0."CardType" = \'C\'
    AND T0."frozenFor" = \'N\'
)
--WHERE "Aging Period" = \'121+ Days\'
GROUP BY
	"BPLId",
    "SlpCode"
    --"Aging Period"
) tbl1
FULL OUTER JOIN (
/*
Select "BranchCode","SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName", SUM("NetSalesAmountLC") AS "NetSalesAmountLC", SUM("GrossProfitLC") AS "GrossProfitLC"
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
GROUP BY
"BranchCode","SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"
*/

SELECT "BranchCode","SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName", SUM("NetSalesAmountLC") AS "NetSalesAmountLC", SUM("GrossProfitLC") AS "GrossProfitLC"
FROM (
SELECT (SELECT
    T."DocNum"
FROM
    (
        SELECT
            T0."DocNum",
            ROW_NUMBER() OVER (ORDER BY T0."DocNum") AS "rownum"
        FROM
            AL_YASEEN_AGRI_PLIVE.ORIN T0
        JOIN
            AL_YASEEN_AGRI_PLIVE.RIN1 T1 ON T0."DocEntry" = T1."DocEntry"
        WHERE
            T0."DocNum" = "DocumentNumber"
            AND T1."BaseType" = \'203\'
    ) T
WHERE
    T."rownum" = 1


) as "DownPaymentFlag",*
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
--AND "SalesEmployeeOrBuyerNumber" = 322
AND "DocumentTypeCode" != 17
)
WHERE "DownPaymentFlag" IS NULL
GROUP BY
"BranchCode","SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName"

) tabl2 ON tbl1."SlpCode" = tabl2."SalesEmployeeOrBuyerNumber"

--outstanding amount per employee
LEFT JOIN (

SELECT "SlpCode1" AS "SlpCode", "full_outstanding", "Outstanding_4_Months", "full_outstanding" - "Outstanding_4_Months" AS "outstanding_overdue" FROM (
SELECT * FROM (

	select "SlpCode" as "SlpCode1",IFNULL(sum("Balance"), 0) AS "full_outstanding" from (

	SELECT
		T0."SlpCode",
	    T0."CardCode" AS "Customer Code",
	    T0."CardName" AS "Customer Name",
	    SUM(T1."Debit" - T1."Credit") AS "Balance"
	FROM
	    AL_YASEEN_AGRI_PLIVE.OCRD T0
	INNER JOIN
	    AL_YASEEN_AGRI_PLIVE.JDT1 T1 ON T0."CardCode" = T1."ShortName"
	WHERE
	    T1."RefDate" <= \''.$end_date.'\'
	GROUP BY
	    T0."SlpCode",T0."CardCode", T0."CardName"
	ORDER BY
	    T0."CardCode"

	)
	GROUP BY "SlpCode"
) tbl1

LEFT JOIN (

    SELECT "SlpCode" AS "SlpCode2", IFNULL(SUM("total"), 0) AS "Outstanding_4_Months" FROM (
	SELECT "BusinessPartnerCode", "BusinessPartnerName", SUM(CASE WHEN "PostingDate" >= ADD_DAYS(\''.$end_date.'\', -120) AND "PostingDate" <= \''.$end_date.'\' THEN "AgingBalanceDueLC" END) AS "total" FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery"
	WHERE "PostingDate" <= \''.$end_date.'\'
	GROUP BY "BusinessPartnerCode", "BusinessPartnerName") tbl1

	LEFT JOIN (SELECT "CardCode", "CardName", "SlpCode" FROM AL_YASEEN_AGRI_PLIVE."OCRD") tbl2
	ON tbl1."BusinessPartnerCode" = tbl2."CardCode"
	GROUP BY "SlpCode"
) tbl2
    ON tbl1."SlpCode1" = tbl2."SlpCode2"
    ) as "due_tbl"


) tbl3
ON tbl1."SlpCode" = tbl3."SlpCode"
-- end of outstanding per employee

--- Balance Due -------
LEFT JOIN (
SELECT "SlpCode", SUM("CumulativeBalance") AS "Full_Outstanding22" FROM (
SELECT T0."CardCode", T1."Name",T0."SlpCode", T1."CumulativeBalance" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0
LEFT JOIN (
SELECT "Name", "CumulativeBalance" FROM (

WITH CumulativeBalances AS (
    SELECT
        T0."RefDate",
        T0."TransId",
        T0."BaseRef",
        T1."FormatCode",
        T0."LineMemo",
        T0."ShortName" AS "Name",
        T0."Debit",
        T0."Credit",
        T0."Ref1",
        T0."Ref2",
        T0."Ref3Line",
        T0."DueDate",
        T0."TaxDate",
        SUM(T0."Debit" - T0."Credit") OVER (PARTITION BY T0."ShortName" ORDER BY T0."RefDate" ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS "CumulativeBalance",
        ROW_NUMBER() OVER (PARTITION BY T0."ShortName" ORDER BY T0."RefDate" DESC) AS rn
    FROM
        AL_YASEEN_AGRI_PLIVE."JDT1" T0
    INNER JOIN
        AL_YASEEN_AGRI_PLIVE."OACT" T1 ON T0."Account" = T1."AcctCode"
    INNER JOIN
        AL_YASEEN_AGRI_PLIVE."OJDT" T2 ON T0."TransId" = T2."TransId"
    WHERE
        T2."RefDate" <= \''.$end_date.'\'
)
SELECT
    "RefDate",
    "TransId",
    "BaseRef",
    "FormatCode",
    "LineMemo",
    "Name",
    "Debit",
    "Credit",
    "Ref1",
    "Ref2",
    "Ref3Line",
    "DueDate",
    "TaxDate",
    "CumulativeBalance"
FROM
    CumulativeBalances
WHERE
    rn = 1
ORDER BY
    "Name"

    )
) T1
ON T0."CardCode" = T1."Name"
WHERE T0."CardType" = \'C\'
)
GROUP BY "SlpCode"

) tbl4
ON tbl1."SlpCode" = tbl4."SlpCode"

-- End of Balance Due -----

WHERE "BranchCode" = '.$this->area_id.'
AND "BPLId" IS NOT NULL
';

//            dd($sql2);

            $result_profit = odbc_exec($conn, $loss_profit_sql);
            if (!$result_profit)
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
                while ($row = odbc_fetch_array($result_profit)) {
//                    array_push($this->sap_results, $row);
//                    array_push($this->sap_results, $row);
                    $this->profitAndLoss = $row["ProfitAndLoss"];
                }

//                dd($this->profitAndLoss);
            }



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

//                dd($this->sap_results);

                // var_dump($result);
                // while ($row = odbc_fetch_object($result))
                // {
                //     // Should output one row containing the string 'X'
                //     var_dump($row['CardNameXX']);
                // }
            }


            $result2 = odbc_exec($conn, $sql2);
            if (!$result2)
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
                while ($row = odbc_fetch_array($result2)) {
                    array_push($this->sap_results2, $row);
                    array_push($this->slp_code, $row["SlpCode"]);
                }

//                dd($this->sap_results2);

                $gross_collect = collect($this->sap_results2);
                $this->total_grossProfit = $gross_collect->sum('GrossProfitLC');



//                $this->getCustomers('293', $end_date);
                foreach ($this->slp_code as $slp) {
                    $ag = $this->getCustomers($slp, $end_date);
                    array_push($this->slp_aging, [$slp => ['balance' => $ag[0], 'balance due' => $ag[1], 'oldest_date' => $ag[2], 'cust_code' => $ag[3]]]);
                }

                $this->branch_balance = 0;

//                dd($this->slp_aging);

                $flattenedArray = [];
                foreach ($this->slp_aging as $item) {
                    foreach ($item as $key => $value) {
                        $flattenedArray[$key] = $value;
//                        $this->branch_balance += $value["balance"];
                    }
                }




                $this->slp_aging = $flattenedArray;
//                dd($this->slp_aging);
                $this->branch_balance = collect($this->slp_aging)->sum('balance');

//                dd(collect($this->slp_aging)->sum('balance'));


//                dd($flattenedArray);
////                $this->user_ids = $empty;
//                dd(array_values($this->slp_aging));
//                dd($this->slp_aging[292]);
//                dd($this->slp_aging);


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

    public function getCustomers($slpCode, $end_date) {

//        $slpCode = '293';

        $this->customer = [];
        $this->customer_code = [];
        $this->customer_balance = [];
        $aging_balance = 0;
        $customer_balance = 0;
        $full_customer_balance = 0;
        $oldest_inv = Carbon::now()->format('Y-m-d');
        $c_code = '';

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

//            $sql = 'SELECT T0."CardCode", T1."CumulativeBalance" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0
//--WHERE T0."CardType" = \'C\'
//LEFT JOIN (
//SELECT "Name", "CumulativeBalance" FROM (
//
//WITH CumulativeBalances AS (
//    SELECT
//        T0."RefDate",
//        T0."TransId",
//        T0."BaseRef",
//        T1."FormatCode",
//        T0."LineMemo",
//        T0."ShortName" AS "Name",
//        T0."Debit",
//        T0."Credit",
//        T0."Ref1",
//        T0."Ref2",
//        T0."Ref3Line",
//        T0."DueDate",
//        T0."TaxDate",
//        SUM(T0."Debit" - T0."Credit") OVER (PARTITION BY T0."ShortName" ORDER BY T0."RefDate" ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS "CumulativeBalance",
//        ROW_NUMBER() OVER (PARTITION BY T0."ShortName" ORDER BY T0."RefDate" DESC) AS rn
//    FROM
//        AL_YASEEN_AGRI_PLIVE."JDT1" T0
//    INNER JOIN
//        AL_YASEEN_AGRI_PLIVE."OACT" T1 ON T0."Account" = T1."AcctCode"
//    INNER JOIN
//        AL_YASEEN_AGRI_PLIVE."OJDT" T2 ON T0."TransId" = T2."TransId"
//    WHERE
//        T2."RefDate" <= \''.$end_date.'\'
//)
//SELECT
//    "RefDate",
//    "TransId",
//    "BaseRef",
//    "FormatCode",
//    "LineMemo",
//    "Name",
//    "Debit",
//    "Credit",
//    "Ref1",
//    "Ref2",
//    "Ref3Line",
//    "DueDate",
//    "TaxDate",
//    "CumulativeBalance"
//FROM
//    CumulativeBalances
//WHERE
//    rn = 1
//ORDER BY
//    "Name"
//
//    )
//) T1
//ON T0."CardCode" = T1."Name"
//WHERE T0."CardType" = \'C\'
//AND T1."CumulativeBalance" IS NOT NULL
//AND T1."CumulativeBalance" != 0
//AND T1."CumulativeBalance" > 0
//AND T0."SlpCode" != -1
//AND T0."SlpCode" = \''.$slpCode.'\'
//--AND T0."CardCode" = \'0100412\'
//';

            $sql_customer = '
            SELECT T0."CardCode", T0."CardName" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0
WHERE T0."CardType" = \'C\'
AND T0."SlpCode" = \''.$slpCode.'\'
AND T0."CardCode" NOT IN (\'0100000\', \'0200000\', \'0300000\', \'0400000\', \'0500000\', \'0600000\', \'0700000\', \'0800000\', \'0900000\', \'1000000\', \'1100000\', \'1200000\')
            ';



//            dd($sql_customer);
//            dd($sql);




//            $result = odbc_exec($conn, $sql);
            $result = odbc_exec($conn, $sql_customer);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->customer, $row);
                    array_push($this->customer_code, $row["CardCode"]);
                }

//                dd($this->customer_code);
//                $balance_due = collect($this->customer);
//                $x = collect($balance_due->where('CardCode', '0100590')->first()["CumulativeBalance"]);
//                $cum_balance = floatval($x[0]);
//                dd(floatval($cum_balance));

            }

//            $balance_due = collect($this->customer);


            // for customer balance due
            foreach ($this->customer_code as $cust_code) {
//                $cust_code = '0100582';


//                $customer_balance += $cum_balance;

                $sql_balance = '
                SELECT * FROM (
SELECT
    T0."RefDate",
    T0."TransId",
    T0."BaseRef",
    T1."FormatCode",
    T0."LineMemo",
    T0."ShortName" AS "Name",
    T0."Debit",
    T0."Credit",
    T0."Ref1",
    T0."Ref2",
    T0."Ref3Line",
    T0."DueDate",
    T0."TaxDate",
    SUM(T0."Debit" - T0."Credit") OVER (ORDER BY T0."RefDate" ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS "CumulativeBalance",
    CASE
                WHEN T0."TransType" = 24 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T00."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked A/R Invoice",
            CASE
                WHEN T0."TransType" = 13 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T22."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked Incoming Payment"

FROM
    AL_YASEEN_AGRI_PLIVE."JDT1" T0
INNER JOIN
    AL_YASEEN_AGRI_PLIVE."OACT" T1 ON T0."Account" = T1."AcctCode"
INNER JOIN
    AL_YASEEN_AGRI_PLIVE."OJDT" T2 ON T0."TransId" = T2."TransId"
WHERE
    T2."RefDate" <= \''.$end_date.'\'
    AND T0."ShortName" = \''.$cust_code.'\'
ORDER BY
    T0."RefDate"
    )
    WHERE ("Linked A/R Invoice" IS NULL AND "Linked Incoming Payment" IS NULL)
    ORDER BY "RefDate" DESC, "TransId" DESC
    LIMIT 1';
//                dd($sql_balance);

                $result_balance = odbc_exec($conn, $sql_balance);
                if (!$result_balance)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {
                    while ($row = odbc_fetch_array($result_balance)) {
//                        array_push($this->customer, $row);
//                        array_push($this->customer_code, $row["CardCode"]);
                        if (floatval($row["CumulativeBalance"]) >= 1) {
//                            array_push($this->customer_balance, [$row["CardCode"] => $row["CumulativeBalance"]]);
//                            $this->customer_balance[$row["CardCode"]] = $row["CumulativeBalance"];
                            $this->customer_balance[$row["Name"]] = $row["CumulativeBalance"];
                            $full_customer_balance += $row["CumulativeBalance"];
                        }
                    }
                }

            }
//            dd(array_keys($this->customer_balance));
//            dd($this->customer_balance["0101015"]);
//            dd($this->customer_balance);

            // for aging 120
//            foreach ($this->customer_code as $cust_code) {
            foreach (array_keys($this->customer_balance) as $cust_code) {
//                $cust_code = '0100582';

//                $x = collect($balance_due->where('CardCode', $cust_code)->first()["CumulativeBalance"]);
                $x = $this->customer_balance[$cust_code];
                $cum_balance = floatval($x);

                $customer_balance += $cum_balance;


                $sql_aging = 'SELECT IFNULL(SUM("Debit (LC)"), 0) AS "Aging" FROM (
WITH CumulativeSum AS (
    SELECT * FROM (
        SELECT
            T0."RefDate" AS "Posting Date",
            T0."DueDate" AS "Due Date",
            T0."TaxDate" AS "Document Date",
            CASE
                WHEN T0."TransType" = 18 THEN \'A/P Invoice\'
                WHEN T0."TransType" = 19 THEN \'A/P Credit Note\'
                WHEN T0."TransType" = 46 THEN \'Outgoing Payment\'
                WHEN T0."TransType" = 30 THEN \'Journal Entry\'
                WHEN T0."TransType" = 13 THEN \'A/R Invoice\'
                WHEN T0."TransType" = 24 THEN \'Incoming Payment\'
                WHEN T0."TransType" = 14 THEN \'A/R Credit Note\'
            END AS "Document Type",
            T3."CardCode" AS "Business Partner Code",
            T3."CardName" AS "Business Partner Name",
            T0."BaseRef" AS "Document Number",
            T0."TransId" AS "Transaction Number",
            T1."Account" AS "Account Code",
            T2."AcctName" AS "Account Name",
            --T1."Debit" AS "Debit (LC)",
            CASE WHEN T1."Debit" >= 0 THEN T1."Debit" ELSE 0 END AS "Debit (LC)",
            T1."Credit" AS "Credit (LC)",
            (T1."Debit" - T1."Credit") AS "Balance",
            T0."Memo" AS "Remarks",
            --SUM(T1."Debit") OVER (ORDER BY T0."RefDate" DESC, T0."TransId" DESC) AS "Cumulative Debit",
            SUM(CASE WHEN T1."Debit" >= 0 THEN T1."Debit" ELSE 0 END) OVER (ORDER BY T0."RefDate" DESC, T0."TransId" DESC) AS "Cumulative Debit",
            CASE
                WHEN T0."TransType" = 24 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T00."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked A/R Invoice",
            CASE
                WHEN T0."TransType" = 13 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T22."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked Incoming Payment"
        FROM
            AL_YASEEN_AGRI_PLIVE.OJDT T0
            INNER JOIN AL_YASEEN_AGRI_PLIVE.JDT1 T1 ON T0."TransId" = T1."TransId"
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T1."Account" = T2."AcctCode"
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."ShortName" = T3."CardCode"
        WHERE
            T0."RefDate" <= \''.$end_date.'\'
            AND T3."CardCode" = \''.$cust_code.'\'
        ORDER BY T0."RefDate" DESC,T0."TransId" DESC
    )
    WHERE ("Linked A/R Invoice" IS NULL AND "Linked Incoming Payment" IS NULL)
),
AdjustedSum AS (
    SELECT *,
           CASE
               WHEN "Cumulative Debit" > '.$cum_balance.' THEN '.$cum_balance.' - (SUM("Debit (LC)") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING))
               ELSE "Debit (LC)"
           END AS "Adjusted Debit"
    FROM CumulativeSum
),
FinalResult AS (
    SELECT *,
           SUM("Adjusted Debit") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS "Cumulative Adjusted Debit"
    FROM AdjustedSum
),
RankedResults AS (
    SELECT *,
           ROW_NUMBER() OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS rn
    FROM FinalResult
)
SELECT
    "Posting Date",
    "Due Date",
    "Document Date",
    "Document Type",
    "Business Partner Code",
    "Business Partner Name",
    "Document Number",
    "Transaction Number",
    "Account Code",
    "Account Name",
    "Adjusted Debit" AS "Debit (LC)",
    "Credit (LC)",
    "Balance",
    "Remarks",
    "Adjusted Debit",
    "Cumulative Debit",
    "Cumulative Adjusted Debit"
FROM RankedResults
WHERE rn <= (
    SELECT MAX(rn)
    FROM RankedResults
    WHERE "Cumulative Adjusted Debit" = '.$cum_balance.'
)
ORDER BY "Posting Date" DESC, "Transaction Number" DESC

)
WHERE DAYS_BETWEEN("Posting Date", \''.$end_date.'\') > 120
AND "Debit (LC)" != 0;
';

                $result_aging = odbc_exec($conn, $sql_aging);
                if (!$result_aging)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {
                    while ($row = odbc_fetch_array($result_aging)) {
//                        dd($row);
//                        array_push($this->customer, $row);
//                        array_push($this->customer_code, $row["CardCode"]);
                        $aging_balance += $row["Aging"];
                    }

                }

                // oldest invoice

                $sql_oldest_invoice = 'SELECT "Posting Date" AS "Oldest_Date" FROM (
WITH CumulativeSum AS (
    SELECT * FROM (
        SELECT
            T0."RefDate" AS "Posting Date",
            T0."DueDate" AS "Due Date",
            T0."TaxDate" AS "Document Date",
            CASE
                WHEN T0."TransType" = 18 THEN \'A/P Invoice\'
                WHEN T0."TransType" = 19 THEN \'A/P Credit Note\'
                WHEN T0."TransType" = 46 THEN \'Outgoing Payment\'
                WHEN T0."TransType" = 30 THEN \'Journal Entry\'
                WHEN T0."TransType" = 13 THEN \'A/R Invoice\'
                WHEN T0."TransType" = 24 THEN \'Incoming Payment\'
                WHEN T0."TransType" = 14 THEN \'A/R Credit Note\'
            END AS "Document Type",
            T3."CardCode" AS "Business Partner Code",
            T3."CardName" AS "Business Partner Name",
            T0."BaseRef" AS "Document Number",
            T0."TransId" AS "Transaction Number",
            T1."Account" AS "Account Code",
            T2."AcctName" AS "Account Name",
            --T1."Debit" AS "Debit (LC)",
            CASE WHEN T1."Debit" >= 0 THEN T1."Debit" ELSE 0 END AS "Debit (LC)",
            T1."Credit" AS "Credit (LC)",
            (T1."Debit" - T1."Credit") AS "Balance",
            T0."Memo" AS "Remarks",
            --SUM(T1."Debit") OVER (ORDER BY T0."RefDate" DESC, T0."TransId" DESC) AS "Cumulative Debit",
            SUM(CASE WHEN T1."Debit" >= 0 THEN T1."Debit" ELSE 0 END) OVER (ORDER BY T0."RefDate" DESC, T0."TransId" DESC) AS "Cumulative Debit",
            CASE
                WHEN T0."TransType" = 24 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T00."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked A/R Invoice",
            CASE
                WHEN T0."TransType" = 13 THEN (
                    SELECT MAX(T22."DocNum")
                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
                    WHERE T22."DocNum" = T0."BaseRef"
                    AND T00."DocDate" = T22."DocDate"
                    AND T11."SumApplied" = T22."DocTotal"
                )
                ELSE NULL
            END AS "Linked Incoming Payment"
        FROM
            AL_YASEEN_AGRI_PLIVE.OJDT T0
            INNER JOIN AL_YASEEN_AGRI_PLIVE.JDT1 T1 ON T0."TransId" = T1."TransId"
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T1."Account" = T2."AcctCode"
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."ShortName" = T3."CardCode"
        WHERE
            T0."RefDate" <= \''.$end_date.'\'
            AND T3."CardCode" = \''.$cust_code.'\'
        ORDER BY T0."RefDate" DESC,T0."TransId" DESC
    )
    WHERE ("Linked A/R Invoice" IS NULL AND "Linked Incoming Payment" IS NULL)
),
AdjustedSum AS (
    SELECT *,
           CASE
               WHEN "Cumulative Debit" > '.$cum_balance.' THEN '.$cum_balance.' - (SUM("Debit (LC)") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING))
               ELSE "Debit (LC)"
           END AS "Adjusted Debit"
    FROM CumulativeSum
),
FinalResult AS (
    SELECT *,
           SUM("Adjusted Debit") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS "Cumulative Adjusted Debit"
    FROM AdjustedSum
),
RankedResults AS (
    SELECT *,
           ROW_NUMBER() OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS rn
    FROM FinalResult
)
SELECT
    "Posting Date",
    "Due Date",
    "Document Date",
    "Document Type",
    "Business Partner Code",
    "Business Partner Name",
    "Document Number",
    "Transaction Number",
    "Account Code",
    "Account Name",
    "Adjusted Debit" AS "Debit (LC)",
    "Credit (LC)",
    "Balance",
    "Remarks",
    "Adjusted Debit",
    "Cumulative Debit",
    "Cumulative Adjusted Debit"
FROM RankedResults
WHERE rn <= (
    SELECT MAX(rn)
    FROM RankedResults
    WHERE "Cumulative Adjusted Debit" = '.$cum_balance.'
)
ORDER BY "Posting Date" DESC, "Transaction Number" DESC
)
WHERE "Document Type" != \'Incoming Payment\'
AND "Debit (LC)" != 0
ORDER BY "Posting Date" ASC
LIMIT 1';


//                $sql_oldest_invoice2 = 'SELECT * FROM (
//WITH CumulativeSum AS (
//    SELECT * FROM (
//        SELECT
//            T0."RefDate" AS "Posting Date",
//            T0."DueDate" AS "Due Date",
//            T0."TaxDate" AS "Document Date",
//            CASE
//                WHEN T0."TransType" = 18 THEN \'A/P Invoice\'
//                WHEN T0."TransType" = 19 THEN \'A/P Credit Note\'
//                WHEN T0."TransType" = 46 THEN \'Outgoing Payment\'
//                WHEN T0."TransType" = 30 THEN \'Journal Entry\'
//                WHEN T0."TransType" = 13 THEN \'A/R Invoice\'
//                WHEN T0."TransType" = 24 THEN \'Incoming Payment\'
//                WHEN T0."TransType" = 14 THEN \'A/R Credit Note\'
//            END AS "Document Type",
//            T3."CardCode" AS "Business Partner Code",
//            T3."CardName" AS "Business Partner Name",
//            T0."BaseRef" AS "Document Number",
//            T0."TransId" AS "Transaction Number",
//            T1."Account" AS "Account Code",
//            T2."AcctName" AS "Account Name",
//            T1."Debit" AS "Debit (LC)",
//            T1."Credit" AS "Credit (LC)",
//            (T1."Debit" - T1."Credit") AS "Balance",
//            T0."Memo" AS "Remarks",
//            SUM(T1."Debit") OVER (ORDER BY T0."TransId" DESC) AS "Cumulative Debit",
//            CASE
//                WHEN T0."TransType" = 24 THEN (
//                    SELECT T22."DocNum"
//                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
//                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
//                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
//                    WHERE T00."DocNum" = T0."BaseRef"
//                    AND T00."DocDate" = T22."DocDate"
//                    AND T11."SumApplied" = T22."DocTotal"
//                )
//                ELSE NULL
//            END AS "Linked A/R Invoice",
//            CASE
//                WHEN T0."TransType" = 13 THEN (
//                    SELECT T22."DocNum"
//                    FROM AL_YASEEN_AGRI_PLIVE.ORCT T00
//                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T11 ON T00."DocEntry" = T11."DocNum"
//                    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T22 ON T22."DocEntry" = T11."DocEntry"
//                    WHERE T22."DocNum" = T0."BaseRef"
//                    AND T00."DocDate" = T22."DocDate"
//                    AND T11."SumApplied" = T22."DocTotal"
//                )
//                ELSE NULL
//            END AS "Linked Incoming Payment"
//        FROM
//            AL_YASEEN_AGRI_PLIVE.OJDT T0
//            INNER JOIN AL_YASEEN_AGRI_PLIVE.JDT1 T1 ON T0."TransId" = T1."TransId"
//            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T1."Account" = T2."AcctCode"
//            LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."ShortName" = T3."CardCode"
//        WHERE
//            T0."RefDate" <= \''.$end_date.'\'
//            AND T3."CardCode" = \''.$cust_code.'\'
//        ORDER BY T0."TransId" DESC
//    )
//    WHERE ("Linked A/R Invoice" IS NULL AND "Linked Incoming Payment" IS NULL)
//),
//AdjustedSum AS (
//    SELECT *,
//           CASE
//               WHEN "Cumulative Debit" > '.$cum_balance.' THEN '.$cum_balance.' - (SUM("Debit (LC)") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING))
//               ELSE "Debit (LC)"
//           END AS "Adjusted Debit"
//    FROM CumulativeSum
//),
//FinalResult AS (
//    SELECT *,
//           SUM("Adjusted Debit") OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS "Cumulative Adjusted Debit"
//    FROM AdjustedSum
//),
//RankedResults AS (
//    SELECT *,
//           ROW_NUMBER() OVER (ORDER BY "Posting Date" DESC, "Transaction Number" DESC) AS rn
//    FROM FinalResult
//)
//SELECT
//    "Posting Date",
//    "Due Date",
//    "Document Date",
//    "Document Type",
//    "Business Partner Code",
//    "Business Partner Name",
//    "Document Number",
//    "Transaction Number",
//    "Account Code",
//    "Account Name",
//    "Adjusted Debit" AS "Debit (LC)",
//    "Credit (LC)",
//    "Balance",
//    "Remarks",
//    "Adjusted Debit",
//    "Cumulative Debit",
//    "Cumulative Adjusted Debit"
//FROM RankedResults
//WHERE rn <= (
//    SELECT MAX(rn)
//    FROM RankedResults
//    WHERE "Cumulative Adjusted Debit" = '.$cum_balance.'
//)
//ORDER BY "Posting Date" DESC, "Transaction Number" DESC
//)
//WHERE "Document Type" != \'Incoming Payment\'
//ORDER BY "Posting Date" ASC';

                $result_oldest = odbc_exec($conn, $sql_oldest_invoice);
                if (!$result_oldest)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {
//                    $ss = [];
                    while ($row = odbc_fetch_array($result_oldest)) {
//                        dd($row);
//                        array_push($this->customer, $row);
//                        array_push($this->customer_code, $row["CardCode"]);

//                        array_push($ss, $row);
//                        dd(Carbon::parse($row["Oldest_Date"]) . '--' .$oldest_inv . '||' . Carbon::parse($row["Oldest_Date"])->lt($oldest_inv));
//                        $a = Carbon::parse($row["Oldest_Date"])->format('Y-m-d');

                        $fmt = Carbon::parse($row["Oldest_Date"])->format('Y-m-d');
//                        dd($oldest_inv);

                        $x = Carbon::createFromFormat('Y-m-d', $fmt);
//                        $y = Carbon::createFromFormat('Y-m-d', $oldest_inv);

//                        if(Carbon::parse($row["Oldest_Date"])->lt($oldest_inv)) {
                        if($x->lt($oldest_inv)) {
                            $oldest_inv = $x->format('Y-m-d');
                            $c_code = $cust_code;
//                            $oldest_inv = $row["Oldest_Date"];
                        }
                    }

//                    dd($ss);

                }

                // end of oldest invoice
            }


            odbc_close($conn);
        }

        return [$customer_balance, $aging_balance, $oldest_inv, $c_code];
    }
}
