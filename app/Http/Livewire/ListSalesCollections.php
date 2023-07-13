<?php

namespace App\Http\Livewire;

use App\Models\FAExtra;
use Livewire\Component;

class ListSalesCollections extends Component
{
    public function render()
    {

        dd($this->collected('10252', '2023-06-01 00:00:00', '2023-06-07 23:59:59'));
//        $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
//            ->where('StudentMast.Code', '10041')
//            ->where(function ($query) {
//                $query->orWhere('VoucherNo', 'like',  '030%')
//                    ->orWhere('VoucherNo', 'like',  '040%')
//                    ->orWhere('VoucherNo', 'like',  '050%');
//            })
//            ->where('VoucherDate', '>=', '2023-06-01 00:00:00')
//            ->where('VoucherDate', '<=', '2023-06-07 00:00:00')
//            ->whereNotIN('VoucherNo', function ($query) {
//                $query->select('VField9')
//                    ->from('FAExtra as b')
//                ->whereRaw('FAExtra.VoucherNo = b.VField9');
//            })
//            ->sum('Value');
//            ->get();

//        $collected_vouchers = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
//            ->where('StudentMast.Code', '10041')
//            ->where(function ($query) {
//                $query->orWhere('FAExtra.VoucherNo', 'like',  '030%')
//                    ->orWhere('FAExtra.VoucherNo', 'like',  '040%')
//                    ->orWhere('FAExtra.VoucherNo', 'like',  '050%');
////                    ->orWhere('FAExtra.VoucherNo', 'like',  '031%');
////                    ->orWhere('b.VoucherNo', 'like',  '041%')
////                    ->orWhere('b.VoucherNo', 'like',  '051%');
//            })
//            ->where('FAExtra.VoucherDate', '>=', '2023-06-01 00:00:00')
//            ->where('FAExtra.VoucherDate', '<=', '2023-06-07 23:59:59')
//            //->whereNotNull('b.VoucherNo')
////            ->whereNotIN('VoucherNo', function ($query) {
////                $query->select('VField9')
////                    ->from('FAExtra as b')
////                    ->whereRaw('FAExtra.VoucherNo = b.VField9');
////            })
////            ->sum('Value');
//        ->get('VoucherNo')->toArray();
////        dd($collected);
//
////        $collected_vouchers = $collected->get('VoucherNo');
////        dd($collected_vouchers);
//
//        $collectedReverse = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
//            ->where('StudentMast.Code', '10041')
//            ->where(function ($query) {
//                $query->orWhere('VoucherNo', 'like',  '031%')
//                    ->orWhere('VoucherNo', 'like',  '041%')
//                    ->orWhere('VoucherNo', 'like',  '051%');
//            })
//            ->whereIn('VField9', [$collected_vouchers])
//            //->where('VoucherDate', '>=', '2023-06-01 00:00:00')
//            //->where('VoucherDate', '<=', '2023-06-07 00:00:00')
//            ->get('VField9')->toArray();
////        dd($collectedReverse);
////        $x = $collected->diff($collectedReverse);
////        dd($x);
//
//        $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
//            ->where('StudentMast.Code', '10041')
////            ->whereIn('VoucherNo', [$collected_vouchers])
////            ->whereNotIn('VoucherNo', [$collectedReverse])
//            ->where(function ($query) use ($collectedReverse){
//                $query->whereNotIn('VoucherNo', [$collectedReverse])
//                    ->whereNotIn('VField9', [$collectedReverse]);
////                    ->orWhere('FAExtra.VoucherNo', 'like',  '031%');
////                    ->orWhere('b.VoucherNo', 'like',  '041%')
////                    ->orWhere('b.VoucherNo', 'like',  '051%');
//            })
//            ->where('VoucherDate', '>=', '2023-06-01 00:00:00')
//            ->where('VoucherDate', '<=', '2023-06-07 23:59:59')
//            //->whereNotNull('b.VoucherNo')
////            ->whereNotIN('VoucherNo', function ($query) {
////                $query->select('VField9')
////                    ->from('FAExtra as b')
////                    ->whereRaw('FAExtra.VoucherNo = b.VField9');
////            })
////            ->sum('Value');
//            ->get();
//        dd($collected);
//



        return view('livewire.list-sales-collections');
    }

    public function collected($emp_code, $start_date, $end_date) {

        $collected_vouchers = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
            ->where('StudentMast.Code', $emp_code)
            ->where(function ($query) {
                $query->orWhere('FAExtra.VoucherNo', 'like',  '030%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '040%')
                    ->orWhere('FAExtra.VoucherNo', 'like',  '050%');
            })
            ->where('FAExtra.VoucherDate', '>=', $start_date)
            ->where('FAExtra.VoucherDate', '<=', $end_date)

            ->get('VoucherNo')->toArray();
//        dd($collected_vouchers);

        $collectedReverse = [];
        if (count($collected_vouchers) > 0) {
            $collectedReverse = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->where('StudentMast.Code', $emp_code)
                ->where(function ($query) {
                    $query->orWhere('VoucherNo', 'like', '031%')
                        ->orWhere('VoucherNo', 'like', '041%')
                        ->orWhere('VoucherNo', 'like', '051%');
                })
                ->whereIn('VField9', [$collected_vouchers])
                ->get('VField9')->toArray();
//        dd($collectedReverse);
        }


        $collected = [];
        // if there is a reverse
        if (count($collectedReverse) > 0) {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->where('StudentMast.Code', $emp_code)
                ->whereNotIn('VoucherNo', [$collectedReverse])
                ->whereNotIn('VField9', [$collectedReverse])
//                ->where(function ($query) use ($collectedReverse){
//                    $query->whereNotIn('VoucherNo', [$collectedReverse])
//                        ->whereNotIn('VField9', [$collectedReverse]);
//                })
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
                ->sum('Value');
//            ->get();
            dd($collected);
        }
        else {
            $collected = FAExtra::join('StudentMast', 'FAExtra.Student', 'StudentMast.NodeNo')
                ->where('StudentMast.Code', $emp_code)
//                ->where(function ($query) use ($collectedReverse){
//                    $query->whereNotIn('VoucherNo', [$collectedReverse])
//                        ->whereNotIn('VField9', [$collectedReverse]);
//                })
                ->where('VoucherDate', '>=', $start_date)
                ->where('VoucherDate', '<=', $end_date)
                ->sum('Value');
//            ->get();
        }

        return $collected;


    }


}
