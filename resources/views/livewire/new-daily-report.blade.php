<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">إنشاء تقرير جديد</div>
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
            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2 text-xs">اسم العميل
                        <span class="text-red-500">*</span>
                    </label>
{{--                    <div wire:ignore>--}}
{{--                        <select id="customer_name" name="customer_name"--}}
{{--                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                                style="@error('item_id') border: solid 1px #fda4af; @enderror">--}}
{{--                            @foreach($customers as $customer)--}}
{{--                                <option value="{{$customer->Code}}-{{ $customer->Arabic_Name }}">{{$customer->Code}}-{{ $customer->Arabic_Name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    <div>
                        <select id="customer_name" name="customer_name" wire:model="customer_name"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('item_id') border: solid 1px #fda4af; @enderror">
                            <option value="-1">الرجاء اختيار العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->Code}}-{{ $customer->Arabic_Name }}">{{$customer->Code}}-{{ $customer->Arabic_Name }}</option>
                            @endforeach
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
                           class="text-xs text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    </textarea>
                    @error('report_note') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex sm:flex-row flex-col gap-4">
                <div class="mt-4 text-center w-full">
                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
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
                <div class="mt-4 text-center w-full">
                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>حفظ وادخال تقرير آخر</span>
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

    <script>
        $(document).ready(function() {
            $('#customer_name').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            // $('#customer_name').select2({
            //     dir: "rtl",
            //     dropdownCssClass: "select-font-size",
            //     ajax: {
            //         // url: 'http://localhost:8000/api/customers',
            //         url: 'https://api.github.com/search/repositories',
            //         dataType: 'json',
            //         // data: function (params) {
            //         //     var query = {
            //         //         cust: params.term,
            //         //         // search: params.term,
            //         //         // type: 'public'
            //         //     }
            //         //
            //         //     console.log("======query======");
            //         //     console.log(query.cust);
            //         //     // Query parameters will be ?search=[term]&type=public
            //         //     return query;
            //         // }
            //         data: function (params) {
            //             return {
            //                 q: params.term, // search term
            //                 page: params.page
            //             };
            //         },
            //         // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            //     }
            // });


            // $('#customer_name').on('select2:open', function(e){
            //     $('input.select2-search__field').on('input', function () {
            //         $wire.call('index', $(this).val())
            //     })
            // });
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
    </style>
@stop
