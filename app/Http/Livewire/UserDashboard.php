<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserDashboard extends Component
{
    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

    }
    public function render()
    {
        return view('livewire.user-dashboard')
            ->layout('layouts.dashboard');
    }
}
