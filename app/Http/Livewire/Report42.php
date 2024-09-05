<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report42 extends Component
{
    public $dept_id = ["dept_all"];
    public $show_msg = false;

    public $start_date;
    public $end_date;

    public $year;

    public $scribes_results = [];
    public $query;
    public $branches;

    protected $listeners = ['create-report' => 'create_report'];

    public function booted() {


        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('report-42', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mount() {

        $this->query = User::where('id', Auth::id())->first();
        $this->branches = json_decode($this->query->branches);
    }


    public function render()
    {
        return view('livewire.report42')
            ->layout('layouts.dashboard');
    }

    public function create_report($start_date, /*$end_date,*/ $dept_id) {

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
        $this->scribes_results = [];
        $this->sap_results = [];

        $this->year = Carbon::parse($start_date)->year;

        if (in_array("dept_all", $this->dept_id)) {
            $this->dept_id = $this->branches;
        }

        $start_date = Carbon::parse($start_date)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::parse($start_date)->endOfMonth()->format('Y-m-d');

        $this->start_date = $start_date;
        $this->end_date = $end_date;

        ///////////////


//        $this->scribesQuery($start_date, $end_date, $dept_id);
        /*
        if (is_null($start_date) == false && is_null($end_date) == false) {

            if ($start_date >= '2011-07-01' && $end_date <= '2023-12-31') {
                $this->scribesQuery($start_date, $end_date, $dept_id, $sp_type);
            }
            elseif ($start_date > '2023-12-31' && $end_date > '2023-12-31') {
                $this->sapQuery($start_date, $end_date, $dept_id);
            }
            elseif ($start_date >= '2011-07-01' && $end_date > '2023-12-31') {
                $this->scribesQuery($start_date, '2023-12-31', $dept_id, $sp_type);
                $this->sapQuery('2024-01-01', $end_date, $dept_id);
            }


        }
        else {
            dd('coco');
        }
        */
        // end dates

        if (is_null($start_date) == false && is_null($end_date) == false) {

//            if ($start_date > '2025-12-31' && $end_date > '2025-12-31') {
            if ($this->year >= 2026) {
//                dd('2026');
                // only sap
//                dd('only sap');
//                $this->scribesQuery($start_date, $end_date, $dept_id);
                $this->sapQuery($start_date, $end_date, $dept_id);
            }
            elseif ($this->year <= 2023) {
//                dd('2023');
//            elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31') {
                // only scribes
//                dd('only scribes');
                $this->scribesQuery($start_date, $end_date, $dept_id);
                //                $this->sapQuery($start_date, $end_date, $dept_id);
            }
            elseif ($this->year == 2024) {
//                dd('2024');
//            elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31') {
                // only scribes
//                dd('only scribes');


                $this->scribes_results = [];
                $this->sap_results = [];

                $this->scribesQuery2024($start_date, $end_date, $dept_id);
                $this->sapQuery2024($start_date, $end_date, $dept_id);

                $this->scribes_results = collect($this->scribes_results);
                $this->sap_results = collect($this->sap_results);
//                    $x = $this->scribes_results->merge($this->sap_results);
//                    dd($x);

                $this->merged = $this->scribes_results->merge($this->sap_results)
                    ->groupBy('Code')
                    ->values()
                    ->all();
//                dd($this->merged);
            }
            elseif ($this->year == 2025) {
//                dd('2025');
//            elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31') {
                // only scribes
//                dd('only scribes');


                $this->scribes_results = [];
                $this->sap_results = [];

                $this->scribesQuery($start_date, $end_date, $dept_id);
                $this->sapQuery($start_date, $end_date, $dept_id);

                $this->scribes_results = collect($this->scribes_results);
                $this->sap_results = collect($this->sap_results);
//                    $x = $this->scribes_results->merge($this->sap_results);
//                    dd($x);

                $this->merged = $this->scribes_results->merge($this->sap_results)
                    ->groupBy('Code')
                    ->values()
                    ->all();
//                    dd($this->merged);
            }
//            else {
//                // scribes & sap
////                dd('scribes & sap');
////                $this->scribesQuery($start_date, '2023-12-31', $dept_id);
////                $this->sapQuery('2024-01-01', $end_date, $dept_id);
//
////                $this->sapQuery($start_date, $end_date, $dept_id); // this one is good
//
////                if($start_date <= '2023-12-31' && $end_date >= '2024-01-01') {
////                if(Carbon::parse('2024-01-01')->between($start_date, $end_date)) {
////                    dd('here');
//
//                $start_date_scribe = Carbon::parse($start_date)->subYear()->startOfMonth()->format('Y-m-d');
//                $end_date_scribe = Carbon::parse($start_date)->subYear()->endOfMonth()->format('Y-m-d');
//
//
//                $start_date_year = Carbon::parse($start_date)->endOfMonth()->subYear()->format('Y-m-d');
//                $end_date_year = Carbon::parse($start_date)->endOfMonth()->format('Y-m-d');
//
//                $start_date_year_scribe = null;
//                $end_date_year_scribe = null;
//
//                $start_date_year_sap = null;
//                $end_date_year_sap = null;
//
//                $dateToCheck = Carbon::parse('2024-01-01');
//                if ($dateToCheck->between($start_date_year, $end_date_year)) {
//                    $start_date_year_scribe = $start_date_year;
//                    $end_date_year_scribe = '2023-12-31';
//
//                    $start_date_year_sap = '2024-01-01';
//                    $end_date_year_sap = $end_date_year;
//                }
//
//
////                $this->start_date = $start_date;
////                $this->end_date = $end_date;
//
//                    $this->scribesQueryBetween($start_date_scribe, $end_date_scribe,$start_date_year_scribe, $end_date_year_scribe, $dept_id);
////                    $this->scribesQuery($start_date, '2023-12-31', $dept_id); // good
////                    $this->sapQuery('2024-01-01', $end_date, $dept_id); // this one is good
//                    $this->sapQuery($start_date, $end_date, $dept_id); // this one is good
////                    dd($this->sap_results);
//                    $this->scribes_results = collect($this->scribes_results);
//                    $this->sap_results = collect($this->sap_results);
////                    $x = $this->scribes_results->merge($this->sap_results);
////                    dd($x);
//
//                    $this->merged = $this->scribes_results->merge($this->sap_results)
//                        ->groupBy('Code')
////                        ->map(function ($items) {
////                            return array_merge(...$items->toArray());
////                        })
//                        ->values()
//                        ->all();
////                    dd($this->merged);
////
////                    dd($this->sap_results->where('Code', '0101'));
////                    dd($this->scribes_results->where('Code', '0101'));
//
//
//
////                    $t2 =
////                    floatval($record["S1 Sales"]);
//
////                    dd($this->scribes_results[0]->SP1Sales);
////                    dd($this->sap_results[0]["S1 Sales"]);
//
////                    dd("range 1: ${start_date} -> 2023-12-31  ||  range 2: 2024-01-01 -> ${end_date}");
//
////                }
////                else {
////                    dd("range 1: ${start_date} ->  ${end_date}");
////                }
//            }

        }
        else {
            dd('coco');
        }




        /// /////////////////////////

        $this->show_msg = true;
        $this->emit('finished');
    }

    public function scribesQuery($start_date, $end_date, $departments) {

//        dd($departments);
        if (in_array('dept_all', $departments)) {
            $departments = ["3", "10","7","13","4","6","5","12","11","9","8","505"];
        }

//        dd( implode(', ', $departments));

        /*
        if(Carbon::parse('2024-01-01')->between($start_date, $end_date)) {

            dd("range 1: ${start_date} -> 2023-12-31  ||  range 2: 2024-01-01 -> ${end_date}");

        }
        else {
            dd("range 1: ${start_date} ->  ${end_date}");
        }
        */

//        dd(Carbon::parse('06/30/2024')->addMonths(-4)->format("Y-m-d"));
//        dd(Carbon::parse('2023-12-15')->between($start_date, $end_date));

//        dd(Carbon::parse($end_date)->subYears(2)->format('Y-m-d'));

//        $previous_start_date = Carbon::parse($this->start_date)->subYear()->format('Y-m-d');
//        $previous_end_date = Carbon::parse($this->end_date)->subYear()->format('Y-m-d');
//        $previous_2_end_date = Carbon::parse($this->end_date)->subYears(2)->format('Y-m-d');
//        $due_date = Carbon::parse($this->end_date)->addMonths(-4)->format("Y-m-d");

        $previous_start_date = Carbon::parse($start_date)->subYear()->format('Y-m-d');
        $previous_end_date = Carbon::parse($end_date)->subYear()->format('Y-m-d');
        $previous_2_end_date = Carbon::parse($end_date)->subYears(2)->format('Y-m-d');
        $due_date = Carbon::parse($end_date)->addMonths(-4)->format("Y-m-d");


//        dd($end_date);
//        dd($previous_2_end_date);
//        dd($previous_2_end_date);
//        dd($previous_end_date);
//        dd($previous_start_date);


//        $scribesStmt = "select NodeNo,Code,name,arabic_name ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='".$start_date."' and SIDate<='".$end_date." 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1Sales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-' ) as SP1SalesReturn  ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-' ) as SP1SalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSalesIncrease ,(select
//sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And
//ActualVoucherprefix='SRT-') as SP1YearSalesReturnIncrease ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
//SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
//Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )   and cashonly=0 And ActualVoucherprefix='SIV-') as
//SPYearSalesNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and
//PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
//(DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnNotCash ,(select
//sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And
//ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423)
//And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SIV-') as SPYearSalesIncreaseNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno
//and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnIncreaseNotCash ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPCashSales ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSales ,isnull((select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in
//('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSalesIncrease ,isnull((select sum(value+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and
//DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And
//ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
//SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
//Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2Sales ,(select
//sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And
//ActualVoucherprefix='SRT-') as SP2SalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<=
//'" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
//(DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2SalesIncrease ,(select sum(value+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSales,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturnIncrease  /*into CR_DivisionsAnalysis0*/ from Areamast where [group]=0  And NodeNo In (3,6 )";

    $scribesStmt = "
Select * ,(select SUM(Balance) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments).") and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  ) as DebitCustomers ,isNull((select sum(Total+Paid) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments)." ) and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  and (total+paid)>0 and  voucherdate<'".$due_date."'),0) as DueBalance  ,(select sum(TotalCost)
from Pinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno    and DefAccounts.Area = tbl1.Nodeno and
(DonotUpdateStock=0 or productno=10423) And PIdate <='" . $end_date . " 23:59:25') as InpuCost, (select sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where
Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno and (DonotUpdateStock=0 or productno=10423) And
(Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate <='" . $end_date . " 23:59:25' ) as OutPutCost , (select
sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno
and (DonotUpdateStock=0 or productno=10423)  And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate
<='" . $end_date . " 23:59:25' And SIdate>'" . $previous_end_date . "') as OutPutCostYear  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast    where
accmast.nodeno=accountdr  and     Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) ),0)  as TotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and           Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as COGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast   where accmast.nodeno=accountdr  and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(2,4) ),0)  as TotalIncome  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast   where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) ),0)  as YearTotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and         Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . "  23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as YearCOGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast    where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(2,4) ),0)  as YearTotalIncome  /*into CR_DivisionsAnalysis*/ from --CR_DivisionsAnalysis0
(


select NodeNo,Code,name,arabic_name ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='".$start_date."' and SIDate<='".$end_date." 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1Sales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-' ) as SP1SalesReturn  ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-' ) as SP1SalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSalesIncrease ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And
            ActualVoucherprefix='SRT-') as SP1YearSalesReturnIncrease ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )   and cashonly=0 And ActualVoucherprefix='SIV-') as
    SPYearSalesNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and
            PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnNotCash ,(select
    sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And
            ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423)
            And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SIV-') as SPYearSalesIncreaseNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno
            and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnIncreaseNotCash ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPCashSales ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSales ,isnull((select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in
            ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSalesIncrease ,isnull((select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and
            DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And
            ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2Sales ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And
            ActualVoucherprefix='SRT-') as SP2SalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<=
            '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2SalesIncrease ,(select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSales,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturnIncrease  /*into CR_DivisionsAnalysis0*/ from Areamast where [group]=0  And NodeNo In (".implode(',', $departments)." )


    ) as tbl1";


//        dd($scribesStmt);

            $query = DB::connection('sqlsrv')->select($scribesStmt);

//            dd($query);

            $this->scribes_results = $query;
    }
    public function scribesQuery2024($start_date, $end_date, $departments) {

//        dd($departments);
        if (in_array('dept_all', $departments)) {
            $departments = ["3", "10","7","13","4","6","5","12","11","9","8","505"];
        }

//        dd( implode(', ', $departments));

        /*
        if(Carbon::parse('2024-01-01')->between($start_date, $end_date)) {

            dd("range 1: ${start_date} -> 2023-12-31  ||  range 2: 2024-01-01 -> ${end_date}");

        }
        else {
            dd("range 1: ${start_date} ->  ${end_date}");
        }
        */

//        dd(Carbon::parse('06/30/2024')->addMonths(-4)->format("Y-m-d"));
//        dd(Carbon::parse('2023-12-15')->between($start_date, $end_date));

//        dd(Carbon::parse($end_date)->subYears(2)->format('Y-m-d'));

//        $previous_start_date = Carbon::parse($this->start_date)->subYear()->format('Y-m-d');
//        $previous_end_date = Carbon::parse($this->end_date)->subYear()->format('Y-m-d');
//        $previous_2_end_date = Carbon::parse($this->end_date)->subYears(2)->format('Y-m-d');
//        $due_date = Carbon::parse($this->end_date)->addMonths(-4)->format("Y-m-d");

        $previous_start_date = Carbon::parse($start_date)->subYear()->format('Y-m-d');
        $previous_end_date = Carbon::parse($end_date)->subYear()->format('Y-m-d');
        $previous_2_end_date = Carbon::parse($end_date)->subYears(2)->format('Y-m-d');
        $due_date = Carbon::parse($end_date)->addMonths(-4)->format("Y-m-d");


//        dd($end_date);
//        dd($previous_2_end_date);
//        dd($previous_2_end_date);
//        dd($previous_end_date);
//        dd($previous_start_date);


//        $scribesStmt = "select NodeNo,Code,name,arabic_name ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='".$start_date."' and SIDate<='".$end_date." 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1Sales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-' ) as SP1SalesReturn  ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-' ) as SP1SalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSalesIncrease ,(select
//sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And
//ActualVoucherprefix='SRT-') as SP1YearSalesReturnIncrease ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
//SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
//Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )   and cashonly=0 And ActualVoucherprefix='SIV-') as
//SPYearSalesNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and
//PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
//(DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnNotCash ,(select
//sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And
//ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423)
//And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SIV-') as SPYearSalesIncreaseNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno
//and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnIncreaseNotCash ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPCashSales ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSales ,isnull((select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in
//('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSalesIncrease ,isnull((select sum(value+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and
//DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And
//ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
//SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
//Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2Sales ,(select
//sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
//and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And
//ActualVoucherprefix='SRT-') as SP2SalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<=
//'" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
//(DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2SalesIncrease ,(select sum(value+ExtraFieldsTotal) from
//ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSales,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturnIncrease  /*into CR_DivisionsAnalysis0*/ from Areamast where [group]=0  And NodeNo In (3,6 )";

        $scribesStmt = "
Select * ,(select SUM(Balance) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments).") and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  ) as DebitCustomers ,isNull((select sum(Total+Paid) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments)." ) and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  and (total+paid)>0 and  voucherdate<'".$due_date."'),0) as DueBalance  ,(select sum(TotalCost)
from Pinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno    and DefAccounts.Area = tbl1.Nodeno and
(DonotUpdateStock=0 or productno=10423) And PIdate <='" . $end_date . " 23:59:25') as InpuCost, (select sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where
Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno and (DonotUpdateStock=0 or productno=10423) And
(Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate <='" . $end_date . " 23:59:25' ) as OutPutCost , (select
sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno
and (DonotUpdateStock=0 or productno=10423)  And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate
<='" . $end_date . " 23:59:25' And SIdate>'" . $previous_end_date . "') as OutPutCostYear  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast    where
accmast.nodeno=accountdr  and     Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) ),0)  as TotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and           Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as COGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast   where accmast.nodeno=accountdr  and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(2,4) ),0)  as TotalIncome  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast   where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='2023-12-31 23:59:25' and accmast.[type] in(3) ),0)  as YearTotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and         Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . "  23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as YearCOGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast    where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='2023-12-31 23:59:25' and accmast.[type] in(2,4) ),0)  as YearTotalIncome  /*into CR_DivisionsAnalysis*/ from --CR_DivisionsAnalysis0
(


select NodeNo,Code,name,arabic_name ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='".$start_date."' and SIDate<='".$end_date." 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1Sales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-' ) as SP1SalesReturn  ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-' ) as SP1SalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='2023-12-31 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='2023-12-31 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSalesIncrease ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And
            ActualVoucherprefix='SRT-') as SP1YearSalesReturnIncrease ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )   and cashonly=0 And ActualVoucherprefix='SIV-') as
    SPYearSalesNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and
            PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnNotCash ,(select
    sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And
            ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423)
            And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SIV-') as SPYearSalesIncreaseNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno
            and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnIncreaseNotCash ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPCashSales ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSales ,isnull((select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in
            ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSalesIncrease ,isnull((select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and
            DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And
            ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2Sales ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And
            ActualVoucherprefix='SRT-') as SP2SalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<=
            '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2SalesIncrease ,(select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='2023-12-31 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSales,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and PIDate<='2023-12-31 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturnIncrease  /*into CR_DivisionsAnalysis0*/ from Areamast where [group]=0  And NodeNo In (".implode(',', $departments)." )


    ) as tbl1";


//        dd($scribesStmt);

        $query = DB::connection('sqlsrv')->select($scribesStmt);

//            dd($query);

        $this->scribes_results = $query;
    }

    public function scribesQueryBetween($start_date, $end_date, $start_date_year, $end_date_year, $departments) {

//        dd($departments);
        if (in_array('dept_all', $departments)) {
            $departments = ["3", "10","7","13","4","6","5","12","11","9","8","505"];
        }

        $previous_start_date = Carbon::parse($start_date)->subYear()->format('Y-m-d');
        $previous_end_date = Carbon::parse($end_date)->subYear()->format('Y-m-d');
        $previous_2_end_date = Carbon::parse($end_date)->subYears(2)->format('Y-m-d');
        $due_date = Carbon::parse($end_date)->addMonths(-4)->format("Y-m-d");


        $scribesStmt = "
Select * ,(select SUM(Balance) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments).") and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  ) as DebitCustomers ,isNull((select sum(Total+Paid) from (Select Areamast.Nodeno,Areamast.code,Accountdr,voucherno,voucherdate,SUM((amountdr-amountcr)*exchangeRate) as Balance,isnull((select sum(total) from billwise where CustomerNo=accountdr and billwise.type='N' and billwise.voucherno not like '030-%%'  and billwise.voucherno=purchasedata.voucherno and voucherdate<='".$end_date." 23:59:25'),0)  as total  ,isnull((select sum(total) from billwise where CustomerNo=accountdr  and billwise.Refrence=purchasedata.voucherno  and voucherdate<'".$end_date."'),0)  as paid  /*into DebitCustomers*/ from purchasedata ,AccMast,Areamast where   Accmast_Department=Areamast.nodeno and  AccountDr=accmast.NodeNo and accmast.Type in (10 ) And voucherdate<='".$end_date." 23:59:25' And purchasedata.DoNotUpdateAccounts=0 And PDC='N'  And Accmast_Department In (".implode(',', $departments)." ) and voucherno in (select voucherno from billwise where accountdr=customerno)  Group by Areamast.Nodeno,Areamast.Code,AccountDr,voucherno,voucherdate ) as DebitCustomers where DebitCustomers.Code = tbl1.Code  and (total+paid)>0 and  voucherdate<'".$due_date."'),0) as DueBalance  ,(select sum(TotalCost)
from Pinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno    and DefAccounts.Area = tbl1.Nodeno and
(DonotUpdateStock=0 or productno=10423) And PIdate <='" . $end_date . " 23:59:25') as InpuCost, (select sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where
Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno and (DonotUpdateStock=0 or productno=10423) And
(Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate <='" . $end_date . " 23:59:25' ) as OutPutCost , (select
sum(TotalCost) from Sinvoice,Deptmast,DefAccounts where Department=Deptmast.nodeno and department=deptnodeno and    DefAccounts.Area = tbl1.Nodeno
and (DonotUpdateStock=0 or productno=10423)  And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and (executed=1 or salesman=17))  ) And SIdate
<='" . $end_date . " 23:59:25' And SIdate>'" . $previous_end_date . "') as OutPutCostYear  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast    where
accmast.nodeno=accountdr  and     Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) ),0)  as TotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and           Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as COGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast   where accmast.nodeno=accountdr  and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>='" . $start_date . "'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(2,4) ),0)  as TotalIncome  , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata,accmast   where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='2023-12-31 23:59:25' and accmast.[type] in(3) ),0)  as YearTotalExpenses     , isnull((select sum((amountdr-amountcr)*exchangerate) from purchasedata, accmast where accmast.nodeno=accountdr and         Area =tbl1.Nodeno and         donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . "  23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(3) and accountdr in (409,410,411)  ),0) as YearCOGS , isnull((select abs(sum((amountdr-amountcr)*exchangerate)) from purchasedata,accmast    where accmast.nodeno=accountdr and Area = tbl1.Nodeno and        donotupdateaccounts=0 and voucherdate>'" . $previous_end_date . " 23:59:25'  and voucherdate <='" . $end_date . " 23:59:25' and accmast.[type] in(2,4) ),0)  as YearTotalIncome  /*into CR_DivisionsAnalysis*/ from --CR_DivisionsAnalysis0
(


select NodeNo,Code,name,arabic_name ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='".$start_date."' and SIDate<='".$end_date." 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1Sales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-' ) as SP1SalesReturn  ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-' ) as SP1SalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $start_date_year . " 23:59:25' and SIDate<='" . $end_date_year . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSales ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $start_date_year . " 23:59:25' and PIDate<='" . $end_date_year . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SRT-') as SP1YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . Carbon::parse($start_date_year)->subYear()->endOfMonth()->format('Y-m-d') . " 23:59:25' and SIDate<= '" . Carbon::parse($end_date_year)->subYear()->format('Y-m-d') . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And ActualVoucherprefix='SIV-') as SP1YearSalesIncrease ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . Carbon::parse($start_date_year)->subYear()->endOfMonth()->format('Y-m-d') . " 23:59:25' and PIDate<= '" . Carbon::parse($end_date_year)->subYear()->format('Y-m-d') . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='1' And
            ActualVoucherprefix='SRT-') as SP1YearSalesReturnIncrease ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>'" . $previous_end_date . " 23:59:25' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )   and cashonly=0 And ActualVoucherprefix='SIV-') as
    SPYearSalesNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . " 23:59:25' and
            PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnNotCash ,(select
    sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . " 23:59:25' and SIDate<= '" . $previous_end_date . " 23:59:25' And
            ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423)
            And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SIV-') as SPYearSalesIncreaseNotCash ,(select sum(value*exchangerate+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . " 23:59:25' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno
            and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' )  and cashonly=0 And ActualVoucherprefix='SRT-') as SPYearSalesReturnIncreaseNotCash ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPCashSales ,isnull((select sum(value*exchangerate+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSales ,isnull((select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturn ,isnull((select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_2_end_date . "' and SIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in
            ('1','2' ) and cashonly=1 And ActualVoucherprefix='SIV-'),0) as SPYearCashSalesIncrease ,isnull((select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_2_end_date . "' and PIDate<='" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and
            DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode in ('1','2' ) and cashonly=1 And
            ActualVoucherprefix='SRT-' ),0) as SYearPCashSalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where
    SIdate>='" . $start_date . "' and SIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and
    Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2Sales ,(select
    sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>='" . $start_date . "' and PIDate<='" . $end_date . " 23:59:25' And  ProductNo=productmast.NodeNo
            and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And
            ActualVoucherprefix='SRT-') as SP2SalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $previous_start_date . "' and SIDate<=
            '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And
            (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2SalesIncrease ,(select sum(value+ExtraFieldsTotal) from
    ALLPInvoice,productmast,DefAccounts where PIdate>'" . $previous_start_date . "' and PIDate<= '" . $previous_end_date . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2SalesReturnIncrease ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . $start_date_year . " 23:59:25' and SIDate<='" . $end_date_year . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSales,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . $start_date_year . " 23:59:25' and PIDate<='" . $end_date_year . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturn ,(select sum(value+ExtraFieldsTotal) from ALLSInvoice,productmast,DefAccounts where SIdate>'" . Carbon::parse($start_date_year)->subYear()->endOfMonth()->format('Y-m-d') . " 23:59:25' and SIDate<= '" . Carbon::parse($end_date_year)->subYear()->format('Y-m-d') . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SIV-') as SP2YearSalesIncrease ,(select sum(value+ExtraFieldsTotal) from ALLPInvoice,productmast,DefAccounts where PIdate>'" . Carbon::parse($start_date_year)->subYear()->endOfMonth()->format('Y-m-d') . " 23:59:25' and PIDate<= '" . Carbon::parse($end_date_year)->subYear()->format('Y-m-d') . " 23:59:25' And  ProductNo=productmast.NodeNo and department=deptnodeno and DefAccounts.Area=areamast.nodeno /*and Area=Areamast.Nodeno*/ And (DonotUpdateStock=0 or productno=10423) And SpecialityCode='2' And ActualVoucherprefix='SRT-') as SP2YearSalesReturnIncrease  /*into CR_DivisionsAnalysis0*/ from Areamast where [group]=0  And NodeNo In (".implode(',', $departments)." )


    ) as tbl1";


//        dd($scribesStmt);

        $query = DB::connection('sqlsrv')->select($scribesStmt);

//            dd($query);

        $this->scribes_results = $query;
    }

    public function sapQuery($start_date, $end_date, $departments) {

//        if (count($this->sap_codes) > 0) {
            $depts = ['3' => '3', '10' => '4', '7' =>'5', '13' =>'6', '4' =>'7', '6' => '8', '5' => '9', '12' => '10', '11' => '11', '9' => '12', '8' => '13', '505' => '14'];
            $sap_depts = [];

            if (in_array('dept_all', $departments)) {
                $sap_depts = $depts;
            }
            else {
                foreach ($departments as $department) {
                    array_push($sap_depts, $depts[$department]);
                }
            }

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

                $sql = 'SELECT

"BPLId",
(SELECT OBPL."TaxIdNum" FROM AL_YASEEN_AGRI_PLIVE.OBPL WHERE OBPL."BPLId" = F0."BPLId") as "Code",
"BPLName",
"Location",
SUM("S1 Sales") AS "S1 Sales",
SUM("S2 Sales") AS "S2 Sales",
SUM("S1 Sales PY") AS "S1 Sales PY",
SUM("S2 Sales PY") AS "S2 Sales PY",
SUM("S1 Sales Year") AS "S1 Sales Year",
SUM("S2 Sales Year") AS "S2 Sales Year",
SUM("S1 Sales Year PY") AS "S1 Sales Year PY",
SUM("S2 Sales Year PY") AS "S2 Sales Year PY",
SUM("Outstanding Receivables") AS "Outstanding Receivables",
SUM("Outstanding Receivables Over 120") AS "Outstanding Receivables Over 120",
SUM("Stock Value") AS "Stock Value",
SUM("Clean Receivables") AS "Clean Receivables",
SUM("COGS") AS "COGS",
SUM("Operating Expenses") AS "Operating Expenses",
SUM("NPAT Period") AS "NPAT Period",
SUM("NPAT Annual") AS "NPAT Annual"

FROM

(

/*Sales Data*/

SELECT
\'Sales\' AS "ROWID",
tbl1."BPLId",
tbl1."BPLName",
tbl1."Location",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between \'' . $start_date . '\' and \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between \'' . $start_date . '\' and \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(\'' . $start_date . '\',-1) and ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales PY",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(\'' . $start_date . '\',-1) and ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales PY",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365) AND \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales Year",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365) AND \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales Year",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365),-1) AND ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales Year PY",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365),-1) AND ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales Year PY",
0 AS "Outstanding Receivables",
0 AS "Outstanding Receivables Over 120",
0 AS "Stock Value",
0 AS "Clean Receivables",
0 AS "COGS",
0 AS "Operating Expenses",
0 AS "NPAT Period",
0 AS "NPAT Annual"

FROM (

SELECT

T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
T0."LineTotal",
T1."DocDate",
"QryGroup1",
"QryGroup2",
"QryGroup3",
T0."BaseRef",
T1."DiscPrcnt"

FROM AL_YASEEN_AGRI_PLIVE.INV1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T1."BPLId" = T3."BPLId"

UNION ALL

SELECT
T2."BPLId",
T2."BPLName",
T2."GlblLocNum" as "Location",
CASE WHEN T0."CANCELED" = \'C\' THEN T1."LineTotal" ELSE -T1."LineTotal" END as "LineTotal",
T1."DocDate",
T3."QryGroup1",
T3."QryGroup2",
T3."QryGroup3",
\'\' as "BaseRef",
0 as "DiscPrcnt"

FROM
    AL_YASEEN_AGRI_PLIVE.ORIN T0
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.RIN1 T1 ON T0."DocEntry" = T1."DocEntry"
JOIN
AL_YASEEN_AGRI_PLIVE.OBPL T2 ON T0."BPLId" = T2."BPLId"
JOIN
AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
JOIN
AL_YASEEN_AGRI_PLIVE.OCRD T4 ON T3."CardCode" = T4."CardCode"

) as tbl1

GROUP BY

tbl1."BPLId",
tbl1."BPLName",
tbl1."Location"

/* End of Sales Data*/

UNION ALL

/*JDT Data*/

SELECT

\'Receivables\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),
SUM(
CASE WHEN DAYS_BETWEEN(T0."DueDate",\'' . $end_date . '\') >= 120 THEN (
(CASE WHEN T0."DebCred" = \'D\' THEN (T0."Debit"-T0."Credit")-ifnull(T4."ReconSum",0)
WHEN T0."DebCred" = \'C\' THEN -((T0."Credit"-T0."Debit")-ifnull(T4."ReconSum",0)) END)) ELSE 0 END)
,0,0,0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode" AND T2."CardType" = \'C\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"
LEFT JOIN (SELECT SUM("ReconSum") AS "ReconSum",SUM("ReconSumSC") AS "ReconSumSC",SUM("ReconSumFC") AS "ReconSumFC","TransRowId","TransId" FROM AL_YASEEN_AGRI_PLIVE.ITR1 T0 JOIN AL_YASEEN_AGRI_PLIVE.OITR T1 ON T0."ReconNum" = T1."ReconNum" AND T1."ReconDate" <= \'' . $end_date . '\'
GROUP BY "TransRowId","TransId") T4 ON T0."TransId" = T4."TransId" AND T0."Line_ID" = T4."TransRowId"

WHERE T1."RefDate" <= \'' . $end_date . '\'

GROUP BY

T3."BPLId",
T0."DebCred",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

/*Stock Data*/

SELECT

\'Stock\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),0,0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\' AND T2."AcctCode" = \'1203010001\'

GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

/*JDT Data*/

SELECT

\'Clean Receivables\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode" AND T2."CardType" = \'C\' AND T2."QryGroup1" = \'N\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'

GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'COGS\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,SUM(CASE WHEN T1."RefDate" between ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365) AND \'' . $end_date . '\' THEN (T0."Debit"-T0."Credit") ELSE 0 END),0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T4 ON T2."FatherNum" = T4."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T5 ON T4."FatherNum" = T5."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T6 ON T5."FatherNum" = T6."AcctCode" AND T6."AcctCode" = \'51\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'Operating Expenses\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,SUM(T0."Debit"-T0."Credit"),0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T4 ON T2."FatherNum" = T4."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T5 ON T4."FatherNum" = T5."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T6 ON T5."FatherNum" = T6."AcctCode" AND T2."GroupMask" IN (5,6) AND T6."AcctCode" <> \'51\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" between \'' . $start_date . '\' AND \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'NPAT Period\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,0,SUM(T0."Debit"-T0."Credit"),0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode" AND T2."GroupMask" > 3
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" between \'' . $start_date . '\' AND \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'NPAT Annual\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,0,SUM(T0."Debit"-T0."Credit"),
SUM(CASE WHEN T1."RefDate" between ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365) AND \'' . $end_date . '\' THEN (T0."Debit"-T0."Credit") ELSE 0 END)

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode" AND T2."GroupMask" > 3
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

) F0

WHERE F0."BPLId" IN (SELECT "BPLId" FROM AL_YASEEN_AGRI_PLIVE.USR6 T0 JOIN AL_YASEEN_AGRI_PLIVE.OUSR T1 ON T0."UserCode" = T1."USER_CODE" WHERE "UserCode" = \'ctc90010.2\')
AND F0."BPLId" IN ('. implode(', ', $sap_depts).')

GROUP BY

"BPLId",
"BPLName",
"Location"
ORDER BY "BPLId"';


//                dd($sql);
                $result = odbc_exec($conn, $sql);
                if (!$result)
                {
                    echo "Error while sending SQL statement to the database server.\n";
                    echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
                }
                else
                {
//                dd(odbc_fetch_array($result));

                    while ($row = odbc_fetch_array($result)) {
                        array_push($this->sap_results, $row);
                    }

                }
                odbc_close($conn);
            }
//        }

    }

    public function sapQuery2024($start_date, $end_date, $departments) {

//        if (count($this->sap_codes) > 0) {
        $depts = ['3' => '3', '10' => '4', '7' =>'5', '13' =>'6', '4' =>'7', '6' => '8', '5' => '9', '12' => '10', '11' => '11', '9' => '12', '8' => '13', '505' => '14'];
        $sap_depts = [];

        if (in_array('dept_all', $departments)) {
//            dd($this->dept_id);
//            $sap_depts = $depts;
            $sap_depts = $this->dept_id;
        }
        else {
            foreach ($departments as $department) {
                array_push($sap_depts, $depts[$department]);
            }
        }

//        dd(implode(', ', $sap_depts));
//        dd($sap_depts);

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

            $sql = 'SELECT

"BPLId",
(SELECT OBPL."TaxIdNum" FROM AL_YASEEN_AGRI_PLIVE.OBPL WHERE OBPL."BPLId" = F0."BPLId") as "Code",
"BPLName",
"Location",
SUM("S1 Sales") AS "S1 Sales",
SUM("S2 Sales") AS "S2 Sales",
SUM("S1 Sales PY") AS "S1 Sales PY",
SUM("S2 Sales PY") AS "S2 Sales PY",
SUM("S1 Sales Year") AS "S1 Sales Year",
SUM("S2 Sales Year") AS "S2 Sales Year",
SUM("S1 Sales Year PY") AS "S1 Sales Year PY",
SUM("S2 Sales Year PY") AS "S2 Sales Year PY",
SUM("Outstanding Receivables") AS "Outstanding Receivables",
SUM("Outstanding Receivables Over 120") AS "Outstanding Receivables Over 120",
SUM("Stock Value") AS "Stock Value",
SUM("Clean Receivables") AS "Clean Receivables",
SUM("COGS") AS "COGS",
SUM("Operating Expenses") AS "Operating Expenses",
SUM("NPAT Period") AS "NPAT Period",
SUM("NPAT Annual") AS "NPAT Annual"

FROM

(

/*Sales Data*/

SELECT
\'Sales\' AS "ROWID",
tbl1."BPLId",
tbl1."BPLName",
tbl1."Location",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between \'' . $start_date . '\' and \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between \'' . $start_date . '\' and \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(\'' . $start_date . '\',-1) and ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales PY",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(\'' . $start_date . '\',-1) and ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales PY",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between \'2024-01-01\' AND \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales Year",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between \'2024-01-01\' AND \'' . $end_date . '\' THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales Year",
SUM(CASE WHEN "QryGroup2" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365),-1) AND ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S1 Sales Year PY",
SUM(CASE WHEN "QryGroup3" = \'Y\' AND tbl1."DocDate" between ADD_YEARS(ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365),-1) AND ADD_YEARS(\'' . $end_date . '\',-1) THEN (CASE WHEN tbl1."BaseRef" != \'\' THEN (-tbl1."LineTotal"- (-tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) ELSE (tbl1."LineTotal"- (tbl1."LineTotal"*(tbl1."DiscPrcnt"/100))) END) END) AS "S2 Sales Year PY",
0 AS "Outstanding Receivables",
0 AS "Outstanding Receivables Over 120",
0 AS "Stock Value",
0 AS "Clean Receivables",
0 AS "COGS",
0 AS "Operating Expenses",
0 AS "NPAT Period",
0 AS "NPAT Annual"

FROM (

SELECT

T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
T0."LineTotal",
T1."DocDate",
"QryGroup1",
"QryGroup2",
"QryGroup3",
T0."BaseRef",
T1."DiscPrcnt"

FROM AL_YASEEN_AGRI_PLIVE.INV1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OINV T1 ON T0."DocEntry" = T1."DocEntry"
JOIN AL_YASEEN_AGRI_PLIVE.OITM T2 ON T0."ItemCode" = T2."ItemCode"
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T1."BPLId" = T3."BPLId"

UNION ALL

SELECT
T2."BPLId",
T2."BPLName",
T2."GlblLocNum" as "Location",
CASE WHEN T0."CANCELED" = \'C\' THEN T1."LineTotal" ELSE -T1."LineTotal" END as "LineTotal",
T1."DocDate",
T3."QryGroup1",
T3."QryGroup2",
T3."QryGroup3",
\'\' as "BaseRef",
0 as "DiscPrcnt"

FROM
    AL_YASEEN_AGRI_PLIVE.ORIN T0
INNER JOIN
    AL_YASEEN_AGRI_PLIVE.RIN1 T1 ON T0."DocEntry" = T1."DocEntry"
JOIN
AL_YASEEN_AGRI_PLIVE.OBPL T2 ON T0."BPLId" = T2."BPLId"
JOIN
AL_YASEEN_AGRI_PLIVE.OITM T3 ON T1."ItemCode" = T3."ItemCode"
JOIN
AL_YASEEN_AGRI_PLIVE.OCRD T4 ON T3."CardCode" = T4."CardCode"

) as tbl1

GROUP BY

tbl1."BPLId",
tbl1."BPLName",
tbl1."Location"

/* End of Sales Data*/

UNION ALL

/*JDT Data*/

SELECT

\'Receivables\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),
SUM(
CASE WHEN DAYS_BETWEEN(T0."DueDate",\'' . $end_date . '\') >= 120 THEN (
(CASE WHEN T0."DebCred" = \'D\' THEN (T0."Debit"-T0."Credit")-ifnull(T4."ReconSum",0)
WHEN T0."DebCred" = \'C\' THEN -((T0."Credit"-T0."Debit")-ifnull(T4."ReconSum",0)) END)) ELSE 0 END)
,0,0,0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode" AND T2."CardType" = \'C\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"
LEFT JOIN (SELECT SUM("ReconSum") AS "ReconSum",SUM("ReconSumSC") AS "ReconSumSC",SUM("ReconSumFC") AS "ReconSumFC","TransRowId","TransId" FROM AL_YASEEN_AGRI_PLIVE.ITR1 T0 JOIN AL_YASEEN_AGRI_PLIVE.OITR T1 ON T0."ReconNum" = T1."ReconNum" AND T1."ReconDate" <= \'' . $end_date . '\'
GROUP BY "TransRowId","TransId") T4 ON T0."TransId" = T4."TransId" AND T0."Line_ID" = T4."TransRowId"

WHERE T1."RefDate" <= \'' . $end_date . '\'

GROUP BY

T3."BPLId",
T0."DebCred",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

/*Stock Data*/

SELECT

\'Stock\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),0,0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\' AND T2."AcctCode" = \'1203010001\'

GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

/*JDT Data*/

SELECT

\'Clean Receivables\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,0,
SUM(T0."Debit"-T0."Credit"),0,0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OCRD T2 ON T0."ShortName" = T2."CardCode" AND T2."CardType" = \'C\' AND T2."QryGroup1" = \'N\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'

GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'COGS\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,SUM(CASE WHEN T1."RefDate" between ADD_DAYS(ADD_DAYS(\'' . $start_date . '\',1),-365) AND \'' . $end_date . '\' THEN (T0."Debit"-T0."Credit") ELSE 0 END),0,0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T4 ON T2."FatherNum" = T4."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T5 ON T4."FatherNum" = T5."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T6 ON T5."FatherNum" = T6."AcctCode" AND T6."AcctCode" = \'51\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'Operating Expenses\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,SUM(T0."Debit"-T0."Credit"),0,0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T4 ON T2."FatherNum" = T4."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T5 ON T4."FatherNum" = T5."AcctCode"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T6 ON T5."FatherNum" = T6."AcctCode" AND T2."GroupMask" IN (5,6) AND T6."AcctCode" <> \'51\'
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" between \'' . $start_date . '\' AND \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'NPAT Period\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,0,SUM(T0."Credit"-T0."Debit"),0

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode" AND T2."GroupMask" > 3
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" between \'' . $start_date . '\' AND \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

UNION ALL

SELECT

\'NPAT Annual\',
T3."BPLId",
T3."BPLName",
T3."GlblLocNum" as "Location",
0,0,0,0,0,0,0,0,0,0,
0,0,0,0,0,
--SUM(T0."Credit"-T0."Debit"),
SUM(CASE WHEN T1."RefDate" between \'2024-01-01\' AND \'' . $end_date . '\' THEN (T0."Credit"-T0."Debit") ELSE 0 END)

FROM AL_YASEEN_AGRI_PLIVE.JDT1 T0

JOIN AL_YASEEN_AGRI_PLIVE.OJDT T1 ON T0."TransId" = T1."TransId"
JOIN AL_YASEEN_AGRI_PLIVE.OACT T2 ON T0."Account" = T2."AcctCode" AND T2."GroupMask" > 3
JOIN AL_YASEEN_AGRI_PLIVE.OBPL T3 ON T0."BPLId" = T3."BPLId"

WHERE T1."RefDate" <= \'' . $end_date . '\'
GROUP BY

T3."BPLId",
T3."BPLName",
T3."GlblLocNum"

) F0

WHERE F0."BPLId" IN (SELECT "BPLId" FROM AL_YASEEN_AGRI_PLIVE.USR6 T0 JOIN AL_YASEEN_AGRI_PLIVE.OUSR T1 ON T0."UserCode" = T1."USER_CODE" WHERE "UserCode" = \'ctc90010.2\')
AND F0."BPLId" IN ('. implode(', ', $sap_depts).')

GROUP BY

"BPLId",
"BPLName",
"Location"
ORDER BY "BPLId"';


//                dd($sql);
            $result = odbc_exec($conn, $sql);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
//                dd(odbc_fetch_array($result));

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->sap_results, $row);
                }

            }
            odbc_close($conn);
        }
//        }

    }
}
