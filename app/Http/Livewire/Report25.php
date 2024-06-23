<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report25 extends Component
{
    public $customer_list = [];
    public $customer_id;

    public $scribes_results = [];
    public $sap_trans_results = [];
    public $sap_aging_results = [];
    public $sap_sales_results = [];

    public $start_date;
    public $end_date;
    public $show_msg = false;

    protected $listeners = ['create-report' => 'create_report'];

    public function render()
    {
        $this->getCustomers();

        return view('livewire.report25')
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
        $this->sap_trans_results = [];
        $this->sap_aging_results = [];
        $this->sap_sales_results = [];

        $this->generateReport();
    }

    public function generateReport() {

        $this->show_msg = false;
//        $this->scribesQuery($this->start_date, $this->end_date);


        if (is_null($this->start_date) == false && is_null($this->end_date) == false) {

            if ($this->start_date >= '2011-07-01' && $this->end_date <= '2023-12-31') {
                $this->scribesQuery($this->start_date, $this->end_date);
            }
            elseif ($this->start_date > '2023-12-31' && $this->end_date > '2023-12-31') {
                $this->sapQuery($this->start_date, $this->end_date);
            }
            elseif ($this->start_date >= '2011-07-01' && $this->end_date > '2023-12-31') {
                $this->scribesQuery($this->start_date, '2023-12-31');
                $this->sapQuery('2024-01-01', $this->end_date);

                $this->mergeQuery();
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

        $scribesStmt = "
DECLARE @customer_id INT;
SET @customer_id = (SELECT NodeNo FROM AccMast where Code = '".$this->customer_id."');
select * from (
SELECT
	(select Code from AccMast where NodeNo = AccountDR) as customer_code,
	(select CreditLimit from AccMast where NodeNo = AccountDR) as credit_limit,
	count(case when SUBSTRING(VoucherNo, 1, 3) = '210' then VoucherNo end) as sales_count,
	sum(case when SUBSTRING(VoucherNo, 1, 3) = '210' then Net_without_VAT end) as sales_sum,
	count(case when SUBSTRING(VoucherNo, 1, 3) in ('211', '212') then VoucherNo end) as reverse_count,
	sum(case when SUBSTRING(VoucherNo, 1, 3) in ('211', '212') then Net_without_VAT end) as reverse_sum,
	count(case when SUBSTRING(VoucherNo, 1, 3) = '030' then VoucherNo end) as receipt_count,
	sum(case when SUBSTRING(VoucherNo, 1, 3) = '030' then Net end) as receipt_sum,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."'), 0) and VoucherDate <=  '".$end_date." 23:59:25' and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month1,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-1, 0) and VoucherDate <=  CONCAT(EOMONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-1, 0)), ' 23:59:25') and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month2,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-2, 0) and VoucherDate <=  CONCAT(EOMONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-2, 0)), ' 23:59:25') and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month3,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-3, 0) and VoucherDate <=  CONCAT(EOMONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-3, 0)), ' 23:59:25') and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month4,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-4, 0) and VoucherDate <=  CONCAT(EOMONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-4, 0)), ' 23:59:25') and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month5,
	sum(case when VoucherDate >=  DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-5, 0) and VoucherDate <=  CONCAT(EOMONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, '". $end_date ."')-5, 0)), ' 23:59:25') and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month6
	--sum(case when VoucherDate >=  '2011-01-01' and VoucherDate <= '2024-01-31' and SUBSTRING(VoucherNo, 1, 3) in ('210')  then Net + paid end) as month6

FROM (
select 0 AS SNo ,'OPB-' as VoucherNo ,'01-01-1900' as VoucherDate , @customer_id as AccountDR , Sum(AmountDr*Exchangerate) as NetDr , Sum(AmountCr*Exchangerate) as NetCr  ,Sum(AmountDr*Exchangerate - AmountCr*Exchangerate) as Net, '' as paid, Sum(AmountDr*Exchangerate - AmountCr*Exchangerate)/1.15 as Net_without_VAT   from  Purchasedata ,AccMast  where donotupdateaccounts=0 and accountDr = AccMast.NodeNo And accountdr =  @customer_id And  (VoucherDate <  '".$start_date."' )
union all
select ROW_NUMBER() OVER (ORDER BY VoucherDate) AS SNo ,VoucherNo ,VoucherDate , AccountDR , Sum(AmountDr*Exchangerate) as NetDr , Sum(AmountCr*Exchangerate) as NetCr ,Sum(AmountDr*Exchangerate - AmountCr*Exchangerate) as Net, isnull((select sum(b2.total) from billwise b2 where b2.Refrence=PurchaseData.VoucherNo and b2.CustomerNo=@customer_id and b2.VoucherDate<='".$end_date." 23:59:25'), 0.00) as paid, Sum(AmountDr*Exchangerate - AmountCr*Exchangerate)/1.15 as Net_without_VAT  from  Purchasedata ,AccMast  where donotupdateaccounts=0 and accountDr = AccMast.NodeNo   And accountdr = @customer_id And (VoucherDate >=  '".$start_date."' and VoucherDate <=  '".$end_date." 23:59:25')   Group By VoucherNo , Voucherdate,AccountDr
) AS tbl1
group by AccountDR) as tbl1
full outer join (
select
	(select Code from AccMast where NodeNo = @customer_id) as customer_code,
	isnull(max(case when Sply = '0' and Type = 'SIV' then NoSply end), 0) -  isnull(max(case when Sply = '0' and Type = 'PIV' then NoSply end),0) as sp0_count,
	isnull(sum(case when Sply = '0' and (Type = 'SIV') then Value end), 0) - isnull(sum(case when Sply = '0' and (Type = 'PIV') then Value end), 0) as sp0_sales,
	isnull(sum(case when Sply = '0' and (Type = 'SIV') then Cost end), 0) - isnull(sum(case when Sply = '0' and (Type = 'PIV') then Cost end), 0) as sp0_cost,
	isnull(max(case when Sply = '1' and Type = 'SIV' then NoSply end), 0) -  isnull(max(case when Sply = '1' and Type = 'PIV' then NoSply end),0) as sp1_count,
	isnull(sum(case when Sply = '1' and (Type = 'SIV') then Value end), 0) - isnull(sum(case when Sply = '1' and (Type = 'PIV') then Value end), 0) as sp1_sales,
	isnull(sum(case when Sply = '1' and (Type = 'SIV') then Cost end), 0) - isnull(sum(case when Sply = '1' and (Type = 'PIV') then Cost end), 0) as sp1_cost,
	isnull(max(case when Sply = '2' and Type = 'SIV' then NoSply end), 0) -  isnull(max(case when Sply = '2' and Type = 'PIV' then NoSply end),0) as sp2_count,
	isnull(sum(case when Sply = '2' and (Type = 'SIV') then Value end), 0) - isnull(sum(case when Sply = '2' and (Type = 'PIV') then Value end), 0) as sp2_sales,
	isnull(sum(case when Sply = '2' and (Type = 'SIV') then Cost end), 0) - isnull(sum(case when Sply = '2' and (Type = 'PIV') then Cost end), 0) as sp2_cost
from (
 Select SInvoiceNo as VoucherNo, SIDate as VoucherDate ,ProductNo ,Sum(ActualQty) as Qty , Rate , Sum(Value*Exchangerate + ExtraFieldsTotal) as Value ,    Sum(TotalCost) as Cost , SpecialityCode as Sply,  case when (SpecialityCode = '' OR SpecialityCode =  '0') then     (Select count(distinct ProductNo) From
Sinvoice,ProductMast M where   ProductNo = M.NodeNo And partyno = @customer_id and (SIDate >= '".$start_date."' And SIDate <= '".$end_date." 23:59:25') and        (M.SpecialityCode = '' OR M.SpecialityCode = '0') And ActualVoucherPrefix = 'SIV-' )    else (Select count(distinct ProductNo) From Sinvoice ,ProductMast T where
ProductNo = T.NodeNo And partyno = @customer_id and (SIDate >= '".$start_date."' And SIDate <= '".$end_date." 23:59:25') and        (T.SpecialityCode <> '' And T.SpecialityCode <> '0') And ActualVoucherPrefix = 'SIV-'  And T.SpecialityCode =ProductMast.SpecialityCode) End   as NoSply   ,'SIV' as Type , ActualVoucherPrefix From
SInvoice,ProductMast Where ProductNo = NodeNo  And PartyNo = @customer_id  And (SIDate >= '".$start_date."' And SIDate <= '".$end_date." 23:59:25')     And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  And ActualVoucherPrefix = 'SIV-' Group By SInvoiceNo,ProductNo, SIDate,Rate , ActualVoucherPrefix ,SpecialityCode,NodeNo    union all    Select PinvoiceNo as VoucherNo, PIDate as VoucherDate ,ProductNo ,Sum(ActualQty) as Qty , Rate , Sum(Value*Exchangerate + ExtraFieldsTotal) as Value ,    Sum(TotalCost) as Cost , SpecialityCode as Sply,case when (SpecialityCode = '' OR SpecialityCode =  '0') then     (Select count(distinct ProductNo) From PInvoice,ProductMast M where   ProductNo = M.NodeNo And partyno = @customer_id and (PIDate >= '".$start_date."' And PIDate <= '".$end_date." 23:59:25') and        (M.SpecialityCode = '' OR M.SpecialityCode = '0') And ActualVoucherPrefix = 'SRT-' )    else (Select count(distinct ProductNo) From PInvoice ,ProductMast T where ProductNo = T.NodeNo And partyno = @customer_id and (PIDate >= '".$start_date."' And PIDate <= '".$end_date." 23:59:25') and        (T.SpecialityCode <> '' And T.SpecialityCode <> '0') And ActualVoucherPrefix = 'SRT-'  And T.SpecialityCode =ProductMast.SpecialityCode) End   as NoSply  ,'PIV' as Type , ActualVoucherPrefix From Pinvoice,ProductMast   Where ProductNo = NodeNo  And PartyNo = @customer_id  And (PIDate >= '".$start_date."' And PIDate <= '".$end_date." 23:59:25')   And ActualVoucherPrefix = 'SRT-'   And (Select Name From DeptMast Where NodeNo = Department) Not In (Select DeptName From DeptRights Where UserName='su')  Group By PinvoiceNo,ProductNo, PIDate,Rate , ActualVoucherPrefix ,SpecialityCode,NodeNo    --order by SPLY ,Type , VoucherDate , ProductNo
) as tbl2
) as tbl2
on tbl1.customer_code = tbl2.customer_code";

//        dd($scribesStmt);

        $query = DB::connection('sqlsrv')->select($scribesStmt);
        $this->scribes_results = $query;
//        dd($this->scribes_results);

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

            // trans
            $sql = 'SELECT \'A/R Invoice\' AS "TransType",
"CardCode",
COUNT(DISTINCT T0."DocEntry") AS "No",
SUM(T1."LineTotal"*(1-(IFNULL(T0."DiscPrcnt",0)/100))) AS "NetSales"
FROM AL_YASEEN_AGRI_PLIVE.OINV T0
JOIN AL_YASEEN_AGRI_PLIVE.INV1 T1 ON T0."DocEntry"=T1."DocEntry" AND T0."CANCELED"=\'N\'
WHERE T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
AND "CardCode" = \''.$this->customer_id.'\'
GROUP BY "CardCode"

UNION ALL

SELECT \'A/R Invoice (Cancellation)\' AS "TransType",
"CardCode",
COUNT(DISTINCT T0."DocEntry") AS "No",
SUM(T1."LineTotal"*(1-(IFNULL(T0."DiscPrcnt",0)/100))) AS "NetSales"
FROM AL_YASEEN_AGRI_PLIVE.OINV T0
JOIN AL_YASEEN_AGRI_PLIVE.INV1 T1 ON T0."DocEntry"=T1."DocEntry" AND T0."CANCELED"=\'C\'
WHERE T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
AND "CardCode" = \''.$this->customer_id.'\'
GROUP BY "CardCode"

UNION ALL

SELECT \'A/R Credit Note\' AS "TransType",
"CardCode",
COUNT(DISTINCT T0."DocEntry") AS "No",
SUM(T1."LineTotal"*(1-(IFNULL(T0."DiscPrcnt",0)/100))) AS "NetSales"
FROM AL_YASEEN_AGRI_PLIVE.ORIN T0
JOIN AL_YASEEN_AGRI_PLIVE.RIN1 T1 ON T0."DocEntry"=T1."DocEntry" AND T0."CANCELED"=\'N\'
WHERE T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
AND "CardCode" = \''.$this->customer_id.'\'
GROUP BY "CardCode"

UNION ALL

SELECT \'A/R Credit Note (Cancellation)\' AS "TransType",
"CardCode",
COUNT(DISTINCT T0."DocEntry") AS "No",
SUM(T1."LineTotal"*(1-(IFNULL(T0."DiscPrcnt",0)/100))) AS "NetSales"
FROM AL_YASEEN_AGRI_PLIVE.ORIN T0
JOIN AL_YASEEN_AGRI_PLIVE.RIN1 T1 ON T0."DocEntry"=T1."DocEntry" AND T0."CANCELED"=\'C\'
WHERE T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
AND "CardCode" = \''.$this->customer_id.'\'
GROUP BY "CardCode"';

            // aging statments
            $sql_aging = '
SELECT
F0."CardCode",
F0."CardName",
F0."CreditLine",
F0."Balance",
F0."FutureLC",
F0."0-A1_LC",
F0."A1-A2_LC",
F0."A2-A3_LC",
F0."A3-A4_LC",
F0."A4-A5_LC",
F0."A5+_LC",
SUM(F0."0-A1_LC"+
F0."A1-A2_LC"+
F0."A2-A3_LC"+
F0."A3-A4_LC"+
F0."A4-A5_LC"+
F0."A5+_LC") OVER (PARTITION BY F0."CardCode") AS "TotalBalance",
IFNULL(F1."PDC",0) AS "PDC"

FROM (

SELECT
T0."CardCode",
T0."CardName",
T0."CreditLine",
T0."Balance",
SUM(CASE WHEN T0."RefDate" > \''.$end_date.'\' THEN T0."BalanceDue" ELSE 0 END) as "FutureLC",
SUM(CASE WHEN T0."RefDate" <=  \''.$end_date.'\' AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-(30+1))	  THEN T0."BalanceDue" ELSE 0 END) AS "0-A1_LC",
SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-30-1)	AND	 T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-60-1)	  THEN T0."BalanceDue" ELSE 0 END) AS "A1-A2_LC",
SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-60-1)	AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-90-1)	  THEN T0."BalanceDue" ELSE 0 END) AS "A2-A3_LC",
SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-90-1)	AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-120-1)	  THEN T0."BalanceDue" ELSE 0 END) AS "A3-A4_LC",
SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-120-1) AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-150-1)		  THEN T0."BalanceDue" ELSE 0 END) AS "A4-A5_LC",
SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-150-1)	THEN T0."BalanceDue" ELSE 0 END) AS "A5+_LC"

