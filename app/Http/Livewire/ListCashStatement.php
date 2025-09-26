<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListCashStatement extends Component
{
//    public $area_id = -1;
    public $start_date;
    public $end_date;
    public $results = [];
    public $sap_results = [];
    public $customer_list = [];
    public $customer_code = '';

    protected $listeners = ['create-report' => 'generateReport'];

    protected $rules = [
//        'area_id' => 'required|not_in:-1',
        'start_date' => 'required',
        'end_date' => 'required',
        'customer_code' => 'required'
    ];

    protected $messages = [
//        'area_id.required' => "مطلوب",
//        'area_id.not_in' => "مطلوب",
        'start_date.required' => "مطلوب",
        'end_date.required' => "مطلوب",
        'customer_code.required' => "مطلوب",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }


        if ((Auth::user()->user_group && in_array('list.customer-cash-statement', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {

        $this->query = User::where('id', Auth::id())->first();
        $this->branches = json_decode($this->query->branches);
        $this->customers();
//        dd($this->branches);

    }

    public function render()
    {
        return view('livewire.list-cash-statement')
            ->layout('layouts.dashboard');
    }

    public function generateReport($start_date, $end_date, $customer_code) {

        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->customer_code = $customer_code;

//        $this->validate();
//        $this->emit('show-container');
        $this->proccess_report();
//        $this->emit('show-container');
        $this->emit('finished');
    }

    public function proccess_report() {

        $branches = json_decode(Auth::user()->branches);

        $customer = AccMast::where('Code', $this->customer_code)
            ->whereIn('Accmast_Department', $branches)
            ->count();

        if ($customer < 1) {
            $this->results = [];
//            return $this->results;
        }

        $start_date = date($this->start_date . ' 00:00:00');
        $end_date = $this->end_date . ' 23:59:59';

//        $query = DB::connection('sqlsrv')->select("SELECT Code, Name, VoucherNo, VoucherDate, Value, customer_code, emp_name FROM (
//select distinct code,name, PaymentMethodDetails.*
//,sum(sinvoice.Value*exchangerate+extrafieldstotal) as svalue
//from PaymentMethodDetails,sinvoice
//,accmast where sinvoiceno=voucherno and
//partyno=nodeno and accmast.[type]=10
//and PaymentMethodDetails.type in (1,2,3,4)
//and voucherdate>=:start_date1 and  voucherdate<=:end_date1
//  group by code,name, voucherno,accountno,PaymentMethodDetails.value,PaymentMethodDetails.type,
//voucherdate,PaymentMethodDetails.ActualVoucherPrefix
//union all
//select distinct code,name, PaymentMethodDetails.*
//,sum(pinvoice.Value*exchangerate+extrafieldstotal) as svalue
//from PaymentMethodDetails,pinvoice
//,accmast where pinvoiceno=voucherno and
//partyno=nodeno and accmast.[type]=10
//and PaymentMethodDetails.type in (1,2,3,4)
//and voucherdate>=:start_date2 and  voucherdate<=:end_date2
//  group by code,name, voucherno,accountno,PaymentMethodDetails.value,PaymentMethodDetails.type,
//voucherdate,PaymentMethodDetails.ActualVoucherPrefix) as cash_tbl
//LEFT JOIN (select accmast.Code as customer_code, StudentMast.Arabic_Name as emp_name from accmast, WarrentyInfo, StudentMast
//where accmast.NodeNo = WarrentyInfo.AccountNo
//and WarrentyInfo.SalesEmployee = StudentMast.NodeNo) as manager_tbl
//on cash_tbl.code = manager_tbl.customer_code
//where Code =:customer_code
//order by VoucherDate asc", [
//
//            'start_date1' => $start_date,
//            'start_date2' => $start_date,
//            'end_date1' => $end_date,
//            'end_date2' => $end_date,
//            'customer_code' => $this->customer_code,
////            'voucherno1' => $this->search,
////            'voucherno2' => $this->search,
//        ]);

        $this->results = [];
        $this->sap_results = [];

        $query = DB::connection('sqlsrv')->select("SELECT Code, Name, VoucherNo, VoucherDate,item_code, qty, rate, Arabic_Name, BaseUnits as Unit, svalue as item_value, (Value/1.15) as Value, customer_code, emp_name FROM (
select accmast.code,accmast.name, ProductMast.Code as item_code , SInvoice.Rate, productmast.Arabic_Name, ProductMast.BaseUnits, PaymentMethodDetails.*
,ActualQty as qty, (sinvoice.Value*exchangerate+extrafieldstotal) as svalue
from PaymentMethodDetails,sinvoice, ProductMast
,accmast where sinvoiceno=voucherno and
partyno=accmast.nodeno and accmast.[type]=10
and SInvoice.ProductNo = ProductMast.NodeNo
and PaymentMethodDetails.type in (1,2,3,4)
and voucherdate>=:start_date1 and  voucherdate<=:end_date1
union all
select accmast.code,accmast.name, ProductMast.Code as item_code , PInvoice.Rate, productmast.Arabic_Name, ProductMast.BaseUnits, PaymentMethodDetails.*
,ActualQty as qty, (pinvoice.Value*exchangerate+extrafieldstotal) as svalue
from PaymentMethodDetails,pinvoice, ProductMast
,accmast where pinvoiceno=voucherno and
partyno=accmast.nodeno and accmast.[type]=10
and PInvoice.ProductNo = ProductMast.NodeNo
and PaymentMethodDetails.type in (1,2,3,4)
and voucherdate>=:start_date2 and  voucherdate<=:end_date2
) as cash_tbl
LEFT JOIN (select accmast.Code as customer_code, StudentMast.Arabic_Name as emp_name from accmast, WarrentyInfo, StudentMast
where accmast.NodeNo = WarrentyInfo.AccountNo
and WarrentyInfo.SalesEmployee = StudentMast.NodeNo) as manager_tbl
on cash_tbl.code = manager_tbl.customer_code
where Code =:customer_code
order by VoucherDate asc", [

            'start_date1' => $start_date,
            'start_date2' => $start_date,
            'end_date1' => $end_date,
            'end_date2' => $end_date,
            'customer_code' => $this->customer_code,
        ]);

        $this->results = json_decode(json_encode($query), true);



        $this->sapQuery($start_date, $end_date, $this->customer_code);
//        return $this->results;
        $this->emit('show-container');

    }

    public function sapQuery($start_date, $end_date, $customer_code) {

        if (Carbon::parse($start_date)->lt('2024-01-01')) {
            $start_date = '2024-01-01';
        }

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

        }
        else
        {

            $sql = 'SELECT "BusinessPartnerCode" as "customer_code", "BusinessPartnerName" as "Name", "SalesEmployeeOrBuyerName" as "emp_name", "DocumentNumber" as "VoucherNo", "DocumentDate" as "VoucherDate", "FullTotal" as "Value", T5."ItemCode" as "item_code", "ItemDescription" as "Arabic_Name", "SalUnitMsr" as "Unit", "QuantityInInventoryUoM" as "qty",
       IFNULL(("NetSalesAmountLC"/"QuantityInInventoryUoM"), 0) as "rate",

       "NetSalesAmountLC" as "item_value"  FROM (
SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",
(select "NetSalesAmountLC" FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery" WHERE "DocumentTypeCode" != \'17\' AND "DocumentTypeCode" != \'15\' AND "DocumentNumber" = T1."DocumentNumber" AND "DocumentTypeCode" = T1."DocumentTypeCode") as "FullTotal", * FROM (
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
WHERE "DocumentDate" >= \''.$start_date.'\' AND "DocumentDate" <= \''.$end_date.'\'
AND "DocumentTypeCode" != \'17\'
AND "DocumentTypeCode" != \'15\'

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2
ON T1."BusinessPartnerCode" = T2."CardCode"
--WHERE T2."CardCode" = \'0100412\'

LEFT JOIN AL_YASEEN_AGRI_PLIVE.OINV T3
ON T1."DocumentNumber" = T3."DocNum"
WHERE T2."CardCode" = \''.$customer_code.'\'
) as a
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T5
ON a."ItemCode" = T5."ItemCode"

WHERE a."BranchCode" IS NOT NULL
AND a."InvType" IS NULL
ORDER BY "DocumentNumber"';

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

    public function customers() {

        $this->customer_list = [];

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
            $customer_depts = ['2' => '01%', '3' => '01%', '10' => '02%', '7' => '03%', '13' => '04%', '4' => '05%' , '6' => '06%', '5' => '07%', '12' => '08%', '11' => '09%', '9' => '10%', '8' => '11%', '505' => '12%', '15' => '01%', '500' => '01%', '504' => '01%'];
            $customer_codes = array_intersect_key($customer_depts, array_flip($this->branches));


            $query = implode(' OR ', array_map(function ($value) {
                return 'T0."CardCode" LIKE \'' . $value . '\'';
            }, $customer_codes));

            $customerQuery = 'SELECT T0."CardCode", T0."CardName",
       CASE
	WHEN "CardCode" LIKE \'01%\' THEN \'0101\'
	WHEN "CardCode" LIKE \'02%\' THEN \'0102\'
	WHEN "CardCode" LIKE \'03%\' THEN \'0103\'
	WHEN "CardCode" LIKE \'04%\' THEN \'0104\'
	WHEN "CardCode" LIKE \'05%\' THEN \'0105\'
	WHEN "CardCode" LIKE \'06%\' THEN \'0106\'
	WHEN "CardCode" LIKE \'07%\' THEN \'0107\'
	WHEN "CardCode" LIKE \'08%\' THEN \'0108\'
	WHEN "CardCode" LIKE \'09%\' THEN \'0109\'
	WHEN "CardCode" LIKE \'10%\' THEN \'0110\'
	WHEN "CardCode" LIKE \'11%\' THEN \'0111\'
	WHEN "CardCode" LIKE \'12%\' THEN \'0112\'
END AS "Dept"
FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\' AND ('.$query.')
ORDER BY "CardCode"';

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

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }
}
