<?php

namespace App\Livewire\Partials\Auth;

use Livewire\Component;

class Header extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;
    }

    public function render()
    {
        return view('livewire.partials.auth.header');
    }
}
