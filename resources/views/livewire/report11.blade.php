@section('title')
    تقرير عمليات الأصناف
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <a href="{{ route('sap-reports') }}" class="text-gray-700 hover:text-gray-900 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير ساب</a>
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير عمليات الأصناف</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
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
                            <option value="0101" >الاحساء</option>
                            <option value="0102" >جدة</option>
                            <option value="0103" >الرياض</option>
                            <option value="0104" >وادي الدواسر</option>
                            <option value="0105" >الجوف</option>
                            <option value="0106" >الدمام</option>
                            <option value="0107" >الخرج</option>
                            <option value="0108" >نجران</option>
                            <option value="0109" >حائل</option>
                            <option value="0110" >تبوك</option>
                            <option value="0111" >القصيم</option>
                            <option value="0112" >ساجر</option>
                            <option value="0201" >مزرعة الدالوة</option>
                            <option value="0202" >مزرعة الفضول</option>
                            <option value="0203" >مزرعة الدلم</option>
                            <option value="0001" >المركز الرئيسي</option>
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
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إعادة ضبط</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
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
                            {{--                            <option value="cat_all" @if(in_array("cat_all", $cat_type)) selected @endif>الكل</option>--}}
                            {{--                            <option value="bathoor" @if(in_array("bathoor", $cat_type)) selected @endif>بذور</option>--}}
                            {{--                            <option value="asmedah" @if(in_array("asmedah", $cat_type)) selected @endif>اسمدة</option>--}}
                            {{--                            <option value="mobedat" @if(in_array("mobedat", $cat_type)) selected @endif>مبيدات</option>--}}
                            {{--                            <option value="other" @if(in_array("other", $cat_type)) selected @endif>اخرى</option>--}}
                            <option value="select_group" selected>اختر مجموعة</option>
                            <option value="groups_all">الكل</option>
                            <option value="commerce">الادارة التجارية</option>
                            <option value="farms">الانتاج الزراعي</option>
                            <option value="sundries">النثريات</option>
                        </select>
                    </div>
                    @error('group_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="cat_container" class="w-full hide">
                    <label class="block font-bold mb-2">نوع المواد
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="cat_type" name="cat_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">
                            {{--                            <option value="cat_all" @if(in_array("cat_all", $cat_type)) selected @endif>الكل</option>--}}
                            {{--                            <option value="bathoor" @if(in_array("bathoor", $cat_type)) selected @endif>بذور</option>--}}
                            {{--                            <option value="asmedah" @if(in_array("asmedah", $cat_type)) selected @endif>اسمدة</option>--}}
                            {{--                            <option value="mobedat" @if(in_array("mobedat", $cat_type)) selected @endif>مبيدات</option>--}}
                            {{--                            <option value="other" @if(in_array("other", $cat_type)) selected @endif>اخرى</option>--}}
                            {{--                            <option value="cat_all" selected>الكل</option>--}}
                            {{--                            @foreach($itemGrp as $item)--}}
                            {{--                                <option value="{{ $item['ItemGroupCode'] }}">{{ $item['ItemGroupName'] }}</option>--}}
                            {{--                            @endforeach--}}
                        </select>
                    </div>
                    @error('cat_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="sp_container" class="w-full hide">
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
                <div id="vendor_container" class="w-full hide">
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
                    @error('vendor_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                    <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">--}}
                {{--                        <span></span>--}}
                {{--                        <span>إنشاء تقرير</span>--}}
                {{--                    </span>--}}
                {{--                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">--}}
                {{--                    <span></span>--}}
                {{--                    <span>الرجاء الانتظار</span>--}}
                {{--                    </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
        </div>
        <div id="product-code-row" style="padding: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
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
        <div id="submit-row" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">خيارات
                        {{--                        <span class="text-red-500">*</span>--}}
                    </label>
                    {{--                    <div wire:ignore>--}}
                    {{--                        <select id="report_type" name="report_type"--}}
                    {{--                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
                    {{--                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">--}}
                    {{--                            <option value="byItem" selected>11- ملخص عمليات اصناف</option>--}}
                    {{--                            <option value="byDepartment">12- مبيعات الفروع للصنف</option>--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                    <div wire:ignore class="flex items-center mb-4">
                        <input id="report_type" name="report_type" type="checkbox" value="byDepartment" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">عمليات الاصناف بالتفصيل للفروع</label>
                    </div>
                    @error('report_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
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
        <div id="tbl2-container" class="overflow-x-auto mt-9">
            @if(count($group_results) > 0)

                <div class="mb-5 p-2">
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <div class="flex flex-col sm:flex-row gap-4 w-full">
                            <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">
                                <label class="block font-bold mb-5">خيارات اظهار الأعمدة الخاصة</label>
                                <div class="flex flex-row gap-2.5">
                                    <div class="flex items-center mb-4 ml-8">
                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">اظهار</label>
                                    </div>
{{--                                    <div class="flex items-center mb-4 ml-8">--}}
{{--                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التكلفة</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="flex items-center mb-4 ml-8">--}}
{{--                                        <input id="margin" type="checkbox" value="margin" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الهامش</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="flex items-center mb-4 ml-8">--}}
{{--                                        <input id="margin-percentage" type="checkbox" value="margin-percentage" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">النسبة</label>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                </div>
            @endif

            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الكود</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        @if($report_type == 'byItem')
                        <div class="text-sm">الوصف</div>
                        @else
                            <div class="text-sm">الفرع</div>
                        @endif
                    </th>
                    @if($report_type == 'byItem')
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">الوحدة</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">مميز</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">المورد</div>
                        </th>
                    @endif
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">كمية</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">صافي المبيعات</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">متوسط البيع</div>
                    </th>
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
                @endphp
                {{--                @foreach($scribes_results as $record)--}}
                {{--                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->OldCode}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->ItemName}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                {{--                            {{$record->SalUnitMsr}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->Speciality}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->VendorName}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->TotalQuantitySold)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->TotalSalesAmount, 2)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->AverageUnitPrice, 2)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->Cost, 2)}}--}}
                {{--                        </td>--}}
                {{--                        @php //$margin = floatval($record->Svalue) - floatval($record->SalesTotalCost)  @endphp--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->GrossProfit, 2)}}--}}
                {{--                        </td>--}}
                {{--                        @php //$marginPercentage = floatval($record->SalesTotalCost) != 0 ? (($margin / floatval($record->SalesTotalCost))*100) : '0'; @endphp--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->GrossProfitPer, 2)}}--}}
                {{--                        </td>--}}
                {{--                    </tr>--}}
                {{--                    @php $counter++ @endphp--}}
                {{--                @endforeach--}}
                @if($report_type == 'byItem')

                    @foreach($group_results as $record)
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
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
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{number_format($record['GrossProfitPer'], 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach

                @elseif($report_type == 'byDepartment')
                    @foreach($group_results as $outer_record)
                        @foreach($outer_record as $record)
                            @if($record["OldCode"] != $item_code)
                                    <?php $item_code = $record["OldCode"]; ?>
                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
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
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["Department"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                    {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{number_format($record['GrossProfitPer'], 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                    @endforeach

                @endif
                </tbody>
            </table>
            @else
                <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
                    <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
                    <span class="mt-4">لا يوجد نتائج للعرض</span>
                </div>
            @endif
        </div>
    @endif
</div>

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        var selected_cat_type = null;
        Livewire.on('show-container', () => {
            $("#gen-report").html('<b>إنشاء تقرير</b>');

        });

        Livewire.on('finished', () => {
            console.log(selected_cat_type);
            $("#cat_type").select2('val', selected_cat_type);
            old_search_type = $("input[name='search_type']:checked").val();
            console.log('old_search_type:'+ old_search_type);

            if(old_search_type == 'item_code_search') {
                $('#filteration-row2').addClass('hide');
                $('#product-code-row').removeClass('hide');
                $('#submit-row').removeClass('hide');
            }

            if(old_search_type == 'advanced_search') {
                $('#filteration-row2').removeClass('hide');
                $('#product-code-row').addClass('hide');
                $('#submit-row').removeClass('hide');
            }
            // $("#cat_type option[value='"+selected_cat_type+"']").prop('selected', true);
            $('.cost').addClass('hide');
            swal.close();
        });

        $(document).ready(function () {

            $('#dept_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#group_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#cat_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#sp_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });
            $('#vendor_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#product_code').select2({
                dir: "rtl",
                minimumInputLength: 3,
                dropdownCssClass: "select-font-size"
            });

            var prev_depts = $('#dept_id').select2("val");
            var prev_cats = $('#cat_type').select2("val");
            var prev_sps = $('#sp_type').select2("val");
            var prev_vendors = $('#vendor_type').select2("val");

            $('#dept_id').on('change', function (e) {
                var data = $('#dept_id').select2("val");

                if (prev_depts && prev_depts.includes('dept_all') == false && data.includes('dept_all') == true && prev_depts.length != data.length) {
                    $("#dept_id option").prop('selected', false);
                    $("#dept_id option[value='dept_all']").prop('selected', true);

                    prev_depts = $(this).val();
                    $('#dept_id').change();
                }
                else {

                    if (prev_depts && prev_depts.length != data.length) {
                        $("#dept_id option[value='dept_all']").removeAttr('selected');
                        prev_depts = $(this).val();

                        $("#dept_id").change();
                    }
                }
            });

            $("input[name='search_type']").change(function () {
                search_type = $(this).val();
                $('#product_code').val("");

                if (search_type == "item_code_search") {
                    $('#filteration-row2').addClass('hide');
                    $('#product-code-row').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                    $('#product_code').select2({
                        dir: "rtl",
                        minimumInputLength: 3,
                        dropdownCssClass: "select-font-size"
                    });
                }
                else if(search_type == "advanced_search") {
                    $('#filteration-row2').removeClass('hide');
                    $('#product-code-row').addClass('hide');
                    $('#submit-row').addClass('hide');
                }

                // re-intialize the select2
                $('#group_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $("#group_type option[value='select_group']").prop('selected', true);
            });

            $('#group_type').on('change', function (e) {
                var data = $('#group_type').select2("val");

                if (prev_cats && prev_cats.includes('groups_all') == false && data.includes('groups_all') == true && prev_cats.length != data.length) {
                    $("#group_type option").prop('selected', false);
                    $("#group_type option[value='groups_all']").prop('selected', true);

                    prev_cats = $(this).val();
                    $('#group_type').change();
                }
                else {
                    if (prev_cats && prev_cats.length != data.length) {
                        $("#group_type option[value='groups_all']").removeAttr('selected');
                        prev_cats = $(this).val();
                        $("#group_type").change();
                    }
                }

                Swal.fire({
                    title: 'الرجاء الإنتظار',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                });
                Livewire.emit('item-category', data);
            });

            Livewire.on('finished-categories', (categories) => {
                $('#cat_type').empty();
                $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
                for (var index = 0; index < categories.length; index++) {
                    $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
                }
                prev_cats = 'cat_all';
                $("#filteration-row2").removeClass('hide');

                var data = $('#group_type').select2("val");

                if(data == 'commerce') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'farms') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'sundries') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'groups_all') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'select_group') {
                    $('#cat_container').addClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#submit-row').addClass('hide');
                }

                // re-intialize the select2
                $('#group_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#cat_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#sp_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#vendor_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });

                swal.close();
            });

            $('#cat_type').on('change', function (e) {
                var data = $('#cat_type').select2("val");
                console.log('selected: ' + data);
                console.log('prev selected: ' + prev_cats);

                if (prev_cats && prev_cats.includes('cat_all') == false && data.includes('cat_all') == true && prev_cats.length != data.length) {
                    $("#cat_type option").prop('selected', false);
                    $("#cat_type option[value='cat_all']").prop('selected', true);

                    prev_cats = $(this).val();
                    $('#cat_type').change();
                }
                else {
                    if (prev_cats && prev_cats.length != data.length) {
                        $("#cat_type option[value='cat_all']").removeAttr('selected');
                        prev_cats = $(this).val();
                        $("#cat_type").change();
                    }
                }
            });

            $('#sp_type').on('change', function (e) {
                var data = $('#sp_type').select2("val");

                if (prev_sps && prev_sps.includes('sp_all') == false && data.includes('sp_all') == true && prev_sps.length != data.length) {
                    $("#sp_type option").prop('selected', false);
                    $("#sp_type option[value='sp_all']").prop('selected', true);

                    prev_sps = $(this).val();
                    $('#sp_type').change();
                }
                else {
                    if (prev_sps && prev_sps.length != data.length) {
                        $("#sp_type option[value='sp_all']").removeAttr('selected');
                        prev_sps = $(this).val();
                        $("#sp_type").change();
                    }
                }

            });

            $('#vendor_type').on('change', function (e) {
                var data = $('#vendor_type').select2("val");

                if (prev_vendors && prev_vendors.includes('vendor_all') == false && data.includes('vendor_all') == true && prev_vendors.length != data.length) {
                    $("#vendor_type option").prop('selected', false);
                    $("#vendor_type option[value='vendor_all']").prop('selected', true);

                    prev_vendors = $(this).val();
                    $('#vendor_type').change();
                }
                else {
                    if (prev_vendors && prev_vendors.length != data.length) {
                        $("#vendor_type option[value='vendor_all']").removeAttr('selected');
                        prev_vendors = $(this).val();
                        $("#vendor_type").change();
                    }
                }

            });

            $('#gen-report').on('click', function () {

                var report_type = $('#report_type').is(":checked") ? "byDepartment" : "byItem";
                // var report_type = $('#report_type').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var search_type = $("input[name='search_type']:checked").val();
                var product_code = $("#product_code").select2("val");

                var dept_id = $('#dept_id').select2("val");
                var group_type = $('#group_type').select2("val");
                var cat_type = $('#cat_type').select2("val");
                selected_cat_type = $('#cat_type').select2("val");
                var sp_type = group_type == 'groups_all' || group_type == 'commerce' ? $('#sp_type').select2("val") : null;
                var vendor_type = group_type == 'groups_all' || group_type == 'commerce' ? $('#vendor_type').select2("val") : null;

                // clear selections
                $("#cost").prop('checked', false);
                $("#margin").prop('checked', false);
                $("#margin-percentage").prop('checked', false);


                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                // Swal.fire({
                //     title: 'الرجاء الإنتظار',
                //     allowOutsideClick: false,
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     willOpen: () => {
                //         Swal.showLoading()
                //     },
                // });

                // Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code);

                console.log(product_code);
                if (search_type == 'item_code_search') {

                    if(start_date == '' || end_date == '' || dept_id == null || $.trim(product_code) == "") {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        $("#gen-report").html('<b>إنشاء تقرير</b>');
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

                        Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code);
                        // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                    }
                }
                else if (search_type == 'advanced_search') {

                    if (group_type == "commerce" || group_type == "groups_all") {
                        if(start_date == null || start_date == '' || end_date == '' || end_date == null || dept_id == null || cat_type == null || sp_type == null || vendor_type == null) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
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

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code);
                            // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                        }
                    }

                    else if (group_type == "farms" || group_type == "sundries") {
                        if(start_date == '' || end_date == '' || dept_id == null || cat_type == null) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
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

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code);
                            // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                        }
                    }
                    else {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        $("#gen-report").html('<b>إنشاء تقرير</b>');
                    }
                }
            });

            // $('#cost input[type="checkbox"]').on('change', function (e) {
            //
            //     alert('dada');
            //     if ($(this).is(':checked')) {
            //         alert('cost ticked');
            //     }
            //     else {
            //         alert('cost not ticked');
            //     }
            // });

        });

        function hideColumn(type) {

            if (type.checked) {
                $('.cost').removeClass('hide');
                // $('.' + type.value).removeClass('hide');
                // console.log(type.val() + ' not ticked');
            }
            else {
                console.log(type.value + ' ticked');
                // $('.' + type.value).addClass('hide');
                $('.cost').addClass('hide');
            }
        }

    </script>
@stop
@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
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

        #report-logo {
            display: none;
        }
    </style>
@stop
