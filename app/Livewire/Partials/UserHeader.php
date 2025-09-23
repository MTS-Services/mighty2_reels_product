<?php
// Fixed UserHeader.php Component
namespace App\Livewire\Partials;

use App\Models\City;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserHeader extends Component
{
    public string $pageSlug;
    public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;
    public bool $filterOpen = false;
    
    // Modal states
    public bool $showFilterModal = false;
    public bool $showSearchModal = false;

    // Filter properties
    public $searchTerm = '';
    public $selectedRooms = '';
    public $selectedRoomsArray = [];
    public $selectedCity = '';
    public $selectedType = '';

    // Loading states
    public $searchLoading = false;
    public $roomsLoading = false;
    public $cityLoading = false;
    public $typeLoading = false;
    public $resetLoading = false;

    // Search History
    public $recentSearches = [];

    protected $listeners = [
        'syncFilters' => 'syncFilters',
    ];

    public function mount(string $pageSlug = '')
    {
        $this->pageSlug = $pageSlug;

        // Load search history from session
        $this->recentSearches = Session::get('recentSearches', []);

        if ($pageSlug === 'home') {
            $this->searchTerm = request()->get('search', '');
            $this->selectedRooms = request()->get('rooms', '');
            $this->selectedCity = request()->get('city', '');
            $this->selectedType = request()->get('type', '');
            
            // Convert comma-separated rooms string to array
            $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];
        }
    }

    // Filter Modal methods
    public function openFilterModal()
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function applyFilters()
    {
        $this->showFilterModal = false;
        $this->navigateWithFilters();
    }

    // Search Modal methods
    public function openSearchModal()
    {
        $this->showSearchModal = true;
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
    }

    /**
     * Trigger search manually (when user clicks search button)
     */
    public function performSearch()
    {
        $term = trim($this->searchTerm);
        if (!empty($term)) {
            $this->addToSearchHistory($term);
        }

        // Close search modal and navigate with filters
        $this->showSearchModal = false;
        $this->navigateWithFilters();
    }

    /**
     * Add search term to history
     */
    private function addToSearchHistory($term)
    {
        $history = Session::get('recentSearches', []);

        // Remove if exists (avoid duplicates)
        $history = array_filter($history, fn($item) => $item !== $term);

        // Add at the beginning
        array_unshift($history, $term);

        // Limit to 10 items
        $history = array_slice($history, 0, 10);

        Session::put('recentSearches', $history);
        $this->recentSearches = $history;
    }

    public function removeSearch($term)
    {
        $history = Session::get('recentSearches', []);
        $history = array_values(array_filter($history, fn($item) => $item !== $term));

        Session::put('recentSearches', $history);
        $this->recentSearches = $history;
    }

    public function clearSearches()
    {
        Session::forget('recentSearches');
        $this->recentSearches = [];
    }

    /**
     * Set property type and apply filters
     */
    public function setPropertyType($type)
    {
        $this->selectedType = $type;
        
        // Apply filters immediately if on home page and NOT in modal
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Clear property type
     */
    public function clearPropertyType()
    {
        $this->selectedType = '';
        
        // Apply filters immediately if on home page and NOT in modal
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Alternative method - Set to FOR SALE
     */
    public function setForSale()
    {
        $this->selectedType = 'sale'; // Use hardcoded value first
        
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Alternative method - Set to FOR RENT
     */
    public function setForRent()
    {
        $this->selectedType = 'rent'; // Use hardcoded value first
        
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Toggle room selection
     */
    public function toggleRoom($room)
    {
        if (in_array($room, $this->selectedRoomsArray)) {
            // Remove room from selection
            $this->selectedRoomsArray = array_values(array_filter(
                $this->selectedRoomsArray,
                fn($r) => $r != $room
            ));
        } else {
            // Add room to selection
            $this->selectedRoomsArray[] = $room;
        }

        // Update the selectedRooms string
        $this->selectedRooms = implode(',', $this->selectedRoomsArray);

        // Apply filters immediately if on home page and NOT in modal
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->roomsLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Clear all room selections
     */
    public function clearSelectedRooms()
    {
        $this->selectedRoomsArray = [];
        $this->selectedRooms = '';

        // Apply filters immediately if on home page and NOT in modal
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->roomsLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Check if a room is selected
     */
    public function isRoomSelected($room)
    {
        return in_array($room, $this->selectedRoomsArray);
    }

    public function syncFilters($filters)
    {
        $this->searchTerm = $filters['searchTerm'] ?? '';
        $this->selectedRooms = $filters['selectedRooms'] ?? '';
        $this->selectedCity = $filters['selectedCity'] ?? '';
        $this->selectedType = $filters['selectedType'] ?? '';

        // Convert comma-separated rooms string to array
        $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];

        $this->resetLoadingStates();
    }

    // FIXED: Re-enable auto-apply for search term
    public function updatedSearchTerm()
    {
        // Only auto-search if NOT in modal and search term is not empty
        if ($this->pageSlug === 'home' && !$this->showSearchModal && !empty(trim($this->searchTerm))) {
            $this->addToSearchHistory(trim($this->searchTerm));
            $this->searchLoading = true;
            $this->navigateWithFilters();
        }
    }

    public function updatedSelectedRooms()
    {
        // Update array when string changes (from URL or other sources)
        $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];
        
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->roomsLoading = true;
            $this->navigateWithFilters();
        }
    }

    // FIXED: Re-enable auto-apply for city selection
    public function updatedSelectedCity()
    {
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->cityLoading = true;
            $this->navigateWithFilters();
        }
    }

    // FIXED: Re-enable auto-apply for type selection
    public function updatedSelectedType()
    {
        if ($this->pageSlug === 'home' && !$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Navigate with current filter state
     */
    private function navigateWithFilters()
    {
        $queryParams = [];

        if (!empty($this->searchTerm)) $queryParams['search'] = $this->searchTerm;
        if (!empty($this->selectedRooms)) $queryParams['rooms'] = $this->selectedRooms;
        if (!empty($this->selectedCity)) $queryParams['city'] = $this->selectedCity;
        if (!empty($this->selectedType)) $queryParams['type'] = $this->selectedType;

        $url = route('home');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $this->redirect($url, navigate: true);
    }

    /**
     * Reset all filters and navigate to clean home page
     */
    public function resetAllFilters()
    {
        $this->resetLoading = true;
        $this->selectedRoomsArray = [];
        $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);
        
        // Close any open modals
        $this->showFilterModal = false;
        $this->showSearchModal = false;
        
        return $this->redirect(route('home'), navigate: true);
    }

    /**
     * Reset loading states
     */
    private function resetLoadingStates()
    {
        $this->searchLoading = false;
        $this->roomsLoading = false;
        $this->cityLoading = false;
        $this->typeLoading = false;
        $this->resetLoading = false;
    }

    /**
     * User logout
     */
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