@section('title')
    تقرير الاقسام التسويقية
@stop
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير الاقسام التسويقية</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" wire:model="start_date" min="2024-01-01" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" wire:model="end_date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
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

    @if($show_msg)
        @php
            $current_month = 0;
            $previous_month = 0;
            $year_total = 0;

        @endphp
        <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black; color: black">
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">الوصف</div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>مبيعات الفترة</div>
                        </div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">مبيعات فترة (العام الماضي)</div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">نمو %</div>
                    </th>
                    <th style="padding: 10px;">
                        <div class="text-xs">مبيعات سنة</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @foreach($sap_results as $record)
                    <tr style="border: 1px dashed black;">
                        <td style="padding: 10px; background-color: #e8f9e8; border-left: 2px solid black; font-weight: bold" class="whitespace-nowrap">
                            @if($record["mrkt_type"] == "QryGroup30")
                                ادارة فنية - الاسمدة م1
                            @elseif($record["mrkt_type"] == "QryGroup31")
                                ادارة فنية - المبيدات م1
                            @elseif($record["mrkt_type"] == "QryGroup32")
                                ادارة فنية - البذور م1
                            @elseif($record["mrkt_type"] == "QryGroup40")
                                اقسام تسويقية - الحدائق والصحة العامة
                            @elseif($record["mrkt_type"] == "QryGroup41")
                                اقسام تسويقية - المكافحة المتكاملة
                            @elseif($record["mrkt_type"] == "QryGroup50")
                                تقنيات الزراعة - الآليات
                            @elseif($record["mrkt_type"] == "QryGroup51")
                                تقنيات الزراعة - الري
                            @elseif($record["mrkt_type"] == "QryGroup52")
                                تقنيات الزراعة - انظمة نترا
                            @elseif($record["mrkt_type"] == "QryGroup53")
                                تقنيات الزراعة - الخدمات
                            @endif
                        </td>
                        <td style="color:#0072ffb8; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ number_format(floatval($record["CurrentMonth"]), 2) }}
                            @php $current_month += floatval($record["CurrentMonth"]) @endphp
                        </td>
                        <td style="color: #5f9ea0; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ number_format(floatval($record["PreviousMonth"]), 2) }}
                            @php $previous_month += floatval($record["PreviousMonth"]) @endphp
                        </td>
                        <td style="color: #c71585; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ floatval($record["PreviousMonth"]) != 0 ? number_format(((floatval($record["CurrentMonth"])-floatval($record["PreviousMonth"]))/floatval($record["PreviousMonth"]))*100, 2) : 0 }}
                        </td>
                        <td style="color: #00008b; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ number_format(floatval($record["CurrentYear"]), 2) }}
                            @php $year_total += floatval($record["CurrentYear"]) @endphp
                        </td>

                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold; background-color: #fff8dc;">
                    <td style="padding: 10px;border-left: 2px solid black; color: black">المجموع</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($current_month , 2) }}</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($previous_month , 2) }}</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ $previous_month != 0 ? number_format((($current_month-$previous_month)/$previous_month)*100 , 2) : 0 }}</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($year_total , 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

{{--    <script>--}}
{{--        $('#gen-report').on('click', function () {--}}


{{--            var dept_id = $('#dept_id').select2("val");--}}
{{--            var start_date = $('#start_date').val();--}}
{{--            var end_date = $('#end_date').val();--}}
{{--            // alert(start_date);--}}


{{--            if(start_date == '' || end_date == '' || (dept_id == "" || dept_id == null)) {--}}
{{--                Swal.fire({--}}
{{--                    title: "حدث خطأ",--}}
{{--                    text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",--}}
{{--                    icon: "error",--}}
{{--                    confirmButtonText: "موافق",--}}
{{--                });--}}
{{--                $("#gen-report").html('<b>إنشاء تقرير</b>');--}}
{{--            }--}}
{{--            else {--}}
{{--                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');--}}

{{--                Swal.fire({--}}
{{--                    title: 'الرجاء الإنتظار',--}}
{{--                    allowOutsideClick: false,--}}
{{--                    showCancelButton: false,--}}
{{--                    showConfirmButton: false,--}}
{{--                    willOpen: () => {--}}
{{--                        Swal.showLoading()--}}
{{--                    },--}}
{{--                });--}}

{{--                Livewire.emit('create-report', start_date, end_date, dept_id);--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
@stop

@section('scripts-css')

@stop