FROM (

SELECT
T2."CardCode",
T2."CardName",
T2."CreditLine",
T2."Balance",
T0."RefDate",
CASE WHEN T0."DebCred" = \'D\' THEN (T0."Debit"-T0."Credit")-ifnull(T3."ReconSum",0)
	 WHEN T0."DebCred" = \'C\' THEN -((T0."Credit"-T0."Debit")-ifnull(T3."ReconSum",0)) ELSE 0 END as "BalanceDue",

CASE WHEN T0."DebCred" = \'D\' THEN (T0."SYSDeb"-T0."SYSCred")-ifnull(T3."ReconSumSC",0)
	 WHEN T0."DebCred" = \'C\' THEN -((T0."SYSCred"-T0."SYSDeb")-ifnull(T3."ReconSumSC",0)) ELSE 0 END as "BalanceDueSC",

CASE WHEN T0."DebCred" = \'D\' THEN (T0."FCDebit"-T0."FCCredit")-ifnull(T3."ReconSumFC",0)
	 WHEN T0."DebCred" = \'C\' THEN -((T0."FCCredit"-T0."FCDebit")-ifnull(T3."ReconSumFC",0)) ELSE 0  END as "BalanceDueFC"

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode"
LEFT JOIN (SELECT SUM("ReconSum") AS "ReconSum",SUM("ReconSumSC") AS "ReconSumSC",SUM("ReconSumFC") AS "ReconSumFC","TransRowId","TransId" FROM AL_YASEEN_AGRI_PLIVE.ITR1 T0 JOIN AL_YASEEN_AGRI_PLIVE.OITR T1 ON T0."ReconNum" = T1."ReconNum" AND T1."ReconDate" <= \''.$end_date.'\' GROUP BY "TransRowId","TransId") T3 ON T0."TransId" = T3."TransId"
AND T0."Line_ID" = T3."TransRowId"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRG T4 ON T2."GroupCode" = T4."GroupCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCTG T5 ON T2."GroupNum" = T5."GroupNum"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRY T6 ON T2."Country"=T6."Code"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OACT T8 ON T0."Account"=T8."AcctCode"

WHERE (T0."RefDate" <= \''.$end_date.'\')
AND T2."CardCode" = \''.$this->customer_id.'\'
) T0

GROUP BY T0."CardCode",T0."CardName",T0."CreditLine",T0."Balance"

) F0

LEFT JOIN(
SELECT T0."CardCode",
SUM(T0."CheckSum") AS "PDC"
FROM AL_YASEEN_AGRI_PLIVE.OPDF T0
JOIN AL_YASEEN_AGRI_PLIVE.PDF1 T1 ON T0."DocEntry" = T1."DocNum"
WHERE T0."ObjType" = \'24\' AND
(T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\')

GROUP BY T0."CardCode"
)F1 ON F0."CardCode"=F1."CardCode"
WHERE F0."CardCode" = \''.$this->customer_id.'\'
ORDER BY F0."CardCode"';

            // sales
            $sql_sales = 'SELECT "CardCode","SPL",
SUM("No") AS "No",
SUM("NetSales") AS "NetSales",
SUM("GrssProfit") AS "GrssProfit"

FROM

(SELECT T3."CardCode",COALESCE(T5."Property1",T5."Property2",T5."Property3") AS "SPL",
COUNT(DISTINCT T1."DocEntry") OVER (PARTITION BY T1."DocEntry",T3."CardCode") AS "No",
T0."LineTotal"*(1-(IFNULL(T1."DiscPrcnt",0)/100)) AS "NetSales",
T0."GrssProfit" AS "GrssProfit"

FROM AL_YASEEN_AGRI_PLIVE.INV1 T0
JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry"=T1."DocEntry" AND T1."CANCELED"=\'N\'
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode"=T2."ItemCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."CardCode"=T3."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T5 ON T2."ItemCode"=T5."ItemCode"

WHERE T1."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'

UNION ALL

SELECT T3."CardCode",COALESCE(T5."Property1",T5."Property2",T5."Property3") AS "SPL",
-COUNT(DISTINCT T1."DocEntry") OVER (PARTITION BY T1."DocEntry",T3."CardCode") AS "No",
-T0."LineTotal"*(1-(IFNULL(T1."DiscPrcnt",0)/100)) AS "NetSales",
-T0."GrssProfit" AS "GrssProfit"

FROM AL_YASEEN_AGRI_PLIVE.RIN1 T0
JOIN AL_YASEEN_AGRI_PLIVE.ORIN T1 ON T0."DocEntry"=T1."DocEntry" AND T1."CANCELED"=\'N\'
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode"=T2."ItemCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T3 ON T1."CardCode"=T3."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T5 ON T2."ItemCode"=T5."ItemCode"

WHERE T1."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'
)
WHERE "CardCode" = \''.$this->customer_id.'\'
GROUP BY "CardCode","SPL"';


            $result = odbc_exec($conn, $sql);
            $result2 = odbc_exec($conn, $sql_aging);
            $result3 = odbc_exec($conn, $sql_sales);

            if (!$result || !$result2 || !$result3)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_trans_results, $row);
                }

                while ($row = odbc_fetch_array($result2)) {
                    array_push($this->sap_aging_results, $row);
                }

                while ($row = odbc_fetch_array($result3)) {
                    array_push($this->sap_sales_results, $row);
                }
            }

