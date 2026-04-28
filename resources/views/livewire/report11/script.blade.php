@section('scripts')
    {{--    <script src="{{ asset('js/jquery.min.js') }}"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        const allowedMarketingTypeOptions = @json(method_exists($this, 'marketingTypeOptions') ? $this->marketingTypeOptions() : []);
        const defaultMarketingTypeSelection = @json($this->marketing_type ?? ['marketing_all']);

        function syncMarketingTypeOptions() {
            const $marketingType = $('#marketing_type');

            if (! $marketingType.length) {
                return;
            }

            const selectedValues = Array.isArray(defaultMarketingTypeSelection) && defaultMarketingTypeSelection.length
                ? defaultMarketingTypeSelection
                : ['marketing_all'];

            $marketingType.empty();
            $marketingType.append(new Option('الكل', 'marketing_all', false, selectedValues.includes('marketing_all')));

            Object.entries(allowedMarketingTypeOptions).forEach(([value, label]) => {
                const isSelected = selectedValues.includes(String(value));
                $marketingType.append(new Option(label, value, false, isSelected));
            });
        }

        var groups_all = {
            "104": "اسمدة أحادية",
            "105": "اسمدة مركبة ورقية",
            "106": "اسمدة مركبة ذوابة",
            "107": "اسمدة مركبة حبيبية",
            "108": "اسمدة مركبة سائلة ومعلقة",
            "109": "عناصر نادرة",
            "110": "احماض دبالية",
            "111": "احماض امينية",
            "112": "اعشاب بحرية",
            "114": "مصحح ملوحة وحموضة",
            "115": "اسمدة متخصصة",
            "116": "ترب أساسية",
            "117": "بوتنج سويل",
            "118": "عطن",
            "120": "مبيدات حشرية",
            "121": "مبيدات فطرية",
            "123": "مبيدات اعشاب",
            "124": "حشرات نافعة",
            "125": "مستخلصات نباتية",
            "126": "فرمونات وجواذب",
            "127": "مصائد ولواصق",
            "128": "مواد لاصقة وناشرة",
            "129": "مبيدات قوارض",
            "130": "مبيدات صحة عامة",
            "139": "مرشات يدوية ملحقاتها",
            "140": "مقصات ومحشات",
            "141": "بلاستك تغطية وتعقيم",
            "142": "صواني ومراكن",
            "143": "خيوط واسلاك",
            "144": "شباك وشاش",
            "145": "معدات قياس",
            "147": "مواد تعبئة",
            "148": "الات يدوية",
            "150": "مرشات الية وملحقاتها",
            "151": "اليات وملحقاتها",
            "152": "هوجيندرون",
            "154": "ميجا جرين للصناعات المتطورة",
            "155": "ازود",
            "156": "إدارة مياة أخرى",
            "157": "داكوم",
            "158": "كاروسبراي",
            "159": "اوربيناتي",
            "161": "اخري (مكائن و قطع غيار)",
            "162": "نحل وادواته",
            "163": "صيانة",
            "164": "مبيعات / مشتريات مباشرة",
            "137": "بذور نجيل",
            "132": "بذور محاصيل حقلية",
            "131": "بذور خضار",
            "133": "بذور اعلاف",
            "134": "بذور أشجار مثمرة",
            "135": "بذور ورقيات",
            "165": "منتج خضار",
            "166": "منتج فواكة",
            "169": "أدوات تعبئة",
            "171": "أدوات ومواد بيوت محمية",
            "172": "بذور حبوب",
            "173": "ريفولس",
            "174": "جرينوكي",
            "175": "ركين",
            "177": "مواد تبخير وتعقيم",
            "178": "كائنات دقيقة",
            "179": "ابصال",
            "180": "الأصول الثابتة",
        };
        var commerce = {
            "104": "اسمدة أحادية",
            "105": "اسمدة مركبة ورقية",
            "106": "اسمدة مركبة ذوابة",
            "107": "اسمدة مركبة حبيبية",
            "108": "اسمدة مركبة سائلة ومعلقة",
            "109": "عناصر نادرة",
            "110": "احماض دبالية",
            "111": "احماض امينية",
            "112": "اعشاب بحرية",
            "114": "مصحح ملوحة وحموضة",
            "115": "اسمدة متخصصة",
            "116": "ترب أساسية",
            "117": "بوتنج سويل",
            "118": "عطن",
            "120": "مبيدات حشرية",
            "121": "مبيدات فطرية",
            "123": "مبيدات اعشاب",
            "124": "حشرات نافعة",
            "125": "مستخلصات نباتية",
            "126": "فرمونات وجواذب",
            "127": "مصائد ولواصق",
            "128": "مواد لاصقة وناشرة",
            "129": "مبيدات قوارض",
            "130": "مبيدات صحة عامة",
            "139": "مرشات يدوية ملحقاتها",
            "140": "مقصات ومحشات",
            "141": "بلاستك تغطية وتعقيم",
            "142": "صواني ومراكن",
            "143": "خيوط واسلاك",
            "144": "شباك وشاش",
            "145": "معدات قياس",
            "147": "مواد تعبئة",
            "148": "الات يدوية",
            "150": "مرشات الية وملحقاتها",
            "151": "اليات وملحقاتها",
            "152": "هوجيندرون",
            "154": "ميجا جرين للصناعات المتطورة",
            "155": "ازود",
            "156": "إدارة مياة أخرى",
            "157": "داكوم",
            "158": "كاروسبراي",
            "159": "اوربيناتي",
            "161": "اخري (مكائن و قطع غيار)",
            "162": "نحل وادواته",
            "163": "صيانة",
            "164": "مبيعات / مشتريات مباشرة",
            "137": "بذور نجيل",
            "132": "بذور محاصيل حقلية",
            "131": "بذور خضار",
            "133": "بذور اعلاف",
            "134": "بذور أشجار مثمرة",
            "135": "بذور ورقيات",
            "171": "أدوات ومواد بيوت محمية",
            "172": "بذور حبوب",
            "173": "ريفولس",
            "174": "جرينوكي",
            "175": "ركين",
            "177": "مواد تبخير وتعقيم",
            "178": "كائنات دقيقة",
            "179": "ابصال",
            "180": "الأصول الثابتة",
        };
        var farms = {
            "165": "منتج خضار",
            "166": "منتج فواكه",
        };
        var sundries = {
            "169": "أدوات تعبئة"
        };

        var selected_cat_type = null;

        function setReportButtonLoading() {
            $("#gen-report")
                .prop('disabled', true)
                .html('<b>الرجاء الإنتظار..</b>');
        }

        function resetReportButton() {
            $("#gen-report")
                .prop('disabled', false)
                .html('<b>إنشاء تقرير</b>');
        }

        function closeReportLoadingAlert() {
            if (typeof Swal !== 'undefined' && Swal.close) {
                Swal.close();
            } else if (typeof swal !== 'undefined' && swal.close) {
                swal.close();
            }
        }

        Livewire.on('show-container', () => {
            resetReportButton();

        });

        Livewire.on('finished', () => {
            resetReportButton();
            closeReportLoadingAlert();

            console.log('selected' + selected_cat_type);
            $("#cat_type").select2('val', selected_cat_type);
            old_search_type = $("input[name='search_type']:checked").val();
            // var data = $('#group_type').select2("val");
            console.log('old_search_type:'+ old_search_type);

            if(old_search_type == 'item_code_search') {
                // $('#filteration-row2').addClass('hide');
                $('#filteration-row3').addClass('hide');
                $('#product-code-row').removeClass('hide');
                $('#submit-row').removeClass('hide');
            }

            if(old_search_type == 'advanced_search') {
                // $('#filteration-row2').removeClass('hide');
                $('#filteration-row3').removeClass('hide');
                $('#product-code-row').addClass('hide');
                $('#submit-row').removeClass('hide');

                data = 'commerce';

                if(data == 'commerce') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'farms') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'sundries') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'groups_all') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'select_group') {
                    $('#cat_container').addClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').addClass('hide');
                }
            }




            // $("#cat_type option[value='"+selected_cat_type+"']").prop('selected', true);
            $('.cost').addClass('hide');


            ///////////////////////////

            var div = document.getElementById("branch-container");
            var btn = document.getElementsByClassName("collapsible");
            div.style.display = "none";
            div.classList.toggle("active");
            $("button.collapsible").removeClass("active");

            // btn.classList.toggle("active");
            var content = div.nextElementSibling;
            if (div.classList.contains("active")) {
                div.style.display = "none"; // Show content if active
            } else {
                div.style.display = "block"; // Hide content if not active
            }

            //////////////////////////////////////////////////
        });

        document.addEventListener('livewire:load', function () {
            if (typeof Livewire !== 'undefined' && Livewire.hook) {
                Livewire.hook('message.processed', () => {
                    resetReportButton();
                    closeReportLoadingAlert();
                });

                Livewire.hook('message.failed', () => {
                    resetReportButton();
                    closeReportLoadingAlert();
                });
            }
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

            syncMarketingTypeOptions();
            $('#marketing_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#vendor_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#customer_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#product_code').select2({
                dir: "rtl",
                minimumInputLength: 3,
                dropdownCssClass: "select-font-size"
            });

            // customers = @this.customer_list;
            // // console.log(x);
            // const $select = $("#customer_type");
            // $.each(customers, function(index, item) {
            //     $select.append($("<option>").val(item.CardCode).text(item.CardName));
            // });


            // $('#cat_type').val($('#cat_type option:first').val());
            $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
            var prev_depts = $('#dept_id').select2("val");
            var prev_groups = $('#group_type').select2("val");
            var prev_cats = $('#cat_type').select2("val");
            var prev_sps = $('#sp_type').select2("val");
            var prev_marketing = $('#marketing_type').select2("val");
            var prev_vendors = $('#vendor_type').select2("val");
            var prev_customers = $('#customer_type').select2("val");

            var cust_codes = [];

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

                // to hide the emps based on the selected branch
                console.log("selected branch:" + prev_depts);
                cust_codes = prev_depts;

                cust_codes = cust_codes.map(function(val) {
                    if (val === '0101') return '01';
                    if (val === '0102') return '02';
                    if (val === '0103') return '03';
                    if (val === '0104') return '04';
                    if (val === '0105') return '05';
                    if (val === '0106') return '06';
                    if (val === '0107') return '07';
                    if (val === '0108') return '08';
                    if (val === '0109') return '09';
                    if (val === '0110') return '10';
                    if (val === '0111') return '11';
                    if (val === '0112') return '12';
                    if (val === '0201') return '01';
                    if (val === '0202') return '01';
                    if (val === '0203') return '01';
                    if (val === '0001') return '01';
                    return val; // keep original if no match
                });
                // console.log("selected codes:" + cust_codes);

                $('#dept_id option').each(function() {
                    // Get the value of the data-dept attribute
                    var deptValue = $(this).val();
                    // console.log(deptValue);

                    // Check if the value is NOT in the array

                    if (prev_depts.includes('dept_all')) {
                        // console.log('hide:' + deptValue);
                        // console.log('hideRORO:' + $('#customer_type').select2().data('cust'));
                        $('#emps_type option').show();
                        $('#customer_type option').show();

                        $("#customer_type").select2({
                            dir: "rtl",
                            dropdownCssClass: "select-font-size",
                            templateResult: function (option, container) {
                                $(container).css("display", "block");
                                return option.text;
                            }
                        });
                    }
                    else if (!prev_depts.includes(deptValue)) {
                        // console.log('hide:' + deptValue);
                        // console.log('hideCOCO:' + $('#customer_type').select2().data('cust'));
                        $('option[data-dept="'+ deptValue +'"]').hide();
                    }
                    else {
                        $('option[data-dept="'+ deptValue +'"]').show();
                        // console.log('hide:' + deptValue);
                        // $('option[data-cust="'+ deptValue +'"]').show();
                        // $('#customer_type option[data-cust="'+ deptValue +'"]').css('display', 'block')
                        // $('#customer_type').select2();

                        // if (cust_codes.includes('dept_all')) {
                        //     // cust_codes = ['0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109', '0110', '0111', '0112', '0201', '0202', '0203', '0001'];
                        //     cust_codes = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        // }
                        $("#customer_type").select2({
                            dir: "rtl",
                            dropdownCssClass: "select-font-size",
                            templateResult: function (option, container) {
                                const id = $(option.element).attr("data-select2-id");
                                const value = $(option.element).attr("value");
                                // console.log(value);

                                if (id) {
                                    if (value && cust_codes.some(prefix => value.startsWith(prefix))) {
                                        $(container).css("display", "block");
                                    }
                                    else {
                                        $(container).css("display", "none");
                                    }
                                }

                                return option.text;
                            }
                        });
                    }
                });


            });

            $("input[name='search_type']").change(function () {
                search_type = $(this).val();
                $('#product_code').val("");

                if (search_type == "item_code_search") {
                    // $('#filteration-row2').addClass('hide');
                    $('#filteration-row3').addClass('hide');
                    $('#grouping').addClass('hide');
                    $('#sortBy').addClass('hide');
                    $('#sortDir').addClass('hide');
                    $('#product-code-row').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                    $('#product_code').select2({
                        dir: "rtl",
                        minimumInputLength: 3,
                        dropdownCssClass: "select-font-size"
                    });
                }
                else if(search_type == "advanced_search") {
                    // $('#filteration-row2').removeClass('hide');
                    $('#filteration-row3').removeClass('hide');
                    $('#grouping').removeClass('hide');
                    $('#sortBy').removeClass('hide');
                    $('#sortDir').removeClass('hide');
                    $('#product-code-row').addClass('hide');
                    $('#submit-row').addClass('hide');


                    /* start of hiding and removing filteration 2-3*/
                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
                    // for (var index = 0; index < categories.length; index++) {
                    //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
                    // }
                    Object.keys(commerce).forEach(function(key) {
                        // console.log("Key: " + key + ", Value: " + groups_all[key]);
                        $('#cat_type').append('<option value="' + key + '">' + commerce[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                    /* end of hiding and removing filteration 2-3*/

                    re_intialize();

                    $("#customer_type").select2({
                        dir: "rtl",
                        dropdownCssClass: "select-font-size",
                        templateResult: function (option, container) {
                            const id = $(option.element).attr("data-select2-id");
                            const value = $(option.element).attr("value");
                            // console.log(value);

                            if (id) {
                                if (value && cust_codes.some(prefix => value.startsWith(prefix))) {
                                    $(container).css("display", "block");
                                }
                                else {
                                    $(container).css("display", "none");
                                }
                            }

                            return option.text;
                        }
                    });
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

                // $('#marketing_type').select2("val");
                $('#marketing_type').val($('#marketing_type option:first').val()).trigger('change');

                if (prev_groups && prev_groups.includes('groups_all') == false && data.includes('groups_all') == true && prev_groups.length != data.length) {
                    $("#group_type option").prop('selected', false);
                    $("#group_type option[value='groups_all']").prop('selected', true);

                    prev_groups = $(this).val();
                    $('#group_type').change();
                }
                else {
                    if (prev_groups && prev_groups.length != data.length) {
                        $("#group_type option[value='groups_all']").removeAttr('selected');
                        prev_groups = $(this).val();
                        $("#group_type").change();
                    }
                }

                // Swal.fire({
                //     title: 'الرجاء الإنتظار',
                //     allowOutsideClick: false,
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     willOpen: () => {
                //         Swal.showLoading()
                //     },
                // });
                // @this.group_type = data;
                // @this.set('group_type', data);
                // console.log("group_type:" + @this.group_type);
                console.log("group_typexx:" + data);
                // Livewire.emit('item-category', data);
                // Livewire.emit('change-group-type', data);

                // $('#cat_type').empty();

                if(data == 'commerce') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
                    // for (var index = 0; index < categories.length; index++) {
                    //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
                    // }
                    Object.keys(commerce).forEach(function(key) {
                        // console.log("Key: " + key + ", Value: " + groups_all[key]);
                        $('#cat_type').append('<option value="' + key + '">' + commerce[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'farms') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');

                    Object.keys(farms).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + farms[key] + '</option>');
                    });

                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'sundries') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');

                    Object.keys(sundries).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + sundries[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'groups_all') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');


                    Object.keys(groups_all).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + groups_all[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'select_group') {
                    $('#cat_type').empty();
                    $('#cat_container').addClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').addClass('hide');
                }

                // re-intialize the select2
                re_intialize();
                /*
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
                syncMarketingTypeOptions();
                $('#marketing_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });

                $('#vendor_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                */

            });

            // Livewire.on('finished-categories2', () => {
            //     // $('#cat_type').empty();
            //     // $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
            //     // for (var index = 0; index < categories.length; index++) {
            //     //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
            //     // }
            //     prev_cats = 'cat_all';
            //     $("#filteration-row2").removeClass('hide');
            //
            //     var data = $('#group_type').select2("val");
            //
            //     if(data == 'commerce') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').removeClass('hide');
            //         $('#marketing_type_container').removeClass('hide');
            //         $('#vendor_container').removeClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'farms') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'sundries') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'groups_all') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').removeClass('hide');
            //         $('#vendor_container').removeClass('hide');
            //         $('#marketing_type_container').removeClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'select_group') {
            //         $('#cat_container').addClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').addClass('hide');
            //     }
            //
            //     // re-intialize the select2
            //     $('#group_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#cat_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#sp_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#marketing_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //
            //     $('#vendor_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //
            //     // swal.close();
            // });

            $('#cat_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#cat_type').select2("val");
                // var data = $('#cat_type').select2("val", selected_cat_type);
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

            $('#sp_type').on("select2:select select2:unselecting", function (e) {
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

            $('#marketing_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#marketing_type').select2("val");

                console.log('selected: ' + data);
                console.log('prev selected: ' + prev_marketing);

                if (prev_marketing && prev_marketing.includes('marketing_all') == false && data.includes('marketing_all') == true && prev_marketing.length != data.length) {
                    $("#marketing_type option").prop('selected', false);
                    $("#marketing_type option[value='marketing_all']").prop('selected', true);

                    prev_marketing = $(this).val();
                    $('#marketing_type').change();
                }
                else {
                    if (prev_marketing && prev_marketing.length != data.length) {
                        $("#marketing_type option[value='marketing_all']").removeAttr('selected');
                        prev_marketing = $(this).val();
                        $("#marketing_type").change();
                    }
                }

            });

            $('#vendor_type').on("select2:select select2:unselecting", function (e) {
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

                // var report_type = $('#report_type').is(":checked") ? "byDepartment" : "byItem";
                var report_type = $('#report_type').val();
                // alert(report_type);
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var search_type = $("input[name='search_type']:checked").val();
                var product_code = $("#product_code").select2("val");

                var dept_id = $('#dept_id').select2("val");
                var group_type = $('#group_type').select2("val");
                var cat_type = $('#cat_type').select2("val");
                selected_cat_type = $('#cat_type').select2("val");
                var sp_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#sp_type').select2("val") : null;
                var marketing_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#marketing_type').select2("val") : null;
                var vendor_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#vendor_type').select2("val") : null;
                var customer_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#customer_type').select2("val") : null;
                var emps_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#emps_type').val() : null;

                // clear selections
                $("#cost").prop('checked', false);
                $("#margin").prop('checked', false);
                $("#margin-percentage").prop('checked', false);


                setReportButtonLoading();

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
                        resetReportButton();
                    }
                    else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        resetReportButton();
                    }
                    else {
                        setReportButtonLoading();

                        Swal.fire({
                            title: 'الرجاء الإنتظار',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        customer_type = 'customer_all';
                        emps_type = 'employees_all';
                        Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type, emps_type);
                        // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                    }
                }
                else if (search_type == 'advanced_search') {
                    group_type = 'commerce';

                    if (group_type == "commerce" || group_type == "groups_all") {
                        if(start_date == null || start_date == '' || end_date == '' || end_date == null || dept_id == null || cat_type == null || sp_type == null || vendor_type == null) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            resetReportButton();
                        }
                        else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            resetReportButton();
                        }
                        else {
                            setReportButtonLoading();

                            Swal.fire({
                                title: 'الرجاء الإنتظار',
                                allowOutsideClick: false,
                                showCancelButton: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            });

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type, emps_type);
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
                            resetReportButton();
                        }
                        else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            resetReportButton();
                        }
                        else {
                            setReportButtonLoading();

                            Swal.fire({
                                title: 'الرجاء الإنتظار',
                                allowOutsideClick: false,
                                showCancelButton: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            });

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type, emps_type);
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
                        resetReportButton();
                    }
                }
                else {
                    resetReportButton();
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

        function re_intialize() {

            // re-intialize the select2
            try {
                // $('#group_type').select2({
                //     dir: "rtl",
                //     dropdownCssClass: "select-font-size"
                // });
                $('#cat_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#sp_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                syncMarketingTypeOptions();
                $('#marketing_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#vendor_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });

                $('#customer_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
            }
            catch (e) {

            }


        }
        // this functions returns the subtotals of each grouping and hides extra details
        function summary(type) {

            if (type.checked) {
                $('.summary').addClass('hidden');
                // $('.' + type.value).removeClass('hide');
                // console.log(type.val() + ' not ticked');
            }
            else {
                console.log(type.value + ' ticked');
                // $('.' + type.value).addClass('hide');
                $('.summary').removeClass('hidden');
            }
        }

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

        function show_hide(acc) {
            if ($('.row-'+acc).hasClass('hide')) {
                $('.row-'+acc).removeClass('hide');
                $('.parent-'+acc).text('-');
            } else {
                $('.row-'+acc).addClass('hide');
                $('.parent-'+acc).text('+');
            }

        }

        /* start of collapsible code*/

        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (this.classList.contains("active")) {
                    content.style.display = "block"; // Show content if active
                } else {
                    content.style.display = "none"; // Hide content if not active
                }

            });
        }

        /* end of collapsible code*/

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

        .record-row { opacity: 1; transform: translateY(0); transition: opacity 0.5s ease, transform 0.5s ease; }
        .record-row.hide-row {
            opacity: 0; transform: translateY(-20px); /* Adjust vertical movement if needed */
        }

        #report-logo {
            display: none;
        }

        /*thead th {*/
        /*    top: 0;*/
        /*    position: sticky;*/
        /*    background-color: #666666;*/
        /*    z-index: 20;*/
        /*}*/
        /*thead th {*/
        /*    position: sticky;*/
        /*    top: 0;*/
        /*    background-color: #f1f1f1;*/
        /*    z-index: 1;*/
        /*}*/

        /*.table-container-x {*/
        /*    max-height: 300px;*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*    width: 100%;*/
        /*}*/


        /*#tbl2 thead, tbl2 tfoot, #tbl2 tbody {*/
        /*    display: block;*/
        /*    !*width: 100%;*!*/
        /*}*/
        /*.table-container {*/
        /*    max-height: 400px; !* Adjust the height as needed *!*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*}*/

        /*#tbl2 tbody {*/
        /*    max-height: 300px;*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*    width: 100%;*/
        /*}*/

        /*#tbl2 thead {*/
        /*    position: sticky;*/
        /*    top: 0;*/
        /*    z-index: 2;*/
        /*}*/

        .tbl-fixed {
            overflow-x: scroll;
            overflow-y: scroll;
            height: fit-content;
            max-height: 70vh;
        }

        table th {
            position: sticky;
            top: 0px;
            background: #f8fafc;
            border: 2px solid black;
        }




        /* Style the button that is used to open and close the collapsible content */
        .collapsible {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 5px;
            width: 100%;
            border: none;
            /*text-align: left;*/
            outline: none;
            font-size: 15px;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .collapsible:hover {
            background-color: #ccc;
        }

        /* Style the collapsible content. Note: hidden by default */
        #branch-container {
            padding: 18px 18px;
            display: block;
            overflow: hidden;
            background-color: #f1f1f1;
        }

        .collapsible:after {
            content: '\02795'; /* Unicode character for "plus" sign (+) */
            font-size: 13px;
            color: white;
            float: left;
            margin-left: 5px;
        }

        button.active:after {
            content: "\2796"; /* Unicode character for "minus" sign (-) */
    </style>
@stop
