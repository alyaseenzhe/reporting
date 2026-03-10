@section('title')
    23- ترتيب الفروع بكميات المبيعات
@stop
<div>

    <div x-data="{ container:true, itemSearch:false, advancedSearch:false,
    activeItem: null,
    toggleItem(itemCode) {
        this.activeItem = this.activeItem === itemCode ? null : itemCode;
    }
{{--showBranches: false,--}}
{{-- branches: @entangle('branches').defer,--}}
{{--    employees: @entangle('employees').defer,--}}
{{--        expanded: @entangle('expanded').defer,--}}

{{--    expandedItems: {},--}}

{{--    toggleItem(itemCode) {--}}
{{--        this.expandedItems[itemCode] = !this.expandedItems[itemCode]--}}

{{--        // load only once--}}
{{--        if (this.expandedItems[itemCode] && !this.branches[itemCode]) {--}}
{{--            $wire.loadBranches(itemCode)--}}
{{--        }--}}
{{--    },--}}
{{--    toggleEmployees(itemCode, branchId) {--}}
{{--    const key = itemCode + '-' + branchId;--}}
{{--    this.expandedEmployees[key] = !this.expandedEmployees[key];--}}

{{--    if (this.expandedEmployees[key]) {--}}
{{--        // load employees from Livewire--}}
{{--        $wire.loadEmployees(itemCode, branchId).then(() => {--}}
{{--            // assign employees to the branch locally so Alpine can render them--}}
{{--            const branch = this.branches[itemCode].find(b => b.BranchId === branchId);--}}
{{--            if (branch) {--}}
{{--                branch.employees = this.employees[itemCode]?.[branchId] ?? [];--}}
{{--            }--}}
{{--        });--}}
{{--    }--}}
{{--}--}}
        }"
         {{--     x-init="console.log('ALPINE employees = ', employees)"--}}
         {{--     x-on:livewire:update.window="showBranches = null;  showEmployees: null;"--}}
         x-cloak>


        @include('livewire.items.search')

        @if($show_msg)
            <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">

                @if(count($group_results) > 0)

                    <table  id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
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
                                <div class="text-sm" >النسبة%

                                </div>
                            </th>

                        </tr>
                        </thead>


                        {{--@dd($group_results)--}}
                        <tbody>
                        @foreach($group_results as $item)

                            @php
                                $code = $item['ItemCode'] ?? '';
                            @endphp
                            <tr  style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer " class="py-4"
                                 {{--                                 @click="activeItem === {{ json_encode($item['ItemCode']) }}--}}
                                 {{--                                 ? activeItem = null--}}
                                 {{--                                   : activeItem = {{ json_encode($item['ItemCode']) }}"--}}


                                 wire:key="item-{{ $code }}"
                                 class="bg-cyan-50 font-bold cursor-pointer border-b"
                                 x-data="{ code: {{ json_encode($item['ItemCode']) }}}"
                                 @click="toggleItem({{ $item['ItemCode']}})"

                                {{--                                 @click="activeItem = activeItem === {{ json_encode($item["ItemCode"]) }} ? null : {{ json_encode($item["ItemCode"]) }}"--}}
                            >

                                <td style=" border-left: 2px dashed #a8a8a8;" class="border p-1 whitespace-nowrap">
                                    {{--                                    @click="activeItem === {{ json_encode($item['ItemCode']) }}"--}}
                                    {{--                                    @click="activeItem = activeItem === {{ json_encode($item["ItemCode"]) }} ? null : {{ json_encode($item["ItemCode"]) }}">--}}
                                    {{--                                <span x-text="showBranches ? '-' : '+'"></span>--}}
                                    <span x-text="activeItem === {{$item['ItemCode']}} ? '-' : '+'"></span>

                                    {{--                                    @click="toggleItem('{{ addslashes($item['ItemCode']) }}')">--}}
                                    {{--                                    <span x-text="expanded['{{ $item['ItemCode'] }}'] ? '-' : '+'"></span>--}}
                                </td>
                                <td  style=" border-left: 2px dashed #a8a8a8;   word-wrap: break-word; " class="border p-1 "  class="p-1 font-bold ">
                                    <div class="flex flex-row justify-between mx-2 " style="width: 300px">

                                        {{$item['ItemCode']  .' - '.  $item['ItemName'] }}


                                    </div>
                                </td>


                                <td    style=" border-left: 2px dashed #a8a8a8;" class="border p-2 py-6 whitespace-nowrap" >
                                    <div class="flex flex-row justify-between mx-2">
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                    {{$item["Unit"]}}
                                                    </span>
                                        </div>
                                        <div>اجمالي المبيعات:
                                            <span style="color: #227dd7;">  {{number_format($item['TotalQuantitySale'])}} </span>
                                        </div>
                                        <div>قسم:
                                            <span style="color: #227dd7">{{__($item["mrkt_type"])}}</span>

                                        </div>
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{$item["ItemGroup"]}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td  style="border-left: 2px solid black;" class="border p-1 ">


                                    <div class="flex flex-row justify-between mx-2">

                                        <div>التميز:
                                            <span style="color: #227dd7">
                                                    {{$item['Speciality']}}
                                                    </span>
                                        </div>
                                        <div>المورد:
                                            <span style="color: #227dd7">
                                                    {{$item["VendorName"]}}
                                                    </span>
                                        </div>
                                    </div>

                            </tr>
                            @foreach ($item['branches'] as $branch)
                                <tr             wire:key="branch-{{ $code }}-{{ $branch['BranchId'] }}"
                                                x-show="activeItem === {{$item["ItemCode"]}}"
                                                x-transition class="bg-white border-b">
                                    {{--                                <tr x-show="activeItem === {{ json_encode($item["ItemCode"]) }}" class="bg-white border-b"  wire:key="item-{{ $item['ItemCode'] }}">--}}
                                    <td></td>
                                    <td class="pl-8 p-2 " >{{ $branch['BranchName'] }}</td>
                                    <td class="pl-8 p-2">{{ $branch['TotalQuantitySaleByBranch'] }}</td>
                                    <td class="pl-8 p-2">{{ number_format($branch['TotalSalesPer']) }}</td>
                                    {{--                                    <td>--}}
                                    {{--                                        <ul>--}}
                                    {{--                                            @foreach ($branch['employees'] as $emp)--}}
                                    {{--                                                <li>{{ $emp['EmployeeName'] }}: {{ $emp['Quantity'] }}</li>--}}
                                    {{--                                            @endforeach--}}
                                    {{--                                        </ul>--}}
                                    {{--                                    </td>--}}
                                </tr>
                        @endforeach
                        {{--                            <template x-if="expandedItems['{{ $item['ItemCode'] }}'] ">--}}
                        {{--                                <template--}}
                        {{--                                    x-for="branch in branches['{{ $item['ItemCode'] }}']"--}}
                        {{--                                    :key="branch.BranchId"--}}
                        {{--                                >--}}
                        {{--                                    <template x-if="branch.BranchId > 0 ">--}}
                        {{--                                        <tr class="bg-white border-b">--}}
                        {{--                                            <td class="cursor-pointer" @click="toggleEmployees('{{ $item['ItemCode'] }}', branch.BranchId)">--}}
                        {{--                                                <span x-text="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId] ? '-' : '+'"></span>--}}
                        {{--                                            </td>--}}
                        {{--                                            <td class="pl-8 p-2" >--}}
                        {{--                                                <span x-text="branch.BranchName"></span>--}}
                        {{--                                                <span x-show="branch.IsBestBranch == 'Y'"> ⭐ </span>--}}
                        {{--                                            </td>--}}
                        {{--                                            <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>--}}
                        {{--                                            <td class="p-2" x-text="Math.round(branch.TotalSalesPer)"></td>--}}
                        {{--                                            --}}{{--                                <td class="p-2" x-text="JSON.stringify(branch.employees)"></td>--}}
                        {{--                                        </tr>--}}

                        {{--                                        <tr><td>true</td></tr>--}}
                        {{--                                        <template x-if="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId]">--}}

                        {{--                                            <template--}}
                        {{--                                                x-for="emp in (branch.employees ?? [])"--}}
                        {{--                                                :key="emp.EmployeeCode"--}}
                        {{--                                            >--}}
                        {{--                                                <tr class="bg-gray-50">--}}
                        {{--                                                    <td></td>--}}
                        {{--                                                    <td class="pl-12 p-2" x-text="emp.EmployeeName"></td>--}}
                        {{--                                                    <td class="p-2" x-text="emp.Quantity"></td>--}}
                        {{--                                                    <td></td>--}}
                        {{--                                                </tr>--}}
                        {{--                                            </template>--}}
                        {{--                                        </template>--}}
                        {{--                                    </template>--}}
                        {{--                                </template>--}}


                        {{--                            </template>--}}

                        {{-- Branch Rows --}}
                        {{--                    <template x-if="$wire.expanded['{{ $item['ItemCode'] }}']">--}}
                        {{--                        <template--}}
                        {{--                            x-init="branches = $wire.branches['{{ $item['ItemCode'] }}'] ?? [],--}}
                        {{--                            console.log('Branches for branches['{{ $item['ItemCode'] }}'] {{ $item['ItemCode'] }}:', branches);"--}}
                        {{--                            x-for="branch in $wire.branches['{{ $item['ItemCode'] }}']"--}}
                        {{--                            :key="branch.BranchId"--}}
                        {{--                        >--}}
                        {{--                            <template x-if="branch.BranchId > 3">--}}
                        {{--                            <tr x-show="branch.BranchId > 3" class="bg-white border-b-2 border-gray-200">--}}
                        {{--                                <td class="p-2 " x-on:click="$wire.loadEmployees('{{ $item['ItemCode'] }}', branch.BranchId)">+</td>--}}
                        {{--                                <td class="pl-8 p-2" x-text="branch.BranchName"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.Employees"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalSalesPer"></td>--}}
                        {{--                            </tr>--}}
                        {{--                                <template x-for="employee in $wire.employees['{{ $item['ItemCode'] }}', branch.BranchId], console.log($wire.employees['{{ $item['ItemCode'] }}', branch.BranchId]) ">--}}
                        {{--                                    <td class="p-2" x-text="employee.EmployeeName"></td>--}}
                        {{--                                    <td class="p-2" x-text="employee.Quantity"></td>--}}
                        {{--                                </template>--}}
                        {{--                            </template>--}}
                        {{--                        </template>--}}
                        {{--                    </template>--}}

                        {{--                    <template x-if="$wire.expanded['{{ $item['ItemCode'] }}']">--}}
                        {{--                        <template--}}
                        {{--                            x-for="branch in ($wire.branches['{{ $item['ItemCode'] }}'] ?? [])"--}}
                        {{--                            :key="branch.BranchId"--}}
                        {{--                        >--}}
                        {{--                            <tr x-show="branch.BranchId > 3" class="bg-white border-b-2 border-gray-200">--}}
                        {{--                                <td class="p-2"--}}
                        {{--                                    @click="$wire.loadEmployees('{{ $item['ItemCode'] }}', branch.BranchId)">--}}
                        {{--                                    +--}}
                        {{--                                </td>--}}

                        {{--                                <td class="pl-8 p-2" x-text="branch.BranchName"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalSalesPer"></td>--}}
                        {{--                            </tr>--}}

                        {{--                            <!-- employees -->--}}
                        {{--                            <template--}}
                        {{--                                x-for="employee in ($wire.employees['{{ $item['ItemCode'] }}']?.[branch.BranchId] ?? [])"--}}
                        {{--                                :key="employee.EmployeeName"--}}
                        {{--                            >--}}
                        {{--                                <tr class="bg-gray-50">--}}
                        {{--                                    <td></td>--}}
                        {{--                                    <td class="pl-12 p-2" x-text="employee.EmployeeName"></td>--}}
                        {{--                                    <td class="p-2" x-text="employee.Quantity"></td>--}}
                        {{--                                    <td></td>--}}
                        {{--                                </tr>--}}
                        {{--                            </template>--}}
                        {{--                        </template>--}}
                        {{--                    </template>--}}


                        {{--                    <template x-if="$wire.expanded['{{ $item['ItemCode'] }}']">--}}
                        {{--                        <template--}}
                        {{--                            x-for="branch in ($wire.branches['{{ $item['ItemCode'] }}'] ?? [])"--}}
                        {{--                            :key="branch.BranchId"--}}
                        {{--                        >--}}
                        {{--                            <tr x-show="branch.BranchId > 3" class="bg-white border-b-2 border-gray-200">--}}
                        {{--                                <td class="p-2"--}}
                        {{--                                    @click="$wire.loadEmployees('{{ $item['ItemCode'] }}', branch.BranchId)">--}}
                        {{--                                    +--}}
                        {{--                                </td>--}}

                        {{--                                <td class="pl-8 p-2" x-text="branch.BranchName"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>--}}
                        {{--                                <td class="p-2" x-text="branch.TotalSalesPer"></td>--}}
                        {{--                            </tr>--}}

                        {{--                            <!-- employees -->--}}
                        {{--                            <template--}}
                        {{--                                x-for="employee in ($wire.employees['{{ $item['ItemCode'] }}']?.[branch.BranchId] ?? [])"--}}
                        {{--                                :key="employee.EmployeeName"--}}
                        {{--                            >--}}
                        {{--                                <tr class="bg-gray-50">--}}
                        {{--                                    <td></td>--}}
                        {{--                                    <td class="pl-12 p-2" x-text="employee.EmployeeName"></td>--}}
                        {{--                                    <td class="p-2" x-text="employee.Quantity"></td>--}}
                        {{--                                    <td></td>--}}
                        {{--                                </tr>--}}
                        {{--                            </template>--}}
                        {{--                        </template>--}}
                        {{--                    </template>--}}




                        {{--                    <template--}}
                        {{--                        x-for="branch in (branches && branches['{{ $item['ItemCode'] }}'] ? branches['{{ $item['ItemCode'] }}'] : [])"--}}
                        {{--                        :key="branch.BranchId"--}}
                        {{--                    >--}}
                        {{--                        <tr--}}
                        {{--                            x-show="expanded['{{ $item['ItemCode'] }}'] && branch.BranchId >= 3"--}}
                        {{--                            class="bg-white border-b"--}}
                        {{--                        >--}}
                        {{--                            <td--}}
                        {{--                                class="p-2 cursor-pointer"--}}
                        {{--                                @click="$wire.loadEmployees('{{ $item['ItemCode'] }}', branch.BranchId)"--}}
                        {{--                            >--}}
                        {{--                                +--}}
                        {{--                            </td>--}}

                        {{--                            <td--}}
                        {{--                                class="p-2 cursor-pointer"--}}
                        {{--                                @click="toggleEmployees('{{ $item['ItemCode'] }}', branch.BranchId)"--}}
                        {{--                            >--}}
                        {{--    <span--}}
                        {{--        x-text="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId] ? '-' : '+'">--}}
                        {{--    </span>--}}
                        {{--                            </td>--}}
                        {{--                            @click="--}}
                        {{--                            $wire.loadEmployees('{{ $item['ItemCode'] }}', branch.BranchId);--}}
                        {{--                            expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId] =--}}
                        {{--                            !expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId];--}}
                        {{--                            "--}}
                        {{--                            >--}}
                        {{--                            <span--}}
                        {{--                                x-text="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId] ? '-' : '+'">--}}
                        {{--    </span>--}}
                        {{--                            </td>--}}

                        {{--                            <td class="pl-8 p-2" x-text="branch.BranchName"></td>--}}
                        {{--                            <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>--}}
                        {{--                            <td class="p-2" x-text="Math.round(branch.TotalSalesPer)"></td>--}}
                        {{--                        </tr>--}}


                        {{--                        <template--}}
                        {{--                            x-if="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId]"--}}
                        {{--                        >--}}
                        {{--                            <template--}}
                        {{--                                x-for="emp in (employees['{{ $item['ItemCode'] }}']?.[branch.BranchId] ?? [])"--}}
                        {{--                                :key="emp.EmployeeCode"--}}
                        {{--                            >--}}
                        {{--                                <tr class="bg-gray-50">--}}
                        {{--                                    <td></td>--}}
                        {{--                                    <td class="pl-12 p-2" x-text="emp.EmployeeName"></td>--}}
                        {{--                                    <td class="p-2" x-text="emp.Quantity"></td>--}}
                        {{--                                    <td></td>--}}
                        {{--                                </tr>--}}
                        {{--                            </template>--}}
                        {{--                        </template>--}}

                        {{--                        <template--}}
                        {{--                            x-if="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId]"--}}
                        {{--                        >--}}
                        {{--                            <template--}}
                        {{--                                x-for="emp in ($wire.employees['{{ $item['ItemCode'] }}']?.[branch.BranchId] ?? [])"--}}
                        {{--                                :key="emp.EmployeeCode"--}}
                        {{--                            >--}}
                        {{--                                <tr class="bg-gray-50">--}}
                        {{--                                    <td></td>--}}
                        {{--                                    <td class="pl-12 p-2" x-text="emp.EmployeeName"></td>--}}
                        {{--                                    <td class="p-2" x-text="emp.Quantity"></td>--}}
                        {{--                                    <td></td>--}}
                        {{--                                </tr>--}}
                        {{--                            </template>--}}
                        {{--                        </template>--}}


                        {{--                        --}}{{--                        <template x-if="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId]">--}}


                        {{--                        </template>--}}
                        {{--                        <tr>--}}
                        {{--                            <td colspan="5" x-text="JSON.stringify(employees['{{ $item['ItemCode'] }}']?.[branch.BranchId])"></td>--}}
                        {{--                        </tr>--}}
                        {{--                    </template>--}}


                        @endforeach


                    </table>


                @endif
                @endif
            </div>
            {{--        {{ $items->links() }}--}}

    </div>
</div>