//            dd($this->sap_aging_results);
            odbc_close($conn);
        }
    }

    public function mergeQuery() {

        $this->sap_trans_results = collect($this->sap_trans_results);
        $this->sap_aging_results = collect($this->sap_aging_results);
        $this->sap_sales_results = collect($this->sap_sales_results);

//        dd($this->sap_trans_results);

//        dd($this->sap_aging_results->where('CardCode', '0400166')[0]['Balance']);
//
        $this->scribes_results[0]->sales_count = floatval($this->scribes_results[0]->sales_count) + (floatval($this->sap_trans_results->where('TransType', 'A/R Invoice') ? floatval($this->sap_trans_results->where('TransType', 'A/R Invoice')[0]['No']): 0));
        $this->scribes_results[0]->sales_sum = floatval($this->scribes_results[0]->sales_sum) + (floatval($this->sap_trans_results->where('TransType', 'A/R Invoice') ? floatval($this->sap_trans_results->where('TransType', 'A/R Invoice')[0]['NetSales']): 0));

        $this->scribes_results[0]->reverse_count = floatval($this->scribes_results[0]->reverse_count) + ($this->sap_trans_results->where('TransType', 'A/R Invoice (Cancellation)')->count() > 0 ? floatval($this->sap_trans_results->where('TransType', 'A/R Invoice (Cancellation)')[0]['No']): 0);
/*        $this->scribes_results[0]->reverse_sum = floatval($this->scribes_results[0]->reverse_sum) + (floatval($this->sap_trans_results->where('TransType', 'A/R Invoice (Cancellation)') ? floatval($this->sap_trans_results->where('TransType', 'A/R Invoice (Cancellation)')[0]['NetSales']): 0));

        $this->scribes_results[0]->receipt_count = floatval($this->scribes_results[0]->receipt_count) + (floatval($this->sap_trans_results->where('TransType', 'A/R Credit Note') ? floatval($this->sap_trans_results->where('TransType', 'A/R Credit Note')[0]['No']): 0));
        $this->scribes_results[0]->receipt_sum = floatval($this->scribes_results[0]->receipt_sum) + (floatval($this->sap_trans_results->where('TransType', 'A/R Credit Note') ? floatval($this->sap_trans_results->where('TransType', 'A/R Credit Note')[0]['NetSales']): 0));

        $this->scribes_results[0]->credit_limit = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['CreditLine']): 0);
        $this->scribes_results[0]->month1 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['0-A1_LC']): 0);
        $this->scribes_results[0]->month2 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['A1-A2_LC']): 0);
        $this->scribes_results[0]->month3 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['A2-A3_LC']): 0);
        $this->scribes_results[0]->month4 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['A3-A4_LC']): 0);
        $this->scribes_results[0]->month5 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['A4-A5_LC']): 0);
        $this->scribes_results[0]->month6 = ($this->sap_aging_results ? floatval($this->sap_aging_results[0]['A5+_LC']): 0);

        $this->scribes_results[0]->sp0_count = floatval($this->scribes_results[0]->sp0_count) + (floatval($this->sap_sales_results->where('SPL', 'Speciality0') ? floatval($this->sap_sales_results->where('SPL', 'Speciality0')[0]['No']): 0));
        $this->scribes_results[0]->sp0_sales = floatval($this->scribes_results[0]->sp0_sales) + (floatval($this->sap_sales_results->where('SPL', 'Speciality0') ? floatval($this->sap_sales_results->where('SPL', 'Speciality0')[0]['NetSales']): 0));
        $this->scribes_results[0]->sp0_cost = floatval($this->scribes_results[0]->sp0_cost) + (floatval($this->sap_sales_results->where('SPL', 'Speciality0') ? (floatval($this->sap_sales_results->where('SPL', 'Speciality0')[0]['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality1')[0]['GrssProfit'])): 0));

        $this->scribes_results[0]->sp1_count = floatval($this->scribes_results[0]->sp1_count) + (floatval($this->sap_sales_results->where('SPL', 'Speciality1') ? floatval($this->sap_sales_results->where('SPL', 'Speciality1')[0]['No']): 0));
        $this->scribes_results[0]->sp1_sales = floatval($this->scribes_results[0]->sp1_sales) + (floatval($this->sap_sales_results->where('SPL', 'Speciality1') ? floatval($this->sap_sales_results->where('SPL', 'Speciality1')[0]['NetSales']): 0));
        $this->scribes_results[0]->sp1_cost = floatval($this->scribes_results[0]->sp1_cost) + (floatval($this->sap_sales_results->where('SPL', 'Speciality1') ? (floatval($this->sap_sales_results->where('SPL', 'Speciality1')[0]['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality1')[0]['GrssProfit'])): 0));

        $this->scribes_results[0]->sp2_count = floatval($this->scribes_results[0]->sp2_count) + (floatval($this->sap_sales_results->where('SPL', 'Speciality2') ? floatval($this->sap_sales_results->where('SPL', 'Speciality2')[0]['No']): 0));
        $this->scribes_results[0]->sp2_sales = floatval($this->scribes_results[0]->sp2_sales) + (floatval($this->sap_sales_results->where('SPL', 'Speciality2') ? floatval($this->sap_sales_results->where('SPL', 'Speciality2')[0]['NetSales']): 0));
        $this->scribes_results[0]->sp2_cost = floatval($this->scribes_results[0]->sp2_cost) + (floatval($this->sap_sales_results->where('SPL', 'Speciality2') ? (floatval($this->sap_sales_results->where('SPL', 'Speciality2')[0]['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality2')[0]['GrssProfit'])): 0));
        */

//        dd($this->scribes_results[0]->month3);

    }
}
