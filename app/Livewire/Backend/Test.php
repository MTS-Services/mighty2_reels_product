<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Test', 'page_slug' => 'Test'])]
class Test extends Component
{
    public function render()
    {
        return view('livewire.backend.test');
    }
}
