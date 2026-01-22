{{--@props(['multiple' => false])--}}

{{--<div {{ $attributes }} wire:ignore x-data="{--}}
{{--    value: @entangle($attributes->wire('model')),--}}
{{--    init(){--}}
{{--        let input = new TomSelect(this.$refs.select, {--}}
{{--            onChange: (value) => this.value = value,--}}
{{--            items: this.value--}}
{{--        });--}}
{{--    }--}}
{{--}">--}}
{{--    <select x-ref="select" {{ $multiple? 'multiple' : '' }}  x-model="value">--}}
{{--        {{ $slot }}--}}
{{--    </select>--}}
{{--</div>--}}
{{--@props(['multiple' => false])--}}

{{--<div {{ $attributes }} wire:ignore--}}
{{--     x-data="{--}}
{{--        value: @entangle($attributes->wire('model')),--}}
{{--        init() {--}}
{{--            let self = this;--}}

{{--            let tom = new TomSelect(this.$refs.select, {--}}
{{--                plugins: {{ $multiple ? "['remove_button']" : '[]' }},--}}
{{--                onChange(values) {--}}

{{--                    // normalize to array--}}
{{--                    values = Array.isArray(values) ? values : [values];--}}

{{--                    // if ALL selected → keep only ALL--}}
{{--                    if (values.includes('dept_all') && values.length > 1) {--}}
{{--                        tom.setValue(['dept_all'], true);--}}
{{--                        self.value = ['dept_all'];--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // CASE 2: user selected another option while ALL exists--}}
{{--                    if (!values.includes('dept_all') && self.value?.includes('dept_all')) {--}}
{{--                        tom.clear(true);--}}
{{--                        tom.setValue(values, true);--}}
{{--                        self.value = values;--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // if another option selected → remove ALL--}}
{{--                    if (!values.includes('dept_all')) {--}}
{{--                        self.value = values;--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    self.value = values;--}}
{{--                }--}}
{{--            });--}}

{{--            // initial state fix--}}
{{--            if (self.value?.includes('dept_all')) {--}}
{{--                tom.setValue(['dept_all'], true);--}}
{{--            }--}}
{{--        }--}}
{{--     }"--}}
{{-->--}}
{{--    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}>--}}
{{--        {{ $slot }}--}}
{{--    </select>--}}
{{--</div>--}}

{{--@props(['multiple' => false, 'all_option'=>'dept_all'])--}}

{{--<div {{ $attributes }} wire:ignore--}}
{{--     x-data="{--}}
{{--        value: @entangle($attributes->wire('model')),--}}
{{--         allOption: @js($all_option),--}}
{{--        init() {--}}
{{--            let self = this;--}}

{{--            let tom = new TomSelect(this.$refs.select, {--}}
{{--                plugins: {{ $multiple ? "['remove_button']" : '[]' }},--}}

{{--                onItemAdd(value) {--}}

{{--                    // If ALL selected → remove others--}}
{{--                    if (value === self.allOption) {--}}

{{--                    tom.items--}}
{{--                     .filter(v => v !== self.allOption)--}}
{{--                      .forEach(v => tom.removeItem(v, true));--}}

{{--                        self.value = [self.allOption];--}}
{{--                 return;--}}

{{--                        tom.clear(true);--}}
{{--                        tom.addItem('dept_all', true);--}}
{{--                        self.value = ['dept_all'];--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // If another option selected → remove ALL--}}
{{--                    if (tom.items.includes(self.allOption)) {--}}
{{--                        tom.removeItem(self.allOption, true);--}}
{{--                    }--}}

{{--                    self.value = tom.items;--}}
{{--                },--}}

{{--                onItemRemove() {--}}
{{--                    self.value = tom.items;--}}
{{--                }--}}
{{--            });--}}

{{--            // Initial hydration--}}
{{--            if (self.value?.includes(self.allOption)) {--}}
{{--                tom.addItem(self.allOption, true);--}}
{{--            }--}}
{{--        }--}}
{{--     }"--}}
{{-->--}}
{{--    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}>--}}
{{--        @if($all_option)--}}
{{--            <option value="{{ $all_option }}">{{ __('الكل') }}</option>--}}
{{--        @endif--}}
{{--        {{ $slot }}--}}
{{--    </select>--}}
{{--</div>--}}



{{--@props(['multiple' => false, 'all_option' => null, 'options' => []])--}}

{{--<div {{ $attributes }} wire:ignore--}}
{{--     x-data="{--}}
{{--        value: @entangle($attributes->wire('model')),--}}
{{--        allOption: @js($all_option),--}}
{{--        init() {--}}
{{--            let self = this;--}}

{{--            setTimeout(() => {--}}
{{--                let tom = new TomSelect(this.$refs.select, {--}}
{{--                    plugins: {{ $multiple ? "['remove_button']" : '[]' }},--}}

{{--                    onItemAdd(value) {--}}
{{--                        if (value === self.allOption) {--}}
{{--                            tom.items--}}
{{--                                .filter(v => v !== self.allOption)--}}
{{--                                .forEach(v => tom.removeItem(v, true));--}}

{{--                            self.value = [self.allOption];--}}
{{--                            return;--}}
{{--                        }--}}

{{--                        if (tom.items.includes(self.allOption)) {--}}
{{--                            tom.removeItem(self.allOption, true);--}}
{{--                        }--}}

{{--                        self.value = [...tom.items]; // always fresh array--}}
{{--                    },--}}

{{--                    onItemRemove() {--}}
{{--                        self.value = [...tom.items];--}}
{{--                    }--}}
{{--                });--}}

{{--                // Hydrate ALL if pre-selected--}}
{{--                if (self.value?.includes(self.allOption)) {--}}
{{--                    tom.addItem(self.allOption, true);--}}

{{--                }--}}
{{--            }, 0);--}}
{{--        }--}}
{{--     }"--}}
{{-->--}}
{{--    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}>--}}
{{--        @if($all_option)--}}
{{--            <option value="{{ $all_option ?? 'de' }}">{{ __('الكل') }}</option>--}}
{{--        @endif--}}

{{--            {{ $slot }}--}}
{{--    </select>--}}
{{--</div>--}}

{{--@props([--}}
{{--    'multiple' => false,--}}
{{--    'all_option' => null,--}}
{{--])--}}

{{--<div wire:ignore--}}
{{--     x-data="{--}}
{{--        value: @entangle($attributes->wire('model')).defer,--}}
{{--        allOption: $el.dataset.all,--}}
{{--        tom: null,--}}

{{--        init() {--}}
{{--            this.tom = new TomSelect(this.$refs.select, {--}}
{{--                plugins: {{ $multiple ? "['remove_button']" : '[]' }},--}}
{{--                onItemAdd: (val) => {--}}
{{--                    if (this.allOption && val === this.allOption) {--}}
{{--                        this.tom.clear(true);--}}
{{--                        this.tom.addItem(this.allOption, true);--}}
{{--                        this.value = [this.allOption];--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    if (this.allOption && this.tom.items.includes(this.allOption)) {--}}
{{--                        this.tom.removeItem(this.allOption, true);--}}
{{--                    }--}}

{{--                    this.value = [...this.tom.items];--}}
{{--                },--}}
{{--                onItemRemove: () => {--}}
{{--                    this.value = [...this.tom.items];--}}
{{--                }--}}
{{--            });--}}

{{--            if (Array.isArray(this.value) && this.value.length) {--}}
{{--                this.value.forEach(v => this.tom.addItem(v, true));--}}
{{--            }--}}
{{--        }--}}
{{--     }"--}}
{{--     data-all="{{ $all_option }}"--}}
{{-->--}}
{{--    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}>--}}
{{--        @if(!is_null($all_option))--}}
{{--            <option value="{{ $all_option ?? '2' }}">الكل</option>--}}
{{--        @endif--}}

{{--        {{ $slot }}--}}
{{--    </select>--}}
{{--</div>--}}


@props(['multiple' => false])

<div wire:ignore
     x-data="{
        value: @entangle($attributes->wire('model')),
        tom: null,

        init() {
            this.tom = new TomSelect(this.$refs.select, {
                plugins: {{ $multiple ? "['remove_button']" : '[]' }},

                onItemAdd: (val) => {
                    // Find the ALL option element
                    let allOptionEl = Array.from(this.$refs.select.options)
                                            .find(o => o.getAttribute('all_option') === 'true');
                    let allValue = allOptionEl?.value;

                    if (val === allValue) {
                        // ALL selected → remove others
                        this.tom.items.filter(v => v !== allValue).forEach(v => this.tom.removeItem(v, true));
                        this.value = [allValue];
                        return;
                    }

                    // Other option selected → remove ALL if exists
                    if (allValue && this.tom.items.includes(allValue)) {
                        this.tom.removeItem(allValue, true);
                    }

                    this.value = [...this.tom.items];
                },

                onItemRemove: () => {
                    this.value = [...this.tom.items];
                }
            });

            // Hydrate existing values
            if (Array.isArray(this.value) && this.value.length) {
                this.value.forEach(v => this.tom.addItem(v, true));
            }
        }
     }"
>
    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}>
        {{ $slot }}
    </select>
</div>
