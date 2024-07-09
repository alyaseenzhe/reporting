@section('title')
    تقرير تحليل الفرع
@stop
<div>
    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" selected>الكل</option>
                            <option value="3" >الاحساء</option>
                            <option value="10" >جدة</option>
                            <option value="7" >الرياض</option>
                            <option value="13" >وادي الدواسر</option>
                            <option value="4" >الجوف</option>
                            <option value="6" >الدمام</option>
                            <option value="5" >الخرج</option>
                            <option value="12" >نجران</option>
                            <option value="11" >حائل</option>
                            <option value="9" >تبوك</option>
                            <option value="8" >القصيم</option>
                            <option value="505" >ساجر</option>
                        </select>
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
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
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">اسم الفرع</div>
                    </th>
                    <th colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">خلال الفترة</div>
                    </th>
                    <th colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">خلال سنة</div>
                    </th>
                    <th colspan="5" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">الآجل</div>
                    </th>
                    <th colspan="3" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مخزون</div>
                    </th>
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">صافي الربح لفترة</div>
                    </th>
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">صافي الربح لسنة</div>
                    </th>
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">مصاريف تشغيلية</div>
                    </th>
                </tr>
                <tr style="border: 2px solid black;">
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مميز 1</div>
                    </th>
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مميز 2</div>
                    </th>
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">المجموع</div>
                    </th>
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مميز 1</div>
                    </th>
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مميز 2</div>
                    </th>
                    <th colspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">المجموع</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مجموع</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">% الآجل</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">% مستحق</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">نسبة الآجل المستحق</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">وعاء الآجل غير نقدي</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">رصيد</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">% مبيعات سنة</div>
                    </th>
                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">تدوير مخزون</div>
                    </th>
                </tr>
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">% نمو</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                @endphp
                @foreach($scribes_results as $record)
                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{$record->arabic_name}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format((((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn))/(floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease)))-1)*100) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format((((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease)))-1)*100) }}
                        </td>

                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format(((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format(((((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/((floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease))+(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease))))-1)*100) }}
                        </td>

                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/(floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease)))-1)*100) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format((((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)))-1)*100) }}
                        </td>

                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2">
                            {{ number_format(((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))))-1)*100) }}
                        </td>

                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format((floatval($record->DebitCustomers))/1000, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((100*((floatval($record->DebitCustomers)))/1000)/(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{number_format(100*(floatval($record->DueBalance)/floatval($record->DebitCustomers)))}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format(100*((floatval($record->DebitCustomers))/1000)/((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000), 2) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format(100*(((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000)/((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)))) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->YearCOGS)/((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000))/1000, 2) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->TotalIncome) - floatval($record->TotalExpenses))/1000, 2) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->YearTotalIncome) - floatval($record->YearTotalExpenses))/1000) }}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format((floatval($record->TotalExpenses) - floatval($record->COGS))/1000, 2) }}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
                @foreach($sap_results as $record)
{{--                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">--}}
{{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                            {{$record['CardCode']}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                            {{$record['TransId']}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
{{--                            {{ \Carbon\Carbon::parse($record['RefDate'])->format('Y-m-d')}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                            {{$record['LineMemo']}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                            {{number_format($record['Debit'], 2)}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                            {{number_format($record['Credit'], 2)}}--}}
{{--                        </td>--}}
{{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                            {{number_format($record['CumulativeBalance'], 2)}}--}}
{{--                        </td>--}}
{{--                    </tr>--}}
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
        $(document).ready(function () {

            $('#dept_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            var prev_depts = $('#dept_id').select2("val");

            $('#dept_id').on('change', function (e) {
                var data = $('#dept_id').select2("val");

                if (prev_depts && prev_depts.includes('dept_all') == false && data.includes('dept_all') == true && prev_depts.length != data.length) {
                    $("#dept_id option").prop('selected', false);
                    $("#dept_id option[value='dept_all']").prop('selected', true);

                    prev_depts = $(this).val();
                    $('#dept_id').change();
                }
                else {

                    if (prev_depts && prev_depts.length != data.length) {
                        $("#dept_id option[value='dept_all']").removeAttr('selected');
                        prev_depts = $(this).val();

                        $("#dept_id").change();
                    }
                }
            });


            $('#gen-report').on('click', function () {


                var dept_id = $('#dept_id').select2("val");
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                // alert(start_date);


                if(start_date == '' || end_date == '' || dept_id == "") {
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

                    Livewire.emit('create-report', start_date, end_date, dept_id);
                }
            });

        });

        Livewire.on('finished', () => {
            swal.close();
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
