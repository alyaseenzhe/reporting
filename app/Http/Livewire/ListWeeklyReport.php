<?php

namespace App\Http\Livewire;

use App\Mail\WeeklyReport;
use App\Models\AccMast;
use App\Models\FAExtra;
use App\Models\PInvoice;
use App\Models\SInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ListWeeklyReport extends Component
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

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.weekly-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
//        $test = [];
//
//        if (array_key_exists('0444')) {
//            $test["0444"] = array_key_exists('sp0', $test["0444"]) ? $test["0444"]['sp0'] += 115 :  ['sp0' => 511];
//        }
//        else {
//            $test["0444"] = ['sp0' => 5];
//        }
////        array_push($test, $test["0444"]);
//        dd($test);

        return view('livewire.list-weekly-report')
            ->layout('layouts.dashboard');
    }

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
        $this->visits = $this->visits($this->emp_codes, $this->start_date, $this->end_date);
//        dd($this->visits);
//        dd($this->sap_results);

        //////////////////// TESTING /////////////////////////



    }

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

    public function generateReport() {

        set_time_limit(2000);

        $this->validate();
        $this->emit('show-container');

        $this->proccess_report();
    }

    public function visits($emp_codes, $start_date, $end_date) {

//        dd($emp_codes);
//        dd($this->end_date);
        $visits = DB::connection('mysql')->table('daily_reports')
            ->join('users', 'daily_reports.added_by', 'users.id')
            ->whereBetween('report_date', [$this->start_date, $this->end_date])
//            ->whereIn('users.emp_code', ['10035'])
            ->whereIn('users.emp_code', $emp_codes)
            ->where('report_type', 'visit')
//            ->where('customer_name', 'not like', '%عميل عام%')
//            ->where('customer_name', 'not like', '%0000000%')
//            ->orWhere('customer_name', 'not like', '%0100000%')
//            ->orWhere('customer_name', 'not like', '%0200000%')
//            ->orWhere('customer_name', 'not like', '%0300000%')
//            ->orWhere('customer_name', 'not like', '%0400000%')
//            ->orWhere('customer_name', 'not like', '%0500000%')
//            ->orWhere('customer_name', 'not like', '%0600000%')
//            ->orWhere('customer_name', 'not like', '%0700000%')
//            ->orWhere('customer_name', 'not like', '%0800000%')
//            ->orWhere('customer_name', 'not like', '%0900000%')
//            ->orWhere('customer_name', 'not like', '%1000000%')
//            ->orWhere('customer_name', 'not like', '%1100000%')
//            ->orWhere('customer_name', 'not like', '%1200000%')
//            ->orWhere('customer_name', 'not like', '%0109999%')

            ->where(function ($query) {
                $query->where('customer_name', 'not like', '%0000000%')
                    ->where('customer_name', 'not like', '%0100000-%')
                    ->where('customer_name', 'not like', '%0200000%')
                    ->where('customer_name', 'not like', '%0300000%')
                    ->where('customer_name', 'not like', '%0400000%')
                    ->where('customer_name', 'not like', '%0500000%')
                    ->where('customer_name', 'not like', '%0600000%')
                    ->where('customer_name', 'not like', '%0700000%')
                    ->where('customer_name', 'not like', '%0800000%')
                    ->where('customer_name', 'not like', '%0900000%')
                    ->where('customer_name', 'not like', '%1000000%')
                    ->where('customer_name', 'not like', '%1100000%')
                    ->where('customer_name', 'not like', '%1200000%')
                    ->where('customer_name', 'not like', '%0109999%');
            })//->toSql();
//            ->get();
            ->select('users.emp_code', DB::raw('COUNT(DISTINCT customer_name) as num_of_visits'))
            ->groupBy('users.emp_code')
            ->pluck('num_of_visits', 'emp_code')->toArray();


//        $visits = DB::connection('mysql2')->table('activity')
//            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
//            ->whereNotNull('customer_id')
//            ->whereIn('author', $emp_codes)
//            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date. ' 23:59:25'])
////            ->where('is_branch_visit', '!=', 'N')
////            ->where('type','a-00')
//            ->where('added_using', 'W')
//            ->whereNotIn('customer_id', ['0000000', '0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000', '0109999'])
////            ->whereIn('place_id',['0101', '01011', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->whereIn('place_id',['0001', '0020', '0099', '0101', '01011', '0102', '01021', '01023', '0103', '01031', '0104', '0105', '0106', '0107', '0108', '01081', '0109', '0110', '0111', '0112', '0202', '0203'])
//            ->select('author', DB::raw('COUNT(DISTINCT customer_id) as num_of_visits'))
//            ->groupBy('author')
//            ->pluck('num_of_visits', 'author')->toArray();
//        dd($visits);

//        $general_visits = DB::connection('mysql2')->table('activity')
//            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
//            ->whereNotNull('customer_id')
//            ->whereIn('author', $emp_codes)
//            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date. ' 23:59:25'])
////            ->where('is_branch_visit', '!=', 'N')
////            ->where('type','a-00')
//            ->where('added_using', 'W')
//            ->whereIn('customer_id', ['0000000', '0100000', '0200000', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0900000', '1000000', '1100000', '1200000', '0109999'])
//            ->whereIn('place_id',['0001', '0020', '0099', '0101', '01011', '0102', '01021', '01023', '0103', '01031', '0104', '0105', '0106', '0107', '0108', '01081', '0109', '0110', '0111', '0112', '0202', '0203'])
////            ->whereIn('place_id',['0101', '01011', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->select('author', DB::raw('COUNT(DISTINCT(customer_id)) as num_of_visits'))
//            ->groupBy('author')
//            ->pluck('num_of_visits', 'author')->toArray();

        $general_visits = DB::table('daily_reports')
            ->join('users', 'daily_reports.added_by', 'users.id')
            ->whereBetween('report_date', [$this->start_date, $this->end_date])
            ->whereIn('users.emp_code', $emp_codes)
            ->where('report_type', 'visit')
//            ->where('customer_name', 'like', '%0000000%')
//            ->orWhere('customer_name', 'like', '%0100000%')
//            ->orWhere('customer_name', 'like', '%0200000%')
//            ->orWhere('customer_name', 'like', '%0300000%')
//            ->orWhere('customer_name', 'like', '%0400000%')
//            ->orWhere('customer_name', 'like', '%0500000%')
//            ->orWhere('customer_name', 'like', '%0600000%')
//            ->orWhere('customer_name', 'like', '%0700000%')
//            ->orWhere('customer_name', 'like', '%0800000%')
//            ->orWhere('customer_name', 'like', '%0900000%')
//            ->orWhere('customer_name', 'like', '%1000000%')
//            ->orWhere('customer_name', 'like', '%1100000%')
//            ->orWhere('customer_name', 'like', '%1200000%')
//            ->orWhere('customer_name', 'like', '%0109999%')
            ->where(function ($query) {
                $query->orWhere('customer_name', 'like', '%0000000%')
                    ->orWhere('customer_name', 'like', '%0100000%')
                    ->orWhere('customer_name', 'like', '%0200000%')
                    ->orWhere('customer_name', 'like', '%0300000%')
                    ->orWhere('customer_name', 'like', '%0400000%')
                    ->orWhere('customer_name', 'like', '%0500000%')
                    ->orWhere('customer_name', 'like', '%0600000%')
                    ->orWhere('customer_name', 'like', '%0700000%')
                    ->orWhere('customer_name', 'like', '%0800000%')
                    ->orWhere('customer_name', 'like', '%0900000%')
                    ->orWhere('customer_name', 'like', '%1000000%')
                    ->orWhere('customer_name', 'like', '%1100000%')
                    ->orWhere('customer_name', 'like', '%1200000%')
                    ->orWhere('customer_name', 'like', '%0109999%');
            })
//            ->get();
            ->select('users.emp_code', DB::raw('COUNT(DISTINCT customer_name) as num_of_visits'))
            ->groupBy('users.emp_code')
            ->pluck('num_of_visits', 'emp_code')->toArray();

//        dd($general_visits);

        foreach ($visits as $key => $visit) {
            if (array_key_exists($key, $general_visits)) {
                $visits[$key] += $general_visits[$key];
            }
            else {
                $visits[$key] += 0;
//                $visits[$key] = $general_visits[$key];
//                $visits = [$key => $general_visits[$key]];
            }
        }

//        dd($visits);

        return $visits;
    }

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

            /*           $sql_x = 'SELECT * FROM (
           SELECT T3."CardCode", T0."TransId", T0."RefDate", T1."LineMemo", T1."Debit", T1."Credit",
                  SUM(T1."Debit" - T1."Credit") OVER (PARTITION BY T1."Account" ORDER BY T0."RefDate", T0."TransId") AS "CumulativeBalance"
           FROM AL_YASEEN_TEST.OJDT T0
           INNER JOIN AL_YASEEN_TEST.JDT1 T1 ON T0."TransId" = T1."TransId"
           INNER JOIN AL_YASEEN_TEST.OACT T2 ON T1."Account" = T2."AcctCode"
           INNER JOIN AL_YASEEN_TEST.OCRD T3 ON T1."ShortName" = T3."CardCode"
           WHERE T0."RefDate" >= \'20230101\'
           AND T3."CardCode" = \''.$this->customer_id.'\'
           ORDER BY T0."TaxDate", T0."TransId") as "tbl1"
           WHERE ("RefDate" >= \''.$start_date.'\' AND "RefDate" <= \''.$end_date.'\')';
           */
