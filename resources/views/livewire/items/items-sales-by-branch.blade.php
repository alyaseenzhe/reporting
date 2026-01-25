@section('title')
    23- تقرير كمية مبيعات الأصناف بالفرع
@stop
<div>
<div x-data="{ container:true, itemSearch:false, advancedSearch:false, showBranches:null, showEmployees: null}"
     x-on:livewire:update.window="showBranches = null;  showEmployees: null;"
     x-cloak>


@include('livewire.items.search')

@if($show_msg)
    <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">

        @if(count($group_results) > 0)

            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm" >
                <tr style="border: 2px solid black;">

                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm"></div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الفرع</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اجمالي الكمية</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الكمية حسب الفرع</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm" >نسبة الفرع

                        </div>
                    </th>
{{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                        <div class="text-sm">متوسط السعر</div>--}}
{{--                    </th>--}}


{{--                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">--}}
{{--                            <div class="text-sm">التكلفة</div>--}}
{{--                        </th>--}}
{{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin cost">--}}
{{--                            <div class="text-sm">الهامش</div>--}}
{{--                        </th>--}}
{{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin-percentage cost">--}}
{{--                            <div class="text-sm">نسبة</div>--}}
{{--                        </th>--}}
{{--                    @endif--}}
                </tr>
                </thead>
{{--                @foreach($group_results as $record)--}}
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            ({{ $record['ItemCode'] }}) - {{ $record['ItemName'] }}--}}
{{--                        </td>--}}
{{--                    </tr>--}}

{{--                    @foreach($record['branches'] as $branch)--}}
{{--                        <tr>--}}
{{--                            <td style="padding-left:20px">--}}
{{--                                {{ $branch['BranchName'] }} —--}}
{{--                                {{ $branch['TotalQuantitySaleByBranch'] }}--}}
{{--                            </td>--}}
{{--                            <td>{{$branch['TotalQuantitySale']}}</td>--}}
{{--                            <td>{{$branch['TotalSalesPer']}}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                @endforeach--}}
{{--                <table class="w-full border mt-4">--}}
{{--                    <thead class="bg-gray-100">--}}
{{--                    <tr>--}}
{{--                        <th class="border p-2">Branch</th>--}}
{{--                        <th class="border p-2">Total Quantity</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($totalsByBranch as $branch)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $branch['BranchName'] }}</td>--}}
{{--                            <td>{{ number_format($branch['TotalQuantity']) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    @foreach($grouped as $item)--}}
{{--                        <tr class="bg-gray-200 font-semibold">--}}
{{--                            <td class="border p-2" colspan="3">--}}
{{--                                {{ $item['ItemCode'] }} - {{ $item['ItemName'] }}--}}
{{--                            </td>--}}

{{--                        </tr>--}}

{{--                        @foreach($item['Branches'] as $branch)--}}
{{--                            <tr>--}}
{{--                                <td class="border p-2"></td>--}}
{{--                                <td class="border p-2">{{ $branch['BranchName'] }}</td>--}}
{{--                                <td>{{$branch['EmployeeName']}}</td>--}}
{{--                                <td class="border p-2 text-right">--}}
{{--                                    {{ number_format($branch['Qty']) }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--                @foreach($grouped as $key=> $record)--}}
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            {{__($record["ItemCode"])}}  - {{__($record["ItemName"])}}--}}
{{--                        </td>--}}
{{--                    <td>--}}
{{--                        {{__($record["BranchName"])}}--}}
{{--                    </td>--}}
{{--                <td>--}}
{{--                    {{$record['TotalQuantitySale']}}--}}
{{--                </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}

    @foreach($group_results as $key=> $record)
{{--        @dd($record)--}}
        @if($currentGroup != $record["ItemCode"])


            @php $currentGroup = $record["ItemCode"]; @endphp
{{--            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0;  @endphp--}}
        @endif

        @if($record["ItemCode"] != $item_group_code)
            <?php $item_group_code = $record["ItemCode"]; ?>
            <tr style="background-color: #faebd7; font-weight: bold; color: red;"  wire:key="item-{{ $key }}-{{ $record['ItemCode'] }}">
                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                                        {{$record["ItemCode"]}}--}}
                {{--                                    </td>--}}
                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="flex flex-row">
                        <div>({{ $record["ItemCode"] }}) - {{$record["ItemName"]}}</div>
                    </div>
                </td>
            </tr>
        @endif


        @if($record["ItemCode"] != $item_group_itemCode_code)
            <?php $item_group_itemCode_code = $record["ItemCode"]; ?>

{{--            <tr onclick="show_hide({{$record["ItemCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer"  wire:key="item-{{ $key }}-{{ $record['ItemCode'] }}">--}}
{{--            <tr @click="showBranches= !showBranches"  style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer"  wire:key="sections-{{ $key }}-{{ $record['ItemCode'] }}">--}}
            <tr  @click="showBranches = showBranches === '{{ $record['ItemCode'] }}' ? null : '{{ $record['ItemCode'] }}';
            showEmployees = '{{ $empKey }}' ? null :    '{{ $empKey }}'"  style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer"  wire:key="sections-{{ $key }}-{{ $record['ItemCode'] }}">
{{--                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["ItemCode"] }}" x-text="showBranches ? '-' : '+'">+</td>--}}
                <td rowspan="" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["ItemCode"] }}" x-text="showBranches === '{{ $record['ItemCode'] }}' ? '-' : '+'">+</td>
                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="flex flex-row justify-between">
                        <div>قسم:
                            <span style="color: #227dd7">{{__($record["mrkt_type"])}}</span>

                        </div>
                        <div>نوع المادة:
                            <span style="color: #227dd7">{{$record["ItemGroup"]}}</span>
                        </div>
                        <div>الوحدة:
                            <span style="color: #227dd7">
                                                    {{$record["Unit"]}}
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

            </tr>
{{--            <tr   @click="showBranches = !showBranches" x-text="showBranches ? '-' : '+'"  onclick="show_hide({{$record["ItemCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer"  wire:key="item-{{ $key }}-{{ $record['ItemCode'] }}">--}}
            <tr  style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer"  wire:key="groups-{{ $key }}-{{ $record['ItemCode'] }}">
{{--                                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["ItemCode"] }}">+</td>--}}
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
{{--                    {{number_format($totalSalesByItem[$record["ItemCode"]][4])}}--}}
                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
{{--                    {{number_format($totalSalesByItem[$record["ItemCode"]][3])}}--}}
                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                    {{number_format($totalSalesByItem[$record["ItemCode"]][0], 2)}}--}}
                    {{number_format($record['TotalQuantitySale'])}}

                </td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
{{--                    {{ $totalSalesByItem[$record["ItemCode"]][3] != 0? number_format($totalSalesByItem[$record["ItemCode"]][0]/$totalSalesByItem[$record["ItemCode"]][3], 2) : 0 }}--}}
                </td>
{{--                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["ItemCode"]][1], 2)}}</td>--}}
{{--                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["ItemCode"]][2], 2)}}</td>--}}
{{--                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["ItemCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["ItemCode"]][2]/$totalSalesByItem[$record["ItemCode"]][0])*100, 2)}}</td>--}}
{{--                @endif--}}
            </tr>
        @endif
{{--        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["ItemCode"]}} " x-show="showBranches"  wire:key="hidden-{{ $key }}-{{ $record['ItemCode'] }}">--}}
                    @foreach($record['branches'] as $bindex =>$branch)
{{--                        <?php $empKey = $record['ItemCode'] . '_' . Str::slug($branch['BranchName'].'_'.$bindex) ?>--}}

                            <?php $branchSlug = Str::slug($branch['BranchName']); // converts "فرع حائل" → "fraa-hael"
                            $empKey = $record['ItemCode'] . '_branch_' . $branchSlug;?>
        <tr   @click="showEmployees = showEmployees === '{{ $empKey }}' ? null :    '{{ $empKey }}',     console.log('showEmployees =', showEmployees)"

              style="border-bottom-color:#7aa6b8 "

             class=" cursor-pointer  border-b
{{--             @if($key%2==0) bg-gray-200 @else /*bg-[#7aa6b8]*/ bg-white @endif --}}
             row-{{$record["ItemCode"]}} "  x-show="showBranches === '{{ $record['ItemCode'] }}'" x-collapse
             wire:key="hidden-{{$bindex }}-{{ $record['ItemCode'] }}"

        >


<td>
    <span x-text="showEmployees === '{{ $empKey }}' ? '-' : '+'"></span>

</td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">

                                {{ $branch['BranchName'] }}
                            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($record['TotalQuantitySale'])}}</td>

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ $branch['TotalQuantitySaleByBranch'] }}

                            </td>

                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$branch['TotalSalesPer']}}</td>
                        @foreach($branch['employees'] as  $emp)
{{--                                <?php $empKey = $record['ItemCode'] . '_' . Str::slug($branch['BranchName']) . '_' . $emp['EmployeeName'].'_'.$ekey; ?>--}}
                            <tr  wire:key="emp-{{ $record['ItemCode'] }}-{{ $branch['BranchName'] }}-{{ $emp['EmployeeName'] }}"
{{--                                style="    border: solid; border-bottom-color: rgb(122, 166, 184) !important;" --}}
                                x-show="showEmployees === '{{ $empKey }}' "  data-branch="{{ $empKey }}" class="bg-gray-100 border-b border-white py-2">
                                <td></td>
                                <td style="padding-left:40px; border-left: 2px solid black;"  class="py-2">
                                    {{ $emp['EmployeeName'] }}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="py-2" >
                                    {{number_format($record['TotalQuantitySale'])}}</td>
                                <td style="border-left: 2px solid black;" class="py-2">
                                    {{ $emp['Quantity'] }}
                                </td>
                                <td style="border-left: 2px solid black;" class="py-2">
                                    {{ $emp['EmployeePer'] }}
                                </td>
                            </tr>
            @endforeach
{{--            @endforeach--}}
{{--                        --}}
{{--                 --}}
{{--            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                {{__($record["BranchName"])}}--}}

