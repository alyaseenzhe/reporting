@section('title')
    26- تقرير كفاية المخزون
@stop
<div>

    <div
        x-data="{
            container: true,
            itemSearch: false,
            advancedSearch: false,
            expandedItems: {},
            expandedBranches: {},
            itemKey(itemCode) {
                return String(itemCode);
            },
            branchKey(itemCode, branchCode) {
                return `${itemCode}|${branchCode}`;
            },
            isItemExpanded(itemCode) {
                return !!this.expandedItems[this.itemKey(itemCode)];
            },
            isBranchExpanded(itemCode, branchCode) {
                return !!this.expandedBranches[this.branchKey(itemCode, branchCode)];
            },
            toggleItem(itemCode) {
                const key = this.itemKey(itemCode);
                this.expandedItems[key] = !this.isItemExpanded(itemCode);
            },
            toggleBranch(itemCode, branchCode) {
                const key = this.branchKey(itemCode, branchCode);
                this.expandedBranches[key] = !this.isBranchExpanded(itemCode, branchCode);
            }
        }"
        x-cloak
    >

        @include('livewire.inventory.search')

        @if($show_msg)
            <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">
{{--@dd($group_results);--}}

                @if(count($group_results) > 0)
                    @php
                        $totals = [
                            'InventoryQuantity' => collect($group_results)->sum('InventoryQuantity'),
                            'BasePrice' => collect($group_results)->sum('BasePrice'),
                            'Cost' => collect($group_results)->sum('Cost'),
                            'InventoryCost' => collect($group_results)->sum('InventoryCost'),
                            'InventoryValue' => collect($group_results)->sum('InventoryValue'),
                            'AnnualQuantitySale' => collect($group_results)->sum('AnnualQuantitySale'),
                        ];

                        $totals['SufficiencyDays'] = $totals['AnnualQuantitySale'] > 0
                            ? round(($totals['InventoryQuantity'] / $totals['AnnualQuantitySale']) * 365, 2)
                            : null;
                    @endphp

                    <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                        <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                        <tr style="border: 2px solid black;">
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm"></div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الفرع</div>
                            </th>
{{--                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                                <div class="text-sm">الوحدة</div>--}}
{{--                            </th>--}}
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كمية المخزون</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">سعر الأساس</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كلفة الوحدة</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كلفة المخزون</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">قيمة المخزون</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كمية المبيعات السنوية</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">أيام الكفاية</div>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($group_results as $item)

                            @php
                                $code = $item['ItemCode'] ?? '';
                                $itemCodeJs = json_encode((string) $code);
                            @endphp
{{--                           @dd($item['branches'])--}}
                            @if(count($item['branches']) >0)
{{--                                <tr>{{count($item['branches'])}}</tr>--}}
                            <tr
                                style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer"
                                wire:key="item-{{ $code }}"
                                @click="toggleItem({{ $itemCodeJs }})"
                            >
                                <td style="border-left: 2px dashed #a8a8a8;" class="border p-1 whitespace-nowrap py-4">
                                    <span x-text="isItemExpanded({{ $itemCodeJs }}) ? '-' : '+'"></span>
                                </td>
                                <td style="border-left: 2px dashed #a8a8a8; word-wrap: break-word;" class="border p-1 py-4">
                                    <div class="flex flex-row justify-between mx-2" style="width: 300px">
                                        <div>
                                            <span>
                                        {{ $item['ItemCode'] . ' - ' . $item['ItemName'] }}
                                            </span>
                                        </div>
                                        <div>
                                        <span style="color: #227dd7">
                                                {{ $item['Unit'] }}
                                            </span>
                                        </div>
                                    </div>

                                </td>
{{--                                <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 py-6 whitespace-nowrap">--}}
{{--                                    <div class="flex flex-row justify-between mx-2">--}}
{{--                                        <div>الوحدة:--}}
{{--                                            <span style="color: #227dd7">--}}
{{--                                                {{ $item['Unit'] }}--}}
{{--                                            </span>--}}
{{--                                        </div>--}}
{{--                                        <div>إجمالي كمية المبيعات:--}}
{{--                                            <span style="color: #227dd7;">{{ number_format($item['TotalQuantitySale']) }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div>قسم:--}}
{{--                                            <span style="color: #227dd7">{{ __($item['mrkt_type']) }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div>المورد:--}}
{{--                                            <span style="color: #227dd7">--}}
{{--                                                {{ $item['VendorName'] }}--}}
{{--                                            </span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}

                                <td>
                                    <span>
                                        {{number_format($item['InventoryQuantity'])}}
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{number_format($item['BasePrice'],2)}}
                                    </span>
                                </td>
                                <td>
{{--                                    <span>--}}
{{--                                        {{number_format($item['Cost'],2)}}--}}
{{--                                    </span>--}}
                                </td>
                                <td>
                                    <span>
                                        {{number_format($item['InventoryCost'],2)}}
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{number_format($item['InventoryValue'],2)}}
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{number_format($item['AnnualQuantitySale'])}}
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        {{number_format($item['SufficiencyDays'])}}
                                    </span>
                                </td>

                            </tr>

{{--                            @dd($item)--}}
                            @foreach ($item['branches'] as $branch)
                                @php
                                    $branchIdJs = json_encode((string) $branch['BranchId']);
                                @endphp
                                <tr
                                    wire:key="branch-{{ $code }}-{{ $branch['BranchId'] }}"
                                    x-show="isItemExpanded({{ $itemCodeJs }})"
                                    x-cloak
{{--                                    x-transition--}}
                                    class="bg-white border-b"
                                >
                                    <td class="border p-1 whitespace-nowrap">
{{--                                        <button--}}
{{--                                            type="button"--}}
{{--                                            class="px-2 py-1 font-bold"--}}
{{--                                            @click.stop="toggleBranch({{ $itemCodeJs }}, {{ $branchIdJs }})"--}}
{{--                                        >--}}
{{--                                            <span x-text="isBranchExpanded({{ $itemCodeJs }}, {{ $branchIdJs }}) ? '-' : '+'"></span>--}}
{{--                                        </button>--}}
                                    </td>
                                    <td class="pl-8 p-2">{{ $branch['BranchName'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['InventoryQuantity'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['BasePrice'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['Cost'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['InventoryCost'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['InventoryValue'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['AnnualQuantitySale'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['SufficiencyDays'] }}</td>

                                </tr>


                            @endforeach
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr style="border-top: 2px solid black; background-color: #f3f4f6; font-weight: bold;">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap"></td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">الإجمالي</td>
                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['InventoryQuantity']) }}</td>
                            <td class="border p-2 whitespace-nowrap"></td>
                            <td class="border p-2 whitespace-nowrap"></td>
{{--                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['BasePrice'], 2) }}</td>--}}
{{--                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['Cost'], 2) }}</td>--}}
                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['InventoryCost'], 2) }}</td>
                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['InventoryValue'], 2) }}</td>
                            <td class="border p-2 whitespace-nowrap">{{ number_format($totals['AnnualQuantitySale']) }}</td>
                            <td class="border p-2 whitespace-nowrap">
{{--                                {{ $totals['SufficiencyDays'] !== null ? number_format($totals['SufficiencyDays']) : '' }}--}}
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                @endif
            </div>
        @endif

    </div>
</div>
