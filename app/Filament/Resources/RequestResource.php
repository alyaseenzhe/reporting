<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaRelationManagerResource\RelationManagers\MembersRelationManager;
use App\Filament\Resources\RequestResource\Pages;
use App\Filament\Resources\RequestResource\RelationManagers;
use App\Filament\Resources\RequestSettlementResource\RelationManagers\SettlementsRelationManager;
use App\Filament\Resources\RequestResource\RelationManagers\AttachmentsRelationManagerRelationManager;
use App\Models\Request;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use App\Models\Settlement;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')->label('النوع')->default('اخلاء طرف'),
                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('hr-files')
                    ->multiple(),
                Forms\Components\Textarea::make('reasons')->label('الأسباب'),




            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('النوع'),

                TextColumn::make('user.name')
                    ->label('انشأ بواسطة')
                    ->searchable()
                    ->sortable(),
                //
                TextColumn::make('hr-file')
                    ->label('Files')
                    ->formatStateUsing(function ($record) {

                        return $record->getMedia('hr-files')
                            ->map(function ($media) {
                                return "<a href='{$media->getUrl()}' target='_blank'>
                            {$media->file_name}
                        </a>";
                            })
                            ->implode('<br>');
                    })
                    ->html()
//                Tables\Columns\TextColumn::make('attachments_count')
//                    ->label('Files')
//                    ->counts('media'),

//                TextColumn::make('attachments')
//                    ->label('Files')
//                    ->formatStateUsing(function ($record) {
//
//                        return $record->getMedia('hr-file')
//                            ->map(function ($media) {
//                                return "<a href='{$media->getUrl()}' target='_blank'>
//                            {$media->file_name}
//                        </a>";
//                            })
//                            ->implode('<br>');
//                    })
//                    ->html()


            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('attachments')
                    ->label('عرض المرفقات')
                    ->icon('heroicon-o-paper-clip')
                    ->modalHeading('المرفقات')
                    ->modalContent(fn ($record) => view(
                        'filament.modals.request-attachments',
                        ['record' => $record]
                    )),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            AttachmentsRelationManagerRelationManager::class,
            SettlementsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }





}