{{--            </td>--}}
{{--            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                {{$record['TotalQuantitySale']}}--}}
{{--                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp--}}
{{--                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp--}}
{{--            </td>--}}
{{--            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                {{number_format($record['TotalQuantitySaleByBranch'])}}--}}
{{--                {{$record['TotalQuantitySaleByBranch']}}--}}
{{--                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySale']); @endphp--}}
{{--                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySale']); @endphp--}}
{{--            </td>--}}
{{--            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                {{number_format($record['TotalSalesAmount'], 2)}}--}}
{{--                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp--}}
{{--                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp--}}
{{--                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp--}}
{{--            </td>--}}
{{--            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}


{{--                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )--}}
{{--                    {{number_format($record['AverageUnitPrice'], 2)}}--}}
{{--                @endif--}}
{{--            </td>--}}
{{--            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">--}}
{{--                    {{number_format($record["Cost"], 2)}}--}}
{{--                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp--}}
{{--                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp--}}
{{--                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp--}}
{{--                </td>--}}
{{--                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">--}}
{{--                    {{number_format($record['GrossProfit'], 2)}}--}}
{{--                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp--}}
{{--                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp--}}
{{--                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp--}}
{{--                </td>--}}
{{--                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">--}}
{{--                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}--}}
{{--                </td>--}}

{{--                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">--}}
{{--                            {{$record['TotalSalesPer']}}--}}

{{--                </td>--}}
{{--            @endif--}}
{{--        </tr>--}}
            @endforeach
        @php $counter++ @endphp
    @endforeach
    </div>

@endif
@endif
</div>

{{--@include('livewire.items.alpineJs')--}}

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

    /*.hide {*/
    /*    display: none;*/
    /*}*/

    .record-row { opacity: 1; transform: translateY(0); transition: opacity 0.5s ease, transform 0.5s ease; }
    .record-row.hide-row {
        opacity: 0; transform: translateY(-20px); /* Adjust vertical movement if needed */
    }

    #report-logo {
        display: none;
    }

    /*thead th {*/
    /*    top: 0;*/
    /*    position: sticky;*/
    /*    background-color: #666666;*/
    /*    z-index: 20;*/
    /*}*/
    /*thead th {*/
    /*    position: sticky;*/
    /*    top: 0;*/
    /*    background-color: #f1f1f1;*/
    /*    z-index: 1;*/
    /*}*/

    /*.table-container-x {*/
    /*    max-height: 300px;*/
    /*    overflow-y: auto;*/
    /*    border: 1px solid #ccc;*/
    /*    width: 100%;*/
    /*}*/


    /*#tbl2 thead, tbl2 tfoot, #tbl2 tbody {*/
    /*    display: block;*/
    /*    !*width: 100%;*!*/
    /*}*/
    /*.table-container {*/
    /*    max-height: 400px; !* Adjust the height as needed *!*/
    /*    overflow-y: auto;*/
    /*    border: 1px solid #ccc;*/
    /*}*/

    /*#tbl2 tbody {*/
    /*    max-height: 300px;*/
    /*    overflow-y: auto;*/
    /*    border: 1px solid #ccc;*/
    /*    width: 100%;*/
    /*}*/

    /*#tbl2 thead {*/
    /*    position: sticky;*/
    /*    top: 0;*/
    /*    z-index: 2;*/
    /*}*/

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




    /* Style the button that is used to open and close the collapsible content */
    .collapsible {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 5px;
        width: 100%;
        border: none;
        /*text-align: left;*/
        outline: none;
        font-size: 15px;
    }

    /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
    .active, .collapsible:hover {
        background-color: #ccc;
    }

    /* Style the collapsible content. Note: hidden by default */
    #branch-container {
        padding: 18px 18px;
        display: block;
        /*overflow: hidden;*/
        background-color: #f1f1f1;
    }

    .collapsible:after {
        content: '\02795'; /* Unicode character for "plus" sign (+) */
        font-size: 13px;
        color: white;
        float: left;
        margin-left: 5px;
    }

    button.active:after {
        content: "\2796"; /* Unicode character for "minus" sign (-) */
</style>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        document.querySelectorAll('[data-branch]')
    </script>
</div>
