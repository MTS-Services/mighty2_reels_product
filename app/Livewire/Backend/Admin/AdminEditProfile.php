<?php

namespace App\Livewire\Backend\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'AdminEditProfile', 'page_slug' => 'AdminEditProfile'])]
class AdminEditProfile extends Component
{
    public function render()
    {
        return view('livewire.backend.admin.admin-edit-profile');
    }
}
