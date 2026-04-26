@section('title')
    23- تقرير الأصناف
@stop
<div>
    <div x-data="{ container:true, itemSearch:false, advancedSearch:false }" x-cloak>
        @include('livewire.items.list-items-search')

        @if($show_msg)
            <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">
                @if(count($group_results) > 0)
                    <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                        <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                        <tr style="border: 2px solid black;">
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">كود الصنف</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الوصف</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الوحدة</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">التمييز</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">المورد</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الإدارة / القسم</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($group_results as $item)
                            <tr style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold;">
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2 whitespace-nowrap">{{ $item['ItemCode'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['ItemName'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['UomCode'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2 whitespace-nowrap">{{ $item['Speciality'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['VendorName'] }}</td>
                                <td style="border-left: 2px solid black;" class="border p-2">{{ __($item['mrkt_type']) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-4 text-center font-bold">لا توجد أصناف مطابقة للفلاتر المحددة</div>
                @endif
            </div>
        @endif
    </div>
</div>
