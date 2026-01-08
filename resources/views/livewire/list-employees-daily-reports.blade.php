@section('title')
    3- تقارير الموظفين
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
    <div id="branch-container" class="mb-6 mt-6">
        <div style="background-color:#f0f8ff" class="p-5 flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الموظف
                        <span class="text-red-500">*</span>
                    </label>
                    <div>
                        {{--                        <select wire:model="single_emp_code"--}}
                        <select id="customer_name"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="-1">اختر الموظف</option>
                            @foreach($emps as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                    </label>
                    {{--                    <input wire:model="start_date" id="start_date" type="date" name="start_date"--}}
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                    </label>
                    {{--                    <input wire:model="end_date" id="end_date" type="date" name="end_date"--}}
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button
                        {{--                        wire:click="search" --}}
                        id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button--}}
                {{--                        wire:click="resetEmps" --}}
                {{--                        id="gen-rest-report" style="background-color: #000000;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                    <span class="mr-2 font-bold" wire:loading.remove wire:target="resetEmps">--}}
                {{--                        <span></span>--}}
                {{--                        <span>استعادة</span>--}}
                {{--                    </span>--}}
                {{--                        <span class="mr-2 font-bold" wire:loading wire:target="resetEmps">--}}
                {{--                    <span></span>--}}
                {{--                    <span>الرجاء الانتظار</span>--}}
                {{--                    </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <div>
        <div class="overflow-x-auto">
            @if($daily_reports && count($daily_reports) > 0 )
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
            {{--            {{ $daily_reports->links() }}--}}
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        $(document).ready(function () {



            $('#gen-report').on('click', function () {

                var customer_id = $('#customer_name').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if (customer_id == "" || customer_id == "-1") {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول المطلوبة حتى تتمكن من البحث",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
                    $("#gen-report").html('<b>بحث</b>');
                } else {
                    $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                    Swal.fire({
                        title: 'الرجاء الإنتظار',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    });

                    Livewire.emit('search', customer_id, start_date, end_date);
                }

            });

            Livewire.on('finished', () => {
                swal.close();
            });
        });
    </script>
@stop

@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
@stop
