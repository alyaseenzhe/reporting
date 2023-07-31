<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NonActiveUser extends Component
{
    public function render()
    {
        return view('livewire.non-active-user')
            ->layout('layouts.dashboard');
    }
}
