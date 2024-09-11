<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SapReports extends Component
{
    public function render()
    {
        return view('livewire.sap-reports')
            ->layout('layouts.dashboard');
    }
}
