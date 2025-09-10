<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Details', 'page_slug' => 'Details'])]
class Details extends Component
{
    public function render()
    {
        return view('livewire.backend.details');
    }
}
