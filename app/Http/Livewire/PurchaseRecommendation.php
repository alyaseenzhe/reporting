<?php

namespace App\Http\Livewire;

use App\Exports\PurchaseRecommendationExport;
use App\Models\AccMast;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
//use Maatwebsite\Excel\Excel;
use Excel;
//use Maatwebsite\Excel;

class PurchaseRecommendation extends Component
{
    public $results = [];
    public $sap_results = [];
    public $dist_days;
    public $item_type = "all_items";

    public $vendor_list = [];
    public $vendor_type = "vendor_all";
    public $show_results = false;
//    public $record_type = 'all';

    public $product_code = "";

    protected $messages = [
        'product_code.required' => "مطلوب",
        'vendor_type.required' => "مطلوب",
        'item_type.required' => "مطلوب",
    ];

    protected $listeners = ['create-report' => 'createReport', 'export-report' => 'exportReport'];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.purchase-recommendation', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {

//        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
//            ->where('ProductMast.PriceList', 1)
//            ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
//            ->get();


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
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $this->vendor_list = [];


            $vendor_stmt = 'SELECT DISTINCT V."CardCode", V."CardName"
            FROM AL_YASEEN_AGRI_PLIVE."OCRD" V
            JOIN AL_YASEEN_AGRI_PLIVE."OITM" I ON V."CardCode" = I."CardCode"
            WHERE V."CardType" = \'S\'';



