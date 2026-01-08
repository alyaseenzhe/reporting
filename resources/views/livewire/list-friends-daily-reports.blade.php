@section('title')
    2- تقارير زملائي
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير زملائي</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div id="branch-container" class="mb-6 mt-6">
        <div style="background-color:#f0f8ff" class="p-5 flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الموظف
                        <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <select wire:model="single_emp_code"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="-1">اختر الموظف</option>
                            @foreach($emps as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إعادة ضبط</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
                <div class="mt-8 text-center w-full">
                    <button wire:click="search" id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold" wire:loading.remove wire:target="search">
                        <span></span>
                        <span>بحث</span>
                    </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="search">
                    <span></span>
                    <span>الرجاء الانتظار</span>
                    </span>
                    </button>
                </div>
                <div class="mt-8 text-center w-full">
                    <button wire:click="resetEmps" id="gen-report" style="background-color: #000000;" class="w-full btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold" wire:loading.remove wire:target="resetEmps">
                        <span></span>
                        <span>استعادة</span>
                    </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="resetEmps">
                    <span></span>
                    <span>الرجاء الانتظار</span>
                    </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div>

        <div class="overflow-x-auto">
            @if(count($friends_reports) > 0 )
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
                    @forelse($friends_reports as $record)
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
            {{--                {{ $friends_reports->links() }}--}}
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>

        $('#my-reports').on('click', function () {

            $('#my-reports').addClass('bg-gray-900');
            $('#my-reports').addClass('text-white');
            $('#my-reports').removeClass('bg-white');
            $('#my-reports').removeClass('text-gray-800');

            $('#friends-reports').removeClass('bg-gray-900');
            $('#friends-reports').removeClass('text-white');
            $('#friends-reports').addClass('bg-white');
            $('#friends-reports').addClass('text-gray-800');

            $('#my-reports-div').removeClass('hide');
            $('#friends-reports-div').addClass('hide');
        });

        $('#friends-reports').on('click', function () {

            $('#friends-reports').addClass('bg-gray-900');
            $('#friends-reports').addClass('text-white');
            $('#friends-reports').removeClass('bg-white');
            $('#friends-reports').removeClass('text-gray-800');

            $('#my-reports').removeClass('bg-gray-900');
            $('#my-reports').removeClass('text-white');
            $('#my-reports').addClass('bg-white');
            $('#my-reports').addClass('text-gray-800');

            $('#friends-reports-div').removeClass('hide');
            $('#my-reports-div').addClass('hide');
        });



    </script>
@stop
@section('css-scripts')
    <style>
        .hide {
            display: none;
        }
    </style>
@stop
