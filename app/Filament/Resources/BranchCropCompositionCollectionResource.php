<?php

namespace App\Filament\Resources;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;
use App\Models\Branch;
use App\Models\BranchCropCollectionCultivationType;
use App\Models\BranchCropCollectionItem;
use App\Models\BranchCropCompositionCollection;
use App\Models\AgriDetais;
use App\Models\AgriType;
use App\Models\CropCatalogCategory;
use App\Models\CropCatalogItem;
use App\Models\Lead;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class BranchCropCompositionCollectionResource extends Resource
{
    protected const CUSTOMER_TYPE_REGISTERED = 'registered_customer';

    protected const CUSTOMER_TYPE_LEAD = 'lead';

    protected const CUSTOMER_TYPE_REDISTRIBUTION = 'redistribution_customer';

    protected static ?string $model = BranchCropCompositionCollection::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'التركيب المحصولي: قائمة العملاء';

    protected static ?string $pluralLabel = 'التركيب المحصولي: قائمة العملاء';

    protected static ?string $label = 'نموذج تركيب محصولي';

    //protected ?string $maxContentWidth = Width::Full;

    /**
     * Build the Filament form schema.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()
                ->schema([
                    static::getCustomerTypeSelectComponent(),
                ]),
            Section::make('معلومات التركيب المحصولي للعملاء')
                ->schema(function (callable $get): array {
                    return static::getCustomerInformationSectionSchema(
                        (string) ($get('type') ?: static::CUSTOMER_TYPE_REGISTERED)
                    );
/*
                    Grid::make(5)->schema([
                          //  ->required(),
//                        TextInput::make('branch_name')
//                            ->label('الفرع')
//                            ->required()
//                            ->maxLength(255),
                        Select::make('branch_id')
                            ->label('الفرع')
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->default(fn (): ?int => static::getSingleAuthorizedBranchId())
                            ->options(function () {
                                return static::getAuthorizedBranchesQuery()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->afterStateUpdated(function (callable $set): void {
                                $set('customer_code', null);
                                $set('engineer_name', null);
                            }),

                        Select::make('customer_code')
                            ->label('العميل')
                            ->columnSpan(['default' => 1, 'md' =>2])
                            ->searchable()
                            ->preload()
                            ->optionsLimit(10000)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->reactive()
                            ->placeholder('اختر الفرع أولاً ثم ابحث عن العميل')
//                            ->helperText(function (callable $get): string {
//                                if (blank($get('branch_id'))) {
//                                    return 'اختر الفرع أولاً، ثم ابحث باسم العميل أو رقمه من SAP.';
//                                }
//
//                                return 'سيتم عرض العملاء التابعين للفرع المحدد فقط.';
//                            })
                            ->options(function (callable $get): array {
                                return static::getCustomerSelectOptionsForBranch(
                                    static::resolveBranchCodeFromState($get('branch_id')),
                                    '',
                                    $get('customer_code'),
                                    null
                                );
                            })
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $customer = app(SapCustomerLookupServiceInterface::class)
                                    ->findCustomerByCode($state);

                                $set(
                                    'engineer_name',
                                    $customer['slp_name'] ?? null
                                );
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $customer = app(SapCustomerLookupServiceInterface::class)
                                    ->findCustomerByCode($value);

                                return $customer['label'] ?? $value;
                            }),

                        TextInput::make('engineer_name')
                            ->label('المهندس المسؤول')
                            ->disabled()
                            ->dehydrated()
//                            ->columnSpan(2)
//                            ->required()
//                            ->helperText('يظهر المهندس تلقائيا من العميل المختار.')
                            ->formatStateUsing(fn ($state): string => (string) $state),
                        TextInput::make('farms_count')
                            ->label('عدد المزارع الخاصة بالعميل')
                            ->required()
                            ->numeric()
                            ->maxValue(9999999999)
                            ->rules(['integer', 'min:1']),
                        TextInput::make('total_farm_area_hectares')
                            ->label('المساحة الاجمالية للمزارع (هكتار)')
                            ->required()
                            ->numeric()
//                            ->columnSpan(2)
                            ->maxValue(9999999999.99)
                            ->rules(['numeric', 'min:0.01']),

                        DatePicker::make('created_at')
                            ->label('تاريخ جمع البيانات')
                            ->hiddenOn('create')
                            ->disabled(),

                        TextInput::make('created_by')
                            ->label('تم انشاؤه بواسطة')
                            ->hiddenOn('create')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($state, ?Model $record): string {
                                return (string) optional(optional($record)->userUpdate)->name;
                            }),

                        DatePicker::make('updated_at')
                            ->label('تاريخ آخر تحديث')
                            ->hiddenOn('create')
                            ->disabled(),
                        //  ->required(),


                        TextInput::make('updated_by')
                            ->label('آخر تعديل بواسطة')
                            ->hiddenOn('create')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($state, ?Model $record): string {
                                return (string) optional(optional($record)->userUpdate)->name;
                            }),
                    ]),
*/
                }),
            Section::make('أنواع الزراعة')
                ->schema([
                    Repeater::make('cultivation_types')
                        ->label('')
                        ->view('components.filament.forms.compact-inline-repeater')
                        ->disableItemMovement()
                        ->minItems(1)
                        ->defaultItems(1)
                        ->schema([
                            Select::make('agri_type_id')
                                    ->label('نوع الزراعة')
                                    ->required()
//                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(['default' => 1, 'md' => 3])
                                    ->options(function (callable $get): array {
                                        $currentAgriTypeId = $get('agri_type_id');
                                        $selectedAgriTypeIds = collect($get('../../cultivation_types') ?? [])
                                            ->pluck('agri_type_id')
                                            ->filter()
                                            ->reject(function ($agriTypeId) use ($currentAgriTypeId) {
                                                return (string) $agriTypeId === (string) $currentAgriTypeId;
                                            })
                                            ->values()
                                            ->all();

                                        return AgriType::query()
                                            ->orderBy('name')
                                            ->get(['id', 'name'])
                                            ->filter(fn (AgriType $agriType): bool => filled($agriType->name))
                                            ->reject(function (AgriType $agriType) use ($selectedAgriTypeIds): bool {
                                                if (static::isRepeatableAgriType($agriType)) {
                                                    return false;
                                                }

                                                return in_array($agriType->getKey(), $selectedAgriTypeIds, false)
                                                    || in_array((string) $agriType->getKey(), $selectedAgriTypeIds, true);
                                            })
                                            ->mapWithKeys(fn (AgriType $agriType): array => [
                                                $agriType->getKey() => trim((string) $agriType->name),
                                            ])
                                            ->toArray();
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('agri_detail_id', null);
                                    }),


                                TextInput::make('total_area_hectares')
                                    ->label('مساحة اجمالية (هـ)')
                                    ->required()
                                    ->numeric()
                                    ->maxValue(9999999999.99)
                                    ->columnSpan(['default' => 1, 'md' => 3])
                                    ->rules(['numeric', 'min:0.01']),
                            Select::make('agri_detail_id')
                                ->label('تفاصيل الزراعة')
//                                ->searchable()
//                                ->preload()
                                ->reactive()
                                ->columnSpan(['default' => 1, 'md' => 3])
                                ->options(function (callable $get): array {
                                    $agriTypeId = $get('agri_type_id');

                                    if (blank($agriTypeId)) {
                                        return [];
                                    }

                                    $currentAgriDetailId = $get('agri_detail_id');
                                    $selectedAgriDetailIds = collect($get('../../cultivation_types') ?? [])
                                        ->pluck('agri_detail_id')
                                        ->filter()
                                        ->reject(function ($agriDetailId) use ($currentAgriDetailId) {
                                            return (string) $agriDetailId === (string) $currentAgriDetailId;
                                        })
                                        ->values()
                                        ->all();

                                    $query = AgriDetais::query()
                                        ->where('agri_type_id', $agriTypeId)
                                        ->whereNotNull('details')
                                        ->where('details', '!=', '')
                                        ->orderBy('details');

                                    if (count($selectedAgriDetailIds)) {
                                        $query->whereNotIn('id', $selectedAgriDetailIds);
                                    }

                                    return $query
                                        ->get(['id', 'details'])
                                        ->mapWithKeys(fn (AgriDetais $agriDetail): array => [
                                            $agriDetail->getKey() => trim((string) $agriDetail->details),
                                        ])
                                        ->toArray();
                                })

                                ->hidden(function (callable $get): bool {
                                    $agriDetailId = $get('agri_type_id');

                                    if (blank($agriDetailId)) {
                                        return true;
                                    }

                                    return ! AgriDetais::query()
                                        ->where('agri_type_id', $agriDetailId)
                                        ->whereNotNull('details')
                                        ->where('details', '!=', '')
                                        ->exists();
//                                    return ! optional(AgriType::find($agriDetailId))->has_details;
                                }),

                            TextInput::make('unit_count')
                                ->label('عدد الوحدات')
                                ->numeric()
                                ->reactive()
                                ->columnSpan(['default' => 1, 'md' => 2])
                                ->hidden(function (callable $get): bool {
                                    $agriTypeId = $get('agri_type_id');

                                    if (! $agriTypeId) {
                                        return true;
                                    }

                                    return ! optional(AgriType::find($agriTypeId))->has_units;
                                })
                                ->rules(['nullable', 'numeric', 'min:0']),




                        ]),
                ]),
            Section::make('التركيب المحصولي')
                ->schema([
                    Repeater::make('crop_composition_items')
                        ->label('')
                        ->view('components.filament.forms.compact-inline-repeater')
                        ->columns(['default' => 1, 'md' => 12])
                        ->disableItemMovement()
                        ->minItems(1)
                        ->defaultItems(1)
                        ->schema([
//                            Grid::make(4)->schema([
                                Select::make('crop_catalog_category_id')
                                    ->label('طبيعة المحصول')
                                    ->required()
                                    ->reactive()
                                    ->options(function (): array {
                                        return CropCatalogCategory::query()
//                                            ->orderBy('sort_order')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->columnSpan(['default' => 1, 'md' => 3])
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('crop_catalog_item_id', null);

                                    }),
                                Select::make('crop_catalog_item_id')
                                    ->label('نوع المحصول')
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(['default' => 1, 'md' => 2])
                                    ->options(function (callable $get): array {
                                        $categoryId = $get('crop_catalog_category_id');
                                        $currentCropItemId = $get('crop_catalog_item_id');

                                        if (! $categoryId) {
                                            return [];
                                        }

                                        $selectedCropItemIds = collect($get('../../crop_composition_items') ?? [])
                                            ->filter(function (array $row) use ($categoryId, $currentCropItemId): bool {
                                                if (($row['crop_catalog_category_id'] ?? null) != $categoryId) {
                                                    return false;
                                                }

                                                return (string) ($row['crop_catalog_item_id'] ?? '') !== (string) $currentCropItemId;
                                            })
                                            ->pluck('crop_catalog_item_id')
                                            ->filter()
                                            ->values()
                                            ->all();

                                        $query = CropCatalogItem::query()
                                            ->where('crop_catalog_category_id', $categoryId);

                                        if (count($selectedCropItemIds)) {
                                            $query->whereNotIn('id', $selectedCropItemIds);
                                        }

                                        return $query
//                                            ->orderBy('sort_order')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }),


                                TextInput::make('cycles_per_year')
                                    ->label('عدد العروات/سنة')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->columnSpan(['default' => 1, 'md' => 2])
                                    ->rules(['integer', 'min:1']),

                                TextInput::make('total_area_hectares')
                                    ->label('مساحة كل العروات (هـ)')
                                    ->required()
                                    ->numeric()
                                    ->maxValue(9999999999.99)
                                    ->columnSpan(['default' => 1, 'md' => 2])
                                    ->rules(['numeric', 'min:0.01']),
//                                Checkbox::make('show_tree_count')
//                                    ->label('إضافة عدد الأشجار')
//                                    ->reactive()
//                                    ->default(false)
//                                    ->columnSpan(4),
                                TextInput::make('trees_count')
                                    ->label('عدد الأشجار')
                                    ->numeric()
                                    ->maxValue(9999999999)
                                    ->reactive()
                                    ->hidden(function (callable $get): bool {
                                        $catalogCategory = $get('crop_catalog_category_id');

                                        if (! $catalogCategory) {
                                            return true;
                                        }

                                        return ! optional(CropCatalogCategory::find($catalogCategory))->has_trees;
                                    })
//                                    ->rules(['nullable', 'numeric', 'min:0']),

                        //                                    ->hidden(fn (callable $get): bool => ! $get('show_tree_count'))
                                    ->columnSpan(['default' => 1, 'md' => 2])
                                    ->rules(['nullable', 'integer', 'min:0']),
//                            ]),
                        ]),
                ]),
            Section::make('الملاحظات الختامية')
                ->schema([
                    Grid::make(4)->schema([
                    Textarea::make('opportunities')
                        ->label('الفرص مع المزارع')
                        ->rows(4),
                    Textarea::make('challenges')
                        ->label('التحديات مع المزارع')
                        ->rows(4),
                        Textarea::make('companies')
                            ->label('المؤسسات التي يتعامل معها')
                            ->rows(4),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(4),
                    ]),


                ]),
        ]);
    }

    protected static function getCustomerTypeSelectComponent(): Select
    {
        return Select::make('type')
            ->label('نوع العميل')
            ->options(static::getCustomerTypeOptions())
            ->default(static::CUSTOMER_TYPE_REGISTERED)
            ->afterStateHydrated(function (Select $component, $state): void {
                if (blank($state)) {
                    $component->state(static::CUSTOMER_TYPE_REGISTERED);
                }
            })
            ->afterStateUpdated(function ($state, callable $set): void {
                $set('customer_code', null);
                $set('lead_id', null);
                $set('customer_name', null);
                $set('lead_name', null);
                $set('lead_phone', null);
                $set('lead_email', null);
                $set('engineer_id', null);
                $set('engineer_name', null);
//                $set(
//                    'engineer_name',
//                    $state === static::CUSTOMER_TYPE_LEAD || $state === static::CUSTOMER_TYPE_REDISTRIBUTION ? static::getAuthenticatedEngineerName() : null
//                );
            })
            ->reactive();
    }

    protected static function getCustomerInformationSectionSchema(string $customerType): array
    {
        return [
            Grid::make(5)->schema([
                Select::make('branch_id')
                    ->label('الفرع')
                    ->required()
//                    ->searchable()
                    ->reactive()
                    ->default(fn (): ?int => static::getSingleAuthorizedBranchId())
                    ->options(function () {
                        return static::getAuthorizedBranchesQuery()
                            ->orderBy('name')
//                            ->pluck('name', 'id')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        $set('customer_code', null);
                        $set('lead_id', null);
                        $set('customer_name', null);
                        $set('lead_name', null);
                        $set('lead_phone', null);
                        $set('lead_email', null);
                        $set('engineer_id', null);
                        $set('engineer_name',null);
//                        $set(
//                            'engineer_name',
//                            in_array($get('type'), [static::CUSTOMER_TYPE_LEAD, static::CUSTOMER_TYPE_REDISTRIBUTION], true)
//                                ? static::getAuthenticatedEngineerName()
//                                : null
//                        );
                    }),

                Select::make('customer_code')
                    ->label(static::getCustomerCodeFieldLabel($customerType))
                    ->columnSpan(['default' => 1, 'md' => 2])
                    ->searchable()
                    ->preload(false)
                    ->optionsLimit(50)
                   // ->optionsLimit(10000)
                    ->hidden(fn (): bool => $customerType === static::CUSTOMER_TYPE_LEAD)
                    ->required(fn (): bool => $customerType !== static::CUSTOMER_TYPE_LEAD)
//                    ->hidden(fn (): bool => in_array($customerType, [static::CUSTOMER_TYPE_LEAD, static::CUSTOMER_TYPE_REDISTRIBUTION], true))
//                    ->required(fn (): bool => ! in_array($customerType, [static::CUSTOMER_TYPE_LEAD, static::CUSTOMER_TYPE_REDISTRIBUTION], true))
                    ->unique(ignoreRecord: true)
                    ->reactive()
                    ->placeholder('اختر الفرع أولا ثم ابحث ')
                    ->options(function (callable $get) use ($customerType): array {
                        return static::getCustomerSelectOptionsForType(
                            $customerType,
                            static::resolveBranchCodeFromState($get('branch_id')),
                            '',
                            $get('customer_code'),
                            null
                        );
                    })
                    ->afterStateUpdated(function ($state, callable $set): void {
//                        $customer = app(SapCustomerLookupServiceInterface::class)
//                            ->findCustomerByCode($state);

                        $customer = static::getCachedSapCustomer($state);

                        $set(
                            'engineer_name',
                            $customer['slp_name'] ?? null
                        );
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
//                        $customer = app(SapCustomerLookupServiceInterface::class)
//                            ->findCustomerByCode($value);

                        $customer = static::getCachedSapCustomer($value);

                        return $customer['label'] ?? $value;
                    }),

                TextInput::make('engineer_name')
                    ->label('المهندس المسؤول')
                    ->disabled()
                    ->dehydrated()
                    ->hidden(fn (): bool => in_array($customerType, [static::CUSTOMER_TYPE_LEAD], true))
                    ->formatStateUsing(fn ($state): string => (string) $state),






//                Select::make('lead_id')
//                    ->label('العميل')
//                    ->columnSpan(['default' => 1, 'md' => 2])
////                    ->searchable()
////                    ->preload()
//                    ->reactive()
//                    ->hidden(fn (): bool => $customerType == static::CUSTOMER_TYPE_REGISTERED)
//                    ->required(fn (): bool => $customerType === static::CUSTOMER_TYPE_LEAD)
////                    ->options(function (): array {
////                        return Lead::query()
////                            ->orderBy('name')
////                            ->pluck('name', 'id')
////                            ->toArray();
////                    })
//                    ->createOptionForm([
//                        TextInput::make('name')
//                            ->required()
//                            ->maxLength(255),
//                        TextInput::make('phone')
//                            ->tel()
//                            ->maxLength(255),
//                        TextInput::make('email')
//                            ->email()
//                            ->maxLength(255),
//                        TextInput::make('business')
//                            ->maxLength(255),
//                    ])
//                    ->createOptionUsing(function (array $data) use ($customerType): int {
//                        $data['code'] = Lead::generateNextCode(
//                            $customerType === static::CUSTOMER_TYPE_REDISTRIBUTION ? 's' : 'l'
//                        );
//
//                        return Lead::query()->create($data)->getKey();
//                    })
//                    ->afterStateUpdated(function ($state, callable $set): void {
//                        $lead = Lead::query()->find($state);
//
//                        $set('customer_name', $lead?->name);
//                        $set('engineer_name', null);
//                    })
//                    ->getOptionLabelUsing(function ($value): ?string {
//                        return Lead::query()->whereKey($value)->value('name');
//                    }),
                Select::make('lead_id')
                    ->label('العميل')
                    ->columnSpan(['default' => 1, 'md' => 2])
                    ->hidden()
                    ->required(false)
                    ->options(function (callable $get): array {
                        $leadId = $get('lead_id');

                        if (blank($leadId)) {
                            return [];
                        }

                        return Lead::query()
                            ->whereKey($leadId)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->suffixIcon(null)
                    ->extraInputAttributes([
                        'style' => 'appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:none;',
                    ])
                    ->placeholder('اضف عميل جديد')
                    ->searchable(false)
                    ->preload(false)
                    ->extraAttributes([
                        'class' => 'hide-select-input',
                    ])
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('رقم التواصل')
                            //->tel()
                            ->maxLength(10),

                        TextInput::make('email')
                            ->label('الإيميل')
                            ->email()
                            ->maxLength(255),
//
//                        TextInput::make('business')
//                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, callable $get) use ($customerType): int {
                        $branchCode = trim((string) static::resolveBranchCodeFromState($get('branch_id')));

                        if ($branchCode === '') {
                            throw ValidationException::withMessages([
                                'branch_id' => 'Please select a branch first.',
                            ]);
                        }

                        $data['code'] = Lead::generateNextCode(
                            $customerType === static::CUSTOMER_TYPE_REDISTRIBUTION ? 'S' : 'L',
                            $branchCode
                        );

                        return Lead::create($data)->id;
                    })
//                    ->afterStateUpdated(function ($state, callable $set): void {
//                        if (filled($state)) {
//                            $set('engineer_name', static::getAuthenticatedEngineerName());
//                        }
//                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        return Lead::query()->whereKey($value)->value('name');
                    }),
                TextInput::make('lead_name')
                    ->label('اسم العميل ')
                    ->columnSpan(['default' => 1, 'md' => 2])
                    ->hidden(fn (): bool => $customerType == static::CUSTOMER_TYPE_REGISTERED)
                    ->required(fn (): bool => $customerType !== static::CUSTOMER_TYPE_REGISTERED)
                    ->maxLength(255),
                Select::make('engineer_id')
                    ->label('المهندس المسؤول')
                    ->options(fn (callable $get): array => static::getLeadEngineerOptions($get('branch_id')))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) use ($customerType): void {
                        static::syncLeadEngineerSelection($customerType, $state, $set, $get);
                    })
                    ->hidden(fn (): bool => $customerType != static::CUSTOMER_TYPE_LEAD),

                TextInput::make('area')->label('المنطقة')
                    ->maxLength(255),
                TextInput::make('contact')->label('جهة الاتصال')
                    ->hidden(fn (): bool => $customerType == static::CUSTOMER_TYPE_REGISTERED)
                    ->maxLength(255),
                TextInput::make('lead_phone')
                    ->label('رقم الجوال')
                    ->hidden(fn (): bool => $customerType == static::CUSTOMER_TYPE_REGISTERED)
                    ->maxLength(10),
                TextInput::make('lead_email')
                    ->label('الإيميل')
                    ->hidden(fn (): bool => $customerType == static::CUSTOMER_TYPE_REGISTERED)
                    ->email()
                    ->maxLength(255),

                TextInput::make('farms_count')
                    ->label('عدد المزارع')
                    ->required()
                    ->numeric()
                    ->maxValue(9999999999)
                    ->rules(['integer', 'min:1']),
                TextInput::make('total_farm_area_hectares')
