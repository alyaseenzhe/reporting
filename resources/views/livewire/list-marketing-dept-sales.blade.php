@section('title')
    15- تقرير الاقسام التسويقية
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
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" selected>الكل</option>
                            @if(in_array("3", $branches))
                                <option value="'0101'" >الاحساء</option>
                            @endif
                            @if(in_array("10", $branches))
                                <option value="'0102'" >جدة</option>
                            @endif
                            @if(in_array("7", $branches))
                                <option value="'0103'" >الرياض</option>
                            @endif
                            @if(in_array("13", $branches))
                                <option value="'0104'" >وادي الدواسر</option>
                            @endif
                            @if(in_array("4", $branches))
                                <option value="'0105'" >الجوف</option>
                            @endif
                            @if(in_array("6", $branches))
                                <option value="'0106'" >الدمام</option>
                            @endif
                            @if(in_array("5", $branches))
                                <option value="'0107'" >الخرج</option>
                            @endif
                            @if(in_array("12", $branches))
                                <option value="'0108'" >نجران</option>
                            @endif
                            @if(in_array("11", $branches))
                                <option value="'0109'" >حائل</option>
                            @endif
                            @if(in_array("9", $branches))
                                <option value="'0110'" >تبوك</option>
                            @endif
                            @if(in_array("8", $branches))
                                <option value="'0111'" >القصيم</option>
                            @endif
                            @if(in_array("505", $branches))
                                <option value="'0112'" >ساجر</option>
                            @endif
                            @if(in_array("3", $branches))
                                <option value="'0201'" >مزرعة الدالوة</option>
                                <option value="'0202'" >مزرعة الفضول</option>
                                <option value="'0203'" >مزرعة الدلم</option>
                                <option value="'0001'" >المركز الرئيسي</option>
                            @endif

                        </select>
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الإدارات والأقسام
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="marketing_type" name="marketing_type[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="marketing_all" selected>الكل</option>
                            @foreach($this->marketingTypeOptions() as $marketingTypeValue => $marketingTypeLabel)
                                <option value="{{ $marketingTypeValue }}">{{ $marketingTypeLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" min="2024-01-01" name="start_date"
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

    @if($show_msg)
        @php
            $current_month = 0;
            $previous_month = 0;
            $year_total = 0;
            $previous_year_total = 0;

        @endphp

{{--    @dd([$current_year, $previous_year])--}}
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="tbl-fixed overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black; color: black">
                    {{--                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">--}}
                    {{--                        <div class="text-xs">الفرع</div>--}}
                    {{--                    </th>--}}
                    <th colspan="2" style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">الوصف</div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>مبيعات الفترة</div>
                        </div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">مبيعات فترة (العام السابق)</div>
                    </th>
                    <th style="padding: 10px; border-left: 2px solid black;">
                        <div class="text-xs">نمو فترة%</div>
                    </th>
{{--                    <th style="padding: 10px;">--}}
{{--                        <div class="text-xs">مبيعات سنة</div>--}}
{{--                    </th>--}}
{{--                    <th style="padding: 10px;">--}}
{{--                        <div class="text-xs">نمو سنة({{$current_year}})</div>--}}
{{--                    </th>--}}
                    <th style="padding: 10px;">
{{--                        <div class="text-xs">نمو سنة % <br> من ({{$previous_year}}  {{$end_date}} الى  )</div>--}}
                        <div class="text-xs">نمو سنة %  </div>

                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @php
                    $mrkt_type = "*";
                @endphp
                @foreach($sap_results as $record)

                    @if($record["mrkt_type"] != $mrkt_type)
                            <?php $mrkt_type = $record["mrkt_type"]; ?>
                        <tr onclick="show_hide('{{$record["mrkt_type"]}}')" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                            <td style="padding: 10px; border-left: 2px solid black; font-weight: bold" class="whitespace-nowrap parent-{{ $record["mrkt_type"] }}">+</td>
                            <td style="padding: 10px; border-left: 2px solid black; font-weight: bold" class="whitespace-nowrap">
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
                                {{ number_format(floatval($record["CurrentMonth_total"]), 2) }}
                            </td>
                            <td style="color: #5f9ea0; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                                {{ number_format(floatval($record["PreviousMonth_total"]), 2) }}
                            </td>
                            <td  style="  @if( floatval($record["PreviousMonth_total"]) != 0 && number_format(((floatval($record["CurrentMonth_total"])-floatval($record["PreviousMonth_total"]))/floatval($record["PreviousMonth_total"]))*100, 2)< 0) color: #c71585; @else color: darkgreen; @endif padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                                {{ floatval($record["PreviousMonth_total"]) != 0 ? number_format(((floatval($record["CurrentMonth_total"])-floatval($record["PreviousMonth_total"]))/floatval($record["PreviousMonth_total"]))*100, 2) : 0 }}
                            </td>
{{--                            <td style="color: #00008b; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">--}}
{{--                                {{ number_format(floatval($record["CurrentYear_total"]), 2) }}--}}
{{--                            </td>--}}
{{--                            <td style="color: #00008b; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">--}}
{{--                                {{ number_format(floatval($record["PreviousYear_total"]), 2) }}--}}
{{--                            </td>--}}
                            <td  style="  @if($record["CurrentYear_total"] - $record["PreviousYear_total"] < 0) color: #c71585; @else color: darkgreen; @endif padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">

                            {{$record["PreviousYear_total"] != 0 ? number_format( ($record["CurrentYear_total"] - $record["PreviousYear_total"])/ $record["PreviousYear_total"] *100, 2) : 0 }}
                                @php $previous_year_total += floatval($record["PreviousYear_total"]) @endphp
                            </td>
                        </tr>
                    @endif

                    <tr style="border: 1px dashed black;" class="row-{{$record["mrkt_type"]}} hide">
                        <td style="padding: 10px; background-color: #e8f9e8; border-left: 2px solid black; font-weight: bold" class="whitespace-nowrap">
                            {{ $record['BranchRegistrationNumber'] }}
                        </td>
                        <td style="padding: 10px; background-color: #e8f9e8; border-left: 2px solid black; font-weight: bold" class="whitespace-nowrap">
                            {{ $record['BranchName'] }}
                        </td>
                        <td style="color:#0072ffb8; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ number_format(floatval($record["CurrentMonth"]), 2) }}
                            @php $current_month += floatval($record["CurrentMonth"]) @endphp
                        </td>
                        <td style="color: #5f9ea0; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ number_format(floatval($record["PreviousMonth"]), 2) }}
                            @php $previous_month += floatval($record["PreviousMonth"]) @endphp
                        </td>
                        <td style=" @if(floatval($record["PreviousMonth"]) != 0  && number_format(((floatval($record["CurrentMonth"])-floatval($record["PreviousMonth"]))/floatval($record["PreviousMonth"]))*100, 2) <0) color: #c71585; @else color:darkgreen; @endif padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ floatval($record["PreviousMonth"]) != 0 ? number_format(((floatval($record["CurrentMonth"])-floatval($record["PreviousMonth"]))/floatval($record["PreviousMonth"]))*100, 2) : 0 }}
                        </td>
{{--                        <td style="color: #00008b; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">--}}
{{--                            {{ number_format(floatval($record["CurrentYear"]), 2) }}--}}
{{--                            @php $year_total += floatval($record["CurrentYear"]) @endphp--}}

{{--                        </td>--}}
{{--                        <td style="color: #00008b; padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">--}}
{{--                            {{ number_format(floatval($record["PreviousYear"]), 2) }}--}}

{{--                        </td>--}}
                        <td  style="  @if($record["CurrentYear"] - $record["PreviousYear"] < 0) color: #c71585; @else color: darkgreen; @endif padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">
                            @php $year_total += floatval($record["CurrentYear"]) @endphp
                        {{ $record["PreviousYear"] != 0 ? number_format(($record["CurrentYear"] - $record["PreviousYear"]) / $record["PreviousYear"]* 100, 2): 0 }}


                        </td>

                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold; background-color: #fff8dc;">
                    <td colspan="2" style="padding: 10px;border-left: 2px solid black; color: black">المجموع</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($current_month , 2) }}</td>
                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($previous_month , 2) }}</td>
                    <td  style=" @if($current_month-$previous_month < 0) color: #c71585; @else color: darkgreen; @endif padding: 10px;border-left: 2px solid black; ">{{ $previous_month != 0 ? number_format((($current_month-$previous_month)/$previous_month)*100 , 2) : 0 }}</td>
{{--                    <td style="padding: 10px;border-left: 2px solid black; color: black">{{ number_format($year_total , 2) }}</td>--}}
{{--                    <td>{{$previous_year_total}}</td>--}}
                    <td  style="  @if($year_total - $previous_year_total < 0) color: #c71585; @else color: darkgreen; @endif padding: 10px; font-weight: bold; border-left: 2px solid black;" class="whitespace-nowrap">


                    {{$year_total != 0 ?  number_format(($year_total - $previous_year_total) / $previous_year_total *100, 2) : 0 }}
{{--                        <p> current year: {{$year_total}}</p>--}}
{{--                        <p> previous year: {{$previous_year_total}}</p>--}}
                    </td>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <script>

        $(document).ready(function () {

            $('#dept_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#marketing_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            var prev_depts = $('#dept_id').select2("val");
            var prev_marketing = $('#marketing_type').select2("val");

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

            $('#marketing_type').on('change', function () {
                var data = $('#marketing_type').select2("val");

                if (prev_marketing && prev_marketing.includes('marketing_all') == false && data.includes('marketing_all') == true && prev_marketing.length != data.length) {
                    $("#marketing_type option").prop('selected', false);
                    $("#marketing_type option[value='marketing_all']").prop('selected', true);

                    prev_marketing = $(this).val();
                    $('#marketing_type').change();
                }
                else {

                    if (prev_marketing && prev_marketing.length != data.length) {
                        $("#marketing_type option[value='marketing_all']").removeAttr('selected');
                        prev_marketing = $(this).val();

                        $("#marketing_type").change();
                    }
                }
            });

            $('#gen-report').on('click', function () {

                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var dept_id = $('#dept_id').select2("val");
                var marketing_type = $('#marketing_type').select2("val");

                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');


                if(start_date == '' || end_date == '' || start_date == null || end_date == null || dept_id == null || marketing_type == null) {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
                    $("#gen-report").html('<b>إنشاء تقرير</b>');
                }
                else if((new Date(start_date)) < (new Date('2024-01-01')) || (new Date(end_date)) < (new Date('2024-01-01'))) {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "التواريخ يجب ان تكون من 2024-01-01 واعلى حتى تتمكن من انشاء التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
                    $("#gen-report").html('<b>إنشاء تقرير</b>');
                }
                else {
                    Swal.fire({
                        title: 'الرجاء الإنتظار',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    });

                    Livewire.emit('create-report', start_date, end_date, dept_id, marketing_type);
                }
            })

        });


        Livewire.on('finished', () => {
            swal.close();
        })

        function show_hide(acc) {
            if ($('.row-'+acc).hasClass('hide')) {
                $('.row-'+acc).removeClass('hide');
                $('.parent-'+acc).text('-');
            } else {
                $('.row-'+acc).addClass('hide');
                $('.parent-'+acc).text('+');
            }

        }
    </script>
@stop

@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .hide {
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
    </style>
@stop
