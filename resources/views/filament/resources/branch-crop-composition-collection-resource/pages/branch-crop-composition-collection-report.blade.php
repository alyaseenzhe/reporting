<x-filament::page>
{{--<x-filament::page :widget-data="$this->getWidgetData()">--}}
    <style>
        .branch-crop-report-table .crop-row > td {
            background-color: #dcfce7 !important;
            border-top: 1px solid #86efac;
            border-bottom: 1px solid #86efac;
        }

        .branch-crop-report-table .crop-row > td:first-child {
            border-left: 4px solid #16a34a;
        }

        .branch-crop-report-table .branch-row > td {
            background-color: #dbeafe !important;
            border-top: 1px solid #93c5fd;
            border-bottom: 1px solid #93c5fd;
        }

        .branch-crop-report-table .branch-row > td:first-child {
            border-left: 4px solid #2563eb;
        }

        .branch-crop-report-table .customer-row > td {
            background-color: #fef3c7 !important;
            border-top: 1px solid #fcd34d;
            border-bottom: 1px solid #fcd34d;
        }

        .branch-crop-report-table .customer-row > td:first-child {
            border-left: 4px solid #d97706;
        }

        .branch-crop-report-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .branch-crop-report-table thead th {
            position: sticky;
            top: 0;
            z-index: 20;
            background-color: rgb(249 250 251);
        }
    </style>

    <div
        wire:key="crop-composition-report-{{ md5(json_encode($this->filters)) }}"
        x-data="{
            expandedCrops: {},
            expandedBranches: {},
            showFilters: true,
            showCustomerModal: @entangle('isCustomerModalOpen'),
            toggleCrop(key) {
                this.expandedCrops[key] = ! this.expandedCrops[key];
            },
            toggleBranch(key) {
                this.expandedBranches[key] = ! this.expandedBranches[key];
            },
            toggleFilters() {
                this.showFilters = ! this.showFilters;
            },
            isCropExpanded(key) {
                return !! this.expandedCrops[key];
            },
            isBranchExpanded(key) {
                return !! this.expandedBranches[key];
            },
        }"
        class="space-y-6"
    >
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-700">الفلاتر</h3>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                    @click="toggleFilters()"
                    x-bind:aria-label="showFilters ? 'إغلاق الفلاتر' : 'فتح الفلاتر'"
                    x-bind:title="showFilters ? 'إغلاق الفلاتر' : 'فتح الفلاتر'"
                >
                    <svg x-show="showFilters" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <svg x-show="! showFilters" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <div x-show="showFilters" x-collapse class="border-t border-gray-100 p-4">
                {{ $this->form }}
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">المحاصيل</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($cropCount) }}</div>
            </div>

