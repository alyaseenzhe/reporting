<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfitLossStmtReport extends Component
{

    public $branch_id;
    public $start_date;
    public $end_date;
    public $sap_results = [];
    public $show_msg = false;

    public $acc_name = [
        '41' => '41 - إيرادات النشاط الرئيسي',
        '51' => '51 - تكلفة البضاعة المباعة',
        '61' => '61 - مصاريف تشغيل',
        '71' => '71 - ايرادات اخرى',
        '81' => '81 - زكاة وفوائد بنكية',
    ];

    protected $listeners = ['create-report' => 'create_report'];

    public function booted() {


        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('profit-loss-stmt-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.profit-loss-stmt-report')
            ->layout('layouts.dashboard');
    }

    public function create_report($branch_id, $start_date, $end_date) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->branch_id = $branch_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;

        $this->generateReport();
    }

    public function generateReport() {

        $this->show_msg = false;

        $this->sap_results = [];

        $this->sapQuery($this->branch_id, $this->start_date, $this->end_date);

        $this->show_msg = true;
        $this->emit('finished');

    }

    public function sapQuery($branch_id, $start_date, $end_date) {

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');

        $host = env('DB_HOST_FOURTH');

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

            $sql = 'SELECT * FROM (
SELECT "Account Code", "Account Name", SUM("Debit Amount") AS "DebitAmount", SUM("Credit Amount") AS "CreditAmount" FROM (

SELECT
OACT."AcctCode" AS "Account Code",
OACT."AcctName" AS "Account Name",
JDT1."RefDate" AS "Transaction Date",
OJDT."Memo" AS "Transaction Description",
JDT1."Debit" AS "Debit Amount",
JDT1."Credit" AS "Credit Amount",
JDT1."ProfitCode" AS "Cost Center"
FROM
AL_YASEEN_AGRI_PLIVE.JDT1
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OJDT ON JDT1."TransId" = OJDT."TransId"
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OACT ON JDT1."Account" = OACT."AcctCode"
WHERE
JDT1."RefDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'';
    if ($branch_id != 'all') {
        $sql .= 'AND JDT1."ProfitCode" = \''.$branch_id.'\'';
    }
$sql .= ' ORDER
BY JDT1."RefDate"
)

WHERE
"Account Code" LIKE \'4%\'
OR "Account Code" LIKE \'5%\'
OR "Account Code" LIKE \'6%\'
OR "Account Code" LIKE \'7%\'
OR "Account Code" LIKE \'8%\'

GROUP BY "Account Code", "Account Name"
ORDER BY "Account Code"

) TBL0

LEFT JOIN (
SELECT "Account Code2", (SELECT T0."AcctName" FROM AL_YASEEN_AGRI_PLIVE.OACT T0 WHERE T0."AcctCode" = "Account Code2") AS "AccName", (SUM("CreditAmount")-SUM("DebitAmount")) AS "Total" FROM (
SELECT SUBSTRING("Account Code", 1,2) AS "Account Code2", SUM("Debit Amount") AS "DebitAmount", SUM("Credit Amount") AS "CreditAmount" FROM (

SELECT
OACT."AcctCode" AS "Account Code",
OACT."AcctName" AS "Account Name",
JDT1."RefDate" AS "Transaction Date",
OJDT."Memo" AS "Transaction Description",
JDT1."Debit" AS "Debit Amount",
JDT1."Credit" AS "Credit Amount",
JDT1."ProfitCode" AS "Cost Center"
FROM
AL_YASEEN_AGRI_PLIVE.JDT1
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OJDT ON JDT1."TransId" = OJDT."TransId"
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OACT ON JDT1."Account" = OACT."AcctCode"
WHERE
JDT1."RefDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'';

    if ($branch_id != 'all') {
        $sql .= 'AND JDT1."ProfitCode" = \'' . $branch_id . '\'';
    }

$sql .= ' ORDER BY JDT1."RefDate"
)

WHERE
"Account Code" LIKE \'4%\'
OR "Account Code" LIKE \'5%\'
OR "Account Code" LIKE \'6%\'
OR "Account Code" LIKE \'7%\'
OR "Account Code" LIKE \'8%\'

GROUP BY "Account Code"
ORDER BY "Account Code")
GROUP BY "Account Code2"
ORDER BY "Account Code2"

) TBL1 ON SUBSTRING(TBL0."Account Code", 1,2) = TBL1."Account Code2"
ORDER BY 1;
';

//    dd($sql);


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
                }
            }
            odbc_close($conn);

//            dd($this->sap_results);
        }
    }

}
