<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]

class Home extends Component
{
    public function render()
    {
        return view('livewire.frontend.home');
    }
}
