<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseRecommendation extends Component
{
    public $results = [];
    public $dist_days;
    public $item_type = "all_items";

    public $vendor_list = [];
    public $vendor_type = "vendor_all";
    public $show_results = false;

    public $product_code = "";

    protected $messages = [
        'product_code.required' => "مطلوب",
        'vendor_type.required' => "مطلوب",
        'item_type.required' => "مطلوب",
    ];

    protected $listeners = ['create-report' => 'createReport'];

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

        $this->vendor_list = AccMast::join('ProductMast', 'ProductMast.VendorNo', 'accmast.NodeNo')
            ->where('ProductMast.PriceList', 1)
            ->selectRaw('DISTINCT accmast.NodeNo, accmast.Arabic_Name')
            ->get();
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
            ->layout('layouts.dashboard');
    }

    public function createReport($item_type, $vendor_type, $product_code) {

//        dd($item_type);

        $this->results = [];

//        if ($item_type == "item_code") {
//            $this->validate([
//                'product_code' => 'required',
//                'item_type' => 'required',
//                'vendor_type' => 'required',
//            ]);
//        }
//        else {
//            $this->validate([
//                'item_type' => 'required',
//                'vendor_type' => 'required',
//            ]);
//        }
//        dd($item_type);

        $today = Carbon::today()->format('m/d/Y');
//        dd($today);

        if ($item_type == 'item_vendor') {
            $stmt = "SELECT * FROM (
SELECT tbl2.NodeNo,
	tbl2.Code,
	tbl2.Arabic_Name,
	tbl2.BaseUnits,
	tbl2.VendorNo,
	tbl2.Vendor_Code,
	tbl2.Vendor_ArName,
	ProductMast.LeadTime,
	ProductMast.ReOrderLevel1 as 'MinOrder',
	Qty_In-Qty_Out+Qty_In2 as 'Stock'
	FROM (
SELECT NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	SUM(Qty_In) as 'Qty_In',
	SUM(Qty_Out) as 'Qty_Out',
    SUM(Qty_in2) as 'Qty_In2'
	FROM (
SELECT Distinct
	NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<='".$today." 23:59:25'  And Department = V.Department   Group By ProductNo) as Qty_In ,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<='".$today." 23:59:25' And Department = V.Department    Group By ProductNo) as Qty_Out,
	case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today ." 23:59:25'  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today." 23:59:25'  )  ) else 0 end  as Qty_in2
	FROM (Select distinct
	NodeNo ,
	Code ,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
	-Sum(TotalCost) as Cost
	from SInvoice ,productmast
	Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND VendorNo = '". $vendor_type ."'
	And (SIDate <= '". $today ." 23:59:25'  )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
	union all
	Select distinct
	NodeNo ,
	Code,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
	Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND VendorNo = '". $vendor_type ."'
	And (PIDate <= '".$today." 23:59:25')
	group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
	group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName ) as tbl1
	group By NodeNo , Code , Arabic_Name ,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName) as tbl2 LEFT JOIN ProductMast ON tbl2.NodeNo = ProductMast.NodeNo
	--ORDER BY Vendor_Code
	) as tbl3
	LEFT JOIN
	(SELECT
	ProductNo,
	SUM(Qty) as Qty,
	SUM(ExecutedQty) as ExecutedQty,
	SUM(DeliveredQty) as DeliveredQty,
	SUM(case when Delivered = 'Delivered' THEN (Qty-ExecutedQty) +(ExecutedQty-DeliveredQty) ELSE  (Qty-ExecutedQty) END) as final_qty_a,

	SUM(QtyOrderd) as QtyOrdered,
	SUM(ExecutedQtyOrdered) as ExecutedQtyOrdered,
	SUM(DeliveredQtyOrdered) as DeliveredQtyOrdered,
	SUM((isnull(QtyOrderd,0)-isnull(ExecutedQtyOrdered, 0)) +(isnull(ExecutedQtyOrdered,0)-isnull(DeliveredQtyOrdered, 0))) as final_qty_ordered,
	(SUM(isnull(Qty, 0))-SUM(isnull(ExecutedQty, 0))) + (SUM(isnull(QtyOrderd,0))-SUM(isnull(ExecutedQtyOrdered, 0))) as final_qty
FROM (
Select
	ProductNo,
	Code,
	(Select Name From ProductMast Where NodeNo = ProductNo) as ProductName,
	ProductMast.Description,
	(Select Arabic_Name From ProductMast Where NodeNo = ProductNo) as ProductArName,
	BaseUnits ,
	(Select BaseArabicUnit From Units Where BaseUnit = BaseUnits) as BaseArabicUnits ,
	PODate ,
	Q.Department,
	(Select Name From DeptMast Where NodeNo = Q.Department) as Name,
	(Select Arabic_Name From DeptMast Where NodeNo = Q.Department) as Arabic_Name ,
	POrderNo as VoucherNo,
	Q.VField18 as AltRef ,
	case when Q.Executed = 0 then 'Open' else 'closed' end as [Open],
	case when (Select Top 1 ActualQty From POrder Where RefrenceNo = Q.POrderNo) >= 0 then 'Invoiced' else '' End as Invoiced ,
	case when (Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo /*and ProductNo = Q.ProductNo*/)) >= 0 then 'Delivered' else '' End as Delivered ,
	(Select Name From AccMast Where NodeNo = AccountNo) as Customer,(Select Arabic_Name From AccMast Where NodeNo = AccountNo) as Arabic_Customer,
	Sum(Q.value)  as Amount ,
	sum(field2) as net,
	sum(case when POrderNo like '280-%%' then ActualQty end) as Qty ,
	sum(case when POrderNo like '280-%%' then ExecutedQty end) as ExecutedQty,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '280-%%')), 0)  as DeliveredQty,

	sum(case when POrderNo like '290-%%' then ActualQty end) as QtyOrderd ,
	sum(case when POrderNo like '290-%%' then ExecutedQty end) as ExecutedQtyOrdered,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '290-%%')), 0)  as DeliveredQtyOrdered

	From POrder Q,Idetails,ProductMast ,extrafields
	where porderno=extrafields.voucherno
	And sequenceno = extrafields.sno
	and POrderNo = Idetails.VoucherNo
	And ProductNo = NodeNo
	And ProductNo in (SELECT ProductNo FROM ProductMast WHERE Pricelist = 1 AND VendorNo = '". $vendor_type ."') And
	Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND VendorNo = '". $vendor_type ."'
	And (PODate >= '01/01/2023' And PODate <= '". $today ." 23:59:25')
	And Executed = 0
	And (PorderNo like '280-%%' or PorderNo like '290-%%')
	--And (Select Name From DeptMast Where NodeNo = Q.Department) Not In (Select DeptName From DeptRights Where UserName ='HQ-BAlrashed')
	Group By Department ,PODate ,POrderNo ,VField18 ,Executed ,AccountNo,ProductNo,Code,BaseUnits ,Description --order by ProductNo ,POrderNo, Q.Department,POdate
) as tbl
GROUP BY ProductNo) as tbl4
ON tbl3.NodeNo = tbl4.ProductNo
ORDER BY Vendor_Code, Code";
        }
        else if ($item_type == 'item_code') {
            $stmt = "SELECT * FROM (
SELECT tbl2.NodeNo,
	tbl2.Code,
	tbl2.Arabic_Name,
	tbl2.BaseUnits,
	tbl2.VendorNo,
	tbl2.Vendor_Code,
	tbl2.Vendor_ArName,
	ProductMast.LeadTime,
	ProductMast.ReOrderLevel1 as 'MinOrder',
	Qty_In-Qty_Out+Qty_In2 as 'Stock'
	FROM (
SELECT NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	SUM(Qty_In) as 'Qty_In',
	SUM(Qty_Out) as 'Qty_Out',
    SUM(Qty_in2) as 'Qty_In2'
	FROM (
SELECT Distinct
	NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<='".$today." 23:59:25'  And Department = V.Department   Group By ProductNo) as Qty_In ,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<='".$today." 23:59:25' And Department = V.Department    Group By ProductNo) as Qty_Out,
	case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today ." 23:59:25'  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today." 23:59:25'  )  ) else 0 end  as Qty_in2
	FROM (Select distinct
	NodeNo ,
	Code ,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
	-Sum(TotalCost) as Cost
	from SInvoice ,productmast
	Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND Code = '". $product_code ."'
	And (SIDate <= '". $today ." 23:59:25'  )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
	union all
	Select distinct
	NodeNo ,
	Code,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
	Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND Code = '". $product_code ."'
	And (PIDate <= '".$today." 23:59:25')
	group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
	group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName ) as tbl1
	group By NodeNo , Code , Arabic_Name ,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName) as tbl2 LEFT JOIN ProductMast ON tbl2.NodeNo = ProductMast.NodeNo
	--ORDER BY Vendor_Code
	) as tbl3
	LEFT JOIN
	(SELECT
	ProductNo,
	SUM(Qty) as Qty,
	SUM(ExecutedQty) as ExecutedQty,
	SUM(DeliveredQty) as DeliveredQty,
	SUM(case when Delivered = 'Delivered' THEN (Qty-ExecutedQty) +(ExecutedQty-DeliveredQty) ELSE  (Qty-ExecutedQty) END) as final_qty_a,

	SUM(QtyOrderd) as QtyOrdered,
	SUM(ExecutedQtyOrdered) as ExecutedQtyOrdered,
	SUM(DeliveredQtyOrdered) as DeliveredQtyOrdered,
	SUM((isnull(QtyOrderd,0)-isnull(ExecutedQtyOrdered, 0)) +(isnull(ExecutedQtyOrdered,0)-isnull(DeliveredQtyOrdered, 0))) as final_qty_ordered,
	(SUM(isnull(Qty, 0))-SUM(isnull(ExecutedQty, 0))) + (SUM(isnull(QtyOrderd,0))-SUM(isnull(ExecutedQtyOrdered, 0))) as final_qty
FROM (
Select
	ProductNo,
	Code,
	(Select Name From ProductMast Where NodeNo = ProductNo) as ProductName,
	ProductMast.Description,
	(Select Arabic_Name From ProductMast Where NodeNo = ProductNo) as ProductArName,
	BaseUnits ,
	(Select BaseArabicUnit From Units Where BaseUnit = BaseUnits) as BaseArabicUnits ,
	PODate ,
	Q.Department,
	(Select Name From DeptMast Where NodeNo = Q.Department) as Name,
	(Select Arabic_Name From DeptMast Where NodeNo = Q.Department) as Arabic_Name ,
	POrderNo as VoucherNo,
	Q.VField18 as AltRef ,
	case when Q.Executed = 0 then 'Open' else 'closed' end as [Open],
	case when (Select Top 1 ActualQty From POrder Where RefrenceNo = Q.POrderNo) >= 0 then 'Invoiced' else '' End as Invoiced ,
	case when (Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo /*and ProductNo = Q.ProductNo*/)) >= 0 then 'Delivered' else '' End as Delivered ,
	(Select Name From AccMast Where NodeNo = AccountNo) as Customer,(Select Arabic_Name From AccMast Where NodeNo = AccountNo) as Arabic_Customer,
	Sum(Q.value)  as Amount ,
	sum(field2) as net,

	sum(case when POrderNo like '280-%%' then ActualQty end) as Qty ,
	sum(case when POrderNo like '280-%%' then ExecutedQty end) as ExecutedQty,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '280-%%')), 0)  as DeliveredQty,

	sum(case when POrderNo like '290-%%' then ActualQty end) as QtyOrderd ,
	sum(case when POrderNo like '290-%%' then ExecutedQty end) as ExecutedQtyOrdered,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '290-%%')), 0)  as DeliveredQtyOrdered

	From POrder Q,Idetails,ProductMast ,extrafields
	where porderno=extrafields.voucherno
	And sequenceno = extrafields.sno
	and POrderNo = Idetails.VoucherNo
	And ProductNo = NodeNo
	And ProductNo in (SELECT ProductNo FROM ProductMast WHERE Pricelist = 1 AND Code = '". $product_code ."') And
	Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	AND Code = '". $product_code ."'
	And (PODate >= '01/01/2023' And PODate <= '". $today ." 23:59:25')
	And Executed = 0
	And (PorderNo like '280-%%' or PorderNo like '290-%%')
	--And (Select Name From DeptMast Where NodeNo = Q.Department) Not In (Select DeptName From DeptRights Where UserName ='HQ-BAlrashed')
	Group By Department ,PODate ,POrderNo ,VField18 ,Executed ,AccountNo,ProductNo,Code,BaseUnits ,Description --order by ProductNo ,POrderNo, Q.Department,POdate
) as tbl
GROUP BY ProductNo) as tbl4
ON tbl3.NodeNo = tbl4.ProductNo
ORDER BY Vendor_Code, Code";
        }
        else {
            $stmt = "SELECT * FROM (
SELECT tbl2.NodeNo,
	tbl2.Code,
	tbl2.Arabic_Name,
	tbl2.BaseUnits,
	tbl2.VendorNo,
	tbl2.Vendor_Code,
	tbl2.Vendor_ArName,
	ProductMast.LeadTime,
	ProductMast.ReOrderLevel1 as 'MinOrder',
	Qty_In-Qty_Out+Qty_In2 as 'Stock'
	FROM (
SELECT NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	SUM(Qty_In) as 'Qty_In',
	SUM(Qty_Out) as 'Qty_Out',
    SUM(Qty_in2) as 'Qty_In2'
	FROM (
SELECT Distinct
	NodeNo,
	Code,
	Arabic_Name,
	BaseUnits,
	VendorNo,
	Vendor_Code,
	Vendor_ArName,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From PInvoice Where (ProductNo = NodeNo) And (DoNotUpdateStock=0)  And PIDate<='".$today." 23:59:25'  And Department = V.Department   Group By ProductNo) as Qty_In ,
	(Select Sum((ActualQty*ConversionQty)+(FreeQty*ConversionQty)) As TotalQty From SInvoice Where  (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17))) and (ProductNo = NodeNo)     And (DoNotUpdateStock=0)  And SIDate<='".$today." 23:59:25' And Department = V.Department    Group By ProductNo) as Qty_Out,
	case when (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today ." 23:59:25'  )  ) is not null then  (select SUM(actualQty)  from sinvoice where ProductNo=V.NodeNo and SInvoiceNo like '250%%' And Department = V.department And Salesman<>17  And Executed=0  And DonotUpdateStock=0  And (SIDate <= '". $today." 23:59:25'  )  ) else 0 end  as Qty_in2
	FROM (Select distinct
	NodeNo ,
	Code ,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName,
	-Sum(TotalCost) as Cost
	from SInvoice ,productmast
	Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And (Sinvoiceno not like '250-%%' or (Sinvoiceno like '250-%%' and ( executed=1 or Salesman=17)))  And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	And (SIDate <= '". $today ." 23:59:25'  )  group By NodeNo , Code , Name , Arabic_Name ,Department,BaseUnits,VendorNo
	union all
	Select distinct
	NodeNo ,
	Code,
	Arabic_Name,
	Department ,
	BaseUnits ,
	VendorNo,
	case when (Select Code From AccMast Where NodeNo = Vendorno) is not null then (Select Code From AccMast Where   NodeNo = Vendorno) else ''    end as Vendor_Code ,
	case when (Select Arabic_Name From AccMast Where NodeNo = Vendorno) is not null then (Select Arabic_Name From AccMast Where NodeNo = Vendorno)    else '' end as Vendor_ArName  ,
	Sum(TotalCost) as Cost   from PInvoice,productmast Where ProductNo = NodeNo And [Group] = 0  And DoNotUpdateStock = 0   And  NodeNo in (SELECT NodeNo FROM ProductMast WHERE Pricelist = 1)
	And  Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	And (PIDate <= '".$today." 23:59:25')
	group By NodeNo , Code ,Arabic_Name ,Department,BaseUnits,VendorNo) V
	group By NodeNo , Code , Arabic_Name ,Department,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName ) as tbl1
	group By NodeNo , Code , Arabic_Name ,BaseUnits,VendorNo  , Vendor_Code, Vendor_ArName) as tbl2 LEFT JOIN ProductMast ON tbl2.NodeNo = ProductMast.NodeNo
	--ORDER BY Vendor_Code
	) as tbl3
	LEFT JOIN
	(SELECT
	ProductNo,
	SUM(Qty) as Qty,
	SUM(ExecutedQty) as ExecutedQty,
	SUM(DeliveredQty) as DeliveredQty,
	SUM(case when Delivered = 'Delivered' THEN (Qty-ExecutedQty) +(ExecutedQty-DeliveredQty) ELSE  (Qty-ExecutedQty) END) as final_qty_a,

	SUM(QtyOrderd) as QtyOrdered,
	SUM(ExecutedQtyOrdered) as ExecutedQtyOrdered,
	SUM(DeliveredQtyOrdered) as DeliveredQtyOrdered,
	SUM((isnull(QtyOrderd,0)-isnull(ExecutedQtyOrdered, 0)) +(isnull(ExecutedQtyOrdered,0)-isnull(DeliveredQtyOrdered, 0))) as final_qty_ordered,
	(SUM(isnull(Qty, 0))-SUM(isnull(ExecutedQty, 0))) + (SUM(isnull(QtyOrderd,0))-SUM(isnull(ExecutedQtyOrdered, 0))) as final_qty
FROM (
Select
	ProductNo,
	Code,
	(Select Name From ProductMast Where NodeNo = ProductNo) as ProductName,
	ProductMast.Description,
	(Select Arabic_Name From ProductMast Where NodeNo = ProductNo) as ProductArName,
	BaseUnits ,
	(Select BaseArabicUnit From Units Where BaseUnit = BaseUnits) as BaseArabicUnits ,
	PODate ,
	Q.Department,
	(Select Name From DeptMast Where NodeNo = Q.Department) as Name,
	(Select Arabic_Name From DeptMast Where NodeNo = Q.Department) as Arabic_Name ,
	POrderNo as VoucherNo,
	Q.VField18 as AltRef ,
	case when Q.Executed = 0 then 'Open' else 'closed' end as [Open],
	case when (Select Top 1 ActualQty From POrder Where RefrenceNo = Q.POrderNo) >= 0 then 'Invoiced' else '' End as Invoiced ,
	case when (Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo /*and ProductNo = Q.ProductNo*/)) >= 0 then 'Delivered' else '' End as Delivered ,
	(Select Name From AccMast Where NodeNo = AccountNo) as Customer,(Select Arabic_Name From AccMast Where NodeNo = AccountNo) as Arabic_Customer,
	Sum(Q.value)  as Amount ,
	sum(field2) as net,

	sum(case when POrderNo like '280-%%' then ActualQty end) as Qty ,
	sum(case when POrderNo like '280-%%' then ExecutedQty end) as ExecutedQty,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '280-%%')), 0)  as DeliveredQty,

	sum(case when POrderNo like '290-%%' then ActualQty end) as QtyOrderd ,
	sum(case when POrderNo like '290-%%' then ExecutedQty end) as ExecutedQtyOrdered,
	ISNULL((Select Top 1 ActualQty From PInvoice Where RefrenceNo = (Select Top 1 POrderNo From POrder Where RefrenceNo = Q.POrderNo and ProductNo = Q.ProductNo and Q.POrderNo like '290-%%')), 0)  as DeliveredQtyOrdered

	From POrder Q,Idetails,ProductMast ,extrafields
	where porderno=extrafields.voucherno
	And sequenceno = extrafields.sno
	and POrderNo = Idetails.VoucherNo
	And ProductNo = NodeNo
	And ProductNo in (SELECT ProductNo FROM ProductMast WHERE Pricelist = 1) And Department in (515,511,510,509,508,507,506,505,504,500,15,17,16,14,13,12,11,10,9,8,7,6,5,4,3,2,1)
	And (PODate >= '01/01/2023' And PODate <= '". $today ." 23:59:25')
	And Executed = 0
	And (PorderNo like '280-%%' or PorderNo like '290-%%')
	--And (Select Name From DeptMast Where NodeNo = Q.Department) Not In (Select DeptName From DeptRights Where UserName ='HQ-BAlrashed')
	Group By Department ,PODate ,POrderNo ,VField18 ,Executed ,AccountNo,ProductNo,Code,BaseUnits ,Description --order by ProductNo ,POrderNo, Q.Department,POdate
) as tbl
GROUP BY ProductNo) as tbl4
ON tbl3.NodeNo = tbl4.ProductNo
ORDER BY Vendor_Code, Code";
        }
        
        $this->results = DB::connection('sqlsrv')->select($stmt);
        $this->show_results = true;

        $this->emit('finished');
    }
}
