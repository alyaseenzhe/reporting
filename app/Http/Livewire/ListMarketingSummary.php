<?php

namespace App\Http\Livewire;

use App\Mail\MarketingSummaryEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ListMarketingSummary extends Component
{

    public $employees = [];
    public $start_date;
    public $end_date;

    public $month_start_date;
    public $month_end_date;
    public $year_end_date;


    public $data = [];

    public $wk_s_total = [];
    public $wk_m_total = [];
    public $wk_b_total = [];

    protected $rules = [
        'start_date' => 'required',
        'end_date' => 'required',
    ];

    protected $messages = [
        'start_date.required' => "مطلوب",
        'end_date.required' => "مطلوب",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.marketing-summary', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.list-marketing-summary')
            ->layout('layouts.dashboard');
    }

    public function generateReport() {

        $this->validate();

        $this->wk_s_total = [];
        $this->wk_m_total = [];
        $this->wk_b_total = [];

//        $end_of_month_before = Carbon::create($this->start_date)->subDay()->format('Y-m-d');
//        $first_of_month_before = Carbon::create($end_of_month_before)->subMonths(2)->addDay()->format('Y-m-d');

        $end_of_month_before = Carbon::create($this->end_date)->format('Y-m-d');
        $first_of_month_before = Carbon::create($end_of_month_before)->subMonths(2)->addDay()->format('Y-m-d');
//        dd('first-date: '. $first_of_month_before . "| end-date: ". $end_of_month_before);

        $this->month_start_date = $first_of_month_before;
        $this->month_end_date = $end_of_month_before;
        $this->year_end_date = Carbon::create($this->end_date)->subYear()->format('Y-m-d');

        $this->employees = DB::connection('mysql2')->table('role')
            ->join('users', 'users.role', 'role.r_id')
            ->whereIn('users.role', [14, 40, 43])
            ->where('privileges', '<>', '')
            ->get();

//        $emp = $this->employees->where('id', 10200)->first();
//        dd($emp->role);

        $employees_ids = [];

        foreach ($this->employees as $emp) {
            array_push($employees_ids, $emp->id);
        }

//        $customers = DB::connection('mysql2')->table('activity')
//            ->whereNotNull('customer_id')
//            ->whereIn('author', $employees_ids)
//            ->whereBetween('activity_timestamp', [$this->start_date, $this->end_date])
////            ->where('is_branch_visit', '!=', 'N')
//            ->where('type','a-00')
//            ->selectRaw('DISTINCT author, customer_id')
//            ->get();


        // to get customers that an employee has visited
//        $employees_visits_customers_ids = [];
//
//        foreach ($employees_ids as $emp_id) {
//            $customers_ids = [];
//            foreach ($customers->where('author', $emp_id) as $customer) {
//                array_push($customers_ids, $customer->customer_id);
//            }
//            $employees_visits_customers_ids[$emp_id] = $customers_ids;
//        }

        $visits = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereNotIn('customer_id', ['0000000', '0100000', '0109999', '0200000', '0200999', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0800999', '0809999', '0900000', '1000000', '1100000', '1200000'])
            ->whereIn('author', $employees_ids)
            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
//            ->where('is_branch_visit', '!=', 'N')
            ->where('type','a-00')
            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
            ->select('author', DB::raw('COUNT(DISTINCT customer_id) as num_of_visits'))
            ->groupBy('author')
            ->pluck('num_of_visits', 'author')->toArray();

        $visits_general_customers = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('customer_id', ['0000000', '0100000', '0109999', '0200000', '0200999', '0300000', '0400000', '0500000', '0600000', '0700000', '0800000', '0800999', '0809999', '0900000', '1000000', '1100000', '1200000'])
            ->whereIn('author', $employees_ids)
            ->whereBetween('activity_timestamp', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
//            ->where('is_branch_visit', '!=', 'N')
            ->where('type','a-00')
            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
            ->select('author', DB::raw('COUNT(customer_id) as num_of_visits'))
            ->groupBy('author')
            ->pluck('num_of_visits', 'author')->toArray();


//        $places = DB::connection('mysql2')->table('activity')
//            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
//            ->whereNotNull('customer_id')
//            ->whereIn('author', $employees_ids)
//            ->whereBetween('activity_timestamp', [$this->start_date, $this->end_date])
////            ->where('is_branch_visit', '!=', 'N')
//            ->where('type','a-00')
//            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->selectRaw('DISTINCT SUBSTRING(place_id, 1, 4) AS place_id, place_name, author')
//            ->get();

        $places = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('author', $employees_ids)
            ->whereBetween('activity_timestamp', [$this->start_date, $this->end_date])
//            ->where('is_branch_visit', '!=', 'N')
            ->where('type','a-00')
            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
            ->selectRaw('author, place_name, selected_loc, MIN(activity_timestamp) as min_date')
            ->groupBy('author', 'place_name', 'selected_loc')
            ->get()->toArray();

//        dd($places);
//        $sinvoice_stmt = '';
//        $pinvoice_stmt = '';

//        foreach ($places as $key => $record) {
//            if ($key === array_key_first($places)) {
//                $sinvoice_stmt .= "(sIDate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and sIDate<='". $this->end_date. " 23:59:59" ."')";
//                $pinvoice_stmt .= "(pidate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and pidate<='". $this->end_date. " 23:59:59" ."')";
//            } else {
//                $sinvoice_stmt .= " or (sIDate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and sIDate<='". $this->end_date. " 23:59:59" ."')";
//                $pinvoice_stmt .= " or (pidate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and pidate<='". $this->end_date. " 23:59:59" ."')";
//            }
//        }

//        $sinvoice_stmt = "(". $sinvoice_stmt .")";
//        $pinvoice_stmt = "(". $pinvoice_stmt .")";

        $week_sinvoice_stmt = "(sIDate>='" . Carbon::parse($this->start_date)->format('Y-m-d')."' and sIDate<='". $this->end_date. " 23:59:59" ."')";
        $week_pinvoice_stmt = "(pidate>='" . Carbon::parse($this->start_date)->format('Y-m-d')."' and pidate<='". $this->end_date. " 23:59:59" ."')";

//        dd($sinvoice_stmt);
//        $s_total = [];
//        $m_total = [];
//        $b_total = [];

        foreach ($places as $key => $record) {

//            dd($record);
            $emp = $this->employees->where('id', $record->author)->first();

            $sinvoice_stmt = "(sIDate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and sIDate<='". $this->end_date. " 23:59:59" ."')";
            $pinvoice_stmt = "(pidate>='" . Carbon::parse($record->min_date)->format('Y-m-d')."' and pidate<='". $this->end_date. " 23:59:59" ."')";

            if ($emp->role == 40) { // s
                $s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ". $sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
having branchname = :area", [":area" => $record->place_name]);

                $s_query = collect($s_query);
                $s_data = $s_query->pluck('s_total')->first();
//            $s_data = $s_query->pluck('s_total', 'branchname')->toArray();
                array_push($this->wk_s_total, ['place_name' => $record->place_name, 'min_date' => Carbon::parse($record->min_date)->format('Y-m-d'), 'author' => $record->author, 'total' => $s_data]);
//            array_push($this->s_total, ['place_name' => $record->place_name, 'author' => $record->author, 'total' => $s_data]);
            }
            elseif ($emp->role == 14) { // b
                $b_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and " . $sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
having branchname = :area", [":area" => $record->place_name]);

                $b_query = collect($b_query);
                $b_data = $b_query->pluck('s_total')->first();
//            $b_data = $b_query->pluck('s_total', 'branchname')->toArray();
                array_push($this->wk_b_total, ['place_name' => $record->place_name, 'min_date' => Carbon::parse($record->min_date)->format('Y-m-d'), 'author' => $record->author, 'total' => $b_data]);
//                dd($this->wk_b_total);
//            array_push($this->b_total, ['place_name' => $record->place_name, 'author' => $record->author, 'total' => $b_data]);
//        $week_b_total = $b_query->sum('s_total');

            }
            elseif ($emp->role == 43) { // m
                $m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$sinvoice_stmt.
                    " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$pinvoice_stmt.
                    " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
having branchname = :area", [":area" => $record->place_name]);

                $m_query = collect($m_query);
                $m_data = $m_query->pluck('s_total')->first();
//            dd($m_data);
//            $m_data = $m_query->pluck('s_total', 'branchname')->toArray();
                array_push($this->wk_m_total, ['place_name' => $record->place_name, 'min_date' => Carbon::parse($record->min_date)->format('Y-m-d'), 'author' => $record->author, 'total' => $m_data]);
//            array_push($this->m_total, ['place_name' => $record->place_name, 'author' => $record->author, 'total' => $m_data]);

//            dd($m_data);
//                dd($this->wk_m_total);
            }

        }


//        dd($this->wk_m_total);
//        dd(array_sum($m_total));

//        $s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
//select distinct Employeecode,Employeename,branchName
//,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ". $sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Value
//,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ".$sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Cost
//from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ".$sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
//group by branchname");
//
//        $s_query = collect($s_query);
//        $s_data = $s_query->pluck('s_total', 'branchname')->toArray();
//        $week_s_total = $s_query->sum('s_total');


        $week_s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ". $week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname");

        $week_s_query = collect($week_s_query);
        $week_s_data = $week_s_query->pluck('s_total', 'branchname')->toArray();
        $week_s_total = $week_s_query->sum('s_total');
//        dd($s_data);


//        $s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
//select distinct Employeecode,Employeename,branchName
//,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date1 and sIDate<=:end_date_time1
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date2 and pidate<=:end_date_time2
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Value
//,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date3 and sIDate<=:end_date_time3
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date4 and pidate<=:end_date_time4
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Cost
//from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date5 and sIDate<=:end_date_time5
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and ProductMast.Code like '17%'
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date6 and pidate<=:end_date_time6
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
//group by branchname",
//            [
//                'start_date1' => $this->start_date,
//                'start_date2' => $this->start_date,
//                'start_date3' => $this->start_date,
//                'start_date4' => $this->start_date,
//                'start_date5' => $this->start_date,
//                'start_date6' => $this->start_date,
//                'end_date_time1' => $this->end_date . " 23:59:59",
//                'end_date_time2' => $this->end_date . " 23:59:59",
//                'end_date_time3' => $this->end_date . " 23:59:59",
//                'end_date_time4' => $this->end_date . " 23:59:59",
//                'end_date_time5' => $this->end_date . " 23:59:59",
//                'end_date_time6' => $this->end_date . " 23:59:59",
//            ]);

//        $m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
//select distinct Employeecode,Employeename,branchName
//,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ".$sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Value
//,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ".$sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Cost
//from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and ".$sinvoice_stmt.
//" group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and ".$pinvoice_stmt.
//" and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
//group by branchname
//having branchname = :area", [":area" => $record->place_name]);
//
//        $m_query = collect($m_query);
//        $m_data = $m_query->pluck('s_total', 'branchname')->toArray();
//        $week_m_total = $m_query->sum('s_total');
//        dd($m_query);

        $week_m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
");

        $week_m_query = collect($week_m_query);
        $week_m_data = $week_m_query->pluck('s_total', 'branchname')->toArray();
        $week_m_total = $week_m_query->sum('s_total');

//        dd($week_m_total);



//        dd($m_query);
//        $m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
//select distinct Employeecode,Employeename,branchName
//,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date1 and sIDate<=:end_date_time1
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date2 and pidate<=:end_date_time2
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Value
//,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date3 and sIDate<=:end_date_time3
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date4 and pidate<=:end_date_time4
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Cost
//from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date5 and sIDate<=:end_date_time5
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date6 and pidate<=:end_date_time6
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
//group by branchname
//",
//            [
//                'start_date1' => $this->start_date,
//                'start_date2' => $this->start_date,
//                'start_date3' => $this->start_date,
//                'start_date4' => $this->start_date,
//                'start_date5' => $this->start_date,
//                'start_date6' => $this->start_date,
//                'end_date_time1' => $this->end_date . " 23:59:59",
//                'end_date_time2' => $this->end_date . " 23:59:59",
//                'end_date_time3' => $this->end_date . " 23:59:59",
//                'end_date_time4' => $this->end_date . " 23:59:59",
//                'end_date_time5' => $this->end_date . " 23:59:59",
//                'end_date_time6' => $this->end_date . " 23:59:59",
//            ]);



        $week_b_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and " . $week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and ".$week_sinvoice_stmt.
            " group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
union all
select
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and ".$week_pinvoice_stmt.
            " and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname");

        $week_b_query = collect($week_b_query);
        $week_b_data = $week_b_query->pluck('s_total', 'branchname')->toArray();
        $week_b_total = $week_b_query->sum('s_total');
//        $b_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
//select distinct Employeecode,Employeename,branchName
//,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date1 and sIDate<=:end_date_time1
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date2 and pidate<=:end_date_time2
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Value
//,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date3 and sIDate<=:end_date_time3
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date4 and pidate<=:end_date_time4
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
//and s0.branchname=tbl1.branchname
//and s0.SpecialityCode='1'),0) as Spl1Cost
//from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
//from sinvoice ,productmast,areamasT
//,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
//and studentmast.nodeno>1
//and sIDate>=:start_date5 and sIDate<=:end_date_time5
// group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
//union all
//select
//   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
//      areamast.Arabic_Name as branchname,
//-sum(value*exchangerate+extrafieldstotal) as Value
//  ,-sum(totalcost) as totalcost
//from pinvoice ,productmast,areamast
//,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
//and area=areamast.nodeno
//and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
//and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
//and pidate>=:start_date6 and pidate<=:end_date_time6
//and studentmast.nodeno>1
//group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
//group by branchname",
//            [
//                'start_date1' => $this->start_date,
//                'start_date2' => $this->start_date,
//                'start_date3' => $this->start_date,
//                'start_date4' => $this->start_date,
//                'start_date5' => $this->start_date,
//                'start_date6' => $this->start_date,
//                'end_date_time1' => $this->end_date . " 23:59:59",
//                'end_date_time2' => $this->end_date . " 23:59:59",
//                'end_date_time3' => $this->end_date . " 23:59:59",
//                'end_date_time4' => $this->end_date . " 23:59:59",
//                'end_date_time5' => $this->end_date . " 23:59:59",
//                'end_date_time6' => $this->end_date . " 23:59:59",
//            ]);


//        dd($m_query);
//        dd($m_data);
//        dd($week_m_total);


//        dd($b_data);


        // a month before

//        $month_places = DB::connection('mysql2')->table('activity')
//            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
//            ->whereNotNull('customer_id')
//            ->whereIn('author', $employees_ids)
//            ->whereBetween('activity_timestamp', [$first_of_month_before, $end_of_month_before])
////            ->where('is_branch_visit', '!=', 'N')
//            ->where('type','a-00')
//            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->selectRaw('DISTINCT SUBSTRING(place_id, 1, 4) AS place_id, place_name, author')
//            ->get();
        $month_places = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('author', $employees_ids)
            ->whereBetween('activity_timestamp', [$first_of_month_before, $end_of_month_before])
//            ->where('is_branch_visit', '!=', 'N')
            ->where('type','a-00')
            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->selectRaw('DISTINCT SUBSTRING(place_id, 1, 4) AS place_id, place_name, author')
            ->selectRaw('author, place_name, selected_loc as place_id, MIN(activity_timestamp) as min_date, DATE_SUB(MIN(activity_timestamp), INTERVAL 1 YEAR) as min_year_date')
            ->groupBy('author', 'place_name', 'selected_loc')
            ->get();

        $year_places = DB::connection('mysql2')->table('activity')
            ->join('work_places', 'activity.selected_loc', 'work_places.place_id')
            ->whereNotNull('customer_id')
            ->whereIn('author', $employees_ids)
            ->whereBetween('activity_timestamp', [$first_of_month_before, $end_of_month_before])
//            ->where('is_branch_visit', '!=', 'N')
            ->where('type','a-00')
            ->whereIn('place_id',['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112'])
//            ->selectRaw('DISTINCT SUBSTRING(place_id, 1, 4) AS place_id, place_name, author')
            ->selectRaw('author, place_name, selected_loc as place_id, DATE_SUB(MIN(activity_timestamp), INTERVAL 1 YEAR) as min_year_date')
            ->groupBy('author', 'place_name', 'selected_loc')
            ->get();


        $month_places_total = [];
        $year_places_total = [];

        foreach ($month_places as $m_place) {
//            dd("start: " . $m_place->min_year_date . "| end: " . $this->year_end_date);

            $emp = $this->employees->where('id', $m_place->author)->first();

            if ($emp->role == 40) {

                // month
                $month_s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname",
                    [
                        'start_date1' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
                        'start_date2' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date2' => $first_of_month_before,
                        'start_date3' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date3' => $first_of_month_before,
                        'start_date4' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date4' => $first_of_month_before,
                        'start_date5' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date5' => $first_of_month_before,
                        'start_date6' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $end_of_month_before . " 23:59:59",
                        'end_date_time2' => $end_of_month_before . " 23:59:59",
                        'end_date_time3' => $end_of_month_before . " 23:59:59",
                        'end_date_time4' => $end_of_month_before . " 23:59:59",
                        'end_date_time5' => $end_of_month_before . " 23:59:59",
                        'end_date_time6' => $end_of_month_before . " 23:59:59",
                    ]);

                $month_s_query = collect($month_s_query);
                $month_s_data = $month_s_query->pluck('s_total', 'branchname')->toArray();
                array_push($month_places_total, $month_s_data);

            }
            elseif ($emp->role == 43) {

                // month
                $month_m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
",
                    [
                        'start_date1' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date2' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date3' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date4' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date5' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date6' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
//                        'start_date2' => $first_of_month_before,
//                        'start_date3' => $first_of_month_before,
//                        'start_date4' => $first_of_month_before,
//                        'start_date5' => $first_of_month_before,
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $end_of_month_before . " 23:59:59",
                        'end_date_time2' => $end_of_month_before . " 23:59:59",
                        'end_date_time3' => $end_of_month_before . " 23:59:59",
                        'end_date_time4' => $end_of_month_before . " 23:59:59",
                        'end_date_time5' => $end_of_month_before . " 23:59:59",
                        'end_date_time6' => $end_of_month_before . " 23:59:59",
                    ]);

                $month_m_query = collect($month_m_query);
                $month_m_data = $month_m_query->pluck('s_total', 'branchname')->toArray();
                array_push($month_places_total, $month_m_data);

            }
            elseif ($emp->role == 14) {
                // month
                $month_b_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname",
                    [
                        'start_date1' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date2' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date3' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date4' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date5' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
                        'start_date6' => Carbon::parse($m_place->min_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
//                        'start_date2' => $first_of_month_before,
//                        'start_date3' => $first_of_month_before,
//                        'start_date4' => $first_of_month_before,
//                        'start_date5' => $first_of_month_before,
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $end_of_month_before . " 23:59:59",
                        'end_date_time2' => $end_of_month_before . " 23:59:59",
                        'end_date_time3' => $end_of_month_before . " 23:59:59",
                        'end_date_time4' => $end_of_month_before . " 23:59:59",
                        'end_date_time5' => $end_of_month_before . " 23:59:59",
                        'end_date_time6' => $end_of_month_before . " 23:59:59",
                    ]);

                $month_b_query = collect($month_b_query);
                $month_b_data = $month_b_query->pluck('s_total', 'branchname')->toArray();
                array_push($month_places_total, $month_b_data);

            }

//        dd($month_m_data);

        }
