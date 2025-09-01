<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Profile', 'page_slug' => 'profile'])]
class Profile extends Component
{
    public function render()
    {
        return view('livewire.backend.profile');
    }
}
