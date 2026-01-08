@section('title')
    10- فواتير عميل
@stop
<div>
    <div class="mb-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">فواتير عميل</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-row gap-4">
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div wire:ignore class="w-full">--}}
                {{--                    <label class="block font-bold mb-2">رقم العميل--}}
                {{--                        <span class="text-red-500">*</span>--}}
                {{--                    </label>--}}
                {{--                    <input id="customer_code" type="text" name="customer_code" wire:model="customer_code"--}}
                {{--                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
                {{--                           style="@error('item_id') border: solid 1px #fda4af; @enderror">--}}
                {{--                    @error('customer_code') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
                {{--                </div>--}}
                <div class="w-full">
                    <label class="block font-bold mb-2">رقم العميل
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_code" name="customer_code"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('customer_type') border: solid 1px #fda4af; @enderror">
                            @foreach($customer_list as $customer)
                                <option value="{{ $customer['CardCode'] }}">{{ $customer['CardCode'] }} - {{ $customer['CardName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_code')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button id="gen-report"
                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>إنشاء تقرير</span>
                        </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="report-btn" wire:loading.remove wire:target="generateReport" class="printable">
        <div id="tbl2-container" class="tbl-fixed overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">

                        <div class="text-sm">رقم الفاتورة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">تاريخ الفاتورة</div>
                    </th>
                    <th colspan="3" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">قيمة الفاتورة</div>
                    </th>
                </tr>
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">

                        <div class="text-sm">اسم الصنف</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">

                        <div class="text-sm">الوحدة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">كمية</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">سعر</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مجموع</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php $total = 0; $voucher_no = "*"; $counter = 0; @endphp
                @foreach($results as $record)

                    @if($record['VoucherNo'] != $voucher_no)
                        {{--                        <tr>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['Name']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                @if((substr($record['customer_code'], 0, 2) == "01" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-01") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الاحساء--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "02" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-02") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    جدة--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "03" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-03") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الرياض--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "04" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-04") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    وادي الدواسر--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "05" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-05") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الجوف--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "06" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-06") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الدمام--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "07" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-07") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الخرج--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "08" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-08") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    نجران--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "09" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-09") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    حائل--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "10" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-10") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    تبوك--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "11" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-11") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    القصيم--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "12" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-12" && strlen($record['customer_code'] ) == 9))--}}
                        {{--                                    ساجر--}}
                        {{--                                @endif--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['VoucherNo']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['VoucherDate']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2">--}}
                        {{--                                {{ number_format($record['Value'], 2) }}--}}
                        {{--                                @php $total += floatval($record['Value']); @endphp--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2">--}}
                        {{--                                {{$record['emp_name']}}--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}

                        {{--                        <tr onclick="show_hide('{{$record["VoucherNo"]}}')" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">--}}
                        {{--                            <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record['VoucherNo'] }}">+</td>--}}
                        {{--                            --}}{{--                            <td colspan="5" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div class="flex flex-row justify-between">--}}
                        {{--                            --}}{{--                                    <div>العميل--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>الفرع--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>رقم الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>تاريخ الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>قيمة الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>اسم المهندس--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                </div>--}}

                        {{--                            --}}{{--                            --}}{{----}}{{--                                            مجموع جزئي للصنف--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>العميل--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>الفرع--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                            <td colspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>رقم الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>تاريخ الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>قيمة الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>اسم المهندس--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                        </tr>--}}
                        <tr onclick="show_hide('{{$record["VoucherNo"]}}')" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record['Name']}}--}}
                            {{--                            </td>--}}
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                @if((substr($record['customer_code'], 0, 2) == "01" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-01") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الاحساء--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "02" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-02") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    جدة--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "03" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-03") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الرياض--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "04" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-04") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    وادي الدواسر--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "05" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-05") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الجوف--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "06" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-06") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الدمام--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "07" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-07") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الخرج--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "08" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-08") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    نجران--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "09" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-09") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    حائل--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "10" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-10") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    تبوك--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "11" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-11") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    القصيم--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "12" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-12" && strlen($record['customer_code'] ) == 9))--}}
                            {{--                                    ساجر--}}
                            {{--                                @endif--}}
                            {{--                            </td>--}}
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                +
                            </td>
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record['VoucherNo']}}
                            </td>
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($record['VoucherDate'])->format('Y-m-d') }}
                            </td>
                            <td colspan="3" style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Value'], 2) }}
                            </td>
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record['emp_name']}}--}}
                            {{--                            </td>--}}

                        </tr>

                            <?php $voucher_no = $record['VoucherNo']; ?>
                            <?php $total = $total + $record['Value']; ?>

                    @endif


                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{ $record["VoucherNo"] }} hide">
                        <td style="color: black;" class="border p-2 whitespace-nowrap">
                            {{$record['item_code']}}
                        </td>
                        <td style="color: #a22a2a;" class="border p-2 whitespace-nowrap">
                            {{$record['Arabic_Name']}}
                        </td>
                        <td style="color: #a22a2a;" class="border p-2 whitespace-nowrap">
                            {{$record['Unit']}}
                        </td>
                        <td style="color: #ff8659;" class="border p-2 whitespace-nowrap">
                            {{ number_format($record['qty'])}}
                        </td>
                        <td style="color: blueviolet;" class="border p-2 whitespace-nowrap">
                            {{ number_format($record['rate'])}}
                        </td>
                        <td style="color: green;" class="border p-2 whitespace-nowrap">
                            {{number_format($record['item_value'], 2)}}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach

                @foreach($sap_results as $record)

                    @if($record['VoucherNo'] != $voucher_no)
                        {{--                        <tr>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['Name']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                @if((substr($record['customer_code'], 0, 2) == "01" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-01") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الاحساء--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "02" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-02") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    جدة--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "03" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-03") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الرياض--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "04" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-04") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    وادي الدواسر--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "05" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-05") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الجوف--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "06" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-06") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الدمام--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "07" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-07") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    الخرج--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "08" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-08") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    نجران--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "09" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-09") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    حائل--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "10" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-10") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    تبوك--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "11" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-11") && strlen($record['customer_code'] ) == 9)--}}
                        {{--                                    القصيم--}}
                        {{--                                @elseif((substr($record['customer_code'], 0, 2) == "12" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-12" && strlen($record['customer_code'] ) == 9))--}}
                        {{--                                    ساجر--}}
                        {{--                                @endif--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['VoucherNo']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2 whitespace-nowrap">--}}
                        {{--                                {{$record['VoucherDate']}}--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2">--}}
                        {{--                                {{ number_format($record['Value'], 2) }}--}}
                        {{--                                @php $total += floatval($record['Value']); @endphp--}}
                        {{--                            </td>--}}
                        {{--                            <td class="border p-2">--}}
                        {{--                                {{$record['emp_name']}}--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}

                        {{--                        <tr onclick="show_hide('{{$record["VoucherNo"]}}')" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">--}}
                        {{--                            <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record['VoucherNo'] }}">+</td>--}}
                        {{--                            --}}{{--                            <td colspan="5" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div class="flex flex-row justify-between">--}}
                        {{--                            --}}{{--                                    <div>العميل--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>الفرع--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>رقم الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>تاريخ الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>قيمة الفاتورة--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                    <div>اسم المهندس--}}
                        {{--                            --}}{{--                                    </div>--}}
                        {{--                            --}}{{--                                </div>--}}

                        {{--                            --}}{{--                            --}}{{----}}{{--                                            مجموع جزئي للصنف--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>العميل--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>الفرع--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                            <td colspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>رقم الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>تاريخ الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div>قيمة الفاتورة--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            --}}{{--                            <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            --}}{{--                                <div>اسم المهندس--}}
                        {{--                            --}}{{--                                </div>--}}
                        {{--                            --}}{{--                            </td>--}}
                        {{--                        </tr>--}}
                        <tr onclick="show_hide('{{$record["VoucherNo"]}}')" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record['Name']}}--}}
                            {{--                            </td>--}}
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                @if((substr($record['customer_code'], 0, 2) == "01" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-01") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الاحساء--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "02" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-02") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    جدة--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "03" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-03") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الرياض--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "04" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-04") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    وادي الدواسر--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "05" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-05") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الجوف--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "06" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-06") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الدمام--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "07" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-07") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    الخرج--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "08" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-08") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    نجران--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "09" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-09") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    حائل--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "10" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-10") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    تبوك--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "11" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-11") && strlen($record['customer_code'] ) == 9)--}}
                            {{--                                    القصيم--}}
                            {{--                                @elseif((substr($record['customer_code'], 0, 2) == "12" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-12" && strlen($record['customer_code'] ) == 9))--}}
                            {{--                                    ساجر--}}
                            {{--                                @endif--}}
                            {{--                            </td>--}}
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                +
                            </td>
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record['VoucherNo']}}
                            </td>
                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($record['VoucherDate'])->format('Y-m-d') }}
                            </td>
                            <td colspan="3" style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Value'], 2) }}
                            </td>
                            {{--                            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record['emp_name']}}--}}
                            {{--                            </td>--}}

                        </tr>

                            <?php $voucher_no = $record['VoucherNo']; ?>
                            <?php $total = $total + $record['Value']; ?>
                    @endif


                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{ $record["VoucherNo"] }} hide">
                        <td style="color: black;" class="border p-2 whitespace-nowrap">
                            {{$record['item_code']}}
                        </td>
                        <td style="color: #a22a2a;" class="border p-2 whitespace-nowrap">
                            {{$record['Arabic_Name']}}
                        </td>
                        <td style="color: #a22a2a;" class="border p-2 whitespace-nowrap">
                            {{$record['Unit']}}
                        </td>
                        <td style="color: #ff8659;" class="border p-2 whitespace-nowrap">
                            {{ number_format($record['qty'])}}
                        </td>
                        <td style="color: blueviolet;" class="border p-2 whitespace-nowrap">
                            {{ number_format($record['rate'])}}
                        </td>
                        <td style="color: green;" class="border p-2 whitespace-nowrap">
                            {{number_format($record['item_value'], 2)}}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
                </tbody>
                <tfoot style="border: 2px solid black;">
                <tr>
                    <th style="padding: 10px; background-color: #e8e8e8;" colspan="7">
                        <span>المجموع: </span>
                        <span>{{ number_format($total, 2) }}</span>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div  wire:loading wire:target="generateReport" class="w-full">
        <div class="w-full" style="border: solid 1px grey;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <rect x="17.5" y="30" width="15" height="40" fill="#e15b64">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>
                </rect>
                <rect x="42.5" y="30" width="15" height="40" fill="#f8b26a">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>
                </rect>
                <rect x="67.5" y="30" width="15" height="40" fill="#abbd81">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
                </rect>
            </svg>

            <div class="mb-4 bold text-2xl text-center">
                الرجاء الإنتظار
            </div>
        </div>
    </div>
</div>

@section('scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        $(document).ready(function () {
            $('#customer_code').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });
        })

        $('#gen-report').on('click', function () {

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var customer_code = $('#customer_code').select2("val");

            $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

            if(start_date == '' || end_date == '') {
                Swal.fire({
                    title: "حدث خطأ",
                    text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                    icon: "error",
                    confirmButtonText: "موافق",
                });
                $("#gen-report").html('<b>إنشاء تقرير</b>');
            }

            else {
                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                Swal.fire({
                    title: 'الرجاء الإنتظار',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                });

                Livewire.emit('create-report', start_date, end_date, customer_code);
                // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
            }
        })

        Livewire.on('finished', () => {

            swal.close();
        })

        function show_hide(acc) {
            console.log('hehe');
            if ($('.row-'+acc).hasClass('hide')) {
                console.log('coco');
                $('.row-'+acc).removeClass('hide');
                $('.parent-'+acc).text('-');
            } else {
                console.log('.row-'+acc);
                console.log('.parent-'+acc);
                $('.row-'+acc).addClass('hide');
                $('.parent-'+acc).text('+');
            }
        }
    </script>

    {{--    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>--}}

    {{--    <script>--}}

    {{--        // $('#tbl2').DataTable().destroy();--}}
    {{--        // $('#tbl2').empty();--}}
    {{--        // a();--}}
    {{--        // Livewire.on('show-container', () => {--}}
    {{--        //--}}
    {{--        //--}}
    {{--        //     // if ( $.fn.dataTable.isDataTable('#tbl2') ) {--}}
    {{--        //     //     $('#tbl2').DataTable( {--}}
    {{--        //     //         destroy: true,--}}
    {{--        //     //         searching: false--}}
    {{--        //     //     } );--}}
    {{--        //     //     // this.dataTable.destroy();--}}
    {{--        //     //     // this.chRef.detectChanges();--}}
    {{--        //     //     // this.dataTable = $("#tbl2").DataTable();--}}
    {{--        //     //     // $('#tbl2').DataTable().destroy();--}}
    {{--        //     //     // $('#tbl2').empty();--}}
    {{--        //     //--}}
    {{--        //     //     // a();--}}
    {{--        //     //     // const table = new DataTable('#tbl2');--}}
    {{--        //     //     // table.draw();--}}
    {{--        //     //     //--}}
    {{--        //     //     // // $('#tbl2').DataTable().clear().destroy();--}}
    {{--        //     //     // a();--}}
    {{--        //     // }--}}
    {{--        //--}}
    {{--        //     a();--}}
    {{--        //--}}
    {{--        //--}}
    {{--        // });--}}

    {{--        function a() {--}}

    {{--            // if ( $.fn.dataTable.isDataTable('#tbl2') ) {--}}
    {{--            //     $('#tbl2').DataTable().destroy();--}}
    {{--            //     $('#tbl2').empty();--}}
    {{--            // }--}}

    {{--            div = document.getElementById("report-btn");--}}
    {{--            // div_title = document.getElementById("report_title");--}}
    {{--            // // area = document.getElementById("area_id");--}}
    {{--            // start_date = document.getElementById("start_date");--}}
    {{--            // end_date = document.getElementById("end_date");--}}

    {{--            // div.classList.remove("hide");--}}

    {{--            // div_title.innerHTML = "تقرير " + area.options[area.selectedIndex].text + "(" + date.value + ")"--}}

    {{--            var collapsedGroups = {};--}}

    {{--            // tbl.draw();--}}

    {{--            $('#tbl2').DataTable(--}}
    {{--                {--}}
    {{--                    initComplete: function () {--}}
    {{--                        this.api()--}}
    {{--                            .columns(2)--}}
    {{--                            .every(function () {--}}
    {{--                                var column = this;--}}
    {{--                                var select = $('<select><option value=""></option></select>')--}}
    {{--                                    .appendTo($(column.header()).empty())--}}
    {{--                                    .on('change', function () {--}}
    {{--                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());--}}

    {{--                                        column.search(val ? '^' + val + '$' : '', true, false).draw();--}}
    {{--                                    });--}}

    {{--                                column--}}
    {{--                                    .data()--}}
    {{--                                    .unique()--}}
    {{--                                    .sort()--}}
    {{--                                    .each(function (d, j) {--}}
    {{--                                        select.append('<option value="' + d + '">' + d + '</option>');--}}
    {{--                                    });--}}

    {{--                                $(column.footer()).empty();--}}

    {{--                            });--}}
    {{--                    },--}}
    {{--                    dom: 'lBfrtip',--}}
    {{--                    retrieve: true,--}}
    {{--                    // "bDestroy": true,--}}
    {{--                    "lengthMenu": [ 100, 200, 300, 400 ],--}}
    {{--                    "pageLength": 300,--}}
    {{--                    "language": {--}}
    {{--                        "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",--}}
    {{--                        "sLoadingRecords": "جارٍ التحميل...",--}}
    {{--                        "sProcessing": "جارٍ التحميل...",--}}
    {{--                        "sLengthMenu": "أظهر _MENU_ مدخلات",--}}
    {{--                        "sZeroRecords": "لم يعثر على أية سجلات",--}}
    {{--                        "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",--}}
    {{--                        "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",--}}
    {{--                        "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",--}}
    {{--                        "sInfoPostFix": "",--}}
    {{--                        "sSearch": "ابحث:",--}}
    {{--                        "sUrl": "",--}}
    {{--                        "oPaginate": {--}}
    {{--                            "sFirst": "الأول",--}}
    {{--                            "sPrevious": "السابق",--}}
    {{--                            "sNext": "التالي",--}}
    {{--                            "sLast": "الأخير"--}}
    {{--                        },--}}
    {{--                        "oAria": {--}}
    {{--                            "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",--}}
    {{--                            "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"--}}
    {{--                        }--}}
    {{--                    },--}}
    {{--                    buttons: [--}}
    {{--                        {extend: 'copy', text: 'نسخ'},--}}
    {{--                        {extend: 'excel', text: 'تصدير إلى اكسل'},--}}
    {{--                    ],--}}
    {{--                    // start of row group section--}}
    {{--                    // paging: false,--}}
    {{--                    order: [--}}
    {{--                        [4, 'asc'], [0, 'asc']--}}
    {{--                    ],--}}
    {{--                    columnDefs: [ { orderable: false, targets: [0, 1] }],--}}
    {{--                    // rowGroup: {--}}
    {{--                    //     startRender: null,--}}
    {{--                    //     endRender: function (rows, group) {--}}
    {{--                    //--}}
    {{--                    //--}}
    {{--                    //         var customer_name = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(3)[0];--}}
    {{--                    //--}}
    {{--                    //         var collected_amount = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(4)--}}
    {{--                    //             .reduce(function (a, b) {--}}
    {{--                    //                 // console.log(b);--}}
    {{--                    //                 return a + parseFloat(b.replace(/\,/g,'')) * 1;--}}
    {{--                    //             }, 0);--}}
    {{--                    //--}}
    {{--                    //         var cash_sales = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(5)--}}
    {{--                    //             .reduce(function (a, b) {--}}
    {{--                    //                 // console.log(b);--}}
    {{--                    //                 return a + parseFloat(b.replace(/\,/g,'')) * 1;--}}
    {{--                    //             }, 0);--}}
    {{--                    //--}}
    {{--                    //         var postponed_sales = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(6)--}}
    {{--                    //             .reduce(function (a, b) {--}}
    {{--                    //                 // console.log(b);--}}
    {{--                    //                 return a + parseFloat(b.replace(/\,/g,'')) * 1;--}}
    {{--                    //             }, 0);--}}
    {{--                    //--}}
    {{--                    //--}}
    {{--                    //         var postponed_total = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(7)--}}
    {{--                    //             .reduce(function (a, b) {--}}
    {{--                    //                 // console.log(b);--}}
    {{--                    //                 return a + parseFloat(b.replace(/\,/g,'')) * 1;--}}
    {{--                    //             }, 0);--}}
    {{--                    //--}}
    {{--                    //         var dueAmount = rows--}}
    {{--                    //             .data()--}}
    {{--                    //             .pluck(8)--}}
    {{--                    //             .reduce(function (a, b) {--}}
    {{--                    //                 // console.log(b);--}}
    {{--                    //                 return a + parseFloat(b.replace(/\,/g,'')) * 1;--}}
    {{--                    //             }, 0);--}}
    {{--                    //--}}
    {{--                    //         // dueAmount = $.fn.dataTable.render.number(',', '.', 0, '$').display( dueAmount );--}}
    {{--                    //--}}
    {{--                    //         // var ageAvg = rows--}}
    {{--                    //         //     .data()--}}
    {{--                    //         //     .pluck(3)--}}
    {{--                    //         //     .reduce( function (a, b) {--}}
    {{--                    //         //         return a + b*1;--}}
    {{--                    //         //     }, 0) / rows.count();--}}
    {{--                    //--}}
    {{--                    //         return $('<tr style="background-color: #f2f0f0; font-weight: bold; color: #233881;" />')--}}
    {{--                    //             .append('<td style="border: 1px solid;" colspan="4">المجموع لـ '+ group + '</td>')--}}
    {{--                    //             .append('<td style="border: 1px solid;" >' + collected_amount.toLocaleString("en-US") + '</td>')--}}
    {{--                    //             .append('<td style="border: 1px solid;" >' + cash_sales.toLocaleString("en-US") + '</td>')--}}
    {{--                    //             .append('<td style="border: 1px solid;" >' + postponed_sales.toLocaleString("en-US") + '</td>')--}}
    {{--                    //             .append('<td style="border: 1px solid;" >' + postponed_total.toLocaleString("en-US") + '</td>')--}}
    {{--                    //             .append('<td style="border: 1px solid;" >' + dueAmount.toLocaleString("en-US") + '</td>');--}}
    {{--                    //     },--}}
    {{--                    //     dataSrc: [2, 3]--}}
    {{--                    // }--}}
    {{--                });--}}


    {{--            const table = new DataTable('#tbl2');--}}
    {{--            table.draw();--}}



    {{--            // filtering--}}
    {{--            // const report_typeEl = document.querySelector('#report_type');--}}
    {{--            // report_typeEl.selectedIndex = 0;--}}
    {{--            //--}}
    {{--            //--}}
    {{--            // if (report_typeEl.value == "show") {--}}
    {{--            //     // Custom range filtering function--}}
    {{--            //     DataTable.ext.search.push(function (settings, data, dataIndex) {--}}
    {{--            //         let reportType = data[9]; // use data for the status column--}}
    {{--            //         // console.log(reportType);--}}
    {{--            //         // console.log(report_typeEl.value);--}}
    {{--            //         if (report_typeEl.value == reportType) {--}}
    {{--            //             return true--}}
    {{--            //         }--}}
    {{--            //--}}
    {{--            //         return false;--}}
    {{--            //     });--}}
    {{--            //--}}
    {{--            // }--}}
    {{--            // else {--}}
    {{--            //     DataTable.ext.search.pop();--}}
    {{--            // }--}}
    {{--            //--}}
    {{--            // const table = new DataTable('#tbl2');--}}
    {{--            // table.draw();--}}

    {{--// Changes to the inputs will trigger a redraw to update the table--}}
    {{--//             report_typeEl.addEventListener('change', function () {--}}
    {{--//                 if (report_typeEl.value == "show") {--}}
    {{--//                     // Custom range filtering function--}}
    {{--//                     DataTable.ext.search.push(function (settings, data, dataIndex) {--}}
    {{--//                         let reportType = data[9]; // use data for the status column--}}
    {{--//--}}
    {{--//                         if (report_typeEl.value == reportType) {--}}
    {{--//                             return true--}}
    {{--//                         }--}}
    {{--//--}}
    {{--//                         return false;--}}
    {{--//                     });--}}
    {{--//                 }--}}
    {{--//                 else {--}}
    {{--//                     DataTable.ext.search.pop();--}}
    {{--//                 }--}}
    {{--//                 table.draw();--}}
    {{--//             });--}}
    {{--        }--}}



    {{--    </script>--}}
@stop
@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            width: 100%;
            padding-right: 2.5rem;
            padding-top: 0.2rem;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }

        .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            /* left: 1px; */
            right: 9px;
        }

        .select-font-size {
            font-size: 0.875rem; /* 14px */
            line-height: 1.25rem; /* 20px */
        }

        .hide {
            display: none;
        }

        #report-logo {
            display: none;
        }

        .tbl-fixed {
            overflow-x: scroll;
            overflow-y: scroll;
            height: fit-content;
            max-height: 70vh;
        }

        table th {
            position: sticky;
            top: 0px;
            background: #f8fafc;
            border: 2px solid black;
        }

        @media print {

            @page {size: A4 landscape}

            html { overflow: hidden; }

            body * {
                visibility: hidden;
                margin:0; padding:0;
                background-color: white;
            }
            .printable * {
                visibility: visible;
            }
            #tbl2 {
                transform: scale(0.7);
                translate: 14%;
            }
            #tbl2-container {
                overflow: hidden;
            }

            #branch-container {
                display: none;
            }

            #body-content {
                background-color: white;
            }

            #report-logo {
                display: unset;
            }

            .sticky {
                display: none;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css" />

@stop
