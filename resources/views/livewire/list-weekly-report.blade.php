<div>
    {{-- Stop trying to control. --}}
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">التقرير الإسبوعي</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="area_id" name="area_id" wire:model="area_id"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="-1">الرجاء اختيار الفرع</option>
                        @if(in_array("3", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="01">فرع الاحساء</option>
                        @endif
                        @if(in_array("10", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="02">فرع جدة</option>
                        @endif
                        @if(in_array("7", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="03">فرع الرياض</option>
                        @endif
                        @if(in_array("13", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="04">فرع وادي الدواسر</option>
                        @endif
                        @if(in_array("4", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="05">فرع الجوف</option>
                        @endif
                        @if(in_array("6", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="06">فرع الدمام</option>
                        @endif
                        @if(in_array("5", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="07">فرع الخرج</option>
                        @endif
                        @if(in_array("12", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="08">فرع نجران</option>
                        @endif
                        @if(in_array("11", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="09">فرع حائل</option>
                        @endif
                        @if(in_array("9", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="10">فرع تبوك</option>
                        @endif
                        @if(in_array("8", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="11">فرع القصيم</option>
                        @endif
                        @if(in_array("505", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="12">فرع ساجر</option>
                        @endif
                    </select>
                    @error('area_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date" wire:model="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date" wire:model="end_date"
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
                <div wire:ignore class="mt-8 text-center w-full">
                    <button wire:click.prevent="sendReport" wire:loading.attr="disabled"
                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="sendReport">
                            <span></span>
                            <span>ارسال</span>
                        </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="sendReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th class="whitespace-nowrap">
                        <div class="text-sm">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-sm">الاسم</div>
                    </th>
                    <th>
                        <div class="text-sm"># عملاء مبيعات</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-sm"># زيارات</div>
                    </th>
                    <th>
                        <div class="text-sm">$ مستحق</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-sm">%المستحق</div>
                    </th>
                    <th>
                        <div class="text-sm">تحصيل</div>
                    </th>
                    <th>
                        <div class="text-sm">م نقدية</div>
                    </th>
                    <th>
                        <div class="text-sm">م آجلة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-sm">مجموع مبيعات</div>
                    </th>
                    <th>
                        <div class="text-sm">#بذور</div>
                    </th>
                    <th>
                        <div class="text-sm">$بذور</div>
                    </th>
                    <th>
                        <div class="text-sm">#مبيدات</div>
                    </th>
                    <th>
                        <div class="text-sm">$مبيدات</div>
                    </th>
                    <th>
                        <div class="text-sm">#اسمدة</div>
                    </th>
                    <th>
                        <div class="text-sm">$اسمدة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-sm">#اخرى</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-sm">$اخرى</div>
                    </th>
                    <th>
                        <div class="text-sm">مميز 1</div>
                    </th>
                    <th>
                        <div class="text-sm">مميز 2</div>
                    </th>
                    <th>
                        <div class="text-sm">مميز 0</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                $total_cust = 0;
                $total_visit = 0;
                $total_due_amount = 0;
                $total_post_amount = 0;
                $total_collected = 0;
                $total_cash = 0;
                $total_postponed = 0;
                $total_grand_total = 0;
                $total_bathoor = 0;
                $total_mobedat = 0;
                $total_asmedah = 0;
                $total_other = 0;
                $total_sp0 = 0;
                $total_sp1 = 0;
                $total_sp2 = 0;
                @endphp
                @foreach($emp_total as $key => $record)
                    @if($key != "")
                        @if($record['postponed_amount'] != '0' || $record['postponed_due_amount'] != '0' || $record['collected'] != '0' || $record['cash'] != '0' || $record['postponed_sales'] != '0' || $record['bathoor'] != '0' || $record['mobedat'] != '0' || $record['asmedah'] != '0' || $record['other'] != '0' || $record['speciality0'] != '0' || $record['speciality1'] != '0' || $record['speciality2'] != '0')
                            <tr>
                                <td style="background-color: #e8f9e8;" class="whitespace-nowrap">
                                    {{$key}}
                                </td>
                                <td style="background-color: #e8f9e8; border-left: 2px solid black;" class="whitespace-nowrap">
                                    {{$emp_codes[$key]}}
                                </td>
                                <td>
                                    {{ array_key_exists($key, $customer_purchased) ?  $customer_purchased[$key] : "0"}}
                                    @php $total_cust +=  array_key_exists($key, $customer_purchased) ?  floatval($customer_purchased[$key]) : 0; @endphp
                                </td>
                                <td style="border-left: 2px solid black;">
                                    {{ array_key_exists($key, $visits) ?  $visits[$key] : "0"}}
                                    @php $total_visit +=  array_key_exists($key, $visits) ?  floatval($visits[$key]) : 0 @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['postponed_due_amount']/1000)) }}
                                    @php $total_due_amount +=  floatval($record['postponed_due_amount']) @endphp
                                    @php $total_post_amount +=  floatval($record['postponed_amount']) @endphp
                                </td>
                                <td style="border-left: 2px solid black;">
                                    {{ number_format($record['postponed_amount']) == "0" ? 0: number_format((floatval($record['postponed_due_amount'])/floatval($record['postponed_amount'])*100)) }}
                                </td>
                                <td>
                                    {{ number_format(round($record['collected']/1000)) }}
                                    @php $total_collected +=  floatval($record['collected']) @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['cash']/1000)) }}
                                    @php $total_cash +=  floatval($record['cash']) @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['postponed_sales']/1000)) }}
                                    @php $total_postponed +=  floatval($record['postponed_sales']) @endphp
                                </td>
                                <td style="border-left: 2px solid black;">
                                    {{ number_format(round((floatval($record['cash'])+floatval($record['postponed_sales']))/1000)) }}
                                    @php $total_grand_total +=  floatval($record['cash'])+floatval($record['postponed_sales'])  @endphp
                                </td>
                                <td>
                                    {{ array_key_exists($key, $category_qty) ?  (array_key_exists("bathoor", $category_qty[$key]) ? $category_qty[$key]['bathoor'] : 0) : "0"}}
                                </td>
                                <td>
                                    {{ number_format(round($record['bathoor']/1000)) }}
                                    @php $total_bathoor +=  floatval($record['bathoor']) @endphp
                                </td>
                                <td>

                                    {{ array_key_exists($key, $category_qty) ?  (array_key_exists("mobedat", $category_qty[$key]) ? $category_qty[$key]['mobedat'] : 0) : "0"}}
                                </td>
                                <td>
                                    {{ number_format(round($record['mobedat']/1000)) }}
                                    @php $total_mobedat +=  floatval($record['mobedat']) @endphp
                                </td>
                                <td>
                                    {{ array_key_exists($key, $category_qty) ?  (array_key_exists("asmedah", $category_qty[$key]) ? $category_qty[$key]['asmedah'] : 0) : "0"}}
                                </td>
                                <td>
                                    {{ number_format(round($record['asmedah']/1000)) }}
                                    @php $total_asmedah +=  floatval($record['asmedah']) @endphp
                                </td>
                                <td>
                                    {{ array_key_exists($key, $category_qty) ?  (array_key_exists("other", $category_qty[$key]) ? $category_qty[$key]['other'] : 0) : "0"}}
                                </td>
                                <td style="border-left: 2px solid black;">
                                    {{ number_format(round($record['other']/1000)) }}
                                    @php $total_other +=  floatval($record['other']) @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['speciality1']/1000))}}
                                    @php $total_sp1 +=  floatval($record['speciality1']) @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['speciality2']/1000))}}
                                    @php $total_sp2 +=  floatval($record['speciality2']) @endphp
                                </td>
                                <td>
                                    {{ number_format(round($record['speciality0']/1000))}}
                                    @php $total_sp0 +=  floatval($record['speciality0']) @endphp
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                </tbody>
                <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold; background-color: #fff8dc;">
                    <td style="border-left: 2px solid black;" colspan="2">آداء الفرع كامل</td>
                    <td>{{ number_format($total_cust) }}</td>
                    <td style="border-left: 2px solid black;">{{ number_format($total_visit) }}</td>
                    <td>{{ number_format(round($total_due_amount/1000)) }}</td>
{{--                    <td style="border-left: 2px solid black;">{{ number_format(round(($total_due_amount/$total_post_amount)*100)) }}</td>--}}
                    <td style="border-left: 2px solid black;">{{ number_format($total_post_amount) != '0' ? number_format(($total_due_amount/$total_post_amount)*100) : "0" }}</td>
                    <td>{{ number_format(round($total_collected/1000)) }}</td>
                    <td>{{ number_format(round($total_cash/1000)) }}</td>
                    <td>{{ number_format(round($total_postponed/1000)) }}</td>
                    <td style="border-left: 2px solid black;">{{ number_format(round($total_grand_total/1000)) }}</td>
                    <td>{{ number_format($category_qty_total['bathoor']) }}</td>
                    <td>{{ number_format(round($total_bathoor/1000)) }}</td>
                    <td>{{ number_format($category_qty_total['mobedat']) }}</td>
                    <td>{{ number_format(round($total_mobedat/1000)) }}</td>
                    <td>{{ number_format($category_qty_total['asmedah']) }}</td>
                    <td>{{ number_format(round($total_asmedah/1000)) }}</td>
                    <td>{{ number_format($category_qty_total['other']) }}</td>
                    <td style="border-left: 2px solid black;">{{ number_format(round($total_other/1000)) }}</td>
                    <td>{{ number_format(round($total_sp1/1000)) }}</td>
                    <td>{{ number_format(round($total_sp2/1000)) }}</td>
                    <td>{{ number_format(round($total_sp0/1000)) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px; margin-right: 22px;">
            <span style="font-weight: bold; margin-bottom: 20px">ملاحظات:</span>
            <ul style="list-style-type: disc; text-decoration: underline">
                <li style="margin-top: 10px;">جميع أرقام المبيعات الموجودة في الجدول غير شاملة الضريبة.</li>
                <li>جميع الأرقام المالية الموجودة في الجدول هي بالآلاف.</li>
            </ul>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

    <script>
        Livewire.on('show-container', () => {

            div = document.getElementById("report-btn");
            div_title = document.getElementById("report_title");
            area = document.getElementById("area_id");
            start_date = document.getElementById("start_date");
            end_date = document.getElementById("end_date");

            div.classList.remove("hide");

        });
    </script>
@stop
@section('css-scripts')
    <style>
        table td {
            border: solid 1px black;
            padding: 0.5rem;
        }

        table th{
            border: solid 1px black;
            padding: 0.5rem;
        }

        .hide {
            display: none;
        }

        #report-logo {
            display: none;
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
