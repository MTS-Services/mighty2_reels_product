<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Footer', 'page_slug' => 'Footer'])]
class Footer extends Component
{
    public function render()
    {
        return view('livewire.partials.footer');
    }
}
