@section('title')
    عرض التقرير
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">عرض التقرير</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">الموقع</label>
                    <div style="color: #5222e1">{{$record->location1}}</div>
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">تاريخ التقرير</label>
                    <div style="color: #5222e1">{{$record->report_date}}</div>
                </div>
            </div>
            <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">نوع التقرير</label>
                    <div style="color: #5222e1">
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
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">الموقع كتابةً</label>
                    <div style="color: #5222e1">{{$record->location2}}</div>
                </div>
            </div>
            @if($record->report_type == 'visit')
                <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">اسم العميل</label>
                    <div style="color: #5222e1">{{$record->customer_name}}</div>
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">المرافقون</label>
                    <div style="color: #5222e1">{{$record->companion}}</div>
                </div>
            </div>
            @endif

            <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">
                <div class="w-full">
                    <label class="block font-bold mb-6 text-xs">العمل/المنجزات</label>
                    <div style="color: #5222e1">{{$record->report_note}}</div>
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

            $('#gen-report').on('click', function () {

                // var report_type = $('#report_type').is(":checked") ? "byDepartment" : "byItem";
                var report_type = $('#report_type').val();
                var report_date = $('#report_date').val();
                var customer_name = $('#customer_name').val();
                var location1 = $('#location1').val();
                var location2 = $('#location2').val();
                var companion = $('#companion').val();
                var report_note = $('#report_note').val();

                // var search_type = $("input[name='search_type']:checked").val();
                // var product_code = $("#product_code").val();
                //
                // var dept_id = $('#dept_id').select2("val");
                // var group_type = $('#group_type').select2("val");
                // var cat_type = $('#cat_type').select2("val");
                // selected_cat_type = $('#cat_type').select2("val");
                // var sp_type = group_type == 'groups_all' || group_type == 'commerce' ? $('#sp_type').select2("val") : null;
                // var vendor_type = group_type == 'groups_all' || group_type == 'commerce' ? $('#vendor_type').select2("val") : null;
                //
                // // clear selections
                // $("#cost").prop('checked', false);
                // $("#margin").prop('checked', false);
                // $("#margin-percentage").prop('checked', false);


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


                if($.trim(report_date) == '' || $.trim(location2) == '' || $.trim(report_note) == "") {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إضافة التقرير",
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

                    Livewire.emit('create-report', report_type, report_date, customer_name, location1, location2, companion, report_note);
                    // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                }

                //////////////////
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
    </style>
@stop
