<?php

namespace App\Livewire\Frontend\User;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'UserProfile', 'page_slug' => 'UserProfile'])]
class UserProfile extends Component
{
    public function render()
    {
        return view('livewire.frontend.user.user-profile');
    }
}
