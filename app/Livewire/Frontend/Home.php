<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Models\FavouretReel;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Home extends Component
{
    public $currentVideoId = null;
    public $currentActiveVideoId = null;
    public $currentReelId = null;
    public $showVideo = true;
    public $favourite = [];
    public $volume = 1.0;
    public $isMuted = false;
    public $videos = [];

    // Header/Navigation states
    // public bool $sidebarOpen = false;
    public bool $openProfileDropdown = false;

    // Modal states
    public bool $showFilterModal = false;
    public bool $showSearchModal = false;

    // Filter properties with URL binding - these handle the actual data filtering
    #[Url(as: 'search', except: '')]
    public $searchTerm = '';

    #[Url(as: 'rooms', except: '')]
    public $selectedRooms = '';

    #[Url(as: 'city', except: '')]
    public $selectedCity = '';

    #[Url(as: 'type', except: '')]
    public $selectedType = '';

    // Room selection array for multiple room selection
    public $selectedRoomsArray = [];

    // City search property
    public $citySearch = '';

    // Loading states
    public $isLoading = false;
    public $searchLoading = false;
    public $roomsLoading = false;
    public $cityLoading = false;
    public $typeLoading = false;
    public $resetLoading = false;


    // Search History
    public $recentSearches = [];

    // Event listeners for header actions

    public function mount()
    {
        // Convert comma-separated rooms string to array
        $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];

        // Load search history from session
        $this->recentSearches = Session::get('recentSearches', []);

        // Load videos initially
        $this->loadVideos();
    }

    /**
     * Handle property updates dynamically.
     */
    public function updated($property)
    {
        if (in_array($property, ['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType'])) {
            // Update selectedRoomsArray when selectedRooms URL parameter changes
            if ($property === 'selectedRooms') {
                $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];
            }

            $this->loadVideos();
        }
    }

    // Filter Modal methods
    #[On('open-filter-modal')]
    public function openFilterModal()
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
        $this->citySearch = ''; // Reset city search when closing modal
    }

    public function applyFilters()
    {
        $this->showFilterModal = false;
        $this->citySearch = ''; // Reset city search when applying filters
        $this->navigateWithFilters();
    }

    // Search Modal methods
    #[On('open-search-modal')]
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

        if (!$this->showFilterModal) {
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

        if (!$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Alternative method - Set to FOR SALE
     */
    public function setForSale()
    {
        $this->selectedType = 'sale';

        if (!$this->showFilterModal) {
            $this->typeLoading = true;
            $this->navigateWithFilters();
        }
    }

    /**
     * Alternative method - Set to FOR RENT
     */
    public function setForRent()
    {
        $this->selectedType = 'rent';

        if (!$this->showFilterModal) {
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

        // Apply filters immediately if NOT in modal
        if (!$this->showFilterModal) {
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

        // Apply filters immediately if NOT in modal
        if (!$this->showFilterModal) {
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

    // Auto-apply for search term
    public function updatedSearchTerm()
    {
        // Only auto-search if NOT in modal and search term is not empty
        if (!$this->showSearchModal && !empty(trim($this->searchTerm))) {
            $this->addToSearchHistory(trim($this->searchTerm));
            $this->searchLoading = true;
            $this->navigateWithFilters();
        }
    }

    public function updatedSelectedRooms()
    {
        // Update array when string changes (from URL or other sources)
        $this->selectedRoomsArray = !empty($this->selectedRooms) ? explode(',', $this->selectedRooms) : [];

        if (!$this->showFilterModal) {
            $this->roomsLoading = true;
            $this->navigateWithFilters();
        }
    }

    // Auto-apply for city selection
    public function updatedSelectedCity()
    {
        if (!$this->showFilterModal) {
            $this->cityLoading = true;
            $this->navigateWithFilters();
        }
    }

    // Auto-apply for type selection
    public function updatedSelectedType()
    {
        if (!$this->showFilterModal) {
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
    #[On('reset-all-filters')]
    public function resetAllFilters()
    {
        $this->resetLoading = true;
        $this->selectedRoomsArray = [];
        $this->citySearch = ''; // Reset city search
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
  protected $listeners = ['logout-requested' => 'logout'];

    public function logout()
    {

        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return $this->redirectRoute('home', navigate: true);
    }

    public function loadVideos()
    {
        $this->isLoading = true;
        // Build query with filters
        $query = Product::with(['reels', 'images', 'city'])
            ->active()
            ->has('reels'); // Only products with reels

        // Search functionality - improved logic
        if (!empty(trim($this->searchTerm))) {
            $searchTerm = trim($this->searchTerm);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('price', 'like', '%' . $searchTerm . '%')
                    ->orWhere('room', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('city', function ($cityQuery) use ($searchTerm) {
                        $cityQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Room filter - Updated to handle multiple selections
        if (!empty($this->selectedRoomsArray)) {
            $query->where(function ($q) {
                foreach ($this->selectedRoomsArray as $room) {
                    if ($room === '5+') {
                        // For 5+, show all rooms that are 5 or greater
                        $q->orWhere('room', '>=', 5);
                    } else {
                        // For specific room numbers (1-4), show exact match
                        $q->orWhere('room', $room);
                    }
                }
            });
        }

        // City filter
        if (!empty($this->selectedCity)) {
            $query->where('city_id', $this->selectedCity);
        }

        // Type filter - properly handle the type filtering
        if (!empty($this->selectedType)) {
            // Map frontend values to database values
            $typeMapping = [
                'sale' => Product::TYPE_SALE,
                'rent' => Product::TYPE_RENT,
            ];

            // Use the mapped value if it exists, otherwise use the raw value
            $dbType = $typeMapping[$this->selectedType] ?? $this->selectedType;
            $query->where('type', $dbType);
        }

        $products = $query->inRandomOrder()->get();

        // Get user favorites
        $userFavorites = Auth::check()
            ? FavouretReel::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        $this->videos = [];
        $this->favourite = [];

        foreach ($products as $product) {
            $isFavorite = in_array($product->id, $userFavorites);
            $this->favourite[$product->id] = $isFavorite;

            // First try to get primary reel, if none exists get random reel
            $selectedReel = $product->reels()->active()->where('is_primary', 1)->first();

            if (!$selectedReel) {
                $selectedReel = $product->reels()->active()->inRandomOrder()->first();
            }

            if ($selectedReel) {
                $this->videos[] = [
                    'id' => $product->id,
                    'reel_id' => $selectedReel->id,
                    'file' => $selectedReel->file ? Storage::url($selectedReel->file) : null,
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                    'room' => $product->room,
                    'type' => $product->getTypeLabelAttribute(),
                    'city_name' => $product->city?->name ?? 'Unknown',
                    'favourite' => $isFavorite,
                ];
            }
        }

        $this->videos = collect($this->videos)->shuffle()->toArray();

        if (!empty($this->videos)) {
            $this->currentVideoId = $this->videos[0]['id'];
            $this->currentReelId = $this->videos[0]['reel_id'];
        } else {
            $this->currentVideoId = null;
            $this->currentReelId = null;
        }

        $this->isLoading = false;
        $this->resetLoadingStates();
    }

    public function resetFilters()
    {
        $this->selectedRoomsArray = [];
        $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);

        // Clear URL parameters by redirecting to clean URL
        $this->redirect(route('home'), navigate: true);
    }

    public function updateCurrentVideo($videoId)
    {
        $this->currentVideoId = $videoId;
        $video = collect($this->videos)->firstWhere('id', $videoId);
        if ($video) {
            $this->currentReelId = $video['reel_id'];
        }
    }

    public function toggleVideoDescription()
    {
        $this->showVideo = !$this->showVideo;
    }

    public function toggleFavourite($productId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to view your favorites');
        }

        $targetId = $productId ?? $this->currentVideoId;
        if (!$targetId || !array_key_exists($targetId, $this->favourite)) {
            session()->flash('error', 'Video not found');
            return;
        }

        $userId = Auth::id();
        $isFavorite = $this->favourite[$targetId];

        try {
            if ($isFavorite) {
                FavouretReel::where('user_id', $userId)
                    ->where('product_id', $targetId)
                    ->delete();
                $this->favourite[$targetId] = false;
                session()->flash('message', 'Removed from favorites');
            } else {
                FavouretReel::create([
                    'user_id' => $userId,
                    'product_id' => $targetId,
                    'created_by' => $userId,
                ]);
                $this->favourite[$targetId] = true;
                session()->flash('message', 'Added to favorites');
            }

            // Update the videos array
            foreach ($this->videos as &$video) {
                if ($video['id'] == $targetId) {
                    $video['favourite'] = $this->favourite[$targetId];
                }
            }
            unset($video);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating favorites');
        }
    }

    public function viewProduct($productId)
    {
        return redirect()->route('product', ['id' => $productId]);
    }

    public function toggleMute()
    {
        $this->isMuted = !$this->isMuted;
        if (!$this->isMuted && $this->volume == 0) {
            $this->volume = 0.5;
        }
    }

    public function setVolume($level)
    {
        $this->volume = max(0.0, min(1.0, (float) $level));
        $this->isMuted = ($this->volume === 0);
    }

    public function goToProduct()
    {
        $productId = $this->currentActiveVideoId ?? ($this->videos[0]['id'] ?? 1);
        $reelId = $this->currentReelId ?? ($this->videos[0]['reel_id'] ?? null);

        if ($reelId) {
            return redirect()->route('product', [
                'id' => $productId,
                'reel_id' => $reelId
            ]);
        }

        return redirect()->route('product', ['id' => $productId]);
    }

    public function setActiveVideo($videoId)
    {
        $this->currentActiveVideoId = $videoId;
        $video = collect($this->videos)->firstWhere('id', $videoId);
        if ($video) {
            $this->currentReelId = $video['reel_id'];
        }
    }

    public function refreshReels()
    {
        $this->loadVideos();
    }

    public function render()
    {
        // Get all cities
        $allCities = City::active()->pluck('name', 'id');

        // Filter cities based on search term
        $filteredCities = $allCities;
        if (!empty($this->citySearch)) {
            $filteredCities = $allCities->filter(function ($name, $id) {
                return stripos($name, $this->citySearch) !== false;
            });
        }

        return view('livewire.frontend.home', [
            'videos' => $this->videos,
            'cities' => $filteredCities,
            'maxRooms' => max(Product::active()->max('room') ?? 0, 5),
        ]);
    }
}
