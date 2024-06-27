<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\PDO;
use Livewire\Component;
use function PHPUnit\Framework\isNull;

class CommissionReport extends Component
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

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('commission-report', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $result = 0;
        return view('livewire.commission-report')
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
        $days = intval($end_of_day->diffInDays($start_of_day) + 1);

        $result = DB::connection('sqlsrv')->select("SELECT tbl_profit.area as 'area_id', Arabic_Name as 'area_name', tbl_net_profit.net_profit, profit, inventory_total, total_postponed, (tbl_net_profit.net_profit-inventory_total-total_postponed) as 'total', concat('%', tbl_area_commission.area_commission) as 'area_commission', ((tbl_net_profit.net_profit-inventory_total-total_postponed)*(tbl_area_commission.area_commission/100)) as 'calculated_commission' FROM (SELECT tbl_out.area, SUM(tbl_out.profit_sp0+tbl_out.profit_sp1+tbl_out.profit_sp2) as profit FROM (
    select Employeecode,Employeename,branchName, area, SPL0, sum(Spl0Value - Spl0cost) as profit_sp0, SPL1, sum(Spl1Value - Spl1cost) as profit_sp1, SPL2, sum(Spl2Value - Spl2cost) as profit_sp2
        from (
            select distinct Employeecode,Employeename,branchName, area
        ,0 as SPL0,isnull((select sum(s0.value) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0Value
         ,isnull((select sum(s0.totalcost) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0cost

         ,1 as SPL1,isnull((select sum(s0.value) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
         ,isnull((select sum(s0.totalcost) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
          ,2 as SPL2,isnull((select sum(s0.value) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Value
         ,isnull((select sum(s0.totalcost) from salesEmployeebyspcialityandbranch s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Cost
        from (
            select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
        ,SpecialityCode,areamast.Arabic_Name as branchname, areamast.nodeno as area,
        sum(value*exchangerate+extrafieldstotal) as Value
        ,sum(totalcost) as totalcost from sinvoice ,productmast,areamasT,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
         and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time1
         group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
         union all
         select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,areamast.Arabic_Name as branchname, areamast.nodeno as area,-sum(value*exchangerate+extrafieldstotal) as Value,-sum(totalcost) as totalcost
         from pinvoice ,productmast,areamast
         ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
         and pidate>=:start_date2 and pidate<=:end_date_time2
and studentmast.nodeno>1
         group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
        ) as salesEmployeebyspcialityandbranch
        ) as tbl1
        group by tbl1.Employeecode,tbl1.Employeename,tbl1.branchName, area, tbl1.SPL0, tbl1.SPL1, tbl1.SPL2
        ) AS tbl_out
        where area = :area1
        group by tbl_out.area) as tbl_profit, (SELECT tbl_out.area_out as area, tbl_out.Arabic_Name, (Cost_in+Cost_out)*(0.01) as inventory_total FROM (Select DefAccounts.Area as area_out ,AreaMast.Arabic_Name, 'Out' as [Type]  ,SUM(-TotalCost) as Cost_out  From DeptMast , Sinvoice,productmast, DefAccounts, AreaMast
        where Deptmast.NodeNo = DefAccounts.DeptNodeNo and AreaMast.NodeNo = DefAccounts.Area and productmast.nodeno=productno and DeptMast.NodeNo = Department  And DoNotUpdateStock = 0    And (SIDate >= '07/01/2011' And SIDate <= :end_date_time6  )  And
        ProductNo in (select NodeNo from ProductMast) And Department in (select NodeNo from DeptMast)
        group by DefAccounts.Area , AreaMast.Arabic_Name) as tbl_out, (Select DefAccounts.Area as area_in , AreaMast.Arabic_Name, 'In' as [Type], sum(TotalCost) as Cost_in  From DeptMast , Pinvoice  ,productmast, DefAccounts, AreaMast
        where Deptmast.NodeNo = DefAccounts.DeptNodeNo and AreaMast.NodeNo = DefAccounts.Area and productmast.Nodeno=productno and Deptmast.NodeNo = Department  And DoNotUpdateStock = 0   And (PIDate >= '07/01/2011' And PIDate <= :end_date_time7  )  And ProductNo in (select NodeNo from ProductMast) And Department in (select NodeNo from DeptMast)
        group by DefAccounts.Area , AreaMast.Arabic_Name
        ) as tbl_in,(SELECT DeptNodeNo,Area FROM DefAccounts) as tbl_dept_area
        where tbl_in.area_in = tbl_out.area_out
and tbl_out.area_out = tbl_dept_area.DeptNodeNo
and area_out = :area2) as tbl_inventory, (select tbl_balance_total.Area, SUM(tbl_balance_total.Balance)*0.01 as total_postponed from (select * from (
 Select distinct WarrentyInfo.SalesEmployee, B.CustomerNo, B.Area,AccMast.Name As CustomerName,AccMast.Arabic_Name As CustomerArabicName,AccMast.Code As CustomerCode ,AccMast.Type as CustomerType, case when (select Sum(Total) from BillWise where  CustomerNo = B.CustomerNo and VoucherDate <=:end_date_time3 ) <> 0  then (select Sum(Total) from BillWise where  CustomerNo
= B.CustomerNo and  VoucherDate <=:end_date_time8 ) else 0 end as Balance From BillWise B,accmast, WarrentyInfo  Where B.CustomerNo = AccMast.NodeNo and B.CustomerNo = WarrentyInfo.AccountNo and NodeNo in (select AccountNo from WarrentyInfo where AccountStatus in ('عملاء نشيطين لدى الفرع'  )) And  (AccMast.Type = 9 OR AccMast.Type = 10)
And  (VoucherDate <=  :end_date_time9) And (B.Type = 'N')
and CustomerNo in (SELECT distinct WarrentyInfo.AccountNo FROM WarrentyInfo, accmast where AccountStatus in ('عملاء نشيطين لدى الفرع'  ) and WarrentyInfo.AccountNo = accmast.NodeNo and Type = 10 and accmast.Accmast_Department = :area3)
And (PDC = 'N')
and Area = :area7) as tbl_tameer
where Balance >= 1
or Balance <= -1) as tbl_balance_total
        group by tbl_balance_total.Area) as tbl_postponed, (select area, area_commission from tbl_area_commission where area = :area4) AS tbl_area_commission, (select tbl_income.area, sum(income+expenses) as 'net_profit' from (Select AreaMast.NodeNo as area, sum(AmountCr*ExchangeRate-AmountDr*ExchangeRate) as income From PurchaseData,AccMast,AreaMast Where (AreaMast.NodeNo = :area5   ) And AreaMast.NodeNo = Area And  DoNotUpdateAccounts=0 And  VoucherDate >= :start_date3 And VoucherDate <= :end_date_time4 And AccMast.NodeNo = AccountDr And ( AccMast.Type = 4 OR AccMast.Type = 2) group by AreaMast.NodeNo) as tbl_income, (Select AreaMast.NodeNo as area, sum(AmountCr*ExchangeRate-AmountDr*ExchangeRate) as expenses From PurchaseData,AccMast,AreaMast Where (AreaMast.NodeNo = :area6   ) And AreaMast.NodeNo = Area And  DoNotUpdateAccounts=0 And  VoucherDate >= :start_date4 And VoucherDate <= :end_date_time5 And AccMast.NodeNo = AccountDr And ( AccMast.Type = 5 OR AccMast.Type = 3) group by AreaMast.NodeNo) as tbl_expenses
        where tbl_income.area = tbl_expenses.area
        group by tbl_income.area) as tbl_net_profit
        WHERE tbl_profit.area = tbl_inventory.area
AND tbl_inventory.area = tbl_postponed.Area
AND tbl_postponed.Area = tbl_area_commission.area
AND tbl_area_commission.area = tbl_net_profit.area", ['area1' => $this->area_id, 'area2' => $this->area_id, 'area3' => $this->area_id, 'area4' => $this->area_id, 'area5' => $this->area_id, 'area6' => $this->area_id, 'area7' => $this->area_id, 'start_date1' => $this->first_date, 'start_date2' => $this->first_date, 'start_date3' => $this->first_date, 'start_date4' => $this->first_date, 'end_date_time1' => $this->last_date . " 23:59:23", 'end_date_time2' => $this->last_date . " 23:59:23", 'end_date_time3' => $this->last_date . " 23:59:23", 'end_date_time4' => $this->last_date . " 23:59:23", 'end_date_time5' => $this->last_date . " 23:59:23", 'end_date_time6' => $this->last_date . " 23:59:23", 'end_date_time7' => $this->last_date . " 23:59:23", 'end_date_time8' => $this->last_date . " 23:59:23", 'end_date_time9' => $this->last_date . " 23:59:23", /*'end_date_time10' => $this->last_date . " 23:59:23", 'end_date_time11' => $this->last_date . " 23:59:23", 'end_date_time12' => $this->last_date . " 23:59:23"*/]);

        $this->result = count((array)$result) > 0 ? (array)$result[0] : null;

        $tbl2_result = DB::connection('sqlsrv')->select("select distinct *, /* start of employee_postponed*/ (select SUM(DueAmount) as employee_postponed from (
     select * from (
 Select distinct StudentMast.Code as EmpCode, WarrentyInfo.SalesEmployee, B.CustomerNo, B.Area,AccMast.Name As CustomerName,AccMast.Arabic_Name As CustomerArabicName,AccMast.Code As CustomerCode ,AccMast.Type as CustomerType, case when (select Sum(Total) from BillWise where  CustomerNo = B.CustomerNo and VoucherDate <=:end_date_time1 ) <> 0  then (select Sum(Total) from BillWise where  CustomerNo
= B.CustomerNo and  VoucherDate <=:end_date_time2 ) else 0 end as DueAmount From BillWise B,accmast, WarrentyInfo, StudentMast Where StudentMast.NodeNo=WarrentyInfo.SalesEmployee and B.CustomerNo = AccMast.NodeNo and B.CustomerNo = WarrentyInfo.AccountNo and accmast.NodeNo in (select AccountNo from WarrentyInfo where AccountStatus in ('عملاء نشيطين لدى الفرع'  )) And  (AccMast.Type = 9 OR AccMast.Type = 10)
And  (VoucherDate <=  :end_date_time3) And (B.Type = 'N')
and CustomerNo in (SELECT distinct WarrentyInfo.AccountNo FROM WarrentyInfo, accmast where AccountStatus in ('عملاء نشيطين لدى الفرع'  ) and WarrentyInfo.AccountNo = accmast.NodeNo and accmast.Type = 10 and accmast.Accmast_Department = :area9)
And (PDC = 'N')
and Area = :area11) as tbl_tameer
where DueAmount >= 1
or DueAmount <= -1
) as tbl1
where (EmpCode != '' and EmpCode = tb1.Employeecode)
group by EmpCode) as employee_postponed /* end of employee_postponed*/, /*start of employee_postponed_due */ (select SUM(DueAmount) as postponed_due from (select * from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time7),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time8),0.00) as DueAmount
,:end_date_time9 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time10),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time11),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time72
and (accmast.Code like '0%' or accmast.Code like '1%' or accmast.Code like '1-%')
) as tbl
where DATEDIFF(day, VoucherDate, :end_date_time73) > :days1
) as tbl_old_vouchers
where EmpCode = tb1.Employeecode) as employee_postponed_due /*end of employee_postponed_due */, /* start of oldest_voucher tbl*/(select top 1 VoucherDate as oldest_voucher from (
select isnull((select top 1 StudentMast.Code from WarrentyInfo, StudentMast where StudentMast.NodeNo=WarrentyInfo.SalesEmployee  and AccountNo=accmast.NodeNo order by StudentMast.Code desc),'') as EmpCode
,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time15),0.00) as paid,
total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time16),0.00) as DueAmount
,:end_date_time17 as cutdate,Area
from billwise ,accmast,areamast, WarrentyInfo
where customerno=accmast.nodeno and areamast.nodeno=area
and WarrentyInfo.AccountNo = accmast.NodeNo
and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع'  )
and billwise.[type]='N'
and (total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time18),0.00) >=1
or total+
isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time19),0.00) <=-1)
and accmast.[Type]=10
and voucherdate <=:end_date_time74
and (accmast.Code like '0%' or accmast.Code like '1%' or accmast.Code like '1-%')
) as tbl
where DATEDIFF(day, VoucherDate, :end_date_time75) > :days2
and EmpCode = tb1.Employeecode
order by EmpCode, VoucherDate asc) as oldest_voucher /* end of oldest_voucher tbl*/ from /* start of tb1*/ (SELECT Employeecode, EmployeeName, branchname, tbl_left_emp_commission.area, area_commission, area_profit, tot, employee_profit, percentage_area_employee, tbl_left_emp_commission.employee_commission, role, sales_manager, area_manager, store_manager, mat_dev_manager1, mat_dev_manager2, (tbl_left_emp_commission.employee_commission*(tbl_employees_position.sales_manager/100)) as calc_sales_manager, (tbl_left_emp_commission.employee_commission*(tbl_employees_position.area_manager/100)) as calc_area_manager, (tbl_left_emp_commission.employee_commission*(tbl_employees_position.store_manager/100)) as calc_store_manager, (tbl_left_emp_commission.employee_commission*(tbl_employees_position.mat_dev_manager1/100)) as calc_mat_dev1, (tbl_left_emp_commission.employee_commission*(tbl_employees_position.mat_dev_manager2/100)) as calc_mat_dev2 FROM (select Employeecode, EmployeeName, branchname, area, area_commission, area_profit, employee_profit, ((employee_profit/area_profit)*100) as percentage_area_employee, ((((employee_profit/area_profit)*100)*area_commission)/100) as employee_commission, tbl_cols0.tot from (SELECT * FROM (SELECT tbl_employee_profit.Employeecode, tbl_employee_profit.EmployeeName, tbl_employee_profit.branchname, tbl_employee_profit.area, tbl_employee_profit.tot, SUM(tbl_employee_profit.profit_sp0+tbl_employee_profit.profit_sp1+tbl_employee_profit.profit_sp2) AS employee_profit FROM (select Employeecode,Employeename,branchName, area, SPL0, sum(Spl0Value - Spl0cost) as profit_sp0, SPL1, sum(Spl1Value - Spl1cost) as profit_sp1, SPL2, sum(Spl2Value - Spl2cost) as profit_sp2, SUM(Spl0Value+Spl1Value+Spl2Value) as tot
from (
    SELECT * FROM (select distinct Employeecode,Employeename,branchName, area
,0 as SPL0,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date27 and sIDate<=:end_date_time36
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date10 and pidate<=:end_date_time55
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date9 and sIDate<=:end_date_time56
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date28 and pidate<=:end_date_time57
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0cost

 ,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date11 and sIDate<=:end_date_time38
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date29 and pidate<=:end_date_time58
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date12 and sIDate<=:end_date_time39
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date30 and pidate<=:end_date_time59
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
  ,2 as SPL2,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date13 and sIDate<=:end_date_time40
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date31 and pidate<=:end_date_time60
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date14 and sIDate<=:end_date_time41
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date32 and pidate<=:end_date_time61
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Cost
from (
    select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, --productmast.Code,productmast.name,productmast.Arabic_Name,Deptmast.code as deptCode,deptmast.Name as deptName,
areamast.Arabic_Name as branchname, areamast.nodeno as area,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
   /* PinvoiceNo, SNo, SequenceNo, ProductNo, ActualQty, ExecutedQty, SIDate, Rate, Value, RefrenceNo, Executed, Department, Branch, Area, Salesman, DueDate, Pinvoice.CurrencySymbol, ExchangeRate, Pinvoice.SalesAccount,
                         InternalSNo, InternalRefrenceNo, VoucherCode, FreeQty, PartyNo, ExtraFieldsTotal, PartyBalance, Units, ConversionQty, VField0, VField1, VField2, VField3, VField4, VField5, VField6, VField7, VField8, VField9,
                         VField10, VField11, VField12, VField13, VField14, VField15, VField16, VField17, VField18, VField19, BillNo, DoNotUpdateStock, FieldPtr, ActualVoucherPrefix, CostCenter, Project, Car, Customer, Supplier, Student,
                         AvgRate, TotalCost*/
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
and sIDate>=:start_date1 and sIDate<=:end_date_time23
--and productmast.code='220005'

 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
 union all
 select --productmast.Code,productmast.name,productmast.Arabic_Name,Deptmast.code as deptCode,deptmast.Name as deptName,
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname, areamast.nodeno as area,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
   /*pInvoiceNo, SNo, SequenceNo, ProductNo, -ActualQty as actualqty, ExecutedQty, pIDate, Rate, -Value as value, RefrenceNo, Executed, Department, Branch, Area, Salesman, DueDate, pinvoice.CurrencySymbol, ExchangeRate, pinvoice.PurchaseAccount,
                         InternalSNo, InternalRefrenceNo, VoucherCode, FreeQty, PartyNo, -ExtraFieldsTotal as ExtraFieldsTotal, -PartyBalance as PartyBalance, Units, ConversionQty, VField0, VField1, VField2, VField3, VField4, VField5, VField6, VField7, VField8, VField9,
                         VField10, VField11, VField12, VField13, VField14, VField15, VField16, VField17, VField18, VField19, BillNo, DoNotUpdateStock, FieldPtr, ActualVoucherPrefix, CostCenter, Project, Car, Customer, Supplier, Student,
                         AvgRate, TotalCost*/
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date2 and pidate<=:end_date_time24
and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
) as salesEmployeebyspcialityandbranch) as t1, (select area as area_t2, emp_id from tbl_emp_position) as t2
where t1.Employeecode = t2.emp_id
and t1.area = t2.area_t2
) as tbl1
where area = :area1
group by tbl1.Employeecode,tbl1.Employeename,tbl1.branchName, area, tbl1.SPL0, tbl1.SPL1, tbl1.SPL2) AS tbl_employee_profit
GROUP BY tbl_employee_profit.Employeecode, tbl_employee_profit.EmployeeName, tbl_employee_profit.branchname, tbl_employee_profit.area, tbl_employee_profit.tot) as tbl_employee_profit, /* start of area commission */(SELECT ((tbl_net_profit.net_profit-inventory_total-total_postponed)*(tbl_area_commission.area_commission/100)) as 'area_commission' FROM (SELECT tbl_out.area, SUM(tbl_out.profit_sp0+tbl_out.profit_sp1+tbl_out.profit_sp2) as profit FROM (
    select Employeecode,Employeename,branchName, area, SPL0, sum(Spl0Value - Spl0cost) as profit_sp0, SPL1, sum(Spl1Value - Spl1cost) as profit_sp1, SPL2, sum(Spl2Value - Spl2cost) as profit_sp2
from (
    select distinct Employeecode,Employeename,branchName, area
,0 as SPL0,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date15 and sIDate<=:end_date_time42
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date33 and pidate<=:end_date_time62
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date16 and sIDate<=:end_date_time43
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date34 and pidate<=:end_date_time63
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0cost

,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date17 and sIDate<=:end_date_time44
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date35 and pidate<=:end_date_time64
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date18 and sIDate<=:end_date_time45
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date36 and pidate<=:end_date_time65
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
,2 as SPL2,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date19 and sIDate<=:end_date_time46
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date37 and pidate<=:end_date_time66
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Value
,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date20 and sIDate<=:end_date_time47
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date38 and pidate<=:end_date_time67
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Cost
from (
    select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode,areamast.Arabic_Name as branchname, areamast.nodeno as area,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost from sinvoice ,productmast,areamasT,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
and studentmast.nodeno>1
and sIDate>=:start_date3 and sIDate<=:end_date_time25
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
union all
select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,areamast.Arabic_Name as branchname, areamast.nodeno as area,-sum(value*exchangerate+extrafieldstotal) as Value,-sum(totalcost) as totalcost
from pinvoice ,productmast,areamast
,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
and pidate>=:start_date4 and pidate<=:end_date_time26
and studentmast.nodeno>1
group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
) as salesEmployeebyspcialityandbranch
) as tbl1
group by tbl1.Employeecode,tbl1.Employeename,tbl1.branchName, area, tbl1.SPL0, tbl1.SPL1, tbl1.SPL2
) AS tbl_out
where area = :area2
group by tbl_out.area) as tbl_profit, (SELECT tbl_out.area_out as area, tbl_out.Arabic_Name, (Cost_in+Cost_out)*(0.01) as inventory_total FROM (Select DefAccounts.Area as area_out ,AreaMast.Arabic_Name, 'Out' as [Type]  ,SUM(-TotalCost) as Cost_out  From DeptMast , Sinvoice,productmast, DefAccounts, AreaMast
where Deptmast.NodeNo = DefAccounts.DeptNodeNo and AreaMast.NodeNo = DefAccounts.Area and productmast.nodeno=productno and DeptMast.NodeNo = Department  And DoNotUpdateStock = 0    And (SIDate >= '07/01/2011' And SIDate <= :end_date_time27  )  And
ProductNo in (select NodeNo from ProductMast) And Department in (select NodeNo from DeptMast)
group by DefAccounts.Area , AreaMast.Arabic_Name) as tbl_out, (Select DefAccounts.Area as area_in , AreaMast.Arabic_Name, 'In' as [Type], sum(TotalCost) as Cost_in  From DeptMast , Pinvoice  ,productmast, DefAccounts, AreaMast
where Deptmast.NodeNo = DefAccounts.DeptNodeNo and AreaMast.NodeNo = DefAccounts.Area and productmast.Nodeno=productno and Deptmast.NodeNo = Department  And DoNotUpdateStock = 0   And (PIDate >= '07/01/2011' And PIDate <= :end_date_time28  )  And ProductNo in (select NodeNo from ProductMast) And Department in (select NodeNo from DeptMast)
group by DefAccounts.Area , AreaMast.Arabic_Name
) as tbl_in,(SELECT DeptNodeNo,Area FROM DefAccounts) as tbl_dept_area
where tbl_in.area_in = tbl_out.area_out
and tbl_out.area_out = tbl_dept_area.DeptNodeNo
and area_out = :area3) as tbl_inventory, (select tbl_balance_total.Area, SUM(tbl_balance_total.Balance)*0.01 as total_postponed from (SELECT * FROM (Select distinct B.Area, B.CustomerNo,AccMast.Arabic_Name As CustomerArabicName,AccMast.Code As CustomerCode,
(select Sum(Total) from BillWise where  CustomerNo = B.CustomerNo and VoucherDate <=:end_date_time29 ) as Balance
From BillWise B,AccMast  Where B.CustomerNo = AccMast.NodeNo and NodeNo in (select AccountNo from WarrentyInfo where AccountStatus in ('عملاء نشيطين لدى الفرع'  ))
And  (AccMast.Type = 9 OR AccMast.Type = 10)
And (VoucherDate <=  :end_date_time31)
And (B.Type = 'N')
And Area = :area4
and accmast.Type = 10
and accmast.Accmast_Department = :area5
and (Code like '0%' or Code like '1%')
) as tbl_b
--WHERE tbl_b.Balance > 0.01
WHERE tbl_b.Balance >= 1
or tbl_b.Balance <= -1) as tbl_balance_total
group by tbl_balance_total.Area) as tbl_postponed, (select area, area_commission from tbl_area_commission where area = :area6) AS tbl_area_commission, (select tbl_income.area, sum(income+expenses) as 'net_profit' from (Select AreaMast.NodeNo as area, sum(AmountCr*ExchangeRate-AmountDr*ExchangeRate) as income From PurchaseData,AccMast,AreaMast Where (AreaMast.NodeNo = :area7   ) And AreaMast.NodeNo = Area And  DoNotUpdateAccounts=0 And  VoucherDate >= :start_date5 And VoucherDate <= :end_date_time32 And AccMast.NodeNo = AccountDr And ( AccMast.Type = 4 OR AccMast.Type = 2) group by AreaMast.NodeNo) as tbl_income, (Select AreaMast.NodeNo as area, sum(AmountCr*ExchangeRate-AmountDr*ExchangeRate) as expenses From PurchaseData,AccMast,AreaMast Where (AreaMast.NodeNo = :area8   ) And AreaMast.NodeNo = Area And  DoNotUpdateAccounts=0 And  VoucherDate >= :start_date6 And VoucherDate <= :end_date_time33 And AccMast.NodeNo = AccountDr And ( AccMast.Type = 5 OR AccMast.Type = 3) group by AreaMast.NodeNo) as tbl_expenses
where tbl_income.area = tbl_expenses.area
group by tbl_income.area) as tbl_net_profit
WHERE tbl_profit.area = tbl_inventory.area
AND tbl_inventory.area = tbl_postponed.Area
AND tbl_postponed.Area = tbl_area_commission.area
AND tbl_area_commission.area = tbl_net_profit.area) /*end of area commission */ as tbl_area_commission) as tbl_cols0, (SELECT SUM(tbl_area_profit.employee_profit) as area_profit FROM (SELECT tbl_employee_profit.Employeecode, tbl_employee_profit.EmployeeName, tbl_employee_profit.branchname, tbl_employee_profit.area, SUM(tbl_employee_profit.profit_sp0+tbl_employee_profit.profit_sp1+tbl_employee_profit.profit_sp2) AS employee_profit FROM (select Employeecode,Employeename,branchName, area, SPL0, sum(Spl0Value - Spl0cost) as profit_sp0, SPL1, sum(Spl1Value - Spl1cost) as profit_sp1, SPL2, sum(Spl2Value - Spl2cost) as profit_sp2
from (
    SELECT * FROM (select distinct Employeecode,Employeename,branchName, area
,0 as SPL0,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date21 and sIDate<=:end_date_time48
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date39 and pidate<=:end_date_time68
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date22 and sIDate<=:end_date_time49
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date40 and pidate<=:end_date_time69
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch.Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='0'),0) as Spl0cost

 ,1 as SPL1,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date23 and sIDate<=:end_date_time50
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date41 and pidate<=:end_date_time70
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date24 and sIDate<=:end_date_time51
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date42 and pidate<=:end_date_time71
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='1'),0) as Spl1Cost
  ,2 as SPL2,isnull((select sum(s0.value) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date25 and sIDate<=:end_date_time52
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date43 and pidate<=:end_date_time37
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Value
 ,isnull((select sum(s0.totalcost) from (select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, areamast.Arabic_Name as branchname,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
  and area=areamast.nodeno
 and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
 and sIDate>=:start_date26 and sIDate<=:end_date_time53
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name
 union all
 select studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
  and area=areamast.nodeno
 and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date44 and pidate<=:end_date_time54
  and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name) as s0 where s0.Employeecode=salesEmployeebyspcialityandbranch. Employeecode
and s0.branchname=salesEmployeebyspcialityandbranch.branchname
and s0.SpecialityCode='2'),0) as Spl2Cost
from (
    select studentmast.code as Employeecode,studentmast.arabic_name as EmployeeName
,SpecialityCode, --productmast.Code,productmast.name,productmast.Arabic_Name,Deptmast.code as deptCode,deptmast.Name as deptName,
areamast.Arabic_Name as branchname, areamast.nodeno as area,
sum(value*exchangerate+extrafieldstotal) as Value
,sum(totalcost) as totalcost
   /* PinvoiceNo, SNo, SequenceNo, ProductNo, ActualQty, ExecutedQty, SIDate, Rate, Value, RefrenceNo, Executed, Department, Branch, Area, Salesman, DueDate, Pinvoice.CurrencySymbol, ExchangeRate, Pinvoice.SalesAccount,
                         InternalSNo, InternalRefrenceNo, VoucherCode, FreeQty, PartyNo, ExtraFieldsTotal, PartyBalance, Units, ConversionQty, VField0, VField1, VField2, VField3, VField4, VField5, VField6, VField7, VField8, VField9,
                         VField10, VField11, VField12, VField13, VField14, VField15, VField16, VField17, VField18, VField19, BillNo, DoNotUpdateStock, FieldPtr, ActualVoucherPrefix, CostCenter, Project, Car, Customer, Supplier, Student,
                         AvgRate, TotalCost*/
 from sinvoice ,productmast,areamasT
 ,studentmast where studentmast.NODENO=student AND productno=productmast.nodeno
and area=areamast.nodeno
and (sinvoiceno like '210-%' or sinvoiceno like '213-%')
 and studentmast.nodeno>1
and sIDate>=:start_date7 and sIDate<=:end_date_time34
--and productmast.code='220005'

 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
 union all
 select --productmast.Code,productmast.name,productmast.Arabic_Name,Deptmast.code as deptCode,deptmast.Name as deptName,
   studentmast.code as Employeecode, studentmast.Arabic_Name as Employeename ,SpecialityCode,
      areamast.Arabic_Name as branchname, areamast.nodeno as area,
-sum(value*exchangerate+extrafieldstotal) as Value
  ,-sum(totalcost) as totalcost
   /*pInvoiceNo, SNo, SequenceNo, ProductNo, -ActualQty as actualqty, ExecutedQty, pIDate, Rate, -Value as value, RefrenceNo, Executed, Department, Branch, Area, Salesman, DueDate, pinvoice.CurrencySymbol, ExchangeRate, pinvoice.PurchaseAccount,
                         InternalSNo, InternalRefrenceNo, VoucherCode, FreeQty, PartyNo, -ExtraFieldsTotal as ExtraFieldsTotal, -PartyBalance as PartyBalance, Units, ConversionQty, VField0, VField1, VField2, VField3, VField4, VField5, VField6, VField7, VField8, VField9,
                         VField10, VField11, VField12, VField13, VField14, VField15, VField16, VField17, VField18, VField19, BillNo, DoNotUpdateStock, FieldPtr, ActualVoucherPrefix, CostCenter, Project, Car, Customer, Supplier, Student,
                         AvgRate, TotalCost*/
 from pinvoice ,productmast,areamast
 ,studentmast where studentmast.nodeno=student and productno=productmast.nodeno
and area=areamast.nodeno
and (pinvoiceno like '211-%' or pinvoiceno like '212-%')
 and pidate>=:start_date8 and pidate<=:end_date_time35
and studentmast.nodeno>1
 group by studentmast.code,studentmast.arabic_name,SpecialityCode,areamast.Arabic_Name, areamast.nodeno
) as salesEmployeebyspcialityandbranch) as t1, (select area as area_t2, emp_id from tbl_emp_position) as t2
where t1.Employeecode = t2.emp_id
and t1.area = t2.area_t2
) as tbl1
where area = :area10
group by tbl1.Employeecode,tbl1.Employeename,tbl1.branchName, area, tbl1.SPL0, tbl1.SPL1, tbl1.SPL2) AS tbl_employee_profit
GROUP BY tbl_employee_profit.Employeecode, tbl_employee_profit.EmployeeName, tbl_employee_profit.branchname, tbl_employee_profit.area) as tbl_area_profit) as tbl_area_profit) as tbl_left_emp_commission, (select tbl_emp_position.area, tbl_emp_position.emp_id, tbl_emp_position.role, tbl_position_commission.sales_manager, tbl_position_commission.area_manager, tbl_position_commission.store_manager, tbl_position_commission.mat_dev_manager1, tbl_position_commission.mat_dev_manager2  from tbl_emp_position, tbl_area_commission, tbl_position_commission
where tbl_emp_position.area = tbl_area_commission.area
and tbl_emp_position.role = tbl_position_commission.role) as tbl_employees_position
where tbl_left_emp_commission.area = tbl_employees_position.area
and tbl_left_emp_commission.Employeecode = tbl_employees_position.emp_id) as tb1/* end of tb1*/ /*start of tb2*/",
            [/*'area1' => $this->area_id,*/
                'days1' => $days,
                'days2' => $days,
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
//                'area12' => $this->area_id,
//                'area13' => $this->area_id,
//                'area14' => $this->area_id,
//                'area15' => $this->area_id,
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
                'start_date15' => $this->first_date,
                'start_date16' => $this->first_date,
                'start_date17' => $this->first_date,
                'start_date18' => $this->first_date,
                'start_date19' => $this->first_date,
                'start_date20' => $this->first_date,
                'start_date21' => $this->first_date,
                'start_date22' => $this->first_date,
                'start_date23' => $this->first_date,
                'start_date24' => $this->first_date,
                'start_date25' => $this->first_date,
                'start_date26' => $this->first_date,
                'start_date27' => $this->first_date,
                'start_date28' => $this->first_date,
                'start_date29' => $this->first_date,
                'start_date30' => $this->first_date,
                'start_date31' => $this->first_date,
                'start_date32' => $this->first_date,
                'start_date33' => $this->first_date,
                'start_date34' => $this->first_date,
                'start_date35' => $this->first_date,
                'start_date36' => $this->first_date,
                'start_date37' => $this->first_date,
                'start_date38' => $this->first_date,
                'start_date39' => $this->first_date,
                'start_date40' => $this->first_date,
                'start_date41' => $this->first_date,
                'start_date42' => $this->first_date,
                'start_date43' => $this->first_date,
                'start_date44' => $this->first_date,
                'end_date_time1' => $this->last_date . " 23:59:23",
                'end_date_time2' => $this->last_date . " 23:59:23",
                'end_date_time3' => $this->last_date . " 23:59:23",
//                'end_date_time4' => $this->last_date . " 23:59:23",
//                'end_date_time5' => $this->last_date . " 23:59:23",
//                'end_date_time6' => $this->last_date . " 23:59:23",
                'end_date_time7' => $this->last_date . " 23:59:23",
                'end_date_time8' => $this->last_date . " 23:59:23",
                'end_date_time9' => $this->last_date . " 23:59:23",
                'end_date_time10' => $this->last_date . " 23:59:23",
                'end_date_time11' => $this->last_date . " 23:59:23",
//                'end_date_time12' => $this->last_date . " 23:59:23",
//                'end_date_time13' => $this->last_date . " 23:59:23",
//                'end_date_time14' => $this->last_date . " 23:59:23",
                'end_date_time15' => $this->last_date . " 23:59:23",
                'end_date_time16' => $this->last_date . " 23:59:23",
                'end_date_time17' => $this->last_date . " 23:59:23",
                'end_date_time18' => $this->last_date . " 23:59:23",
                'end_date_time19' => $this->last_date . " 23:59:23",
//                'end_date_time20' => $this->last_date . " 23:59:23",
//                'end_date_time21' => $this->last_date . " 23:59:23",
//                'end_date_time22' => $this->last_date . " 23:59:23",
                'end_date_time23' => $this->last_date . " 23:59:23",
                'end_date_time24' => $this->last_date . " 23:59:23",
                'end_date_time25' => $this->last_date . " 23:59:23",
                'end_date_time26' => $this->last_date . " 23:59:23",
                'end_date_time27' => $this->last_date . " 23:59:23",
                'end_date_time28' => $this->last_date . " 23:59:23",
                'end_date_time29' => $this->last_date . " 23:59:23",
//            'end_date_time30' => $this->last_date . " 23:59:23",
                'end_date_time31' => $this->last_date . " 23:59:23",
                'end_date_time32' => $this->last_date . " 23:59:23",
                'end_date_time33' => $this->last_date . " 23:59:23",
                'end_date_time34' => $this->last_date . " 23:59:23",
                'end_date_time35' => $this->last_date . " 23:59:23",
                'end_date_time36' => $this->last_date . " 23:59:23",
                'end_date_time37' => $this->last_date . " 23:59:23",
                'end_date_time38' => $this->last_date . " 23:59:23",
                'end_date_time39' => $this->last_date . " 23:59:23",
                'end_date_time40' => $this->last_date . " 23:59:23",
                'end_date_time41' => $this->last_date . " 23:59:23",
                'end_date_time42' => $this->last_date . " 23:59:23",
                'end_date_time43' => $this->last_date . " 23:59:23",
                'end_date_time44' => $this->last_date . " 23:59:23",
                'end_date_time45' => $this->last_date . " 23:59:23",
                'end_date_time46' => $this->last_date . " 23:59:23",
                'end_date_time47' => $this->last_date . " 23:59:23",
                'end_date_time48' => $this->last_date . " 23:59:23",
                'end_date_time49' => $this->last_date . " 23:59:23",
                'end_date_time50' => $this->last_date . " 23:59:23",
                'end_date_time51' => $this->last_date . " 23:59:23",
                'end_date_time52' => $this->last_date . " 23:59:23",
                'end_date_time53' => $this->last_date . " 23:59:23",
                'end_date_time54' => $this->last_date . " 23:59:23",
                'end_date_time55' => $this->last_date . " 23:59:23",
                'end_date_time56' => $this->last_date . " 23:59:23",
                'end_date_time57' => $this->last_date . " 23:59:23",
                'end_date_time58' => $this->last_date . " 23:59:23",
                'end_date_time59' => $this->last_date . " 23:59:23",
                'end_date_time60' => $this->last_date . " 23:59:23",
                'end_date_time61' => $this->last_date . " 23:59:23",
                'end_date_time62' => $this->last_date . " 23:59:23",
                'end_date_time63' => $this->last_date . " 23:59:23",
                'end_date_time64' => $this->last_date . " 23:59:23",
                'end_date_time65' => $this->last_date . " 23:59:23",
                'end_date_time66' => $this->last_date . " 23:59:23",
                'end_date_time67' => $this->last_date . " 23:59:23",
                'end_date_time68' => $this->last_date . " 23:59:23",
                'end_date_time69' => $this->last_date . " 23:59:23",
                'end_date_time70' => $this->last_date . " 23:59:23",
                'end_date_time71' => $this->last_date . " 23:59:23",
                'end_date_time72' => $this->last_date . " 23:59:23",
                'end_date_time73' => $this->last_date . " 23:59:23",
                'end_date_time74' => $this->last_date . " 23:59:23",
                'end_date_time75' => $this->last_date . " 23:59:23",]);


        $this->result_tbl2 = json_decode(json_encode($tbl2_result), true);
    }
}
