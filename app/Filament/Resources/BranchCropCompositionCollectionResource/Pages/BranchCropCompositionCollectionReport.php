<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use App\Models\BranchCropCollectionItem;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class BranchCropCompositionCollectionReport extends Page
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected static string $view = 'filament.resources.branch-crop-composition-collection-resource.pages.branch-crop-composition-collection-report';

    protected static ?string $title = 'Branch Crop Composition Report';

    protected ?string $maxContentWidth = 'full';

    public function mount(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canViewAny(), 403);
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Collections')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('index')),
        ];
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

    protected function getCustomerCropRows(): Collection
    {
        $accessibleCollections = static::getResource()::getAccessibleCollectionsQuery()
            ->select([
                'branch_crop_composition_collections.id',
                'branch_crop_composition_collections.branch_id',
                'branch_crop_composition_collections.customer_code',
                'branch_crop_composition_collections.customer_name',
                'branch_crop_composition_collections.engineer_name',
            ]);

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
}