//                    ->label('المساحة الإجمالية (هـ)')
                    ->label('مساحة كل المزارع (هـ)')
                    ->required()
                    ->numeric()
                    ->maxValue(9999999999.99)
                    ->rules(['numeric', 'min:0.01']),

//                DatePicker::make('created_at')
//                    ->label('تاريخ انشاء السجل')
//                    ->hiddenOn('create')
//                    ->disabled(),
//
//                TextInput::make('created_by')
//                    ->label('انشيء بواسطة')
//                    ->hiddenOn('create')
//                    ->disabled()
//                    ->dehydrated(false)
//                    ->formatStateUsing(function ($state, ?Model $record): string {
//                        return (string) optional(optional($record)->userCreate)->name;
//                    }),
//
//                DatePicker::make('updated_at')
//                    ->label('تاريخ آخر تعديل')
//                    ->hiddenOn('create')
//                    ->disabled(),
//
//                TextInput::make('updated_by')
//                    ->label('عدل بواسطة')
//                    ->hiddenOn('create')
//                    ->disabled()
//                    ->dehydrated(false)
//                    ->formatStateUsing(function ($state, ?Model $record): string {
//                        return (string) optional(optional($record)->userUpdate)->name;
//                    }),
            ]),
            Grid::make(4)
                ->schema([
                    DatePicker::make('created_at')
                        ->label('تاريخ انشاء السجل')
                        ->hiddenOn('create')
                        ->disabled(),

                    TextInput::make('created_by')
                        ->label('انشيء بواسطة')
                        ->hiddenOn('create')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(function ($state, ?Model $record): string {
                            return (string) optional(optional($record)->userCreate)->name;
                        }),

                    DatePicker::make('updated_at')
                        ->label('تاريخ آخر تعديل')
                        ->hiddenOn('create')
                        ->disabled(),

                    TextInput::make('updated_by')
                        ->label('عدل بواسطة')
                        ->hiddenOn('create')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(function ($state, ?Model $record): string {
                            return (string) optional(optional($record)->userUpdate)->name;
                        }),
                    ])
        ];
    }

    protected static function getCustomerTypeOptions(): array
    {
        return [
            static::CUSTOMER_TYPE_REGISTERED => 'عميل مسجل',
            static::CUSTOMER_TYPE_LEAD => 'عميل محتمل',
            static::CUSTOMER_TYPE_REDISTRIBUTION => 'عميل اعادة التوزيع',
        ];
    }

    protected static function getCustomerCodeFieldLabel(string $customerType): string
    {
        return $customerType === static::CUSTOMER_TYPE_REDISTRIBUTION
            ? 'المؤسسة'
            : 'العميل';
    }

    protected static function getCustomerSelectOptionsForType(
        string $customerType,
        ?string $branchCode,
        ?string $search,
        ?string $currentCustomerCode = null,
        ?int $limit = 50
    ): array {
        $customers = static::getCustomerSelectOptionsForBranchRows(
            $branchCode,
            $search,
            $currentCustomerCode,
            $limit
        );

        if ($customerType === static::CUSTOMER_TYPE_REDISTRIBUTION) {
            $customers = static::filterRedistributionCustomerRows($customers);
        }

        return static::customerRowsToOptions($customers);
    }

    /**
     * Build the Filament table schema.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('customer_code')
                    ->formatStateUsing(fn ($state, Model $record): string => static::getDisplayCustomerCode($record))
                    ->label('كود العميل')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search): void {
                            $query->where('customer_code', 'like', "%{$search}%")
                                ->orWhereHas('lead', fn (Builder $query) => $query->where('code', 'like', "%{$search}%"));
                        });
                    }),
                TextColumn::make('customer_name')
                    ->formatStateUsing(fn ($state, Model $record): string => static::getDisplayCustomerName($record))
                    ->label('العميل')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search): void {
                            $query->where('customer_name', 'like', "%{$search}%")
                                ->orWhereHas('lead', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
                        });
                    }),
                TextColumn::make('sap_customer')
                    ->label('المؤسسة')
                    ->formatStateUsing(fn ($state, Model $record): string => static::getSapCustomerLabel($record)),
//                TextColumn::make('branch.name')
//                    ->label('الفرع')
//                    ->searchable(),
//                TextColumn::make('engineer_name')
//                    ->label('المهندس المسؤول')
//                    ->formatStateUsing(function ($state, Model $record): string {
////                        $customer = app(SapCustomerLookupServiceInterface::class)
////                            ->findCustomerByCode($record->customer_code);
//                        $customer = static::getCachedSapCustomer($record->customer_code);
//
//                        return (string) ($customer['slp_name'] ?? $state ?? '');
//                    })
//                    ->searchable(
//                        query: fn (Builder $query, string $search): Builder => static::applySapEngineerNameSearch($query, $search)
//                    ),
                TextColumn::make('engineer_name')
                    ->label('المهندس المسؤول'),
                TextColumn::make('total_farm_area_hectares')
//                    ->label('مساحة المزارع (هـ)')
                    ->label(' المزارع (هـ)')
                    ->searchable(),
                TextColumn::make('cultivation_types_sum_total_area_hectares')
//                    ->label('مساحة الزراعة (هـ)')
                    ->label(' الزراعة (هـ)')
                    ->formatStateUsing(fn ($state): string => number_format((float) ($state ?? 0), 2)),
                TextColumn::make('items_sum_total_area_hectares')
                    ->label('التركيب المحصولي (هـ)')
                    ->formatStateUsing(fn ($state): string => number_format((float) ($state ?? 0), 2)),
            ])
            ->filters([
                Filter::make('customer_code')
                    ->form([
                        TextInput::make('customer_code')
                            ->label('رقم العميل')
                            ->placeholder('اكتب رقم العميل'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['customer_code'] ?? null,
                            function (Builder $query, $name): Builder {
                                return $query->where(function (Builder $query) use ($name): void {
                                    $query->where('customer_code', 'like', "%{$name}%")
                                        ->orWhereHas('lead', fn (Builder $query) => $query->where('code', 'like', "%{$name}%"));
                                });
                            }
                        );
                    }),
                Filter::make('customer_name')
                    ->form([
                        TextInput::make('customer_name')
                            ->label('اسم العميل')
                            ->placeholder('اكتب اسم العميل'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['customer_name'] ?? null,
                            function (Builder $query, $name): Builder {
                                return $query->where(function (Builder $query) use ($name): void {
                                    $query->where('customer_name', 'like', "%{$name}%")
                                        ->orWhereHas('lead', fn (Builder $query) => $query->where('name', 'like', "%{$name}%"));
                                });
                            }
                        );
                    }),
//                Filter::make('engineer_name')
//                    ->form([
//                        TextInput::make('engineer_name')
//                            ->label('اسم المهندس')
//                            ->placeholder('اكتب اسم المهندس'),
//                    ])
//                    ->query(function (Builder $query, array $data): Builder {
//                        return $query->when(
//                            $data['engineer_name'] ?? null,
//                            fn (Builder $query, $engineerName) => static::applySapEngineerNameSearch($query, $engineerName)
//                        );
//                    }),
                SelectFilter::make('branch')
                    ->label('الفرع')
                    ->relationship('branch', 'name'),
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
    /**
     * Get the resource relations.
     */
    public static function getRelations(): array
    {
        return [];
    }

