<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    //protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = ' المصروفات';

    protected static ?string $pluralLabel = 'المصروفات';

    protected static ?string $label = 'كشف مطالبة المصروفات';

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

                Select::make('user_id')->label('اسم الموظف')
                    ->relationship('user', 'name'),

                 ]),
                ]),
                Section::make('معلومات التركيب المحصولي للعملاء')
                    ->schema([
                        Grid::make(4)->schema([
                TextInput::make('location')
                    ->label('الموقع')->required(),


                TextInput::make('amount')
                    ->label('المبلغ')
                    ->required()
                    ->numeric()
                    ->reactive()
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
                    ->default(0),

                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpan(2)->required(),
                Select::make('status')->label('الحالة')
                    ->options([
                        1 => 'تحت الإجراء',
                        2 => 'تمت المافقة',
//                        'published' => 'Published',
                    ])
                    ->hiddenOn('create')
                    ->default(1)
                    ->disablePlaceholderSelection(),

//                TextInput::make('created_by')
//                    ->label('تم انشاؤه بواسطة')
//                    ->hiddenOn('create')
//                    ->disabled()
//                    ->dehydrated(false)
//                    ->formatStateUsing(function ($state, ?Model $record): string {
//                        return (string) optional(optional($record)->userUpdate)->name;
//                    }),

                        ]),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->label('اسم الموظف'),
                TextColumn::make('amount')
                    ->label('المبلغ'),
                TextColumn::make('vat')
                    ->label('الضريبة'),
                TextColumn::make('total')
                ->label('الاجمالي'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('update_status')
                    ->label('Update Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'تحت الإجراء',
                                'approved' => 'تمت الموافقة',
                                'rejected' => 'مرفوضة',
                            ])
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data) {
                        \App\Models\Expense::whereIn('id', $records->pluck('id'))
                            ->update(['status' => $data['status']]);
                    })
            ]);
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
        ];
    }
}