            $result = odbc_exec($conn, $vendor_stmt);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                $this->vendor_list = [];
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->vendor_list, $row);
                }

            }
            odbc_close($conn);
        }
    }

    public function render()
    {
        $settings_record = Setting::first();
        $this->dist_days = $settings_record->dist_days;

//        $target_date = Carbon::today()->firstOfMonth()->addMonths($no_days);
//        $year = $target_date->format('Y');
//        $month = $target_date->format('n');
//
//        $no_days = ceil(91/30);
//        dd($no_days);
//
//        $start_date = Carbon::today()->firstOfMonth()->format('Y-m-d');
//        $end_date = Carbon::today()->addMonths($no_days-1)->endOfMonth()->format('Y-m-d');
////        dd($end_date);
//        $period = new CarbonPeriod($start_date, '1 month', $end_date);
////        dd($period);
//
//        $stmt = "";
//
//
////        foreach ($emp_codes as $key => $emp_code) {
////            if ($key === array_key_first($emp_codes)) {
////                $postponed_stmt .= "('".$emp_code."', ";
////            }
////            elseif ($key === array_key_last($emp_codes)) {
////                $postponed_stmt .= "'".$emp_code."')";
////            }
////            else {
////                $postponed_stmt .= "'".$emp_code."',";
////            }
////        }
////        dd($period->toArray());
//        $period = $period->toArray();
//
//        foreach($period as $key => $month) {
////            dd($month->format('Y-n'));
////            select SUM(target) as target from product_target_branch_totals where product_id = '". $record->Code ."' and month = '". $month ."' and year = '". $year."'"
//            if (count($period) > 1) {
//                if ($key === array_key_first($period)) {
//                    $stmt .= "((year ='".$month->format('Y')."' and month = '".$month->format('n')."') or ";
//                }
//                elseif ($key === array_key_last($period)) {
//                    $stmt .= "(year ='".$month->format('Y')."' and month = '".$month->format('n')."'))";
//                }
//                else {
//                    $stmt .= "(year ='".$month->format('Y')."' and month = '".$month->format('n')."') or ";
//                }
//            }
//            else {
//                $stmt .= "(year ='".$month->format('Y')."' and month = '".$month->format('n')."')";
//            }
////            "(year ='2024' and month = '1')"
//        }
//        dd($stmt);

//        $no_days = ceil(50/30);
//        $target_date = Carbon::today()->firstOfMonth()->addMonths($no_days);
//        $year = $target_date->format('Y');
//        $month = $target_date->format('n');

//        dd($year);
//        dd(Carbon::today()->firstOfMonth()->addMonths($no_days)->format('Y-n'));

        return view('livewire.purchase-recommendation')
//        return view('livewire.purchase-recommendation-table-export')
            ->layout('layouts.dashboard');
    }

    public function createReport($item_type, $vendor_type, $product_code) {

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
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {

            $this->sap_results = [];


            $today = Carbon::today()->format('m/d/Y');

            if ($item_type == 'item_vendor') {
                $stmt = '
                SELECT
	T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1" AS "OldItemCode",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
    T0."OnHand"
 FROM AL_YASEEN_AGRI_PLIVE.OITM T0
 INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"
 LEFT JOIN (
 SELECT DISTINCT

    T2."DocEntry",
    T2."DocNum",
    T2."DocDate",
	T2."ReqDate" as "DocDueDate",
    T2."CardCode",
    T2."CardName",
    T3."ItemCode" AS "ItemCode2",
    T4."U_UDF1" AS "OldItemCode",
    T3."Dscription",
	T3."ItemCode",
    T3."OpenQty" AS "OpenQoutation"
FROM

    AL_YASEEN_AGRI_PLIVE.OPQT T2

INNER JOIN

    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"

INNER JOIN

	AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"

INNER JOIN

	AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"

WHERE

    T2."DocStatus" = \'O\' -- Filters for open purchase quotation
 ) AS "tbl_Quotation" ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"
 WHERE T1."CardCode" = \''.$vendor_type.'\'
 AND T0."validFor" = \'Y\'
 GROUP BY
 T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    T0."OnHand"
 ORDER BY T1."CardCode"';



                $stmt= 'SELECT
T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1" AS "OldItemCode",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
    SUM("tbl_PurchaseRequest"."OpenPurchaseRequest") AS "OpenPurchaseRequest",
    T0."OnHand"
 FROM AL_YASEEN_AGRI_PLIVE.OITM T0
 INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"

 LEFT JOIN (
 SELECT DISTINCT
    T2."DocEntry",
    T2."DocNum",
    T2."DocDate",
T2."ReqDate" as "DocDueDate",
    T2."CardCode",
    T2."CardName",
    T3."ItemCode" AS "ItemCode2",
    T4."U_UDF1" AS "OldItemCode",
    T3."Dscription",
T3."ItemCode",
    T3."OpenQty" AS "OpenQoutation"
FROM
    AL_YASEEN_AGRI_PLIVE.OPQT T2
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"
INNER JOIN
AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"
WHERE
    T2."DocStatus" = \'O\'
 ) AS "tbl_Quotation" ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"

 LEFT JOIN (
 SELECT DISTINCT
    T2."DocEntry",
    T2."DocNum",
    T2."DocDate",
    T2."ReqDate" as "DocDueDate",
    T3."ItemCode" AS "ItemCode2",
    T3."OpenQty" AS "OpenPurchaseRequest"
FROM
    AL_YASEEN_AGRI_PLIVE.OPRQ T2
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.PRQ1 T3 ON T2."DocEntry" = T3."DocEntry"
WHERE
    T2."DocStatus" = \'O\'
 ) AS "tbl_PurchaseRequest" ON "tbl_PurchaseRequest"."ItemCode2" = T0."ItemCode"

 WHERE T1."CardCode" = \''.$vendor_type.'\'
 AND T0."validFor" = \'Y\'
 GROUP BY
 T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    T0."OnHand"
 ORDER BY T1."CardCode"';
            }
            else if ($item_type == 'item_code') {
//                $stmt = "SELECT * FROM (
//SELECT tbl2.NodeNo,
//	tbl2.Code,
//	tbl2.Arabic_Name,
//	tbl2.BaseUnits,
//	tbl2.VendorNo,
//	tbl2.Vendor_Code,
//	tbl2.Vendor_ArName,
//	ProductMast.LeadTime,
//	ProductMast.ReOrderLevel1 as 'MinOrder',
//	Qty_In-Qty_Out+Qty_In2 as 'Stock'
//	FROM (
//SELECT NodeNo,
//	Code,
//	Arabic_Name,
//	BaseUnits,
//	VendorNo,
//	Vendor_Code,
//	Vendor_ArName,
//	SUM(Qty_In) as 'Qty_In',
//	SUM(Qty_Out) as 'Qty_Out',
//    SUM(Qty_in2) as 'Qty_In2'
//	FROM (
//SELECT Distinct
//	NodeNo,
//	Code,
//	Arabic_Name,
//	BaseUnits,
//	VendorNo,
//	Vendor_Code,
//	Vendor_ArName,
//	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<='".$today." 23:59:25'  And Department = V.Department   Group By ProductNo) as Qty_In ,
//	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<='".$today." 23:59:25' And Department = V.Department    Group By ProductNo) as Qty_Out,
//	case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today ." 23:59:25'  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today." 23:59:25'  )  ) else 0 end  as Qty_in2
//	FROM (Select distinct
//	NodeNo ,
//	Code ,
//	Arabic_Name,
//	Department ,
//	BaseUnits ,
//	VendorNo,
//	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
//	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
//	-Sum(TotalCost) as Cost
//	from SInvoice ,productmast
//	Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
//	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	AND Code = '". $product_code ."'
//	And (SIDate <= '". $today ." 23:59:25'  )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
//	union all
//	Select distinct
//	NodeNo ,
//	Code,
//	Arabic_Name,
//	Department ,
//	BaseUnits ,
//	VendorNo,
//	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
//	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
//	Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
//	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	AND Code = '". $product_code ."'
//	And (PIDate <= '".$today." 23:59:25')
//	group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
//	group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName ) as tbl1
//	group By NodeNo , Code , Arabic_Name ,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName) as tbl2 LEFT JOIN ProductMast ON tbl2.NodeNo = ProductMast.NodeNo
//	--ORDER BY Vendor_Code
//	) as tbl3
//	LEFT JOIN
//	(SELECT
//	ProductNo,
//	SUM(Qty) as Qty,
//	SUM(ExecutedQty) as ExecutedQty,
//	SUM(DeliveredQty) as DeliveredQty,
//	SUM(case when Delivered = 'Delivered' THEN (Qty-ExecutedQty) +(ExecutedQty-DeliveredQty) ELSE  (Qty-ExecutedQty) END) as final_qty_a,
//
//	SUM(QtyOrderd) as QtyOrdered,
//	SUM(ExecutedQtyOrdered) as ExecutedQtyOrdered,
//	SUM(DeliveredQtyOrdered) as DeliveredQtyOrdered,
//	SUM((isnull(QtyOrderd,0)-isnull(ExecutedQtyOrdered, 0)) +(isnull(ExecutedQtyOrdered,0)-isnull(DeliveredQtyOrdered, 0))) as final_qty_ordered,
//	(SUM(isnull(Qty, 0))-SUM(isnull(ExecutedQty, 0))) + (SUM(isnull(QtyOrderd,0))-SUM(isnull(ExecutedQtyOrdered, 0))) as final_qty,
//	CASE WHEN (MIN(VField13)) = '2100-01-01' or (MIN(VField13)) = '1900-01-01' then null else MIN(VField13) end as purchase_arrival_date,
//    COUNT(VField13) as count_purchase_order
//FROM (
//Select
//	ProductNo,
//	Code,
//	(Select Name From ProductMast Where NodeNo = ProductNo) as ProductName,
//	ProductMast.Description,
//	(Select Arabic_Name From ProductMast Where NodeNo = ProductNo) as ProductArName,
//	BaseUnits ,
//	(Select BaseArabicUnit From Units Where BaseUnit = BaseUnits) as BaseArabicUnits ,
//	PODate ,
//	Q.Department,
//	(Select Name From DeptMast Where NodeNo = Q.Department) as Name,
//	(Select Arabic_Name From DeptMast Where NodeNo = Q.Department) as Arabic_Name ,
//	POrderNo as VoucherNo,
//	Q.VField18 as AltRef ,
//	case when Q.Executed = 0 then 'Open' else 'closed' end as [Open],
//	case when (Select Top 1 ActualQty From POrder Where RefrenceNo = Q.POrderNo) >= 0 then 'Invoiced' else '' End as Invoiced ,
//	case when (Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo /*and ProductNo = Q.ProductNo*/)) >= 0 then 'Delivered' else '' End as Delivered ,
//	(Select Name From AccMast Where NodeNo = AccountNo) as Customer,(Select Arabic_Name From AccMast Where NodeNo = AccountNo) as Arabic_Customer,
//	Sum(Q.value)  as Amount ,
//	sum(field2) as net,
//
//	sum(case when POrderNo like '280-%%' then ActualQty end) as Qty ,
//	sum(case when POrderNo like '280-%%' then ExecutedQty end) as ExecutedQty,
//	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '280-%%')), 0)  as DeliveredQty,
//
//	sum(case when POrderNo like '290-%%' then ActualQty end) as QtyOrderd ,
//	sum(case when POrderNo like '290-%%' then ExecutedQty end) as ExecutedQtyOrdered,
//	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '290-%%')), 0)  as DeliveredQtyOrdered,
//	PARSE((case when VField13 = '' then '01-01-2100' else VField13 end) as date USING 'AR-LB') as VField13
//
//	From POrder Q,Idetails,ProductMast ,extrafields
//	where porderno=extrafields.voucherno
//	And sequenceno = extrafields.sno
//	and POrderNo = Idetails.VoucherNo
//	And ProductNo = NodeNo
//	And ProductNo in (SELECT ProductNo FROM ProductMast WHERE Pricelist = 1 AND Code = '". $product_code ."') And
//	Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	AND Code = '". $product_code ."'
//	And (PODate >= '01/01/2023' And PODate <= '". $today ." 23:59:25')
//	And Executed = 0
//	And (PorderNo like '280-%%' or PorderNo like '290-%%')
//	--And (Select Name From DeptMast Where NodeNo = Q.Department) Not In (Select DeptName From DeptRights Where UserName ='HQ-BAlrashed')
//	Group By Department ,PODate ,POrderNo ,VField18 ,Executed ,AccountNo,ProductNo,Code,BaseUnits ,Description, VField13 --order by ProductNo ,POrderNo, Q.Department,POdate
//) as tbl
//GROUP BY ProductNo) as tbl4
//ON tbl3.NodeNo = tbl4.ProductNo
//ORDER BY Vendor_Code, Code";

                $stmt = '
                SELECT
	T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1" AS "OldItemCode",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
    T0."OnHand"
 FROM AL_YASEEN_AGRI_PLIVE.OITM T0
 INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"
 LEFT JOIN (
 SELECT DISTINCT

    T2."DocEntry",
    T2."DocNum",
    T2."DocDate",
	T2."ReqDate" as "DocDueDate",
    T2."CardCode",
    T2."CardName",
    T3."ItemCode" AS "ItemCode2",
    T4."U_UDF1" AS "OldItemCode",
    T3."Dscription",
	T3."ItemCode",
    T3."OpenQty" AS "OpenQoutation"
FROM

    AL_YASEEN_AGRI_PLIVE.OPQT T2

INNER JOIN

    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"

INNER JOIN

	AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"

INNER JOIN

	AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"

WHERE

    T2."DocStatus" = \'O\' -- Filters for open purchase quotation
 ) AS "tbl_Quotation" ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"
 WHERE T0."ItemCode" = \''.$product_code.'\'
 AND T0."validFor" = \'Y\'
 GROUP BY
 T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    T0."OnHand"
 ORDER BY T1."CardCode"';

                $stmt = 'SELECT
T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1" AS "OldItemCode",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",

    (
        SELECT SUM(TPRQ1."OpenQty")
        FROM AL_YASEEN_AGRI_PLIVE.OPRQ TPRQ
        INNER JOIN AL_YASEEN_AGRI_PLIVE.PRQ1 TPRQ1
            ON TPRQ."DocEntry" = TPRQ1."DocEntry"
        WHERE
            TPRQ."DocStatus" = \'O\'
            AND TPRQ1."ItemCode" = T0."ItemCode"
    ) AS "OpenPurchaseRequest",

    T0."OnHand"

FROM AL_YASEEN_AGRI_PLIVE.OITM T0

INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1
    ON T0."CardCode" = T1."CardCode"

LEFT JOIN (
 SELECT DISTINCT
    T2."DocEntry",
    T2."DocNum",
    T2."DocDate",
    T2."ReqDate" as "DocDueDate",
    T2."CardCode",
    T2."CardName",
    T3."ItemCode" AS "ItemCode2",
    T4."U_UDF1" AS "OldItemCode",
    T3."Dscription",
    T3."ItemCode",
    T3."OpenQty" AS "OpenQoutation"
FROM
    AL_YASEEN_AGRI_PLIVE.OPQT T2
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"
WHERE
    T2."DocStatus" = \'O\'
) AS "tbl_Quotation"
    ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"

WHERE T0."ItemCode" = \''.$product_code.'\'
AND T0."validFor" = \'Y\'

GROUP BY
 T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    T0."OnHand"

ORDER BY T1."CardCode"';
            }
            else {

//            $stmt = "SELECT * FROM (
//SELECT tbl2.NodeNo,
//	tbl2.Code,
//	tbl2.Arabic_Name,
//	tbl2.BaseUnits,
//	tbl2.VendorNo,
//	tbl2.Vendor_Code,
//	tbl2.Vendor_ArName,
//	ProductMast.LeadTime,
//	ProductMast.ReOrderLevel1 as 'MinOrder',
//	Qty_In-Qty_Out+Qty_In2 as 'Stock'
//	FROM (
//SELECT NodeNo,
//	Code,
//	Arabic_Name,
//	BaseUnits,
//	VendorNo,
//	Vendor_Code,
//	Vendor_ArName,
//	SUM(Qty_In) as 'Qty_In',
//	SUM(Qty_Out) as 'Qty_Out',
//    SUM(Qty_in2) as 'Qty_In2'
//	FROM (
//SELECT Distinct
//	NodeNo,
//	Code,
//	Arabic_Name,
//	BaseUnits,
//	VendorNo,
//	Vendor_Code,
//	Vendor_ArName,
//	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<='".$today." 23:59:25'  And Department = V.Department   Group By ProductNo) as Qty_In ,
//	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<='".$today." 23:59:25' And Department = V.Department    Group By ProductNo) as Qty_Out,
//	case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today ." 23:59:25'  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today." 23:59:25'  )  ) else 0 end  as Qty_in2
//	FROM (Select distinct
//	NodeNo ,
//	Code ,
//	Arabic_Name,
//	Department ,
//	BaseUnits ,
//	VendorNo,
//	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
//	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
//	-Sum(TotalCost) as Cost
//	from SInvoice ,productmast
//	Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
//	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	And (SIDate <= '". $today ." 23:59:25'  )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
//	union all
//	Select distinct
//	NodeNo ,
//	Code,
//	Arabic_Name,
//	Department ,
//	BaseUnits ,
//	VendorNo,
//	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
//	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
//	Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
//	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	And (PIDate <= '".$today." 23:59:25')
//	group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
//	group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName ) as tbl1
//	group By NodeNo , Code , Arabic_Name ,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName) as tbl2 LEFT JOIN ProductMast ON tbl2.NodeNo = ProductMast.NodeNo
//	--ORDER BY Vendor_Code
//	) as tbl3
//	LEFT JOIN
//	(SELECT
//	ProductNo,
//	SUM(Qty) as Qty,
//	SUM(ExecutedQty) as ExecutedQty,
//	SUM(DeliveredQty) as DeliveredQty,
//	SUM(case when Delivered = 'Delivered' THEN (Qty-ExecutedQty) +(ExecutedQty-DeliveredQty) ELSE  (Qty-ExecutedQty) END) as final_qty_a,
//
//	SUM(QtyOrderd) as QtyOrdered,
//	SUM(ExecutedQtyOrdered) as ExecutedQtyOrdered,
//	SUM(DeliveredQtyOrdered) as DeliveredQtyOrdered,
//	SUM((isnull(QtyOrderd,0)-isnull(ExecutedQtyOrdered, 0)) +(isnull(ExecutedQtyOrdered,0)-isnull(DeliveredQtyOrdered, 0))) as final_qty_ordered,
//	(SUM(isnull(Qty, 0))-SUM(isnull(ExecutedQty, 0))) + (SUM(isnull(QtyOrderd,0))-SUM(isnull(ExecutedQtyOrdered, 0))) as final_qty,
//	CASE WHEN (MIN(VField13)) = '2100-01-01' or (MIN(VField13)) = '1900-01-01' then null else MIN(VField13) end as purchase_arrival_date,
//    COUNT(VField13) as count_purchase_order
//FROM (
//Select
//	ProductNo,
//	Code,
//	(Select Name From ProductMast Where NodeNo = ProductNo) as ProductName,
//	ProductMast.Description,
//	(Select Arabic_Name From ProductMast Where NodeNo = ProductNo) as ProductArName,
//	BaseUnits ,
//	(Select BaseArabicUnit From Units Where BaseUnit = BaseUnits) as BaseArabicUnits ,
//	PODate ,
//	Q.Department,
//	(Select Name From DeptMast Where NodeNo = Q.Department) as Name,
//	(Select Arabic_Name From DeptMast Where NodeNo = Q.Department) as Arabic_Name ,
//	POrderNo as VoucherNo,
//	Q.VField18 as AltRef ,
//	case when Q.Executed = 0 then 'Open' else 'closed' end as [Open],
//	case when (Select Top 1 ActualQty From POrder Where RefrenceNo = Q.POrderNo) >= 0 then 'Invoiced' else '' End as Invoiced ,
//	case when (Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo /*and ProductNo = Q.ProductNo*/)) >= 0 then 'Delivered' else '' End as Delivered ,
//	(Select Name From AccMast Where NodeNo = AccountNo) as Customer,(Select Arabic_Name From AccMast Where NodeNo = AccountNo) as Arabic_Customer,
//	Sum(Q.value)  as Amount ,
//	sum(field2) as net,
//
//	sum(case when POrderNo like '280-%%' then ActualQty end) as Qty ,
//	sum(case when POrderNo like '280-%%' then ExecutedQty end) as ExecutedQty,
//	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '280-%%')), 0)  as DeliveredQty,
//
//	sum(case when POrderNo like '290-%%' then ActualQty end) as QtyOrderd ,
//	sum(case when POrderNo like '290-%%' then ExecutedQty end) as ExecutedQtyOrdered,
//	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '290-%%')), 0)  as DeliveredQtyOrdered,
//	PARSE((case when VField13 = '' then '01-01-2100' else VField13 end) as date USING 'AR-LB') as VField13
//
//	From POrder Q,Idetails,ProductMast ,extrafields
//	where porderno=extrafields.voucherno
//	And sequenceno = extrafields.sno
//	and POrderNo = Idetails.VoucherNo
//	And ProductNo = NodeNo
//	And ProductNo in (SELECT ProductNo FROM ProductMast WHERE Pricelist = 1) And Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
//	And (PODate >= '01/01/2023' And PODate <= '". $today ." 23:59:25')
//	And Executed = 0
//	And (PorderNo like '280-%%' or PorderNo like '290-%%')
//	--And (Select Name From DeptMast Where NodeNo = Q.Department) Not In (Select DeptName From DeptRights Where UserName ='HQ-BAlrashed')
//	Group By Department ,PODate ,POrderNo ,VField18 ,Executed ,AccountNo,ProductNo,Code,BaseUnits ,Description, VField13 --order by ProductNo ,POrderNo, Q.Department,POdate
//) as tbl
//GROUP BY ProductNo) as tbl4
//ON tbl3.NodeNo = tbl4.ProductNo
//ORDER BY Vendor_Code, Code";
//                $stmt = 'SELECT
//
//    "CardCode",
//    "CardName",
//    "ItemCode",
//    "OldItemCode",
//    "ItemName",
//    "InvntryUom",
//    "LeadTime",
//    "U_SafetyStock",
//    "OnHand",
//    COUNT(*) AS "count_purchase_order",
//    MIN("DocDueDate") AS "DocDueDate",
//    SUM("OpenQty") AS "OpenQty"
//
//    FROM (
//
//SELECT
//	OCRD."CardCode",
//    OCRD."CardName",
//    OITM."ItemCode",
//    OITM."U_UDF1" AS "OldItemCode",
//    OITM."ItemName",
//    OITM."InvntryUom",
//    OITM."LeadTime",
//    OITM."U_SafetyStock",
//	"tbl_0"."DocEntry",
//    "tbl_0"."DocNum",
//    "tbl_0"."DocDate",
//    "tbl_0"."DocDueDate",
//    "tbl_0"."Quantity",
//    "tbl_0"."OpenQty",
//    SUM("tbl_0"."OnHand") AS "OnHand"
//
// FROM AL_YASEEN_AGRI_PLIVE.OITM
// INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD ON OITM."CardCode" = OCRD."CardCode"
//
//LEFT JOIN (
//
//SELECT
//    T0."DocEntry",
//    T0."DocNum",
//    T0."DocDate",
//    T0."DocDueDate",
//    T0."CardCode",
//    T0."CardName",
//    T1."ItemCode",
//    T2."U_UDF1" AS "OldItemCode",
//    T1."Dscription",
//    T2."InvntryUom",
//    T2."LeadTime",
//    T2."U_SafetyStock",
//    T1."Quantity",
//    T1."OpenQty",
//    SUM(T3."OnHand") AS "OnHand"
//FROM
//
//    AL_YASEEN_AGRI_PLIVE.OPOR T0
//
//INNER JOIN
//
//    AL_YASEEN_AGRI_PLIVE.POR1 T1 ON T0."DocEntry" = T1."DocEntry"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITM T2 ON T1."ItemCode" = T2."ItemCode"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITW T3 ON T1."ItemCode" = T3."ItemCode"
//
//WHERE
//
//	T0."DocStatus" = \'O\' -- Filters for open purchase order
//
//	GROUP BY
//	T0."DocEntry",
//    T0."DocNum",
//    T0."DocDate",
//    T0."DocDueDate",
//    T0."CardCode",
//    T0."CardName",
//    T1."ItemCode",
//    T2."U_UDF1",
//    T1."Dscription",
//    T2."InvntryUom",
//    T2."LeadTime",
//    T2."U_SafetyStock",
//    T1."Quantity",
//    T1."OpenQty"
//
//
//UNION ALL
//
//
//SELECT
//
//    T2."DocEntry",
//    T2."DocNum",
//    T2."DocDate",
//    --T2."DocDueDate",
//	T2."ReqDate" as "DocDueDate",
//    T2."CardCode",
//    T2."CardName",
//    T4."U_UDF1" AS "OldItemCode",
//    T3."ItemCode",
//    T3."Dscription",
//    T4."InvntryUom",
//    T4."LeadTime",
//    T4."U_SafetyStock",
//    SUM(T5."OnHand") AS "OnHand",
//    T3."Quantity",
//    T3."OpenQty"
//
//FROM
//
//    AL_YASEEN_AGRI_PLIVE.OPQT T2
//
//INNER JOIN
//
//    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"
//
//WHERE
//
//    T2."DocStatus" = \'O\' -- Filters for open purchase quotation
//
//GROUP BY
//	T2."DocEntry",
//    T2."DocNum",
//    T2."DocDate",
//	T2."ReqDate",
//    T2."CardCode",
//    T2."CardName",
//    T4."U_UDF1",
//    T3."ItemCode",
//    T3."Dscription",
//    T4."InvntryUom",
//    T4."LeadTime",
//    T4."U_SafetyStock",
//    T3."Quantity",
//    T3."OpenQty"
//
//  ) as "tbl_0" ON "tbl_0"."ItemCode" = OITM."ItemCode"
//
//  GROUP BY OCRD."CardCode",
//    OCRD."CardName",
//    OITM."ItemCode",
//    OITM."U_UDF1",
//    OITM."ItemName",
//    OITM."InvntryUom",
//    OITM."LeadTime",
//    OITM."U_SafetyStock",
//	"tbl_0"."DocEntry",
//    "tbl_0"."DocNum",
//    "tbl_0"."DocDate",
//    "tbl_0"."DocDueDate",
//    "tbl_0"."Quantity",
//    "tbl_0"."OpenQty"
//
//    ) as tbl1
//
//GROUP BY "CardCode",
//    "CardName",
//    "ItemCode",
//    "OldItemCode",
//    "ItemName",
//    "InvntryUom",
//    "LeadTime",
//    "U_SafetyStock",
//    "OnHand"
//
//ORDER BY "CardCode"
//  ';

//                $stmt = '
//                SELECT
//	T1."CardCode",
//    T1."CardName",
//    T0."ItemCode",
//    T0."U_UDF1" AS "OldItemCode",
//    T0."ItemName",
//    T0."InvntryUom",
//    T0."LeadTime",
//    T0."U_SafetyStock",
//    T0."OnOrder",
//    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
//    T0."OnHand"
// FROM AL_YASEEN_AGRI_PLIVE.OITM T0
// INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"
// LEFT JOIN (
// SELECT DISTINCT
//
//    T2."DocEntry",
//    T2."DocNum",
//    T2."DocDate",
//	T2."ReqDate" as "DocDueDate",
//    T2."CardCode",
//    T2."CardName",
//    T3."ItemCode" AS "ItemCode2",
//    T4."U_UDF1" AS "OldItemCode",
//    T3."Dscription",
//	T3."ItemCode",
//    T3."OpenQty" AS "OpenQoutation"
//FROM
//
//    AL_YASEEN_AGRI_PLIVE.OPQT T2
//
//INNER JOIN
//
//    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"
//
//INNER JOIN
//
//	AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"
//
//WHERE
//
//    T2."DocStatus" = \'O\' -- Filters for open purchase quotation
// ) AS "tbl_Quotation" ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"
// WHERE T0."validFor" = \'Y\'
// GROUP BY
// T1."CardCode",
//    T1."CardName",
//    T0."ItemCode",
//    T0."U_UDF1",
//    T0."ItemName",
//    T0."InvntryUom",
//    T0."LeadTime",
//    T0."U_SafetyStock",
//    T0."OnOrder",
//    T0."OnHand"
// ORDER BY T1."CardCode"';

//                $stmt = 'SELECT
//T1."CardCode",
//    T1."CardName",
//    T0."ItemCode",
//    T0."U_UDF1" AS "OldItemCode",
//    T0."ItemName",
//    T0."InvntryUom",
//    T0."LeadTime",
//    T0."U_SafetyStock",
//    T0."OnOrder",
//    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
//    T0."OnHand"
// FROM AL_YASEEN_AGRI_PLIVE.OITM T0
// INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"
// LEFT JOIN (
// SELECT DISTINCT
//
//    T2."DocEntry",
//    T2."DocNum",
//    T2."DocDate",
//T2."ReqDate" as "DocDueDate",
//    T2."CardCode",
//    T2."CardName",
//    T3."ItemCode" AS "ItemCode2",
//    T4."U_UDF1" AS "OldItemCode",
//    T3."Dscription",
//T3."ItemCode",
//    T3."OpenQty" AS "OpenQoutation"
//FROM
//
//    AL_YASEEN_AGRI_PLIVE.OPQT T2
//
//INNER JOIN
//
//    AL_YASEEN_AGRI_PLIVE.PQT1 T3 ON T2."DocEntry" = T3."DocEntry"
//
//INNER JOIN
//
//AL_YASEEN_AGRI_PLIVE.OITM T4 ON T3."ItemCode" = T4."ItemCode"
//
//INNER JOIN
//
//AL_YASEEN_AGRI_PLIVE.OITW T5 ON T3."ItemCode" = T5."ItemCode"
//
//WHERE
//
//    T2."DocStatus" = \'O\' -- Filters for open purchase quotation
// ) AS "tbl_Quotation" ON "tbl_Quotation"."ItemCode2" = T0."ItemCode"
// WHERE T0."validFor" = \'Y\'
// GROUP BY
// T1."CardCode",
//    T1."CardName",
//    T0."ItemCode",
//    T0."U_UDF1",
//    T0."ItemName",
//    T0."InvntryUom",
//    T0."LeadTime",
//    T0."U_SafetyStock",
//    T0."OnOrder",
//    T0."OnHand"
// ORDER BY T1."CardCode"';


                $stmt = 'SELECT
    T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1" AS "OldItemCode",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    SUM("tbl_Quotation"."OpenQoutation") AS "OpenQoutation",
    SUM("tbl_PurchaseRequest"."OpenPurchaseRequest") AS "OpenPurchaseRequest",
    T0."OnHand"

FROM AL_YASEEN_AGRI_PLIVE.OITM T0

INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1
    ON T0."CardCode" = T1."CardCode"

LEFT JOIN (
    SELECT
        T3."ItemCode",
        SUM(T3."OpenQty") AS "OpenQoutation"
    FROM AL_YASEEN_AGRI_PLIVE.OPQT T2
    INNER JOIN AL_YASEEN_AGRI_PLIVE.PQT1 T3
        ON T2."DocEntry" = T3."DocEntry"
    WHERE T2."DocStatus" = \'O\'
    GROUP BY T3."ItemCode"
) AS "tbl_Quotation"
    ON "tbl_Quotation"."ItemCode" = T0."ItemCode"

LEFT JOIN (
    SELECT
        T3."ItemCode",
        SUM(T3."OpenQty") AS "OpenPurchaseRequest"
    FROM AL_YASEEN_AGRI_PLIVE.OPRQ T2
    INNER JOIN AL_YASEEN_AGRI_PLIVE.PRQ1 T3
        ON T2."DocEntry" = T3."DocEntry"
    WHERE T2."DocStatus" = \'O\'
    GROUP BY T3."ItemCode"
) AS "tbl_PurchaseRequest"
    ON "tbl_PurchaseRequest"."ItemCode" = T0."ItemCode"

WHERE T0."validFor" = \'Y\'

GROUP BY
    T1."CardCode",
    T1."CardName",
    T0."ItemCode",
    T0."U_UDF1",
    T0."ItemName",
    T0."InvntryUom",
    T0."LeadTime",
    T0."U_SafetyStock",
    T0."OnOrder",
    T0."OnHand"

ORDER BY T1."CardCode"';
            }



//            dd($stmt);
            $result = odbc_exec($conn, $stmt);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                $this->sap_results = [];
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
                }

//                dd($this->sap_results);

                $this->show_results = true;

                $this->emit('finished');


            }
            odbc_close($conn);
        }
    }

    public function exportReport($record_type) {

        $this->show_results = false;

        $today = Carbon::today()->format('d-m-Y');
        $this->emit('finished');
        return Excel::download(new PurchaseRecommendationExport($this->sap_results, $this->dist_days, $record_type, $this->show_results), "purchase-recommendation (${today}).xlsx");

    }
}