{{--            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">--}}
{{--                <div class="text-sm font-medium text-gray-500">عدد الفروع</div>--}}
{{--                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($branchCount) }}</div>--}}
{{--            </div>--}}

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">عدد العملاء</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($customerCount) }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">محموع المزارع (هـ)</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalFarmAreaHectares, 2) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">محموع أنواع الزراعة (هـ)</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalCultivationAreaHectares, 2) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">محموع التركيب المحصولي (هـ)</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalCropAreaHectares, 2) }}</div>
            </div>
{{--            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">--}}
{{--                <div class="text-sm font-medium text-gray-500">المساحة الإجمالية (هـ)</div>--}}
{{--                <div class="text-sm font-medium text-gray-500">محموع التركيب المحصولي (هـ)</div>--}}
{{--                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalAreaHectares, 2) }}</div>--}}
{{--            </div>--}}
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="max-h-[70vh] overflow-auto">
                <table class="branch-crop-report-table w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-16 px-4 py-3  text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                            <th class="px-4 py-3  text-right text-xs font-semibold uppercase tracking-wide text-gray-500">طبيعة المحصول</th>
                            <th class="px-4 py-3  text-right text-xs font-semibold uppercase tracking-wide text-gray-500">نوع المحصول</th>
{{--                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">الفرع</th>--}}
{{--                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">العميل</th>--}}
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">عدد العملاء</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">مساحة المزارع (هـ)</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">أنواع الزراعة (هـ)</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">التركيب المحصولي (هـ)</th>
{{--                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">التركيب المحصولي (هـ)</th>--}}
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($reportRows as $crop)
                            <tr class="crop-row">
                                <td class="px-4 py-3 align-top">
                                    @if (count($crop['branches']))
                                        <button
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-300 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                                            @click="toggleCrop('{{ $crop['key'] }}')"
                                        >
                                            <span x-show="! isCropExpanded('{{ $crop['key'] }}')">+</span>
                                            <span x-show="isCropExpanded('{{ $crop['key'] }}')" x-cloak>-</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $crop['crop_category'] }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $crop['crop_name'] }}</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-700">{{ number_format($crop['customers_count']) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($crop['total_farm_area_hectares'], 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($crop['total_cultivation_area_hectares'], 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($crop['total_crop_item_area_hectares'], 2) }}</td>
{{--                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($crop['total_area_hectares'], 2) }}</td>--}}
                            </tr>

                            @foreach ($crop['branches'] as $branch)
                                <tr
                                    x-bind:style="isCropExpanded('{{ $crop['key'] }}') ? 'display: table-row;' : 'display: none;'"
                                    x-cloak
                                    class="branch-row"
                                >
                                    <td></td>
                                    <td class="px-4 py-3 align-top">
                                        @if (count($branch['customers']))
                                            <button
                                                type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-300 text-sm font-semibold text-gray-700 transition hover:bg-white"
                                                @click.stop="toggleBranch('{{ $crop['key'] }}-{{ $branch['key'] }}')"
                                            >
                                                <span x-show="! isBranchExpanded('{{ $crop['key'] }}-{{ $branch['key'] }}')">+</span>
                                                <span x-show="isBranchExpanded('{{ $crop['key'] }}-{{ $branch['key'] }}')" x-cloak>-</span>
                                            </button>
                                        @endif
                                    </td>
{{--                                    <td class="px-4 py-3 pl-8 text-sm text-gray-400">-</td>--}}
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $branch['branch_name'] }}</td>
{{--                                    <td class="px-4 py-3 text-sm text-gray-400">-</td>--}}
{{--                                    <td class="px-4 py-3 text-sm text-gray-400">-</td>--}}
                                    <td class="px-4 py-3 text-right text-sm text-gray-700">{{ number_format($branch['customers_count']) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($branch['total_farm_area_hectares'], 2) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($branch['total_cultivation_area_hectares'], 2) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($branch['total_crop_item_area_hectares'], 2) }}</td>
{{--                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($branch['total_area_hectares'], 2) }}</td>--}}
                                </tr>

                                @foreach ($branch['customers'] as $customer)
                                    <tr
                                        x-bind:style="isCropExpanded('{{ $crop['key'] }}') && isBranchExpanded('{{ $crop['key'] }}-{{ $branch['key'] }}') ? 'display: table-row;' : 'display: none;'"
                                        x-cloak
                                        class="customer-row cursor-pointer transition hover:brightness-95"
                                        @if ($customer['collection_id'])
                                            wire:click="openCustomerModal({{ $customer['collection_id'] }})"
                                        @endif
                                    >
                                        <td class="px-4 py-3"></td>
                                        <td class="px-4 py-3 pl-12 text-sm text-gray-400"></td>
                                        <td class="px-4 py-3 text-sm text-gray-900 flex ">
                                            العميل:{{ ' ' . $customer['customer_code'] . ' - ' . $customer['customer_name'] . ' ' }}
                                            @if (($customer['type'] ?? null) === 'redistribution_customer' && filled($customer['sap_customer_code'] ?? null))
                                                <div class="mt-1 text-xs  px-6 text-gray-600">
                                                    المؤسسة: {{ $customer['sap_customer_code'] }}{{ filled($customer['sap_customer_name'] ?? null) ? ' - ' . $customer['sap_customer_name'] : '' }}
                                                </div>
                                            @endif
                                        </td>
{{--                                        <td class="px-4 py-3 text-sm text-gray-500"></td>--}}
                                        <td class="px-4 py-3 text-sm text-gray-700">المهندس المسؤول: {{ ' ' . $customer['engineer_name'] }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($customer['total_farm_area_hectares'], 2) }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($customer['total_cultivation_area_hectares'], 2) }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($customer['total_crop_item_area_hectares'], 2) }}</td>
{{--                                        <td class="px-4 py-3 text-right text-sm text-gray-400">-</td>--}}
{{--                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($customer['total_area_hectares'], 2) }}</td>--}}
                                    </tr>
                                @endforeach
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500">
                                    لا توجد بيانات عن المحصول متاحة لك ضمن صلاحيات الوصول الحالية
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            x-cloak
            x-show="showCustomerModal"
            x-transition.opacity
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/50 p-4"
            @keydown.escape.window="showCustomerModal = false; $wire.closeCustomerModal()"
            @click.self="showCustomerModal = false; $wire.closeCustomerModal()"
        >
            <div
                x-show="showCustomerModal"
                x-transition
                class="relative mx-auto my-8 flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                @click.stop
            >
                <div class="flex items-center justify-end border-b border-gray-200 px-4 py-3">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
                        @click="showCustomerModal = false; $wire.closeCustomerModal()"
                    >
                        عودة
                    </button>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto p-4">
                    {{ $this->customerViewForm }}
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
