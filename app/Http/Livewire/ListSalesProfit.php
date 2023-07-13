<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListSalesProfit extends Component
{
    public $area_id = -1;
    public $result;
    public $result_tbl2 = [];
    public $selected_date;
    public $first_date;
    public $last_date;

    protected $rules = [
        'area_id' => 'required|not_in:-1',
        'selected_date' => 'required',
    ];

    protected $messages = [
        'area_id.required' => "مطلوب",
        'area_id.not_in' => "مطلوب",
        'selected_date.required' => "مطلوب",
    ];

    public function render()
    {
        $result = 0;
        return view('livewire.list-sales-profit')
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        set_time_limit(2000);
        $this->validate();
        $this->emit('show-container');

        $this->first_date = date('Y-m-01', strtotime($this->selected_date));
        $this->last_date = date('Y-m-t', strtotime($this->selected_date));

        $start_of_day = Carbon::parse($this->first_date)->subMonths(3);
        $end_of_day = Carbon::parse($this->last_date);
//        $days = intval($end_of_day->diffInDays($start_of_day) + 1);
//
//        DB::connection('sqlsrv')->statement('DECLARE @result1 TABLE (Employeecode NVARCHAR(50), EmployeeName NVARCHAR(150), SpecialityCode NVARCHAR(250), branchname NVARCHAR(150), Value FLOAT, totalcost FLOAT)');
//        DB::connection('sqlsrv')->statement('DECLARE @area AS INT = :area1', ['area1' => $this->area_id]);
//        DB::connection('sqlsrv')->statement("INSERT INTO @result1
//select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//  and area=areamast.nodeno
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date1 and sIDate<=:end_date_time1
//and AreaMast.NodeNo = @area
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//  and area=areamast.nodeno
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date2 and pidate<=:end_date_time2
//  and studentmast.nodeno>1
//  and AreaMast.NodeNo = @area
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name", [
//    'start_date1' => $this->first_date,
//    'start_date2' => $this->first_date,
//    'end_date_time1' => $this->last_date . " 23:59:23",
//    'end_date_time2' => $this->last_date . " 23:59:23",]);

        $tbl2_result = DB::connection('sqlsrv')->select("select distinct Employeecode,Employeename,branchName
,0 as SPL0,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
and AreaMast.NodeNo = :area1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area2
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='0'),0) as Spl0Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
and AreaMast.NodeNo = :area3
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area4
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='0'),0) as Spl0cost
 ,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
and AreaMast.NodeNo = :area5
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area6
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date7 and sIDate<=:end_date_time7
and AreaMast.NodeNo = :area7
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date8 and pidate<=:end_date_time8
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area8
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
  ,2 as SPL2,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date9 and sIDate<=:end_date_time9
and AreaMast.NodeNo = :area9
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date10 and pidate<=:end_date_time10
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area10
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
  and s0.branchname=tbl1.branchname
and s0.SpecialityCode='2'),0) as Spl2Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date11 and sIDate<=:end_date_time11
and AreaMast.NodeNo = :area11
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date12 and pidate<=:end_date_time12
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area12
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
  and s0.branchname=tbl1.branchname
and s0.SpecialityCode='2'),0) as Spl2Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date13 and sIDate<=:end_date_time13
and AreaMast.NodeNo = :area13
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date14 and pidate<=:end_date_time14
  and studentmast.nodeno>1
  and AreaMast.NodeNo = :area14
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1",
        [
            'area1' => $this->area_id,
            'area2' => $this->area_id,
            'area3' => $this->area_id,
            'area4' => $this->area_id,
            'area5' => $this->area_id,
            'area6' => $this->area_id,
            'area7' => $this->area_id,
            'area8' => $this->area_id,
            'area9' => $this->area_id,
            'area10' => $this->area_id,
            'area11' => $this->area_id,
            'area12' => $this->area_id,
            'area13' => $this->area_id,
            'area14' => $this->area_id,
            'start_date1' => $this->first_date,
            'start_date2' => $this->first_date,
            'start_date3' => $this->first_date,
            'start_date4' => $this->first_date,
            'start_date5' => $this->first_date,
            'start_date6' => $this->first_date,
            'start_date7' => $this->first_date,
            'start_date8' => $this->first_date,
            'start_date9' => $this->first_date,
            'start_date10' => $this->first_date,
            'start_date11' => $this->first_date,
            'start_date12' => $this->first_date,
            'start_date13' => $this->first_date,
            'start_date14' => $this->first_date,
            'end_date_time1' => $this->last_date . " 23:59:23",
            'end_date_time2' => $this->last_date . " 23:59:23",
            'end_date_time3' => $this->last_date . " 23:59:23",
            'end_date_time4' => $this->last_date . " 23:59:23",
            'end_date_time5' => $this->last_date . " 23:59:23",
            'end_date_time6' => $this->last_date . " 23:59:23",
            'end_date_time7' => $this->last_date . " 23:59:23",
            'end_date_time8' => $this->last_date . " 23:59:23",
            'end_date_time9' => $this->last_date . " 23:59:23",
            'end_date_time10' => $this->last_date . " 23:59:23",
            'end_date_time11' => $this->last_date . " 23:59:23",
            'end_date_time12' => $this->last_date . " 23:59:23",
            'end_date_time13' => $this->last_date . " 23:59:23",
            'end_date_time14' => $this->last_date . " 23:59:23",
        ]);

        $this->result_tbl2 = json_decode(json_encode($tbl2_result), true);
//        dd($this->result_tbl2);
    }
}
