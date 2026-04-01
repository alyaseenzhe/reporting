<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CropCatalogCategoryResource\Pages;
use App\Filament\Resources\CropCatalogCategoryResource\RelationManagers;
use App\Models\CropCatalogCategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CropCatalogCategoryResource extends Resource
{
    protected static ?string $model = CropCatalogCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'أنواع الزراعة';

    protected static ?string $pluralLabel = 'أنواع الزراعة';

    protected static ?string $label = ' نوع الزراعة';
//
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('اسم نوع الزراعة')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم نوع الزراعة')
                    ,
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
            'index' => Pages\ListCropCatalogCategories::route('/'),
            'create' => Pages\CreateCropCatalogCategory::route('/create'),
            'edit' => Pages\EditCropCatalogCategory::route('/{record}/edit'),
        ];
    }
}
