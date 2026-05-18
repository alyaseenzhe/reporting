<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

class AuthenticateFilament extends FilamentAuthenticate
{
    protected function redirectTo($request): string
    {
        return route('login');
    }
}
