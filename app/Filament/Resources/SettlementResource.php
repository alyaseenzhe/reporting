<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettlementResource\Pages;
use App\Filament\Resources\SettlementResource\RelationManagers;
use App\Models\Settlement;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class SettlementResource extends Resource
{
    protected static ?string $model = Settlement::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم التسوية')->required(),
//                Forms\Components\TextInput::make('department')->email()->required(),
                Select::make('department')
                    ->label('القسم')
                    ->options([
                        'employee'=> 'الموظف',
                        'hr'=> 'الموارد البشرية',
                        'it' => 'تقنية المعلومات',
                        'accounting'=>'المالية'])

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('اسم التسويه'),
                TextColumn::make('department')
                    ->enum([
                    'employee' => 'الموظف',
                    'hr' => 'الموارد البشرية',
                    'it' => 'تقنية المعلومات',
                    'accounting' => 'المالية'
                ])

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettlements::route('/'),
            'create' => Pages\CreateSettlement::route('/create'),
            'edit' => Pages\EditSettlement::route('/{record}/edit'),
//            'hr' => Pages\HrDepartment::route('/{record}/edit'),
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

    public function canAccessPanel(Panel $panel): bool
    {
        return static::isAdminUser();
    }

    protected static function isAdminUser(): bool
    {
        return optional(Auth::user())->role === 'a';
    }
}
