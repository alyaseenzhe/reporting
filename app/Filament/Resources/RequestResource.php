<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestResource\Pages;
use App\Filament\Resources\RequestResource\RelationManagers\AttachmentsRelationManagerRelationManager;
use App\Models\Request;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->label('النوع')
                    ->default('اخلاء طرف'),

                TextInput::make('name')
                    ->label('اسم الطلب')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('reasons')
                    ->columnSpan(2)
                    ->label('الأسباب'),
                SpatieMediaLibraryFileUpload::make('attachments')
                    ->label('المرفقات')
                    ->collection('employee-files')
                    ->columnSpan(2)
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('النوع'),
                TextColumn::make('name')
                    ->label('اسم الطلب'),
                TextColumn::make('user.name')
                    ->label('مقدم الطلب')
                    ->searchable()
                    ->sortable(),
//                TextColumn::make('attachments')
//                    ->label('المرفقات')
//                    ->getStateUsing(function ($record) {
//                        return collect($record->getMedia())
//                            ->map(function ($media) {
//                                return "<a href='{$media->getUrl()}' target='_blank'>{$media->file_name}</a>";
//                            })
//                            ->implode('<br>');
//                    })
//                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('settlements')
                    ->label('العهد')
                    ->icon('heroicon-o-check-circle')
                    ->url(fn ($record): string => static::getUrl('settlements', ['record' => $record])),
//                Action::make('attachments')
//                    ->label('Attachments')
//                    ->icon('heroicon-o-paper-clip')
//                    ->modalHeading('Attachments')
//                    ->modalContent(fn ($record) => view('filament.modals.request-attachments', [
//                        'record' => $record,
//                    ])),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
            'settlements' => Pages\ManageRequestSettlements::route('/{record}/settlements'),
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
