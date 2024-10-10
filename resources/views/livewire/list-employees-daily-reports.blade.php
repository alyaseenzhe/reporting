@section('title')
    التقارير اليومية
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير الموظفين</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <div class="overflow-x-auto">
            @if(count($daily_reports) > 0 )
                <table class="table-auto w-full border text-center">
                    <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                    <tr>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">الفترة</div>
                        </th>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">رقم الموظف</div>
                        </th>
                        <th class="border p-2 whitespace-nowrap">
                            <div class="text-lg">الموظف</div>
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
                                    <div class="text-center text-gray-800 text-lg" style="color: #c02424;">{{ $record->start_of_week }} - {{ $record->end_of_week }}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-lg">{{ $record->user->emp_code }}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-lg">{{ $record->user->name }}</div>
                                </div>
                            </td>

                            <td class="p-2 whitespace-nowrap sm:flex justify-center">
                                <div class="m-1.5">
                                    <a
{{--                                        href="{{ route('show.daily-report', ['id' => $record->id]) }}"--}}
                                        href="{{ route('show.employee-report', array('id' => $record->added_by, 'week_date' => $record->start_of_week)) }}"
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
