<x-filament::page>
    <div
        x-data="{
            expandedCrops: {},
            expandedBranches: {},
            toggleCrop(key) {
                this.expandedCrops[key] = ! this.expandedCrops[key];
            },
            toggleBranch(key) {
                this.expandedBranches[key] = ! this.expandedBranches[key];
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
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            {{ $this->form }}
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">المحاصيل</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($cropCount) }}</div>
            </div>

{{--            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">--}}
{{--                <div class="text-sm font-medium text-gray-500">الفروع</div>--}}
{{--                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($branchCount) }}</div>--}}
{{--            </div>--}}

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">عدد العملاء</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($customerCount) }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-gray-500">المساحة الإجمالية (هكتار)</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalAreaHectares, 2) }}</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-16 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Expand</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">نوع المحصول</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">اسم المحصول</th>
{{--                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">رقم العميل</th>--}}
{{--                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">المهندس المسؤول</th>--}}
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">العملاء</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">مجموع المساحة (هكتار)</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($reportRows as $crop)
                            <tr class="bg-white">
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
{{--
--}}
                                <td class="px-4 py-3 text-right text-sm text-gray-700">{{ number_format($crop['customers_count']) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($crop['total_area_hectares'], 2) }}</td>
                            </tr>

                            @foreach ($crop['branches'] as $branch)
                                <tr
                                    x-show="isCropExpanded('{{ $crop['key'] }}')"
                                    x-cloak
                                    class="bg-gray-50"
                                >
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
                                    <td class="px-4 py-3 pl-8 text-sm text-gray-400">-</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $branch['branch_name'] }}</td>
{{--                                    <td class="px-4 py-3 text-sm text-gray-400">-</td>--}}
{{--                                    <td class="px-4 py-3 text-sm text-gray-400">-</td>--}}
                                    <td class="px-4 py-3 text-right text-sm text-gray-700">{{ number_format($branch['customers_count']) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($branch['total_area_hectares'], 2) }}</td>
                                </tr>

                                @foreach ($branch['customers'] as $customer)
                                    <tr
                                        x-show="isCropExpanded('{{ $crop['key'] }}') && isBranchExpanded('{{ $crop['key'] }}-{{ $branch['key'] }}')"
                                        x-cloak
                                        class="bg-white"
                                    >
                                        <td class="px-4 py-3"></td>
                                        <td class="px-4 py-3 pl-12 text-sm text-gray-400">-</td>
                                        <td class="px-4 py-3 text-sm text-gray-900"> العميل:{{' '.$customer['customer_code'] .' - '. $customer['customer_name'].' ' }}</td>
{{--                                        <td class="px-4 py-3 text-sm text-gray-500"></td>--}}
                                        <td class="px-4 py-3 text-sm text-gray-700"> المهندس المسؤول: {{ ' '.$customer['engineer_name'] }}</td>
{{--                                        <td class="px-4 py-3 text-right text-sm text-gray-400">-</td>--}}
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($customer['total_area_hectares'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">
                                    No crop composition data is available for the current access scope.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>
