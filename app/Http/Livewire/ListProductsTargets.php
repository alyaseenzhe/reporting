<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListProductsTargets extends Component
{
    public $dept_id = -1;
    public $selected_month;
    public $results = [];
    public $list = [];

    protected $rules = [
        'dept_id' => 'required|not_in:-1',
        'selected_month' => 'required',
    ];

    protected $messages = [
        'dept_id.required' => "مطلوب",
        'dept_id.not_in' => "مطلوب",
        'selected_month.required' => "مطلوب",
    ];

    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.products-targets', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.list-products-targets')
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        $this->validate();
        $this->emit('show-container');

        $month_stmt = '';
        $this->list = [];
        $this->results = [];


        $years = [];

        $selected_year1 = Carbon::parse($this->selected_month);
        $selected_year2 = Carbon::parse($this->selected_month)->addMonth(11);
        $year1 = $selected_year1->format('Y');
        $year2 = $selected_year2->format('Y');

        $this->list = [];
        for ($i = 0; $i < 12; $i++) {
//            $year = $selected_year1->format('Y');
            $month = Carbon::parse($this->selected_month)->addMonth($i)->format('n');
            $year = Carbon::parse($this->selected_month)->addMonth($i)->format('Y');
//            dd(Carbon::parse($this->selected_month)->addMonth($i)->format('m'));
//            $list[$selected_year1->format('Y')] =  [Carbon::parse($this->selected_month)->addMonth($i)->format('m')];
//            array_push($list[$year], $month);
            $this->list[$year][] = $month;
        }

//        dd($list);

        $month_counter = 1;

//        foreach ($this->list as $year_key => $year) {
//            $month_stmt = "SELECT Code,
//		Arabic_Name,
//		BaseUnits
//      ,[Department]
//	  , (CASE WHEN SUM(Revision) > 0 THEN SUM(Revision) ELSE SUM(Taget) END) as 'Actual_Target'
//	  , (CASE WHEN SUM(Revision) > 0 THEN SUM(Revision)*MAX(WholeSale) ELSE SUM(Taget)*MAX(WholeSale) END) as 'Value'";
//
//            foreach ($year as $month) {
//                $month_stmt .= ", MAX((CASE WHEN [month] = ". $month ." THEN Taget END)) as tmonth".$month_counter;
//                $month_stmt .= ", MAX((CASE WHEN [month] = ". $month ." THEN Revision END)) as rmonth".$month_counter;
//                $month_counter++;
//            }
//
//            $month_stmt .= ", SUM(Taget) as 'total_target'
//	  , SUM(Revision) as 'total_revision'
//	  FROM (
//SELECT *
//FROM (
// SELECT ProductMast.Code,
//		ProductMast.Arabic_Name,
//		ProductMast.BaseUnits,
//		[VoucherNo]
//      ,[Department]
//      ,[month]
//      ,[Year]
//      ,[ProductsTarget].PriceList
//      ,[ProductNo]
//      ,[Taget]
//      ,[Revision]
//	  , WholeSale
//	  ,ProductsTarget.Date, ROW_NUMBER() OVER (PARTITION BY
// Arabic_Name,BaseUnits,[Department], month, year, ProductNo ORDER BY ProductsTarget.Date) AS row_number
// FROM [AccountsC5].[dbo].[ProductsTarget], ProductMast
// where  ProductsTarget.ProductNo = ProductMast.NodeNo
// and Year = ". $year_key ."
// and Department = ".$this->dept_id."
//) AS t
//WHERE t.row_number = 1
//  ) as tbl
//  group by Code,
//		Arabic_Name,
//		BaseUnits
//      ,[Department]
//	  Order by Department, Code";
//
//
////            dd($month_stmt);
//
//            $query = DB::connection('sqlsrv')->select($month_stmt);
//            $fetch_query = json_decode(json_encode($query), true);
//
//            array_push($this->results, $fetch_query);
//
//        }

        $month_stmt = "SELECT Code,
		Arabic_Name,
		BaseUnits
      ,[Department]
      ,WholeSale
      , VendorNo
	  , (ISNULL((select Code from accmast where NodeNo = tbl.VendorNo) , '-')) as 'vendor_code'
	  , (ISNULL((select Arabic_Name from accmast where NodeNo = tbl.VendorNo) , '-')) as 'vendor_name'
	  , (CASE WHEN SUM(Revision) > 0 THEN SUM(Revision) ELSE SUM(Taget) END) as 'Actual_Target'
	  , (CASE WHEN SUM(Revision) > 0 THEN SUM(Revision)*MAX(WholeSale) ELSE SUM(Taget)*MAX(WholeSale) END) as 'Value'";

        foreach ($this->list as $year_key => $year) {

            foreach ($year as $month) {
                $month_stmt .= ", MAX((CASE WHEN [month] = ". $month ." and Year = ".$year_key." THEN Taget END)) as tmonth".$month_counter;
                $month_stmt .= ", MAX((CASE WHEN [month] = ". $month ." and Year = ".$year_key." THEN Revision END)) as rmonth".$month_counter;
                $month_counter++;
            }
        }

        $month_stmt .= ", SUM(Taget) as 'total_target'
	  , SUM(Revision) as 'total_revision'
	  FROM (
SELECT *
FROM (
 SELECT ProductMast.Code,
		ProductMast.Arabic_Name,
		ProductMast.BaseUnits,
		[VoucherNo]
      ,[Department]
      ,[month]
      ,[Year]
      ,[ProductsTarget].PriceList
      ,[ProductNo]
      ,[Taget]
      ,[Revision]
	  , WholeSale
	  ,ProductsTarget.Date
	  , VendorNo
	  , ROW_NUMBER() OVER (PARTITION BY
 Arabic_Name,BaseUnits,[Department], month, year, ProductNo ORDER BY ProductsTarget.Date) AS row_number
 FROM [AccountsC5].[dbo].[ProductsTarget], ProductMast
 where  ProductsTarget.ProductNo = ProductMast.NodeNo
 and Department = ".$this->dept_id."
 and ProductMast.Pricelist = 1
) AS t
WHERE t.row_number = 1
  ) as tbl
  group by Code,
		Arabic_Name,
		BaseUnits
      ,[Department]
      ,WholeSale
	  , VendorNo
	  Order by VendorNo, Code";


        //            dd($month_stmt);

        $query = DB::connection('sqlsrv')->select($month_stmt);
        $fetch_query = json_decode(json_encode($query), true);

        array_push($this->results, $fetch_query);

//        dd($this->results);

//        dd($month_stmt);

//        var_dump($this->results);

//        array_push($years, $year1);
//        array_push($years, $year2);
//
//        $years = array_unique($years);
//
//        dd($years);
//
//        dd(Carbon::parse($this->selected_month)->addMonth(0)->format('Y-m-d'));

//        $this->first_date = date('Y-m-01', strtotime($this->selected_date));
//        $this->last_date = date('Y-m-t', strtotime($this->selected_date));


    }
}