//// new query of SAP
///
///




//            $sql = 'SELECT * FROM (
//SELECT
//	tbl1."BPLId",
//	tbl1."BPLName",
//	tbl1."SlpCode",
//	tbl1."OldSlpCode",
//	tbl1."SlpName",
//	SUM(CASE WHEN tbl1."Type" = \'CASH\' THEN tbl1."FullTotal" ELSE 0 END) AS "Cash_Total",
//	SUM(CASE WHEN tbl1."Type" = \'CREDIT\' THEN tbl1."FullTotal" ELSE 0 END) AS "Credit_Total",
//	SUM(CASE WHEN tbl1."Type" = \'CASH\' THEN tbl1."FullTotal" ELSE 0 END) + SUM(CASE WHEN tbl1."Type" = \'CREDIT\' THEN tbl1."FullTotal" ELSE 0 END) AS "Total"
//
//--	CASE WHEN MAX(tbl1."Type") = 4 THEN SUM(tbl1."FullTotal") END AS "Cash_Total",
//--	CASE WHEN MAX(tbl1."Type") = 6 THEN SUM(tbl1."FullTotal") END AS "Credit_Total"
//FROM (
//
//SELECT
//--*,
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	T0."DocTotal",
//	T0."DocTotal"-T0."VatSum" AS "FullTotal",
//--	SUM(T0."DocTotal"-T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	CASE WHEN T0."DocDate" = T4."DocDate" THEN \'CASH\' ELSE \'CREDIT\' END AS "Type"
//
//--	T3."DocDate"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.OINV T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//--AND T0."DocDate" >= \'2024-08-01\' and T0."DocDate" <= \'2024-08-12\'
//--AND T0."DocDate" >= \'2024-08-12\'
//--AND T0."DocDate" = \'2024-08-12\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//
//UNION ALL
//
//SELECT
//--*,
//
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	-T0."DocTotal",
//	-T0."DocTotal"+T0."VatSum" AS "FullTotal",
//	--SUM(-T0."DocTotal"+T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	\'CASH\' AS "Type"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.ORIN T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//--AND T0."DocDate" >= \'2024-08-01\' and T0."DocDate" <= \'2024-08-12\'
//--AND T0."DocDate" >= \'2024-08-12\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//) AS tbl1
//GROUP BY tbl1."BPLId",
//	tbl1."BPLName",
//	tbl1."SlpCode",
//	tbl1."OldSlpCode",
//	tbl1."SlpName"
//	--tbl1."Type"
//) AS "cash_tbl"
//LEFT JOIN(SELECT
//	tbl1."BPLId",
//	tbl1."BPLName",
//	tbl1."SlpCode",
//	tbl1."OldSlpCode",
//	tbl1."SlpName",
//	SUM(CASE WHEN tbl1."SPL" = \'Speciality0\' THEN tbl1."LineTotal" ELSE 0 END) AS "Speciality0",
//	SUM(CASE WHEN tbl1."SPL" = \'Speciality1\' THEN tbl1."LineTotal" ELSE 0 END) AS "Speciality1",
//	SUM(CASE WHEN tbl1."SPL" = \'Speciality2\' THEN tbl1."LineTotal" ELSE 0 END) AS "Speciality2"
//FROM (
//SELECT
//--*,
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	T0."DocTotal",
//	T0."DocTotal"-T0."VatSum" AS "FullTotal",
//	T5."LineTotal",
//--	SUM(T0."DocTotal"-T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	CASE WHEN T0."DocDate" = T4."DocDate" THEN \'CASH\' ELSE \'CREDIT\' END AS "Type",
//	COALESCE(T7."Property1",T7."Property2",T7."Property3") AS "SPL"
//
//--	T3."DocDate"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.OINV T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//JOIN AL_YASEEN_AGRI_PLIVE.INV1 T5 ON T5."DocEntry"=T0."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T6 ON T5."ItemCode"=T6."ItemCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T7 ON T6."ItemCode"=T7."ItemCode"
//
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//--AND T0."DocDate" >= \'2024-08-01\' and T0."DocDate" <= \'2024-08-12\'
//--AND T0."DocDate" >= \'2024-08-12\'
//--AND T0."DocDate" = \'2024-08-12\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//
//UNION ALL
//
//SELECT
//--*,
//
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	-T0."DocTotal",
//	-T0."DocTotal"+T0."VatSum" AS "FullTotal",
//	-T5."LineTotal",
//	--SUM(-T0."DocTotal"+T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	\'CASH\' AS "Type",
//	COALESCE(T7."Property1",T7."Property2",T7."Property3") AS "SPL"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.ORIN T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//JOIN AL_YASEEN_AGRI_PLIVE.RIN1 T5 ON T5."DocEntry"=T0."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T6 ON T5."ItemCode"=T6."ItemCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T7 ON T6."ItemCode"=T7."ItemCode"
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//--AND T0."DocDate" >= \'2024-08-01\' and T0."DocDate" <= \'2024-08-12\'
//--AND T0."DocDate" >= \'2024-08-12\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//) as tbl1
//GROUP BY tbl1."BPLId",
//	tbl1."BPLName",
//	tbl1."SlpCode",
//	tbl1."OldSlpCode",
//	tbl1."SlpName"
//) as "speciality_tbl" ON "cash_tbl"."SlpCode" = "speciality_tbl"."SlpCode"
//LEFT JOIN (
//SELECT x."BPLId",
//	x."BPLName",
//	x."SlpCode",
//	x."OldSlpCode",
//	x."SlpName",
//	IFNULL(COUNT(DISTINCT CASE WHEN x."ItemType" = \'Fertilizers\' THEN x."ItemCode" END), 0) AS "FertilizersCount",
//	SUM(CASE WHEN x."ItemCode" LIKE \'11%\' THEN x."LineTotal" ELSE 0 END) AS "Fertilizers",
//	IFNULL(COUNT(DISTINCT CASE WHEN x."ItemType" = \'Chemicals\' THEN x."ItemCode" END), 0) AS "ChemicalsCount",
//	SUM(CASE WHEN x."ItemCode" LIKE \'12%\' THEN x."LineTotal" ELSE 0 END) AS "Chemicals",
//	IFNULL(COUNT(DISTINCT CASE WHEN x."ItemType" = \'Seeds\' THEN x."ItemCode" END), 0) AS "SeedsCount",
//	SUM(CASE WHEN x."ItemCode" LIKE \'13%\' THEN x."LineTotal" ELSE 0 END) AS "Seeds",
//	IFNULL(COUNT(DISTINCT CASE WHEN x."ItemType" = \'Others\' THEN x."ItemCode" END), 0) AS "OthersCount",
//	SUM(CASE WHEN x."ItemCode" LIKE \'14%\' OR x."ItemCode" LIKE \'15%\' OR x."ItemCode" LIKE \'16%\' OR x."ItemCode" LIKE \'28%\' OR x."ItemCode" LIKE \'29%\' OR x."ItemCode" LIKE \'30%\'  THEN x."LineTotal" ELSE 0 END) AS "Others"
//	FROM (
//--SELECT * FROM (
//
//SELECT
//--*,
//	T6."ItemCode",
//	T5."Quantity",
//	CASE
//		WHEN T6."ItemCode" LIKE \'11%\' THEN \'Fertilizers\'
//		WHEN T6."ItemCode" LIKE \'12%\' THEN \'Chemicals\'
//		WHEN T6."ItemCode" LIKE \'13%\' THEN \'Seeds\'
//		WHEN T6."ItemCode" LIKE \'14%\' OR T6."ItemCode" LIKE \'15%\' OR T6."ItemCode" LIKE \'16%\' OR T6."ItemCode" LIKE \'28%\' OR T6."ItemCode" LIKE \'29%\' OR T6."ItemCode" LIKE \'30%\' THEN \'Others\'
//	END AS "ItemType",
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	T0."DocTotal",
//	T0."DocTotal"-T0."VatSum" AS "FullTotal",
//	T5."LineTotal",
//--	SUM(T0."DocTotal"-T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	CASE WHEN T0."DocDate" = T4."DocDate" THEN \'CASH\' ELSE \'CREDIT\' END AS "Type",
//	COALESCE(T7."Property1",T7."Property2",T7."Property3") AS "SPL"
//
//--	T3."DocDate"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.OINV T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//JOIN AL_YASEEN_AGRI_PLIVE.INV1 T5 ON T5."DocEntry"=T0."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T6 ON T5."ItemCode"=T6."ItemCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T7 ON T6."ItemCode"=T7."ItemCode"
//
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//--AND T0."DocDate" >= \'2024-08-01\' and T0."DocDate" <= \'2024-08-12\'
//--AND T0."DocDate" >= \'2024-08-12\'
//--AND T0."DocDate" = \'2024-08-12\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//
//UNION ALL
//
//SELECT
//--*,
//	T6."ItemCode",
//	T5."Quantity",
//	CASE
//		WHEN T6."ItemCode" LIKE \'11%\' THEN \'Fertilizers\'
//		WHEN T6."ItemCode" LIKE \'12%\' THEN \'Chemicals\'
//		WHEN T6."ItemCode" LIKE \'13%\' THEN \'Seeds\'
//		WHEN T6."ItemCode" LIKE \'14%\' OR T6."ItemCode" LIKE \'15%\' OR T6."ItemCode" LIKE \'16%\' OR T6."ItemCode" LIKE \'28%\' OR T6."ItemCode" LIKE \'29%\' OR T6."ItemCode" LIKE \'30%\' THEN \'Others\'
//	END AS "ItemType",
//	T0."DocNum",
//	T0."CANCELED",
//	T0."DocDate",
//	T0."CardCode",
//	T0."CardName",
//	-T0."DocTotal",
//	-T0."DocTotal"+T0."VatSum" AS "FullTotal",
//	-T5."LineTotal",
//	--SUM(-T0."DocTotal"+T0."VatSum"),
//	--T0."JrnlMemo",
//	T0."BPLId",
//	T0."BPLName",
//	T2."SlpCode",
//	T2."Memo" AS "OldSlpCode",
//	T2."SlpName",
//	T4."DocNum",
//	T4."DocDate",
//	T3."SumApplied",
//	\'CASH\' AS "Type",
//	COALESCE(T7."Property1",T7."Property2",T7."Property3") AS "SPL"
//--SUM(T0."DocTotal"-T0."VatSum")
//
//FROM AL_YASEEN_AGRI_PLIVE.ORIN T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
//JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
//JOIN AL_YASEEN_AGRI_PLIVE.RIN1 T5 ON T5."DocEntry"=T0."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T6 ON T5."ItemCode"=T6."ItemCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE."U_ItemProperties" T7 ON T6."ItemCode"=T7."ItemCode"
//WHERE T0."BPLId" = \''.$branch.'\'
//AND T0."CANCELED" = \'N\'
//AND T0."DocDate" >= \''.$start_date.'\' and T0."DocDate" <= \''.$end_date.'\'
//) as x
//GROUP BY x."BPLId",
//	x."BPLName",
//	x."SlpCode",
//	x."OldSlpCode",
//	x."SlpName"
//--AND x."SlpCode" = 305
//) as "category_tbl" ON "category_tbl"."SlpCode" = "speciality_tbl"."SlpCode"
//LEFT JOIN (
//SELECT
//tbl1."BPLId",
//tbl1."BPLName",
//tbl1."SlpCode",
//tbl1."OldSlpCode",
//tbl1."SlpName",
//SUM(tbl1."A4-A5_LC") as "Month5",
//SUM(tbl1."A5+_LC") as "Older",
//SUM(tbl1."TotalBalance") as "TotalBalance"
//
//FROM (
//SELECT
//F0."BPLId",
//F0."BPLName",
//F0."SlpCode",
//F0."OldSlpCode",
//F0."SlpName",
//F0."CardCode",
//F0."CardName",
//F0."CreditLine",
//F0."Balance",
//F0."FutureLC",
//F0."0-A1_LC",
//F0."A1-A2_LC",
//F0."A2-A3_LC",
//F0."A3-A4_LC",
//F0."A4-A5_LC",
//F0."A5+_LC",
//SUM(F0."0-A1_LC"+
//F0."A1-A2_LC"+
//F0."A2-A3_LC"+
//F0."A3-A4_LC"+
//F0."A4-A5_LC"+
//F0."A5+_LC") OVER (PARTITION BY F0."CardCode") AS "TotalBalance",
//IFNULL(F1."PDC",0) AS "PDC"
//
//FROM (
//
//SELECT
//T0."BPLId",
//T0."BPLName",
//T0."SlpCode",
//T0."OldSlpCode",
//T0."SlpName",
//T0."CardCode",
//T0."CardName",
//T0."CreditLine",
//T0."Balance",
//SUM(CASE WHEN T0."RefDate" > \''.$end_date.'\' THEN T0."BalanceDue" ELSE 0 END) as "FutureLC",
//SUM(CASE WHEN T0."RefDate" <=  \''.$end_date.'\' AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-(30+1))  THEN T0."BalanceDue" ELSE 0 END) AS "0-A1_LC",
//SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-30-1)AND T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-60-1)  THEN T0."BalanceDue" ELSE 0 END) AS "A1-A2_LC",
//SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-60-1)AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-90-1)  THEN T0."BalanceDue" ELSE 0 END) AS "A2-A3_LC",
//SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-90-1)AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-120-1)  THEN T0."BalanceDue" ELSE 0 END) AS "A3-A4_LC",
//SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-120-1) AND  T0."RefDate" > ADD_DAYS(\''.$end_date.'\',-150-1)  THEN T0."BalanceDue" ELSE 0 END) AS "A4-A5_LC",
//SUM(CASE WHEN T0."RefDate" <= ADD_DAYS(\''.$end_date.'\',-150-1)THEN T0."BalanceDue" ELSE 0 END) AS "A5+_LC"
//
//FROM (
//
//SELECT
//T0."BPLId",
//T0."BPLName",
//T9."SlpCode",
//T9."Memo" AS "OldSlpCode",
//T9."SlpName",
//T2."CardCode",
//T2."CardName",
//T2."CreditLine",
//T2."Balance",
//T0."RefDate",
//CASE WHEN T0."DebCred" = \'D\' THEN (T0."Debit"-T0."Credit")-ifnull(T3."ReconSum",0)
// WHEN T0."DebCred" = \'C\' THEN -((T0."Credit"-T0."Debit")-ifnull(T3."ReconSum",0)) ELSE 0 END as "BalanceDue",
//
//CASE WHEN T0."DebCred" = \'D\' THEN (T0."SYSDeb"-T0."SYSCred")-ifnull(T3."ReconSumSC",0)
// WHEN T0."DebCred" = \'C\' THEN -((T0."SYSCred"-T0."SYSDeb")-ifnull(T3."ReconSumSC",0)) ELSE 0 END as "BalanceDueSC",
//
//CASE WHEN T0."DebCred" = \'D\' THEN (T0."FCDebit"-T0."FCCredit")-ifnull(T3."ReconSumFC",0)
// WHEN T0."DebCred" = \'C\' THEN -((T0."FCCredit"-T0."FCDebit")-ifnull(T3."ReconSumFC",0)) ELSE 0  END as "BalanceDueFC"
//
//FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0
//
//JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
//JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode"
//LEFT JOIN (SELECT SUM("ReconSum") AS "ReconSum",SUM("ReconSumSC") AS "ReconSumSC",SUM("ReconSumFC") AS "ReconSumFC","TransRowId","TransId" FROM AL_YASEEN_AGRI_PLIVE.ITR1 T0 JOIN AL_YASEEN_AGRI_PLIVE.OITR T1 ON T0."ReconNum" = T1."ReconNum" AND T1."ReconDate" <= \''.$this->end_date.'\' GROUP BY "TransRowId","TransId") T3 ON T0."TransId" = T3."TransId"
//AND T0."Line_ID" = T3."TransRowId"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRG T4 ON T2."GroupCode" = T4."GroupCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCTG T5 ON T2."GroupNum" = T5."GroupNum"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRY T6 ON T2."Country"=T6."Code"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OACT T8 ON T0."Account"=T8."AcctCode"
//LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP T9 ON T2."SlpCode" = T9."SlpCode"
//
//WHERE (T0."RefDate" <= \''.$end_date.'\')
//--AND T2."CardCode" = \'0100412\'
//) T0
//
//GROUP BY T0."CardCode",T0."CardName",T0."CreditLine",T0."Balance", T0."SlpCode", T0."OldSlpCode", T0."SlpName", T0."BPLId",
//T0."BPLName"
//
//) F0
//
//LEFT JOIN(
//SELECT T0."CardCode",
//SUM(T0."CheckSum") AS "PDC"
//FROM AL_YASEEN_AGRI_PLIVE.OPDF T0
//JOIN AL_YASEEN_AGRI_PLIVE.PDF1 T1 ON T0."DocEntry" = T1."DocNum"
//WHERE T0."ObjType" = \'24\' AND
//(T0."DocDate" BETWEEN \''.$start_date.'\' AND \''.$end_date.'\')
//
//GROUP BY T0."CardCode"
//)F1 ON F0."CardCode"=F1."CardCode"
//--WHERE F0."CardCode" = \'0100412\'
//ORDER BY F0."CardCode") as tbl1
//WHERE tbl1."BPLId" = \''.$branch.'\'
//
//GROUP BY tbl1."BPLId",
//tbl1."BPLName",
//tbl1."SlpCode",
//tbl1."OldSlpCode",
//tbl1."SlpName"
//) as "BalanceDue_tbl" ON "BalanceDue_tbl"."SlpCode" = "category_tbl"."SlpCode"';

            $sql = 'SELECT * FROM (
