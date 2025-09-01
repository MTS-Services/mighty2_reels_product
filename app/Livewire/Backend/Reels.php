<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Reels extends Component
{
    public function render()
    {
        return view('livewire.backend.reels');
    }
}
