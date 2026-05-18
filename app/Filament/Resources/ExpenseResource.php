<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use App\Models\ExpenseDetails;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    //protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = ' المطالبات المالية';

    protected static ?string $pluralLabel = 'المطالبات المالية';

    protected static ?string $label = 'كشف مطالبة مالية';

    public static function getStatusOptions(): array
    {
        return [
            '1' => 'تحت الإجراء',
            '2' => 'تمت الموافقة',
            '3' => 'مرفوضة',
        ];
    }

    public static function calculateTotal($amount, $vat): float
    {
        $amount = (float) ($amount ?? 0);
        $vat = (float) ($vat ?? 0);

        return round($amount + (($vat * $amount) / 100), 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات التركيب المحصولي للعملاء')
                    ->schema([
                        Grid::make(4)->schema([
                            DatePicker::make('created_at')
                                ->label('التاريخ')
                                ->hiddenOn('create')
                                ->disabled(),
//
                            Select::make('user_id')->label('اسم الموظف')
                                ->relationship('user', 'name'),
                        ]),
                    ])->hiddenOn('create'),
//                Section::make('معلومات التركيب المحصولي للعملاء')
//                    ->schema([
////                        Grid::make(4)->schema([
////                            TextInput::make('location')
////                                ->label('الموقع')->required(),
////
////                            TextInput::make('amount')
////                                ->label('المبلغ')
////                                ->required()
////                                ->numeric()
////                                ->reactive()
////                                ->afterStateUpdated(function ($state, callable $get, callable $set): void {
////                                    $set('total', static::calculateTotal($state, $get('vat')));
////                                })
////                                ->maxValue(9999999999.99)
////                                ->rules(['numeric', 'min:0.01']),
////
////                            TextInput::make('vat')
////                                ->label('الضريبة')
////                                ->required()
////                                ->numeric()
////                                ->reactive()
////                                ->afterStateUpdated(function ($state, callable $get, callable $set): void {
////                                    $set('total', static::calculateTotal($get('amount'), $state));
////                                })
////                                ->maxValue(9999999999.99)
////                                ->rules(['numeric', 'min:0.01']),
////
////                            TextInput::make('total')
////                                ->label('Total')
////                                ->numeric()
////                                ->disabled()
////                                ->dehydrated()
////                                ->default(0),
////
////                            Textarea::make('description')
////                                ->label('الوصف')
////                                ->columnSpan(2)->required(),
//
//                            Select::make('status')->label('الحالة')
//                                ->options(static::getStatusOptions())
//                                ->hiddenOn('create')
//                                ->default('1')
//                                ->disablePlaceholderSelection(),
//
////                TextInput::make('created_by')
////                    ->label('تم انشاؤه بواسطة')
////                    ->hiddenOn('create')
////                    ->disabled()
////                    ->dehydrated(false)
////                    ->formatStateUsing(function ($state, ?Model $record): string {
////                        return (string) optional(optional($record)->userUpdate)->name;
////                    }),
//                        ]),
                        Section::make('المطالبات المالية')
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

                                        TextInput::make('location')
                                            ->label('الموقع')
                                            ->columnSpan(2)
                                            ->required(),


                                        TextInput::make('description')
                                            ->label('الوصف')
                                            ->columnSpan(3)
                                            ->required(),



                                        TextInput::make('amount')
                                            ->label('المبلغ')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, callable $get, callable $set): void {
                                                $set('total', static::calculateTotal($state, $get('vat')));
                                            })
                                            ->maxValue(9999999999.99)
                                            ->rules(['numeric', 'min:0.01']),

                                        TextInput::make('vat')
                                            ->label('الضريبة')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, callable $get, callable $set): void {
                                                $set('total', static::calculateTotal($get('amount'), $state));
                                            })
                                            ->maxValue(9999999999.99)
                                            ->rules(['numeric', 'min:0.01']),

                                        TextInput::make('total')
                                            ->label('Total')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated()
                                            ->default(0)
                                            ->columnSpan(2),

                                         ]),


                        ]),
                Section::make('المرفقات')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('المرفقات')
                            ->collection('expense_attachments')
                            ->multiple()
                            ->preserveFilenames()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/png',
                                'image/jpeg',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->enableOpen()
                            ->enableDownload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('اسم الموظف'),
//                TextColumn::make('amount')
//                    ->label('المبلغ'),
//                TextColumn::make('vat')
//                    ->label('الضريبة'),
                TextColumn::make('total')
                    ->label('الاجمالي'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('update_status')
                    ->label('Update Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(static::getStatusOptions())
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data) {
                        Expense::query()
                            ->whereIn('id', $records->pluck('id'))
                            ->update(['status' => $data['status']]);
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function extractDetailRows(array $data): array
    {
        return array_values($data['crop_composition_items'] ?? []);
    }

    public static function extractParentData(array $data): array
    {
        $detailRows = static::extractDetailRows($data);

        unset($data['crop_composition_items'], $data['attachments']);

        $data['total'] = collect($detailRows)->sum(function (array $row): float {
            return (float) ($row['total'] ?? 0);
        });

        if (blank($data['status'] ?? null)) {
            $data['status'] = '1';
        }

        return $data;
    }

    public static function syncChildren(Expense $record, array $detailRows): void
    {
        $record->expenseDetails()->delete();

        foreach ($detailRows as $row) {
            $record->expenseDetails()->create([
                'description' => $row['description'] ?? null,
                'location' => $row['location'] ?? null,
                'amount' => $row['amount'] ?? null,
                'vat' => $row['vat'] ?? null,
                'total' => $row['total'] ?? null,
            ]);
        }
    }

    public static function mutateDataBeforeFill(array $data, Model $record): array
    {
        $data['crop_composition_items'] = $record->expenseDetails
            ->map(function (ExpenseDetails $row): array {
                return [
                    'description' => $row->description,
                    'location' => $row->location,
                    'amount' => $row->amount,
                    'vat' => $row->vat,
                    'total' => $row->total,
                ];
            })
            ->toArray();

        return $data;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
            'view' => Pages\ViewExpense::route('/{record}'),
        ];
    }




    public static function canViewAny(): bool
    {
        return auth()->check();
    }
//
    public static function canCreate(): bool
    {
        return auth()->check();
    }
//
    public static function canEdit(Model $record): bool
    {
                if (static::isAdminUser()) {
            return true;
        }

        // Own record
        if ($record->user_id === auth()->id()) {
            return true;
        }

        // Manager can edit employee records
        return User::where('manager_id', auth()->id())
                ->where('id', $record->user_id)
                ->exists();
    }
//
    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }

//    public function canAccessPanel(Panel $panel): bool
//    {
//        return static::isAdminUser();
//    }
//
    protected static function isAdminUser(): bool
    {
        return optional(Auth::user())->role === 'a';
    }
}
