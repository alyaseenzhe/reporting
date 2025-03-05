@section('title')
    كميات المخزون
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير كميات المخزون</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">المستودع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" selected>الكل</option>
                            <option value="000101" >المستودع الرئيسي</option>
                            <option value="000102" >المستودع الزراعي</option>
                            <option value="000103" >مستودع الآلات والمعدات</option>
                            <option value="000104" >مستودع الفضول</option>
                            <option value="000105" >مستودع النحل</option>
                            <option value="000106" >مستودع العينات</option>
                            <option value="010101" >مستودع الاحساء</option>
                            <option value="010102" >مستودع القرية العيا</option>
                            <option value="010201" >مستودع جدة</option>
                            <option value="010202" >مستودع المدينة المنورة</option>
                            <option value="010301" >مستودع معرض الرياض</option>
                            <option value="010302" >مستودع الرياض</option>
                            <option value="010401" >مستودع معرض وادي الدواسر</option>
                            <option value="010402" >مستودع وادي الدواسر</option>
                            <option value="010501" >مستودع الجوف</option>
                            <option value="010601" >مستودع الدمام</option>
                            <option value="010701" >مستودع معرض الخرج</option>
                            <option value="010702" >مستودع الخرج</option>
                            <option value="010801" >مستودع نجران</option>
                            <option value="010802" >مستودع الباحة</option>
                            <option value="010901" >مستودع حائل</option>
                            <option value="011001" >مستودع تبوك</option>
                            <option value="011101" >مستودع معرض القصيم</option>
                            <option value="011102" >مستودع القصيم</option>
                            <option value="011201" >مستودع ساجر</option>
                            <option value="020101" >مستودع مزرعة الدالوة</option>
                            <option value="020201" >مستودع مزرعة الفضول</option>
                            <option value="020301" >مستودع مزرعة الدلم</option>
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
                            <input type="radio" name="search_type" value="vendor_search"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">بالمورد</label>
                        </div>
                    </div>

                    @error('item_type')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
            </div>
        </div>
        <div id="filteration-row2" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
            <div id="vendor_container" class="w-full">
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
                            <option value="{{ $item['ItemCode'] }}">{{ $item['ScribeCode'] . ' | ' . $item['ItemCode'] . ' | ' . $item['ItemName'] . ' | ' . $item['SalUnitMsr']}}</option>
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
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الكود</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المستودع</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">كمية</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                    $card_code = "*";
                    $item_code = "*";
                @endphp
                @forelse($sap_results as $record)
                    @if($record["CardCode"] != $card_code)
                            <?php $card_code = $record["CardCode"]; ?>
                        <tr style="background-color: #a8a6a4; font-weight: bold; color: #000000; text-align: center">
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["CardCode"]}}
                            </td>
                            <td colspan="2" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="flex flex-row justify-between">
                                    <div>المورد: {{$record["CardName"]}}</div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if($record["ItemCode"] != $item_code)
                            <?php $item_code = $record["ItemCode"]; ?>
                        <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["ItemCode"]}}
                            </td>
                            <td colspan="2" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div class="flex flex-row justify-between">
                                    <div>الصنف: {{$record["ItemName"]}}</div>
                                    <div>الوحدة: {{$record["SalUnitMsr"]}}</div>
                                    <div>التميز: {{$record['Speciality']}}</div>
                                    <div>اجمالي الكمية:
                                        {{ number_format($record['OnHand']) }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if(intval($record['QuantityOnHand']) > 0)
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["WarehouseCode"]}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record['WarehouseName']}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ number_format($record['QuantityOnHand'])}}
                            </td>
                        </tr>
                        @php $counter++ @endphp
                    @endif
                @empty
                    <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
                        <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
                        <span class="mt-4">لا يوجد نتائج للعرض</span>
                    </div>
                @endforelse
                </tbody>
            </table>
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

            old_search_type = $("input[name='search_type']:checked").val();
            console.log('old_search_type:'+ old_search_type);

            if(old_search_type == 'item_code_search') {
                $('#filteration-row2').addClass('hide');
                $('#product-code-row').removeClass('hide');
                $('#submit-row').removeClass('hide');
            }

            if(old_search_type == 'vendor_search') {
                $('#filteration-row2').removeClass('hide');
                $('#product-code-row').addClass('hide');
                $('#submit-row').removeClass('hide');
            }
            // $("#cat_type option[value='"+selected_cat_type+"']").prop('selected', true);
            swal.close();
        });

        $(document).ready(function () {

            $('#dept_id').select2({
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
                else if(search_type == "vendor_search") {
                    $('#filteration-row2').removeClass('hide');
                    $('#product-code-row').addClass('hide');
                    // $('#submit-row').addClass('hide');
                    $('#submit-row').removeClass('hide');
                    $('#vendor_type').select2({
                        dir: "rtl",
                        minimumInputLength: 3,
                        dropdownCssClass: "select-font-size"
                    });
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

                var search_type = $("input[name='search_type']:checked").val();
                var product_code = $("#product_code").select2("val");

                var dept_id = $('#dept_id').select2("val");
                var vendor_type =  $('#vendor_type').select2("val");


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
                // if (search_type == 'item_code_search') {

                if(dept_id == null) {
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

                    Livewire.emit('create-report', search_type, product_code, vendor_type, dept_id);
                    // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                }
                // }
            });

        });


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
