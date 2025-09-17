<?php

namespace App\Livewire\Partials;

use App\Models\City;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserHeader extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;
    public bool $filterOpen = false;

    // Filter properties
    public $searchTerm = '';
    public $selectedRooms = '';
    public $selectedCity = '';
    public $selectedType = '';

    protected $listeners = [
        'filtersUpdated' => 'updateFilters',
        'resetFilters' => 'resetAllFilters',
    ];

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;

        // Initialize filters from URL parameters if on home page
        if ($pageSlug === 'home') {
            $this->searchTerm = request()->get('search', '');
            $this->selectedRooms = request()->get('rooms', '');
            $this->selectedCity = request()->get('city', '');
            $this->selectedType = request()->get('type', '');
        } else {
            $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);
        }
    }

    public function updatedSearchTerm()
    {
        if ($this->pageSlug === 'home') {
            $this->dispatch('updateHomeFilters', [
                'searchTerm' => $this->searchTerm,
                'selectedRooms' => $this->selectedRooms,
                'selectedCity' => $this->selectedCity,
                'selectedType' => $this->selectedType,
            ]);
        }
    }

    public function updatedSelectedRooms()
    {
        if ($this->pageSlug === 'home') {
            $this->dispatch('updateHomeFilters', [
                'searchTerm' => $this->searchTerm,
                'selectedRooms' => $this->selectedRooms,
                'selectedCity' => $this->selectedCity,
                'selectedType' => $this->selectedType,
            ]);
        }
    }

    public function updatedSelectedCity()
    {
        if ($this->pageSlug === 'home') {
            $this->dispatch('updateHomeFilters', [
                'searchTerm' => $this->searchTerm,
                'selectedRooms' => $this->selectedRooms,
                'selectedCity' => $this->selectedCity,
                'selectedType' => $this->selectedType,
            ]);
        }
    }

    public function updatedSelectedType()
    {
        if ($this->pageSlug === 'home') {
            $this->dispatch('updateHomeFilters', [
                'searchTerm' => $this->searchTerm,
                'selectedRooms' => $this->selectedRooms,
                'selectedCity' => $this->selectedCity,
                'selectedType' => $this->selectedType,
            ]);
        }
    }

    public function resetAllFilters()
    {
        $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);

        if ($this->pageSlug === 'home') {
            $this->dispatch('updateHomeFilters', []);
        }
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
