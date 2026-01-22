<div>
    <div class="mb-1">
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير عمليات الأصناف</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <button style="border: 1px solid #838383;" type="button" class="collapsible active">خيارات البحث</button>

    <div style="border: 1px solid #838383;" id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('start_date') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('end_date') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" selected>الكل</option>
                            @if(in_array("3", $branches))
                                <option value="0101" >الاحساء</option>
                            @endif
                            @if(in_array("10", $branches))
                                <option value="0102" >جدة</option>
                            @endif
                            @if(in_array("7", $branches))
                                <option value="0103" >الرياض</option>
                            @endif
                            @if(in_array("13", $branches))
                                <option value="0104" >وادي الدواسر</option>
                            @endif
                            @if(in_array("4", $branches))
                                <option value="0105" >الجوف</option>
                            @endif
                            @if(in_array("6", $branches))
                                <option value="0106" >الدمام</option>
                            @endif
                            @if(in_array("5", $branches))
                                <option value="0107" >الخرج</option>
                            @endif
                            @if(in_array("12", $branches))
                                <option value="0108" >نجران</option>
                            @endif
                            @if(in_array("11", $branches))
                                <option value="0109" >حائل</option>
                            @endif
                            @if(in_array("9", $branches))
                                <option value="0110" >تبوك</option>
                            @endif
                            @if(in_array("8", $branches))
                                <option value="0111" >القصيم</option>
                            @endif
                            @if(in_array("505", $branches))
                                <option value="0112" >ساجر</option>
                            @endif
                            @if(in_array("3", $branches))
                                <option value="0201" >مزرعة الدالوة</option>
                                <option value="0202" >مزرعة الفضول</option>
                                <option value="0203" >مزرعة الدلم</option>
                                <option value="0001" >المركز الرئيسي</option>
                            @endif

                        </select>
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-4">نوع البحث
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-row">
                        <div class="flex items-center w-full">
                            <input type="radio" name="search_type" value="item_code_search"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                برقم الصنف</label>
                        </div>
                        <div class="flex items-center w-full">
                            <input type="radio" name="search_type" value="advanced_search"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">متقدم</label>
                        </div>
                    </div>

                    @error('item_type')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>

            </div>
        </div>
        <div id="filteration-row2" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div id="group_container" class="w-full">
                    <label class="block font-bold mb-2">المجموعات
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="group_type" name="group_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">

                            <option value="select_group" selected>اختر مجموعة</option>
                            <option value="groups_all">الكل</option>
                            <option value="commerce">الادارة التجارية</option>
                            <option value="farms">الانتاج الزراعي</option>
                            <option value="sundries">النثريات</option>
                        </select>
                    </div>
                    @error('group_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div id="filteration-row3" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide" wire:ignore>
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div id="cat_container" class="w-full" wire:ignore>
                    <label class="block font-bold mb-2">نوع المواد
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="cat_type" wire:model.lazy="cat_type" name="cat_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">

                        </select>
                    </div>
                    @error('cat_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="sp_container" class="w-full" wire:ignore>
                    <label class="block font-bold mb-2">نوع المميز
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="sp_type" name="sp_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                            <option value="sp_all" selected>الكل</option>
                            <option value="0">مميز 0</option>
                            <option value="1">مميز 1</option>
                            <option value="2">مميز 2</option>
                        </select>
                    </div>
                    @error('sp_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="marketing_type_container" class="w-full" wire:ignore>
                    <label class="block font-bold mb-2">الإدارات والاقسام
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="marketing_type" name="marketing_type" multiple="multiple" wire:ignore
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                            <option value="marketing_all" selected>الكل</option>
                            <option value="30">ادارة فنية - الاسمدة م1</option>
                            <option value="31">ادارة فنية - المبيدات م1</option>
                            <option value="32">ادارة فنية - البذور م1</option>
                            <option value="40">اقسام تسويقية - الحدائق والصحة العامة</option>
                            <option value="41">اقسام تسويقية - المكافحة المتكاملة</option>
                            <option value="50">الآليات والري - الاليات</option>
                            <option value="51">الآليات والري - الري</option>
                            <option value="52">الآليات والري - الري المطري</option>
                            <option value="53">الآليات والري - الخدمات</option>
                        </select>
                    </div>
                    @error('marketing_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="vendor_container" class="w-full" wire:ignore>
                    <label class="block font-bold mb-2">الموردين
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="vendor_type" name="vendor_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="vendor_all" selected>الكل</option>
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vendor_type')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div id="customer_container" class="w-full" wire:ignore>
                    <label class="block font-bold mb-2">العملاء
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_type" name="customer_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('customer_type') border: solid 1px #fda4af; @enderror">
                            <option value="customer_all" selected>الكل</option>
                            @foreach($customer_list as $customer)
                                <option value="{{ $customer['CardCode'] }}">{{ $customer['CardCode'] }} - {{ $customer['CardName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_type')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div id="sales_container" class="w-full">
                    <label class="block font-bold mb-2">الموظفين
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="emps_type" name="emps_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('emps_type') border: solid 1px #fda4af; @enderror">
                            <option value="employees_all" selected>الكل</option>
                            @foreach($emps as $employee)
                                <option value="{{ $employee->emp_code }}" data-dept="{{ $employee->sales_dept_code }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('emps_type')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div id="product-code-row" style="padding: 20px" class="w-full flex flex-col gap-4 mt-3 hide" wire:ignore>
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div wire:ignore id="product_code_div" class="w-full">
                    <label class="block font-bold mb-2">رقم الصنف
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="product_code" name="product_code"
                            class="form-input w-full @error('product_code') border-red-300 @enderror"
                            style="@error('products_code') border: solid 1px #fda4af; @enderror">
                        @foreach($products_codes as $item)
                            <option value="{{ $item['ItemCode'] }}">{{ $item['ScribeCode'] . ' | ' . $item['ItemCode'] . ' | ' . $item['ItemName']}}</option>
                        @endforeach
                    </select>
                    {{--                    <input type="text" id="product_code"--}}
                    {{--                           class="form-input w-full @error('product_code') border-red-300 @enderror">--}}
                    @error('product_code')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
            </div>
        </div>
        <div id="submit-row" class="w-full flex flex-col gap-4 mt-3 hide" wire:ignore>
            <div class="w-full flex flex-col sm:flex-row gap-4">
                {{--                <div class="w-full">--}}
                {{--                    <label class="block font-bold mb-2">خيارات التجميع--}}
                {{--                        --}}{{--                        <span class="text-red-500">*</span>--}}
                {{--                    </label>--}}
                {{--                    --}}{{--                    <div wire:ignore>--}}
                {{--                    --}}{{--                        <select id="report_type" name="report_type"--}}
                {{--                    --}}{{--                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
                {{--                    --}}{{--                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">--}}
                {{--                    --}}{{--                            <option value="byItem" selected>11- ملخص عمليات اصناف</option>--}}
                {{--                    --}}{{--                            <option value="byDepartment">12- مبيعات الفروع للصنف</option>--}}
                {{--                    --}}{{--                        </select>--}}
                {{--                    --}}{{--                    </div>--}}
                {{--                    <div wire:ignore class="flex items-center mb-4">--}}
                {{--                        <input id="report_type" name="report_type" type="checkbox" value="byDepartment" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                {{--                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">عمليات الاصناف بالتفصيل للفروع</label>--}}
                {{--                    </div>--}}
                {{--                    @error('report_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
                {{--                </div>--}}

                <div id="grouping" wire:ignore class="w-full">
                    <label class="block font-bold mb-2">خيارات التجميع (Grouping)
                        {{--                        <span class="text-red-500">*</span>--}}
                    </label>
                    <select id="report_type" name="report_type"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                        {{--                        <option value="byItem" selected>بدون تجميع</option>--}}
                        <option value="byDepartment" selected>بالصنف</option>
                        <option value="byItemGroup">بنوع المواد</option>
                        <option value="bySpeciality">بالمميز</option>
                        <option value="byMarketingType">بالاقسام</option>
                        <option value="byVendor">بالمورد</option>
                        <option value="byCustomer">بالعميل</option>
                        <option value="byEmployee">بالموظف</option>
                    </select>

                </div>


                <div id="sortBy" class="w-full" wire:ignore.self>
                    <!-- Sales -->
                    <label class="block font-bold mb-2">الترتيب بالأعمدة (Sorting)

                    </label>
                    <div class="flex flex-wrap gap-2">


{{--                        <label class="cursor-pointer">--}}
{{--                            <input--}}
{{--                                type="radio"--}}
{{--                                name="sortBy"--}}
{{--                                value="code"--}}
{{--                                wire:model.defer="sortBy"--}}
{{--                                class="sr-only peer"--}}
{{--                            >--}}

{{--                            <span class="block px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--        peer-checked:bg-blue-600--}}
{{--          peer-checked:text-red-600 peer-checked:border-blue-600--}}
{{--        hover:bg-gray-200 transition">--}}
{{--                                بالكود--}}
{{--                            </span>--}}
{{--                        </label>--}}

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="sortBy"
                                value="code"
                                wire:model.defer="sortBy"
                                class="sr-only peer"
                            >

                            <span class="block px-3 py-1.5 text-sm rounded border
        peer-checked:bg-gray-600
        peer-checked:text-white peer-checked:border-blue-600
        hover:bg-gray-400 transition">
        بالكود
    </span>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="sortBy"
                                value="GroupTotalSales"
                                wire:model.defer="sortBy"
                                class="hidden peer"
                            >

                            <span class="block px-3 py-1.5 text-sm rounded border
        peer-checked:bg-gray-600
        peer-checked:text-white peer-checked:border-blue-600
        hover:bg-gray-400 transition">
                                اجمالي المبيعات
                            </span>
                        </label>

                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="sortBy"
                                value="GroupGrossProfit"
                                wire:model.defer="sortBy"
                                class="hidden peer"
                            >

                            <div class="block px-3 py-1.5 text-sm rounded border
        peer-checked:bg-gray-600
        peer-checked:text-white peer-checked:border-blue-600
        hover:bg-gray-400 transition">
                               الهامش
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="sortBy"
                                value="GroupGrossProfitPer"
                                wire:model.defer="sortBy"
                                class="hidden peer"
                            >

                            <span class="block px-3 py-1.5 text-sm rounded border
        peer-checked:bg-gray-600
        peer-checked:text-white peer-checked:border-blue-600
        hover:bg-gray-400 transition">
                               النسبة
                            </span>
                        </label>

                            @endif
                    </div>


                </div>



                <div id="sortDir" class="w-full" wire:ignore.self>
                    <!-- Sales -->
                    <label class="block font-bold mb-2">نوع الترتيب</label>

                    <div class="flex flex-wrap gap-2">
{{--                        <div class="flex gap-2">--}}
                            <!-- ASC -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="sortDir"
                                    value="ASC"
                                    wire:model.defer="sortDir"
                                    class="hidden peer"
                                >
                                <span class="px-2 py-1 text-sm
                                rounded border
            peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600
            hover:bg-gray-400 transition flex items-center gap-1">
                                    ASC ▲
                                </span>
                            </label>

                            <!-- DESC -->


                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="sortDir"
                                    value="desc"
                                    wire:model.defer="sortDir"
                                    class="hidden peer"
                                >
                                <div class="px-2 py-1 text-sm rounded border
            peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600
            hover:bg-gray-400 transition flex items-center gap-1">
                                    DESC ▼
                                </div>
                            </label>
{{--                        </div>--}}
                    </div>
                </div>

                    {{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sortBy" value="EmployeeTotalSales" wire:model.lazy="sortBy" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                hover:bg-gray-200 flex items-center gap-1 transition">--}}

{{--                                اجمالي المبيعات--}}

{{--                                <span>--}}
{{--                    @if($sortBy === 'EmployeeTotalSales')--}}
{{--                                        {{ $sortDir === 'asc' ? '▲' : '▼' }}--}}
{{--                                    @else--}}
{{--                                        ▲--}}
{{--                                    @endif--}}
{{--                </span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                        <!-- Cost -->--}}
{{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sortBy" value="EmployeeGrossProfit" wire:model.lazy="sortBy" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                hover:bg-gray-200 flex items-center gap-1 transition">--}}

{{--                                الهامش--}}

{{--                                <span>--}}
{{--                    @if($sortBy === 'EmployeeGrossProfit')--}}
{{--                                        {{ $sortDir === 'asc' ? '▲' : '▼' }}--}}
{{--                                    @else--}}
{{--                                        ▲--}}
{{--                                    @endif--}}
{{--                </span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                        <!-- Ratio -->--}}
{{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sortBy" value="GroupGrossProfitPer" wire:model.lazy="sortBy" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                peer-checked:bg-gray-800 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                hover:bg-gray-200 flex items-center gap-1 transition">--}}

{{--                                النسبة--}}

{{--                                <span>--}}
{{--                    @if($sortBy === 'GroupGrossProfitPer')--}}
{{--                                        {{ $sortDir === 'asc' ? '▲' : '▼' }}--}}
{{--                                    @else--}}
{{--                                        ▲--}}
{{--                                    @endif--}}
{{--                </span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                    </div>--}}

{{--                    <!-- Hidden toggle for ASC/DESC -->--}}
{{--                    <input type="checkbox" wire:model.lazy="toggleDirection" class="hidden">--}}
{{--                </div>--}}
{{--                <div class="w-full" >--}}
{{--                    <label class="block font-bold mb-2"> ترتيب الأعمدة (Sorting)--}}
{{--                    </label>--}}


{{--                    <div class="flex flex-wrap gap-2">--}}

{{--                        <!-- Sales -->--}}
{{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sort_option" value="sales" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                    peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                    hover:bg-gray-200 flex items-center gap-1 transition">--}}
{{--                                اجمالي المبيعات--}}
{{--                                <span class="text-gray-500 peer-checked:text-white">▲</span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                        <!-- Cost -->--}}
{{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sort_option" value="cost" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                    peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                    hover:bg-gray-200 flex items-center gap-1 transition">--}}
{{--                                الهامش--}}
{{--                                <span class="text-gray-500 peer-checked:text-white">▲</span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                        <!-- Ratio -->--}}
{{--                        <label class="cursor-pointer">--}}
{{--                            <input type="radio" name="sort_option" value="ratio" class="hidden peer">--}}
{{--                            <div class="px-3 py-1.5 text-sm rounded border bg-gray-100--}}
{{--                    peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600--}}
{{--                    hover:bg-gray-200 flex items-center gap-1 transition">--}}
{{--                                النسبة--}}
{{--                                <span class="text-gray-500 peer-checked:text-white">▲</span>--}}
{{--                            </div>--}}
{{--                        </label>--}}

{{--                    </div>--}}
{{--                </div>--}}

                <div class="mt-8 text-center w-full">
                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                        <span></span>
                        <span>إنشاء تقرير</span>
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

    @if($show_msg)
        <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-4">
            @if(count($group_results) > 0)

                <div style="background-color: #f5f5f5;" class="mb-2 p-2">
                    <div class="flex flex-col sm:flex-row gap-6 w-full">

                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <div><label class="font-bold mb-5 text-sm">خيارات اظهار الأعمدة الخاصة</label></div>
                            <div>
                                <span class="text-xs">(</span>
                                <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-xs font-medium text-gray-900 dark:text-gray-300">اظهار</label>
                                <span class="text-xs">)</span>
                            </div>
                            {{--                            <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">--}}



                            {{--                            </div>--}}
{{--                        </div>--}}
            @endif


                            <div ><label class="font-bold mb-5 text-sm">الملخص</label></div>
                            <div>
{{--                                <span class="text-xs">(</span>--}}
                                <input id="summary" type="checkbox" value="summary" onchange="summary(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                            </div>



                </div>
                </div>

            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center" wire:ignore>
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm" >
                <tr style="border: 2px solid black;">

                    @if($report_type == 'byItem')
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">كود الصنف</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">الوصف</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">وحدة</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">مميز</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">مورد</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">كمية</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">صافي المبيعات</div>

                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">متوسط السعر</div>
                        </th>
                    @else
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">الفرع</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">#عمليات</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">كمية</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm" >صافي المبيعات

                            </div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">متوسط السعر</div>
                        </th>
                    @endif
                    @if($report_type == 'byDepartment' || $report_type == 'byItemGroup' || $report_type == 'bySpeciality' || $report_type == 'byMarketingType' || $report_type == 'byVendor' || $report_type == 'byEmployee')

                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                            <div class="text-sm">التكلفة</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin cost">
                            <div class="text-sm">الهامش</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin-percentage cost">
                            <div class="text-sm">نسبة</div>
                        </th>
                    @endif
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                    $item_code = "*";
                    $item_group_code = "*";
                    $item_group_itemCode_code = "*";
                    $speciality_code = "*";
                    $marketing_type_code = "*";
                    $vendor_code = "*";
                @endphp



                @if($report_type == 'byItem')
                    @php $item_total = 0; $cost_total = 0; $gross_total = 0; @endphp
                    @foreach($group_results as $record)
{{--                        <tr wire:key="rec-{{ now() }}" class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">--}}
                        <tr wire:key="{{$record->id}}" class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["OldCode"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ $record['ItemName'] }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{$record["SalUnitMsr"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record['Speciality']}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record["VendorName"]}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $item_total = $item_total + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['AverageUnitPrice'], 2)}}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $cost_total = $cost_total + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $gross_total = $gross_total + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{--                                    {{number_format($record['GrossProfitPer'], 2)}}--}}
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach

                @elseif($report_type == 'byDepartmentX')
                    @php $currentGroup = null; @endphp
                    @php $dept_item_total = 0; $dept_cost_total = 0; $dept_gross_total = 0; @endphp
                    @php $dept_item_subtotal = 0; $dept_cost_subtotal = 0; $dept_gross_subtotal = 0; @endphp
                    @foreach($group_results as $outer_record)
                        @foreach($outer_record as $record)
                            @if($record["OldCode"] != $item_code)
                                    <?php $item_code = $record["OldCode"]; ?>
                                <tr wire:key="rec-{{ now() }}" style="background-color: #faebd7; font-weight: bold; color: red;">
                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        {{$record["OldCode"]}}
                                    </td>
                                    <td colspan="10" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>الصنف: {{$record["ItemName"]}}</div>
                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>
                                            <div>التميز: {{$record['Speciality']}}</div>
                                            <div>المورد: {{$record["VendorName"]}}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr wire:key="rec-{{ now() }}" class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                        ادارة فنية - الاسمدة م1
                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                        ادارة فنية - المبيدات م1
                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                        ادارة فنية - البذور م1
                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                        اقسام تسويقية - الحدائق والصحة العامة
                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                        اقسام تسويقية - المكافحة المتكاملة
                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                        الاليات والري - الاليات
                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                        الاليات والري - الري
                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                        الاليات والري - الري المطري
                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                        الاليات والري - الخدمات
                                    @else
                                        عام
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["ItemGroup"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["Speciality"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["ItemName"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["Department"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{__($record["Department"])}}

                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $dept_item_total = $dept_item_total + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $dept_cost_total = $dept_cost_total + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $dept_gross_total = $dept_gross_total + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                    @endforeach
                @elseif($report_type == 'byDepartment')

                    @foreach($group_results as $record)
                        @if($currentGroup != $record["OldCode"])


                            @php $currentGroup = $record["OldCode"]; @endphp
                            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0;  @endphp
                        @endif

                        @if($record["OldCode"] != $item_group_code)
                                <?php $item_group_code = $record["OldCode"]; ?>

                            <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        {{$record["OldCode"]}}--}}
                                {{--                                    </td>--}}
                                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row">
                                        <div>({{ $record["OldCode"] }}) - {{$record["ItemName"]}}</div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($record["OldCode"] != $item_group_itemCode_code)
                                <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                            <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row justify-between">
                                        <div>قسم:
                                            <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                    ادارة فنية - الاسمدة م1
                                                @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                    ادارة فنية - المبيدات م1
                                                @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                    ادارة فنية - البذور م1
                                                @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                    اقسام تسويقية - الحدائق والصحة العامة
                                                @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                    اقسام تسويقية - المكافحة المتكاملة
                                                @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                    الاليات والري - الاليات
                                                @elseif($record["mrkt_type"] == "aleyat - ray")
                                                    الاليات والري - الري
                                                @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                    الاليات والري - الري المطري
                                                @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                    الاليات والري - الخدمات
                                                @else
                                                    عام
                                                @endif
                                                    </span>
                                        </div>
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{$record["ItemGroup"]}}</span>
                                        </div>
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                        </div>
                                        <div>التميز:
                                            <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                        </div>
                                        <div>المورد:
                                            <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                        </div>
                                    </div>

                            </tr>
                            <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                @endif
                            </tr>
                        @endif
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{__($record["Department"])}}

                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record['TransCount']}}
                                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                                @endif
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach

                @elseif($report_type == 'byItemGroup')
{{--                    <x-grouping :group_results="$group_results->toArray()" recordGroup="ItemGroup" :currentGroup="$currentGroup"/>--}}
        @foreach($group_results as $record)
            @if($currentGroup != $record["ItemGroup"])
        {{-- Output subtotals for the previous group --}}
                @if($currentGroup !== null)
            <tr wire:key="rec-{{ now() }}" style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    مجموع جزئي
                </td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                    {{number_format($itemGroup_item_subtotal, 2)}}
                </td>
                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                    {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                </td>
                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                @endif
            </tr>
        @endif

                    @php $currentGroup = $record["ItemGroup"]; @endphp
{{--        @php $currentGroup = $recordGroup; @endphp--}}
        @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0;  @endphp
    @endif

{{--    @if($record[$recordGroup] != $item_group_code)--}}
{{--            <?php $item_group_code = $record[$recordGroup]; ?>--}}
                @if($record["ItemGroup"] != $item_group_code)
                    <?php $item_group_code = $record["ItemGroup"]; ?>

        <tr style="background-color: #faebd7; font-weight: bold; color: red;">
            {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
            {{--                                        {{$record["OldCode"]}}--}}
            {{--                                    </td>--}}
            <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                <div class="flex flex-row">
{{--                    <div>{{$record[$recordGroup]}}</div>--}}
                                            <div>{{$record["ItemGroup"]}}</div>
                    {{--                                            <div>الصنف: {{$record["ItemName"]}}</div>--}}
                    {{--                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>--}}
                    {{--                                            <div>التميز: {{$record['Speciality']}}</div>--}}
                    {{--                                            <div>المورد: {{$record["VendorName"]}}</div>--}}
                </div>
            </td>
        </tr>
    @endif
    @if($record["OldCode"] != $item_group_itemCode_code)
            <?php $item_group_itemCode_code = $record["OldCode"]; ?>

        <tr onclick="show_hide({{$record["OldCode"]}})"  class="summary" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
            <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
            <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                <div class="flex flex-row justify-between">
                    <div>قسم:
                        <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                ادارة فنية - الاسمدة م1
                            @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                ادارة فنية - المبيدات م1
                            @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                ادارة فنية - البذور م1
                            @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                اقسام تسويقية - الحدائق والصحة العامة
                            @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                اقسام تسويقية - المكافحة المتكاملة
                            @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                الاليات والري - الاليات
                            @elseif($record["mrkt_type"] == "aleyat - ray")
                                الاليات والري - الري
                            @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                الاليات والري - الري المطري
                            @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                الاليات والري - الخدمات
                            @else
                                عام
                            @endif
                                                    </span>
                    </div>
                    <div>كودالصنف:
                        <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                    </div>
                    <div>الصنف:
                        <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                    </div>
                    <div>الوحدة:
                        <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                    </div>
                    <div>التميز:
                        <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                    </div>
                    <div>المورد:
                        <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                    </div>
                </div>

            {{--                                            مجموع جزئي للصنف--}}
            {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
            {{--                                        </td>--}}
            {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
            {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
            {{--                                        </td>--}}
            {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
            {{--                                        </td>--}}
            {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
            {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
            {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
            {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
            {{--                                        @endif--}}
        </tr>
        <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
            {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
            </td>
            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
            </td>
            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
            </td>
            <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
            </td>
            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
            @endif
        </tr>
    @endif
    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} summary hide">


        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
            {{__($record["Department"])}}

        </td>
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
            {{number_format($record['TransCount'])}}
            @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
            @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
        </td>
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
            {{number_format($record['TotalQuantitySold'])}}
            @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
            @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
        </td>
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
            {{number_format($record['TotalSalesAmount'], 2)}}
            @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
            @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
            @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
        </td>
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


            @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                {{number_format($record['AverageUnitPrice'], 2)}}
            @endif
        </td>
        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                {{number_format($record["Cost"], 2)}}
                @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                {{number_format($record['GrossProfit'], 2)}}
                @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
            </td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
            </td>
        @endif
    </tr>
    @php $counter++ @endphp
@endforeach
{{--                    @endforeach--}}
@if($currentGroup !== null)
    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
            مجموع جزئي
        </td>
        {{--                            العمليات                                --}}
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
        {{--                           الكمية                                  --}}
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
        {{--                          صافي المبيعات ------------------------}}
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
            {{number_format($itemGroup_item_subtotal, 2)}}
        </td>
        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
        </td>
        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
        @endif
    </tr>
@endif

{{--                    @foreach($tableRows as $row)--}}
{{--                        @if($row['type'] === 'group_subtotal')--}}
{{--                            <tr class="bg-yellow-200 font-bold">--}}
{{--                                <td colspan="1">{{ $row['group_name'] ?? 'مجموع' }}</td>--}}
{{--                                <td>{{ $row['totals']['trans'] }}</td>--}}
{{--                                <td>{{ $row['totals']['quantity'] }}</td>--}}
{{--                                <td>{{ number_format($row['totals']['item'], 2) }}</td>--}}
{{--                                <td>-</td>--}}
{{--                                @if(Auth::user()->user_group->cost == '1' || Auth::user()->role == 'a')--}}
{{--                                    <td>{{ number_format($row['totals']['cost'], 2) }}</td>--}}
{{--                                    <td>{{ number_format($row['totals']['gross'], 2) }}</td>--}}
{{--                                    <td>{{ $row['totals']['item'] ? number_format(($row['totals']['gross']/$row['totals']['item'])*100, 2) : 0 }}</td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                        @elseif($row['type'] === 'record')--}}
{{--                            <tr>--}}
{{--                                <td>{{ $row['data']['ItemName'] }}</td>--}}
{{--                                <td>{{ $row['data']['TransCount'] }}</td>--}}
{{--                                <td>{{ $row['data']['TotalQuantitySold'] }}</td>--}}
{{--                                <td>{{ number_format($row['data']['TotalSalesAmount'], 2) }}</td>--}}
{{--                                <td>{{ $row['data']['SalUnitMsr'] }}</td>--}}
{{--                                @if(Auth::user()->user_group->cost == '1' || Auth::user()->role == 'a')--}}
{{--                                    <td>{{ number_format($row['data']['Cost'], 2) }}</td>--}}
{{--                                    <td>{{ number_format($row['data']['GrossProfit'], 2) }}</td>--}}
{{--                                    <td>{{ $row['data']['TotalSalesAmount'] ? number_format(($row['data']['GrossProfit']/$row['data']['TotalSalesAmount'])*100, 2) : 0 }}</td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}

                    {{--                    @php $currentGroup = null; $currentItemName = null; @endphp--}}
{{--                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; $itemGroup_trans_total = 0; @endphp--}}
{{--                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0; @endphp--}}
{{--                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp--}}

{{--                    @include('livewire.report11.grouping',[--}}
{{--                                         'trans_subtotal' =>$itemGroup_trans_subtotal ,--}}
{{--                                         'quantity_total' => $itemGroup_quantity_total,--}}
{{--                                         'item_subtotal'=> $itemGroup_item_subtotal,--}}
{{--                                         'quantity_subtotal' => $itemGroup_quantity_subtotal,--}}

{{--])--}}

                @elseif($report_type == 'bySpeciality')

                    @foreach($group_results as $record)
                        @if($currentGroup != $record["Speciality"])

                            {{-- Output subtotals for the previous group --}}
                            @if($currentGroup !== null)
                                <tr wire:key="rec-{{ now() }}" style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        مجموع جزئي
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($itemGroup_item_subtotal, 2)}}
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif

                            @php $currentGroup = $record["Speciality"]; @endphp
                            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0  @endphp
                        @endif

                        @if($record["Speciality"] != $item_group_code)
                                <?php $item_group_code = $record["Speciality"]; ?>

                            <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        {{$record["OldCode"]}}--}}
                                {{--                                    </td>--}}
                                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row">
                                        <div>مميز {{$record["Speciality"]}}</div>

                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($record["OldCode"] != $item_group_itemCode_code)
                                <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row justify-between">
                                        <div>قسم:
                                            <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                    ادارة فنية - الاسمدة م1
                                                @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                    ادارة فنية - المبيدات م1
                                                @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                    ادارة فنية - البذور م1
                                                @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                    اقسام تسويقية - الحدائق والصحة العامة
                                                @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                    اقسام تسويقية - المكافحة المتكاملة
                                                @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                    الاليات والري - الاليات
                                                @elseif($record["mrkt_type"] == "aleyat - ray")
                                                    الاليات والري - الري
                                                @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                    الاليات والري - الري المطري
                                                @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                    الاليات والري - الخدمات
                                                @else
                                                    عام
                                                @endif
                                                    </span>
                                        </div>
                                        <div>كودالصنف:
                                            <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                        </div>
                                        <div>الصنف:
                                            <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                        </div>
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                        </div>
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">
                                                    {{$record['ItemGroup']}}
                                                    </span>
                                        </div>
                                        <div>المورد:
                                            <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                        </div>
                                    </div>

                                {{--                                            مجموع جزئي للصنف--}}
                                {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        </td>--}}
                                {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                {{--                                        @endif--}}
                            </tr>
                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                @endif
                            </tr>
                        @endif
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} summary hide">


                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{__($record["Department"])}}

                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TransCount'])}}
                                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                                @endif
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
                    {{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byMarketingType')

                    @foreach($group_results as $record)
                        @if($currentGroup != $record["mrkt_type"])

                            {{-- Output subtotals for the previous group --}}
                            @if($currentGroup !== null)
                                <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        مجموع جزئي
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($itemGroup_item_subtotal, 2)}}
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif

                            @php $currentGroup = $record["mrkt_type"]; @endphp
                            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0;  @endphp
                        @endif

                        @if($record["mrkt_type"] != $item_group_code)
                                <?php $item_group_code = $record["mrkt_type"]; ?>

                            <tr  style="background-color: #faebd7; font-weight: bold; color: red;">
                                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        {{$record["OldCode"]}}--}}
                                {{--                                    </td>--}}
                                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row">
                                        <div>
                                            @if($record["mrkt_type"] == "fan - asmedah 1")
                                                ادارة فنية - الاسمدة م1
                                            @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                ادارة فنية - المبيدات م1
                                            @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                ادارة فنية - البذور م1
                                            @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                اقسام تسويقية - الحدائق والصحة العامة
                                            @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                اقسام تسويقية - المكافحة المتكاملة
                                            @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                الاليات والري - الاليات
                                            @elseif($record["mrkt_type"] == "aleyat - ray")
                                                الاليات والري - الري
                                            @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                الاليات والري - الري المطري
                                            @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                الاليات والري - الخدمات
                                            @else
                                                عام
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($record["OldCode"] != $item_group_itemCode_code)
                                <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row justify-between">
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                        </div>
                                        <div>كودالصنف:
                                            <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                        </div>
                                        <div>الصنف:
                                            <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                        </div>
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                        </div>
                                        <div>التميز:
                                            <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                        </div>
                                        <div>المورد:
                                            <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                        </div>
                                    </div>

                                {{--                                            مجموع جزئي للصنف--}}
                                {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        </td>--}}
                                {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                {{--                                        @endif--}}
                            </tr>
                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                @endif
                            </tr>
                        @endif
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} summary hide">


                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{__($record["Department"])}}

                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TransCount'])}}
                                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                                @endif
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
                    {{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byVendor')

                    @foreach($group_results as $record)

                        @if($currentGroup != $record["VendorName"])

                            {{-- Output subtotals for the previous group --}}
                            @if($currentGroup !== null)
                                <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        مجموع جزئي
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($itemGroup_item_subtotal, 2)}}
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif

                            @php $currentGroup = $record["VendorName"]; @endphp
                            @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0  @endphp
                        @endif

                        @if($record["VendorName"] != $item_group_code)
                                <?php $item_group_code = $record["VendorName"]; ?>

                            <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        {{$record["OldCode"]}}--}}
                                {{--                                    </td>--}}
                                <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row">
                                        <div>
                                            {{ $record["VendorName"] }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($record["OldCode"] != $item_group_itemCode_code)
                                <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    <div class="flex flex-row justify-between">
                                        <div>نوع المادة:
                                            <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                        </div>
                                        <div>كودالصنف:
                                            <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                        </div>
                                        <div>الصنف:
                                            <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                        </div>
                                        <div>الوحدة:
                                            <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                        </div>
                                        <div>التميز:
                                            <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                        </div>
                                        <div>قسم:
                                            <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                    ادارة فنية - الاسمدة م1
                                                @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                    ادارة فنية - المبيدات م1
                                                @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                    ادارة فنية - البذور م1
                                                @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                    اقسام تسويقية - الحدائق والصحة العامة
                                                @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                    اقسام تسويقية - المكافحة المتكاملة
                                                @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                    الاليات والري - الاليات
                                                @elseif($record["mrkt_type"] == "aleyat - ray")
                                                    الاليات والري - الري
                                                @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                    الاليات والري - الري المطري
                                                @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                    الاليات والري - الخدمات
                                                @else
                                                    عام
                                                @endif
                                                </span>
                                        </div>
                                    </div>

                                {{--                                            مجموع جزئي للصنف--}}
                                {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                {{--                                        </td>--}}
                                {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                {{--                                        </td>--}}
                                {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                {{--                                        @endif--}}
                            </tr>
                            <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][4])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                </td>
                                <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                    <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                @endif
                            </tr>
                        @endif
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} summary hide">

                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            {{__($record["Department"])}}
                            </td>

                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TransCount'])}}
                                @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                                @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                                @endif
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                    @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                    @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                    @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach
                    {{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byCustomer')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; $itemGroup_trans_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

                    @foreach($group_results as $outer_record)
                        @php
                            $item_group_itemCode_code = null;
                            $item_group_code = null;
                        @endphp
                        @foreach($outer_record as $record)

                            @if($currentGroup != $record["BusinessPartnerCode"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["BusinessPartnerCode"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0  @endphp
                            @endif

                            @if($record["BusinessPartnerCode"] != $item_group_code)
                                    <?php $item_group_code = $record["BusinessPartnerCode"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>
                                                {{--                                                {{ $record["VendorName"] }}--}}
                                                {{ $record["BusinessPartnerCode"] }} - {{ $record["BusinessPartnerName"] }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][4])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][0]/$totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][2]/$totalSalesByItem[$record["BusinessPartnerCode"]][$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} summary hide">

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                       {{__($record["Department"])}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TransCount'])}}
                                    @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                    @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                    @endforeach
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif



                @endif
                </tbody>

                @if($report_type == 'byItem')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #f8e1ab; font-weight: bold">
                        <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $item_total == 0 ? 0 : number_format(($gross_total/$item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>


                @elseif($report_type == 'byEmployee')

                    @foreach($group_results as $outer_record)
                        @php
                            $item_group_itemCode_code = null;
                            $item_group_code = null;
                        @endphp
                        @foreach($outer_record as $key => $record)

                            @if($currentGroup != $record["EmployeeCode"])

                                {{-- Output subtotals for the previous group --}}
{{--                                @if($currentGroup !== null)--}}
{{--                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">--}}
{{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                                            مجموع جزئي--}}
{{--                                        </td>--}}
{{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>--}}
{{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>--}}

{{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                                            {{number_format($itemGroup_item_subtotal, 2)}}--}}
{{--                                        </td>--}}
{{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
{{--                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}--}}
{{--                                        </td>--}}
{{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>--}}
{{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>--}}
{{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>--}}
{{--                                        @endif--}}
{{--                                    </tr>--}}
{{--                                @endif--}}

                                @php $currentGroup = $record["EmployeeCode"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; $itemGroup_trans_subtotal = 0  @endphp
                            @endif

                            @if($record["EmployeeCode"] != $item_group_code)
                                    <?php $item_group_code = $record["EmployeeCode"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="8" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>
                                                {{--                                                {{ $record["VendorName"] }}--}}
                                                {{ $record["EmployeeCode"] }} - {{ $record["EmployeeName"] .'- '. __($record["Department"] )}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                </tr>
                                <tr class="summary" onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][4])?? 0}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][3])?? 0}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0], 2)?? 0}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0]/$totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][3], 2) : 0 }}
                                    </td>
{{--                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][1], 2)??0}}</td>--}}
{{--                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][2], 2)?? 0}}</td>--}}
{{--                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][2]/$totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0])*100, 2)}}</td>--}}
{{--                                    @endif--}}

                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][1], 2)??0}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][2], 2)?? 0}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][2]/$totalSalesByItem[$record["EmployeeCode"]][$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr wire:key="emp-{{ $record['EmployeeCode'] }}-item-{{ $record['OldCode'] }}-dept-{{ $record['Department'] }}"
                                class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}}  summary hide">

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{__($record["Department"])}}

                                </td>
                                                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                                                    {{number_format($record['TransCount'])}}
                                                                    @php $itemGroup_trans_total = $itemGroup_trans_total + floatval($record['TransCount']); @endphp
                                                                    @php $itemGroup_trans_subtotal = $itemGroup_trans_subtotal + floatval($record['TransCount']); @endphp
                                                                </td>

                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                        @if($currentGroup !== null)
                            <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    مجموع جزئي
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_trans_subtotal)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($itemGroup_item_subtotal, 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                @endif
                            </tr>
                        @endif


{{--                        @foreach($employeeSubtotals as $subtotal)--}}
{{--                            <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">--}}
{{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                                    مجموع جزئي - {{ $subtotal['EmployeeName'] }}--}}
{{--                                </td>--}}
{{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($subtotal['TransSubtotal']) }}</td>--}}
{{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($subtotal['QuantitySubtotal']) }}</td>--}}
{{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($subtotal['SalesSubtotal'], 2) }}</td>--}}
{{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
{{--                                    {{ $subtotal['QuantitySubtotal'] != 0 ? number_format($subtotal['SalesSubtotal'] / $subtotal['QuantitySubtotal'], 2) : 0 }}--}}
{{--                                </td>--}}
{{--                                @if(auth()->user()->user_group->cost == '1' || auth()->user()->role == 'a')--}}
{{--                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">{{ number_format($subtotal['CostSubtotal'], 2) }}</td>--}}
{{--                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">{{ number_format($subtotal['GrossSubtotal'], 2) }}</td>--}}
{{--                                    <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">{{ $subtotal['SalesSubtotal'] == 0 ? 0 : number_format(($subtotal['GrossSubtotal'] / $subtotal['SalesSubtotal']) * 100, 2) }}</td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                            @endforeach--}}
                    @endforeach


                        @endif
                        </tbody>

                        @if($report_type == 'byItem')
                            <tfoot>
                            <tr style="border-top: 2px solid black; background-color: #f8e1ab; font-weight: bold">
                                <td colspan="7" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    الإجمالي
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($item_total, 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($cost_total, 2)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($gross_total, 2)}}</td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $item_total == 0 ? 0 : number_format(($gross_total/$item_total)*100, 2)}}</td>
                                @endif
                @elseif($report_type == 'byDepartmentX')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #f8e1ab; font-weight: bold">
                        <td colspan="3" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($dept_item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($dept_cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($dept_gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $dept_item_total == 0 ? 0 : number_format(($dept_gross_total/$dept_item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>
                @elseif($report_type == 'byDepartment' || $report_type == 'byItemGroup' || $report_type == 'bySpeciality' || $report_type == 'byMarketingType' || $report_type == 'byVendor' || $report_type == 'byCustomer' || $report_type == 'byEmployee')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #abdcf8; font-weight: bold">
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($itemGroup_trans_total)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($itemGroup_quantity_total)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($itemGroup_item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $itemGroup_quantity_total != 0 ? number_format($itemGroup_item_total/$itemGroup_quantity_total, 2) : 0 }}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_total == 0 ? 0 : number_format(($itemGroup_gross_total/$itemGroup_item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>
                @endif

                            </tr>
                            </tfoot>
                @else
            </table>

                <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
                    <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
                    <span class="mt-4">لا يوجد نتائج للعرض</span>
                </div>
            @endif
        </div>
    @endif
</div>

@section('fixed-title')
    <span style="text-align: center">تقرير عمليات الأصناف</span>
@stop
@include('livewire.report11.script')

