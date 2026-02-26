<?php

namespace App\Filament\Resources\RequestSettlementResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettlementsRelationManager extends RelationManager
{
    protected static string $relationship = 'settlements';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\CheckboxColumn::make('is_done')
                    ->label('تمت')
                    ->toggleable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user->role == 'hr') {
            $query->whereHas('type', fn ($q) =>
            $q->where('department', 'hr')
            );
        }

        if ($user->role == 'u') {
            $query->whereHas('type', fn ($q) =>
            $q->where('department', 'finance')
            );
        }

        if ($user->type == 'it') {
            $query->whereHas('type', fn ($q) =>
            $q->where('department', 'it')
            );
        }

        return $query;
    }
}
