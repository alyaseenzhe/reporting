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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CropCatalogCategoryResource extends Resource
{
    protected static ?string $model = CropCatalogCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'طبيعة المحصول';

    protected static ?string $pluralLabel = 'طبيعة المحصول';

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

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return static::isAdminUser() || static::userHasCropPermission('list-crop-category');
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCropCatalogCategories::route('/'),
            'create' => Pages\CreateCropCatalogCategory::route('/create'),
            'edit' => Pages\EditCropCatalogCategory::route('/{record}/edit'),
        ];
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

    protected static function isAdminUser(): bool
    {
        return optional(Auth::user())->role === 'a';
    }
}
