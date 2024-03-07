@section('title')
    حاسبة التوزيع
@stop
@section('title-btn')

@stop
<div>
    <div id="filter-container" class="mb-6 mt-6">
        <div style="background-color: #f0f9ff;" class="flex flex-col gap-4 p-6">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div id="product_code_div" class="w-full">
                    <label class="block font-bold mb-2">رقم الصنف
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="product_code"
                           class="form-input w-full rounded-full shadow-sm border-gray-300 bold text-center @error('product_code') border-red-300 @enderror">
                    @error('product_code')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="month" name="start_month"
                           class="text-center text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-full"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="month" name="end_month"
                           class="text-center text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-full"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button id="create-report" style="background-color: #026832;"
                            class="w-full btn hover:bg-indigo-600 text-white rounded-full">
                        <span class="mr-2 font-bold">
                        <span></span>
                        <span>إنشاء تقرير</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if($results)

        @if(count($results) > 0)
            <div id="capacity-container" class="mb-6 mt-6">
                <div style="background-color: #fff8f0; border: 1px solid #c47317;" class="flex flex-col gap-4 p-6">
                    <div class="w-full flex flex-col sm:flex-row gap-4">
                        <div wire:key="size-{{time()}}" class="w-full">
                            <label class="block font-bold mb-2">الكمية
                                <span class="text-red-500">*</span>
                            </label>
                            <input id="size" type="number" class="form-input w-full rounded-full text-center">
                        </div>
                        <div wire:key="capacity-{{time()}}" class="w-full">
                            <label class="block font-bold mb-2">سعة الحاوية
                                <span class="text-red-500">*</span>
                            </label>
                            {{--                        <input id="capacity_size" type="number" value="{{ $this->results[0]->container_size }}" class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md" style="@error('item_id') border: solid 1px #fda4af; @enderror">--}}
                            <input id="capacity_size" type="number" value="{{ $container_size }}" class="text-center text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-full" style="@error('item_id') border: solid 1px #fda4af; @enderror">
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
                @forelse($results as $record)
                    <div wire:key="time()">
                        @if($record->vendor_code != $vendor_id)
                                <?php $vendor_id = $record->vendor_code; ?>
                            <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                <td style="border: 2px solid black;background-color: #dcdcdc"
                                    class="border p-2 whitespace-nowrap col-id-no"
                                    scope="row">{{ $record->vendor_code }}</td>
                                <td colspan="29" style="border: 2px solid black;background-color: #dcdcdc"
                                    class="border p-2 whitespace-nowrap col-id-no"
                                    scope="row">{{ $record->vendor_name }}</td>
                            </tr>
                        @endif
                            <?php
                            $vendor_id = $record->vendor_code;
                            ?>
                        <tr>
                            <th colspan="30" style="border: 2px solid black; background-color: #faebd7"
                                class="col-id-no fixed-header border p-2 whitespace-nowrap">
                                <div class="flex flex-row">
                                    <div class="w-full text-sm text-center">رقم الصنف</div>
                                    <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->product_code }}</div>
                                    <div class="w-full text-sm text-center">اسم الصنف</div>
                                    <div style="color: #fd0e0e"
                                         class="w-full text-sm text-center">{{ $record->product_name }}</div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">تاريخ</div>
                            </th>
                            <th style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">النسبة</div>
                            </th>
                            <th id="branch_3" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">الاحساء</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_3" style="box-shadow: none" name="branch" type="checkbox" value="branch_3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_10" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">جدة</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_10" style="box-shadow: none" name="branch" type="checkbox" value="branch_10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_7" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">الرياض</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_7" style="box-shadow: none" name="branch" type="checkbox" value="branch_7" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_13" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">وادي الدواسر</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_13" style="box-shadow: none" name="branch" type="checkbox" value="branch_13" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_4" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">الجوف</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_4" style="box-shadow: none" name="branch" type="checkbox" value="branch_4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_6" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">الدمام</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_6" style="box-shadow: none" name="branch" type="checkbox" value="branch_6" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_5" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">الخرج</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_5" style="box-shadow: none" name="branch" type="checkbox" value="branch_5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_12" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">نجران</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_12" style="box-shadow: none" name="branch" type="checkbox" value="branch_12" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_11" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">حائل</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_11" style="box-shadow: none" name="branch" type="checkbox" value="branch_11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_9" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">تبوك</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_9" style="box-shadow: none" name="branch" type="checkbox" value="branch_9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_8" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">القصيم</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_8" style="box-shadow: none" name="branch" type="checkbox" value="branch_8" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th id="branch_505" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">ساجر</div>
                                <div style="margin: auto; justify-content: center; margin-top: 5px;" class="flex items-center mb-4 w-full">
                                    <input id="branch_505" style="box-shadow: none" name="branch" type="checkbox" value="branch_505" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-0">
                                </div>
                            </th>
                            <th style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">مجموع المستهدف</div>
                            </th>
                            <th style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">المحسوب</div>
                            </th>
                        </tr>
                            @php
                                $ahsa_total = 0;
                                $jeddah_total = 0;
                                $riyadh_total = 0;
                                $wadi_total = 0;
                                $jouf_total = 0;
                                $dammam_total = 0;
                                $kharaj_total = 0;
                                $najran_total = 0;
                                $hail_total = 0;
                                $tabouk_total = 0;
                                $qaseem_total = 0;
                                $sajer_total = 0;
                                $target_grandtotal = 0;
                            @endphp

                            @foreach($period as $month_title)
                                @php
                                    $month_label = $month_title->format('n') . "-" . $month_title->format('Y');
                                    $ahsa_txt = "target_".$month_label."_3";
                                    $jeddah_txt = "target_".$month_label."_10";
                                    $riyadh_txt = "target_".$month_label."_7";
                                    $wadi_txt = "target_".$month_label."_13";
                                    $jouf_txt = "target_".$month_label."_4";
                                    $dammam_txt = "target_".$month_label."_6";
                                    $kharaj_txt = "target_".$month_label."_5";
                                    $najran_txt = "target_".$month_label."_12";
                                    $hail_txt = "target_".$month_label."_11";
                                    $tabouk_txt = "target_".$month_label."_9";
                                    $qaseem_txt = "target_".$month_label."_8";
                                    $sajer_txt = "target_".$month_label."_505";
                                    $month_total = 0;

                                    $ahsa_txt_id = "target_3_".$month_label;
                                    $jeddah_txt_id = "target_10_".$month_label;
                                    $riyadh_txt_id = "target_7_".$month_label;
                                    $wadi_txt_id = "target_13_".$month_label;
                                    $jouf_txt_id = "target_4_".$month_label;
                                    $dammam_txt_id = "target_6_".$month_label;
                                    $kharaj_txt_id = "target_5_".$month_label;
                                    $najran_txt_id = "target_12_".$month_label;
                                    $hail_txt_id = "target_11_".$month_label;
                                    $tabouk_txt_id = "target_9_".$month_label;
                                    $qaseem_txt_id = "target_8_".$month_label;
                                    $sajer_txt_id = "target_505_".$month_label;
                                @endphp
                                <tr>
                                    <th style="background-color: #8fbc8f; border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ $month_label }}</div>
                                    </th>
                                    <th style="background-color: #89c4898f; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="month_percent_{{$month_label}}" contenteditable="true" class="text-sm">100</div>
                                    </th>
                                    <th id="date_div_3_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $ahsa_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$ahsa_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$ahsa_txt); @endphp
                                        @php $ahsa_total = $ahsa_total + intval($record->$ahsa_txt); @endphp
                                    </th>
                                    <th id="date_div_10_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $jeddah_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$jeddah_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$jeddah_txt); @endphp
                                        @php $jeddah_total = $jeddah_total + intval($record->$jeddah_txt); @endphp
                                    </th>
                                    <th id="date_div_7_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $riyadh_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$riyadh_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$riyadh_txt); @endphp
                                        @php $riyadh_total = $riyadh_total + intval($record->$riyadh_txt); @endphp
                                    </th>
                                    <th id="date_div_13_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $wadi_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$wadi_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$wadi_txt); @endphp
                                        @php $wadi_total = $wadi_total + intval($record->$wadi_txt); @endphp
                                    </th>
                                    <th id="date_div_4_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $jouf_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$jouf_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$jouf_txt); @endphp
                                        @php $jouf_total = $jouf_total + intval($record->$jouf_txt); @endphp
                                    </th>
                                    <th id="date_div_6_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $dammam_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$dammam_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$dammam_txt); @endphp
                                        @php $dammam_total = $dammam_total + intval($record->$dammam_txt); @endphp
                                    </th>
                                    <th id="date_div_5_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $kharaj_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$kharaj_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$kharaj_txt); @endphp
                                        @php $kharaj_total = $kharaj_total + intval($record->$kharaj_txt); @endphp
                                    </th>
                                    <th id="date_div_12_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $najran_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$najran_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$najran_txt); @endphp
                                        @php $najran_total = $najran_total + intval($record->$najran_txt); @endphp
                                    </th>
                                    <th id="date_div_11_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $hail_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$hail_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$hail_txt); @endphp
                                        @php $hail_total = $hail_total + intval($record->$hail_txt); @endphp
                                    </th>
                                    <th id="date_div_9_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $tabouk_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$tabouk_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$tabouk_txt); @endphp
                                        @php $tabouk_total = $tabouk_total + intval($record->$tabouk_txt); @endphp
                                    </th>
                                    <th id="date_div_8_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $qaseem_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$qaseem_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$qaseem_txt); @endphp
                                        @php $qaseem_total = $qaseem_total + intval($record->$qaseem_txt); @endphp
                                    </th>
                                    <th id="date_div_505_{{$month_label}}" style="background-color: #63b96338; border: 1px solid black; z-index: 10" class="border p-2">
                                        <div id="{{ $sajer_txt_id }}" class="text-sm" contenteditable="true">{{ $record->$sajer_txt }}</div>
                                        @php $month_total = $month_total + intval($record->$sajer_txt); @endphp
                                        @php $sajer_total = $sajer_total + intval($record->$sajer_txt); @endphp
                                    </th>
                                    <th style="background-color: #8fbcbc7d; border: 2px solid black; z-index: 10" class="border p-2">
                                        <div id="total_date_{{ $month_label }}" class="text-sm">{{ $month_total }}</div>
                                    </th>
                                    <th style="background-color: #8fbcbc45; border: 2px solid black; z-index: 10" class="border p-2">
                                        <div id="counted_total_date_{{ $month_label }}" class="text-sm">{{ $month_total }}</div>
                                    </th>
                                </tr>
                                @php $target_grandtotal += intval($month_total); @endphp
                            @endforeach
                        <tr>
                            <th colspan="2" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">مجموع المستهدف</div>
                            </th>
                            <th id="grand_total_div_3" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-3" class="text-sm">{{ $ahsa_total }}</div>
                            </th>
                            <th id="grand_total_div_10" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-10" class="text-sm">{{ $jeddah_total }}</div>
                            </th>
                            <th id="grand_total_div_7" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-7" class="text-sm">{{ $riyadh_total }}</div>
                            </th>
                            <th id="grand_total_div_13" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-13" class="text-sm">{{ $wadi_total }}</div>
                            </th>
                            <th id="grand_total_div_4" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-4" class="text-sm">{{ $jouf_total }}</div>
                            </th>
                            <th id="grand_total_div_6" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-6" class="text-sm">{{ $dammam_total }}</div>
                            </th>
                            <th id="grand_total_div_5" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-5" class="text-sm">{{ $kharaj_total }}</div>
                            </th>
                            <th id="grand_total_div_12" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-12" class="text-sm">{{ $najran_total }}</div>
                            </th>
                            <th id="grand_total_div_11" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-11" class="text-sm">{{ $hail_total }}</div>
                            </th>
                            <th id="grand_total_div_9" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-9" class="text-sm">{{ $tabouk_total }}</div>
                            </th>
                            <th id="grand_total_div_8" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-8" class="text-sm">{{ $qaseem_total }}</div>
                            </th>
                            <th id="grand_total_div_505" style="background-color: #E7F2F8; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grand-total-505" class="text-sm">{{ $sajer_total }}</div>
                            </th>
                            <th style="background-color: #b8cede; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="grandtotal" class="text-sm">{{ $target_grandtotal }}</div>
                            </th>
                            <th style="background-color: #D9E4EC; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="counted-grandtotal" class="text-sm">{{ $target_grandtotal }}</div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">التوزيع</div>
                            </th>
                            <th id="dist_div_3" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_3" class="text-sm"></div>
                            </th>
                            <th id="dist_div_10" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_10" class="text-sm"></div>
                            </th>
                            <th id="dist_div_7" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_7" class="text-sm"></div>
                            </th>
                            <th id="dist_div_13" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_13" class="text-sm"></div>
                            </th>
                            <th id="dist_div_4" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_4" class="text-sm"></div>
                            </th>
                            <th id="dist_div_6" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_6" class="text-sm"></div>
                            </th>
                            <th id="dist_div_5" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_5" class="text-sm"></div>
                            </th>
                            <th id="dist_div_12" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_12" class="text-sm"></div>
                            </th>
                            <th id="dist_div_11" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_11" class="text-sm"></div>
                            </th>
                            <th id="dist_div_9" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_9" class="text-sm"></div>
                            </th>
                            <th id="dist_div_8" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_8" class="text-sm"></div>
                            </th>
                            <th id="dist_div_505" style="background-color: #f1e6c875; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_505" class="text-sm"></div>
                            </th>
                            <th colspan="2" style="background-color: #f5e5b9; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="dist_counted" class="text-sm"></div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" style="background-color: #eeeeee; border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-xs">عدد الحاويات</div>
                            </th>
                            <th id="container_dist_div_3" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_3" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_10" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_10" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_7" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_7" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_13" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_13" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_4" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_4" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_6" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_6" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_5" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_5" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_12" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_12" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_11" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_11" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_9" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_9" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_8" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_8" class="text-sm"></div>
                            </th>
                            <th id="container_dist_div_505" style="background-color: #ede7dc; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_505" class="text-sm"></div>
                            </th>
                            <th colspan="2" style="background-color: #DCD2CC; border: 2px solid black; z-index: 10" class="border p-2">
                                <div id="container_dist_counted" class="text-sm"></div>
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

        var selected_product_code = null;

        $('#create-report').on('click', function () {

            if($('#product_code').val() == '' || $('#start_date').val() == '' || $('#end_date').val() == null) {

                Swal.fire({
                    title: "حدث خطأ",
                    text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                    icon: "error",
                    confirmButtonText: "موافق",
                });
            }
            else {
                $("#create-report").html('<b>الرجاء الإنتظار..</b>');

                if($('#product_code').val() != selected_product_code) {
                    $("input#size").val(null);
                }
                selected_product_code = $('#product_code').val();
                start_date = $('#start_date').val();
                end_date = $('#end_date').val();

                Livewire.emit('create-report', selected_product_code, start_date, end_date);
            }
        });

        Livewire.on('finished', () => {
            swal.close();
        });


        Livewire.on('show-container', () => {

            $(`[id ^='branch_']`).prop('checked', false);
            $('input#size').change();
            var size = 0;
            var counted_grandtotal = $('#counted-grandtotal').text();
            var dist_percent = 0;

            $('input#size').on('change', function () {
                //console.log($('#counted-grandtotal').text());
                size = $(this).val();
                var dist_percent = (parseFloat(size)/parseFloat($('#counted-grandtotal').text())*100).toFixed(2);
                console.log(size);
                console.log(counted_grandtotal);
                console.log(dist_percent);

                calculate_grand_total_dist(dist_percent, "#grand-total-3", '#dist_3');
                calculate_grand_total_dist(dist_percent, "#grand-total-10", '#dist_10');
                calculate_grand_total_dist(dist_percent, "#grand-total-7", '#dist_7');
                calculate_grand_total_dist(dist_percent, "#grand-total-13", '#dist_13');
                calculate_grand_total_dist(dist_percent, "#grand-total-4", '#dist_4');
                calculate_grand_total_dist(dist_percent, "#grand-total-6", '#dist_6');
                calculate_grand_total_dist(dist_percent, "#grand-total-5", '#dist_5');
                calculate_grand_total_dist(dist_percent, "#grand-total-12", '#dist_12');
                calculate_grand_total_dist(dist_percent, "#grand-total-11", '#dist_11');
                calculate_grand_total_dist(dist_percent, "#grand-total-9", '#dist_9');
                calculate_grand_total_dist(dist_percent, "#grand-total-8", '#dist_8');
                calculate_grand_total_dist(dist_percent, "#grand-total-505", '#dist_505');

                calculate_grand_total_dist(dist_percent, "#counted-grandtotal", '#dist_counted');
            })

            $("[id^='target_']").on('keyup', function () {
                console.log($(this).text());
                console.log($(this).attr('id'));
                txt = $(this).attr('id').split('_');
                branch_id = txt[1];
                date_txt = txt[2];
                console.log('branch_id:' + branch_id);


                total_column = 0;
                total_row = 0;
                total_dist = 0;
                total_counted = 0;

                // if(isNaN(parseFloat($(this).text()))) {
                //     alert("empty");
                // }
                $(`[id^='target_${branch_id}_']`).each(function () {
                    console.log($(this).text());
                    total_column += isNaN(parseFloat($(this).text())) ? 0 : parseFloat($(this).text());
                });


                $(`#grand-total-${branch_id}`).text(total_column);



                $(`[id^='target_'][id $='_${date_txt}']`).each(function () {
                    console.log($(this).text());
                    total_row += isNaN(parseFloat($(this).text())) ? 0 : parseFloat($(this).text());
                });

                console.log("total_row:"+total_row);
                console.log("date_text:"+date_txt);
                console.log('percent:'+$(`#month_percent_${date_txt}`).text());

                date_percent = parseFloat($(`#month_percent_${date_txt}`).text());
                // $(`#total_date_${date_txt}`).text(total_row);
                $(`#counted_total_date_${date_txt}`).text(total_row*(date_percent/100));


                $(`[id^='counted_total_date_']`).each(function () {
                    console.log($(this).text());
                    total_counted += parseFloat($(this).text());
                });

                $(`#counted-grandtotal`).text(total_counted);
                // $("#counted_grandtotal").text('KKK');

                if(!isNaN(parseFloat($('input#size').val()))) {
                    dist_percent = (parseFloat($('input#size').val())/parseFloat($(`#counted-grandtotal`).text())*100).toFixed(2);
                    total_dist = Math.round(total_column*(dist_percent/100));
                    $(`#dist_${branch_id}`).text(total_dist);

                    $(`#container_dist_${branch_id}`).text((parseFloat(total_dist)/parseFloat($('#capacity_size').val())).toFixed(2));

                    dist_counted = Math.round(parseFloat($("#counted-grandtotal").text())*(dist_percent/100));
                    $('#dist_counted').text(dist_counted);

                    $('#container_dist_counted').text((parseFloat(dist_counted)/parseFloat($('#capacity_size').val())).toFixed(2));
                }
            });

            $("[id^='month_percent_']").on('keyup', function () {
                // $("#target_3_1-2024").on('keyup', function () {
                //     alert('kokoka');
                console.log($(this).text());
                console.log($(this).attr('id'));
                txt = $(this).attr('id').split('_');
                // branch_id = txt[1];
                date_txt = txt[2];
                // alert(date_txt);
                // console.log('branch_id:' + branch_id);


                total_column = 0;
                total_row = 0;
                total_dist = 0;
                total_counted = 0;

                // $(`[id^='target_${branch_id}_']`).each(function () {
                //
                //     console.log($(this).text());
                //     total_column += parseFloat($(this).text());
                // });
                //
                //
                // $(`#grand-total-${branch_id}`).text(total_column);



                $(`[id^='target_'][id $='_${date_txt}']`).each(function () {
                    console.log($(this).text());
                    total_row += parseFloat($(this).text());
                });
                // alert(total_row);

                // alert(date_txt);
                date_percent = parseFloat($(`#month_percent_${date_txt}`).text());
                // alert(date_percent);
                // $(`#total_date_${date_txt}`).text(total_row);
                $(`#counted_total_date_${date_txt}`).text(total_row*(date_percent/100));


                $(`[id^='counted_total_date_']`).each(function () {
                    console.log($(this).text());
                    total_counted += parseFloat($(this).text());
                });

                $(`#counted-grandtotal`).text(total_counted);
                // $("#counted_grandtotal").text('KKK');

                dist_percent = (parseFloat($('input#size').val())/parseFloat($(`#counted-grandtotal`).text())*100).toFixed(2);
                total_dist = Math.round(total_column*(dist_percent/100));
                // $(`#dist_${branch_id}`).text(total_dist);

            });

            $('#capacity_size').on('change', function () {
                container_capacity = parseFloat($(this).val());

                $(`[id^='dist_']`).each(function () {

                    cell_id = $(this).attr('id');
                    // cell_id = cell_id.slice(1);

                    result = (parseFloat($(this).text())/parseFloat(container_capacity)).toFixed(2);
                    // alert(`#container_${cell_id}`);
                    result = isNaN(result) ? "": result;
                    $(`#container_${cell_id}`).text(result);
                });


            });

            $("[id^='branch_']:checkbox").on('change', function () {
                selected_branch_id = $(this).val().split('_');
                // alert(selected_branch_id[1]);
                // alert($(this).is(':checked'));
                result = 0;
                var dist_percent = (parseFloat($('input#size').val())/parseFloat($('#counted-grandtotal').text())*100).toFixed(2);

                if ($(this).is(':checked')) {
                    $(`#grand-total-${selected_branch_id[1]}`).text("0");

                    // bg colors
                    $(`#branch_${selected_branch_id[1]}`).css("background-color", "#ffd7dd");
                    $(`[id^='date_div_${selected_branch_id[1]}_']`).css("background-color", "#ffd7dd");
                    $(`#grand_total_div_${selected_branch_id[1]}`).css("background-color", "#ffd7dd");
                    $(`#dist_div_${selected_branch_id[1]}`).css("background-color", "#ffd7dd");
                    $(`#container_dist_div_${selected_branch_id[1]}`).css("background-color", "#ffd7dd");

                    // disable edit
                    $(`[id ^='target_${selected_branch_id[1]}_']`).prop('contenteditable', false);
                }
                else {
                    // bg colors
                    $(`#branch_${selected_branch_id[1]}`).css("background-color", "#eeeeee");
                    $(`[id^='date_div_${selected_branch_id[1]}_']`).css("background-color", "#63b96338");
                    $(`#grand_total_div_${selected_branch_id[1]}`).css("background-color", "#E7F2F8");
                    $(`#dist_div_${selected_branch_id[1]}`).css("background-color", "#f1e6c875");
                    $(`#container_dist_div_${selected_branch_id[1]}`).css("background-color", "#ede7dc");


                    tmp_total = 0;
                    $(`[id^='target_${selected_branch_id[1]}_']`).each(function () {
                        tmp_total += parseFloat($(this).text());
                    });
                    $(`#grand-total-${selected_branch_id[1]}`).text(Math.round(tmp_total));
                }


                $(`[id^='grand-total-']`).each(function () {

                    cell_id = $(this).attr('id');
                    branch_id = cell_id.split('-');
                    branch_id = branch_id[2];
                    // cell_id = cell_id.slice(1);

                    result += parseFloat($(this).text());

                    // dist_total = Math.round(parseFloat($(this).text())*(dist_percent/100));
                    // dist_total = isNaN(dist_total) ? "" : dist_total;
                    //
                    // $(`#dist_${branch_id}`).text(dist_total);
                    //
                    // container_dist_total = (parseFloat(dist_total)/parseFloat($('#capacity_size').val())).toFixed(2);
                    // container_dist_total = isNaN(container_dist_total) ? "" : container_dist_total;
                    // $(`#container_dist_${branch_id}`).text(container_dist_total);


                    // result = (parseFloat($(this).text())/parseFloat(container_capacity)).toFixed(2);
                    // alert(`#container_${cell_id}`);
                });

//////////////////////////////
//                 $(`[id^='dist_']`).each(function () {
//
//                     cell_id = $(this).attr('id');
//                     // cell_id = cell_id.slice(1);
//
//                     result = (parseFloat($(this).text())/parseFloat(container_capacity)).toFixed(2);
//                     // alert(`#container_${cell_id}`);
//                     result = isNaN(result) ? "": result;
//                     $(`#container_${cell_id}`).text(result);
//                 });
                /////////////////////////

                result = isNaN(result) ? "": result;
                $(`#counted-grandtotal`).text(Math.round(result));
                // dist_counted_result =
                // alert(dist_percent);
                dist_percent = (parseFloat($('input#size').val())/parseFloat($('#counted-grandtotal').text())*100).toFixed(2);

                /////////////
                $(`[id^='grand-total-']`).each(function () {

                    cell_id = $(this).attr('id');
                    branch_id = cell_id.split('-');
                    branch_id = branch_id[2];

                    dist_total = Math.round(parseFloat($(this).text())*(dist_percent/100));
                    dist_total = isNaN(dist_total) ? "" : dist_total;

                    $(`#dist_${branch_id}`).text(dist_total);

                    branch_container_dist_total = (parseFloat(dist_total)/parseFloat($('#capacity_size').val())).toFixed(2);
                    branch_container_dist_total = isNaN(branch_container_dist_total) ? "" : branch_container_dist_total;
                    $(`#container_dist_${branch_id}`).text(branch_container_dist_total);

                });

                ///////////////



                dist_counted_result = Math.round(parseFloat($(`#counted-grandtotal`).text())*(dist_percent/100));
                dist_counted_result = isNaN(dist_counted_result) ? "" : dist_counted_result;
                $('#dist_counted').text(dist_counted_result);

                container_dist_total = (parseFloat($('#dist_counted').text())/parseFloat($('#capacity_size').val())).toFixed(2);
                container_dist_total = isNaN(container_dist_total) ? "" : container_dist_total;
                $(`#container_dist_counted`).text(container_dist_total);


                // $("input#size").change();


            });


            console.log(counted_grandtotal);
            console.log(size);

        });

        function calculate_grand_total_dist(percent, total_div, dist_div) {
            total = parseFloat($(total_div).text());
            result = Math.round(total*(percent/100));
            result = isNaN(result) ? "" : result;
            $(dist_div).text(result);

            container_size = $("#capacity_size").val();
            container_div_txt = "#container_"+dist_div.slice(1);

            container_dist = (parseFloat(result)/parseFloat(container_size)).toFixed(2);
            container_dist = isNaN(container_dist) ? "" : container_dist;
            $(container_div_txt).text(container_dist);

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
