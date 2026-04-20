<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    @php
        $containers = $getChildComponentContainers();
        $isItemCreationDisabled = $isItemCreationDisabled();
        $isItemDeletionDisabled = $isItemDeletionDisabled();
        $minItems = $getMinItems();
        $headerContainer = collect($containers)->first();
        $headerComponents = $headerContainer?->getComponents(withHidden: true) ?? [];
        $gridDefaultColumns = $getColumns('default') ?? 1;
        $gridSmColumns = $getColumns('sm');
        $gridMdColumns = $getColumns('md') ?? 12;
        $gridLgColumns = $getColumns('lg');
        $gridXlColumns = $getColumns('xl');
        $gridTwoXlColumns = $getColumns('2xl');
    @endphp

    <div
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->class('filament-forms-repeater-component space-y-2')
        }}
    >
        @if (count($containers))
            <x-filament-support::grid
                :default="$gridDefaultColumns"
                :sm="$gridSmColumns"
                :md="$gridMdColumns"
                :lg="$gridLgColumns"
                :xl="$gridXlColumns"
                :two-xl="$gridTwoXlColumns"
                class="filament-forms-component-container items-end gap-2 rounded-md bg-gray-50 px-2 py-2 dark:bg-gray-800"
            >
                @foreach ($headerComponents as $headerComponent)
                    @php
                        $isHidden = $headerComponent->isHidden();
                        $isRequired = method_exists($headerComponent, 'isRequired') && $headerComponent->isRequired();
                    @endphp

                    <x-filament-support::grid.column
                        :hidden="$isHidden"
                        :default="$headerComponent->getColumnSpan('default')"
                        :sm="$headerComponent->getColumnSpan('sm')"
                        :md="$headerComponent->getColumnSpan('md')"
                        :lg="$headerComponent->getColumnSpan('lg')"
                        :xl="$headerComponent->getColumnSpan('xl')"
                        :twoXl="$headerComponent->getColumnSpan('2xl')"
                        :class="
                            ($maxWidth = $headerComponent->getMaxWidth()) ? match ($maxWidth) {
                                'xs' => 'max-w-xs',
                                'sm' => 'max-w-sm',
                                'md' => 'max-w-md',
                                'lg' => 'max-w-lg',
                                'xl' => 'max-w-xl',
                                '2xl' => 'max-w-2xl',
                                '3xl' => 'max-w-3xl',
                                '4xl' => 'max-w-4xl',
                                '5xl' => 'max-w-5xl',
                                '6xl' => 'max-w-6xl',
                                '7xl' => 'max-w-7xl',
                                default => $maxWidth,
                            } : null
                        "
                    >
                        @if (! $isHidden && filled($label = $headerComponent->getLabel()))
                            <span @class([
                                'text-sm font-medium leading-4 text-gray-700',
                                'dark:text-gray-300' => config('forms.dark_mode'),
                            ])>
                                {{ $label }}@if ($isRequired)<sup
                                    @class([
                                        'text-danger-700 whitespace-nowrap font-medium',
                                        'dark:text-danger-400' => config('forms.dark_mode'),
                                    ])
                                >*</sup>@endif
                            </span>
                        @endif
                    </x-filament-support::grid.column>
                @endforeach

                <div class="hidden md:col-span-1 md:block"></div>
            </x-filament-support::grid>

            <ul class="space-y-2">
                @foreach ($containers as $uuid => $item)
                    @php
                        $canDeleteItem = (! $isItemDeletionDisabled) && (blank($minItems) || count($containers) > $minItems);
                    @endphp

                    <li
                        wire:key="{{ $this->id }}.{{ $item->getStatePath() }}.{{ $field::class }}.compact-item"
{{--                        class="filament-forms-repeater-component-item rounded-md border border-gray-200 bg-white px-2 py-2 shadow-sm dark:border-gray-700 dark:bg-gray-800"--}}
                        class="filament-forms-repeater-component-item  bg-white px-2 py-2  "
                    >
                        <x-filament-support::grid
                            :default="$gridDefaultColumns"
                            :sm="$gridSmColumns"
                            :md="$gridMdColumns"
                            :lg="$gridLgColumns"
                            :xl="$gridXlColumns"
                            :two-xl="$gridTwoXlColumns"
                            class="filament-forms-component-container items-end gap-2"
                        >
                            @foreach ($item->getComponents(withHidden: true) as $formComponent)
                                @php
                                    $isHidden = $formComponent->isHidden();

                                    if (method_exists($formComponent, 'disableLabel')) {
                                        $formComponent->disableLabel();
                                    }
                                @endphp

                                <x-filament-support::grid.column
                                    :wire:key="$formComponent instanceof \Filament\Forms\Components\Field ? $this->id . '.' . $formComponent->getStatePath() . '.' . $formComponent::class : null"
                                    :hidden="$isHidden"
                                    :default="$formComponent->getColumnSpan('default')"
                                    :sm="$formComponent->getColumnSpan('sm')"
                                    :md="$formComponent->getColumnSpan('md')"
                                    :lg="$formComponent->getColumnSpan('lg')"
                                    :xl="$formComponent->getColumnSpan('xl')"
                                    :twoXl="$formComponent->getColumnSpan('2xl')"
                                    :class="
                                        ($maxWidth = $formComponent->getMaxWidth()) ? match ($maxWidth) {
                                            'xs' => 'max-w-xs',
                                            'sm' => 'max-w-sm',
                                            'md' => 'max-w-md',
                                            'lg' => 'max-w-lg',
                                            'xl' => 'max-w-xl',
                                            '2xl' => 'max-w-2xl',
                                            '3xl' => 'max-w-3xl',
                                            '4xl' => 'max-w-4xl',
                                            '5xl' => 'max-w-5xl',
                                            '6xl' => 'max-w-6xl',
                                            '7xl' => 'max-w-7xl',
                                            default => $maxWidth,
                                        } : null
                                    "
                                >
                                    @if (! $isHidden)
                                        {{ $formComponent }}
                                    @endif
                                </x-filament-support::grid.column>
                            @endforeach

                            <div class="flex items-end justify-end md:col-span-1">
                                @if ($canDeleteItem)
                                    <button
                                        title="{{ __('forms::components.repeater.buttons.delete_item.label') }}"
                                        wire:click.stop="dispatchFormEvent('repeater::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        wire:target="dispatchFormEvent('repeater::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        wire:loading.attr="disabled"
                                        type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-danger-200 bg-danger-50 text-danger-600 transition hover:bg-danger-100 hover:text-danger-700 focus:outline-none focus:ring-2 focus:ring-danger-500 focus:ring-offset-1 dark:border-danger-500/30 dark:bg-danger-500/10 dark:text-danger-400"
                                    >
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.delete_item.label') }}
                                        </span>

                                        <x-heroicon-s-trash
                                            class="h-4 w-4"
                                            wire:loading.remove.delay
                                            wire:target="dispatchFormEvent('repeater::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        />

                                        <x-filament-support::loading-indicator
                                            class="h-4 w-4 text-danger-600"
                                            wire:loading.delay
                                            wire:target="dispatchFormEvent('repeater::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                            x-cloak
                                        />
                                    </button>
                                @endif
                            </div>
                        </x-filament-support::grid>
                    </li>
                @endforeach
            </ul>
        @endif

        @if (! $isItemCreationDisabled)
            <div class="flex justify-start">
                <x-forms::button
                    :wire:click="'dispatchFormEvent(\'repeater::createItem\', \'' . $getStatePath() . '\')'"
                    size="sm"
                    outlined
                >
                    {{ $getCreateItemButtonLabel() }}
                </x-forms::button>
            </div>
        @endif
    </div>
</x-dynamic-component>
