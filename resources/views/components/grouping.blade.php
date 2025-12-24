@props(['group_results' => [], 'recordGroup', 'currentGroup'])


@dd($group_results)
    @foreach($group_results as $record)
{{--        @if($currentGroup != $record["ItemGroup"])--}}
        @if($currentGroup != $record[$recordGroup])
            {{-- Output subtotals for the previous group --}}
            @if($currentGroup !== null)
                <tr wire:key="rec-{{ now() }}" style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        مجموع جزئي
                    </td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        {{number_format($itemGroup_item_subtotal, 2)}}
                    </td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                    </td>
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                    @endif
                </tr>
            @endif

{{--            @php $currentGroup = $record["ItemGroup"]; @endphp--}}
            @php $currentGroup = $recordGroup; @endphp
            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0;  @endphp
        @endif

        @if($record[$recordGroup] != $item_group_code)
            <?php $item_group_code = $record[$recordGroup]; ?>
{{--        @if($record["ItemGroup"] != $item_group_code)--}}
{{--            <?php $item_group_code = $record["ItemGroup"]; ?>--}}

            <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                                        {{$record["OldCode"]}}--}}
                {{--                                    </td>--}}
                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="flex flex-row">
                        <div>{{$record[$recordGroup]}}</div>
{{--                        <div>{{$record["ItemGroup"]}}</div>--}}
                        {{--                                            <div>الصنف: {{$record["ItemName"]}}</div>--}}
                        {{--                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>--}}
                        {{--                                            <div>التميز: {{$record['Speciality']}}</div>--}}
                        {{--                                            <div>المورد: {{$record["VendorName"]}}</div>--}}
                    </div>
                </td>
            </tr>
        @endif
        @if($record["OldCode"] != $item_group_itemCode_code)
            <?php $item_group_itemCode_code = $record["OldCode"]; ?>

            <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="flex flex-row justify-between">
                        <div>قسم:
                            <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                    ادارة فنية - الاسمدة م1
                                @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                    ادارة فنية - المبيدات م1
                                @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                    ادارة فنية - البذور م1
                                @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                    اقسام تسويقية - الحدائق والصحة العامة
                                @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                    اقسام تسويقية - المكافحة المتكاملة
                                @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                    الاليات والري - الاليات
                                @elseif($record["mrkt_type"] == "aleyat - ray")
                                    الاليات والري - الري
                                @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                    الاليات والري - الري المطري
                                @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                    الاليات والري - الخدمات
                                @else
                                    عام
                                @endif
                                                    </span>
                        </div>
                        <div>كودالصنف:
                            <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                        </div>
                        <div>الصنف:
                            <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                        </div>
                        <div>الوحدة:
                            <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                        </div>
                        <div>التميز:
                            <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                        </div>
                        <div>المورد:
                            <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                        </div>
                    </div>

                {{--                                            مجموع جزئي للصنف--}}
                {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                {{--                                        </td>--}}
                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                {{--                                        </td>--}}
                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                                        </td>--}}
                {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                {{--                                        @endif--}}
            </tr>
            <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                    {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                    {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                    {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                    {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                </td>
                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                @endif
            </tr>
        @endif
        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">


            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                {{__($record["Department"])}}

            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($record['TransCount'])}}
                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($record['TotalQuantitySold'])}}
                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($record['TotalSalesAmount'], 2)}}
                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                    {{number_format($record['AverageUnitPrice'], 2)}}
                @endif
            </td>
            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                    {{number_format($record["Cost"], 2)}}
                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                </td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                    {{number_format($record['GrossProfit'], 2)}}
                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                </td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                </td>
            @endif
        </tr>
        @php $counter++ @endphp
    @endforeach
    {{--                    @endforeach--}}
    @if($currentGroup !== null)
        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                مجموع جزئي
            </td>
            {{--                            العمليات                                --}}
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
            {{--                           الكمية                                  --}}
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
            {{--                          صافي المبيعات ------------------------}}
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($itemGroup_item_subtotal, 2)}}
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
            </td>
            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
            @endif
        </tr>
    @endif