SELECT "SlpCode", "SlpName", "BranchName", "BranchCode", "BranchRegistrationNumber", SUM("NetSalesAmountLC") AS "NetSalesAmountLC", SUM(CASE WHEN "PmtType" = \'CASH\' THEN "NetSalesAmountLC" END) AS "CashTotalNEW", SUM(CASE WHEN "PmtType" = \'CREDIT\' THEN "NetSalesAmountLC" END) AS "CreditTotalNEW"  FROM (

SELECT
(SELECT CASE WHEN (COUNT(CASE WHEN T0."DocDate" = T4."DocDate" AND T0."DocTotal" >= T4."DocTotal" THEN \'\' END)) = 1 THEN \'CASH\' ELSE \'CREDIT\' END AS "PaymentType"
FROM AL_YASEEN_AGRI_PLIVE.OINV T0
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T1."CardCode" = T0."CardCode"
JOIN AL_YASEEN_AGRI_PLIVE.OSLP T2 ON T1."SlpCode" = T2."SlpCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.RCT2 T3 ON T0."DocEntry"=T3."baseAbs"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORCT T4 ON T3."DocNum" = T4."DocEntry"
WHERE T0."DocNum" = T1."DocumentNumber") as "PmtType"
,TS."SlpCode", TS."SlpName", (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType", T1.* FROM (
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
/*
UNION ALL


SELECT \'0\',\'0\',\'0\',T0."BPLName", T0."BPLId", \'0\',\'0\',\'0\',\'0\', T0."CardName", T0."CardCode",
\'0\', T0."DocDate", T0."DocNum",\'0\',\'0\',\'0\',\'0\',\'0\', T1."ItemCode", T1."Dscription",
T0."SlpCode", \'0\', \'0\',\'0\',
(T1."INMPrice"*T1."Quantity"), (T1."INMPrice"*T1."Quantity"),
\'0\',\'0\',\'0\',\'0\'
from AL_YASEEN_AGRI_PLIVE.ODPI T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.DPI1 T1 ON T0."DocEntry" = T1."DocEntry"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."CardCode" = T2."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
WHERE

T0."DocDate" >= \''.$start_date.'\' AND T0."DocDate" <= \''.$end_date.'\'
--AND T1."DocEntry" not in (175,367)
AND T1."DocEntry" not in (175,367,359,360,368,364,362,378,379)*/
)
WHERE "BranchCode" = '.$branch.'
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName", "BranchName", "BranchCode", "BranchRegistrationNumber"

) AS "cash_tbl"
--- S0 Sales NEW

LEFT JOIN(
SELECT "SlpCode", "SlpName", SUM("NetSalesAmountLC") AS "S0 Sales NEW"  FROM (
SELECT TS."SlpCode", TS."SlpName",(SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
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
WHERE T2."QryGroup1" = \'Y\'

/*
UNION ALL


SELECT \'0\',\'0\',T0."BPLName", T0."BPLId", \'0\',\'0\',\'0\',\'0\', T0."CardName", T0."CardCode",
\'0\', T0."DocDate", T0."DocNum",\'0\',\'0\',\'0\',\'0\',\'0\', T1."ItemCode", T1."Dscription",
T0."SlpCode", \'0\', \'0\',\'0\',
(T1."INMPrice"*T1."Quantity"), (T1."INMPrice"*T1."Quantity"),
\'0\',\'0\',\'0\',\'0\'
from AL_YASEEN_AGRI_PLIVE.ODPI T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.DPI1 T1 ON T0."DocEntry" = T1."DocEntry"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."CardCode" = T2."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
WHERE T3."QryGroup1" = \'Y\'
AND T0."DocDate" >= \''.$start_date.'\' AND T0."DocDate" <= \''.$end_date.'\'
--AND T1."DocEntry" not in (175,367)
AND T1."DocEntry" not in (175,367,359,360,368,364,362,378,379)*/
)

WHERE "BranchCode" = '.$branch.'
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName"
--AND "SalesEmployeeOrBuyerNumber" = 327
) as "speciality0_tbl" ON "cash_tbl"."SlpCode" = "speciality0_tbl"."SlpCode"

--- End Sales S0 New

--- S1 Sales NEW
LEFT JOIN(
SELECT "SlpCode", "SlpName", SUM("NetSalesAmountLC") AS "S1 Sales NEW"  FROM (
SELECT TS."SlpCode", TS."SlpName",(SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType", T1.* FROM (
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
WHERE T2."QryGroup2" = \'Y\'
/*
UNION ALL


SELECT \'0\',\'0\',T0."BPLName", T0."BPLId", \'0\',\'0\',\'0\',\'0\', T0."CardName", T0."CardCode",
\'0\', T0."DocDate", T0."DocNum",\'0\',\'0\',\'0\',\'0\',\'0\', T1."ItemCode", T1."Dscription",
T0."SlpCode", \'0\', \'0\',\'0\',
(T1."INMPrice"*T1."Quantity"), (T1."INMPrice"*T1."Quantity"),
\'0\',\'0\',\'0\',\'0\'
from AL_YASEEN_AGRI_PLIVE.ODPI T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.DPI1 T1 ON T0."DocEntry" = T1."DocEntry"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."CardCode" = T2."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
WHERE T3."QryGroup2" = \'Y\'
AND T0."DocDate" >= \''.$start_date.'\' AND T0."DocDate" <= \''.$end_date.'\'
--AND T1."DocEntry" not in (175,367)
AND T1."DocEntry" not in (175,367,359,360,368,364,362,378,379)*/
)

WHERE "BranchCode" = '.$branch.'
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName"
--AND "SalesEmployeeOrBuyerNumber" = 327
) as "speciality1_tbl" ON "cash_tbl"."SlpCode" = "speciality1_tbl"."SlpCode"
-- END S1 Sales NEW
-- S2 Sales NEW
LEFT JOIN(
SELECT "SlpCode", "SlpName", SUM("NetSalesAmountLC") AS "S2 Sales NEW"  FROM (
SELECT TS."SlpCode", TS."SlpName", (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType", T1.* FROM (
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
WHERE T2."QryGroup3" = \'Y\'
/*
UNION ALL


SELECT \'0\',\'0\', T0."BPLName", T0."BPLId", \'0\',\'0\',\'0\',\'0\', T0."CardName", T0."CardCode",
\'0\', T0."DocDate", T0."DocNum",\'0\',\'0\',\'0\',\'0\',\'0\', T1."ItemCode", T1."Dscription",
T0."SlpCode", \'0\', \'0\',\'0\',
(T1."INMPrice"*T1."Quantity"), (T1."INMPrice"*T1."Quantity"),
\'0\',\'0\',\'0\',\'0\'
from AL_YASEEN_AGRI_PLIVE.ODPI T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.DPI1 T1 ON T0."DocEntry" = T1."DocEntry"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."CardCode" = T2."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
WHERE T3."QryGroup3" = \'Y\'
AND T0."DocDate" >= \''.$start_date.'\' AND T0."DocDate" <= \''.$end_date.'\'
--AND T1."DocEntry" not in (175,367)
AND T1."DocEntry" not in (175,367,359,360,368,364,362,378,379)*/
)

WHERE "BranchCode" = '.$branch.'
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName"
--AND "SalesEmployeeOrBuyerNumber" = 327
) as "speciality2_tbl" ON "cash_tbl"."SlpCode" = "speciality2_tbl"."SlpCode"

------- END S2 Sales NEW

---- Groups Sales
LEFT JOIN(
SELECT "SlpCode", "SlpName",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'11%\' THEN "ItemCode" END) AS "FertilizersCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'11%\' THEN "NetSalesAmountLC" END) AS "Fertilizers NEW",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'12%\' THEN "ItemCode" END) AS "ChemicalsCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'12%\' THEN "NetSalesAmountLC" END) AS "Chemicals NEW",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'13%\' THEN "ItemCode" END) AS "SeedsCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'13%\' THEN "NetSalesAmountLC" END) AS "Seeds NEW",
COUNT(DISTINCT CASE WHEN "ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\' THEN "ItemCode" END) AS "OthersCount NEW",
SUM(CASE WHEN "ItemCode" LIKE \'14%\' OR "ItemCode" LIKE \'15%\' OR "ItemCode" LIKE \'16%\' OR "ItemCode" LIKE \'28%\' OR "ItemCode" LIKE \'29%\' OR "ItemCode" LIKE \'30%\' THEN "NetSalesAmountLC" END) AS "Others NEW"

FROM (
SELECT TS."SlpCode", TS."SlpName",(SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0 INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry" LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry" AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203 LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry" WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203 GROUP BY TBL0."DocNum") as "InvType", T1.* FROM (
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
--WHERE T2."QryGroup3" = \'Y\'
/*
UNION ALL


SELECT \'0\',\'0\',T0."BPLName", T0."BPLId", \'0\',\'0\',\'0\',\'0\', T0."CardName", T0."CardCode",
\'0\', T0."DocDate", T0."DocNum",\'0\',\'0\',\'0\',\'0\',\'0\', T1."ItemCode", T1."Dscription",
T0."SlpCode", \'0\', \'0\',\'0\',
(T1."INMPrice"*T1."Quantity"), (T1."INMPrice"*T1."Quantity"),
\'0\',\'0\',\'0\',\'0\'
from AL_YASEEN_AGRI_PLIVE.ODPI T0
LEFT JOIN AL_YASEEN_AGRI_PLIVE.DPI1 T1 ON T0."DocEntry" = T1."DocEntry"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."CardCode" = T2."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
WHERE --T3."QryGroup3" = \'Y\'
--AND T0."DocDate" >= \''.$start_date.'\' AND T0."DocDate" <= \''.$end_date.'\'
--AND T1."DocEntry" not in (175,367)
AND T1."DocEntry" not in (175,367,359,360,368,364,362,378,379)*/
)

WHERE "BranchCode" = '.$branch.'
AND "InvType" IS NULL

GROUP BY "SlpCode", "SlpName"
--AND "SalesEmployeeOrBuyerNumber" = 327
) as "grp_tbl" ON "cash_tbl"."SlpCode" = "grp_tbl"."SlpCode"
---- End Groups Sales
----- Start Of Aging Dues -----
LEFT JOIN (

SELECT "SlpCode", "SlpName", "OldSlpCode", SUM("121+") as "121+", SUM("Balance Due") as "Balance Due" FROM (
SELECT "BusinessPartnerCode", "BusinessPartnerName", OS."SlpCode", OS."Memo" as "OldSlpCode", OS."SlpName", IFNULL("0-30",0) as "0-30", IFNULL("31-60",0) as "31-60", IFNULL("61-90",0) as "61-90", IFNULL("91-120",0) as "91-120", IFNULL("121+",0) "121+", (IFNULL("0-30",0)+IFNULL("31-60",0)+IFNULL("61-90",0)+IFNULL("91-120",0)+IFNULL("121+",0)) as "Balance Due" FROM (

SELECT "BusinessPartnerCode", "BusinessPartnerName", SUM(CASE WHEN "days" >=0 AND "days" <= 30 THEN "AgingBalanceDueLC" END) as "0-30", SUM(CASE WHEN "days" >=31 AND "days" <= 60 THEN "AgingBalanceDueLC" END) as "31-60", SUM(CASE WHEN "days" >=61 AND "days" <= 90 THEN "AgingBalanceDueLC" END) as "61-90", SUM(CASE WHEN "days" >=91 AND "days" <= 120 THEN "AgingBalanceDueLC" END) as "91-120", SUM(CASE WHEN "days" >=121 OR "days" < 0 THEN "AgingBalanceDueLC" END) as "121+" FROM (

select DAYS_BETWEEN( "PostingDate", \''.Carbon::today()->format('Y-m-d').'\') as "days", * from "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery"

)

GROUP BY "BusinessPartnerCode", "BusinessPartnerName"--, "BranchName"
) AG
--ORDER BY "BusinessPartnerCode"

LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD OC ON AG."BusinessPartnerCode" = OC."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP OS ON OC."SlpCode" = OS."SlpCode"
--LEFT JOIN OBPL T1 ON T1."BPLName" = AG."BranchName"
--WHERE "BusinessPartnerCode" LIKE \'01%\'
--WHERE T1."BPLId" = 3
--WHERE T1."BPLId" IS NOT NULL
--AND "Balance Due" > 0

--and OS."SlpCode" = 292
ORDER BY "BusinessPartnerCode"
)
WHERE "Balance Due" > 0
GROUP BY "SlpCode", "SlpName", "OldSlpCode"


) as "aging_dues_tbl" ON "cash_tbl"."SlpCode" = "aging_dues_tbl"."SlpCode"

----- End of Aging Dues -------
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
                // echo odbc_num_rows($result);
//                dd(odbc_fetch_row($result));
//                $aa = odbc_result_all($result, "border=1");
//                $x = odbc_fetch_object($result);
//                $this->sap_results
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
                    array_push($this->emp_codes, $row['OldSlpCode']);
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
