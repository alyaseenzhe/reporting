<x-filament::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-white via-slate-50 to-emerald-50 p-6 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        تتبع التسوية
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $requestRecord['type'] ?? 'Request' }}</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            رقم الطلب #{{ $requestRecord['id'] }} تم انشاءه بواسطة {{ $requestRecord['user_name'] ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center shadow-sm">
                        <div class="text-xs font-medium text-slate-500">المجموع</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalCount }}</div>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-xs font-medium text-emerald-700">المكتمل</div>
                        <div class="mt-1 text-2xl font-bold text-emerald-700">{{ $doneCount }}</div>
                    </div>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-xs font-medium text-amber-700">تحت الإجراء</div>
                        <div class="mt-1 text-2xl font-bold text-amber-700">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>

{{--            <div class="mt-6">--}}
{{--                <div class="mb-2 flex items-center justify-between text-sm">--}}
{{--                    <span class="font-medium text-slate-600">Progress</span>--}}
{{--                    <span class="font-semibold text-slate-900">{{ $progress }}%</span>--}}
{{--                </div>--}}
{{--                <div class="h-3 overflow-hidden rounded-full bg-slate-200">--}}
{{--                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300" style="width: {{ $progress }}%;"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex items-center justify-between gap-3 text-sm font-semibold text-slate-600">
                    <div>المرفقات</div>
                    <div class="text-xs text-slate-500">{{ $attachmentCollection }}</div>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">رفع مرفق</div>
{{--                            <p class="mt-1 text-sm text-slate-500">اختر الملف ثم اضغط زر الحفظ</p>--}}
                        </div>
                        <button type="submit" form="attachment-upload-form" class="inline-flex h-12 items-center justify-center rounded-xl bg-emerald-600 bg-sky-700 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-60" wire:loading.attr="disabled">
                            حفظ الملف
                        </button>
                    </div>

                    <form id="attachment-upload-form" wire:submit.prevent="uploadAttachment" class="mt-6 space-y-3">
                        <label class="block">
                            <span class="text-sm font-medium text-slate-700">اختر ملف</span>
                            <input type="file" wire:model="attachment" class="mt-2 block w-full rounded-lg border border-slate-300 bg-white p-2 text-sm text-slate-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </label>
                        @error('attachment')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            @if (! empty($attachments))
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="text-sm font-semibold text-slate-700">الملف الحالي</div>
                    <div class="mt-3 space-y-3">
                        @foreach ($attachments as $attachment)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3">
                                <div class="truncate text-sm font-medium text-slate-900">{{ $attachment['name'] }}</div>
                                <a href="{{ $attachment['url'] }}" target="_blank" class="text-primary-600 underline">فتح</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if (empty($settlements))
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                <div class="text-lg font-semibold text-slate-800">لا توجد ملفات حالية</div>
{{--                <p class="mt-2 text-sm text-slate-500">This page uses the same department permission rules as the old settlement manager.</p>--}}
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="grid grid-cols-[1fr_auto_auto] items-center gap-4 text-sm font-semibold text-slate-600" style="grid-template-columns: 1fr auto auto;">
                        <div>التسوية</div>
                        <div>القسم</div>
                        <div>مكتمل</div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($settlements as $settlement)
                        @php $isDone = (bool) ($settlement['is_done'] ?? false); @endphp

                        <label wire:key="settlement-row-{{ $settlement['id'] }}" class="grid cursor-pointer grid-cols-[1fr_auto_auto] items-center gap-4 px-6 py-4 transition hover:bg-slate-50" style="grid-template-columns: 1fr auto auto;">
                            <div class="min-w-0">
                                <div class="text-base font-semibold text-slate-900">{{ $settlement['name'] }}</div>
                                <div class="mt-1 text-sm {{ $isDone ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $isDone ? 'مكتملة' : 'تحت الإجراء' }}
                                </div>
                            </div>

                            <div>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ strtoupper($settlement['department']) }}
                                </span>
                            </div>

                            <div class="flex justify-end">
                                <input
                                    type="checkbox"
                                    wire:model.defer="doneStates.{{ $settlement['id'] }}"
                                    @checked($isDone)
                                    class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                />
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
