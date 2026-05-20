<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CropCatalogItemResource\Pages;
use App\Filament\Resources\CropCatalogItemResource\RelationManagers;

use App\Models\CropCatalogCategory;
use App\Models\CropCatalogItem;
use Filament\Forms;
use Filament\Forms\Components\Select;
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
use Illuminate\Validation\Rule;


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
                    ->required()
                    ->rule(function (callable $get, ?Model $record) {
                        return Rule::unique('crop_catalog_items', 'name')
                            ->where(fn ($query) => $query->where('crop_catalog_category_id', $get('crop_catalog_category_id')))
                            ->ignore($record);


                    }),
                Select::make('crop_catalog_category_id')
                    ->label('طبيعة المحصول')
                    ->required()
                    ->reactive()
                    ->options(function (): array {
                        return CropCatalogCategory::query()
                            ->pluck('name', 'id')
                            ->toArray();
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')

                    ->label('اسم المحصول'),


                TextColumn::make('category.name')
                    ->label('طبيعة المحصول')
                    ->searchable(),

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
//        return static::canViewAny();
        return false;
    }

    public static function canViewAny(): bool
    {
        return static::isAdminUser() || static::userHasCropPermission('list-crop-collection');
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
            'index' => Pages\ListCropCatalogItems::route('/'),
            'create' => Pages\CreateCropCatalogItem::route('/create'),
            'edit' => Pages\EditCropCatalogItem::route('/{record}/edit'),
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
