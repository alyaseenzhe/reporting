<table id="tbl" style="border: 2px solid black;" class="table-container w-full border text-center">
    <tbody class="text-sm divide-y divide-gray-100">
    <tr>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">رقم المورد</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">اسم المورد</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">رقم الصنف</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">اسم الصنف</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">الوحدة</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">فترة الطلب</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">فترة التوزيع</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">فترة كلية (شهر)</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">المخزون</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                طلبات الشراء
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                تاريخ الوصول
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">المتاح</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">المتاح الأدنى</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">موازنة المتاح</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                المستهدف
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                تاريخ المستهدف
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                الإستهلاك
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                تاريخ الإستهلاك
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">فائض المخزون</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">توصية الشراء</div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                مستهدف 3 شهور تالية
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                تاريخ مستهدف 3 شهور تالية
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                توصية إدارة المواد
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                توصية إدارة المبيعات
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                توصية الإدارة الفنية
            </div>
        </th>
        <th style="background-color: #FFC000; border: 2px solid black; z-index: 10" class="border p-2">
            <div class="text-sm">
                تعميد المدير التنفيذي
            </div>
        </th>
    </tr>
    @forelse($results as $record)
            <?php
            $vendor_id = $record['VendorNo'];
            $full_days = intval($record['LeadTime']) + intval($dist_days);
            $no_days = ceil($full_days / 30);
            $target_date = \Carbon\Carbon::today()->firstOfMonth()->addMonths($no_days);

            $next_target_date01 = \Carbon\Carbon::today()->firstOfMonth()->addMonths($no_days+1);
            $next_target_date02 = \Carbon\Carbon::today()->firstOfMonth()->addMonths($no_days+2);
            $next_target_date03 = \Carbon\Carbon::today()->firstOfMonth()->addMonths($no_days+3);

            $year = $target_date->format('Y');
            $month = $target_date->format('n');


            $start_date = \Carbon\Carbon::today()->firstOfMonth()->format('Y-m-d');
            $end_date = \Carbon\Carbon::today()->addMonths($no_days - 1)->endOfMonth()->format('Y-m-d');
            $period = new \Carbon\CarbonPeriod($start_date, '1 month', $end_date);

            $stmt = "";
            $period = $period->toArray();
            foreach ($period as $key => $month_n) {
                if (count($period) > 1) {
                    if ($key === array_key_first($period)) {
                        $stmt .= "((year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "') or ";
                    } elseif ($key === array_key_last($period)) {
                        $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "'))";
                    } else {
                        $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "') or ";
                    }
                } else {
                    $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "')";
                }
            }

            $val_mozanah = intval($record['MinOrder']) - (intval($record['Stock']) + intval($record['final_qty']));
            $val_mostahdef = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . $record['Code'] . "' and month = '" . $month . "' and year = '" . $year . "'");
            $val_target = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . $record['Code'] . "' and " . $stmt);

            $next_val_target = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . $record['Code'] . "' and ((year ='" . $next_target_date01->format('Y') . "' and month = '" . $next_target_date01->format('n') . "') or (year ='" . $next_target_date02->format('Y') . "' and month = '" . $next_target_date02->format('n') . "') or (year ='" . $next_target_date03->format('Y') . "' and month = '" . $next_target_date03->format('n') . "'))");

            $faed_maqzon = (intval($record['Stock']) + intval($record['final_qty'])) - intval($val_target->target);
            $recommendation = intval($val_mostahdef->target) + intval(($val_mozanah < 0 ? 0 : $val_mozanah)) - ($faed_maqzon < 0 ? 0 : $faed_maqzon);
            ?>
        <div wire:key="time()">

            @if($record_type == 'positive_item' && $recommendation > 0)
                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_Code'] }}</td>
                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_ArName'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Code'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Arabic_Name'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['BaseUnits'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['LeadTime'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $dist_days }}</td>
                    <td  class="w-full text-sm text-center">{{ ceil($full_days/30) }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Stock'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['final_qty'] }}</td>
                    <td  class="w-full text-sm text-center">({{ $record['purchase_arrival_date']? $record['purchase_arrival_date']: "N/A" }}){{intval($record['count_purchase_order']) > 1 ? "*" : ""}}</td>
                    <td  class="w-full text-sm text-center">{{ intval($record['Stock']) + intval($record['final_qty']) }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['MinOrder'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_mozanah < 0 ? 0 : $val_mozanah }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_mostahdef->target }}</td>
                    <td  class="w-full text-sm text-center">{{ \Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30))->format('Y-m') }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_target->target }}</td>
                    <td  class="w-full text-sm text-center">
                        @if(ceil($full_days/30) > 1)
                            <span class="text-xs">(</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                            <span class="text-xs"> الى</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)-1)->format('Y-m')}}</span>
                            <span class="text-xs">)</span>
                        @else
                            <span class="text-xs">(</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                            <span class="text-xs">)</span>
                        @endif
                    </td>
                    <td  class="w-full text-sm text-center">{{ $faed_maqzon < 0 ? 0 : $faed_maqzon  }}</td>
                    <td  class="w-full text-sm text-center">{{ $recommendation }}</td>
                    <td  class="w-full text-sm text-center">{{ $next_val_target->target }}</td>
                    <td  class="w-full text-sm text-center">
                        <span class="text-xs">(</span>
                        <span class="text-xs">{{\Illuminate\Support\Carbon::today()->addMonths(ceil($full_days/30)+1)->firstOfMonth()->format('Y-m')}}</span>
                        <span class="text-xs"> الى</span>
                        <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)+3)->format('Y-m')}}</span>
                        <span class="text-xs">)</span>
                    </td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>
                </tr>
            @elseif($record_type == 'negative_item' && $recommendation <= 0)
                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_Code'] }}</td>
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_ArName'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['Code'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['Arabic_Name'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['BaseUnits'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['LeadTime'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $dist_days }}</td>
                        <td  class="w-full text-sm text-center">{{ ceil($full_days/30) }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['Stock'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['final_qty'] }}</td>
                        <td  class="w-full text-sm text-center">({{ $record['purchase_arrival_date']? $record['purchase_arrival_date']: "N/A" }}){{intval($record['count_purchase_order']) > 1 ? "*" : ""}}</td>
                        <td  class="w-full text-sm text-center">{{ intval($record['Stock']) + intval($record['final_qty']) }}</td>
                        <td  class="w-full text-sm text-center">{{ $record['MinOrder'] }}</td>
                        <td  class="w-full text-sm text-center">{{ $val_mozanah < 0 ? 0 : $val_mozanah }}</td>
                        <td  class="w-full text-sm text-center">{{ $val_mostahdef->target }}</td>
                        <td  class="w-full text-sm text-center">{{ \Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30))->format('Y-m') }}</td>
                        <td  class="w-full text-sm text-center">{{ $val_target->target }}</td>
                        <td  class="w-full text-sm text-center">
                            @if(ceil($full_days/30) > 1)
                                <span class="text-xs">(</span>
                                <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                                <span class="text-xs"> الى</span>
                                <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)-1)->format('Y-m')}}</span>
                                <span class="text-xs">)</span>
                            @else
                                <span class="text-xs">(</span>
                                <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                                <span class="text-xs">)</span>
                            @endif
                        </td>
                        <td  class="w-full text-sm text-center">{{ $faed_maqzon < 0 ? 0 : $faed_maqzon  }}</td>
                        <td  class="w-full text-sm text-center">{{ $recommendation }}</td>
                        <td  class="w-full text-sm text-center">{{ $next_val_target->target }}</td>
                        <td  class="w-full text-sm text-center">
                            <span class="text-xs">(</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->addMonths(ceil($full_days/30)+1)->firstOfMonth()->format('Y-m')}}</span>
                            <span class="text-xs"> الى</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)+3)->format('Y-m')}}</span>
                            <span class="text-xs">)</span>
                        </td>
                        <td  class="w-full text-sm text-center"></td>
                        <td  class="w-full text-sm text-center"></td>
                        <td  class="w-full text-sm text-center"></td>
                        <td  class="w-full text-sm text-center"></td>

                    </tr>
            @elseif($record_type == 'all_item')
                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_Code'] }}</td>
                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no">{{ $record['Vendor_ArName'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Code'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Arabic_Name'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['BaseUnits'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['LeadTime'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $dist_days }}</td>
                    <td  class="w-full text-sm text-center">{{ ceil($full_days/30) }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['Stock'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['final_qty'] }}</td>
                    <td  class="w-full text-sm text-center">({{ $record['purchase_arrival_date']? $record['purchase_arrival_date']: "N/A" }}){{intval($record['count_purchase_order']) > 1 ? "*" : ""}}</td>
                    <td  class="w-full text-sm text-center">{{ intval($record['Stock']) + intval($record['final_qty']) }}</td>
                    <td  class="w-full text-sm text-center">{{ $record['MinOrder'] }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_mozanah < 0 ? 0 : $val_mozanah }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_mostahdef->target }}</td>
                    <td  class="w-full text-sm text-center">{{ \Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30))->format('Y-m') }}</td>
                    <td  class="w-full text-sm text-center">{{ $val_target->target }}</td>
                    <td  class="w-full text-sm text-center">
                        @if(ceil($full_days/30) > 1)
                            <span class="text-xs">(</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                            <span class="text-xs"> الى</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)-1)->format('Y-m')}}</span>
                            <span class="text-xs">)</span>
                        @else
                            <span class="text-xs">(</span>
                            <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->format('Y-m')}}</span>
                            <span class="text-xs">)</span>
                        @endif
                    </td>
                    <td  class="w-full text-sm text-center">{{ $faed_maqzon < 0 ? 0 : $faed_maqzon  }}</td>
                    <td  class="w-full text-sm text-center">{{ $recommendation }}</td>
                    <td  class="w-full text-sm text-center">{{ $next_val_target->target }}</td>
                    <td  class="w-full text-sm text-center">
                        <span class="text-xs">(</span>
                        <span class="text-xs">{{\Illuminate\Support\Carbon::today()->addMonths(ceil($full_days/30)+1)->firstOfMonth()->format('Y-m')}}</span>
                        <span class="text-xs"> الى</span>
                        <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)+3)->format('Y-m')}}</span>
                        <span class="text-xs">)</span>
                    </td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>
                    <td  class="w-full text-sm text-center"></td>

                </tr>
            @endif

        </div>
    @empty
        <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
            <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
            <span class="mt-4">لا يوجد تقرير للعرض</span>
        </div>
    @endforelse
    </tbody>
</table>
