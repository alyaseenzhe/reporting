
<tbody class="text-sm divide-y divide-gray-100">

<tr>


    <td class="border p-2 whitespace-nowrap">
        <div>
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format($profitAndLoss, 2) : ""}}</div>
            {{--                            @if(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-08')--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format($area_loss_profit['2024-08'][$sap_results[0]["BPLId"]], 2) : ""}}</div>--}}
            {{--                            @elseif(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-09')--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format($area_loss_profit['2024-09'][$sap_results[0]["BPLId"]], 2) : ""}}</div>--}}
            {{--                            @else--}}
            {{--                                0--}}
            {{--                            @endif--}}
        </div>
    </td>
    <td class="border p-2 whitespace-nowrap">
        <div>
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($sap_results[0]["TransVal"])*0.01, 2) : ""}}</div>
        </div>
    </td>
    <td class="border p-2 whitespace-nowrap">
        <div>
            {{--                            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($sap_results[0]["Outstanding_Receivable"])*0.01, 2) : ""}}</div>--}}
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($branch_balance)*0.01, 2) : ""}}</div>
        </div>
    </td>
    <td class="border p-2 whitespace-nowrap">
        <div>
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($profitAndLoss)-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01), 2) : ""}}</div>
            {{--                            @if(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-08')--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($area_loss_profit['2024-08'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01), 2) : ""}}</div>--}}
            {{--                            @elseif(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-09')--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format(floatval($area_loss_profit['2024-09'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01), 2) : ""}}</div>--}}
            {{--                            @endif--}}
        </div>
    </td>
    <td class="border p-2 whitespace-nowrap">
        <div>
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? $area_commission[$area_id] : ""}}</div>
        </div>
    </td>
    <td class="border p-2 whitespace-nowrap">
        <div>
            @php $branch_comission = $sap_results ? (floatval($profitAndLoss)-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100) : "" @endphp
            <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format((floatval($profitAndLoss)-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100), 2) : ""}}</div>
            {{--                            @if(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-08')--}}
            {{--                                @php $branch_comission = $sap_results ? (floatval($area_loss_profit['2024-08'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100) : "" @endphp--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format((floatval($area_loss_profit['2024-08'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100), 2) : ""}}</div>--}}
            {{--                            @elseif(\Carbon\Carbon::parse($selected_date)->format('Y-m') == '2024-09')--}}
            {{--                                @php $branch_comission = $sap_results ? (floatval($area_loss_profit['2024-09'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100) : "" @endphp--}}
            {{--                                <div class="text-center text-gray-800 text-sm">{{$sap_results ? number_format((floatval($area_loss_profit['2024-09'][$sap_results[0]["BPLId"]])-(floatval($sap_results[0]["TransVal"])*0.01)-(floatval($branch_balance)*0.01))*($area_commission[$area_id]/100), 2) : ""}}</div>--}}
            {{--                            @endif--}}
        </div>
    </td>
</tr>
</tbody>
</table>
</div>
<br>
{{-- table 2 (details) --}}
<div id="tbl2-container" class="overflow-x-auto">
    <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
        <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
        <tr>
            <th class="border p-2 whitespace-nowrap">
                <div class="text-sm">#</div>
            </th>
            <th class="border p-2 whitespace-nowrap">
                <div class="text-sm">الاسم</div>
            </th>
            <th class="border p-2 whitespace-nowrap">
                <div class="text-sm">الوظيفة</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مساهمة الهامش</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">اجمالي المبيعات</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">% استحقاق الحافز</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مساهمة الربحية %</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">آجل</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مستحق %</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">اقدم فاتورة</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">يصرف؟</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">نسبة العمولة</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مدير مبيعات</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مدير منطقة</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">مدير معرض</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">تطوير مواد 1</div>
            </th>
            <th class="border p-2">
                <div class="text-sm">تطوير مواد 2</div>
            </th>
        </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-100">
        <?php $employee_profit = 0.00; ?>
        <?php $employee_sales = 0.00; ?>
        <?php $percentage_area_employee = 0.00; ?>
        <?php //$employee_postponed = 0.00; ?>
        <?php $employee_postponed = 1; ?>
        <?php $employee_postponed_due = 0.00; ?>
        <?php $employee_postponed_due_percentage = 0.00; ?>
        <?php $employee_commission = 0.00; ?>
        <?php $calc_sales_manager = 0.00; ?>
        <?php $calc_area_manager = 0.00; ?>
        <?php $calc_store_manager = 0.00; ?>
        <?php $calc_mat_dev1 = 0.00; ?>
        <?php $calc_mat_dev2 = 0.00; ?>
        <?php $commission_percentage = []; ?>
        <?php $total_sales_manager = 0.00; ?>
        <?php $total_area_manager = 0.00; ?>
        <?php $total_store_manager = 0.00; ?>
        <?php $total_mat_dev1 = 0.00; ?>
        <?php $total_mat_dev2 = 0.00; ?>
        <?php $percent = 0.00; ?>
        <?php $mosahamat_percent = 0.00; ?>


        @foreach($sap_results2 as $result2)
            @if(\Illuminate\Support\Facades\Auth::user()->role == 'a' || \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '0')
                <tr>
                    <td class="border p-2 whitespace-nowrap">
                        <div>

                            <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['OldSlpCode']?: $result2['SlpCode'] : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['SalesEmployeeName'] : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">
                                {{--                                                                {{$result2 ? $result2->role : ""}}--}}

                                @if(($emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']?? null]??null) == "area_manager")
                                    <span>مدير منطقة</span>
                                @elseif(($emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']?? null]??null)  == "store_manager")
                                    <span>مدير معرض</span>
                                @elseif(($emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']?? null]??null)  == "sales_manager")
                                    <span>مدير مبيعات</span>
                                @elseif(($emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']?? null]??null)  == "mat_dev_manager1")
                                    <span>تطوير مواد 1</span>
                                @elseif(($emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']?? null]??null)  == "mat_dev_manager2")
                                    <span>تطوير مواد 2</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2["GrossProfitLC"])) : ""}}</div>
                        </div>
                    </td>
                        <?php $tot =  $result2 ? floatval($result2["NetSalesAmountLC"]) : 0; ?>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2["NetSalesAmountLC"])) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">
                                @if($tot >= 0 && $tot <= 120000)
                                    30
                                        <?php $commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SlpCode']]] = 30; ?>
                                @elseif($tot > 120000 && $tot <= 240000)
                                    60
                                    @php $percent = 60; @endphp
                                    {{--                                            @dd($result2['OldSlpCode'], $result2['SlpCode'],$result2['SalesEmployeeCode'], $emp_position);--}}
                                        <?php $commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SlpCode']?: $result2['SalesEmployeeCode']]] = 60; ?>
                                @elseif($tot > 240000 && $tot < 400000)
                                    80
                                    @php $percent = 80; @endphp
                                        <?php $commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SlpCode']]] = 80; ?>
                                @elseif($tot >= 400000)
                                    100
                                    @php $percent = 100; @endphp
                                        <?php $commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SlpCode']]] = 100; ?>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        @php $mosahamat_percent += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100);  @endphp
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{--                            outstanding الآجل--}}
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Balance'])) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{ $result2['Balance Due'] &&  $result2['Balance']  && floatval(number_format($result2['Balance'])) != 0? number_format(floatval($result2['Balance Due'])) . " (%".number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100).")" : 0}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result2 ?  ($result2['Balance Due'] &&  $result2['Balance']  && floatval(number_format($result2['Balance'])) != 0? \Carbon\Carbon::parse($result2['OldestInvoice'])->format('Y-m-d')." (". \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) ." يوم)" : "-") : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>

                            @if(floatval(number_format($result2['Balance'])) == 0)
                                {{-- @if( \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= 210)--}}

                                @if( \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    <span style="font-weight: bold; color: green">نعم</span>
                                        <?php $pay = true; ?>
                                @else
                                    <span style="font-weight: bold; color: red">لا</span>
                                        <?php $pay = false; ?>
                                @endif
                            @else
                                {{--  @if(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= 210)--}}
                                @if(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    <span style="font-weight: bold; color: green">نعم</span>
                                        <?php $pay = true; ?>
                                @else
                                    <span style="font-weight: bold; color: red">لا</span>
                                        <?php $pay = false; ?>
                                @endif
                            @endif

                            {{--                                                            @if(floatval(number_format($result2['full_outstanding'])) == 0 && \Carbon\Carbon::parse($result2['oldest_inv'])->diffInDays($last_date) <= 240)--}}
                            {{--                                                                <span style="font-weight: bold; color: green">نعم</span>--}}
                            {{--                                                            @elseif(number_format(floatval($result2['outstanding_overdue'])/floatval($result2['full_outstanding'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_inv'])->diffInDays($last_date) <= 240)--}}
                            {{--                                                                <span style="font-weight: bold; color: green">نعم</span>--}}
                            {{--                                                            @else--}}
                            {{--                                                                <span style="font-weight: bold; color: red">لا</span>--}}
                            {{--                                                            @endif--}}



                            {{--                                                        <div class="text-center text-gray-800 text-lg">{{$result2 ?--}}
                            {{--                                                             (($result2['days'] <= 240 && ((!is_null(number_format(floatval($result2['postponed_partial']))) && number_format(floatval($result2['postponed_partial'])) != 0 && !is_null(number_format(floatval($result2['postponed_total']))) && number_format(floatval($result2['postponed_total'])) != 0))? (number_format(floatval($result2['postponed_partial']))/number_format(floatval($result2['postponed_total']))) : 0 <= 20)? "نعم" : "لا" : ""}}</div>--}}
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            @php $emp_comm = (floatval($branch_comission)*(floatval($result2["GrossProfitLC"])/$total_grossProfit)); @endphp
                            <div class="text-center text-gray-800 text-sm">
                                @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format((floatval($branch_comission)*(floatval($result2["GrossProfitLC"])/$total_grossProfit)), 2) : ""}}
                                @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format((floatval($branch_comission)*(floatval($result2["GrossProfitLC"])/$total_grossProfit)), 2) : ""}}
                                @else
                                    <span>0</span>
                                @endif

                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">

                                @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format(floatval($emp_comm)*(5/100)) : ""}}
                                @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format(floatval($emp_comm)*(5/100)) : ""}}
                                        <?php $total_sales_manager += (($result2 ? floatval($emp_comm)*(5/100) : 0)*($commission_percentage[$emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']]]/100)); ?>
                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            {{--                                {{ $position_commission[$emp_position[$result2["OldCode"]]] }}--}}
                            <div class="text-center text-gray-800 text-sm">
                                {{--                                    <span style="color: red">{{ ($commission_percentage[$emp_position[$result2['OldCode']]]) }}</span>--}}
                                {{--                                    <span style="color: green">{{ ($commission_percentage[$emp_position[$result2['OldCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['area_manager']/100))*$emp_comm) }}</span>--}}
                                @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['area_manager']/100))*$emp_comm) : ""}}
                                        <?php $total_area_manager += ($commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['area_manager']/100))*$emp_comm) ?>
                                @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['area_manager']/100))*$emp_comm) : ""}}
                                        <?php $total_area_manager += ($commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['area_manager']/100))*$emp_comm) ?>
                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">
                                @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{--                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}--}}
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?: $result2['SalesEmployeeCode']]]['store_manager']/100))*$emp_comm) : ""}}
                                        <?php //$total_store_manager += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['store_manager']/100))*$emp_comm) ?>
                                        <?php $total_store_manager += ($commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?: $result2["SalesEmployeeCode"]]]['store_manager']/100))*$emp_comm) ?>

                                @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?: $result2["SlpCode"]?: $result2['SalesEmployeeCode']]]['store_manager']/100))*$emp_comm) : ""}}
                                        <?php //$total_store_manager += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['store_manager']/100))*$emp_comm) ?>
                                        <?php $total_store_manager += ($commission_percentage[$emp_position[$result2['OldSlpCode']?: $result2["SlpCode"]]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?: $result2["SalesEmployeeCode"]]]['store_manager']/100))*$emp_comm) ?>

                                    {{--                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}--}}
                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">
                                @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{--                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}--}}
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager1']/100))*$emp_comm) : ""}}
                                        <?php //$total_mat_dev1 += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['mat_dev_manager1']/100))*$emp_comm) ?>
                                        <?php $total_mat_dev1 += ($commission_percentage[$emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2["SalesEmployeeCode"]]]['mat_dev_manager1']/100))*$emp_comm) ?>

                                @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                    {{--                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}--}}
                                    {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager1']/100))*$emp_comm) : ""}}
                                        <?php //$total_mat_dev1 += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['mat_dev_manager1']/100))*$emp_comm) ?>
                                        <?php $total_mat_dev1 += ($commission_percentage[$emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]]]['mat_dev_manager1']/100))*$emp_comm) ?>

                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['Balance'])) == 0 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                        {{--                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
                                        {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager2']/100))*$emp_comm) : ""}}
                                            <?php //$total_mat_dev2 += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['mat_dev_manager2']/100))*$emp_comm) ?>
                                            <?php $total_mat_dev2 += ($commission_percentage[$emp_position[$result2['OldSlpCode']?:$result2['SalesEmployeeCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager2']/100))*$emp_comm) ?>

                                    @elseif(number_format(floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) <= 20 && \Carbon\Carbon::parse($result2['OldestInvoice'])->diffInDays($last_date) <= $commission_days)
                                        {{--                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
                                        {{$result2 ? number_format((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager2']/100))*$emp_comm) : ""}}
                                            <?php //$total_mat_dev2 += ((floatval($result2["GrossProfitLC"])/$total_grossProfit)*100)*((floatval($position_commission[$emp_position[$result2["OldCode"]]]['mat_dev_manager2']/100))*$emp_comm) ?>
                                            <?php $total_mat_dev2 += ($commission_percentage[$emp_position[$result2['OldSlpCode']]]/100)*((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager2']/100))*$emp_comm) ?>

                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                    <?php $employee_profit += ($result2 ? floatval($result2["GrossProfitLC"]) : 0) ?>
                    <?php $employee_sales += ($result2 ? floatval($result2["NetSalesAmountLC"]) : 0) ?>
                    <?php //$percentage_area_employee += ($result2 ? floatval($commission_percentage[$emp_position[$result2['OldCode']]]) : 0) ?>
                    <?php $employee_postponed += ($result2 ? floatval($result2['Balance']) : 0) ?>
                    <?php $employee_postponed_due += ($result2 ? floatval($result2['Balance Due']) : 0) ?>
                    <?php $employee_postponed_due_percentage =  floatval($result2['Balance']) == 0? 0 : (floatval($result2['Balance Due'])/floatval($result2['Balance'])*100) ?>
                    <?php //$employee_commission += ($result2 ? (((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)) ? floatval($result2['employee_commission']) : 0) : 0) ?>
                    <?php $employee_commission += ($result2 && $pay? $emp_comm : 0) ?>
                    <?php //$calc_sales_manager += ($result2 && $pay? $total_sales_manager : 0) ?>
                    <?php $calc_sales_manager += ($result2 && $pay? ((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['sales_manager']/100))*$emp_comm) : 0) ?>
                    <?php $calc_area_manager += ($result2 && $pay? ((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['area_manager']/100))*$emp_comm) : 0) ?>
                    <?php $calc_store_manager += ($result2 && $pay? ((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['store_manager']/100))*$emp_comm) : 0) ?>
                    <?php $calc_mat_dev1 += ($result2 && $pay? ((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager1']/100))*$emp_comm) : 0) ?>
                    <?php $calc_mat_dev2 += ($result2 && $pay? ((floatval($position_commission[$emp_position[$result2["OldSlpCode"]?:$result2['SalesEmployeeCode']]]['mat_dev_manager2']/100))*$emp_comm) : 0) ?>
                {{--                @elseif(($result2 && $result2['Employeecode'] == \Illuminate\Support\Facades\Auth::user()->emp_code && \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '1') || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                {{--                    <tr>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['Employeecode'] : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['EmployeeName'] : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                                                {{$result2 ? $result2->role : ""}}--}}

                {{--                                    @if($result2['role'] == "area_manager")--}}
                {{--                                        <span>مدير منطقة</span>--}}
                {{--                                    @elseif($result2['role'] == "store_manager")--}}
                {{--                                        <span>مدير معرض</span>--}}
                {{--                                    @elseif($result2['role'] == "sales_manager")--}}
                {{--                                        <span>مدير مبيعات</span>--}}
                {{--                                    @elseif($result2['role'] == "mat_dev_manager1")--}}
                {{--                                        <span>تطوير مواد 1</span>--}}
                {{--                                    @elseif($result2['role'] == "mat_dev_manager2")--}}
                {{--                                        <span>تطوير مواد 2</span>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_profit'])) : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['tot'])) : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                                <?php $tot =  $result2 ? floatval($result2['tot']) : 0; ?>--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if($tot >= 0 && $tot <= 120000)--}}
                {{--                                        30--}}
                {{--                                        @php $percent = 30; @endphp--}}
                {{--                                        <?php $commission_percentage[$result2['role']] = 30; ?>--}}
                {{--                                    @elseif($tot > 120000 && $tot <= 240000)--}}
                {{--                                        60--}}
                {{--                                        @php $percent = 60; @endphp--}}
                {{--                                        <?php $commission_percentage[$result2['role']] = 60; ?>--}}
                {{--                                    @elseif($tot > 240000 && $tot < 400000)--}}
                {{--                                        80--}}
                {{--                                        @php $percent = 80; @endphp--}}
                {{--                                        <?php $commission_percentage[$result2['role']] = 80; ?>--}}
                {{--                                    @elseif($tot >= 400000)--}}
                {{--                                        100--}}
                {{--                                        @php $percent = 100; @endphp--}}
                {{--                                        <?php $commission_percentage[$result2['role']] = 100; ?>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['percentage_area_employee'])) : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_postponed'])) : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{ $result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? number_format(floatval($result2['employee_postponed_due'])) . " (%".number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100).")" : 0}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">{{$result2 ?  ($result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? \Carbon\Carbon::parse($result2['oldest_voucher'])->format('Y-m-d')." (". \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) ." يوم)" : "-") : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                @if(floatval(number_format($result2['employee_postponed'])) == 0)--}}
                {{--                                    @if( \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        <span style="font-weight: bold; color: green">نعم</span>--}}
                {{--                                            <?php $pay = true; ?>--}}
                {{--                                    @else--}}
                {{--                                        <span style="font-weight: bold; color: red">لا</span>--}}
                {{--                                            <?php $pay = false; ?>--}}
                {{--                                    @endif--}}
                {{--                                @else--}}
                {{--                                    @if(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        <span style="font-weight: bold; color: green">نعم</span>--}}
                {{--                                            <?php $pay = true; ?>--}}
                {{--                                    @else--}}
                {{--                                        <span style="font-weight: bold; color: red">لا</span>--}}
                {{--                                            <?php $pay = false; ?>--}}
                {{--                                    @endif--}}
                {{--                                @endif--}}

                {{--                                                            @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                                                <span style="font-weight: bold; color: green">نعم</span>--}}
                {{--                                                            @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                                                <span style="font-weight: bold; color: green">نعم</span>--}}
                {{--                                                            @else--}}
                {{--                                                                <span style="font-weight: bold; color: red">لا</span>--}}
                {{--                                                            @endif--}}



                {{--                                                        <div class="text-center text-gray-800 text-lg">{{$result2 ?--}}
                {{--                                                             (($result2['days'] <= 240 && ((!is_null(number_format(floatval($result2['postponed_partial']))) && number_format(floatval($result2['postponed_partial'])) != 0 && !is_null(number_format(floatval($result2['postponed_total']))) && number_format(floatval($result2['postponed_total'])) != 0))? (number_format(floatval($result2['postponed_partial']))/number_format(floatval($result2['postponed_total']))) : 0 <= 20)? "نعم" : "لا" : ""}}</div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}--}}
                {{--                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}--}}
                {{--                                    @else--}}
                {{--                                        <span>0</span>--}}
                {{--                                    @endif--}}

                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}--}}
                {{--                                        <?php $total_sales_manager += (floatval($result2['calc_sales_manager'])*($percent/100)); ?>--}}
                {{--                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}--}}
                {{--                                        <?php $total_sales_manager += (floatval($result2['calc_sales_manager'])*($percent/100)); ?>--}}
                {{--                                    @else--}}
                {{--                                        <span>0</span>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}--}}
                {{--                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}--}}
                {{--                                    @else--}}
                {{--                                        <span>0</span>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}--}}
                {{--                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}--}}
                {{--                                    @else--}}
                {{--                                        <span>0</span>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}--}}
                {{--                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}--}}
                {{--                                    @else--}}
                {{--                                        <span>0</span>--}}
                {{--                                    @endif--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                        <td class="border p-2 whitespace-nowrap">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-center text-gray-800 text-sm">--}}
                {{--                                    <div class="text-center text-gray-800 text-sm">--}}
                {{--                                        @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
                {{--                                        @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                {{--                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
                {{--                                        @else--}}
                {{--                                            <span>0</span>--}}
                {{--                                        @endif--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </td>--}}
                {{--                    </tr>--}}
                {{--                        <?php $employee_profit += ($result2 ? floatval($result2['employee_profit']) : 0) ?>--}}
                {{--                        <?php $employee_sales += ($result2 ? floatval($result2['tot']) : 0) ?>--}}
                {{--                        <?php $percentage_area_employee += ($result2 ? floatval($result2['percentage_area_employee']) : 0) ?>--}}
                {{--                        <?php $employee_postponed += ($result2 ? floatval($result2['employee_postponed']) : 0) ?>--}}
                {{--                        <?php $employee_postponed_due += ($result2 ? floatval($result2['employee_postponed_due']) : 0) ?>--}}
                {{--                        <?php $employee_postponed_due_percentage =  $employee_postponed == 0? 0 : ($employee_postponed_due/$employee_postponed)*100 ?>--}}
                {{--                        <?php //$employee_commission += ($result2 ? (((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)) ? floatval($result2['employee_commission']) : 0) : 0) ?>--}}
                {{--                        <?php $employee_commission += ($result2 && $pay? floatval($result2['employee_commission']) : 0) ?>--}}
                {{--                        <?php $calc_sales_manager += ($result2 && $pay? floatval($result2['calc_sales_manager']) : 0) ?>--}}
                {{--                        <?php $calc_area_manager += ($result2 && $pay? floatval($result2['calc_area_manager']) : 0) ?>--}}
                {{--                        <?php $calc_store_manager += ($result2 && $pay? floatval($result2['calc_store_manager']) : 0) ?>--}}
                {{--                        <?php $calc_mat_dev1 += ($result2 && $pay? floatval($result2['calc_mat_dev1']) : 0) ?>--}}
                {{--                        <?php $calc_mat_dev2 += ($result2 && $pay? floatval($result2['calc_mat_dev2']) : 0) ?>--}}
            @endif


            {{--                @if((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240))--}}
            {{--                    {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
            {{--                @elseif()--}}
            {{--                    {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
            {{--                @else--}}
            {{--                    <span>0</span>--}}
            {{--                @endif--}}
        @endforeach
        </tbody>
        <tfoot>
        <tr style="background-color: papayawhip; border: 2px solid black; font-weight: bold">
            <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">المجموع</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_profit) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_sales) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ "%" . number_format($mosahamat_percent) }}</td>
            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ "%" . number_format($percentage_area_employee) }}</td>--}}
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_postponed) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_postponed_due) . " (%". number_format(($employee_postponed_due/$employee_postponed)*100) . ")" }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_commission) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_sales_manager) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_area_manager) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_store_manager) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev1) }}</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev2) }}</td>
        </tr>
        <tr style="background-color: papayawhip; border: 2px solid black; font-weight: bold">
            <td colspan="12" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">مبلغ استحقاق الحافز الشهري</td>
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($total_sales_manager) }}</td>

            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_sales_manager*($commission_percentage && array_key_exists('sales_manager', $commission_percentage)? ($commission_percentage['sales_manager']/100) : 0)) }}</td>--}}
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($total_area_manager) }}</td>
            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_area_manager*($commission_percentage && array_key_exists('area_manager', $commission_percentage)? ($commission_percentage['area_manager']/100) : 0)) }}</td>--}}
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($total_store_manager) }}</td>
            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_store_manager*($commission_percentage && array_key_exists('store_manager', $commission_percentage)? ($commission_percentage['store_manager']/100): 0)) }}</td>--}}
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($total_mat_dev1) }}</td>
            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev1*($commission_percentage && array_key_exists('mat_dev_manager1', $commission_percentage)? ($commission_percentage['mat_dev_manager1']/100): 0)) }}</td>--}}
            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($total_mat_dev2) }}</td>
            {{--                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev2*($commission_percentage && array_key_exists('mat_dev_manager2', $commission_percentage)? ($commission_percentage['mat_dev_manager2']/100): 0)) }}</td>--}}
        </tr>

        </tfoot>
