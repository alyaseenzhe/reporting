<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeGrowthReport extends Component
{
    public $area_id = -1;
    public $sap_results = [];
    public $branch_id;
    public $final_results = [];
//    public $test = [];
    public $customer_purchased = [];
    public $category_qty = [];
    public $category_qty_total = [];

    public $emp_codes = [];
    public $visits = [];
    public $overFiftyThousand = [];
    public $emp_total = [];
    public $start_date;
    public $end_date;
    public $branch_postponed_due_amount_grand_total = 0;

    protected $rules = [
        'area_id' => 'required|not_in:-1',
        'start_date' => 'required',
        'end_date' => 'required',
    ];

    protected $messages = [
        'area_id.required' => "مطلوب",
        'area_id.not_in' => "مطلوب",
        'start_date.required' => "مطلوب",
        'end_date.required' => "مطلوب",
    ];

    /**
     * A Livewire lifecycle hook that runs on every request, after the component is hydrated.
     * It's used here for authorization, checking if the user is active and has permissions
     * to view this report. It redirects unauthorized users.
     */
    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.employee-growth', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * The standard Livewire method to render the component's Blade view.
     * It also specifies the master layout file to be used.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.employee-growth-report')
            ->layout('layouts.dashboard');
    }

    /**
     * Prepares and triggers the data fetching for the report.
     * It translates a selected area_id into a specific branch_id and then
     * calls the sapQuery method to retrieve data from the SAP HANA database.
     */
    public function proccess_report() {

        if ($this->area_id == '01') { // ahsa
//            $this->branch_id = '0101';
            $this->branch_id = '3';
        }
        elseif ($this->area_id == '02') { // jeddah
            $this->branch_id = '4';
//            $this->branch_id = '0102';
        }
        elseif ($this->area_id == '03') { // riyadh
            $this->branch_id = '5';
//            $this->branch_id = '0103';
        }
        elseif ($this->area_id == '04') { // wadi dawaser
            $this->branch_id = '6';
//            $this->branch_id = '0104';
        }
        elseif ($this->area_id == '05') { // jouf
            $this->branch_id = '7';
//            $this->branch_id = '0105';
        }
        elseif ($this->area_id == '06') { // dammam
            $this->branch_id = '8';
//            $this->branch_id = '0106';
        }
        elseif ($this->area_id == '07') { // kharaj
            $this->branch_id = '9';
//            $this->branch_id = '0107';
        }
        elseif ($this->area_id == '08') { // najran
            $this->branch_id = '10';
//            $this->branch_id = '0108';
        }
        elseif ($this->area_id == '09') { // hail
            $this->branch_id = '11';
//            $this->branch_id = '0109';
        }
        elseif ($this->area_id == '10') { // tabuk
            $this->branch_id = '12';
//            $this->branch_id = '0110';
        }
        elseif ($this->area_id == '11') { // qaseem
            $this->branch_id = '13';
//            $this->branch_id = '0111';
        }
        elseif ($this->area_id == '12') { // sajer
            $this->branch_id = '14';
//            $this->branch_id = '0112';
        }


        set_time_limit(2000);

//        $this->visits = $this->visits(['10175'], $this->start_date, $this->end_date);
//        dd($this->visits(['10035', '10036', '10032'], $this->start_date, $this->end_date));
        $this->sap_results = [];
        $this->emp_codes = [];

        $this->sapQuery($this->branch_id, $this->start_date, $this->end_date);
//        $this->visits = $this->visits($this->emp_codes, $this->start_date, $this->end_date);
//        dd($this->visits);
//        dd($this->sap_results);

        //////////////////// TESTING /////////////////////////



    }

    /**
     * This method handles the distribution of the generated report via email.
     * It contains predefined recipient lists for each branch and queues an email
     * containing the report data using the `WeeklyReport` Mailable.
     *
     * @return int
     */
    public function sendReport() {

        $ahsa_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'amir.saleh@alyaseenagri.com', 'sales.ahsa@alyaseenagri.com', 'gamil.mohamed@alyaseenagri.com', 'ahmed.wahd@alyaseenagri.com'];
        $jeddah_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'ibrahim.talat@alyaseenagri.com', 'sales.jeddah@alyaseenagri.com', 'ahmed.nassef@alyaseenagri.com'];
        $riyadh_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mohammed.samy@alyaseenagri.com', 'mohammed.fawzy@alyaseenagri.com', 'sales.riyadh@alyaseenagri.com'];
        $wadi_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mohammed.salem@alyaseenagri.com', 'omar.elhelefy@alyaseenagri.com', 'sales.wadi@alyaseenagri.com', 'ahmed.ibrahim@alyaseenagri.com'];
        $jouf_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'alsaid.saad@alyaseenagri.com', 'mosaad.dahshan@alyaseenagri.com', 'sales.aljouf@alyaseenagri.com'];
        $dammam_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'sales.dammam@alyaseenagri.com', 'atef.ibrahem@alyaseenagri.com'];
        $kharaj_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'radwan.hussen@alyaseenagri.com', 'sales.alkharj@alyaseenagri.com', 'abdullah.hatem@alyaseenagri.com'];
        $najran_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'wael.badr@alyaseenagri.com', 'omar.mohammed@alyaseenagri.com', 'sales.najran@alyaseenagri.com'];
        $hail_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'ahmed.elzekely@alyaseenagri.com', 'mohammed.majdi@alyaseenagri.com'];
        $tabouk_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'mahmoud.hashem@alyaseenagri.com', 'ahmed.khaled@alyaseenagri.com', 'sales.tabuk@alyaseenagri.com'];
        $qaseem_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'abdelwahab.hegazy@alyaseenagri.com', 'sales.qaseem@alyaseenagri.com', 'mohamed.nagy@alyaseenagri.com'];
        $sajer_branch = ['sadekr@alyaseenagri.com', 'mohammedsr@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'waleed.elnaggar@alyaseenagri.com', 'sales.sajer@alyaseenagri.com', 'abdulaziz.sharqawi@alyaseenagri.com'];

//        Mail::to(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));

        if ($this->area_id == '01') {
//            Mail::to($ahsa_branch)->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
            Mail::to($ahsa_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '02') {
            Mail::to($jeddah_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '03') {
            Mail::to($riyadh_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '04') {
            Mail::to($wadi_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '05') {
            Mail::to($jouf_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '06') {
            Mail::to($dammam_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '07') {
            Mail::to($kharaj_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '08') {
            Mail::to($najran_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '09') {
            Mail::to($hail_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '10') {
            Mail::to($tabouk_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '11') {
            Mail::to($qaseem_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }
        elseif ($this->area_id == '12') {
            Mail::to($sajer_branch)->cc(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->sap_results, $this->area_id, $this->start_date, $this->end_date, $this->visits));
        }

        return 0;
    }

    /**
     * The primary action method triggered by the user to generate the report.
     * It validates the form input, shows a loading state, and then calls
     * the main processing function.
     */
    public function generateReport() {

        set_time_limit(2000);

        $this->validate();
        $this->emit('show-container');

        $this->proccess_report();
    }

    /**
     * Connects to the SAP HANA database via ODBC to execute a complex sales analysis query.
     * The query compares sales data between the selected date range and the same period
     * in the previous year, aggregating results by salesperson for the specified branch.
     *
     * @param string $branch The branch code to filter the query by.
     * @param string $start_date The start of the reporting period.
     * @param string $end_date The end of the reporting period.
     */
    public function sapQuery($branch, $start_date, $end_date) {

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

            $previous_start_date = Carbon::parse($start_date)->subYear()->format('Y-m-d');
            $previous_end_date = Carbon::parse($end_date)->subYear()->format('Y-m-d');


            $sql = 'SELECT "SlpCode", "SlpName",
SUM(CASE WHEN ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "FullTotalOLD",
SUM(CASE WHEN ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "FullTotalNEW",

COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'11%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "ItemCode" END) AS "FertilizersCount OLD",
SUM(CASE WHEN "ItemCode" LIKE \'11%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "Fertilizers OLD",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'11%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "ItemCode" END) AS "FertilizersCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'11%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "Fertilizers NEW",

COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'12%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "ItemCode" END) AS "ChemicalsCount OLD",
SUM(CASE WHEN "ItemCode" LIKE \'12%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "Chemicals OLD",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'12%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "ItemCode" END) AS "ChemicalsCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'12%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "Chemicals NEW",

COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'13%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "ItemCode" END) AS "SeedsCount OLD",
SUM(CASE WHEN "ItemCode" LIKE \'13%\' AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "Seeds OLD",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'13%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "ItemCode" END) AS "SeedsCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'13%\' AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "Seeds NEW",

COUNT(DISTINCT CASE WHEN ("ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\') AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "ItemCode" END) AS "OthersCount OLD",
SUM(CASE WHEN ("ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\') AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "Others OLD",
COUNT(DISTINCT CASE WHEN ("ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\') AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "ItemCode" END) AS "OthersCount NEW",
SUM(CASE WHEN ("ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\') AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "Others NEW",

SUM(CASE WHEN ("Speciality" = \'sp0\') AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "S0 Sales OLD",
SUM(CASE WHEN ("Speciality" = \'sp1\') AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "S1 Sales OLD",
SUM(CASE WHEN ("Speciality" = \'sp2\') AND ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $previous_end_date . '\') THEN "NetSalesAmountLC" END) AS "S2 Sales OLD",
SUM(CASE WHEN ("Speciality" = \'sp0\') AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "S0 Sales NEW",
SUM(CASE WHEN ("Speciality" = \'sp1\') AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "S1 Sales NEW",
SUM(CASE WHEN ("Speciality" = \'sp2\') AND ("DocumentDate" >= \'' . $start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\') THEN "NetSalesAmountLC" END) AS "S2 Sales NEW"

FROM (
SELECT TS."SlpCode", TS."SlpName",(SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",
CASE
    WHEN T2."QryGroup1" = \'Y\' THEN \'sp0\'
    WHEN T2."QryGroup2" = \'Y\' THEN \'sp1\'
    WHEN T2."QryGroup3" = \'Y\' THEN \'sp2\'
END AS "Speciality", T1.* FROM (
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
WHERE ("DocumentDate" >= \'' . $previous_start_date . '\' AND "DocumentDate" <= \'' . $end_date . '\')
AND "DocumentTypeCode" != \'17\'

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD TC ON T1."BusinessPartnerCode" = TC."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP TS ON TC."SlpCode" = TS."SlpCode"
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
--WHERE T2."QryGroup2" = \'Y\'

)

WHERE "BranchCode" = '. $branch . '
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName"';

//    dd($sql);


            $result = odbc_exec($conn, $sql);

            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                // echo odbc_num_rows($result);
//                dd(odbc_fetch_row($result));
//                $aa = odbc_result_all($result, "border=1");
//                $x = odbc_fetch_object($result);
//                $this->sap_results
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
//                    array_push($this->emp_codes, $row['OldSlpCode']);
                }

//                var_dump($this->sap_results);
//                dd($this->emp_codes);
//                dd($this->sap_results);

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
