<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use App\Filament\Resources\BranchCropCompositionCollectionResource\Widgets\BranchCropCompositionCollection as BranchCropCompositionCollectionWidget;
use App\Models\BranchCropCollectionItem;
use App\Models\BranchCropCompositionCollection;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Actions;
use Filament\Resources\Form as ResourceForm;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BranchCropCompositionCollectionReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected const ALL_FILTER_VALUE = '__all';

    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'تقرير التركيب المحصولي';

    protected static ?string $pluralLabel = 'تقرير التركيب المحصولي';

    protected static ?string $label = 'تقرير تركيب محصولي';

    protected static string $view = 'filament.resources.branch-crop-composition-collection-resource.pages.branch-crop-composition-collection-report';

    protected static ?string $title = '25- تقرير التركيب المحصولي';

    protected ?string $maxContentWidth = 'full';

    protected static ?string $slug = 'report';

    protected static bool $shouldRegisterNavigation = false;

    public array $filters = [
        'branch_ids' => [self::ALL_FILTER_VALUE],
        'customer_codes' => [self::ALL_FILTER_VALUE],
        'engineer_names' => [self::ALL_FILTER_VALUE],
        'crop_category_ids' => [self::ALL_FILTER_VALUE],
        'crop_item_ids' => [self::ALL_FILTER_VALUE],
    ];

    public array $customerViewData = [];

    public bool $isCustomerModalOpen = false;

    public ?int $selectedCustomerCollectionId = null;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canViewAny(), 403);

        $this->form->fill($this->filters);
        $this->customerViewForm->fill([]);
    }

    public function updatedFilters(): void
    {
        foreach (array_keys($this->filters) as $key) {
            $this->filters[$key] = $this->normalizeFilterValues($this->filters[$key] ?? []);
        }
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('clearFilters')
                ->label('اعادة ضبط الفلاتر')
                ->icon('heroicon-o-x')
                ->action(function (): void {
                    $this->filters = [
                        'branch_ids' => [static::ALL_FILTER_VALUE],
                        'customer_codes' => [static::ALL_FILTER_VALUE],
                        'engineer_names' => [static::ALL_FILTER_VALUE],
                        'crop_category_ids' => [static::ALL_FILTER_VALUE],
                        'crop_item_ids' => [static::ALL_FILTER_VALUE],
                    ];

                    $this->form->fill($this->filters);
                }),
            Actions\Action::make('back')
                ->label('عودة')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
//            Forms\Components\Section::make('Filters')
//                ->schema([
                    Forms\Components\Grid::make(5)
                        ->schema([
                            Forms\Components\Select::make('branch_ids')
                                ->label('الفرع')
                                ->placeholder('')
                                ->options($this->getBranchOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->extraAttributes([
                                    'style' => 'z-index: 30;',])
                                ->preload()
                                ->afterStateUpdated(fn ($state, $old, callable $set) => $set(
                                    'branch_ids',
                                    $this->resolveSelectableFilterState((array) $state, (array) ($old ?? []))
                                ))
                                ->extraAlpineAttributes($this->getImmediateAllFilterRemovalAlpineAttributes())
                                ->reactive(),
                            Forms\Components\Select::make('customer_codes')
                                ->label('العميل')
                                ->placeholder('')
                                ->options($this->getCustomerOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'class' => 'relative z-30',])
                                ->afterStateUpdated(fn ($state, $old, callable $set) => $set(
                                    'customer_codes',
                                    $this->resolveSelectableFilterState((array) $state, (array) ($old ?? []))
                                ))
                                ->extraAlpineAttributes($this->getImmediateAllFilterRemovalAlpineAttributes())
                                ->reactive(),
                            Forms\Components\Select::make('engineer_names')
                                ->label('المهندس المسؤول')
                                ->placeholder('')
                                ->options($this->getEngineerOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(fn ($state, $old, callable $set) => $set(
                                    'engineer_names',
                                    $this->resolveSelectableFilterState((array) $state, (array) ($old ?? []))
                                ))
                                ->extraAlpineAttributes($this->getImmediateAllFilterRemovalAlpineAttributes())
                                ->reactive(),
                            Forms\Components\Select::make('crop_category_ids')
                                ->label('طبيعة المحصول')
                                ->placeholder('')
                                ->options($this->getCropCategoryOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(function ($state, $old, callable $set): void {
                                    $state = $this->resolveSelectableFilterState((array) $state, (array) ($old ?? []));
                                    $set('crop_category_ids', $state);

                                    $selectedItemIds = $this->normalizeFilterValues($this->filters['crop_item_ids'] ?? []);

                                    if (in_array(static::ALL_FILTER_VALUE, $selectedItemIds, true)) {
                                        return;
                                    }

                                    $availableItemIds = array_map(
                                        'strval',
                                        array_keys($this->getCropItemOptions((array) $state))
                                    );

                                    $selectedItemIds = array_values(array_filter(
                                        $selectedItemIds,
                                        fn (string $itemId): bool => in_array($itemId, $availableItemIds, true)
                                    ));

                                    $set(
                                        'crop_item_ids',
                                        count($selectedItemIds) ? $selectedItemIds : [static::ALL_FILTER_VALUE]
                                    );
                                })
                                ->extraAlpineAttributes($this->getImmediateAllFilterRemovalAlpineAttributes())
                                ->reactive(),
                            Forms\Components\Select::make('crop_item_ids')
                                ->label('نوع المحصول')
                                ->placeholder('')
                                ->options(fn (callable $get): array => $this->getCropItemOptions(
                                    $get('crop_category_ids') ?? []
                                ))
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(fn ($state, $old, callable $set) => $set(
                                    'crop_item_ids',
                                    $this->resolveSelectableFilterState((array) $state, (array) ($old ?? []))
                                ))
                                ->extraAlpineAttributes($this->getImmediateAllFilterRemovalAlpineAttributes())
                                ->reactive(),
                        ]),
//                ]),
        ];
    }

    protected function getImmediateAllFilterRemovalAlpineAttributes(): array
    {
        $allValue = static::ALL_FILTER_VALUE;

        return [
            'x-on:change.capture' => <<<JS
                const allValue = '{$allValue}';
                const oldState = Array.isArray(state) ? state.map(String) : [];
                const rawNextState = select.getValue(true) ?? [];

                let nextState = Array.isArray(rawNextState)
                    ? rawNextState.map(String).filter((value) => value !== '')
                    : [String(rawNextState)].filter((value) => value !== '');

                const hasAllInNextState = nextState.includes(allValue);
                const hasAllInOldState = oldState.includes(allValue);

                if (hasAllInNextState && nextState.length > 1) {
                    nextState = hasAllInOldState
                        ? nextState.filter((value) => value !== allValue)
                        : [allValue];
                }

                if (! nextState.length) {
                    nextState = [allValue];
                }

                const normalizedRawState = Array.isArray(rawNextState)
                    ? rawNextState.map(String).filter((value) => value !== '')
                    : [String(rawNextState)].filter((value) => value !== '');

                if (JSON.stringify(nextState) === JSON.stringify(normalizedRawState)) {
                    return;
                }

                isStateBeingUpdated = true;
                state = nextState;
                select.removeActiveItems();
                select.setChoiceByValue(nextState);
                refreshPlaceholder();
                \$nextTick(() => isStateBeingUpdated = false);
            JS,
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'filters';
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath($this->getFormStatePath()),
            'customerViewForm' => $this->makeForm()
                ->context('view')
                ->disabled()
                ->model($this->getCustomerViewFormModel())
                ->schema($this->getCustomerViewFormSchema())
                ->statePath('customerViewData')
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    public function openCustomerModal(int $collectionId): void
    {
        $collection = static::getResource()::getAccessibleCollectionsQuery()
            ->whereKey($collectionId)
            ->firstOrFail();

        $this->selectedCustomerCollectionId = $collection->getKey();

        $data = static::getResource()::mutateDataBeforeFill(
            $collection->attributesToArray(),
            $collection
        );

        $this->customerViewForm->model($collection)->fill($data);
        $this->isCustomerModalOpen = true;
    }

    public function closeCustomerModal(): void
    {
        $this->isCustomerModalOpen = false;
        $this->selectedCustomerCollectionId = null;
        $this->customerViewData = [];
        $this->customerViewForm->model(BranchCropCompositionCollection::class)->fill([]);
    }

    protected function getViewData(): array
    {
        $cropRows = $this->getCustomerCropRows();
        $reportRows = $this->buildHierarchy($cropRows);

        return [
            'reportRows' => $reportRows,
            'cropCount' => count($reportRows),
            'branchCount' => collect($reportRows)->sum(fn (array $crop): int => count($crop['branches'])),
            'customerCount' => collect($reportRows)
                ->flatMap(fn (array $crop): Collection => collect($crop['branches']))
                ->flatMap(fn (array $branch): Collection => collect($branch['customers']))
                ->pluck('customer_code')
                ->unique()
                ->count(),
            'totalAreaHectares' => collect($reportRows)->sum('total_area_hectares'),
        ];
    }

//    protected function getHeaderWidgets(): array
//    {
//        return [
//            BranchCropCompositionCollectionWidget::class,
//        ];
//    }

    protected function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function getWidgetData(): array
    {
        return [
            'chartData' => $this->buildBranchAreaPieChartData($this->getCustomerCropRows()),
        ];
    }

    protected function getCustomerCropRows(): Collection
    {
        $accessibleCollections = $this->getFilteredAccessibleCollectionsQuery();
        $cropCategoryIds = $this->getEffectiveFilterValues($this->filters['crop_category_ids'] ?? []);
        $cropItemIds = $this->getEffectiveFilterValues($this->filters['crop_item_ids'] ?? []);

        return BranchCropCollectionItem::query()
            ->selectRaw('
                MIN(accessible_collections.id) as collection_id,
                branch_crop_collection_items.crop_catalog_category_id,
                branch_crop_collection_items.crop_catalog_item_id,
                accessible_collections.branch_id,
                accessible_collections.type,
                accessible_collections.display_customer_code as customer_code,
                accessible_collections.display_customer_name as customer_name,
                accessible_collections.sap_customer_code,
                accessible_collections.sap_customer_name,
                accessible_collections.engineer_name,
                categories.name as category_name,
                crops.name as crop_name,
                branches.name as branch_name,
                SUM(branch_crop_collection_items.total_area_hectares) as total_area_hectares
            ')
            ->joinSub($accessibleCollections, 'accessible_collections', function ($join): void {
                $join->on(
                    'accessible_collections.id',
                    '=',
                    'branch_crop_collection_items.branch_crop_composition_collection_id'
                );
            })
            ->leftJoin('crop_catalog_categories as categories', 'categories.id', '=', 'branch_crop_collection_items.crop_catalog_category_id')
            ->leftJoin('crop_catalog_items as crops', 'crops.id', '=', 'branch_crop_collection_items.crop_catalog_item_id')
            ->leftJoin('branches', 'branches.id', '=', 'accessible_collections.branch_id')
            ->when(
                count($cropCategoryIds),
                fn (Builder $query) => $query->whereIn(
                    'branch_crop_collection_items.crop_catalog_category_id',
                    $cropCategoryIds
                )
            )
            ->when(
                count($cropItemIds),
                fn (Builder $query) => $query->whereIn(
                    'branch_crop_collection_items.crop_catalog_item_id',
                    $cropItemIds
                )
            )
            ->groupBy([
                'branch_crop_collection_items.crop_catalog_category_id',
                'branch_crop_collection_items.crop_catalog_item_id',
                'accessible_collections.branch_id',
                'accessible_collections.type',
                'accessible_collections.display_customer_code',
                'accessible_collections.display_customer_name',
                'accessible_collections.sap_customer_code',
                'accessible_collections.sap_customer_name',
                'accessible_collections.engineer_name',
                'categories.name',
                'crops.name',
                'branches.name',
            ])
            ->orderBy('categories.name')
            ->orderBy('crops.name')
            ->orderBy('branches.name')
            ->orderBy('accessible_collections.display_customer_name')
            ->get()
            ->map(function ($row): array {
                return [
                    'collection_id' => $row->collection_id ? (int) $row->collection_id : null,
                    'crop_catalog_category_id' => $row->crop_catalog_category_id,
                    'crop_catalog_item_id' => $row->crop_catalog_item_id,
                    'branch_id' => $row->branch_id,
                    'type' => trim((string) ($row->type ?? '')),
                    'customer_code' => trim((string) $row->customer_code),
                    'customer_name' => trim((string) ($row->customer_name ?: '-')),
                    'sap_customer_code' => trim((string) ($row->sap_customer_code ?? '')),
                    'sap_customer_name' => trim((string) ($row->sap_customer_name ?? '')),
                    'engineer_name' => trim((string) ($row->engineer_name ?: '-')),
                    'category_name' => trim((string) ($row->category_name ?: '-')),
                    'crop_name' => trim((string) ($row->crop_name ?: '-')),
                    'branch_name' => trim((string) ($row->branch_name ?: '-')),
                    'total_area_hectares' => round((float) $row->total_area_hectares, 2),
                ];
            });
    }
    protected function buildHierarchy(Collection $rows): array
    {
        return $rows
            ->groupBy(fn (array $row): string => $row['crop_catalog_category_id'] . ':' . $row['crop_catalog_item_id'])
            ->map(function (Collection $cropGroup): array {
                $firstRow = $cropGroup->first();

                $branches = $cropGroup
                    ->groupBy('branch_id')
                    ->map(function (Collection $branchGroup): array {
                        $firstBranchRow = $branchGroup->first();
                        $customers = $branchGroup
                            ->map(function (array $customerRow): array {
                                return [
                                    'collection_id' => $customerRow['collection_id'],
                                    'type' => $customerRow['type'],
                                    'customer_code' => $customerRow['customer_code'],
                                    'customer_name' => $customerRow['customer_name'],
                                    'sap_customer_code' => $customerRow['sap_customer_code'],
                                    'sap_customer_name' => $customerRow['sap_customer_name'],
                                    'engineer_name' => $customerRow['engineer_name'],
                                    'total_area_hectares' => $customerRow['total_area_hectares'],
                                ];
                            })
                            ->sortBy([
                                ['customer_name', 'asc'],
                                ['customer_code', 'asc'],
                            ])
                            ->values()
                            ->all();

                        return [
                            'key' => 'branch-' . $firstBranchRow['branch_id'],
                            'branch_name' => $firstBranchRow['branch_name'],
                            'customers_count' => collect($customers)->pluck('customer_code')->unique()->count(),
                            'total_area_hectares' => round((float) $branchGroup->sum('total_area_hectares'), 2),
                            'customers' => $customers,
                        ];
                    })
                    ->sortBy('branch_name')
                    ->values()
                    ->all();

                return [
                    'key' => 'crop-' . $firstRow['crop_catalog_category_id'] . '-' . $firstRow['crop_catalog_item_id'],
                    'crop_category' => $firstRow['category_name'],
                    'crop_name' => $firstRow['crop_name'],
                    'customers_count' => $cropGroup->pluck('customer_code')->unique()->count(),
                    'total_area_hectares' => round((float) $cropGroup->sum('total_area_hectares'), 2),
                    'branches' => $branches,
                ];
            })
            ->sortBy([
                ['crop_category', 'asc'],
                ['crop_name', 'asc'],
            ])
            ->values()
            ->all();
    }

    protected function buildBranchAreaPieChartData(Collection $rows): array
    {
        $colors = [
            '#0f766e',
            '#2563eb',
            '#f59e0b',
            '#dc2626',
            '#7c3aed',
            '#059669',
            '#ea580c',
            '#4f46e5',
            '#be123c',
            '#0891b2',
        ];

        $branches = $rows
            ->groupBy('branch_id')
            ->map(function (Collection $branchRows) {
                $firstRow = $branchRows->first();

                return [
                    'branch_name' => $firstRow['branch_name'],
                    'total_area_hectares' => round((float) $branchRows->sum('total_area_hectares'), 2),
                ];
            })
            ->sortByDesc('total_area_hectares')
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'المساحة بالهكتار',
                    'data' => $branches->pluck('total_area_hectares')->all(),
                    'backgroundColor' => $branches
                        ->keys()
                        ->map(fn (int $index): string => $colors[$index % count($colors)])
                        ->all(),
                ],
            ],
            'labels' => $branches->pluck('branch_name')->all(),
        ];
    }

    protected function getDisplayCustomerCollectionsQuery(): Builder
    {
        return static::getResource()::getAccessibleCollectionsQuery()
            ->leftJoin('leads', 'leads.id', '=', 'branch_crop_composition_collections.lead_id')
            ->select([
                'branch_crop_composition_collections.id',
                'branch_crop_composition_collections.branch_id',
                'branch_crop_composition_collections.customer_code',
                'branch_crop_composition_collections.customer_name',
                'branch_crop_composition_collections.engineer_name',
                'branch_crop_composition_collections.type',
            ])
            ->selectRaw('branch_crop_composition_collections.customer_code as sap_customer_code')
            ->selectRaw('branch_crop_composition_collections.customer_name as sap_customer_name')
            ->selectRaw("\n                CASE\n                    WHEN branch_crop_composition_collections.type = 'redistribution_customer'\n                        THEN COALESCE(leads.code, branch_crop_composition_collections.customer_code)\n                    ELSE branch_crop_composition_collections.customer_code\n                END as display_customer_code\n            ")
            ->selectRaw("\n                CASE\n                    WHEN branch_crop_composition_collections.type = 'redistribution_customer'\n                        THEN COALESCE(leads.name, branch_crop_composition_collections.customer_name)\n                    ELSE branch_crop_composition_collections.customer_name\n                END as display_customer_name\n            ");
    }
    protected function getFilteredAccessibleCollectionsQuery(): Builder
    {
        $branchIds = $this->getEffectiveFilterValues($this->filters['branch_ids'] ?? []);
        $customerCodes = $this->getEffectiveFilterValues($this->filters['customer_codes'] ?? []);
        $engineerNames = $this->getEffectiveFilterValues($this->filters['engineer_names'] ?? []);

        return $this->getDisplayCustomerCollectionsQuery()
            ->when(
                count($branchIds),
                fn (Builder $query) => $query->whereIn('branch_crop_composition_collections.branch_id', $branchIds)
            )
            ->when(
                count($customerCodes),
                fn (Builder $query) => $query->whereIn(DB::raw("CASE WHEN branch_crop_composition_collections.type = 'redistribution_customer' THEN COALESCE(leads.code, branch_crop_composition_collections.customer_code) ELSE branch_crop_composition_collections.customer_code END"), $customerCodes)
            )
            ->when(
                count($engineerNames),
                fn (Builder $query) => $query->whereIn('branch_crop_composition_collections.engineer_name', $engineerNames)
            );
    }
    protected function getBranchOptions(): array
    {
        return $this->withAllOption(static::getResource()::getAccessibleCollectionsQuery()
            ->leftJoin('branches', 'branches.id', '=', 'branch_crop_composition_collections.branch_id')
            ->orderBy('branches.name')
            ->pluck('branches.name', 'branch_crop_composition_collections.branch_id')
            ->filter()
            ->toArray());
    }

    protected function getCustomerOptions(): array
    {
        return $this->withAllOption($this->getDisplayCustomerCollectionsQuery()
            ->orderBy('display_customer_name')
            ->get()
            ->mapWithKeys(function ($row): array {
                $code = trim((string) $row->display_customer_code);

                if ($code === '') {
                    return [];
                }

                $name = trim((string) ($row->display_customer_name ?? ''));

                return [
                    $code => $name !== '' ? $code . ' - ' . $name : $code,
                ];
            })
            ->all());
    }
    protected function getEngineerOptions(): array
    {
        return $this->withAllOption(static::getResource()::getAccessibleCollectionsQuery()
            ->whereNotNull('engineer_name')
            ->pluck('engineer_name')
            ->map(fn ($name): string => trim((string) $name))
            ->filter()
            ->unique()
            ->sort()
            ->mapWithKeys(fn (string $name): array => [$name => $name])
            ->all());
    }

    protected function getCropCategoryOptions(): array
    {
        $accessibleCollections = static::getResource()::getAccessibleCollectionsQuery()
            ->select('branch_crop_composition_collections.id');

        return $this->withAllOption(BranchCropCollectionItem::query()
            ->joinSub($accessibleCollections, 'accessible_collections', function ($join): void {
                $join->on(
                    'accessible_collections.id',
                    '=',
                    'branch_crop_collection_items.branch_crop_composition_collection_id'
                );
            })
            ->leftJoin('crop_catalog_categories as categories', 'categories.id', '=', 'branch_crop_collection_items.crop_catalog_category_id')
            ->orderBy('categories.name')
            ->pluck('categories.name', 'branch_crop_collection_items.crop_catalog_category_id')
            ->filter()
            ->toArray());
    }

    protected function getCropItemOptions(array $selectedCategoryIds = []): array
    {
        $accessibleCollections = static::getResource()::getAccessibleCollectionsQuery()
            ->select('branch_crop_composition_collections.id');

        $selectedCategoryIds = $this->getEffectiveFilterValues($selectedCategoryIds);

        return $this->withAllOption(BranchCropCollectionItem::query()
            ->joinSub($accessibleCollections, 'accessible_collections', function ($join): void {
                $join->on(
                    'accessible_collections.id',
                    '=',
                    'branch_crop_collection_items.branch_crop_composition_collection_id'
                );
            })
            ->leftJoin('crop_catalog_items as items', 'items.id', '=', 'branch_crop_collection_items.crop_catalog_item_id')
            ->when(
                count($selectedCategoryIds),
                fn (Builder $query) => $query->whereIn(
                    'branch_crop_collection_items.crop_catalog_category_id',
                    $selectedCategoryIds
                )
            )
            ->orderBy('items.name')
            ->pluck('items.name', 'branch_crop_collection_items.crop_catalog_item_id')
            ->filter()
            ->toArray());
    }

    protected function withAllOption(array $options): array
    {
        return [static::ALL_FILTER_VALUE => 'الكل'] + $options;
    }

    protected function normalizeFilterValues(array $values): array
    {
        $values = collect($values)
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values()
            ->all();

        if (! count($values)) {
            return [static::ALL_FILTER_VALUE];
        }

        return $values;
    }

    protected function getEffectiveFilterValues(array $values): array
    {
        $values = $this->normalizeFilterValues($values);

        if (in_array(static::ALL_FILTER_VALUE, $values, true)) {
            return [];
        }

        return $values;
    }

    protected function resolveSelectableFilterState(array $state, array $old): array
    {
        $state = collect($state)
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all();

        $old = $this->normalizeFilterValues($old);

        $hasAllInState = in_array(static::ALL_FILTER_VALUE, $state, true);
        $hasAllInOld = in_array(static::ALL_FILTER_VALUE, $old, true);

        if ($hasAllInState && count($state) > 1) {
            if ($hasAllInOld) {
                $state = array_values(array_filter(
                    $state,
                    fn (string $value): bool => $value !== static::ALL_FILTER_VALUE
                ));
            } else {
                $state = [static::ALL_FILTER_VALUE];
            }
        }

        return $this->normalizeFilterValues($state);
    }

    protected function getCustomerViewFormSchema(): array
    {
        return static::getResource()::form(
            ResourceForm::make()->columns(config('filament.layout.forms.have_inline_labels') ? 1 : 2)
        )->getSchema();
    }

    protected function getCustomerViewFormModel(): BranchCropCompositionCollection | string
    {
        if (! $this->selectedCustomerCollectionId) {
            return BranchCropCompositionCollection::class;
        }

        return static::getResource()::getAccessibleCollectionsQuery()
            ->whereKey($this->selectedCustomerCollectionId)
            ->first() ?? BranchCropCompositionCollection::class;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

}

