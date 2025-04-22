<div>
    <div class="mb-5">
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">كشف حساب عميل</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">كشف حساب عميل (21)</div>
    </div>
    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">العميل
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_name" name="customer_name"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            {{--                            <option value="customer_all">الكل</option>--}}
                            @foreach($customer_list as $customer)
                                <option value="{{ $customer['CardCode'] }}">{{$customer['CardCode']}} : {{ $customer['CardName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_name') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('start_date') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('end_date') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إعادة ضبط</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
                <div class="mt-8 text-center w-full">
                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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

    @if($show_msg)
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">رقم العميل</div>--}}
                    {{--                    </th>--}}
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مرجع السند</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">تاريخ العملية</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">نوع السند</div>
                    </th>
                    <th style="border-left: 2px solid black; color: #1c7430" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مدين (+)</div>
                    </th>
                    <th style="border-left: 2px solid black; color: #721c24" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">دائن (-)</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الرصيد</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                @endphp
                @foreach($scribes_results as $key => $record)
                    @if(count($scribes_results) > 0 && $key === array_key_first($scribes_results))
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record->CardCode}}--}}
                            {{--                            </td>--}}
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{--                            {{$record->TransId}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{--                            {{ \Carbon\Carbon::parse($record->RefDate)->format('Y-m-d')}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                الرصيد الافتتاحي
                            </td>
                            @php  $ob = floatval($record->CumulativeBalance) + floatval($record->Credit) - floatval($record->Debit); @endphp
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                                {{ $ob > 0 ? number_format($ob, 2) : ''}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                                {{ $ob < 0 ? number_format($ob, 2) : ''}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $ob != 0 ? number_format($ob, 2) : '' }}
                                {{--                            {{number_format($record->CumulativeBalance, 2)}}--}}
                            </td>
                        </tr>
                        @php $counter++ @endphp
                    @endif
                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                        {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            {{$record->CardCode}}--}}
                        {{--                        </td>--}}
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{$record->TransId}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ \Carbon\Carbon::parse($record->RefDate)->format('Y-m-d')}}
                        </td>
                        @php $trans_code = explode('-', $record->TransId); $trans_code = $trans_code[0];  @endphp
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            @if($trans_code == "210")
                                مبيعات
                            @elseif($trans_code == "211")
                                مبيعات (عكس)
                            @elseif($trans_code == "212")
                                مبيعات (ارتجاع)
                            @elseif($trans_code == "213")
                                مبيعات (عكس ارتجاع)
                            @elseif($trans_code == "220")
                                مشتريات
                            @elseif($trans_code == "221")
                                مشتريات (عكس)
                            @elseif($trans_code == "222")
                                مشتريات (ارتجاع)
                            @elseif($trans_code == "223")
                                مشتريات (عكس ارتجاع)
                            @elseif($trans_code == "230")
                                تسوية موجبة
                            @elseif($trans_code == "231")
                                تسوية موجبة (عكس)
                            @elseif($trans_code == "240")
                                تسوية سالبة
                            @elseif($trans_code == "241")
                                تسوية سالبة (عكس)
                            @elseif($trans_code == "250")
                                نقل من
                            @elseif($trans_code == "251")
                                نقل من (عكس)
                            @elseif($trans_code == "260")
                                نقل إلى
                            @elseif($trans_code == "261")
                                نقل إلى (عكس)
                            @elseif($trans_code == "270")
                                تسعيرة
                            @elseif($trans_code == "280")
                                طلب شراء
                            @elseif($trans_code == "290")
                                امر شراء
                            @elseif($trans_code == "300")
                                مبيعات داخلية
                            @elseif($trans_code == "301")
                                مبيعات داخلية (عكس)
                            @elseif($trans_code == "302")
                                مبيعات داخلية (ارتجاع)
                            @elseif($trans_code == "303")
                                مبيعات داخلية (عكس ارتجاع)
                            @elseif($trans_code == "310")
                                مشتريات فروع
                            @elseif($trans_code == "311")
                                مشتريات فروع (عكس)
                            @elseif($trans_code == "312")
                                مشتريات فروع (ارتجاع)
                            @elseif($trans_code == "313")
                                مشتريات فروع (عكس ارتجاع)
                            @elseif($trans_code == "320")
                                استلام مشتريات
                            @elseif($trans_code == "321")
                                استلام مشتريات (عكس)
                            @elseif($trans_code == "322")
                                استلام مشتريات (ارتجاع)
                            @elseif($trans_code == "323")
                                استلام مشتريات (عكس ارتجاع)

                            @elseif($trans_code == "010")
                                قيد
                            @elseif($trans_code == "011")
                                قيد (عكس)
                            @elseif($trans_code == "020")
                                سند صرف
                            @elseif($trans_code == "021")
                                سند صرف (عكس)
                            @elseif($trans_code == "030")
                                سند قبض
                            @elseif($trans_code == "031")
                                سند قبض (عكس)
                            @elseif($trans_code == "040")
                                اشعار دائن
                            @elseif($trans_code == "041")
                                اشعار دائن (عكس)
                            @elseif($trans_code == "050")
                                اشعار مدين
                            @elseif($trans_code == "051")
                                اشعار مدين (عكس)
                            @elseif($trans_code == "060")
                                نقل نقدي
                            @elseif($trans_code == "061")
                                نقل نقدي (عكس)
                            @elseif($trans_code == "070")
                                قبض شيكات آجلة
                            @elseif($trans_code == "071")
                                قبض شيكات آجلة (عكس)
                            @elseif($trans_code == "080")
                                دفع شيكات آجلة
                            @elseif($trans_code == "081")
                                دفع شيكات آجلة (عكس)
                            @else
                                N/A
                            @endif
                            {{--                        {{$record->LineMemo}}--}}
                        </td>
                        {{--                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                        {{$record->LineMemo}}--}}
                        {{--                    </td>--}}
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record->Debit != 0? number_format($record->Debit, 2) : ''}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record->Credit != 0? number_format($record->Credit, 2) : ''}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record->CumulativeBalance != 0? number_format($record->CumulativeBalance, 2) : ''}}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
                @foreach($sap_results as $key2 => $record)
                    @if(count($scribes_results) == 0 && $key2 === array_key_first($sap_results))
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                                {{$record['CardCode']}}--}}
                            {{--                            </td>--}}
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{--                            {{$record['TransId']}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{--                            {{ \Carbon\Carbon::parse($record['RefDate'])->format('Y-m-d')}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                الرصيد الافتتاحي
                            </td>
                            @php  $ob = floatval($record['CumulativeBalance']) + floatval($record['Credit']) - floatval($record['Debit']); @endphp
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                                {{ $ob > 0 ? number_format($ob, 2) : ''}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                                {{ $ob < 0 ? number_format($ob, 2) : ''}}--}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $ob != 0 ? number_format($ob, 2) : '' }}
                                {{--                            {{number_format($record['CumulativeBalance'], 2)}}--}}
                            </td>
                        </tr>
                        @php $counter++ @endphp
                    @endif
                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                        {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            {{$record['CardCode']}}--}}
                        {{--                        </td>--}}
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{$record['TransId']}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ \Carbon\Carbon::parse($record['RefDate'])->format('Y-m-d')}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            @if(str_contains($record['LineMemo'], 'فواتير الحسابات مستحقة القبض'))
                                مبيعات
                            @elseif(str_contains($record['LineMemo'], 'الدَفعات الواردة'))
                                سند قبض
                            @elseif(str_contains($record['LineMemo'], 'المذكرات الدائنة للحسابات مستحقة القبض'))
                                مبيعات (ارتجاع)
                            @else
                                {{$record['LineMemo']}}
                            @endif
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record['Debit'] != 0 ? number_format($record['Debit'], 2) : ''}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record['Credit'] != 0 ? number_format($record['Credit'], 2) : ''}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $record['CumulativeBalance'] != 0 ? number_format($record['CumulativeBalance'], 2) : ''}}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        Livewire.on('show-container', () => {
            $("#gen-report").html('<b>إنشاء تقرير</b>');

        });

        Livewire.on('finished', () => {
            swal.close();
        });

        $(document).ready(function () {

            $('#customer_name').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#gen-report').on('click', function () {

                var customer_id = $('#customer_name').select2("val");
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                // $("#gen-report").html('<b>الرجاء الإنتظار..</b>');
                //
                // Swal.fire({
                //     title: 'الرجاء الإنتظار',
                //     allowOutsideClick: false,
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     willOpen: () => {
                //         Swal.showLoading()
                //     },
                // });
                //
                // Livewire.emit('create-report', customer_id, start_date, end_date);


                if(start_date == '' || end_date == '') {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
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

                    Livewire.emit('create-report', customer_id, start_date, end_date);
                }
            });



        });

    </script>
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
    </style>
@stop
