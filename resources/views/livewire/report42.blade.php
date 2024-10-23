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
                            <option value="dept_all" @if(in_array("dept_all", $dept_id)) selected @endif>الكل</option>
                            @foreach($branches as $branch)
                                @if($branch == "3")
                                    <option value="3" @if(in_array("3", $dept_id)) selected @endif>الاحساء</option>
                                @elseif($branch == "10")
                                    <option value="10" @if(in_array("10", $dept_id)) selected @endif>جدة</option>
                                @elseif($branch == "7")
                                    <option value="7" @if(in_array("7", $dept_id)) selected @endif>الرياض</option>
                                @elseif($branch == "13")
                                    <option value="13" @if(in_array("13", $dept_id)) selected @endif>وادي الدواسر</option>
                                @elseif($branch == "4")
                                    <option value="4" @if(in_array("4", $dept_id)) selected @endif>الجوف</option>
                                @elseif($branch == "6")
                                    <option value="6" @if(in_array("6", $dept_id)) selected @endif>الدمام</option>
                                @elseif($branch == "5")
                                    <option value="5" @if(in_array("5", $dept_id)) selected @endif>الخرج</option>
                                @elseif($branch == "12")
                                    <option value="12" @if(in_array("12", $dept_id)) selected @endif>نجران</option>
                                @elseif($branch == "11")
                                    <option value="11" @if(in_array("11", $dept_id)) selected @endif>حائل</option>
                                @elseif($branch == "9")
                                    <option value="9" @if(in_array("9", $dept_id)) selected @endif>تبوك</option>
                                @elseif($branch == "8")
                                    <option value="8" @if(in_array("8", $dept_id)) selected @endif>القصيم</option>
                                @elseif($branch == "505")
                                    <option value="505" @if(in_array("505", $dept_id)) selected @endif>ساجر</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الشهر
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="month" onkeydown="return false" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
{{--                <div wire:ignore class="w-full">--}}
{{--                    <label class="block font-bold mb-2">تاريخ النهاية--}}
{{--                        <span class="text-red-500">*</span>--}}
{{--                    </label>--}}
{{--                    <input id="end_date" type="date" onkeydown="return false" name="end_date"--}}
{{--                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                           style="@error('item_id') border: solid 1px #fda4af; @enderror">--}}
{{--                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
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
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">اسم الفرع</div>
                    </th>
                    <th colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">خلال الفترة</div>
                    </th>
                    <th colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">خلال سنة</div>
                    </th>
                    <th colspan="4" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">الآجل</div>
                    </th>
                    <th colspan="3" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">مخزون</div>
                    </th>
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">صافي الربح لفترة</div>
                    </th>
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">صافي الربح لسنة</div>
                    </th>
                    <th rowspan="3" style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-xs">مصاريف تشغيلية</div>
                    </th>
                    @endif
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
                        @if ($year == 2024)
                            <div class="text-xs">قيمة المستحق</div>
                        @else
                            <div class="text-xs">نسبة الآجل المستحق</div>
                        @endif
                    </th>
{{--                    <th rowspan="2" style="border-left: 2px solid black;" class="border p-2">--}}
{{--                        <div class="text-xs">وعاء الآجل غير نقدي</div>--}}
{{--                    </th>--}}
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
                        <div class="text-xs">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">الشهر</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">سنة</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-xs">% نمو</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;

                    $month_sp1 = 0;
                    $month_old_sp1 = 0;
                    $month_sp2 = 0;
                    $month_old_sp2 = 0;
                    $month_total = 0;
                    $month_old_total = 0;

                    $year_sp1 = 0;
                    $year_old_sp1 = 0;
                    $year_sp2 = 0;
                    $year_old_sp2 = 0;
                    $year_total = 0;
                    $year_old_total = 0;

                    $post_total = 0;
                    $post_percent_post = 0;
                    $post_percent_due = 0;
                    $post_value_due = 0;

                    $stock_value = 0;
                    $stock_year_sales = 0;
                    $stock_tadweer = 0;

                    $profit_period = 0;
                    $profit_year = 0;
                    $ope_expenses = 0;

                @endphp
{{--                @if ($start_date > '2025-12-31' && $end_date > '2025-12-31')--}}
                @if ($year >= 2026)
                    @foreach($sap_results as $record)
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["BPLName"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S1 Sales"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S1 Sales PY"]) != 0 ? ((floatval($record["S1 Sales"]) - floatval($record["S1 Sales PY"])) / floatval($record["S1 Sales PY"])) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S2 Sales"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S2 Sales PY"]) != 0 ? ((floatval($record["S2 Sales"]) - floatval($record["S2 Sales PY"])) / floatval($record["S2 Sales PY"])) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["S1 Sales"])/1000)+(floatval($record["S2 Sales"])/1000))}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ (floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"])) != 0 ? (((floatval($record["S1 Sales"])+floatval($record["S2 Sales"]))-(floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"]))) / (floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"]))) : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S1 Sales Year"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S1 Sales Year PY"]) != 0 ? ((floatval($record["S1 Sales Year"]) - floatval($record["S1 Sales Year PY"])) / floatval($record["S1 Sales Year PY"])) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S2 Sales Year"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S2 Sales Year PY"]) != 0 ? ((floatval($record["S2 Sales Year"]) - floatval($record["S2 Sales Year PY"])) / floatval($record["S2 Sales Year PY"])) * 100 : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["S1 Sales Year"])/1000)+(floatval($record["S2 Sales Year"])/1000))}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ (floatval($record["S1 Sales Year PY"])+floatval($record["S2 Sales Year PY"])) != 0 ? (((floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"]))-(floatval($record["S1 Sales Year PY"])+floatval($record["S2 Sales Year PY"]))) / (floatval($record["S1 Sales Year PY"])+floatval($record["S2 Sales Year PY"]))) : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["Outstanding Receivables"])/1000, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])) != 0 ? number_format(((floatval($record["Outstanding Receivables"]))/(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])))*100) : 0}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["Outstanding Receivables Over 120"]) / floatval($record["Outstanding Receivables"])) * 100)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(($record["Clean Receivables"] / $record["Outstanding Receivables"]) * 100, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                N/A
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Stock Value']) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ (floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])) != 0 ? number_format(((floatval($record["Stock Value"]))/(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])))*100) : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ floatval($record["Stock Value"]) != 0 ? number_format((floatval($record["COGS"])/floatval($record["Stock Value"]))*100, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['NPAT Period']/1000, 2) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['NPAT Annual']/1000, 2) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Operating Expenses']/1000, 2) }}
                            </td>
                            @endif
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format(((((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/((floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease))+(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease))))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/(floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format(((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->DebitCustomers))/1000, 2)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((100*((floatval($record->DebitCustomers)))/1000)/(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(100*(floatval($record->DueBalance)/floatval($record->DebitCustomers)))}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format(100*((floatval($record->DebitCustomers))/1000)/((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000), 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format(100*(((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000)/((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)))) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->YearCOGS)/((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000))/1000, 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->TotalIncome) - floatval($record->TotalExpenses))/1000, 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->YearTotalIncome) - floatval($record->YearTotalExpenses))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->TotalExpenses) - floatval($record->COGS))/1000, 2) }}--}}
                            {{--                        </td>--}}
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
{{--                @elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31')--}}
                @elseif ($year <= 2023)
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
{{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                                {{ number_format((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000) }}--}}
{{--                            </td>--}}
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format(100*(((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000)/((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)))) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format((floatval($record->YearCOGS)/((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000))/1000, 2) }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format((floatval($record->TotalIncome) - floatval($record->TotalExpenses))/1000, 2) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format((floatval($record->YearTotalIncome) - floatval($record->YearTotalExpenses))/1000) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format((floatval($record->TotalExpenses) - floatval($record->COGS))/1000, 2) }}
                            </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
                @elseif ($year == 2024)
                    @foreach($merged as $record)
                        @if($record[0]->Code != '0155')
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
    {{--                                {{$record[1]["BPLName"]}}--}}
                                    {{$record[0]->arabic_name}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $month_sp1 += (floatval($record[1]["S1 Sales"])/1000); @endphp
                                    {{number_format((floatval($record[1]["S1 Sales"])/1000))}}
                                </td>
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{number_format((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)}}--}}
                                {{--                                </td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    @php $month_old_sp1 += (floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)); @endphp
                                    {{--                                    {{ (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) != 0 ? number_format(((floatval($record[1]["S1 Sales"]) - (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))/(floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))*100) : 0 }}--}}
                                    {{ number_format((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((floatval($record[1]["S1 Sales"]) - (floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))) / (floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))) * 100 : 0) }}
                                    {{--                                    {{ number_format((((floatval($record[1]["S1 Sales"]))/(floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease)))-1)*100) }}--}}
                                    {{--                                    {{ number_format(((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) != 0 ? (floatval($record[1]["S1 Sales"])/1000) - ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) / ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)*100 : 0) }}--}}
                                    {{--                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                    {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $month_sp2 += (floatval($record[1]["S2 Sales"])/1000); @endphp
                                    {{number_format((floatval($record[1]["S2 Sales"])/1000))}}
                                </td>
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{number_format((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/1000)}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2">--}}
                                {{--                                    {{ number_format(((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales PY"]) != 0 ? ((floatval($record[1]["S2 Sales"]) - floatval($record[1]["S2 Sales PY"])) / floatval($record[1]["S2 Sales PY"])) * 100 : 0)) }}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2">--}}
                                {{--                                    {{ (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)) != 0 ? number_format(((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/(floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))*100) : 0 }}--}}
                                {{--                                </td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    @php $month_old_sp2 += (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)); @endphp
                                    {{--                                    {{ (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) != 0 ? number_format(((floatval($record[1]["S1 Sales"]) - (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))/(floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))*100) : 0 }}--}}
                                    {{ number_format((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) / (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) * 100 : 0) }}
                                    {{--                                    {{ number_format((((floatval($record[1]["S1 Sales"]))/(floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease)))-1)*100) }}--}}
                                    {{--                                    {{ number_format(((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) != 0 ? (floatval($record[1]["S1 Sales"])/1000) - ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) / ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)*100 : 0) }}--}}
                                    {{--                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                    {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $month_total += (floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000);  @endphp
                                    {{number_format(((floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    @php $month_old_total += ((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))); @endphp
                                    {{ number_format(((((floatval($record[1]["S1 Sales"]))+(floatval($record[1]["S2 Sales"])))-((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))/((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))*100) }}
                                    {{--                                    {{ number_format((((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) + (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"])) != 0 ? (((floatval($record[1]["S1 Sales"])+floatval($record[1]["S2 Sales"]))-(floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) / (floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) : 0)) }}--}}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{--                            {{((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000)}}--}}
                                    @php $year_sp1 += ((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000); @endphp
                                    {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}
                                </td>
    {{--                            <td>{{((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))}}</td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    @php $year_old_sp1 += ((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))); @endphp
                                    {{ number_format(((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)))+(floatval($record[1]["S1 Sales Year"])))-((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))))*100) }}

                                    {{--                                    {{ (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) / (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) * 100 : 0 }}--}}

                                    {{--                                    {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}--}}
                                    {{--                                    {{ number_format(((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/(floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S1 Sales Year PY"]) != 0 ? ((floatval($record[1]["S1 Sales Year"]) - floatval($record[1]["S1 Sales Year PY"])) / floatval($record[1]["S1 Sales Year PY"])) * 100 : 0)) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $year_sp2 += ((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/1000)+(floatval($record[1]["S2 Sales Year"])/1000); @endphp

                                    {{number_format(((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/1000)+(floatval($record[1]["S2 Sales Year"])/1000))}}
                                </td>
    {{--                            <td>{{ ((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) }}</td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    @php $year_old_sp2 += ((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))); @endphp

                                    {{ number_format(((((((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))+(floatval($record[1]["S2 Sales Year"])))-((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))))*100) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $year_total += (((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)+((floatval($record[1]["S1 Sales Year"])/1000)+(floatval($record[1]["S2 Sales Year"])/1000)); @endphp
                                    {{number_format((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)+((floatval($record[1]["S1 Sales Year"])/1000)+(floatval($record[1]["S2 Sales Year"])/1000)))}}
                                </td>
    {{--                            <td style="border-left: 2px solid black;" class="border p-2">--}}
    {{--                                {{ (floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)) }}--}}
    {{--                                kkk{{ ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))+((floatval($record[1]["S1 Sales Year"]))+(floatval($record[1]["S2 Sales Year"]))))-(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))-1)*100}}--}}


    {{--                                --}}{{--                                    {{ number_format((((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"])) != 0 ? (((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"]))-(floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) / (floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) : 0)) }}--}}
    {{--                            </td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $year_old_total += ((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)));  @endphp
                                    {{ number_format(((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))+((floatval($record[1]["S1 Sales Year"]))+(floatval($record[1]["S2 Sales Year"]))))-((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))*100) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $post_total += (floatval($record[1]["Outstanding Receivables"])/1000); @endphp
                                    {{number_format((floatval($record[1]["Outstanding Receivables"])/1000), 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $post_percent_post += (floatval(($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval(($record[1]["S2 Sales Year"]))+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))); @endphp
                                    {{ number_format(((floatval(($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+floatval(($record[1]["S2 Sales Year"]))+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))) != 0 ? number_format(((floatval($record[1]["Outstanding Receivables"]))/(floatval(($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval(($record[1]["S2 Sales Year"]))+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))))*100)) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $post_percent_due +=  floatval($record[1]["Outstanding Receivables Over 120"]);  @endphp
                                    {{number_format(((floatval($record[1]["Outstanding Receivables"]) != 0? (floatval($record[1]["Outstanding Receivables Over 120"]) / floatval($record[1]["Outstanding Receivables"])) : 0) * 100))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $post_value_due += ((floatval($record[1]["Outstanding Receivables Over 120"]) )/1000);  @endphp
                                    {{number_format(((floatval($record[1]["Outstanding Receivables Over 120"]) )/1000))}}
                                </td>
    {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
    {{--                                {{ number_format((($record[1]["Outstanding Receivables"] != 0? ($record[1]["Clean Receivables"] / $record[1]["Outstanding Receivables"]) : 0) * 100), 2) }}--}}
    {{--                            </td>--}}
    {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
    {{--                                {{ number_format((0)) }}--}}
    {{--                            </td>--}}
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $stock_value += ($record[1]['Stock Value'])/1000; @endphp
                                    {{ number_format(($record[1]['Stock Value'])/1000) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
    {{--                                {{ number_format((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))) != 0? (100*(((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000)/((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)))) : 0)+((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}--}}
                                    @php $stock_year_sales += ((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))); @endphp
                                    {{ ((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))))*100) : 0 }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @php $stock_tadweer += floatval($record[1]["COGS"]);  @endphp
                                    {{ (floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0) }}
{{--                                    {{ number_format((floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}--}}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        @if(\Carbon\Carbon::parse($start_date)->format('Y-m') == '2024-08')
                                            @php $profit_period += ($profit_loss['2024-08'][$record[1]["BPLId"]][0]); @endphp
                                            {{ number_format(($profit_loss['2024-08'][$record[1]["BPLId"]][0])) }}
                                        @else
                                            @php $profit_period += ($record[1]['NPAT Period']/1000); @endphp
                                            {{ number_format(($record[1]['NPAT Period']/1000), 2) }}
                                        @endif
                                    </td>
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        @if(\Carbon\Carbon::parse($start_date)->format('Y-m') == '2024-08')
                                            @php $profit_year += ($profit_loss['2024-08'][$record[1]["BPLId"]][1]);  @endphp
                                            {{ number_format(($profit_loss['2024-08'][$record[1]["BPLId"]][1])) }}
                                        @else
                                            @php $profit_year += (((floatval($record[0]->YearTotalIncome) - floatval($record[0]->YearTotalExpenses)))+($record[1]['NPAT Annual']))/1000;  @endphp
                                            {{ number_format((((floatval($record[0]->YearTotalIncome) - floatval($record[0]->YearTotalExpenses)))+($record[1]['NPAT Annual']))/1000) }}
                                        @endif
                                    </td>
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        @if(\Carbon\Carbon::parse($start_date)->format('Y-m') == '2024-08')
                                            @php $ope_expenses += ($profit_loss['2024-08'][$record[1]["BPLId"]][2]); @endphp
                                            {{ number_format(($profit_loss['2024-08'][$record[1]["BPLId"]][2])) }}
                                        @else
                                            @php $ope_expenses += ($record[1]['Operating Expenses']/1000); @endphp
                                            {{ number_format(($record[1]['Operating Expenses']/1000), 2) }}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endif
                    @endforeach
                @elseif ($year == 2025)
                    @foreach($merged as $record)
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["BPLName"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S1 Sales"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S1 Sales PY"]) != 0 ? ((floatval($record["S1 Sales"]) - floatval($record["S1 Sales PY"])) / floatval($record["S1 Sales PY"])) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S2 Sales"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ floatval($record["S2 Sales PY"]) != 0 ? ((floatval($record["S2 Sales"]) - floatval($record["S2 Sales PY"])) / floatval($record["S2 Sales PY"])) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["S1 Sales"])/1000)+(floatval($record["S2 Sales"])/1000))}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ (floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"])) != 0 ? (((floatval($record["S1 Sales"])+floatval($record["S2 Sales"]))-(floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"]))) / (floatval($record["S1 Sales PY"])+floatval($record["S2 Sales PY"]))) : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S1 Sales Year"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ (floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease)) != 0 ? ((floatval($record["S1 Sales Year"]) - (floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))) / (floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))) * 100 : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["S2 Sales Year"])/1000)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ (floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)) != 0 ? ((floatval($record["S2 Sales Year"]) - (floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))) / (floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))) * 100 : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["S1 Sales Year"])/1000)+(floatval($record["S2 Sales Year"])/1000))}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{ ((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))) != 0 ? (((floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"]))-((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)))) / ((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)))) : 0 }}
                            </td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(floatval($record["Outstanding Receivables"])/1000, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])) != 0 ? number_format(((floatval($record["Outstanding Receivables"]))/(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])))*100) : 0}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format((floatval($record["Outstanding Receivables Over 120"]) / floatval($record["Outstanding Receivables"])) * 100)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{number_format(($record["Clean Receivables"] / $record["Outstanding Receivables"]) * 100, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                N/A
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Stock Value']) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ (floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])) != 0 ? number_format(((floatval($record["Stock Value"]))/(floatval($record["S1 Sales Year"])+floatval($record["S2 Sales Year"])))*100) : 0 }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ floatval($record["Stock Value"]) != 0 ? number_format((floatval($record["COGS"])/floatval($record["Stock Value"]))*100, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format($record['NPAT Period']/1000, 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format($record['NPAT Annual']/1000, 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format($record['Operating Expenses']/1000, 2) }}
                                </td>
                            @endif
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP2Sales) - floatval($record->SP2SalesReturn))/(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format(((((floatval($record->SP1Sales) - floatval($record->SP1SalesReturn)) + (floatval($record->SP2Sales) - floatval($record->SP2SalesReturn)))/((floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease))+(floatval($record->SP2SalesIncrease) - floatval($record->SP2SalesReturnIncrease))))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn))/(floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format((((floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn))/(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease)))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                            {{--                            {{ number_format(((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/((floatval($record->SP1YearSalesIncrease) - floatval($record->SP1YearSalesReturnIncrease))+(floatval($record->SP2YearSalesIncrease) - floatval($record->SP2YearSalesReturnIncrease))))-1)*100) }}--}}
                            {{--                        </td>--}}

                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format((floatval($record->DebitCustomers))/1000, 2)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((100*((floatval($record->DebitCustomers)))/1000)/(((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(100*(floatval($record->DueBalance)/floatval($record->DebitCustomers)))}}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format(100*((floatval($record->DebitCustomers))/1000)/((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000), 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->SPYearSalesNotCash) - floatval($record->SPYearSalesReturnNotCash))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format(100*(((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000)/((((floatval($record->SP1YearSales) - floatval($record->SP1YearSalesReturn)) + (floatval($record->SP2YearSales) - floatval($record->SP2YearSalesReturn)))/1000)))) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->YearCOGS)/((floatval($record->InpuCost) - floatval($record->OutPutCost))/1000))/1000, 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->TotalIncome) - floatval($record->TotalExpenses))/1000, 2) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->YearTotalIncome) - floatval($record->YearTotalExpenses))/1000) }}--}}
                            {{--                        </td>--}}
                            {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{ number_format((floatval($record->TotalExpenses) - floatval($record->COGS))/1000, 2) }}--}}
                            {{--                        </td>--}}
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
                @endif
                </tbody>

                @if ($year == 2024)
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #ffeacd; font-weight: bold;" class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        المجموع
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
{{--                        {{number_format(floatval($month_sp1))}}--}}
                        {{ number_format($month_sp1) }}
                    </td>
                    {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                                    {{number_format((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)}}--}}
                    {{--                                </td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2">

{{--                        {{ $month_old_sp1 }}--}}
                        {{--                                    {{ (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) != 0 ? number_format(((floatval($record[1]["S1 Sales"]) - (floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))/(floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)))*100) : 0 }}--}}
                        {{ number_format( $month_old_sp1 != 0 ? ((floatval($month_sp1) - (floatval($month_old_sp1/1000))) / (floatval($month_old_sp1/1000))) * 100 : 0) }}
                        {{--                                    {{ number_format((((floatval($record[1]["S1 Sales"]))/(floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease)))-1)*100) }}--}}
                        {{--                                    {{ number_format(((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) != 0 ? (floatval($record[1]["S1 Sales"])/1000) - ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) / ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)*100 : 0) }}--}}
                        {{--                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                        {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                    </td>

                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ number_format($month_sp2) }}
{{--                        @php $month_sp2 += (floatval($record[1]["S2 Sales"])/1000); @endphp--}}
{{--                        {{number_format((floatval($record[1]["S2 Sales"])/1000))}}--}}
                    </td>
                    {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                                    {{number_format((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/1000)}}--}}
                    {{--                                </td>--}}
                    {{--                                <td style="border-left: 2px solid black;" class="border p-2">--}}
                    {{--                                    {{ number_format(((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales PY"]) != 0 ? ((floatval($record[1]["S2 Sales"]) - floatval($record[1]["S2 Sales PY"])) / floatval($record[1]["S2 Sales PY"])) * 100 : 0)) }}--}}
                    {{--                                </td>--}}
                    {{--                                <td style="border-left: 2px solid black;" class="border p-2">--}}
                    {{--                                    {{ (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)) != 0 ? number_format(((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/(floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))*100) : 0 }}--}}
                    {{--                                </td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2">
                        {{ number_format( $month_old_sp2 != 0 ? ((floatval($month_sp2) - (floatval($month_old_sp2/1000))) / (floatval($month_old_sp2/1000))) * 100 : 0) }}
{{--                        @php $month_old_sp2 += (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)); @endphp--}}
{{--                        --}}
{{--                        {{ number_format((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) / (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) * 100 : 0) }}--}}

                        {{--                                    {{ number_format((((floatval($record[1]["S1 Sales"]))/(floatval($record->SP1SalesIncrease) - floatval($record->SP1SalesReturnIncrease)))-1)*100) }}--}}
                        {{--                                    {{ number_format(((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) != 0 ? (floatval($record[1]["S1 Sales"])/1000) - ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000) / ((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)*100 : 0) }}--}}
                        {{--                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                        {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                    </td>

                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
{{--                        @php $month_total += (floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000);  @endphp--}}
                        {{number_format($month_total)}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2">
{{--                        @php $month_old_total += ((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))); @endphp--}}
                        {{ number_format(((((floatval($month_sp1))+(floatval($month_sp2)))-($month_old_total/1000))/($month_old_total/1000))*100) }}

                        {{--                                    {{ number_format((((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) + (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"])) != 0 ? (((floatval($record[1]["S1 Sales"])+floatval($record[1]["S2 Sales"]))-(floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) / (floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) : 0)) }}--}}
                    </td>

                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">

                        {{ number_format($year_sp1) }}
{{--                        {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}--}}
                    </td>
                    {{--                            <td>{{((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))}}</td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2">
                        {{ number_format( $year_old_sp1 != 0 ? ((floatval($year_sp1) - (floatval($year_old_sp1/1000))) / (floatval($year_old_sp1/1000))) * 100 : 0) }}
{{--                        {{ number_format(((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)))+(floatval($record[1]["S1 Sales Year"])))-((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))))*100) }}--}}

                        {{--                                    {{ (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((floatval($record[1]["S2 Sales"]) - (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) / (floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) * 100 : 0 }}--}}

                        {{--                                    {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}--}}
                        {{--                                    {{ number_format(((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/(floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S1 Sales Year PY"]) != 0 ? ((floatval($record[1]["S1 Sales Year"]) - floatval($record[1]["S1 Sales Year PY"])) / floatval($record[1]["S1 Sales Year PY"])) * 100 : 0)) }}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ number_format($year_sp2) }}
{{--                        {{number_format(((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/1000)+(floatval($record[1]["S2 Sales Year"])/1000))}}--}}
                    </td>
                    {{--                            <td>{{ ((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) }}</td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2">
                        {{ number_format( $year_old_sp2 != 0 ? ((floatval($year_sp2) - (floatval($year_old_sp2/1000))) / (floatval($year_old_sp2/1000))) * 100 : 0) }}
{{--                        {{ number_format(((((((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))+(floatval($record[1]["S2 Sales Year"])))-((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))))*100) }}--}}
                    </td>

                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{number_format($year_total)}}
{{--                        {{number_format((((floatval($year_sp1)) + (floatval($year_old_sp2)))/1000)+(($year_sp1)+($year_sp2)))}}--}}
                    </td>
                    {{--                            <td style="border-left: 2px solid black;" class="border p-2">--}}
                    {{--                                {{ (floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)) }}--}}
                    {{--                                kkk{{ ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))+((floatval($record[1]["S1 Sales Year"]))+(floatval($record[1]["S2 Sales Year"]))))-(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))-1)*100}}--}}


                    {{--                                --}}{{--                                    {{ number_format((((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"])) != 0 ? (((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"]))-(floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) / (floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) : 0)) }}--}}
                    {{--                            </td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ number_format(((($year_total)-($year_old_total/1000))/($year_old_total/1000))*100) }}
{{--                        {{ number_format(((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))+((floatval($record[1]["S1 Sales Year"]))+(floatval($record[1]["S2 Sales Year"]))))-((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))*100) }}--}}
                    </td>

                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{number_format((floatval($post_total)), 2)}}
{{--                        {{number_format((floatval($record[1]["Outstanding Receivables"])/1000), 2)}}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ number_format(($post_percent_post/1000 != 0 ? number_format(((floatval($post_total))/($post_percent_post/1000)*100)) : 0)) }}
{{--                        {{ number_format(((floatval(($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+floatval(($record[1]["S2 Sales Year"]))+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))) != 0 ? number_format(((floatval($record[1]["Outstanding Receivables"]))/(floatval(($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval(($record[1]["S2 Sales Year"]))+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))))*100)) : 0)) }}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{number_format((($post_total != 0? (floatval($post_percent_due/1000) / floatval($post_total)) : 0) * 100))}}
{{--                        {{number_format(((floatval($record[1]["Outstanding Receivables"]) != 0? (floatval($record[1]["Outstanding Receivables Over 120"]) / floatval($record[1]["Outstanding Receivables"])) : 0) * 100))}}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{number_format($post_value_due)}}
{{--                        {{number_format(((floatval($record[1]["Outstanding Receivables Over 120"]) )/1000))}}--}}
                    </td>
                    {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                                {{ number_format((($record[1]["Outstanding Receivables"] != 0? ($record[1]["Clean Receivables"] / $record[1]["Outstanding Receivables"]) : 0) * 100), 2) }}--}}
                    {{--                            </td>--}}
                    {{--                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                                {{ number_format((0)) }}--}}
                    {{--                            </td>--}}
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ number_format($stock_value) }}
{{--                        {{ number_format(($record[1]['Stock Value'])/1000) }}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
{{--                        @php $stock_year_sales += ((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))); @endphp--}}
{{--                        {{ ((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))))*100) : 0 }}--}}
{{--                        {{ $stock_year_sales }}--}}
                        {{ ($stock_year_sales) != 0 ? number_format((((floatval($stock_value))/($stock_year_sales/1000)))*100) : 0 }}
{{--                        {{ ((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/((floatval($record[1]["S1 Sales Year"])+((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))))+(floatval($record[1]["S2 Sales Year"])+((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))))))*100) : 0 }}--}}
                    </td>
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        {{ floatval($stock_value) != 0 ? number_format(((floatval($stock_tadweer)/floatval($stock_value))/1000)*100, 2) : 0 }}
{{--                        {{ number_format((floatval($stock_value) != 0 ? number_format((floatval($stock_tadweer)/floatval($stock_value))*100, 2) : 0), 2) }}--}}
{{--                        {{ number_format((floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}--}}
                    </td>
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format($profit_period) }}
{{--                            {{ number_format(($record[1]['NPAT Period']/1000), 2) }}--}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format($profit_year) }}
{{--                            {{ number_format((((floatval($record[0]->YearTotalIncome) - floatval($record[0]->YearTotalExpenses)))+($record[1]['NPAT Annual']))/1000) }}--}}
                        </td>
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{ number_format($ope_expenses) }}
{{--                            {{ number_format(($record[1]['Operating Expenses']/1000), 2) }}--}}
                        </td>
                    @endif
                </tr>
                </tfoot>
                @endif
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
                // var end_date = $('#end_date').val();
                // alert(start_date);


                if(start_date == '' || /*end_date == '' ||*/ (dept_id == "" || dept_id == null)) {
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

                    Livewire.emit('create-report', start_date, /*end_date,*/ dept_id);
                }
            });

            // $('#start_date').change(function () {
            //
            //     var selectedDate = new Date($(this).val()); // Replace with your selected date
            //     selectedDate.setFullYear(selectedDate.getFullYear() + 1);
            //
            //     const formattedDate = `${selectedDate.getFullYear()}-${('0' + (selectedDate.getMonth()+1)).slice(-2)}-${('0' + selectedDate.getDate()).slice(-2)}`;
            //
            //     if ($('#end_date').val() != '' && (new Date($('#end_date').val())) > selectedDate) {
            //         $('#end_date').val('');
            //     }
            //
            //     document.getElementById("end_date").setAttribute("max", formattedDate);
            //
            // });

            // $('#end_date').change(function () {
            //
            //     var selectedDate2 = new Date($(this).val()); // Replace with your selected date
            //     selectedDate2.setFullYear(selectedDate2.getFullYear() - 1);
            //
            //     const formattedDate2 = `${selectedDate2.getFullYear()}-${('0' + (selectedDate2.getMonth()+1)).slice(-2)}-${('0' + selectedDate2.getDate()).slice(-2)}`;
            //
            //     if ($('#start_date').val() != '' && (new Date($('#start_date').val())) < selectedDate2) {
            //         $('#start_date').val('');
            //     }
            //
            //     document.getElementById("start_date").setAttribute("min", formattedDate2);
            //
            // });

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
