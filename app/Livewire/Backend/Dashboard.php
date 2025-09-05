<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Dashboard', 'page_slug' => 'Dashboard'])]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.backend.dashboard');
    }
}
