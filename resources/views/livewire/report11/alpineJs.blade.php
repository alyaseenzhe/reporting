<script>
    function reportComponent() {
        return {
            // Reactive state
            dept_id: null,
            group_type: 'select_group',
            cat_type: null,
            selected_cat_type: null,
            showCat: false,
            currentCategories: {},

            categories: {
                commerce: {
                    "104": "اسمدة أحادية",
                    "105": "اسمدة مركبة ورقية",
                    "106": "اسمدة مركبة ذوابة",
                    // ... rest
                },
                farms: {
                    "165": "منتج خضار",
                    "166": "منتج فواكة"
                },
                sundries: {
                    "169": "أدوات تعبئة"
                },
                groups_all: {
                    "104": "اسمدة أحادية",
                    "105": "اسمدة مركبة ورقية",
                    // ... rest
                }
            },

            init() {
                // Initialize Select2 (or Alpine-friendly select)
                this.initSelect2();
                Livewire.on('finished', () => {
                    this.selected_cat_type = this.cat_type;
                    this.updateCatVisibility();
                });
            },

            initSelect2() {
                this.$nextTick(() => {
                    $('.select2').select2({ dir: "rtl" }).on('change', (event) => {
                        const name = event.target.getAttribute('x-model');
                        this[name] = $(event.target).val();
                    });
                });
            },

            onDeptChange() {
                // Filtering customers based on selected dept
                Livewire.emit('updateDept', this.dept_id);
            },

            onGroupChange() {
                this.updateCatVisibility();
                Livewire.emit('updateGroup', this.group_type);
            },

            updateCatVisibility() {
                if (this.group_type in this.categories) {
                    this.currentCategories = this.categories[this.group_type];
                    this.showCat = true;
                } else {
                    this.currentCategories = {};
                    this.showCat = false;
                }
                this.cat_type = null;
            },

            generateReport() {
                // Validate required fields
                if (!this.dept_id || !this.group_type) {
                    Swal.fire('خطأ', 'الرجاء تعبئة جميع الحقول', 'error');
                    return;
                }

                let product_code = null;
                if (this.search_type === 'item_code_search') {
                    product_code = this.product_code;
                }

                Livewire.emit('create-report', {
                    dept_id: this.dept_id,
                    group_type: this.group_type,
                    cat_type: this.cat_type,
                    selected_cat_type: this.selected_cat_type,
                    product_code: product_code
                });

                Swal.fire({
                    title: 'الرجاء الإنتظار',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            }
        }
    }
</script>
