<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Reel', 'page_slug' => 'reels'])]
class Reel extends Component
{
    public function render()
    {
        return view('livewire.backend.reel');
    }
}
