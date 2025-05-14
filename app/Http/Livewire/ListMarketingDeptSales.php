<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListMarketingDeptSales extends Component
{

    public $start_date;
    public $end_date;

    public $sap_results = [];

    public $show_msg = false;

    protected $rules = [
        'start_date' => 'required|date|after_or_equal:2024-01-01',
        'end_date' => 'required|date|after_or_equal:2024-01-01',
    ];

    protected $messages = [
        'start_date.required' => "مطلوب",
        'start_date.after_or_equal' => "يجب ان يكون التاريخ اعلى او يساوي 2024-01-01",
        'end_date.required' => "مطلوب",
        'end_date.after_or_equal' => "يجب ان يكون التاريخ اعلى او يساوي 2024-01-01",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.marketing-depts-sales', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.list-marketing-dept-sales')
            ->layout('layouts.dashboard');
    }

    public function generateReport() {

        set_time_limit(2000);

//        dd($this->start_date);

        $this->validate();
        $this->emit('show-container');

        $this->sapQuery($this->start_date, $this->end_date);

        $this->show_msg = true;
    }

    public function sapQuery($start_date, $end_date) {

        $prev_month_start = Carbon::parse($start_date)->addYears(-1)->format('Y-m-d');
        $prev_month_end = Carbon::parse($end_date)->addYears(-1)->format('Y-m-d');
        $prev_month_start_plus_month = Carbon::parse($start_date)->addYears(-1)->addMonth()->format('Y-m-d');

        $this->sap_results = [];
//        dd($prev_month_start_plus_month);

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
            $sql = 'SELECT \'QryGroup30\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup30" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup31\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup31" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup32\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup32" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup40\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup40" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup41\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup41" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup50\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup50" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup51\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup51" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup52\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup52" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL

UNION ALL

SELECT \'QryGroup53\' as "mrkt_type", SUM(CASE WHEN "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$prev_month_end.'\' THEN "NetSalesAmountLC" END) as "PreviousMonth",
SUM(CASE WHEN "DocumentDate" >= \''.$prev_month_start_plus_month.'\' AND "DocumentDate" <= \''.$end_date.'\' THEN "NetSalesAmountLC" END) as "CurrentYear" FROM (

SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
WHERE "DocumentDate" >= \''.$prev_month_start.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND ("DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."QryGroup53" = \'Y\'


)
WHERE "BranchCode" IS NOT NULL
AND "InvType" IS NULL';

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
//                    array_push($this->emp_codes, $row['OldSlpCode']);
                }

            }
            odbc_close($conn);
        }
    }
}