//        dd($month_places_total);
//        dd($year_places_total);

        foreach ($year_places as $y_place) {
//            dd("start: " . $m_place->min_year_date . "| end: " . $this->year_end_date);

            $emp = $this->employees->where('id', $y_place->author)->first();

            if ($emp->role == 40) {

                // year

                $year_s_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and ProductMast.Code like '17%'
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and ProductMast.Code like '17%'
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname",
                    [
                        'start_date1' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
                        'start_date2' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date2' => $first_of_month_before,
                        'start_date3' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date3' => $first_of_month_before,
                        'start_date4' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date4' => $first_of_month_before,
                        'start_date5' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date5' => $first_of_month_before,
                        'start_date6' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $this->year_end_date . " 23:59:59",
                        'end_date_time2' => $this->year_end_date . " 23:59:59",
                        'end_date_time3' => $this->year_end_date . " 23:59:59",
                        'end_date_time4' => $this->year_end_date . " 23:59:59",
                        'end_date_time5' => $this->year_end_date . " 23:59:59",
                        'end_date_time6' => $this->year_end_date . " 23:59:59",
                    ]);

                $year_s_query = collect($year_s_query);
                $year_s_data = $year_s_query->pluck('s_total', 'branchname')->toArray();
                array_push($year_places_total, $year_s_data);

            }
            elseif ($emp->role == 43) {

                // year
                $year_m_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and (ProductMast.Code like '10%' or ProductMast.Code like '11%' or ProductMast.Code like '13%' or ProductMast.Code like '14%' or ProductMast.Code like '15%' or ProductMast.Code like '16%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname
",
                    [
                        'start_date1' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date2' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date3' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date4' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date5' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date6' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
//                        'start_date2' => $first_of_month_before,
//                        'start_date3' => $first_of_month_before,
//                        'start_date4' => $first_of_month_before,
//                        'start_date5' => $first_of_month_before,
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $this->year_end_date . " 23:59:59",
                        'end_date_time2' => $this->year_end_date . " 23:59:59",
                        'end_date_time3' => $this->year_end_date . " 23:59:59",
                        'end_date_time4' => $this->year_end_date . " 23:59:59",
                        'end_date_time5' => $this->year_end_date . " 23:59:59",
                        'end_date_time6' => $this->year_end_date . " 23:59:59",
                    ]);

                $year_m_query = collect($year_m_query);
                $year_m_data = $year_m_query->pluck('s_total', 'branchname')->toArray();
//                dd($year_m_data);
                array_push($year_places_total, $year_m_data);

            }
            elseif ($emp->role == 14) {

                // year
                $year_b_query = DB::connection('sqlsrv')->select("select branchname, SUM(Spl1Value) as s_total from (
select distinct Employeecode,Employeename,branchName
,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time3
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time4
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=tbl1.Employeecode
and s0.branchname=tbl1.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName,SpecialityCode,areamast.Arabic_Name as branchname,sum(value*exchangerate+extrafieldstotal) as Value,sum(totalcost) as totalcost
from sinvoice ,productmast,areamasT
,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date5 and sIDate<=:end_date_time5
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
and (ProductMast.Code like '20%' or ProductMast.Code like '21%' or ProductMast.Code like '22%')
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date6 and pidate<=:end_date_time6
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as tbl1) as tbl2
group by branchname",
                    [
                        'start_date1' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date2' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date3' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date4' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date5' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
                        'start_date6' => Carbon::parse($y_place->min_year_date)->format('Y-m-d') . " 00:00:00",
//                        'start_date1' => $first_of_month_before,
//                        'start_date2' => $first_of_month_before,
//                        'start_date3' => $first_of_month_before,
//                        'start_date4' => $first_of_month_before,
//                        'start_date5' => $first_of_month_before,
//                        'start_date6' => $first_of_month_before,
                        'end_date_time1' => $this->year_end_date . " 23:59:59",
                        'end_date_time2' => $this->year_end_date . " 23:59:59",
                        'end_date_time3' => $this->year_end_date . " 23:59:59",
                        'end_date_time4' => $this->year_end_date . " 23:59:59",
                        'end_date_time5' => $this->year_end_date . " 23:59:59",
                        'end_date_time6' => $this->year_end_date . " 23:59:59",
                    ]);

                $year_b_query = collect($year_b_query);
                $year_b_data = $year_b_query->pluck('s_total', 'branchname')->toArray();
                array_push($year_places_total, $year_b_data);
            }

