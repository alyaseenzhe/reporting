@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reportForm', () => ({
                search_type: 'item_code_search',
                group_type: 'commerce',
                cat_type: 'cat_all',
                sp_type: 'sp_all',
                marketing_type: 'marketing_all',
                vendor_type: 'vendor_all',
                customer_type: 'customer_all',
                emps_type: 'employees_all',
                start_date: '',
                end_date: '',
                report_type: 'byDepartment',
                selected_cat_type: null,

                groups_all: { /* your groups_all object */ },
                commerce: { /* your commerce object */ },
                farms: { /* your farms object */ },
                sundries: { /* your sundries object */ },

                init() {
                    // Initialize Select2 and handle updates
                    this.initSelect2('#dept_id');
                    this.initSelect2('#group_type');
                    this.initSelect2('#cat_type');
                    this.initSelect2('#sp_type');
                    this.initSelect2('#marketing_type');
                    this.initSelect2('#vendor_type');
                    this.initSelect2('#customer_type');
                    this.initSelect2('#product_code', { minimumInputLength: 3 });

                    Livewire.on('finished', () => this.onFinished());
                },

                initSelect2(selector, options = {}) {
                    const self = this;
                    const $el = $(selector);
                    $el.select2(Object.assign({ dir: 'rtl', dropdownCssClass: 'select-font-size' }, options));
                    $el.on('change', function () {
                        const val = $el.val();
                        if (selector === '#group_type') self.group_type = val;
                        if (selector === '#cat_type') self.cat_type = val;
                        if (selector === '#sp_type') self.sp_type = val;
                        if (selector === '#marketing_type') self.marketing_type = val;
                        if (selector === '#vendor_type') self.vendor_type = val;
                        if (selector === '#customer_type') self.customer_type = val;
                    });
                },

                onFinished() {
                    this.selected_cat_type = this.cat_type;
                    this.handleSearchType();
                },

                handleSearchType() {
                    if (this.search_type === 'item_code_search') {
                        this.showItemCodeFields();
                    } else if (this.search_type === 'advanced_search') {
                        this.showAdvancedFields();
                    }
                },

                showItemCodeFields() {
                    document.querySelector('#filteration-row3').classList.add('hide');
                    document.querySelector('#product-code-row').classList.remove('hide');
                    document.querySelector('#submit-row').classList.remove('hide');
                },

                showAdvancedFields() {
                    document.querySelector('#filteration-row3').classList.remove('hide');
                    document.querySelector('#product-code-row').classList.add('hide');
                    document.querySelector('#submit-row').classList.remove('hide');

                    switch (this.group_type) {
                        case 'commerce':
                            this.showAllContainers(true, true, true, true, true);
                            break;
                        case 'farms':
                        case 'sundries':
                            this.showAllContainers(true, false, false, false, false);
                            break;
                        case 'groups_all':
                            this.showAllContainers(true, true, true, true, true);
                            break;
                        default:
                            this.showAllContainers(false, false, false, false, false);
                    }

                    this.populateCategories();
                },

                showAllContainers(cat, sp, marketing, vendor, customer) {
                    this.toggleClass('#cat_container', !cat);
                    this.toggleClass('#sp_container', !sp);
                    this.toggleClass('#marketing_type_container', !marketing);
                    this.toggleClass('#vendor_container', !vendor);
                    this.toggleClass('#customer_container', !customer);
                    this.toggleClass('#submit-row', !(cat || sp || marketing || vendor || customer));
                },

                toggleClass(selector, addHide) {
                    const el = document.querySelector(selector);
                    if (!el) return;
                    if (addHide) el.classList.add('hide');
                    else el.classList.remove('hide');
                },

                populateCategories() {
                    let options = { 'cat_all': 'الكل' };
                    if (this.group_type === 'commerce') options = {...options, ...this.commerce};
                    else if (this.group_type === 'farms') options = {...options, ...this.farms};
                    else if (this.group_type === 'sundries') options = {...options, ...this.sundries};
                    else if (this.group_type === 'groups_all') options = {...options, ...this.groups_all};

                    const select = $('#cat_type');
                    select.empty();
                    Object.entries(options).forEach(([key, value]) => {
                        select.append(new Option(value, key, key===this.selected_cat_type, key===this.selected_cat_type));
                    });
                    select.trigger('change');
                },

                generateReport() {
                    if (!this.validateForm()) return;

                    Swal.fire({
                        title: 'الرجاء الإنتظار',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    Livewire.emit('create-report', this.start_date, this.end_date, $('#dept_id').val(), this.group_type, this.cat_type, this.sp_type, this.vendor_type, this.report_type, this.search_type, $('#product_code').val(), this.marketing_type, this.customer_type, this.emps_type);
                },

                validateForm() {
                    if (!this.start_date || !this.end_date || !$('#dept_id').val() || (this.search_type === 'item_code_search' && !$('#product_code').val())) {
                        Swal.fire("حدث خطأ", "الرجاء تعبئة جميع الحقول", "error");
                        return false;
                    }
                    if (new Date(this.start_date).getFullYear() < 2023 || new Date(this.end_date).getFullYear() < 2023) {
                        Swal.fire("حدث خطأ", "الرجاء اختيار تواريخ من 2023 واعلى", "error");
                        return false;
                    }
                    return true;
                }
            }));
        });
    </script>
@stop
