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
                    <input id="start_date" type="date" onkeydown="return false" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" onkeydown="return false" name="end_date"
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
                @if ($start_date > '2026-12-31' && $end_date > '2026-12-31')
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
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['NPAT Period']/1000, 2) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['NPAT Annual']/1000, 2) }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['Operating Expenses']/1000, 2) }}
                            </td>
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
                @elseif ($start_date <= '2023-12-31' && $end_date <= '2023-12-31')
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
                @else
                    @foreach($merged as $record)
                        @if($start_date <= '2023-12-31' && $end_date >= '2024-01-01')
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record[0]->arabic_name}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/1000)+(floatval($record[1]["S1 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}
                                    {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/1000)+(floatval($record[1]["S2 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales PY"]) != 0 ? ((floatval($record[1]["S2 Sales"]) - floatval($record[1]["S2 Sales PY"])) / floatval($record[1]["S2 Sales PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) + (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/1000)+((floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) + (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"])) != 0 ? (((floatval($record[1]["S1 Sales"])+floatval($record[1]["S2 Sales"]))-(floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) / (floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{--                            {{((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000)}}--}}
                                    {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/(floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S1 Sales Year PY"]) != 0 ? ((floatval($record[1]["S1 Sales Year"]) - floatval($record[1]["S1 Sales Year PY"])) / floatval($record[1]["S1 Sales Year PY"])) * 100 : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/1000)+(floatval($record[1]["S2 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales Year PY"]) != 0 ? ((floatval($record[1]["S2 Sales Year"]) - floatval($record[1]["S2 Sales Year PY"])) / floatval($record[1]["S2 Sales Year PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)+((floatval($record[1]["S1 Sales Year"])/1000)+(floatval($record[1]["S2 Sales Year"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"])) != 0 ? (((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"]))-(floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) / (floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[0]->DebitCustomers))/1000)+(floatval($record[1]["Outstanding Receivables"])/1000), 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000) != 0? ((100*((floatval($record[0]->DebitCustomers)))/1000)/(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)):0)+((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Outstanding Receivables"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[0]->DebitCustomers) != 0? (100*(floatval($record[0]->DueBalance)/floatval($record[0]->DebitCustomers))) : 0)+((floatval($record[1]["Outstanding Receivables"]) != 0? (floatval($record[1]["Outstanding Receivables Over 120"]) / floatval($record[1]["Outstanding Receivables"])) : 0) * 100))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(100*(((floatval($record[0]->SPYearSalesNotCash) - floatval($record[0]->SPYearSalesReturnNotCash))/1000) != 0? (((floatval($record[0]->DebitCustomers))/1000)/((floatval($record[0]->SPYearSalesNotCash) - floatval($record[0]->SPYearSalesReturnNotCash))/1000)) : 0)+(($record[1]["Outstanding Receivables"] != 0? ($record[1]["Clean Receivables"] / $record[1]["Outstanding Receivables"]) : 0) * 100), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->SPYearSalesNotCash) - floatval($record[0]->SPYearSalesReturnNotCash))/1000)+(0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000)+($record[1]['Stock Value'])) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)) != 0? (100*(((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000)/((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)))) : 0)+((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000) != 0 ? ((floatval($record[0]->YearCOGS)/((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000))/1000) : 0)+(floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->TotalIncome) - floatval($record[0]->TotalExpenses))/1000)+($record[1]['NPAT Period']/1000), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->YearTotalIncome) - floatval($record[0]->YearTotalExpenses))/1000)+($record[1]['NPAT Annual']/1000)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->TotalExpenses) - floatval($record[0]->COGS))/1000)+($record[1]['Operating Expenses']/1000), 2) }}
                                </td>
                            </tr>
                        @elseif(($start_date >= '2024-01-01' && $start_date <= '2024-12-31') && $end_date <= '2025-12-31')
{{--                        @elseif($start_date >= '2024-01-01' && $end_date <= '2024-12-31')--}}
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record[0]->arabic_name}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["S1 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100) : 0) +(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}
                                    {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["S2 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn))/(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales PY"]) != 0 ? ((floatval($record[1]["S2 Sales"]) - floatval($record[1]["S2 Sales PY"])) / floatval($record[1]["S2 Sales PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn)) + (floatval($record[0]->SP2Sales) - floatval($record[0]->SP2SalesReturn)))/((floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease))+(floatval($record[0]->SP2SalesIncrease) - floatval($record[0]->SP2SalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"])) != 0 ? (((floatval($record[1]["S1 Sales"])+floatval($record[1]["S2 Sales"]))-(floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) / (floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{--                            {{((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000)}}--}}
                                    {{number_format(((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/(floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S1 Sales Year PY"]) != 0 ? ((floatval($record[1]["S1 Sales Year"]) - floatval($record[1]["S1 Sales Year PY"])) / floatval($record[1]["S1 Sales Year PY"])) * 100 : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/1000)+(floatval($record[1]["S2 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales Year PY"]) != 0 ? ((floatval($record[1]["S2 Sales Year"]) - floatval($record[1]["S2 Sales Year PY"])) / floatval($record[1]["S2 Sales Year PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)+((floatval($record[1]["S1 Sales Year"])/1000)+(floatval($record[1]["S2 Sales Year"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"])) != 0 ? (((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"]))-(floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) / (floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["Outstanding Receivables"])/1000), 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Outstanding Receivables"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[1]["Outstanding Receivables"]) != 0? (floatval($record[1]["Outstanding Receivables Over 120"]) / floatval($record[1]["Outstanding Receivables"])) : 0) * 100))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((($record[1]["Outstanding Receivables"] != 0? ($record[1]["Clean Receivables"] / $record[1]["Outstanding Receivables"]) : 0) * 100), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['Stock Value'])) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)) != 0? (100*(((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000)/((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/1000)))) : 0)+((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}
{{--                                    {{ number_format((((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000) != 0 ? ((floatval($record[0]->YearCOGS)/((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000))/1000) : 0)+(floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['NPAT Period']/1000), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[0]->YearTotalIncome) - floatval($record[0]->YearTotalExpenses))/1000)+($record[1]['NPAT Annual']/1000)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['Operating Expenses']/1000), 2) }}
                                </td>
                            </tr>
                        @elseif(($start_date >= '2025-01-01' && $start_date <= '2025-12-31') && $end_date <= '2026-12-31')
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record[0]->arabic_name}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["S1 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}
                                    {{--                            {{ number_format(((((floatval($record[0]->SP1Sales) - floatval($record[0]->SP1SalesReturn))/(floatval($record[0]->SP1SalesIncrease) - floatval($record[0]->SP1SalesReturnIncrease)))-1)*100)+(floatval($record[1]["S1 Sales PY"]) != 0 ? ((floatval($record[1]["S1 Sales"]) - floatval($record[1]["S1 Sales PY"])) / floatval($record[1]["S1 Sales PY"])) * 100 : 0)) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["S2 Sales"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((floatval($record[1]["S2 Sales PY"]) != 0 ? ((floatval($record[1]["S2 Sales"]) - floatval($record[1]["S2 Sales PY"])) / floatval($record[1]["S2 Sales PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[1]["S1 Sales"])/1000)+(floatval($record[1]["S2 Sales"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"])) != 0 ? (((floatval($record[1]["S1 Sales"])+floatval($record[1]["S2 Sales"]))-(floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) / (floatval($record[1]["S1 Sales PY"])+floatval($record[1]["S2 Sales PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{--                            {{((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/1000)+(floatval($record[1]["S1 Sales Year"])/1000)}}--}}
                                    {{number_format((floatval($record[1]["S1 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn))/(floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S1 Sales Year PY"]) != 0 ? ((floatval($record[1]["S1 Sales Year"]) - floatval($record[1]["S1 Sales Year PY"])) / floatval($record[1]["S1 Sales Year PY"])) * 100 : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["S2 Sales Year"])/1000))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format(((floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)) != 0 ? ((((floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn))/(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease)))-1)*100) : 0)+(floatval($record[1]["S2 Sales Year PY"]) != 0 ? ((floatval($record[1]["S2 Sales Year"]) - floatval($record[1]["S2 Sales Year PY"])) / floatval($record[1]["S2 Sales Year PY"])) * 100 : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[1]["S1 Sales Year"])/1000)+(floatval($record[1]["S2 Sales Year"])/1000)))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2">
                                    {{ number_format((((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))) != 0 ? (((((floatval($record[0]->SP1YearSales) - floatval($record[0]->SP1YearSalesReturn)) + (floatval($record[0]->SP2YearSales) - floatval($record[0]->SP2YearSalesReturn)))/((floatval($record[0]->SP1YearSalesIncrease) - floatval($record[0]->SP1YearSalesReturnIncrease))+(floatval($record[0]->SP2YearSalesIncrease) - floatval($record[0]->SP2YearSalesReturnIncrease))))-1)*100) : 0)+((floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"])) != 0 ? (((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"]))-(floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) / (floatval($record[1]["S1 Sales Year PY"])+floatval($record[1]["S2 Sales Year PY"]))) : 0)) }}
                                </td>

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format((floatval($record[1]["Outstanding Receivables"])/1000), 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Outstanding Receivables"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{number_format(((floatval($record[1]["Outstanding Receivables"]) != 0? (floatval($record[1]["Outstanding Receivables Over 120"]) / floatval($record[1]["Outstanding Receivables"])) : 0) * 100))}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((($record[1]["Outstanding Receivables"] != 0? ($record[1]["Clean Receivables"] / $record[1]["Outstanding Receivables"]) : 0) * 100), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['Stock Value'])) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(((floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])) != 0 ? number_format(((floatval($record[1]["Stock Value"]))/(floatval($record[1]["S1 Sales Year"])+floatval($record[1]["S2 Sales Year"])))*100) : 0)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format((floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}
                                    {{--                                    {{ number_format((((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000) != 0 ? ((floatval($record[0]->YearCOGS)/((floatval($record[0]->InpuCost) - floatval($record[0]->OutPutCost))/1000))/1000) : 0)+(floatval($record[1]["Stock Value"]) != 0 ? number_format((floatval($record[1]["COGS"])/floatval($record[1]["Stock Value"]))*100, 2) : 0), 2) }}--}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['NPAT Period']/1000), 2) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['NPAT Annual']/1000)) }}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{ number_format(($record[1]['Operating Expenses']/1000), 2) }}
                                </td>
                            </tr>
                        @endif
                        @php $counter++ @endphp
                    @endforeach
                @endif
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

            $('#start_date').change(function () {

                var selectedDate = new Date($(this).val()); // Replace with your selected date
                selectedDate.setFullYear(selectedDate.getFullYear() + 1);

                const formattedDate = `${selectedDate.getFullYear()}-${('0' + (selectedDate.getMonth()+1)).slice(-2)}-${('0' + selectedDate.getDate()).slice(-2)}`;

                if ($('#end_date').val() != '' && (new Date($('#end_date').val())) > selectedDate) {
                    $('#end_date').val('');
                }

                document.getElementById("end_date").setAttribute("max", formattedDate);

            });

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
