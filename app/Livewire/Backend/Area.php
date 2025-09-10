<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Area', 'page_slug' => 'Area'])]
class Area extends Component
{
    public function render()
    {
        return view('livewire.backend.area');
    }
}
