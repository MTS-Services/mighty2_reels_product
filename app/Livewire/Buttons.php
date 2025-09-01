<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Buttons', 'page_slug' => 'Buttons'])]
class Buttons extends Component
{
    public function render()
    {
        return view('livewire.buttons');
    }
}
