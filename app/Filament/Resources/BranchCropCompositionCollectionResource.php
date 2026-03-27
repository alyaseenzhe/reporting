<?php

namespace App\Filament\Resources;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;
use App\Models\BranchCropCollectionCultivationType;
use App\Models\BranchCropCollectionItem;
use App\Models\BranchCropCompositionCollection;
use App\Models\CropCatalogCategory;
use App\Models\CropCatalogItem;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class BranchCropCompositionCollectionResource extends Resource
{
    protected static ?string $model = BranchCropCompositionCollection::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'جمع التركيب المحصولي';

    protected static ?string $pluralLabel = 'نماذج جمع التركيب المحصولي';

    protected static ?string $label = 'نموذج تركيب محصولي';

    /**
     * Build the Filament form schema.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات التركيب المحصولي للعملاء')
                ->schema([
                    Grid::make(2)->schema([
                        DatePicker::make('collection_date')
                            ->label('تاريخ جمع البيانات')
                            ->required(),
                        TextInput::make('branch_name')
                            ->label('الفرع')
                            ->required()
                            ->maxLength(255),
                        Select::make('engineer_id')
                            ->label('المهندس المسؤول')
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(function (string $search): array {
                                return User::query()
                                    ->where('is_active', 1)
                                    ->where('name', 'like', '%' . $search . '%')
                                    ->orderBy('name')
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                return User::query()->whereKey($value)->value('name');
                            }),
                        Select::make('customer_code')
                            ->label('العميل')
                            ->searchable()
                            ->required()
                            ->helperText('ابحث باسم العميل أو رقمه من SAP. عند تعذر الاتصال سيتم عرض نتائج فارغة فقط.')
                            ->getSearchResultsUsing(function (string $search): array {
                                return app(SapCustomerLookupServiceInterface::class)
                                    ->searchCustomers($search);
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $customer = app(SapCustomerLookupServiceInterface::class)
                                    ->findCustomerByCode($value);

                                return $customer['label'] ?? $value;
                            }),
                        TextInput::make('farms_count')
                            ->label('عدد المزارع الخاصة بالعميل')
                            ->required()
                            ->numeric()
                            ->rules(['integer', 'min:1']),
                        TextInput::make('total_farm_area_hectares')
                            ->label('المساحة الاجمالية للمزارع (هكتار)')
                            ->required()
                            ->numeric()
                            ->rules(['numeric', 'min:0.01']),
                    ]),
                ]),
            Section::make('أنواع الزراعة')
                ->schema([
                    Repeater::make('cultivation_types')
                        ->label('تفاصيل أنواع الزراعة')
                        ->minItems(1)
                        ->defaultItems(1)
                        ->schema([
                            Grid::make(4)->schema([
                                Select::make('cultivation_type')
                                    ->label('نوع الزراعة')
                                    ->required()
                                    ->options([
                                        'محميات' => 'محميات',
                                        'رشاشات محورية' => 'رشاشات محورية',
                                        'ري ليات' => 'ري ليات',
                                        'ري غمر' => 'ري غمر',
                                        'أشجار مثمرة' => 'أشجار مثمرة',
                                        'حدائق' => 'حدائق',
                                    ]),
                                TextInput::make('detail_type')
                                    ->label('تفصيل النوع')
                                    ->maxLength(255),
                                TextInput::make('unit_count')
                                    ->label('عدد الوحدات')
                                    ->numeric()
                                    ->rules(['nullable', 'numeric', 'min:0']),
                                TextInput::make('total_area_hectares')
                                    ->label('مساحة اجمالية (هـ)')
                                    ->required()
                                    ->numeric()
                                    ->rules(['numeric', 'min:0.01']),
                            ]),
                        ]),
                ]),
            Section::make('التركيب المحصولي')
                ->schema([
                    Repeater::make('crop_composition_items')
                        ->label('صفوف التركيب المحصولي')
                        ->minItems(1)
                        ->defaultItems(1)
                        ->schema([
                            Grid::make(4)->schema([
                                Select::make('crop_catalog_category_id')
                                    ->label('طبيعة المحصول')
                                    ->required()
                                    ->reactive()
                                    ->options(function (): array {
                                        return CropCatalogCategory::query()
                                            ->orderBy('sort_order')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('crop_catalog_item_id', null);
                                    }),
                                Select::make('crop_catalog_item_id')
                                    ->label('المحصول')
                                    ->required()
                                    ->searchable()
                                    ->options(function (callable $get): array {
                                        $categoryId = $get('crop_catalog_category_id');

                                        if (! $categoryId) {
                                            return [];
                                        }

                                        return CropCatalogItem::query()
                                            ->where('crop_catalog_category_id', $categoryId)
                                            ->orderBy('sort_order')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }),
                                TextInput::make('cycles_per_year')
                                    ->label('عدد العروات/سنة')
                                    ->required()
                                    ->numeric()
                                    ->rules(['integer', 'min:1']),
                                TextInput::make('trees_count')
                                    ->label('عدد الأشجار')
                                    ->numeric()
                                    ->rules(['nullable', 'integer', 'min:0']),
                                TextInput::make('total_area_hectares')
                                    ->label('مساحة كل العروات (هكتار)')
                                    ->required()
                                    ->numeric()
                                    ->rules(['numeric', 'min:0.01']),
                            ]),
                        ]),
                ]),
            Section::make('الملاحظات الختامية')
                ->schema([
                    Textarea::make('opportunities')
                        ->label('الفرص مع المزارع')
                        ->rows(4),
                    Textarea::make('challenges')
                        ->label('التحديات مع المزارع')
                        ->rows(4),
                    Textarea::make('notes')
                        ->label('ملاحظات')
                        ->rows(4),
                ]),
        ]);
    }

    /**
     * Build the Filament table schema.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('collection_date', 'desc')
            ->columns([
                TextColumn::make('collection_date')
                    ->label('تاريخ الجمع')
                    ->date(),
                TextColumn::make('branch_name')
                    ->label('الفرع')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('العميل')
                    ->searchable(),
                TextColumn::make('engineer.name')
                    ->label('المهندس')
                    ->searchable(),
                TextColumn::make('farms_count')
                    ->label('عدد المزارع'),
                TextColumn::make('cropItems_count')
                    ->counts('cropItems')
                    ->label('عدد المحاصيل'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Get the resource relations.
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Get the resource pages.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranchCropCompositionCollections::route('/'),
            'create' => Pages\CreateBranchCropCompositionCollection::route('/create'),
            'edit' => Pages\EditBranchCropCompositionCollection::route('/{record}/edit'),
        ];
    }

    /**
     * Extract the parent form payload before saving.
     */
    public static function extractParentData(array $data): array
    {
        unset($data['cultivation_types'], $data['crop_composition_items']);

        $customer = app(SapCustomerLookupServiceInterface::class)
            ->findCustomerByCode($data['customer_code'] ?? null);

        $data['customer_name'] = $customer['name'] ?? ($data['customer_name'] ?? null);

        return $data;
    }

    /**
     * Extract cultivation type rows from the form payload.
     */
    public static function extractCultivationRows(array $data): array
    {
        return array_values($data['cultivation_types'] ?? []);
    }

    /**
     * Extract crop composition rows from the form payload.
     */
    public static function extractCropRows(array $data): array
    {
        return array_values($data['crop_composition_items'] ?? []);
    }

    /**
     * Sync both child repeaters for the record.
     */
    public static function syncChildren(
        BranchCropCompositionCollection $record,
        array $cultivationRows,
        array $cropRows
    ): void {
        $record->cultivationTypes()->delete();
        $record->cropItems()->delete();

        foreach ($cultivationRows as $index => $row) {
            $record->cultivationTypes()->create([
                'cultivation_type' => $row['cultivation_type'],
                'detail_type' => $row['detail_type'] ?? null,
                'unit_count' => $row['unit_count'] ?? null,
                'total_area_hectares' => $row['total_area_hectares'],
                'sort_order' => $index + 1,
            ]);
        }

        foreach ($cropRows as $index => $row) {
            $record->cropItems()->create([
                'crop_catalog_category_id' => $row['crop_catalog_category_id'],
                'crop_catalog_item_id' => $row['crop_catalog_item_id'],
                'cycles_per_year' => $row['cycles_per_year'],
                'trees_count' => $row['trees_count'] ?? null,
                'total_area_hectares' => $row['total_area_hectares'],
                'sort_order' => $index + 1,
            ]);
        }
    }

    /**
     * Prepare the form state for editing.
     */
    public static function mutateDataBeforeFill(array $data, Model $record): array
    {
        $data['cultivation_types'] = $record->cultivationTypes
            ->map(function (BranchCropCollectionCultivationType $row): array {
                return [
                    'cultivation_type' => $row->cultivation_type,
                    'detail_type' => $row->detail_type,
                    'unit_count' => $row->unit_count,
                    'total_area_hectares' => $row->total_area_hectares,
                ];
            })
            ->toArray();

        $data['crop_composition_items'] = $record->cropItems
            ->map(function (BranchCropCollectionItem $row): array {
                return [
                    'crop_catalog_category_id' => $row->crop_catalog_category_id,
                    'crop_catalog_item_id' => $row->crop_catalog_item_id,
                    'cycles_per_year' => $row->cycles_per_year,
                    'trees_count' => $row->trees_count,
                    'total_area_hectares' => $row->total_area_hectares,
                ];
            })
            ->toArray();

        return $data;
    }
}
