<?php

namespace App\Http\Controllers\Filament;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class LoginRedirectController
{
    public function __invoke(): RedirectResponse
    {
        if (Filament::auth()->check()) {
            return redirect()->intended(Filament::getUrl());
        }

        return redirect()->route('login');
    }
}
