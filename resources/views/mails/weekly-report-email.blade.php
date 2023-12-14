<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركة الياسين الزراعية</title>
    <style>
        table td {
            border: solid 1px black;
            padding: 0.5rem;
        }

        table th{
            border: solid 1px black;
            padding: 0.5rem;
        }
    </style>
</head>
<body style="direction: rtl;">
<div>
    <div style="text-align: center">
        <img width="123" height="98" style="width:1.2833in;height:1.0166in" src="https://reporting.alyaseenagri.com/images/logo-horizontal.png">
    </div>
    <br>
    <div
        style="text-align: center;" class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <h2>ملخص مبيعات {{ $branch }}</h2>
    </div>
    <div style="font-weight: bold; text-align: center">
        <span>({{ $start_date }}</span>
        <span>إلى</span>
        <span>{{ $end_date }})</span>
    </div>
    {{-- table 2 (details) --}}
    <div id="report-btn" class="hide printable">
        {{-- table 2 (details) --}}
        <div id="tbl2-container" style="overflow: auto">
            <table id="tbl2" style="border: 2px solid black; border-collapse: collapse; text-align: center" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th class="whitespace-nowrap">
                        <div class="text-xs">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">الاسم</div>
                    </th>
                    <th>
                        <div class="text-xs"># عملاء مبيعات</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs"># زيارات</div>
                    </th>
                    <th>
                        <div class="text-xs">$ مستحق</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">%المستحق</div>
                    </th>
                    <th>
                        <div class="text-xs">تحصيل</div>
                    </th>
                    <th>
                        <div class="text-xs">م نقدية</div>
                    </th>
                    <th>
                        <div class="text-xs">م آجلة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">مجموع مبيعات</div>
                    </th>
                    <th>
                        <div class="text-xs">#بذور</div>
                    </th>
                    <th>
                        <div class="text-xs">$بذور</div>
                    </th>
                    <th>
                        <div class="text-xs">#مبيدات</div>
                    </th>
                    <th>
                        <div class="text-xs">$مبيدات</div>
                    </th>
                    <th>
                        <div class="text-xs">#اسمدة</div>
                    </th>
                    <th>
                        <div class="text-xs">$اسمدة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">#اخرى</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">$اخرى</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 1</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 2</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 0</div>
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
                @foreach($data as $key => $record)
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
                    <td>{{ isset($category_qty_total['bathoor']) ? number_format($category_qty_total['bathoor']) : 0 }}</td>
                    <td>{{ number_format(round($total_bathoor/1000)) }}</td>
                    <td>{{ isset($category_qty_total['mobedat']) ? number_format($category_qty_total['mobedat']) : 0 }}</td>
                    <td>{{ number_format(round($total_mobedat/1000)) }}</td>
                    <td>{{ isset($category_qty_total['asmedah']) ? number_format($category_qty_total['asmedah']) : 0 }}</td>
                    <td>{{ number_format(round($total_asmedah/1000)) }}</td>
                    <td>{{ isset($category_qty_total['other']) ? number_format($category_qty_total['other']) : 0 }}</td>
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
</div>
</body>
</html>
