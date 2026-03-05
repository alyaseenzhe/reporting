<?php

namespace App\Filament\Resources\RequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttachmentsRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
       TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
       TextInput::make('file_name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
     Forms\Components\Hidden::make('disk')->default('public'),

     Select::make('collection_name')
         ->label('Collection')
         ->options([
             'hr-files' => 'HR Files',
             'it-files' => 'IT Files',
             'finance-files' => 'Finance Files',
             'accounting-files' => 'Accounting Files',
             'employee-files' => 'Employee Files',
         ])
         ->default('hr-files')
         ->required()
         ->reactive(),

     SpatieMediaLibraryFileUpload::make('attachments')
         ->collection(fn($get) => $get('collection_name') ?: 'hr-files')
         ->multiple()
         ->preserveFilenames()
         ->required()
         ->disk('public')


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->label('File')
                    ->formatStateUsing(fn ($record) =>
                    "<a href='{$record->getUrl()}' target='_blank'>{$record->file_name}</a>"
                    )
                    ->html()
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
