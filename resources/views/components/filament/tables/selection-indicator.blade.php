@props([
    'allSelectableRecordsCount',
    'colspan',
    'deselectAllRecordsAction' => 'deselectAllRecords',
    'end' => null,
    'selectAllRecordsAction' => 'selectAllRecords',
    'selectedRecordsCount',
    'selectedRecordsPropertyName' => 'selectedRecords',
])

<div
    wire:key="{{ $this->id }}.table.selection.indicator"
    x-cloak
    x-data="{ isSyncingSelectAll: false, selectAllChecked: false }"
    x-effect="
        if (isSyncingSelectAll) {
            return;
        }

        const selectedCount = {{ $selectedRecordsPropertyName }}.length;
        const totalCount = {{ $allSelectableRecordsCount }};

        selectAllChecked = totalCount > 0 && selectedCount === totalCount;
        $refs.selectAllRecordsCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
    "
    {{ $attributes->class(['filament-tables-selection-indicator flex flex-wrap items-center gap-3 whitespace-nowrap bg-primary-500/10 px-4 py-2 text-sm']) }}
>
    {{ $slot }}

    <div class="flex flex-1 flex-wrap items-center gap-3">
        <x-filament-support::loading-indicator
            x-show="isLoading"
            class="mr-3 inline-block h-4 w-4 text-primary-500 rtl:ml-3 rtl:mr-0"
        />

        <label class="inline-flex items-center gap-2">
            <input
                x-ref="selectAllRecordsCheckbox"
                x-model="selectAllChecked"
                x-on:change="
                    isSyncingSelectAll = true;
                    $refs.selectAllRecordsCheckbox.indeterminate = false;

                    if (selectAllChecked) {
                        {{ $selectAllRecordsAction }}
                    } else {
                        {{ $deselectAllRecordsAction }}
                        {{ $selectedRecordsPropertyName }} = []
                    }

                    $nextTick(() => { isSyncingSelectAll = false })
                "
                class="block rounded border-gray-300 text-primary-600 shadow-sm outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:checked:border-primary-600 dark:checked:bg-primary-600"
                type="checkbox"
            />

            <span class="font-medium text-primary-700 dark:text-primary-300">
                {{ trans_choice('tables::table.selection_indicator.buttons.select_all.label', $allSelectableRecordsCount) }}
            </span>
        </label>

        <span
            @class(['dark:text-white' => config('tables.dark_mode')])
            x-text="
                window.pluralize(@js(__('tables::table.selection_indicator.selected_count')), {{ $selectedRecordsPropertyName }}.length, {
                    count: {{ $selectedRecordsPropertyName }}.length,
                })
            "
        ></span>

        <button
            x-show="{{ $selectedRecordsPropertyName }}.length"
            x-on:click="
                isSyncingSelectAll = true;
                selectAllChecked = false;
                $refs.selectAllRecordsCheckbox.indeterminate = false;
                {{ $deselectAllRecordsAction }}
                {{ $selectedRecordsPropertyName }} = [];
                $nextTick(() => { isSyncingSelectAll = false })
            "
            class="text-sm font-medium text-primary-600"
            type="button"
        >
            {{ __('tables::table.selection_indicator.buttons.deselect_all.label') }}.
        </button>
    </div>

    {{ $end }}
</div>
