<?php

namespace App\Http\Livewire;

use http\Client\Curl\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NonActiveUser extends Component
{
    /**
     * This is a Livewire lifecycle hook that runs once when the component is initialized.
     * It acts as a security check to ensure that only non-active users can see this page.
     * If an active user somehow navigates to this route, they are immediately redirected
     * back to their main dashboard.
     */
    public function mount() {
        if (Auth::user()->is_active == 1) {
            return redirect()->route('dashboard');
        }
    }

    /**
     * The standard Livewire method that renders the component's Blade view. It displays the
     * "Your account is not active" message to the user within the main dashboard layout.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.non-active-user')
            ->layout('layouts.dashboard');
    }
}
