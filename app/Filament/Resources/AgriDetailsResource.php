<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgriDetailsResource\Pages;
use App\Filament\Resources\AgriDetailsResource\RelationManagers;
use App\Models\AgriDetais;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgriDetailsResource extends Resource
{
    protected static ?string $model = AgriDetais::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'تفاصيل نوع الزراعة';

    protected static ?string $pluralLabel = 'تفصيل النوع';

    protected static ?string $label = 'تفصيل النوع';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('details')
                    ->label('تفصيل النوع')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('details')
                    ->label('تفصيل النوع')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListAgriDetails::route('/'),
            'create' => Pages\CreateAgriDetails::route('/create'),
            'edit' => Pages\EditAgriDetails::route('/{record}/edit'),
        ];
    }
}