//    public static function getNavigationItems(): array
//    {
//        return array_merge(parent::getNavigationItems(), [
//            NavigationItem::make('تقرير التركيب المحصولي')
//                ->group(static::getNavigationGroup())
//                ->icon('heroicon-o-chart-bar')
//                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName() . '.report'))
//                ->url(static::getUrl('report')),
//        ]);
//    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName() . '.index'))
                ->url(static::getUrl('index')),

            NavigationItem::make('تقرير التركيب المحصولي')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-chart-bar')
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName() . '.report'))
                ->url(static::getUrl('report')),
        ];
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check()
            && static::getAuthorizedBranchesQuery()->exists()
            && static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return auth()->check()
            && static::getAuthorizedBranchesQuery()->exists()
            && (
                static::userCanManageOthersCropRecords()
                || static::userCanManageOwnCropRecords()
            );
    }

    public static function canCreate(): bool
    {
        return auth()->check()
            && static::getAuthorizedBranchesQuery()->exists()
            && (
                static::userHasCropPermission('create-only-own-crop')
                || static::userHasCropPermission('create-others-crop')
                || static::isAdminUser()
            );
    }

    public static function canEdit(Model $record): bool
    {
        if (! static::canAccessRecordBranch($record)) {
            return false;
        }

        if (static::userHasCropPermission('edit-others-crop') || static::isAdminUser()) {
            return true;
        }

        return static::userHasCropPermission('edit-only-own-crop')
            && static::recordBelongsToCurrentUser($record);
    }

    public static function canDelete(Model $record): bool
    {
        if (! static::canAccessRecordBranch($record)) {
            return false;
        }

        if (static::userHasCropPermission('delete-others-crop') || static::isAdminUser()) {
            return true;
        }

        return static::userHasCropPermission('delete-only-own-crop')
            && static::recordBelongsToCurrentUser($record);
    }

    public static function canView(Model $record): bool
    {
        if (! static::canAccessRecordBranch($record)) {
            return false;
        }

        if (static::canEdit($record)) {
            return true;
        }

        if (static::userHasCropPermission('view-others-crop') || static::isAdminUser()) {
            return true;
        }

        return static::userHasCropPermission('view-only-own-crop')
            && static::recordBelongsToCurrentUser($record);
    }

    public static function getEloquentQuery(): Builder
    {
        return static::applyCollectionAccessScope(
            parent::getEloquentQuery()->with(['lead'])->withSum('cultivationTypes as cultivation_types_sum_total_area_hectares', 'total_area_hectares')
            ->withSum('cropItems as items_sum_total_area_hectares', 'total_area_hectares' )
        );
    }

    public static function getAccessibleCollectionsQuery(): Builder
    {
        return static::applyCollectionAccessScope(static::$model::query());
    }

    /**
     * Get the resource pages.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranchCropCompositionCollections::route('/'),
            'report' => Pages\BranchCropCompositionCollectionReport::route('/report'),
            'create' => Pages\CreateBranchCropCompositionCollection::route('/create'),
            'view' => Pages\ViewBranchCropCompositionCollection::route('/{record}'),
            'edit' => Pages\EditBranchCropCompositionCollection::route('/{record}/edit'),
        ];
    }

    /**
     * Extract the parent form payload before saving.
     */
    public static function prepareParentData(array $data): array
    {
        if (in_array($data['type'] ?? null, [static::CUSTOMER_TYPE_LEAD, static::CUSTOMER_TYPE_REDISTRIBUTION], true)) {
            $lead = static::upsertLeadFromFormData($data);
            $data['lead_id'] = $lead->getKey();
        }

        return static::extractParentData($data);
    }

    public static function extractParentData(array $data): array
    {
        unset($data['cultivation_types'], $data['crop_composition_items'], $data['lead_name'], $data['lead_phone'], $data['lead_email']);

        abort_unless(
            static::getAuthorizedBranchesQuery()
                ->whereKey($data['branch_id'] ?? null)
                ->exists(),
            403
        );

        if (($data['type'] ?? null) === static::CUSTOMER_TYPE_LEAD) {
            if (! static::isLeadEngineerAssignableToBranch($data['engineer_id'] ?? null, $data['branch_id'] ?? null)) {
                throw ValidationException::withMessages([
                    'engineer_id' => 'The selected engineer is not assigned to the selected branch.',
                ]);
            }

            $lead = Lead::query()->find($data['lead_id'] ?? null);

            $data['customer_code'] = $lead->code ?? null;
            $data['customer_name'] = $lead->name ?? ($data['customer_name'] ?? null);
//            $data['engineer_name'] = static::getAuthenticatedEngineerName();
            $data['engineer_name'] = static::resolveEngineerNameFromUserId($data['engineer_id'] ?? null)
                ?? ($data['engineer_name'] ?? null);

            return $data;
        }

        if (($data['type'] ?? null) === static::CUSTOMER_TYPE_REDISTRIBUTION) {
//            $customer = app(SapCustomerLookupServiceInterface::class)
//                ->findCustomerByCode($data['customer_code'] ?? null);

            $customer = static::getCachedSapCustomer($data['customer_code'] ?? null);
            $data['customer_name'] = $customer['name'] ?? ($data['customer_name'] ?? null);
//            $data['engineer_name'] = static::getAuthenticatedEngineerName();
            $data['engineer_id'] = Auth::id();

            return $data;
        }

//        $customer = app(SapCustomerLookupServiceInterface::class)
//            ->findCustomerByCode($data['customer_code'] ?? null);

        $customer = static::getCachedSapCustomer($data['customer_code'] ?? null);

        $data['customer_name'] = $customer['name'] ?? ($data['customer_name'] ?? null);
        $data['engineer_name'] = $customer['slp_name'] ?? ($data['engineer_name'] ?? null);
        $data['engineer_id'] = null;

        return $data;
    }

    protected static function upsertLeadFromFormData(array $data): Lead
    {
        $branchCode = trim((string) static::resolveBranchCodeFromState($data['branch_id'] ?? null));

        if ($branchCode === '') {
            throw ValidationException::withMessages([
                'branch_id' => 'Please select a branch first.',
            ]);
        }

        $attributes = [
            'name' => trim((string) (($data['lead_name'] ?? $data['customer_name']) ?? '')),
            'phone' => static::normalizeOptionalFormValue($data['lead_phone'] ?? null),
            'email' => static::normalizeOptionalFormValue($data['lead_email'] ?? null),
        ];

        $lead = Lead::query()->find($data['lead_id'] ?? null);

        if ($lead) {
            $lead->fill($attributes);
            $lead->save();

            return $lead;
        }

        $attributes['code'] = Lead::generateNextCode(
            ($data['type'] ?? null) === static::CUSTOMER_TYPE_REDISTRIBUTION ? 'S' : 'L',
            $branchCode
        );

        return Lead::query()->create($attributes);
    }

    /**
     * Extract cultivation type rows from the form payload.
     */
    public static function extractCultivationRows(array $data): array
    {
        return array_values($data['cultivation_types'] ?? []);
    }

    /**
     * Ensure cultivation rows don't repeat the same agri type/detail combination.
     */
    public static function validateCultivationRowsUnique(array $cultivationRows): void
    {
        $seen = [];

        foreach ($cultivationRows as $index => $row) {
            $agriTypeId = $row['agri_type_id'] ?? null;
            $agriDetailId = $row['agri_detail_id'] ?? null;

            if (blank($agriTypeId)) {
                continue;
            }

            $uniqueKey = static::isRepeatableAgriTypeId($agriTypeId)
                ? implode(':', [
                    (string) $agriTypeId,
                    $agriDetailId === null ? 'null' : (string) $agriDetailId,
                ])
                : (string) $agriTypeId;

            if (isset($seen[$uniqueKey])) {
                throw ValidationException::withMessages([
                    'data.cultivation_types' => static::isRepeatableAgriTypeId($agriTypeId)
                        ? 'The same agri type and agri detail combination cannot be added more than once.'
                        : 'The same agri type cannot be added more than once.',
                ]);
            }

            $seen[$uniqueKey] = $index;
        }
    }

    protected static function isRepeatableAgriType(AgriType $agriType): bool
    {
        return trim((string) $agriType->name) === 'محميات';
    }

    protected static function isRepeatableAgriTypeId($agriTypeId): bool
    {
        if (blank($agriTypeId)) {
            return false;
        }

        $agriType = AgriType::query()->find($agriTypeId);

        if (! $agriType) {
            return false;
        }

        return static::isRepeatableAgriType($agriType);
    }

    /**
     * Extract crop composition rows from the form payload.
     */
    public static function extractCropRows(array $data): array
    {
        return array_values($data['crop_composition_items'] ?? []);
    }

    /**
     * Ensure crop rows don't repeat the same crop within the same category.
     */
    public static function validateCropRowsUnique(array $cropRows): void
    {
        $seen = [];

        foreach ($cropRows as $index => $row) {
            $categoryId = $row['crop_catalog_category_id'] ?? null;
            $cropItemId = $row['crop_catalog_item_id'] ?? null;

            if (blank($categoryId) || blank($cropItemId)) {
                continue;
            }

            $uniqueKey = implode(':', [
                (string) $categoryId,
                (string) $cropItemId,
            ]);

            if (isset($seen[$uniqueKey])) {
                throw ValidationException::withMessages([
                    'data.crop_composition_items' => 'The same crop cannot be added more than once under the same crop category.',
                ]);
            }

            $seen[$uniqueKey] = $index;
        }
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
                'agri_type_id' => $row['agri_type_id'] ?? null,
                'agri_detail_id' => $row['agri_detail_id'] ?? null,
                'unit_count' => $row['unit_count'] ?? null,
                'total_area_hectares' => $row['total_area_hectares'],
//                'sort_order' => $index + 1,
            ]);
        }

        foreach ($cropRows as $index => $row) {
            $record->cropItems()->create([
                'crop_catalog_category_id' => $row['crop_catalog_category_id'],
                'crop_catalog_item_id' => $row['crop_catalog_item_id'],
                'cycles_per_year' => $row['cycles_per_year'],
                'trees_count' => $row['trees_count'] ?? null,
                'total_area_hectares' => $row['total_area_hectares'],
//                'sort_order' => $index + 1,
            ]);
        }
    }

    /**
     * Prepare the form state for editing.
     */
    public static function mutateDataBeforeFill(array $data, Model $record): array
    {
        if (in_array($record->type ?? null, [static::CUSTOMER_TYPE_LEAD, static::CUSTOMER_TYPE_REDISTRIBUTION], true)) {
            $data['lead_id'] = $record->lead_id;
            $data['lead_name'] = optional($record->lead)->name;
            $data['lead_phone'] = optional($record->lead)->phone;
            $data['lead_email'] = optional($record->lead)->email;
            $data['engineer_name'] = $record->engineer_name;

            if (($record->type ?? null) === static::CUSTOMER_TYPE_LEAD) {
                $data['engineer_id'] = $record->engineer_id;
                $data['customer_name'] = optional($record->lead)->name ?? $record->customer_name;
            }
        } else {
//            $customer = app(SapCustomerLookupServiceInterface::class)
//                ->findCustomerByCode($record->customer_code);

            $customer = static::getCachedSapCustomer($record->customer_code);

            $data['engineer_name'] = $customer['slp_name'] ?? ($record->engineer_name ?? null);
            $data['lead_name'] = null;
            $data['lead_phone'] = null;
            $data['lead_email'] = null;
        }

        $data['cultivation_types'] = $record->cultivationTypes
            ->map(function (BranchCropCollectionCultivationType $row): array {
                return [
                    'agri_type_id' => $row->agri_type_id,
                    'agri_detail_id' => $row->agri_detail_id,
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

    protected static function resolveBranchCodeFromState($branchId): ?string
    {
        if (blank($branchId)) {
            return null;
        }

        return static::getAuthorizedBranchesQuery()
            ->whereKey($branchId)
            ->value('code');
    }

    protected static function getAuthorizedBranchesQuery(): Builder
    {
        return Branch::query()->whereIn('code', static::getAuthorizedBranchCodes());
    }

    protected static function applyCollectionAccessScope(Builder $query): Builder
    {
        $authorizedBranchIds = static::getAuthorizedBranchesQuery()->pluck('id');

        if ($authorizedBranchIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        $query->whereIn('branch_id', $authorizedBranchIds);

        if (static::userCanManageOthersCropRecords()) {
            return $query;
        }

        if (static::userCanManageOwnCropRecords()) {
            return $query->where('engineer_name', Auth::user()->name);
        }

        return $query->whereRaw('1 = 0');
    }

    protected static function getSingleAuthorizedBranchId(): ?int
    {
        $branchIds = static::getAuthorizedBranchesQuery()
            ->limit(2)
            ->pluck('id');

        if ($branchIds->count() !== 1) {
            return null;
        }

        return (int) $branchIds->first();
    }

    protected static function canAccessRecordBranch(Model $record): bool
    {
        return static::getAuthorizedBranchesQuery()
            ->whereKey($record->branch_id)
            ->exists();
    }

    protected static function recordBelongsToCurrentUser(Model $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

//        $customer = app(SapCustomerLookupServiceInterface::class)
//            ->findCustomerByCode($record->customer_code);

        $customer = static::getCachedSapCustomer($record->customer_code);

        $engineerName = $customer['slp_name'] ?? $record->engineer_name;

        return trim((string) $engineerName) === trim((string) $user->name);
    }

    protected static function userCanManageOthersCropRecords(): bool
    {
        return static::isAdminUser()
            || static::userHasCropPermission('view-others-crop')
            || static::userHasCropPermission('edit-others-crop')
            || static::userHasCropPermission('delete-others-crop');
    }

    protected static function userCanManageOwnCropRecords(): bool
    {
        return static::isAdminUser()
            || static::userHasCropPermission('view-only-own-crop')
            || static::userHasCropPermission('edit-only-own-crop')
            || static::userHasCropPermission('delete-only-own-crop');
    }

    protected static function userHasCropPermission(string $permission): bool
    {
        $user = Auth::user();

        if (! $user || ! $user->user_group) {
            return false;
        }

        $cropPermissions = json_decode($user->user_group->crops ?? '[]', true);

        return is_array($cropPermissions) && in_array($permission, $cropPermissions, true);
    }

    protected static function getCustomerSelectOptionsForBranch(
        ?string $branchCode,
        ?string $search,
        ?string $currentCustomerCode = null,
        ?int $limit = 50
    ): array {
        return static::customerRowsToOptions(static::getCustomerSelectOptionsForBranchRows(
            $branchCode,
            $search,
            $currentCustomerCode,
            $limit
        ));
    }

    protected static function getCustomerSelectOptionsForBranchRows(
        ?string $branchCode,
        ?string $search,
        ?string $currentCustomerCode = null,
        ?int $limit = 50
    ): array {
        if (blank($branchCode)) {
            return [];
        }

        $customerPrefixes = static::getCustomerPrefixesForBranchCode($branchCode);

        if (! count($customerPrefixes)) {
            return [];
        }

        $customers = app(SapCustomerLookupServiceInterface::class)
            ->searchCustomerRows($search, $customerPrefixes, $limit);

        $customers = static::filterCustomerRowsForSelectedBranch($customers, $branchCode);
        $customers = static::filterExistingCollectionCustomerRows($customers, $currentCustomerCode);
        $customers = static::filterCustomerRowsForCreatePermission($customers);

        return $customers;
    }

    protected static function filterRedistributionCustomerRows(array $customers): array
    {
        return collect($customers)
            ->filter(fn (array $customer): bool => (bool) ($customer['property_1'] ?? false))
            ->values()
            ->all();
    }

    protected static function filterCustomersForCreatePermission(array $customers): array
    {
        if (static::isAdminUser() || static::userHasCropPermission('create-others-crop')) {
            return $customers;
        }

        if (! static::userHasCropPermission('create-only-own-crop')) {
            return $customers;
        }

        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return collect($customers)
            ->filter(function ($label, $customerCode) use ($user): bool {
//                $customer = app(SapCustomerLookupServiceInterface::class)
//                    ->findCustomerByCode($customerCode);

                $customer = static::getCachedSapCustomer($customerCode);

                return trim((string) ($customer['slp_name'] ?? '')) === trim((string) $user->name);
            })
            ->toArray();
    }

    protected static function filterCustomerRowsForCreatePermission(array $customers): array
    {
        if (static::isAdminUser() || static::userHasCropPermission('create-others-crop')) {
            return $customers;
        }

        if (! static::userHasCropPermission('create-only-own-crop')) {
            return $customers;
        }

        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return collect($customers)
            ->filter(function (array $customer) use ($user): bool {
                $engineerName = $customer['slp_name'] ?? null;

                if (blank($engineerName) && filled($customer['code'] ?? null)) {
//                    $customerDetails = app(SapCustomerLookupServiceInterface::class)
//                        ->findCustomerByCode($customer['code']);

                    $customerDetails = static::getCachedSapCustomer($customer['code']);

                    $engineerName = $customerDetails['slp_name'] ?? null;
                }

                return trim((string) $engineerName) === trim((string) $user->name);
            })
            ->values()
            ->all();
    }

    protected static function filterCustomersForSelectedBranch(array $customers, ?string $branchCode): array
    {
        if (blank($branchCode)) {
            return [];
        }

        return collect($customers)
            ->filter(fn ($label, $customerCode): bool => static::customerBelongsToBranch($customerCode, $branchCode))
            ->toArray();
    }

    protected static function filterCustomerRowsForSelectedBranch(array $customers, ?string $branchCode): array
    {
        if (blank($branchCode)) {
            return [];
        }

        return collect($customers)
            ->filter(fn (array $customer): bool => static::customerBelongsToBranch($customer['code'] ?? null, $branchCode))
            ->values()
            ->all();
    }

    protected static function applySapEngineerNameSearch(Builder $query, ?string $engineerName): Builder
    {
        $matchingCustomerCodes = static::getCustomerCodesForSapEngineerName($engineerName);

        if (! count($matchingCustomerCodes)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('customer_code', $matchingCustomerCodes);
    }

    protected static function getCustomerCodesForSapEngineerName(?string $engineerName): array
    {
        $engineerName = trim(mb_strtolower((string) $engineerName));

        if ($engineerName === '') {
            return [];
        }

        return BranchCropCompositionCollection::query()
            ->whereNotNull('customer_code')
            ->pluck('customer_code')
            ->unique()
            ->filter(function ($customerCode) use ($engineerName): bool {
//                $customer = app(SapCustomerLookupServiceInterface::class)
//                    ->findCustomerByCode($customerCode);

                $customer = static::getCachedSapCustomer($customerCode);

                $sapEngineerName = trim(mb_strtolower((string) ($customer['slp_name'] ?? '')));

                return $sapEngineerName !== ''
                    && mb_strpos($sapEngineerName, $engineerName) !== false;
            })
            ->values()
            ->all();
    }

    protected static function filterExistingCollectionCustomers(
        array $customers,
        ?string $currentCustomerCode = null
    ): array {
        $customerCodes = array_keys($customers);

        if (! count($customerCodes)) {
            return $customers;
        }

        $existingCustomerCodes = BranchCropCompositionCollection::query()
            ->whereIn('customer_code', $customerCodes)
            ->when(
                filled($currentCustomerCode),
                fn (Builder $query) => $query->where('customer_code', '!=', $currentCustomerCode)
            )
            ->pluck('customer_code')
            ->map(fn ($customerCode): string => trim((string) $customerCode))
            ->filter()
            ->all();

        if (! count($existingCustomerCodes)) {
            return $customers;
        }

        return collect($customers)
            ->reject(fn ($label, $customerCode): bool => in_array(trim((string) $customerCode), $existingCustomerCodes, true))
            ->toArray();
    }

    protected static function filterExistingCollectionCustomerRows(
        array $customers,
        ?string $currentCustomerCode = null
    ): array {
        $customerCodes = collect($customers)
            ->pluck('code')
            ->filter()
            ->values()
            ->all();

        if (! count($customerCodes)) {
            return $customers;
        }

        $existingCustomerCodes = BranchCropCompositionCollection::query()
            ->whereIn('customer_code', $customerCodes)
            ->when(
                filled($currentCustomerCode),
                fn (Builder $query) => $query->where('customer_code', '!=', $currentCustomerCode)
            )
            ->pluck('customer_code')
            ->map(fn ($customerCode): string => trim((string) $customerCode))
            ->filter()
            ->all();

        if (! count($existingCustomerCodes)) {
            return $customers;
        }

        return collect($customers)
            ->reject(fn (array $customer): bool => in_array(trim((string) ($customer['code'] ?? '')), $existingCustomerCodes, true))
            ->values()
            ->all();
    }

    protected static function customerRowsToOptions(array $customers): array
    {
        return collect($customers)
            ->mapWithKeys(function (array $customer): array {
                return [
                    $customer['code'] => $customer['label'],
                ];
            })
            ->toArray();
    }

    protected static function customerBelongsToBranch(?string $customerCode, ?string $branchCode): bool
    {
        $customerCode = trim((string) $customerCode);
        $branchCode = trim((string) $branchCode);

        if ($customerCode === '' || $branchCode === '') {
            return false;
        }

        foreach (static::getCustomerPrefixesForBranchCode($branchCode) as $prefix) {
            if (str_starts_with($customerCode, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected static function getCustomerPrefixesForBranchCode(string $branchCode): array
    {
        $branchToCustomerPrefixes = [
            '0001' => ['01'],
            '0101' => ['01'],
            '0102' => ['02'],
            '0103' => ['03'],
            '0104' => ['04'],
            '0105' => ['05'],
            '0106' => ['06'],
            '0107' => ['07'],
            '0108' => ['08'],
            '0109' => ['09'],
            '0110' => ['10'],
            '0111' => ['11'],
            '0112' => ['12'],
            '0201' => ['01'],
            '0202' => ['01'],
            '0203' => ['01'],
        ];

        return $branchToCustomerPrefixes[$branchCode] ?? [];
    }

    protected static function isAdminUser(): bool
    {
        return optional(Auth::user())->role === 'a';
    }

    protected static function getAuthorizedBranchCodes(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $assignedBranches = json_decode($user->branches ?? '[]', true);

        if (! is_array($assignedBranches)) {
            return [];
        }

        $branchCodeMap = [
            '1' => '0001',
            '2' => '0001',
            '3' => '0101',
            '4' => '0105',
            '5' => '0107',
            '6' => '0106',
            '7' => '0103',
            '8' => '0111',
            '9' => '0110',
            '10' => '0102',
            '11' => '0109',
            '12' => '0108',
            '13' => '0104',
            '14' => '0112',
            '15' => '0201',
            '16' => '0202',
            '17' => '0203',
            '500' => '0202',
            '504' => '0203',
            '505' => '0112',
            '0001' => '0001',
            '0101' => '0101',
            '0102' => '0102',
            '0103' => '0103',
            '0104' => '0104',
            '0105' => '0105',
            '0106' => '0106',
            '0107' => '0107',
            '0108' => '0108',
            '0109' => '0109',
            '0110' => '0110',
            '0111' => '0111',
            '0112' => '0112',
            '0201' => '0201',
            '0202' => '0202',
            '0203' => '0203',
        ];

        return array_values(array_unique(array_filter(array_map(
            fn ($branch) => $branchCodeMap[(string) $branch] ?? null,
            $assignedBranches
        ))));
    }

    protected static function getAuthenticatedEngineerName(): ?string
    {
        $name = trim((string) optional(Auth::user())->name);

        return $name !== '' ? $name : null;
    }

    protected static function getLeadEngineerOptions($branchId = null): array
    {
        $branchCode = static::resolveBranchCodeFromState($branchId);

        if (blank($branchCode)) {
            return [];
        }

        return User::query()
            ->whereHas('user_group', function ($query) {
                $query->whereIn('id', [7, 8]);
            })
            ->where('sales_dept_code', $branchCode)
            ->where('is_active',1)
            ->get(['id', 'name'])
            ->pluck('name', 'id')
            ->toArray();
    }

    protected static function isLeadEngineerAssignableToBranch($engineerId, $branchId): bool
    {
        if (blank($engineerId) || blank($branchId)) {
            return false;
        }

        $branchCode = static::resolveBranchCodeFromState($branchId);

        if (blank($branchCode)) {
            return false;
        }

        $engineer = User::query()
            ->whereKey($engineerId)
            ->whereHas('user_group', function ($query) {
                $query->whereIn('id', [7, 8]);
            })
            ->where('sales_dept_code', $branchCode)
            ->first(['id']);

        return (bool) $engineer;
    }

    protected static function syncLeadEngineerSelection(
        string $customerType,
        $engineerId,
        callable $set,
        callable $get
    ): void {
        if ($customerType !== static::CUSTOMER_TYPE_LEAD && $get('type') !== static::CUSTOMER_TYPE_LEAD) {
            return;
        }

        $set('engineer_name', static::resolveEngineerNameFromUserId($engineerId));
    }

    protected static function resolveEngineerNameFromUserId($engineerId): ?string
    {
        if (blank($engineerId)) {
            return null;
        }

        $name = User::query()
            ->whereKey($engineerId)
            ->value('name');

        $name = trim((string) $name);

        return $name !== '' ? $name : null;
    }

    public static function getDisplayCustomerCode(Model $record): string
    {
        if (($record->type ?? null) === static::CUSTOMER_TYPE_REDISTRIBUTION) {
            return trim((string) (optional($record->lead)->code ?: $record->customer_code ?: '-'));
        }

        return trim((string) ($record->customer_code ?: optional($record->lead)->code ?: '-'));
    }

    public static function getDisplayCustomerName(Model $record): string
    {
        if (($record->type ?? null) === static::CUSTOMER_TYPE_REDISTRIBUTION) {
            return trim((string) (optional($record->lead)->name ?: $record->customer_name ?: '-'));
        }

        return trim((string) ($record->customer_name ?: optional($record->lead)->name ?: '-'));
    }

    public static function getSapCustomerLabel(Model $record): string
    {
        if (($record->type ?? null) != static::CUSTOMER_TYPE_REDISTRIBUTION) {
            return '-';
        }

        $customerCode = trim((string) ($record->customer_code ?? ''));
        $customerName = trim((string) ($record->customer_name ?? ''));

        if ($customerCode === '' && $customerName === '') {
            return '-';
        }

        return $customerName !== ''
            ? trim($customerCode . ' - ' . $customerName, ' -')
            : $customerCode;
    }

    protected static function normalizeOptionalFormValue($value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    protected static function getCachedSapCustomer(?string $code): array
    {
        if (blank($code)) {
            return [];
        }

        $customer = Cache::remember(
            'sap_customer_' . $code,
            now()->addHours(6),
            fn () => app(SapCustomerLookupServiceInterface::class)
                ->findCustomerByCode($code)
        );

        return is_array($customer) ? $customer : [];
    }
}
