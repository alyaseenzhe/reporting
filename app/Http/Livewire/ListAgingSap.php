<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class ListAgingSap extends Component
{
    public $area_id = -1;
    public $selected_date;
    public $last_date;
    public $aging_records = [];

    public function render()
    {
        return view('livewire.list-aging-sap')
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        set_time_limit(2000);
//        $this->validate();
        $this->emit('show-container');

        $this->last_date = date('Y-m-t', strtotime($this->selected_date));

        $this->aging_records = [];
        $this->getCustomersBalanceDue($this->last_date);

        $this->emit('show-data');

    }

    public function getCustomersBalanceDue($end_date) {

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

            $sql_customer = 'SELECT T0."CardCode", T0."CardName", T0."SlpCode" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0
WHERE T0."CardType" = \'C\'
AND T0."CardCode" LIKE \''.$this->area_id.'%\'';


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


            }

//            dd($this->customer);

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
//                        dd($row);
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

//            dd($this->customer_balance['1000015']);


            // for aging 120

            foreach (array_keys($this->customer_balance) as $cust_code) {
//                $cust_code = '1000015';

//                $x = collect($balance_due->where('CardCode', $cust_code)->first()["CumulativeBalance"]);
                $x = $this->customer_balance[$cust_code];
                $cum_balance = floatval($x);

                $customer_balance += $cum_balance;


                $sql_aging = '
SELECT tbl1.*, tbl2."SlpCode", tbl2."SlpName", tbl2."Memo" FROM (
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
            T3."SlpCode",
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
        ORDER BY T0."TransId" DESC
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
    "SlpCode",
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

) tbl1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP tbl2
ON tbl1."SlpCode" = tbl2."SlpCode"

WHERE "Document Type" != \'Incoming Payment\'
AND "Debit (LC)" > 0
ORDER BY "Posting Date" ASC, "Transaction Number" ASC
';

//                dd($sql_aging);

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
                        array_push($this->aging_records, $row);
//                        array_push($this->customer_code, $row["CardCode"]);
//                        $aging_balance += $row["Aging"];
                    }

                }

                // oldest invoice

//                $sql_oldest_invoice = 'SELECT "Posting Date" AS "Oldest_Date" FROM (
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
//            SUM(T1."Debit") OVER (ORDER BY T0."RefDate" DESC, T0."TransId" DESC) AS "Cumulative Debit",
//            CASE
//                WHEN T0."TransType" = 24 THEN (
//                    SELECT MAX(T22."DocNum")
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
//                    SELECT MAX(T22."DocNum")
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
//ORDER BY "Posting Date" ASC
//LIMIT 1';



//                $result_oldest = odbc_exec($conn, $sql_oldest_invoice);
//                if (!$result_oldest)
//                {
//                    echo "Error while sending SQL statement to the database server.\n";
//                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
//                }
//                else
//                {
////                    $ss = [];
//                    while ($row = odbc_fetch_array($result_oldest)) {
////                        dd($row);
////                        array_push($this->customer, $row);
////                        array_push($this->customer_code, $row["CardCode"]);
//
////                        array_push($ss, $row);
////                        dd(Carbon::parse($row["Oldest_Date"]) . '--' .$oldest_inv . '||' . Carbon::parse($row["Oldest_Date"])->lt($oldest_inv));
////                        $a = Carbon::parse($row["Oldest_Date"])->format('Y-m-d');
//
//                        $fmt = Carbon::parse($row["Oldest_Date"])->format('Y-m-d');
////                        dd($oldest_inv);
//
//                        $x = Carbon::createFromFormat('Y-m-d', $fmt);
////                        $y = Carbon::createFromFormat('Y-m-d', $oldest_inv);
//
////                        if(Carbon::parse($row["Oldest_Date"])->lt($oldest_inv)) {
//                        if($x->lt($oldest_inv)) {
//                            $oldest_inv = $x->format('Y-m-d');
//                            $c_code = $cust_code;
////                            $oldest_inv = $row["Oldest_Date"];
//                        }
//                    }
//
////                    dd($ss);
//
//                }

                // end of oldest invoice
            }


            odbc_close($conn);
        }

//        dd($this->aging_records);
//        return [$customer_balance, $aging_balance, $oldest_inv, $c_code];
    }
}
