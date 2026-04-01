<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CropCatalogItemResource\Pages;
use App\Filament\Resources\CropCatalogItemResource\RelationManagers;
use App\Models\CropCatalogItem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CropCatalogItemResource extends Resource
{
    protected static ?string $model = CropCatalogItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'المحاصيل';

    protected static ?string $pluralLabel = 'المحاصيل';

    protected static ?string $label = ' المحصول';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('اسم المحصول')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم المحصول'),
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
            'index' => Pages\ListCropCatalogItems::route('/'),
            'create' => Pages\CreateCropCatalogItem::route('/create'),
            'edit' => Pages\EditCropCatalogItem::route('/{record}/edit'),
        ];
    }
}
