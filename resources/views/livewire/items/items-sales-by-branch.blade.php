@section('title')
    23- ترتيب الفروع بكميات المبيعات
@stop
<div>

    <div x-data="{ container:true, itemSearch:false, advancedSearch:false,
    activeItem: null,
    toggleItem(itemCode) {
        this.activeItem = this.activeItem === itemCode ? null : itemCode;
    }

        }"

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
                                <div class="text-sm">اجمالي العمليات</div>
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

                                    </div>
                                </td>
                                <td  style="border-left: 2px solid black;" class="border p-1 " colspan="2">


                                    <div class="flex flex-row justify-between mx-2">
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{$item["ItemGroup"]}}</span>
                                        </div>
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
                                    <td class="pl-8 p-2">{{ $branch['TransCount'] }}</td>

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


                        @endforeach


                    </table>


                @endif
                @endif
            </div>
            {{--        {{ $items->links() }}--}}

    </div>
</div>
