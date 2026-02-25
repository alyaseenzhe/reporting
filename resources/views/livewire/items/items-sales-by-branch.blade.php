@section('title')
    23- تقرير كمية مبيعات الأصناف بالفرع
@stop
<div>

    <div x-data="{ container:true, itemSearch:false, advancedSearch:false,
 branches: @entangle('branches').defer,
    employees: @entangle('employees').defer,
        expanded: @entangle('expanded').defer,

    expandedItems: {},

    toggleItem(itemCode) {
        this.expandedItems[itemCode] = !this.expandedItems[itemCode]

        // load only once
        if (this.expandedItems[itemCode] && !this.branches[itemCode]) {
            $wire.loadBranches(itemCode)
        }
    },
    toggleEmployees(itemCode, branchId) {
    const key = itemCode + '-' + branchId;
    this.expandedEmployees[key] = !this.expandedEmployees[key];

    if (this.expandedEmployees[key]) {
        // load employees from Livewire
        $wire.loadEmployees(itemCode, branchId).then(() => {
            // assign employees to the branch locally so Alpine can render them
            const branch = this.branches[itemCode].find(b => b.BranchId === branchId);
            if (branch) {
                branch.employees = this.employees[itemCode]?.[branchId] ?? [];
            }
        });
    }
}
        }"
         {{--     x-init="console.log('ALPINE employees = ', employees)"--}}
         {{--     x-on:livewire:update.window="showBranches = null;  showEmployees: null;"--}}
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
                                <div class="text-sm" >النسبة%

                                </div>
                            </th>

                        </tr>
                        </thead>


                        {{--@dd($group_results)--}}
                        <tbody>
                        @foreach($group_results as $item)
                            {{-- Item Row --}}
                            {{--                    <tr--}}
                            {{--                        class="bg-orange-50 cursor-pointer hover:bg-orange-100"--}}

                            {{--                    >--}}
                            {{--                        <td colspan="5" class="p-2 font-bold">{{$item['ItemCode']  .' - '.  $item['ItemName'] }}</td>--}}
                            {{--                        <td class="p-2">{{ $item['VendorName'] }}</td>--}}
                            {{--                        <td class="p-2">{{ $item['CardName'] }}</td>--}}
                            {{--                        <td class="p-2">{{ number_format($item['TotalQuantitySale']) ?? 0 }}</td>--}}
                            {{--                    </tr>--}}
                            {{--                    <tr  style=" background-color: #e4fbff; font-weight: bold; cursor: pointer">--}}
                            {{--                        <td colspan="4" style=" border-left: 2px dashed #a8a8a8;" class="border p-1 whitespace-nowrap"  class="p-1 font-bold ">--}}
                            {{--                            {{$item['ItemCode']  .' - '.  $item['ItemName'] }}</td>--}}
                            {{--                    </tr>--}}
                            <tr  style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer " class="py-4">
                                {{--                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $item["ItemCode"] }}" x-text="showBranches ? '-' : '+'">+</td>--}}
                                {{--                        <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $item["ItemCode"] }}" x-text="showBranches === '{{ $item['ItemCode'] }}' ? '-' : '+'">+</td>--}}
                                <td style=" border-left: 2px dashed #a8a8a8;" class="border p-1 whitespace-nowrap"
                                    {{--                            @click="$wire.loadBranches('{{ addslashes($item['ItemCode']) }}')"--}}

                                    @click="toggleItem('{{ addslashes($item['ItemCode']) }}')">
                                    <span x-text="expanded['{{ $item['ItemCode'] }}'] ? '-' : '+'"></span>
                                </td>
                                <td  style=" border-left: 2px dashed #a8a8a8;   word-wrap: break-word; " class="border p-1 "  class="p-1 font-bold ">
                                    <div class="flex flex-row justify-between mx-2">

                                    {{$item['ItemCode']  .' - '.  $item['ItemName'] }}

                                    <div>الوحدة:
                                        <span style="color: #227dd7">
                                                    {{$item["Unit"]}}
                                                    </span>
                                    </div>
                                    </div>
                                </td>

                                <td    style=" border-left: 2px dashed #a8a8a8;" class="border p-2 py-6 whitespace-nowrap" >
                                    <div class="flex flex-row justify-between mx-2">
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
                                <td  style="border-left: 2px solid black;" class="border p-1 whitespace-nowrap">


                                    <div class="flex flex-row justify-between mx-2">
                                        {{--                                <div>قسم:--}}
                                        {{--                                    <span style="color: #227dd7">{{__($item["mrkt_type"])}}</span>--}}

                                        {{--                                </div>--}}
                                        {{--                                <div>نوع المادة:--}}
                                        {{--                                    <span style="color: #227dd7">{{$item["ItemGroup"]}}</span>--}}
                                        {{--                                </div>--}}
{{--                                        <div>الوحدة:--}}
{{--                                            <span style="color: #227dd7">--}}
{{--                                                    {{$item["Unit"]}}--}}
{{--                                                    </span>--}}
{{--                                        </div>--}}
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
                            {{--                    <tr  style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer"  >--}}
                            {{--                        --}}{{--                                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["ItemCode"] }}">+</td>--}}
                            {{--                        <td colspan="5"  style=" border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap"--}}
                            {{--                            @click="$wire.loadBranches('{{ addslashes($item['ItemCode']) }}')"--}}

                            {{--                            @click="toggleItem('{{ addslashes($item['ItemCode']) }}')">--}}
                            {{--                            <span x-text="expanded['{{ $item['ItemCode'] }}'] ? '-' : '+'"></span>--}}
                            {{--                        </td>--}}

                            {{--                        <td style=" border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            <div class="flex flex-row justify-between">--}}
                            {{--                                <div>اجمالي المبيعات:--}}
                            {{--                                    <span style="color: #227dd7;">  {{number_format($item['TotalQuantitySale'])}} </span>--}}
                            {{--                                </div>--}}

                            {{--                            </div>--}}
                            {{--                            --}}{{--                    {{number_format($totalSalesByItem[$record["ItemCode"]][4])}}--}}
                            {{--                            --}}{{--                    {{$record['IsBestBranch']}}--}}

                            {{--                        </td>--}}
                            {{--                        <td style=" border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">--}}
                            {{--                            <div class="flex flex-row justify-between">--}}
                            {{--                                <div>الفرع الاكثر بيعا:--}}
                            {{--                            <span x-text="' {{ $item['bestBranch']['BPLName'] ?? '-' }}'"></span>--}}
                            {{--                                </div>--}}
                            {{--                            @foreach($item['branches'] as $bindex =>$branch)--}}

                            {{--                                @if($branch['IsBestBranch'] == 'Y')--}}
                            {{--                                    <div class="flex flex-row justify-between">--}}
                            {{--                                        <div>الفرع الأكثر مبيعا:--}}
                            {{--                                            <span style="color: #227dd7;" class="pl-4">      {{ $branch['BranchName'] }}</span>--}}
                            {{--                                            الكمية المباعة:--}}
                            {{--                                            <span style="color: #227dd7;"> {{ $branch['TotalQuantitySaleByBranch'] }}</span>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                @endif--}}
                            {{--                            @endforeach--}}

                            {{--                        </td>--}}
                            {{--                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                            {{--                            الكمية التي باعها:--}}
                            {{--                            --}}
                            {{--                            @if(isset($item['bestBranch']['TotalQuantitySaleByBranch']))--}}
                            {{--                            <span x-text="' {{ number_format($item['bestBranch']['TotalQuantitySaleByBranch']) ?? '-' }}'"></span>--}}
                            {{--                            @endif--}}
                            {{--                            --}}{{--                    {{number_format($totalSalesByItem[$record["ItemCode"]][0], 2)}}--}}



                            {{--                        </td>--}}
                            {{--                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                            {{--                            --}}{{--                    {{ $totalSalesByItem[$record["ItemCode"]][3] != 0? number_format($totalSalesByItem[$record["ItemCode"]][0]/$totalSalesByItem[$record["ItemCode"]][3], 2) : 0 }}--}}
                            {{--                        </td>--}}
                            {{--                        --}}{{--                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                            {{--                        --}}{{--                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["ItemCode"]][1], 2)}}</td>--}}
                            {{--                        --}}{{--                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["ItemCode"]][2], 2)}}</td>--}}
                            {{--                        --}}{{--                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["ItemCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["ItemCode"]][2]/$totalSalesByItem[$record["ItemCode"]][0])*100, 2)}}</td>--}}
                            {{--                        --}}{{--                @endif--}}
                            {{--                    </tr>--}}




                            <template x-if="expandedItems['{{ $item['ItemCode'] }}'] ">
                                <template
                                    x-for="branch in branches['{{ $item['ItemCode'] }}']"
                                    :key="branch.BranchId"
                                >
                                    <template x-if="branch.BranchId > 0 ">
                                        <tr class="bg-white border-b">
                                            <td class="cursor-pointer" @click="toggleEmployees('{{ $item['ItemCode'] }}', branch.BranchId)">
                                                <span x-text="expandedEmployees['{{ $item['ItemCode'] }}-' + branch.BranchId] ? '-' : '+'"></span>
                                            </td>
                                            <td class="pl-8 p-2" >
                                                <span x-text="branch.BranchName"></span>
                                                <span x-show="branch.IsBestBranch == 'Y'"> ⭐ </span>
                                            </td>
                                            <td class="p-2" x-text="branch.TotalQuantitySaleByBranch"></td>
                                            <td class="p-2" x-text="Math.round(branch.TotalSalesPer)"></td>
                                            {{--                                <td class="p-2" x-text="JSON.stringify(branch.employees)"></td>--}}
                                        </tr>

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
                                    </template>
                                </template>


                            </template>

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

                        {{--                @foreach($group_results as $item)--}}
                        {{--                    --}}{{-- Item header --}}
                        {{--                    @if(!isset($currentGroup) || $currentGroup != $item["ItemCode"])--}}
                        {{--                        @php $currentGroup = $item["ItemCode"]; @endphp--}}
                        {{--                        <tr style="background-color: #faebd7; font-weight: bold; color: red;">--}}
                        {{--                            <td colspan="8" class="border p-2 whitespace-nowrap">--}}
                        {{--                                <div class="flex flex-row">--}}
                        {{--                                    <div>({{ $item["ItemCode"] }}) - {{ $item["ItemName"] }}</div>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                    @endif--}}

                        {{--                    --}}{{-- Clickable row to load branches --}}
                        {{--                    <tr wire:click="loadBranches('{{ $item['ItemCode'] }}')" style="cursor: pointer;">--}}
                        {{--                        <td>{{ $item['ItemName'] }}</td>--}}
                        {{--                    </tr>--}}

                        {{--                    --}}{{-- Branch rows --}}
                        {{--                    @if(isset($branches[$item['ItemCode']]))--}}
                        {{--                        @foreach($branches[$item['ItemCode']] as $branch)--}}
                        {{--                            <tr>--}}
                        {{--                                <td>{{ $branch['BranchName'] ?? 'N/A' }}</td>--}}
                        {{--                            </tr>--}}
                        {{--            @endforeach--}}
                        {{--        @endif--}}
                        {{--        @endforeach--}}
                    </table>

                    {{--            @php--}}
                    {{--                $pages = ceil(100 / 10);--}}
                    {{--            @endphp--}}

                    {{--            <nav>--}}
                    {{--                <ul class="pagination">--}}
                    {{--                    @for ($i = 1; $i <= $pages; $i++)--}}
                    {{--                        <li class="page-item {{ $page == $i ? 'active' : '' }}">--}}
                    {{--                            <a class="page-link" wire:click="gotoPage({{ $i }})">--}}
                    {{--                                {{ $i }}--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endfor--}}
                    {{--                </ul>--}}
                    {{--            </nav>--}}
                @endif
                @endif
            </div>
            {{--        {{ $items->links() }}--}}

    </div>
</div>

{{--@include('livewire.items.alpineJs')--}}

{{--<style>--}}
{{--    .select2-selection__rendered {--}}
{{--        line-height: 31px !important;--}}
{{--    }--}}
{{--    .select2-container .select2-selection--single {--}}
{{--        height: 38px !important;--}}
{{--        width: 100%;--}}
{{--        padding-right: 2.5rem;--}}
{{--        padding-top: 0.2rem;--}}
{{--    }--}}
{{--    .select2-selection__arrow {--}}
{{--        height: 34px !important;--}}
{{--    }--}}

{{--    .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {--}}
{{--        /* left: 1px; */--}}
{{--        right: 9px;--}}
{{--    }--}}

{{--    .select-font-size {--}}
{{--        font-size: 0.875rem; /* 14px */--}}
{{--        line-height: 1.25rem; /* 20px */--}}
{{--    }--}}

{{--    /*.hide {*/--}}
{{--    /*    display: none;*/--}}
{{--    /*}*/--}}

{{--    .record-row { opacity: 1; transform: translateY(0); transition: opacity 0.5s ease, transform 0.5s ease; }--}}
{{--    .record-row.hide-row {--}}
{{--        opacity: 0; transform: translateY(-20px); /* Adjust vertical movement if needed */--}}
{{--    }--}}

{{--    #report-logo {--}}
{{--        display: none;--}}
{{--    }--}}

{{--    .tbl-fixed {--}}
{{--        overflow-x: scroll;--}}
{{--        overflow-y: scroll;--}}
{{--        height: fit-content;--}}
{{--        max-height: 70vh;--}}
{{--    }--}}

{{--    table th {--}}
{{--        position: sticky;--}}
{{--        top: 0px;--}}
{{--        background: #f8fafc;--}}
{{--        border: 2px solid black;--}}
{{--    }--}}




{{--    /* Style the button that is used to open and close the collapsible content */--}}
{{--    .collapsible {--}}
{{--        background-color: #eee;--}}
{{--        color: #444;--}}
{{--        cursor: pointer;--}}
{{--        padding: 5px;--}}
{{--        width: 100%;--}}
{{--        border: none;--}}
{{--        /*text-align: left;*/--}}
{{--        outline: none;--}}
{{--        font-size: 15px;--}}
{{--    }--}}

{{--    /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */--}}
{{--    .active, .collapsible:hover {--}}
{{--        background-color: #ccc;--}}
{{--    }--}}

{{--    /* Style the collapsible content. Note: hidden by default */--}}
{{--    #branch-container {--}}
{{--        padding: 18px 18px;--}}
{{--        display: block;--}}
{{--        /*overflow: hidden;*/--}}
{{--        background-color: #f1f1f1;--}}
{{--    }--}}

{{--    .collapsible:after {--}}
{{--        content: '\02795'; /* Unicode character for "plus" sign (+) */--}}
{{--        font-size: 13px;--}}
{{--        color: white;--}}
{{--        float: left;--}}
{{--        margin-left: 5px;--}}
{{--    }--}}

{{--    button.active:after {--}}
{{--        content: "\2796"; /* Unicode character for "minus" sign (-) */--}}
{{--</style>--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>--}}
{{--    <script>--}}
{{--        document.querySelectorAll('[data-branch]')--}}
{{--    </script>--}}
{{--</div>--}}
