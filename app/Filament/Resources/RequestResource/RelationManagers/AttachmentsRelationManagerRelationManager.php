<?php

namespace App\Filament\Resources\RequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_name')
                    ->required()
                    ->maxLength(255),
                Select::make('collection_name')
                    ->label('Collection')
                    ->options([
                        'hr-files' => 'HR Files',
                        'it-files' => 'IT Files',
                        'finance-files' => 'Finance Files',
                        'accounting-files' => 'Accounting Files',
                        'employee-files' => 'Employee Files',
                    ])
                    ->required()
                    ->default('hr-files'),

                SpatieMediaLibraryFileUpload::make('media')
                    ->collection(function ($get) {
                        $collection = $get('collection_name');
                        // fallback to default if empty
                        return $collection ?: 'hr-files';
                    })
                    ->multiple()
                    ->preserveFilenames() // keeps original filename
                    ->required()
                    ->reactive(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->label('اسم الملف')
                    ->url(fn ($record) => $record->getUrl()) // رابط الملف
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('mime_type'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
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
}
