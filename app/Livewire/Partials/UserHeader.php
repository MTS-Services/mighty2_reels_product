<?php

namespace App\Livewire\Partials;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use App\Models\City;
use App\Models\Product;

class UserHeader extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;
    public bool $filterOpen = false;
    
    // Search properties
    public string $search = '';
    public string $selectedRoom = '';
    public string $selectedCity = '';
    public string $selectedType = '';

    protected $listeners = ['searchCleared'];

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;
    }

    public function updatedSearch()
    {
        $this->dispatch('search-updated', [
            'search' => $this->search,
            'room' => $this->selectedRoom,
            'city' => $this->selectedCity,
            'type' => $this->selectedType,
        ]);
    }

    public function updatedSelectedRoom()
    {
        $this->dispatch('search-updated', [
            'search' => $this->search,
            'room' => $this->selectedRoom,
            'city' => $this->selectedCity,
            'type' => $this->selectedType,
        ]);
    }

    public function updatedSelectedCity()
    {
        $this->dispatch('search-updated', [
            'search' => $this->search,
            'room' => $this->selectedRoom,
            'city' => $this->selectedCity,
            'type' => $this->selectedType,
        ]);
    }

    public function updatedSelectedType()
    {
        $this->dispatch('search-updated', [
            'search' => $this->search,
            'room' => $this->selectedRoom,
            'city' => $this->selectedCity,
            'type' => $this->selectedType,
        ]);
    }

    public function performSearch()
    {
        $this->dispatch('search-updated', [
            'search' => $this->search,
            'room' => $this->selectedRoom,
            'city' => $this->selectedCity,
            'type' => $this->selectedType,
        ]);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->selectedRoom = '';
        $this->selectedCity = '';
        $this->selectedType = '';
        
        $this->dispatch('search-updated', [
            'search' => '',
            'room' => '',
            'city' => '',
            'type' => '',
        ]);
    }

    public function searchCleared()
    {
        $this->clearSearch();
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
        return view('livewire.partials.user-header', [
            'cities' => City::active()->pluck('name', 'id'),
            'maxRooms' => Product::active()->max('room') ?? 5,
        ]);
    }
}