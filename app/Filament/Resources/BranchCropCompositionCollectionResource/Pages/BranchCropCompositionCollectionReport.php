<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use App\Filament\Resources\BranchCropCompositionCollectionResource\Widgets\BranchCropCompositionCollection as BranchCropCompositionCollectionWidget;
use App\Models\BranchCropCollectionItem;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BranchCropCompositionCollectionReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected const ALL_FILTER_VALUE = '__all';

    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected static string $view = 'filament.resources.branch-crop-composition-collection-resource.pages.branch-crop-composition-collection-report';

    protected static ?string $title = 'تقرير التركيب المحصولي';

    protected ?string $maxContentWidth = 'full';

    public array $filters = [
        'branch_ids' => [self::ALL_FILTER_VALUE],
        'customer_codes' => [self::ALL_FILTER_VALUE],
        'engineer_names' => [self::ALL_FILTER_VALUE],
        'crop_category_ids' => [self::ALL_FILTER_VALUE],
        'crop_item_ids' => [self::ALL_FILTER_VALUE],
    ];

    public function mount(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canViewAny(), 403);

        $this->form->fill($this->filters);
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
                ->label('Clear Filters')
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
                    Forms\Components\Grid::make(4)
                        ->schema([
                            Forms\Components\Select::make('branch_ids')
                                ->label('الفرع')
                                ->placeholder('')
                                ->options($this->getBranchOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->reactive(),
                            Forms\Components\Select::make('customer_codes')
                                ->label('العميل')
                                ->placeholder('')
                                ->options($this->getCustomerOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->reactive(),
                            Forms\Components\Select::make('engineer_names')
                                ->label('المهندس المسؤول')
                                ->placeholder('')
                                ->options($this->getEngineerOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->reactive(),
                            Forms\Components\Select::make('crop_category_ids')
                                ->label('طبيعة المحصول')
                                ->placeholder('')
                                ->options($this->getCropCategoryOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->reactive(),
                            Forms\Components\Select::make('crop_items_ids')
                                ->label(' المحصول')
                                ->placeholder('')
                                ->options($this->getCropItemOptions())
                                ->multiple()
                                ->default([static::ALL_FILTER_VALUE])
                                ->searchable()
                                ->preload()
                                ->reactive(),
                        ]),
//                ]),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'filters';
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

    protected function getHeaderWidgets(): array
    {
        return [
            BranchCropCompositionCollectionWidget::class,
        ];
    }

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
        $accessibleCollections = $this->getFilteredAccessibleCollectionsQuery()
            ->select([
                'branch_crop_composition_collections.id',
                'branch_crop_composition_collections.branch_id',
                'branch_crop_composition_collections.customer_code',
                'branch_crop_composition_collections.customer_name',
                'branch_crop_composition_collections.engineer_name',
            ]);
        $cropCategoryIds = $this->getEffectiveFilterValues($this->filters['crop_category_ids'] ?? []);

        return BranchCropCollectionItem::query()
            ->selectRaw('
                branch_crop_collection_items.crop_catalog_category_id,
                branch_crop_collection_items.crop_catalog_item_id,
                accessible_collections.branch_id,
                accessible_collections.customer_code,
                accessible_collections.customer_name,
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
            ->groupBy([
                'branch_crop_collection_items.crop_catalog_category_id',
                'branch_crop_collection_items.crop_catalog_item_id',
                'accessible_collections.branch_id',
                'accessible_collections.customer_code',
                'accessible_collections.customer_name',
                'accessible_collections.engineer_name',
                'categories.name',
                'crops.name',
                'branches.name',
            ])
            ->orderBy('categories.name')
            ->orderBy('crops.name')
            ->orderBy('branches.name')
            ->orderBy('accessible_collections.customer_name')
            ->get()
            ->map(function ($row): array {
                return [
                    'crop_catalog_category_id' => $row->crop_catalog_category_id,
                    'crop_catalog_item_id' => $row->crop_catalog_item_id,
                    'branch_id' => $row->branch_id,
                    'customer_code' => trim((string) $row->customer_code),
                    'customer_name' => trim((string) ($row->customer_name ?: '-')),
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
                                    'customer_code' => $customerRow['customer_code'],
                                    'customer_name' => $customerRow['customer_name'],
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

    protected function getFilteredAccessibleCollectionsQuery(): Builder
    {
        $branchIds = $this->getEffectiveFilterValues($this->filters['branch_ids'] ?? []);
        $customerCodes = $this->getEffectiveFilterValues($this->filters['customer_codes'] ?? []);
        $engineerNames = $this->getEffectiveFilterValues($this->filters['engineer_names'] ?? []);
        $cropItem = $this->getEffectiveFilterValues($this->filters['crop_item_ids'] ?? []);

        return static::getResource()::getAccessibleCollectionsQuery()
            ->when(
                count($branchIds),
                fn (Builder $query) => $query->whereIn('branch_id', $branchIds)
            )
            ->when(
                count($customerCodes),
                fn (Builder $query) => $query->whereIn('customer_code', $customerCodes)
            )
            ->when(
                count($engineerNames),
                fn (Builder $query) => $query->whereIn('engineer_name', $engineerNames)
            )
            ->when(
                count($engineerNames),
                fn (Builder $query) => $query->whereIn('engineer_name', $engineerNames)
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
        return $this->withAllOption(static::getResource()::getAccessibleCollectionsQuery()
            ->select(['customer_code', 'customer_name'])
            ->orderBy('customer_name')
            ->get()
            ->mapWithKeys(function ($row): array {
                $code = trim((string) $row->customer_code);

                if ($code === '') {
                    return [];
                }

                $name = trim((string) ($row->customer_name ?? ''));

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

    protected function getCropItemOptions(): array
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
            ->leftJoin('crop_catalog_items as items', 'items.id', '=', 'branch_crop_collection_items.crop_catalog_item_id')
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
            ->values()
            ->all();

        if (in_array(static::ALL_FILTER_VALUE, $values, true) && count($values) > 1) {
            $values = array_values(array_filter(
                $values,
                fn (string $value): bool => $value !== static::ALL_FILTER_VALUE
            ));
        }

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
}
