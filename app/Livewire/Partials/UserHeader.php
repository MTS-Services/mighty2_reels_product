<?php

namespace App\Livewire\Partials;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserHeader extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;
    public bool $filterOpen = false;

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        return $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.partials.user-header');
    }
}
