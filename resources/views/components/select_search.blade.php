@props([
    'multiple' => false,
])

<div  wire:ignore
     x-data="{
        value: @entangle($attributes->wire('model')),
        tom: null,

        init() {
            // Initialize TomSelect
            this.tom = new TomSelect(this.$refs.select, {
                plugins: {{ $multiple ? "['remove_button']" : '[]' }},
                create: false,
                persist: false,
                maxItems: {{ $multiple ? 'null' : 1 }},

                onItemAdd: (val) => {
                    // Detect ALL option
                    let allOptionEl = Array.from(this.$refs.select.options)
                                            .find(o => o.getAttribute('all_option') === 'true');
                    let allValue = allOptionEl?.value;

                    if (val === allValue) {
                        // ALL selected → deselect others, keep ALL in dropdown
                        this.tom.items.filter(v => v !== allValue)
                                      .forEach(v => this.tom.removeItem(v, false));
                        this.value = [allValue];
                        return;
                    }

                    // Other option selected → deselect ALL, keep in dropdown
                    if (allValue && this.tom.items.includes(allValue)) {
                        this.tom.removeItem(allValue, false);
                        this.tom.refreshOptions(false);
                    }

                    this.value = [...this.tom.items];
                },
                onItemRemove: () => {
                    this.value = [...this.tom.items];
                }
            });

            // Hydrate pre-selected values
            if (Array.isArray(this.value) && this.value.length) {
                this.value.forEach(v => this.tom.addItem(v, true));
            }


        }
     }"
>
    <select x-ref="select" {{ $multiple ? 'multiple' : '' }}    class="w-full">
        {{ $slot }}
    </select>
</div>
