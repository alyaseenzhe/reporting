<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserGroupResource\Pages;
use App\Models\UserGroup;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserGroupResource extends Resource
{
    protected static ?string $model = UserGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'User Groups';

    protected static ?string $pluralLabel = 'User Groups';

    protected static ?string $label = 'User Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Group Info')
                    ->schema([
                        TextInput::make('name')
                            ->label('Group Name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Report Access')
                    ->schema([
                        CheckboxList::make('report_type')
                            ->label('Allowed Reports')
                            ->options(static::getReportTypeOptions())
                            ->columns(2)
                            ->afterStateHydrated(function (CheckboxList $component, $state): void {
                                if (blank($state)) {
                                    $component->state([]);

                                    return;
                                }

                                if (is_string($state)) {
                                    $decodedState = json_decode($state, true);

                                    $component->state(is_array($decodedState) ? $decodedState : []);

                                    return;
                                }

                                $component->state(is_array($state) ? $state : []);
                            })
                            ->dehydrateStateUsing(function ($state): string {
                                return json_encode(array_values($state ?? []));
                            }),
                    ]),

                Section::make('Visit Access')
                    ->schema([
                        CheckboxList::make('visits')
                            ->label('Allowed Visit Actions')
                            ->options(static::getVisitOptions())
                            ->columns(1)
                            ->afterStateHydrated(function (CheckboxList $component, $state): void {
                                if (blank($state)) {
                                    $component->state([]);

                                    return;
                                }

                                if (is_string($state)) {
                                    $decodedState = json_decode($state, true);

                                    $component->state(is_array($decodedState) ? $decodedState : []);

                                    return;
                                }

                                $component->state(is_array($state) ? $state : []);
                            })
                            ->dehydrateStateUsing(function ($state): string {
                                return json_encode(array_values($state ?? []));
                            }),
                    ]),
                Section::make('التركيب المحصولي')
                    ->schema([
                        CheckboxList::make('crops')
                            ->label('اجراءات نموذج المحاصيل المسموح به')
                            ->options(static::getCropOptions())
                            ->columns(1)
                            ->afterStateHydrated(function (CheckboxList $component, $state): void {
                                if (blank($state)) {
                                    $component->state([]);

                                    return;
                                }

                                if (is_string($state)) {
                                    $decodedState = json_decode($state, true);

                                    $component->state(is_array($decodedState) ? $decodedState : []);

                                    return;
                                }

                                $component->state(is_array($state) ? $state : []);
                            })
                            ->dehydrateStateUsing(function ($state): string {
                                return json_encode(array_values($state ?? []));
                            }),
                    ]),

                Section::make('Permissions')
                    ->schema([
                        Grid::make(2)->schema([
                            Radio::make('cost')
                                ->label('Cost Access')
                                ->options([
                                    '0' => 'No',
                                    '1' => 'Yes',
                                ])
                                ->default('0')
                                ->required()
                                ->inline(),

                            Radio::make('read_type')
                                ->label('Read Scope')
                                ->options([
                                    '1' => 'Own data only',
                                    '0' => 'Branch data',
                                ])
                                ->default('1')
                                ->required()
                                ->inline(),
                        ]),

                        Radio::make('write_product_target')
                            ->label('Product Target Permission')
                            ->options(static::getWriteProductTargetOptions())
                            ->default('0')
                            ->required(),

                        Grid::make(3)->schema([
                            Radio::make('calculate_all_product_target')
                                ->label('Calculate All Product Targets')
                                ->options([
                                    '0' => 'No',
                                    '1' => 'Yes',
                                ])
                                ->default('0')
                                ->required()
                                ->inline(),

                            Radio::make('choose_special_product')
                                ->label('Choose Special Product')
                                ->options([
                                    '0' => 'No',
                                    '1' => 'Yes',
                                ])
                                ->default('0')
                                ->required()
                                ->inline(),

                            Radio::make('edit_special_product')
                                ->label('Edit Special Product')
                                ->options([
                                    '0' => 'No',
                                    '1' => 'Yes',
                                ])
                                ->default('0')
                                ->required()
                                ->inline(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('اسم الصلاحية')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('المستخدمين')
                    ->counts('users')
                    ->sortable(),

//                TextColumn::make('report_type')
//                    ->label('Reports')
//                    ->formatStateUsing(function ($state): string {
//                        $reportKeys = is_string($state) ? json_decode($state, true) : $state;
//
//                        if (! is_array($reportKeys) || empty($reportKeys)) {
//                            return '-';
//                        }
//
//                        $labels = collect($reportKeys)
//                            ->map(fn ($key) => static::getReportTypeOptions()[$key] ?? $key)
//                            ->values()
//                            ->all();
//
//                        return implode(', ', $labels);
//                    })
//                    ->limit(60)
//                    ->toggleable(),
//
//                TextColumn::make('visits')
//                    ->label('Visits')
//                    ->formatStateUsing(function ($state): string {
//                        $visitKeys = is_string($state) ? json_decode($state, true) : $state;
//
//                        if (! is_array($visitKeys) || empty($visitKeys)) {
//                            return '-';
//                        }
//
//                        $labels = collect($visitKeys)
//                            ->map(fn ($key) => static::getVisitOptions()[$key] ?? $key)
//                            ->values()
//                            ->all();
//
//                        return implode(', ', $labels);
//                    })
//                    ->limit(40)
//                    ->toggleable(),

                BadgeColumn::make('cost')
                    ->label('التكلفة')
                    ->enum([
                        '0' => 'لا',
                        '1' => 'نعم',
                    ])
                    ->colors([
                        'secondary' => '0',
                        'success' => '1',
                    ]),

                BadgeColumn::make('read_type')
                    ->label('صلاحية القراءة')
                    ->enum([
                        '1' => 'بيانات المستخدم نفسه فقط',
                        '0' => 'بيانات الفرع التابعة للمستخدم',
                    ])
                    ->colors([
                        'primary' => '1',
                        'warning' => '0',
                    ]),

//                BadgeColumn::make('write_product_target')
//                    ->label('صلاحية المستهدف')
//                    ->enum(static::getWriteProductTargetOptions())
//                    ->colors([
//                        'secondary' => '0',
//                        'success' => '1',
//                        'warning' => '2',
//                        'primary' => '3',
//                    ]),

//                BadgeColumn::make('calculate_all_product_target')
//                    ->label('Calc All')
//                    ->enum([
//                        '0' => 'No',
//                        '1' => 'Yes',
//                    ])
//                    ->colors([
//                        'secondary' => '0',
//                        'success' => '1',
//                    ]),

                BadgeColumn::make('choose_special_product')
                    ->label('Choose Special')
                    ->enum([
                        '0' => 'No',
                        '1' => 'Yes',
                    ])
                    ->colors([
                        'secondary' => '0',
                        'success' => '1',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('edit_special_product')
                    ->label('Edit Special')
                    ->enum([
                        '0' => 'No',
                        '1' => 'Yes',
                    ])
                    ->colors([
                        'secondary' => '0',
                        'success' => '1',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::isAdminUser();
    }

    public static function canViewAny(): bool
    {
        return static::isAdminUser();
    }

    public static function canCreate(): bool
    {
        return static::isAdminUser();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isAdminUser();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isAdminUser();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit' => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }

    protected static function getReportTypeOptions(): array
    {
        return [
            'commission-report' => 'Commission Report',
            'list.non-paid-vouchers' => 'Non-Paid Vouchers',
            'list.sales-profit' => 'Sales Profit',
            'list.sales-collections' => 'Sales Collections',
            'list.postponed-by-customers' => 'Postponed by Customers',
            'list.customer-cash-statement' => 'Customer Cash Statement',
            'list.my-product-target' => 'My Product Target',
            'list.my-product-target-only' => 'My Product Target Only',
            'list.purchase-recommendation' => 'Purchase Recommendation',
            'list.distribution-calc' => 'Distribution Calculator',
            'list.weekly-report' => 'Weekly Report',
            'list.daily-reports' => 'Daily Reports',
            'report-21' => 'Report 21',
            'report-11' => 'Report 11',
            'report-25' => 'Report 25',
            'report-42' => 'Report 42',
        ];
    }

    protected static function getWriteProductTargetOptions(): array
    {
        return [
            '0' => 'ستطيع المستخدم قراءة مستهدف الأصناف للفروع التابعة له',
            '1' => 'يستطيع المستخدم اضافة\تعديل مستهدف الأصناف لنفسه فقط',
            '2' => 'يستطيع المستخدم اضافة\تعديل مستهدف الأصناف لجميع موظفين الفروع التابع لهم',
            '3' => 'يستطيع المستخدم التعديل فقط لمستهدف الأصناف لجميع موظفين الفروع التابع لهم',
        ];
    }

    protected static function getVisitOptions(): array
    {
        return [
            'enter-visit' => 'الدخول على منصة الزيارات',
        ];
    }
    protected static function getCropOptions(): array
    {
        return [
            'create-only-own-crop' => 'إنشاء نموذج محصولي فقط خاص به',
            'create-others-crop' => 'إنشاء نموذج محصولي للأخرين',
            'edit-only-own-crop' => 'تعديل النموذج المحصولي فقط الخاص به',
            'edit-others-crop' => 'تعديل النموذج المحصولي للأخرين',
            'delete-only-own-crop' => 'حذف النموذج المحصولي فقط الخاص به',
            'delete-others-crop' => 'حذف النموذج المحصولي للأخرين',
            'view-only-own-crop' => 'عرض النموذج المحصولي فقط الخاص به',
            'view-others-crop' => 'عرض النموذج المحصولي للأخرين',
            'list-crop-collection'=>'جدول المحاصيل',
            'list-crop-category'=>'جدول طبيعة المحصول',
            'list-agri-type'=>'جدول أنواع الزراعة',
            'list-agri-details'=>'جدول تفاصيل أنواع الزراعة',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return static::isAdminUser();
    }

    protected static function isAdminUser(): bool
    {
        return optional(Auth::user())->role === 'a';
    }
}
