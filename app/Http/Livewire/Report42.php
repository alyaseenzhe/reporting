<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report42 extends Component
{
    public $dept_id = ["dept_all"];
    public $show_msg = false;

    public $scribes_results = [];

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


    public function render()
    {
        return view('livewire.report42')
            ->layout('layouts.dashboard');
    }

    public function create_report($start_date, $end_date, $dept_id) {

//        dd($start_date);
//        dd('start:'.$start_date.'  end:'. $end_date/*.'    dept_id:'.$dept_id[0]*/);

        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
        $this->scribes_results = [];
        $this->sap_results = [];

        ////////////////

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

            if ($start_date > '2025-12-31' && $end_date > '2025-12-31') {
                // only sap
                dd('only sap');
//                $this->scribesQuery($start_date, $end_date, $dept_id);
            }
            elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31') {
                // only scribes
//                dd('only scribes');
                $this->scribesQuery($start_date, $end_date, $dept_id);
                //                $this->sapQuery($start_date, $end_date, $dept_id);
            }
            else {
                // scribes & sap
                dd('scribes & sap');
//                $this->scribesQuery($start_date, '2023-12-31', $dept_id);
//                $this->sapQuery('2024-01-01', $end_date, $dept_id);
            }


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
        $previous_start_date = Carbon::parse($start_date)->subYear()->format('Y-m-d');
        $previous_end_date = Carbon::parse($end_date)->subYear()->format('Y-m-d');
        $previous_2_end_date = Carbon::parse($end_date)->subYears(2)->format('Y-m-d');
        $due_date = Carbon::parse($end_date)->addMonths(-4)->format("Y-m-d");


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
}
