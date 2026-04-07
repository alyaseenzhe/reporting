<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ItDepartment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.it-department';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
