@section('title')
    التقارير اليومية
@stop
@section('title-btn')
    <a href="{{ route('create.daily-report') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
        <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
            <path
                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
        </svg>
        <span class="hidden xs:block mr-2">إنشاء</span>
    </a>
@stop
<div>
    <div class="mb-5">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">التقارير اليومية</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div>
        @if(session()->has('message'))
            <div
                style="background-color: #9ad2dd2b;border: 2px solid #5c9fac;text-align: center;color: #5c9fac;margin-bottom: 20px;"
                class="p-3">
                {{ session('message') }}
            </div>
        @endif
        @if(session()->has('error-message'))
            <div
                style="background-color: #9ad2dd2b;border: 2px solid #5c9fac;text-align: center;color: #5c9fac;margin-bottom: 20px;"
                class="p-3">
                {{ session('error-message') }}
            </div>
        @endif
        @if(session()->has('success'))
            <div x-show="open" x-data="{ open: true }" class="mb-8">
                <div class="px-4 py-2 rounded-sm text-sm bg-green-100 border border-green-200 text-green-600">
                    <div class="flex w-full justify-between items-start">
                        <div class="flex">
                            <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z"></path>
                            </svg>
                            <div class="px-3">{{ session('success') }}</div>
                        </div>
                        <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="open = false">
                            <div class="sr-only">اغلاق</div>
                            <svg class="w-4 h-4 fill-current">
                                <path
                                    d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div>
        <div class="overflow-x-auto">
            @if(count($daily_reports) > 0 )
                <table class="table-auto w-full border text-center">
                    <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                    <tr>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">#</div>
                        </th>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">التاريخ</div>
                        </th>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">النوع</div>
                        </th>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="font-semibold"></div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($daily_reports as $record)
                        <tr>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-lg">{{ $record->id }}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-lg" style="color: #c02424;">{{ $record->report_date }}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-lg">
                                        @if($record->report_type == 'work')
                                            تقرير عمل
                                        @elseif($record->report_type == 'visit')
                                            تقرير زيارة
                                        @elseif($record->report_type == 'sales')
                                            تحصيل/مبيعات
                                        @elseif($record->report_type == 'general-rept')
                                            تقرير عام عن عميل
                                        @elseif($record->report_type == 'meeting')
                                            إجتماع
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="p-2 whitespace-nowrap sm:flex justify-center">
                                <div class="m-1.5">
                                    <a href="{{ route('show.daily-report', ['id' => $record->id]) }}"
                                       class="btn border-gray-200 hover:border-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 shrink-0"
                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="2"/>
                                            <path
                                                d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                        </svg>

                                    </a>
                                </div>
                                <div class="m-1.5">
                                    <a href="{{ route('edit.daily-report', ['id' => $record->id]) }}"
                                       class="btn border-gray-200 hover:border-gray-300">
                                        <svg class="w-4 h-4 fill-current text-gray-500 shrink-0" viewBox="0 0 16 16">
                                            <path
                                                d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="m-1.5">
                                    <div x-data="{ modalOpen: false }">
                                        <button class="btn border-gray-200 hover:border-gray-300"
                                                @click.prevent="modalOpen = true" aria-controls="danger-modal">
                                            <svg class="w-4 h-4 fill-current text-red-500 shrink-0" viewBox="0 0 16 16">
                                                <path
                                                    d="M5 7h2v6H5V7zm4 0h2v6H9V7zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1zM6 2v1h4V2H6zm7 3H3v9h10V5z"></path>
                                            </svg>
                                        </button>
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-50 transition-opacity"
                                             x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-out duration-100"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>
                                        <div id="danger-modal"
                                             class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center transform px-4 sm:px-6"
                                             role="dialog" aria-modal="true" x-show="modalOpen"
                                             x-transition:enter="transition ease-in-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-4"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in-out duration-200"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                                            <div
                                                class="bg-white rounded shadow-lg overflow-auto max-w-lg w-full max-h-full"
                                                @click.outside="modalOpen = false"
                                                @keydown.escape.window="modalOpen = false">
                                                <div class="p-5 flex space-x-4">
                                                    <div
                                                        class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-red-100">
                                                        <svg class="w-4 h-4 shrink-0 fill-current text-red-500"
                                                             viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="mb-8 mr-4 mt-2">
                                                            <div class="text-lg font-semibold text-gray-800">
                                                                هل تريد بالفعل حذف هذا التقرير؟
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-wrap justify-start gap-4">
                                                            <button
                                                                class="btn-sm border-gray-200 hover:border-gray-300 text-gray-600"
                                                                @click="modalOpen = false">لا
                                                            </button>
                                                            <button wire:click.prevent="delete({{$record->id}})"
                                                                    class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                                                                نعم
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="border text-center p-6 text-lg font-bold">لا يوجد تقارير حتى الآن
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            @else
                <div>
                    <div class="border text-center p-6 text-lg font-bold bg-gray-50">لا يوجد تقارير حتى الآن</div>
                </div>
            @endif
            {{ $daily_reports->links() }}
        </div>
    </div>
</div>
