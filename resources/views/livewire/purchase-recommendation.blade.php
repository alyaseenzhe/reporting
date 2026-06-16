@section('title')
    12- توصية الشراء
@stop
@section('title-btn')

@stop
<div>
    <div id="filter-container" class="mb-6 mt-6">
        <div style="background-color: #f0f9ff;" class="flex flex-col gap-4 p-6">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-4">عرض الأصناف</label>
                    <div class="flex flex-row">
                        <div class="flex items-center w-full">
                            <input type="radio" name="item_type" value="all_items" checked
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">جميع
                                الأصناف</label>
                        </div>
                        <div class="flex items-center w-full">
                            <input type="radio" name="item_type" value="item_vendor"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">بالمورد</label>
                        </div>
                        <div class="flex items-center w-full">
                            <input type="radio" name="item_type" value="item_code"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">برقم الصنف</label>
                        </div>
                    </div>

                    @error('item_type')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
                <div wire:ignore id="product_code_div" class="w-full hide">
                    <label class="block font-bold mb-2">رقم الصنف
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="product_code"
                           class="form-input w-full @error('product_code') border-red-300 @enderror">
                    @error('product_code')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
                <div wire:ignore id="vendor_type_div" class="w-full hide">
                    <label class="block font-bold mb-2">الموردين
                        <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <select id="vendor_type" name="vendor_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor["CardCode"] }}"
                                        @if($vendor_type == $vendor["CardCode"]) selected @endif>{{ $vendor["CardName"] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vendor_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button id="create-report" style="background-color: #026832;"
                            class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                        <span></span>
                        <span>إنشاء تقرير</span>
                        </span>
                    </button>
                </div>
                <div id="export-div" style="display: none; cursor: pointer" class="mt-8 text-center w-full">
                    <div id="export-to-excel" style="background-color: #680202;"
                         class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                        <span></span>
                        <span>تصدير إلى اكسل</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($show_results)
        @if($sap_results)
            <div class="mb-5 p-2">
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                    <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">
                        <label class="block font-bold mb-5">خيارات</label>
                        <div class="flex flex-row">
                            <div class="flex items-center mb-4 w-full">
                                <input id="all-items" name="item_record" onclick="records('all_item')" type="radio" value="all_item" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">جميع التوصيات</label>
                            </div>
                            <div class="flex items-center mb-4 w-full">
                                <input id="positive_item" name="item_record" onclick="records('positive_item')" type="radio" value="positive_item" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التوصيات الموجبة</label>
                            </div>
                            <div class="flex items-center mb-4 w-full">
                                <input name="item_record" onclick="records('negative_item')" type="radio" value="negative_item" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التوصيات السالبة</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="tbl-container" class="overflow-x-auto overflow-y-auto">
            <table id="tbl" style="border: 2px solid black;" class="table-container w-full border text-center">
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php
                    $vendor_id = "*";
                    ?>
                @forelse($sap_results as $record)
                    <div wire:key="time()">
                        @if($record['CardCode'] != $vendor_id)
                                <?php $vendor_id = $record['CardCode']; ?>
                            <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                <td style="border: 2px solid black;background-color: #dcdcdc"
                                    class="border p-2 whitespace-nowrap col-id-no"
                                    scope="row">{{ $record['CardCode'] }}</td>
                                <td colspan="29" style="border: 2px solid black;background-color: #dcdcdc"
                                    class="border p-2 whitespace-nowrap col-id-no"
                                    scope="row">{{ $record['CardName'] }}</td>
                            </tr>
                        @endif
                            <?php
                            $vendor_id = $record['CardCode'];
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

                            $val_mozanah = intval($record["U_SafetyStock"]) - (intval($record["OnHand"]) + intval($record["OnOrder"])+intval($record["OpenQoutation"]));
                            $val_mostahdef = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" .( $record["OldItemCode"]?: $record["ItemCode"] ). "' and month = '" . $month . "' and year = '" . $year . "'");
                            $val_target = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . ($record["OldItemCode"]?  :$record["ItemCode"]). "' and " . $stmt);

                            $next_val_target = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" .( $record["OldItemCode"]?: $record["ItemCode"] ). "' and ((year ='" . $next_target_date01->format('Y') . "' and month = '" . $next_target_date01->format('n') . "') or (year ='" . $next_target_date02->format('Y') . "' and month = '" . $next_target_date02->format('n') . "') or (year ='" . $next_target_date03->format('Y') . "' and month = '" . $next_target_date03->format('n') . "'))");

                            $faed_maqzon = (intval($record["OnHand"]) + (intval($record["OnOrder"])+intval($record["OpenQoutation"]))) - intval($val_target->target);
                            $recommendation = intval($val_mostahdef->target) + intval(($val_mozanah < 0 ? 0 : $val_mozanah)) - ($faed_maqzon < 0 ? 0 : $faed_maqzon);

                            ?>
                        <tr class="@if($recommendation > 0) positive-record @else negative-record @endif">
                            <th colspan="30" style="border: 2px solid black; background-color: #faebd7"
                                class="col-id-no fixed-header border p-2 whitespace-nowrap">
                                <div class="flex flex-row">
                                    <div class="w-full text-sm text-center">رقم الصنف</div>
                                    <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record["ItemCode"] }} ({{ $record["OldItemCode"] }})</div>
                                    <div class="w-full text-sm text-center">اسم الصنف</div>
                                    <div style="color: #fd0e0e"
                                         class="w-full text-sm text-center">{{ $record["ItemName"] }}</div>
                                    <div class="w-full text-sm text-center">الوحدة</div>
                                    <div style="color: #fd0e0e"
                                         class="w-full text-sm text-center">{{ $record["InvntryUom"] }}</div>
                                    <div class="w-full text-sm text-center">المورد</div>
                                    <div style="color: #fd0e0e"
                                         class="w-full text-sm text-center">{{ $record["CardName"] }}</div>
                                    <div class="w-full text-sm text-center">فترة الطلب</div>
                                    <div style="color: #fd0e0e"
                                         class="w-full text-sm text-center">{{ $record["LeadTime"] }}</div>
                                    <div class="w-full text-sm text-center">فترة التوزيع</div>
                                    <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $dist_days }}</div>
                                </div>
                            </th>
                        </tr>
                        <tr class="@if($recommendation > 0) positive-record @else negative-record @endif">
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">فترة كلية (شهر)</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">المخزون</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">
                                    طلبات الشراء
{{--                                    التسعيرة--}}
                                    {{--                                <br>--}}
                                    {{--                                @if($record["OpenQty"])--}}
                                    {{--                                    <span class="text-xs">({{ $record["DocDueDate"]? $record["DocDueDate"]: "N/A" }}){{intval($record->count_purchase_order) > 1 ? "*" : ""}} </span>--}}
                                    {{--                                    <span class="text-xs">({{ $record["DocDueDate"]? \Carbon\Carbon::parse($record["DocDueDate"])->format('Y-m-d') : "N/A" }}){{intval($record["count_purchase_order"]) > 1 ? "*" : ""}} </span>--}}
                                    {{--                                @endif--}}
                                </div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">
{{--                                    طلبات الشراء--}}
{{--                                    التسعيرة--}}
                                    طلب الشراء
                                    {{--                                <br>--}}
                                    {{--                                @if($record["OpenQty"])--}}
                                    {{--                                    <span class="text-xs">({{ $record["DocDueDate"]? $record["DocDueDate"]: "N/A" }}){{intval($record->count_purchase_order) > 1 ? "*" : ""}} </span>--}}
                                    {{--                                    <span class="text-xs">({{ $record["DocDueDate"]? \Carbon\Carbon::parse($record["DocDueDate"])->format('Y-m-d') : "N/A" }}){{intval($record["count_purchase_order"]) > 1 ? "*" : ""}} </span>--}}
                                    {{--                                @endif--}}
                                </div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">امر الشراء</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">المتاح</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">المتاح الأدنى</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">موازنة المتاح</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">
                                    المستهدف
                                    <br>
                                    <span class="text-xs">({{ \Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30))->format('Y-m') }})</span>
                                </div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">
                                    الإستهلاك
                                    <br>
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

                                </div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">فائض المخزون</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">توصية الشراء</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">
                                    مستهدف
                                    <span class="text-xs">(3 شهور تالية)</span>
                                    <br>
                                    <span class="text-xs">(</span>
                                    <span class="text-xs">{{\Illuminate\Support\Carbon::today()->addMonths(ceil($full_days/30)+1)->firstOfMonth()->format('Y-m')}}</span>
                                    <span class="text-xs"> الى</span>
                                    <span class="text-xs">{{\Illuminate\Support\Carbon::today()->firstOfMonth()->addMonths(ceil($full_days/30)+3)->format('Y-m')}}</span>
                                    <span class="text-xs">)</span>
                                </div>
                            </th>
                        </tr>
                        <tr class="@if($recommendation > 0) positive-record @else negative-record @endif">
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ ceil($full_days/30) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format($record["OnHand"]) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{number_format($record["OpenPurchaseRequest"])}}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format(intval($record["OpenQoutation"])) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format(intval($record["OnOrder"])) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format(intval($record["OnHand"]) + intval($record["OnOrder"])+intval($record["OpenQoutation"])) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format($record["U_SafetyStock"]) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                @php //$val_mozanah = intval($record->MinOrder) - (intval($record->Stock) + intval($record->final_qty)); @endphp
                                <div class="text-sm">{{ number_format($val_mozanah < 0 ? 0 : $val_mozanah) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <?php
                                    //$val_mostahdef = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . $record->Code . "' and month = '" . $month . "' and year = '" . $year . "'");
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '310245' and month = '". $month ."' and year = '". $year."'");
                                    ?>
                                <div class="text-sm">{{ number_format($val_mostahdef->target) }}</div>
                            </th>
                                <?php
                                //$val_target = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '" . $record->Code . "' and " . $stmt);
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '". $record->Code ."' and " . $stmt);
//                                $val = \Illuminate\Support\Facades\DB::selectOne("select SUM(target) as target from product_target_branch_totals where product_id = '310245' and month = '". $month ."' and year = '". $year."'");
                                ?>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format($val_target->target) }} here</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <?php
                                    //$faed_maqzon = (intval($record->Stock) + intval($record->final_qty)) - intval($val->target);
                                    ?>
                                <div class="text-sm">{{ $faed_maqzon < 0 ? 0 : number_format($faed_maqzon)  }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                {{--                            <div class="text-sm">{{ intval($val_mostahdef->target) + intval(($val_mozanah < 0 ? 0 : $val_mozanah)) - ($faed_maqzon < 0 ? 0 : $faed_maqzon)   }}</div>--}}
                                <div class="text-sm">{{ number_format($recommendation) }}</div>
                            </th>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                {{--                            <div class="text-sm">{{ intval($val_mostahdef->target) + intval(($val_mozanah < 0 ? 0 : $val_mozanah)) - ($faed_maqzon < 0 ? 0 : $faed_maqzon)   }}</div>--}}
                                <div class="text-sm">{{ number_format($next_val_target->target) }}</div>
                            </th>
                        </tr>
                    </div>
                @empty
                    <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
                        <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
                        <span class="mt-4">لا يوجد تقرير للعرض</span>
                    </div>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>

        var item_type = $("input[type='radio'][name='item_type']:checked").val();

        $("input[name='item_type']").change(function () {
            item_type = $(this).val();
            $('#product_code').val("");

            if (item_type == "all_items") {
                $('#product_code_div').addClass('hide');
                $('#vendor_type_div').addClass('hide');

            }
            else if(item_type == "item_vendor") {
                $('#product_code_div').addClass('hide');
                $('#vendor_type_div').removeClass('hide');
            }
            else if(item_type == "item_code") {
                $('#product_code_div').removeClass('hide');
                $('#vendor_type_div').addClass('hide');
            }
        });

        $('#create-report').on('click', function () {

            item_type = $("input[type='radio'][name='item_type']:checked").val();
            // alert(item_type);
            var vendor_type = $("#vendor_type").val();
            var product_code = $("#product_code").val();

            $('#positive_item').prop('checked', true);


            $("#create-report").html('<b>الرجاء الإنتظار..</b>');

            Swal.fire({
                title: 'الرجاء الإنتظار',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            });


            Livewire.emit('create-report', item_type, vendor_type, product_code);
        });

        $('#export-to-excel').on('click', function () {

            record_type = $("input[name='item_record']:checked").val();

            Swal.fire({
                title: 'الرجاء الإنتظار',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            });

            Livewire.emit('export-report', record_type);
        });

        Livewire.on('finished', () => {
            swal.close();
            $('#export-div').css('display', 'unset');
            records('positive_item');
        });

        function records(item_record) {
            if(item_record == 'all_item') {
                $(".positive-record").removeClass("hide");
                $(".negative-record").removeClass("hide");
            }
            else if(item_record == 'positive_item') {
                $(".positive-record").removeClass("hide");
                $(".negative-record").addClass("hide");
            }
            else if(item_record == 'negative_item') {
                $(".negative-record").removeClass("hide");
                $(".positive-record").addClass("hide");
            }
        }

    </script>
@stop
@section('css-scripts')
    <style>
        .hide {
            display: none;
        }
    </style>
@stop
