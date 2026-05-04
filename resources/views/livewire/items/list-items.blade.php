@section('title')
    24- قائمةالأصناف
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
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">رقم صنف المورد</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="text-sm">الحالة</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @dd($group_results)--}}
                        @foreach($group_results as $item)
                            <tr style="border-bottom: 2px solid #a8a8a8; background-color: #e4fbff; font-weight: bold;">
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2 whitespace-nowrap">{{ $item['ItemCode'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['ItemName'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['UomCode'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2 whitespace-nowrap">{{ $item['Speciality'] }}</td>
                                <td style="border-left: 2px solid #a8a8a8;" class="border p-2">{{ $item['VendorName'] }}</td>
                                <td style="border-left: 2px solid black;" class="border p-2">{{ __($item['mrkt_type']) }}</td>
                                <td style="border-left: 2px solid black;" class="border p-2">{{ __($item['CatalogNumber']) }}</td>
                                <td style="border-left: 2px solid black;" class="border p-2">{{-- __($item['validFor']) --}}
                                @if($item['validFor'] == 'Y')
                                    نشط
                                    @else
                                    غير نشط
                                @endif

                                </td>


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

@php
    $allowedMarketingTypeOptions = method_exists($this, 'marketingTypeOptions')
        ? $this->marketingTypeOptions()
        : [];
    $defaultMarketingTypeSelection = $this->marketing_type ?? ['marketing_all'];
@endphp

<script>
    (function () {
        const allowedMarketingTypeOptions = @json($allowedMarketingTypeOptions);
        const defaultMarketingTypeSelection = @json($defaultMarketingTypeSelection);

        function findMarketingTypeSelect() {
            return Array.from(document.querySelectorAll('select')).find((select) => {
                return select.querySelector('option[value="marketing_all"]')
                    && select.querySelector('option[value="30"]')
                    && select.closest('[wire\\:ignore]');
            });
        }

        function syncMarketingTypeOptions() {
            const select = findMarketingTypeSelect();

            if (!select) {
                return;
            }

            const selectedValues = Array.isArray(defaultMarketingTypeSelection) && defaultMarketingTypeSelection.length
                ? defaultMarketingTypeSelection
                : ['marketing_all'];

            if (select.tomselect) {
                select.tomselect.clearOptions();
                select.tomselect.addOption({value: 'marketing_all', text: 'الكل', all_option: 'true'});

                Object.entries(allowedMarketingTypeOptions).forEach(([value, label]) => {
                    select.tomselect.addOption({value, text: label});
                });

                select.tomselect.refreshOptions(false);
                select.tomselect.setValue(selectedValues, true);
                return;
            }

            select.innerHTML = '';

            const allOption = new Option('الكل', 'marketing_all', false, selectedValues.includes('marketing_all'));
            allOption.setAttribute('all_option', 'true');
            select.appendChild(allOption);

            Object.entries(allowedMarketingTypeOptions).forEach(([value, label]) => {
                const option = new Option(label, value, false, selectedValues.includes(String(value)));
                select.appendChild(option);
            });
        }

        document.addEventListener('DOMContentLoaded', syncMarketingTypeOptions);
        document.addEventListener('livewire:load', syncMarketingTypeOptions);

        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('message.processed', () => {
                syncMarketingTypeOptions();
            });
        }
    })();
</script>
