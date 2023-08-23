<?php

namespace App\Http\Livewire;

use http\Client\Curl\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NonActiveUser extends Component
{
    public function mount() {
        if (Auth::user()->is_active == 1) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.non-active-user')
            ->layout('layouts.dashboard');
    }
}
