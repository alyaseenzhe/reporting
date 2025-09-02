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
        '10059' =>	'area_manager',
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
        '10300' =>	'mat_dev_manager2',
        '10239' =>	'mat_dev_manager1',
        '10299' =>	'mat_dev_manager2',
        '10276' =>	'store_manager',
        '10297' =>	'store_manager',
        '10312' =>	'store_manager',
        '10309' =>	'store_manager',
        '10330' =>	'store_manager',
        '10328' =>	'store_manager',
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

    /**
     * booted - Handles user authentication and redirects.
     *
     * This function performs two main checks:
     * 1. Checks if the authenticated user is **not active**. If so, it redirects to the 'non-active-user' route.
     * 2. Checks if the user is either an **administrator** or belongs to a user group with access to 'commission-report'. If either condition is true, the function proceeds. Otherwise, it redirects to the 'dashboard' route.
     *
     */
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

    /**
     * render - Renders the Livewire component view.
     *
     * This function returns the view for the `commission-report` Livewire component.
     * It also specifies the `layouts.dashboard` file as the layout to be used for the view.
     */
    public function render()
    {
        $result = 0;
        return view('livewire.commission-report')
            ->layout('layouts.dashboard');
    }

    /**
     * generateReport - Generates the commission report based on a selected date.
     *
     * This function initiates the report generation process. It first sets the time limit to 2000 seconds
     * to prevent timeout errors for large reports. It then validates the input, and emits a Livewire event to show a loading container.
     * The function calculates the first and last day of the month from the `selected_date` property. Finally, it calls the `sapQuery` method with these dates
     * to fetch the necessary data.
     */
    public function generateReport()
    {
        set_time_limit(2000);
        $this->validate();
        $this->emit('show-container');

        $this->first_date = date('Y-m-01', strtotime($this->selected_date));
        $this->last_date = date('Y-m-t', strtotime($this->selected_date));


        $this->sapQuery($this->first_date, $this->last_date);

    }

    /**
     * sapQuery - Fetches data from SAP HANA database for report generation.
     *
     * This function connects to a SAP HANA database using ODBC to execute multiple queries
     * and retrieve data required for the commission report.
     *
     * It first initializes several arrays to store the results.
     * It checks if the ODBC extension is loaded, then constructs the connection string
     * using environment variables. After successfully connecting, it executes three separate
     * SQL queries:
     * 1. `$loss_profit_sql`: Calculates the profit and loss for a specific cost center within a given date range.
     * 2. `$sql`: Calculates the outstanding balance, gross profit, and inventory values for a specific branch.
     * 3. `$sql2`: Gathers detailed sales, gross profit, and aging balance data for sales employees within a specific branch and date range.
     *
     * The results from these queries are stored in the `sap_results`, `sap_results2`, and `profitAndLoss` properties.
     * It also calculates the total gross profit from the second query's results. Finally, the database connection is closed.
     * The function includes error handling for failed connections and query executions.
     *
     * @param string $start_date The start date for the query range.
     * @param string $end_date The end date for the query range.
     */
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
    SELECT "BranchCode", SUM("Balance Due") as "Branch Balance"  FROM (
SELECT "BusinessPartnerCode", "BusinessPartnerName", OS."SlpCode", OS."Memo" as "OldSlpCode", OS."SlpName", IFNULL("0-30",0) as "0-30", IFNULL("31-60",0) as "31-60", IFNULL("61-90",0) as "61-90", IFNULL("91-120",0) as "91-120", IFNULL("121+",0) "121+", (IFNULL("0-30",0)+IFNULL("31-60",0)+IFNULL("61-90",0)+IFNULL("91-120",0)+IFNULL("121+",0)) as "Balance Due", "OldestInvoice",
CASE
WHEN "BusinessPartnerCode" LIKE \'01%\' THEN \'3\'
WHEN "BusinessPartnerCode" LIKE \'02%\' THEN \'4\'
WHEN "BusinessPartnerCode" LIKE \'03%\' THEN \'5\'
WHEN "BusinessPartnerCode" LIKE \'04%\' THEN \'6\'
WHEN "BusinessPartnerCode" LIKE \'05%\' THEN \'7\'
WHEN "BusinessPartnerCode" LIKE \'06%\' THEN \'8\'
WHEN "BusinessPartnerCode" LIKE \'07%\' THEN \'9\'
WHEN "BusinessPartnerCode" LIKE \'08%\' THEN \'10\'
WHEN "BusinessPartnerCode" LIKE \'09%\' THEN \'11\'
WHEN "BusinessPartnerCode" LIKE \'10%\' THEN \'12\'
WHEN "BusinessPartnerCode" LIKE \'11%\' THEN \'13\'
WHEN "BusinessPartnerCode" LIKE \'12%\' THEN \'14\'
ELSE \'1\'
END as "BranchCode" FROM (

SELECT "BusinessPartnerCode", "BusinessPartnerName", MIN(CASE WHEN "DocumentTypeCode" = 13 THEN "PostingDate" END) as "OldestInvoice", SUM(CASE WHEN "days" >=0 AND "days" <= 30 THEN "AgingBalanceDueLC" END) as "0-30", SUM(CASE WHEN "days" >=31 AND "days" <= 60 THEN "AgingBalanceDueLC" END) as "31-60", SUM(CASE WHEN "days" >=61 AND "days" <= 90 THEN "AgingBalanceDueLC" END) as "61-90", SUM(CASE WHEN "days" >=91 AND "days" <= 120 THEN "AgingBalanceDueLC" END) as "91-120", SUM(CASE WHEN "days" >=121 OR "days" < 0 THEN "AgingBalanceDueLC" END) as "121+" FROM (

select DAYS_BETWEEN( "PostingDate", \''.$end_date.'\') as "days", * from "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery" (\'PLACEHOLDER\' = (\'$$P_AgingDate$$\', \''.$end_date.'\'))

)

GROUP BY "BusinessPartnerCode", "BusinessPartnerName"
) AG


LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD OC ON AG."BusinessPartnerCode" = OC."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP OS ON OC."SlpCode" = OS."SlpCode"
--WHERE OC."validFor" = \'Y\'

ORDER BY "BusinessPartnerCode"

)
WHERE "BranchCode" = '. $this->area_id .'

GROUP BY "BranchCode"

) outstanding_tbl

 LEFT JOIN (
 SELECT "BranchName", "BranchCode", "BranchRegistrationNumber", SUM("GrossProfitLC") AS "GrossProfitLC" FROM (
SELECT "BranchName", "BranchCode", "BranchRegistrationNumber", "DocumentDate", "GrossProfitLC"
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "WarehouseBranchCode" = '.$this->area_id.'
AND "DocumentDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\')
GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber"
 ) gross_tbl ON gross_tbl."BranchCode" = outstanding_tbl."BranchCode"
 LEFT JOIN (

SELECT T1."BPLId", T0."Warehouse", Sum(T0."TransValue") "TransVal",
Sum(T0."CogsVal") "COGS" from AL_YASEEN_AGRI_PLIVE.oinm T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OBPL T1 ON T0."Warehouse" = T1."DflWhs"
WHERE T0."DocDate" <= \''.$end_date.'\'
AND T1."BPLId" = '.$this->area_id.'
Group By T1."BPLId", T0."Warehouse"
 ) stock_tbl ON stock_tbl."BPLId" = outstanding_tbl."BranchCode"';


            $sql2 = 'SELECT * FROM (
    SELECT
        "BranchCode" AS "BPLId",
        CR."SlpCode" AS "SalesEmployeeCode",
        OS."SlpName" AS "SalesEmployeeName",
        SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
        SUM("GrossProfitLC") AS "GrossProfitLC"
    FROM (
        SELECT * FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
        WHERE "BusinessPartnerCode" != 0000001
    ) SA
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD CR ON SA."BusinessPartnerCode" = CR."CardCode"
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP OS ON CR."SlpCode" = OS."SlpCode"
    WHERE SA."DocumentDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
        AND SA."DocumentTypeCode" NOT IN (\'15\', \'17\')
        AND (
            SELECT TABL0."DocNum"
            FROM AL_YASEEN_AGRI_PLIVE.ODPI TABL0
            INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TABL1 ON TABL0."DocEntry" = TABL1."DocEntry"
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TABL2
                ON TABL2."BaseEntry" = TABL1."DocEntry"
                AND TABL2."BaseLine" = TABL1."LineNum"
                AND TABL2."BaseType" = 203
            LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TABL3
                ON TABL2."DocEntry" = TABL3."DocEntry"
            WHERE TABL3."DocNum" = SA."DocumentNumber"
                AND TABL2."BaseType" = 203
            GROUP BY TABL0."DocNum"
        ) IS NULL
    GROUP BY
        "BranchCode",
        CR."SlpCode",
        OS."SlpName"
) tbl1

FULL OUTER JOIN (

SELECT "SlpCode", "OldSlpCode", "SlpName", "BranchCode", SUM("0-30"+"31-60"+"61-90"+"91-120"+"121-210"+"211+") as "Balance", SUM("211+") as "Balance Due", MIN(CASE WHEN "0-30"+"31-60"+"61-90"+"91-120"+"121-210"+"211+" != 0 THEN "OldestInvoice" END) as "OldestInvoice" FROM (

SELECT "BusinessPartnerCode", "BusinessPartnerName", OS."SlpCode", OS."Memo" as "OldSlpCode", OS."SlpName", IFNULL("0-30",0) as "0-30", IFNULL("31-60",0) as "31-60", IFNULL("61-90",0) as "61-90", IFNULL("91-120",0) as "91-120", IFNULL("121-210",0) as "121-210", IFNULL("211+",0) "211+", (IFNULL("0-30",0)+IFNULL("31-60",0)+IFNULL("61-90",0)+IFNULL("91-120",0)+IFNULL("121-210",0)+IFNULL("211+",0)) as "Balance Due", "OldestInvoice",
CASE
	WHEN "BusinessPartnerCode" LIKE \'01%\' THEN \'0101\'
	WHEN "BusinessPartnerCode" LIKE \'02%\' THEN \'0102\'
	WHEN "BusinessPartnerCode" LIKE \'03%\' THEN \'0103\'
	WHEN "BusinessPartnerCode" LIKE \'04%\' THEN \'0104\'
	WHEN "BusinessPartnerCode" LIKE \'05%\' THEN \'0105\'
	WHEN "BusinessPartnerCode" LIKE \'06%\' THEN \'0106\'
	WHEN "BusinessPartnerCode" LIKE \'07%\' THEN \'0107\'
	WHEN "BusinessPartnerCode" LIKE \'08%\' THEN \'0108\'
	WHEN "BusinessPartnerCode" LIKE \'09%\' THEN \'0109\'
	WHEN "BusinessPartnerCode" LIKE \'10%\' THEN \'0110\'
	WHEN "BusinessPartnerCode" LIKE \'11%\' THEN \'0111\'
	WHEN "BusinessPartnerCode" LIKE \'12%\' THEN \'0112\'
	ELSE \'0001\'
END as "BranchCode" FROM (

SELECT "BusinessPartnerCode", "BusinessPartnerName", MIN(CASE WHEN "DocumentTypeCode" = 13 THEN "PostingDate" END) as "OldestInvoice", SUM(CASE WHEN "days" >=0 AND "days" <= 30 THEN "AgingBalanceDueLC" END) as "0-30", SUM(CASE WHEN "days" >=31 AND "days" <= 60 THEN "AgingBalanceDueLC" END) as "31-60", SUM(CASE WHEN "days" >=61 AND "days" <= 90 THEN "AgingBalanceDueLC" END) as "61-90", SUM(CASE WHEN "days" >=91 AND "days" <= 120 THEN "AgingBalanceDueLC" END) as "91-120", SUM(CASE WHEN "days" >=121 AND "days" <= 210 THEN "AgingBalanceDueLC" END) as "121-210", SUM(CASE WHEN "days" >=211 OR "days" < 0 THEN "AgingBalanceDueLC" END) as "211+" FROM (

select DAYS_BETWEEN( "PostingDate", \''.$end_date.'\') as "days", * from "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery" (\'PLACEHOLDER\' = (\'$$P_AgingDate$$\', \''.$end_date.'\'))

)

GROUP BY "BusinessPartnerCode", "BusinessPartnerName"
) AG


LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD OC ON AG."BusinessPartnerCode" = OC."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP OS ON OC."SlpCode" = OS."SlpCode"
--WHERE OC."validFor" = \'Y\'

ORDER BY "BusinessPartnerCode"

)

GROUP BY "SlpCode", "OldSlpCode", "SlpName", "BranchCode"

) tbl2 ON tbl2."SlpCode" = tbl1."SalesEmployeeCode"

WHERE "BPLId" = '.$this->area_id.'
AND "BPLId" IS NOT NULL';

            $result_profit = odbc_exec($conn, $loss_profit_sql);
            if (!$result_profit)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result_profit)) {
                    $this->profitAndLoss = $row["ProfitAndLoss"];
                }

            }


            $result = odbc_exec($conn, $sql);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
                    $this->branch_balance = $row["Branch Balance"];
                }


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

//                foreach ($this->slp_code as $slp) {
//                    $ag = $this->getCustomers($slp, $end_date);
//                    array_push($this->slp_aging, [$slp => ['balance' => $ag[0], 'balance due' => $ag[1], 'oldest_date' => $ag[2], 'cust_code' => $ag[3]]]);
//                }
//
//                $this->branch_balance = 0;
//
////                dd($this->slp_aging);
//
//                $flattenedArray = [];
//                foreach ($this->slp_aging as $item) {
//                    foreach ($item as $key => $value) {
//                        $flattenedArray[$key] = $value;
////                        $this->branch_balance += $value["balance"];
//                    }
//                }
//
//
//
//
//                $this->slp_aging = $flattenedArray;
////                dd($this->slp_aging);
//                $this->branch_balance = collect($this->slp_aging)->sum('balance');

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
}
