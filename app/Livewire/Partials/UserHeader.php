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

    // Filter properties - only handle UI state, not actual filtering
    public $searchTerm = '';
    public $selectedRooms = '';
    public $selectedCity = '';
    public $selectedType = '';

    // Loading states for each filter
    public $searchLoading = false;
    public $roomsLoading = false;
    public $cityLoading = false;
    public $typeLoading = false;
    public $resetLoading = false;

    protected $listeners = [
        'syncFilters' => 'syncFilters',
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
        }
    }

    /**
     * Sync filters from Home component
     */
    public function syncFilters($filters)
    {
        $this->searchTerm = $filters['searchTerm'] ?? '';
        $this->selectedRooms = $filters['selectedRooms'] ?? '';
        $this->selectedCity = $filters['selectedCity'] ?? '';
        $this->selectedType = $filters['selectedType'] ?? '';

        // Reset all loading states
        $this->resetLoadingStates();
    }

    /**
     * Handle search term updates with loading state and URL navigation
     */
    public function updatedSearchTerm()
    {
        if ($this->pageSlug === 'home') {
            $this->searchLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Handle rooms filter updates with loading state and URL navigation
     */
    public function updatedSelectedRooms()
    {
        if ($this->pageSlug === 'home') {
            $this->roomsLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Handle city filter updates with loading state and URL navigation
     */
    public function updatedSelectedCity()
    {
        if ($this->pageSlug === 'home') {
            $this->cityLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Handle type filter updates with loading state and URL navigation
     */
    public function updatedSelectedType()
    {
        if ($this->pageSlug === 'home') {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Navigate to home with current filter parameters as query strings
     */
    private function navigateWithFilters()
    {
        $queryParams = [];

        if (!empty($this->searchTerm)) {
            $queryParams['search'] = $this->searchTerm;
        }

        if (!empty($this->selectedRooms)) {
            $queryParams['rooms'] = $this->selectedRooms;
        }

        if (!empty($this->selectedCity)) {
            $queryParams['city'] = $this->selectedCity;
        }

        if (!empty($this->selectedType)) {
            $queryParams['type'] = $this->selectedType;
        }

        // Build URL with query parameters
        $url = route('home');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        // Navigate to the URL with wire:navigate
        $this->redirect($url, navigate: true);
    }

    /**
     * Reset all filters with loading state
     */
    public function resetAllFilters()
    {
        $this->resetLoading = true;

        $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);

        // Navigate to clean home URL
        return $this->redirect(route('home'), navigate: true);
    }

    /**
     * Reset all loading states
     */
    private function resetLoadingStates()
    {
        $this->searchLoading = false;
        $this->roomsLoading = false;
        $this->cityLoading = false;
        $this->typeLoading = false;
        $this->resetLoading = false;
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
            'maxRooms' => max(Product::active()->max('room') ?? 0, 5),
        ]);
    }
}
