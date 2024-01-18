@section('title')
    تقرير توصية الشراء
@stop
@section('title-btn')

@stop
<div>
    {{-- Stop trying to control. --}}
    <button wire:click.prevent="createReport" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
        <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
            <path
                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
        </svg>
        <span class="hidden xs:block mr-2">Generate</span>
    </button>


{{--    <div id="tbl-container" class="overflow-x-auto overflow-y-auto" style="height: 700px">--}}
        <table id="tbl" style="border: 2px solid black;" class="table-container w-full border text-center">
            <tbody class="text-sm divide-y divide-gray-100">
            <?php
            $vendor_id = "*";
            ?>
            @foreach($results as $record)
                <div>
                    @if($record->VendorNo != $vendor_id)
                            <?php $vendor_id = $record->VendorNo; ?>
                        <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->Vendor_Code }}</td>
                            <td colspan="29" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->Vendor_ArName }}</td>
                        </tr>
                    @endif
                        <?php
                            $vendor_id = $record->VendorNo;
                            $full_days = intval($record->LeadTime) + intval($dist_days);
                            $no_days = ceil($full_days/30);
                            $target_date = \Carbon\Carbon::today()->firstOfMonth()->addMonths($no_days);
                            $year = $target_date->format('Y');
                            $month = $target_date->format('n');


                            $start_date = \Carbon\Carbon::today()->firstOfMonth()->format('Y-m-d');
                            $end_date = \Carbon\Carbon::today()->addMonths($no_days-1)->endOfMonth()->format('Y-m-d');
                            $period = new \Carbon\CarbonPeriod($start_date, '1 month', $end_date);
                            $stmt = "";
                            $period = $period->toArray();
                            foreach($period as $key => $month_n) {
                                if (count($period) > 1) {
                                    if ($key === array_key_first($period)) {
                                        $stmt .= "((year ='".$month_n->format('Y')."' and month = '".$month_n->format('n')."') or ";
                                    }
                                    elseif ($key === array_key_last($period)) {
                                        $stmt .= "(year ='".$month_n->format('Y')."' and month = '".$month_n->format('n')."'))";
                                    }
                                    else {
                                        $stmt .= "(year ='".$month_n->format('Y')."' and month = '".$month_n->format('n')."') or ";
                                    }
                                }
                                else {
                                    $stmt .= "(year ='".$month_n->format('Y')."' and month = '".$month_n->format('n')."')";
                                }
                            }
                        ?>
                    <tr>
                        <th colspan="30" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                            <div class="flex flex-row">
                                <div class="w-full text-sm text-center">رقم الصنف</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->Code }}</div>
                                <div class="w-full text-sm text-center">اسم الصنف</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->Arabic_Name }}</div>
                                <div class="w-full text-sm text-center">الوحدة</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->BaseUnits }}</div>
                                <div class="w-full text-sm text-center">المورد</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->Vendor_ArName }}</div>
                                <div class="w-full text-sm text-center">فترة الطلب</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->LeadTime }}</div>
                                <div class="w-full text-sm text-center">فترة التوزيع</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $dist_days }}</div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الفترة الكلية</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">المخزون الأدنى</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">المخزون</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">موازنة المخزون</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">طلبات الشراء</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">المتاح</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">المستهدف</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الإستهلاك</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">فائض المخزون</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">توصية الشراء</div>
                        </th>
                </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $full_days }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record->MinOrder }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record->Stock }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            @php $val_mozanah = intval($record->MinOrder) - intval($record->Stock); @endphp
                            <div class="text-sm">{{ $val_mozanah < 0 ? 0 : $val_mozanah }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record->final_qty }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ intval($record->Stock) + intval($record->final_qty) }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <?php
                                $val_mostahdef = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '". $record->Code ."' and month = '". $month ."' and year = '". $year."'");
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '310245' and month = '". $month ."' and year = '". $year."'");
                            ?>
                            <div class="text-sm">{{ $val_mostahdef->target }}</div>
                        </th>
                            <?php
                            $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '". $record->Code ."' and ".$stmt);
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '". $record->Code ."' and " . $stmt);
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '310245' and month = '". $month ."' and year = '". $year."'");
                            ?>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $val->target }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <?php
                                $faed_maqzon = (intval($record->Stock) + intval($record->final_qty)) -  intval($val->target);
                            ?>
                            <div class="text-sm">{{ $faed_maqzon < 0 ? 0 : $faed_maqzon  }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ intval($val_mostahdef->target) + intval(($val_mozanah < 0 ? 0 : $val_mozanah)) - ($faed_maqzon < 0 ? 0 : $faed_maqzon)   }}</div>
                        </th>
                </tr>
{{--                    <tr>--}}
{{--                        <th rowspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">--}}
{{--                            <div class="text-sm">الشهر</div>--}}
{{--                        </th>--}}
{{--                            <?php $loop_counter = 0; ?>--}}
{{--                        @foreach ($list as $year_key => $year)--}}
{{--                            @foreach ($year as $month)--}}
{{--                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($loop_counter%2 == 0) background-color: #d2dafa; @else background-color: #f8d2fa; @endif" class="border p-2">--}}
{{--                                    <div class="text-sm">{{ $year_key."-".$month }}</div>--}}
{{--                                </th>--}}
{{--                                    <?php $loop_counter++; ?>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                        <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #d2dafa;" class="border p-2">--}}
{{--                            <div class="text-sm">مجموع كمية</div>--}}
{{--                        </th>--}}
{{--                        <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">--}}
{{--                            <div class="text-sm">مجموع قيمة</div>--}}
{{--                        </th>--}}
{{--                        <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">--}}
{{--                            <div class="text-sm">الفرق</div>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}

{{--                        @foreach ($list as $year_key => $year)--}}
{{--                            @foreach ($year as $month)--}}
{{--                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">--}}
{{--                                    <div class="text-sm">SC</div>--}}
{{--                                </th>--}}
{{--                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">--}}
{{--                                    <div class="text-sm">T</div>--}}
{{--                                </th>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">--}}
{{--                            <div class="text-sm">SC</div>--}}
{{--                        </th>--}}
{{--                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">--}}
{{--                            <div class="text-sm">T</div>--}}
{{--                        </th>--}}
{{--                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">--}}
{{--                            <div class="text-sm">SC</div>--}}
{{--                        </th>--}}
{{--                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">--}}
{{--                            <div class="text-sm">T</div>--}}
{{--                        </th>--}}
{{--                        <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">--}}
{{--                            <div class="text-sm">%</div>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
                </div>
            @endforeach
            </tbody>
        </table>
{{--    </div>--}}
</div>
