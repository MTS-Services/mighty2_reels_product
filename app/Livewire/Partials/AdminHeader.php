<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminHeader extends Component
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
        return view('livewire.partials.admin-header');
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        return $this->redirectRoute('home', navigate: true);
    }
}
