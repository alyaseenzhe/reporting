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
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <a href="{{ route('list.daily-reports') }}" class="text-gray-700 hover:text-gray-900 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">التقارير اليومية</a>
                    </div>
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تحديث التقرير</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

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

    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">تحديث تقرير</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">الموقع
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="location1" name="location1" wire:model="location1"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="المركز الرئيسي - الاحساء">المركز الرئيسي - الاحساء</option>
                        <option value="فرع الاحساء">فرع الاحساء</option>
                        <option value="مستودع الاحساء">مستودع الاحساء</option>
                        <option value="القرية العليا">القرية العليا</option>
                        <option value="فرع جدة">فرع جدة</option>
                        <option value="منطقة المدينة المنورة">منطقة المدينة المنورة</option>
                        <option value="مستودع المدينة">مستودع المدينة</option>
                        <option value="فرع الرياض">فرع الرياض</option>
                        <option value="المجمعة">المجمعة</option>
                        <option value="وادي الدواسر">وادي الدواسر</option>
                        <option value="فرع الجوف">فرع الجوف</option>
                        <option value="الدمام">فرع الدمام</option>
                        <option value="الخرج">فرع الخرج</option>
                        <option value="نجران">فرع نجران</option>
                        <option value="منطقة الباحة">منطقة الباحة</option>
                        <option value="فرع حائل">فرع حائل</option>
                        <option value="فرع تبوك">فرع تبوك</option>
                        <option value="فرع القصيم">فرع القصيم</option>
                        <option value="فرع ساجر">فرع ساجر</option>
                        <option value="مزرعة التجارب">مزرعة التجارب</option>
                        <option value="حرض">حرض</option>
                    </select>
                    @error('location1') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">تاريخ التقرير
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="report_date" type="date" name="report_date" wire:model="report_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('report_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"--}}
                {{--                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إنشاء تقرير</span>--}}
                {{--                        </span>--}}
                {{--                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">--}}
                {{--                        <span></span>--}}
                {{--                        <span>الرجاء الانتظار</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">نوع التقرير
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="report_type" name="report_type" wire:model="report_type"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="work">تقرير عمل</option>
                        <option value="visit">تقرير زيارة</option>
                        <option value="sales">تحصيل/مبيعات</option>
                        <option value="general-rept">تقرير عام عن عميل</option>
                        <option value="meeting">إجتماع</option>
                    </select>
                    @error('report_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">الموقع كتابةً
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="location2" type="text" name="location2" wire:model="location2"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('location2') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"--}}
                {{--                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إنشاء تقرير</span>--}}
                {{--                        </span>--}}
                {{--                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">--}}
                {{--                        <span></span>--}}
                {{--                        <span>الرجاء الانتظار</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
            <div id="customer-div" class="w-full flex sm:flex-row flex-col gap-4 hide">
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2 text-xs">اسم العميل
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_name" name="customer_name" wire:model="customer_name"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('item_id') border: solid 1px #fda4af; @enderror">
                            <option value="-1">الرجاء اختيار العميل</option>
                            @foreach($customer_list as $customer)
                                <option value="{{$customer['CardCode']}}-{{ $customer['CardName'] }}">{{$customer['CardCode']}}-{{ $customer['CardName'] }}</option>
                            @endforeach
                            <option value="عميل آخر">عميل آخر</option>
                        </select>
                    </div>
                    @error('customer_name') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">المرافقون
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="companion" type="text" name="companion" wire:model="companion"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('companion') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">العمل/المنجزات
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea rows="5" id="report_note" type="text" name="report_note" wire:model="report_note"
                              class="text-xs text-gray-900 form-select w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                              style="@error('item_id') border: solid 1px #fda4af; @enderror"></textarea>
                    @error('report_note') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="mt-4 text-center w-full">
                    <button id="gen-report"
                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>حفظ</span>
                        </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {

            rpt_type = $("#report_type").val();

            if(rpt_type == 'visit') {
                $('#customer-div').removeClass('hide');

                $('#customer_name').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
            }
            else {
                $('#customer-div').addClass('hide');
            }



            $('#customer_name').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });


            $('#report_type').on('change', function (e) {
                rpt_type = $(this).val();

                if(rpt_type == 'visit') {
                    $('#customer-div').removeClass('hide');

                    $('#customer_name').select2({
                        dir: "rtl",
                        dropdownCssClass: "select-font-size"
                    });
                }
                else {
                    $('#customer-div').addClass('hide');
                }


            });

            $('#gen-report').on('click', function () {

                var report_type = $('#report_type').val();
                var report_date = $('#report_date').val();
                var customer_name = $('#customer_name').val();
                var location1 = $('#location1').val();
                var location2 = $('#location2').val();
                var companion = $('#companion').val();
                var report_note = $('#report_note').val();



                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');



                if($.trim(report_date) == '' || $.trim(location2) == '' || $.trim(report_note) == ""|| (customer_name == '-1' && report_type == "visit")) {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من تحديث التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
                    $("#gen-report").html('<b>إضافة تقرير</b>');
                }

                else {
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

                    Livewire.emit('update-report', report_type, report_date, customer_name, location1, location2, companion, report_note);
                }
            });
        })
    </script>


@stop
@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            width: 100%;
            padding-right: 2.5rem;
            padding-top: 0.2rem;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }

        .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            /* left: 1px; */
            right: 9px;
        }

        .select-font-size {
            font-size: 0.875rem; /* 14px */
            line-height: 1.25rem; /* 20px */
        }

        .hide {
            display: none;
        }
    </style>
@stop