//        dd($month_m_data);

        }


        //////////

        $this->data = [];
        $m_m_total = [];
        $m_b_total = [];
        $m_s_total = [];

        foreach ($this->employees as $employee) {
            $emp = [];
            $name_of_places = [];
            $month_name_of_places = [];
            $year_name_of_places = [];

            $emp['emp_id'] = $employee->id;
            $emp['emp_name'] = $employee->disp_name;
            $emp['role'] = $employee->role;
            $emp['role_name'] = $employee->r_name;
            $emp['places'] = '';
            $emp['month_places'] = '';
            $emp['year_places'] = '';

//            $modified_places = $places->where('author', $employee->id);
//            $modified_month_places = $month_places->where('author', $employee->id);

            foreach ($places as $key => $place) {
                if ($place->author == $employee->id) {
                    if ($key === array_key_last($places)) {
                        $emp['places'] = $emp['places'] . $place->place_name  .'('. Carbon::parse($place->min_date)->format('Y-m-d').')';
                    }
                    else {
                        $emp['places'] = $emp['places'] . $place->place_name  .'('. Carbon::parse($place->min_date)->format('Y-m-d').'), ';
                    }

                    array_push($name_of_places, $place->place_name);
                }
//                $emp['places'] = $emp['places'] . $place->place_name  .', ';
//                array_push($name_of_places, $place->place_name);
//                    if (end($places) == $place) {
//                        $emp['places'] = $emp['places'] . $place->place_name;
//                        array_push($name_of_places, $place->place_name);
//                    }
//                    else {
//                        $emp['places'] = $emp['places'] . $place->place_name  .', ';
//                        array_push($name_of_places, $place->place_name);
//                    }
            }

            foreach ($month_places as $index => $month_place) {
//                dd($month_place);
                if ($month_place->author == $employee->id) {
                    $emp['month_places'] = $emp['month_places'] . $month_place->place_name . '('. Carbon::parse($month_place->min_date)->format('Y-m-d') .'), ';
//                    $emp['month_place_min_date'] = $month_place->min_date;
                    array_push($month_name_of_places, ['place_name' => $month_place->place_name, 'min_date' => Carbon::parse($month_place->min_date)->format('Y-m-d'), 'total' => $month_places_total[$index][$month_place->place_name]]);

                }
//                    if (end($month_places) == $month_place) {
//                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name;
//                        array_push($month_name_of_places, $month_place->place_name);
//                    }
//                    else {
//                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name  .', ';
//                        array_push($month_name_of_places, $month_place->place_name);
//                    }
            }

            foreach ($year_places as $index => $year_place) {
//                dd($month_place);
                // dd($year_places);
                // dd($year_places_total);
                if ($year_place->author == $employee->id) {
                    $emp['year_places'] = $emp['year_places'] . $year_place->place_name . '('. Carbon::parse($year_place->min_year_date)->format('Y-m-d') .'), ';
//                    $emp['month_place_min_date'] = $month_place->min_date;
                    array_push($year_name_of_places, ['place_name' => $year_place->place_name, 'min_date' => Carbon::parse($year_place->min_year_date)->format('Y-m-d'), 'total' => $year_places_total[$index][$year_place->place_name]]);

                }
//                    if (end($month_places) == $month_place) {
//                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name;
//                        array_push($month_name_of_places, $month_place->place_name);
//                    }
//                    else {
//                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name  .', ';
//                        array_push($month_name_of_places, $month_place->place_name);
//                    }
            }
//            dd($month_name_of_places);
//            if ($modified_places) {
//                foreach ($modified_places as $place) {
//                    $emp['places'] = $emp['places'] . $place->place_name  .', ';
//                    array_push($name_of_places, $place->place_name);
////                    if (end($places) == $place) {
////                        $emp['places'] = $emp['places'] . $place->place_name;
////                        array_push($name_of_places, $place->place_name);
////                    }
////                    else {
////                        $emp['places'] = $emp['places'] . $place->place_name  .', ';
////                        array_push($name_of_places, $place->place_name);
////                    }
//                }
//            }

//            if ($modified_month_places) {
//                foreach ($modified_month_places as $month_place) {
//                    $emp['month_places'] = $emp['month_places'] . $month_place->place_name  .', ';
//                    array_push($month_name_of_places, $month_place->place_name);
////                    if (end($month_places) == $month_place) {
////                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name;
////                        array_push($month_name_of_places, $month_place->place_name);
////                    }
////                    else {
////                        $emp['month_places'] = $emp['month_places'] . $month_place->place_name  .', ';
////                        array_push($month_name_of_places, $month_place->place_name);
////                    }
//                }
//            }

            $visit_index = array_key_exists($employee->id, $visits);
            if ($visit_index && count($places) > 0) {
                $emp['visits'] = $visits[$employee->id]+ (array_key_exists($employee->id, $visits_general_customers) ?  $visits_general_customers[$employee->id] : 0);
            }
            else {
                $emp['visits'] = 0;
            }


//            $emp['total'] = 0;
            $emp['month_total'] = 0;
            $emp['week_total'] = 0;

//            dd($name_of_places);
//            foreach ($name_of_places as $place) {
////            foreach ($modified_places as $place) {
//
//                // asmadah
//                if ($emp['role'] == 40) {
//                    $s_index = array_key_exists($place, $s_data);
////                    $s_index = array_key_exists($place->place_name, $s_data);
//                    if ($s_index) {
//                        $emp['total'] = $emp['total'] + floatval($s_data[$place]);
////                        $emp['total'] = $emp['total'] + floatval($s_data[$place->place_name]);
//                    }
//
//                }
//                // bthoor
//                if ($emp['role'] == 14) {
//                    $b_index = array_key_exists($place, $b_data);
////                    $b_index = array_key_exists($place->place_name, $b_data);
//                    if ($b_index) {
//                        $emp['total'] = $emp['total'] + floatval($b_data[$place]);
////                        $emp['total'] = $emp['total'] + floatval($b_data[$place->place_name]);
//                    }
//                }
//                // mobedat
//                if ($emp['role'] == 43) {
//                    $m_index = array_key_exists($place, $m_data);
////                    $m_index = array_key_exists($place->place_name, $m_data);
//                    if ($m_index) {
//                        $emp['total'] = $emp['total'] + floatval($m_data[$place]);
////                        $emp['total'] = $emp['total'] + floatval($m_data[$place->place_name]);
//                    }
//                }
//            }

            $emp['month_total'] = [];
            $emp['year_total'] = [];
            foreach ($month_name_of_places as $place) {
//            foreach ($modified_month_places as $place) {
                // asmadah
                if ($emp['role'] == 40) {
                    $month_s_index = array_key_exists($place['place_name'], $month_s_data);
                    if ($month_s_index) {

//                        $a[$place] = floatval($month_s_data[$place]);
//                        if ($a) {
//                            array_push($emp, $a);
//                        }
//                        $emp['month_total'][] = [$place => floatval($month_s_data[$place])]; // good
//                        $emp['month_total'] = [$place => floatval($month_s_data[$place])]; // good
//                        $emp['month_total'] = $emp['month_total'] + floatval($month_s_data[$place]); // good
//                        $emp['month_total'] = $emp['month_total'] + floatval($month_s_data[$place]); // good

//                        array_push($emp['month_total'][$place], floatval($month_s_data[$place]));

                        $emp['month_total'][$place['place_name']] =  [floatval($month_s_data[$place['place_name']]), $place['min_date'], $place['total']];
//                        $emp['month_total'] =  ['place_name' => $place->place_name, 'min_date' => Carbon::parse($place->min_date)->format('Y-m-d'), 'total' => floatval($month_s_data[$place])];
                    }
                }
                // bthoor
                if ($emp['role'] == 14) {
                    $month_b_index = array_key_exists($place['place_name'], $month_b_data);
                    if ($month_b_index) {
                        $emp['month_total'][$place['place_name']] =  [floatval($month_b_data[$place['place_name']]), $place['min_date'], $place['total']];
                    }
                }
                // mobedat
                if ($emp['role'] == 43) {
                    $month_m_index = array_key_exists($place['place_name'], $month_m_data);
                    if ($month_m_index) {
                        $emp['month_total'][$place['place_name']] =  [floatval($month_m_data[$place['place_name']]), $place['min_date'], $place['total']];
                    }
                }
            }

            foreach ($year_name_of_places as $place) {
//            foreach ($modified_month_places as $place) {
                // asmadah
                if ($emp['role'] == 40) {
                    $year_s_index = array_key_exists($place['place_name'], $year_s_data);
                    if ($year_s_index) {

//                        $a[$place] = floatval($month_s_data[$place]);
//                        if ($a) {
//                            array_push($emp, $a);
//                        }
//                        $emp['month_total'][] = [$place => floatval($month_s_data[$place])]; // good
//                        $emp['month_total'] = [$place => floatval($month_s_data[$place])]; // good
//                        $emp['month_total'] = $emp['month_total'] + floatval($month_s_data[$place]); // good
//                        $emp['month_total'] = $emp['month_total'] + floatval($month_s_data[$place]); // good

//                        array_push($emp['month_total'][$place], floatval($month_s_data[$place]));

                        $emp['year_total'][$place['place_name']] =  [floatval($year_s_data[$place['place_name']]), $place['min_date'], $place['total']];
//                        $emp['month_total'] =  ['place_name' => $place->place_name, 'min_date' => Carbon::parse($place->min_date)->format('Y-m-d'), 'total' => floatval($month_s_data[$place])];
                    }
                }
                // bthoor
                if ($emp['role'] == 14) {
                    $year_b_index = array_key_exists($place['place_name'], $year_b_data);
                    if ($year_b_index) {
                        $emp['year_total'][$place['place_name']] =  [floatval($year_b_data[$place['place_name']]), $place['min_date'], $place['total']];
                    }
                }
                // mobedat
                if ($emp['role'] == 43) {
                    $year_m_index = array_key_exists($place['place_name'], $year_m_data);
                    if ($year_m_index) {
                        $emp['year_total'][$place['place_name']] =  [floatval($year_m_data[$place['place_name']]), $place['min_date'], $place['total']];
                    }
                }
            }



            if ($emp['role'] == 40) {
                $emp['week_total'] = $week_s_total;
            }
            if ($emp['role'] == 14) {
                $emp['week_total'] = $week_b_total;
            }
            if ($emp['role'] == 43) {
                $emp['week_total'] = $week_m_total;
//                dd($emp['week_total']);
            }

            array_push($this->data, $emp);
        }

//        dd($this->data);

//        dd($this->wk_m_total);

//        dd($this->wk_m_total);

    }

    public function emailReport() {

        Mail::to(['mohammedsr@alyaseenagri.com', 'sadekr@alyaseenagri.com', 'mahmoud.alsabagh@alyaseenagri.com', 'atia.abdullah@alyaseenagri.com', 'mohamed.shaheen@alyaseenagri.com', 'mahmoud.salem@alyaseenagri.com', 'waleed.elhanafy@alyaseenagri.com'])->cc(['mohamed.shaban@alyaseenagri.com', 'basil.alrashed@alyaseenagri.com'])->queue(new MarketingSummaryEmail($this->data, $this->start_date, $this->end_date, $this->month_start_date, $this->month_end_date, $this->wk_s_total, $this->wk_m_total, $this->wk_b_total));
//        Mail::to('basil.alrashed@alyaseenagri.com')->queue(new MarketingSummaryEmail($this->data, $this->start_date, $this->end_date, $this->month_start_date, $this->month_end_date, $this->wk_s_total, $this->wk_m_total, $this->wk_b_total));
    }
}
