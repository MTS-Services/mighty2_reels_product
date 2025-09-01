<?php

namespace App\Livewire\Partials\Guest;

use Livewire\Component;

class Header extends Component
{
    public string $pageSlug;

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;
    }
    public function render()
    {
        return view('livewire.partials.guest.header');
    }
}
