@section('title')
    23- ترتيب الفروع بكميات المبيعات
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

        @include('livewire.items.search')

        @if($show_msg)
            <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">

                @if(count($group_results) > 0)

                    <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                        <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                        <tr style="border: 2px solid black;">
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm"></div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الفرع</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">عدد العمليات</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كمية المبيعات</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">النسبة %</div>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($group_results as $item)

                            @php
                                $code = $item['ItemCode'] ?? '';
                                $itemCodeJs = json_encode((string) $code);
                            @endphp
                            <tr
                                style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer"
                                wire:key="item-{{ $code }}"
                                @click="toggleItem({{ $itemCodeJs }})"
                            >
                                <td style="border-left: 2px dashed #a8a8a8;" class="border p-1 whitespace-nowrap">
                                    <span x-text="isItemExpanded({{ $itemCodeJs }}) ? '-' : '+'"></span>
                                </td>
                                <td style="border-left: 2px dashed #a8a8a8; word-wrap: break-word;" class="border p-1">
                                    <div class="flex flex-row justify-between mx-2" style="width: 300px">
                                        {{ $item['ItemCode'] . ' - ' . $item['ItemName'] }}
                                    </div>
                                </td>
                                <td style="border-left: 2px dashed #a8a8a8;" class="border p-2 py-6 whitespace-nowrap">
                                    <div class="flex flex-row justify-between mx-2">
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                {{ $item['Unit'] }}
                                            </span>
                                        </div>
                                        <div>إجمالي كمية المبيعات:
                                            <span style="color: #227dd7;">{{ number_format($item['TotalQuantitySale']) }}</span>
                                        </div>
                                        <div>قسم:
                                            <span style="color: #227dd7">{{ __($item['mrkt_type']) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-1" colspan="2">
                                    <div class="flex flex-row justify-between mx-2">
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{ $item['ItemGroup'] }}</span>
                                        </div>
                                        <div>التمييز:
                                            <span style="color: #227dd7">
                                                {{ $item['Speciality'] }}
                                            </span>
                                        </div>
                                        <div>المورد:
                                            <span style="color: #227dd7">
                                                {{ $item['VendorName'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($item['branches'] as $branch)
                                @php
                                    $branchIdJs = json_encode((string) $branch['BranchId']);
                                @endphp
                                <tr
                                    wire:key="branch-{{ $code }}-{{ $branch['BranchId'] }}"
                                    x-show="isItemExpanded({{ $itemCodeJs }})"
                                    x-transition
                                    class="bg-white border-b"
                                >
                                    <td class="border p-1 whitespace-nowrap">
                                        <button
                                            type="button"
                                            class="px-2 py-1 font-bold"
                                            @click.stop="toggleBranch({{ $itemCodeJs }}, {{ $branchIdJs }})"
                                        >
                                            <span x-text="isBranchExpanded({{ $itemCodeJs }}, {{ $branchIdJs }}) ? '-' : '+'"></span>
                                        </button>
                                    </td>
                                    <td class="pl-8 p-2">{{ $branch['BranchName'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['TransCount'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['TotalQuantitySaleByBranch'] }}</td>
                                    <td class="pl-8 p-2">{{ number_format($branch['TotalSalesPer']) }}</td>
                                </tr>

                                @foreach ($branch['employees'] as $emp)
                                    <tr
                                        wire:key="branch-{{ $code }}-{{ $branch['BranchId'] }}-{{ $emp['EmployeeCode'] }}"
                                        x-show="isItemExpanded({{ $itemCodeJs }}) && isBranchExpanded({{ $itemCodeJs }}, {{ $branchIdJs }})"
                                        x-transition
                                        class="bg-gray-200 border-b border-white"
                                    >
                                        <td></td>
                                        <td>{{ $emp['EmployeeCode'] }} - {{ $emp['EmployeeName'] }}</td>
                                        <td class="pl-8 p-2">{{ $emp['TransCount'] }}</td>
                                        <td class="pl-8 p-2">{{ $emp['Quantity'] }}</td>
                                        <td class="pl-8 p-2">{{ $branch['TotalQuantitySaleByBranch'] >0 ? number_format($emp['Quantity']/$branch['TotalQuantitySaleByBranch'] *100) : 0}}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                        @endforeach
                        </tbody>
                    </table>

                @endif
            </div>
        @endif

    </div>
</div>
