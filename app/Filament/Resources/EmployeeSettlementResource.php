<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeSettlementResource\Pages;
use App\Filament\Resources\EmployeeSettlementResource\RelationManagers;
use App\Models\EmployeeSettlement;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;


class EmployeeSettlementResource extends Resource
{
    protected static ?string $model = EmployeeSettlement::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->label('اسم الموظف')
                    ->relationship('user', 'name'),
                Select::make('settlement_id')->label('اسم الموظف')
                    ->relationship('settlement', 'name'),
                Select::make('status')->label('الحالة')
                    ->options([
                        1 => 'تحت الإجراء',
                        2 => 'تمت المافقة',
//                        'published' => 'Published',
                    ])
                    ->default(1)
                    ->disablePlaceholderSelection(),
                Textarea::make('note'),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('اسم الموظف'),
                Tables\Columns\TextColumn::make('settlement.name')->label('التسوية'),
                BadgeColumn::make('status')
                    ->enum([
                        1 => 'تحت الإجراء',
                        2 => 'تمت الموافقة',
                        3 => 'Published',
                    ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
//            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
//                BulkAction::make('edit')
                    ->bulkActions([
                        BulkAction::make('bulkEditStatus')
                            ->label('Edit Status & Notes')
                            ->form([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        1 => 'Pending',
                                        2 => 'Approved',
                                        3 => 'Rejected',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('note')
                                    ->label('Notes')
                                    ->rows(3),
                                Forms\Components\FileUpload::make('attachments')
                                    ->label('Attachments')
                                    ->multiple()
                                    ->directory('employee-settlement-attachments'),
                            ])
                            ->action(function (Collection $records, array $data) {
                                foreach ($records as $record) {
                                    $record->status = $data['status'];
                                    if (isset($data['note'])) {
                                        $record->note = $data['note'];
                                    }
                                    if (isset($data['attachments'])) {
                                        // Assuming you have a relation or method to handle attachments
                                        foreach ($data['attachments'] as $file) {
                                            $record->addMedia($file)->toMediaCollection('attachments');
                                        }
                                    }
                                    $record->save();
                                }
                            }),


//                    ])
//                    ->action(fn (Collection $records) => $records->each->delete())
//                    ->deselectRecordsAfterCompletion()

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
            'index' => Pages\ListEmployeeSettlements::route('/'),
            'create' => Pages\CreateEmployeeSettlement::route('/create'),
            'edit' => Pages\EditEmployeeSettlement::route('/{record}/edit'),
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
