<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClearanceRequestResource\Pages;
use App\Filament\Resources\ClearanceRequestResource\RelationManagers;
use App\Models\ClearanceRequest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClearanceRequestResource extends Resource
{
    protected static ?string $model = ClearanceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListClearanceRequests::route('/'),
            'create' => Pages\CreateClearanceRequest::route('/create'),
            'edit' => Pages\EditClearanceRequest::route('/{record}/edit'),
        ];
    }    
}
