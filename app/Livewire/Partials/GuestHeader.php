<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class GuestHeader extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;
    }
    public function render()
    {
        return view('livewire.partials.guest-header');
    }
}
