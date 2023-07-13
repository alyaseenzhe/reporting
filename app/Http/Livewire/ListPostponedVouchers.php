<?php

namespace App\Http\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ListPostponedVouchers extends Component
{
    use WithPagination;
    public $area_id;
    public $last_date;
    public $month;
    public $year;

    public $result;
    public $pram_flag = false;

    public function mount($area_id, $month, $year) {

        $areas = [3, 10, 7, 13, 4, 6, 5, 12, 11, 9, 8, 505];

        if (in_array($area_id, $areas) && (intval($month) >= 1  && intval($month) <= 12) && (intval($year) >= 2011)) {

            $this->pram_flag = true;
        }

    }
    public function render()
    {
        if ($this->pram_flag) {
//            $this->fetch_data($request);

            $records = DB::connection('sqlsrv')->table('billwise')
                ->join('accmast', 'billwise.customerno', '=', 'accmast.nodeno')
                ->join('areamast', 'areamast.nodeno', '=', 'billwise.area')
                ->join('WarrentyInfo', 'WarrentyInfo.AccountNo', '=', 'accmast.NodeNo')
                ->whereIn('WarrentyInfo.AccountStatus', ['عملاء نشيطين لدى الفرع'])
                ->where('billwise.type', 'N')
                ->where('accmast.Type', 10)
                ->where('voucherdate', '<=', '04-30-2023 23:59:23')
                ->where('areamast.nodeno', '8')
//                ->where(DB::raw("billwise.total+isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00)"), '>=', 1)
                ->whereRaw("(billwise.total+isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00) >=1 or billwise.total+isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00) <=-1)")
//                ->orWhere(DB::raw("billwise.total+isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00)"), '<=', -1)
                ->select(DB::raw("isnull((select top 1 code from studentmast,sinvoice where nodeno=student and sinvoiceno=voucherno order by student desc),'') as EmpCode"),
                    DB::raw("isnull((select top 1 Arabic_Name from studentmast,sinvoice where nodeno=student and sinvoiceno=voucherno order by student desc),'') as EmpName"),
                    'accmast.code','accmast.Name', 'accmast.Arabic_Name', 'voucherno', 'voucherdate', 'Total',
                    DB::raw("isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00) as paid"),
                    DB::raw("total+isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<='04-30-2023 23:59:23'),0.00) as DueAmount"),
                    DB::raw("DATEDIFF(day, VoucherDate, '04/30/2023 23:59:25') as days"),
                )
//                ->count();
                ->paginate(20);
//            dd($records);
        }

        return view('livewire.list-postponed-vouchers', compact('records'))
            ->layout('layouts.dashboard');
    }

//    public function fetch_data($request) {
//
//        $last_date = date('Y-m-t', strtotime("$this->year-$this->month"));
//
//        $result = DB::connection('sqlsrv')->select("select isnull((select top 1 code from studentmast,sinvoice where nodeno=student and sinvoiceno=voucherno order by student desc),'') as EmpCode,
//isnull((select top 1 Arabic_Name from studentmast,sinvoice where nodeno=student and sinvoiceno=voucherno order by student desc),'') as EmpName
//,accmast.code,accmast.Name,accmast.Arabic_Name,voucherno,voucherdate,Total,isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
// and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time1),0.00) as paid,
//total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
// and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time2),0.00) as DueAmount
//,:end_date_time3 as cutdate, DATEDIFF(day, VoucherDate, :end_date_time7) as days
//from billwise ,accmast,areamast, WarrentyInfo
//where customerno=accmast.nodeno and areamast.nodeno=area
//and WarrentyInfo.AccountNo = accmast.NodeNo
//and WarrentyInfo.AccountStatus in ('عملاء نشيطين لدى الفرع')
//and billwise.[type]='N'
//and (total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time4),0.00) >=1
//or total+
//isnull((select sum(b2.total)from billwise b2 where b2.Refrence=billwise.voucherno
//and b2.CustomerNo=billwise.CustomerNo and b2.VoucherDate<=:end_date_time5),0.00) <=-1)
//and accmast.[Type]=10
//and voucherdate <=:end_date_time6
//and areamast.nodeno=:area1
//order by EmpCode, Code", ['area1' => $this->area_id, 'end_date_time1' => $last_date . " 23:59:23", 'end_date_time2' => $last_date . " 23:59:23", 'end_date_time3' => $last_date . " 23:59:23", 'end_date_time4' => $last_date . " 23:59:23", 'end_date_time5' => $last_date . " 23:59:23", 'end_date_time6' => $last_date . " 23:59:23", 'end_date_time7' => $last_date . " 23:59:23"]);
//
////        dd($result);
//        $result = $this->arrayPaginator($result, $request);
//        dd($result);
//        $this->result = count((array)$result) > 0 ? (array)$result[0] : null;
////        dd($this->result);
//    }
//
//    public function arrayPaginator($array, $request)
//    {
//        $page = Input::get('page', 1);
//        $perPage = 10;
//        $offset = ($page * $perPage) - $perPage;
//
//        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
//            ['path' => $request->url(), 'query' => $request->query()]);
//    }
}
