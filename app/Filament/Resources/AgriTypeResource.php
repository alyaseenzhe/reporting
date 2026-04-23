<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgriTypeResource\Pages;
use App\Filament\Resources\AgriTypeResource\RelationManagers;
use App\Models\AgriType;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AgriTypeResource extends Resource
{
    protected static ?string $model = AgriType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'النماذج الزراعية';

    protected static ?string $navigationLabel = 'أنواع الزاعة';

    protected static ?string $pluralLabel = 'نوع الزراعة';

    protected static ?string $label = 'نوع الزراعة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('اسم نوع الزراعة')
                    ->required(),
                Checkbox::make('has_units')
                    ->label('يحتوي على وحدات'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم نوع الزراعة')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('has_units')
                    ->label('يحتوي على وحدات')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')
                            ->label('بحث باسم النوع')
                            ->placeholder('اكتب اسم نوع الزراعة'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['name'] ?? null,
                            fn (Builder $query, $name) => $query->where('name', 'like', "%{$name}%")
                        );
                    }),
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
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return static::isAdminUser() || static::userHasCropPermission('list-agri-type');
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
            'index' => Pages\ListAgriTypes::route('/'),
            'create' => Pages\CreateAgriType::route('/create'),
            'edit' => Pages\EditAgriType::route('/{record}/edit'),
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
