<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SapItemMasterData extends Component
{
    public function render()
    {
        return view('livewire.sap-item-master-data')
            ->layout('layouts.dashboard');
    }
}
