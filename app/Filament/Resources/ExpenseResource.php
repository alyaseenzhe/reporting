<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('user_id')->label('اسم الموظف')
                    ->relationship('user', 'name'),

                TextInput::make('location')
                    ->label('الموقع')->required(),


                TextInput::make('amount')
                    ->label('المبلغ')
                    ->required()
                    ->numeric()
                    ->maxValue(9999999999.99)
                    ->rules(['numeric', 'min:0.01']),

                TextInput::make('vat')
                    ->label('الضريبة')
                    ->required()
                    ->numeric()
                    ->maxValue(9999999999.99)
                    ->rules(['numeric', 'min:0.01']),

                Textarea::make('description')
                    ->label('الوصف')->required(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
