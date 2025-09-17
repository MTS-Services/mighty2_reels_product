<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Models\FavouretReel;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
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

    // Filter properties with URL binding
    #[Url(as: 'search')]
    public $searchTerm = '';

    #[Url(as: 'rooms')]
    public $selectedRooms = '';

    #[Url(as: 'city')]
    public $selectedCity = '';

    #[Url(as: 'type')]
    public $selectedType = '';

    // Loading state
    public $isLoading = false;

    protected $listeners = [
        'updateHomeFilters' => 'applyFilters',
    ];

    public function mount()
    {
        // Load videos initially
        $this->loadVideos();
    }

    /**
     * Handle property updates dynamically.
     */
    public function updated($property)
    {
        if (in_array($property, ['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType'])) {
            $this->loadVideos();
        }
    }

    /**
     * Apply filters coming from UserHeader component (via event dispatch)
     */
    public function applyFilters($filters)
    {
        $this->searchTerm = $filters['searchTerm'] ?? '';
        $this->selectedRooms = $filters['selectedRooms'] ?? '';
        $this->selectedCity = $filters['selectedCity'] ?? '';
        $this->selectedType = $filters['selectedType'] ?? '';

        $this->loadVideos();
    }

    public function loadVideos()
    {
        $this->isLoading = true;

        // Build query with filters
        $query = Product::with(['reels', 'images', 'city'])
            ->active()
            ->has('reels'); // Only products with reels

        if ($this->searchTerm !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('price', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('room', 'like', '%' . $this->searchTerm . '%');
            })->orWhereHas('city', function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->selectedRooms != '') {
            $query->where('room', $this->selectedRooms);
        }

        if ($this->selectedCity != '') {
            $query->where('city_id', $this->selectedCity);
        }

        if ($this->selectedType != '') {
            $query->where('type', $this->selectedType);
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

            $randomReel = $product->reels()->active()->inRandomOrder()->first();

            if ($randomReel) {
                $this->videos[] = [
                    'id' => $product->id,
                    'reel_id' => $randomReel->id,
                    'file' => $randomReel->file ? Storage::url($randomReel->file) : null,
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
        }

        $this->isLoading = false;
        $this->render();
    }

    public function resetFilters()
    {
        $this->reset(['searchTerm', 'selectedRooms', 'selectedCity', 'selectedType']);
        $this->loadVideos();
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
        return view('livewire.frontend.home', [
            'videos' => $this->videos,
            'cities' => City::active()->pluck('name', 'id'),
            'maxRooms' => max(Product::active()->max('room') ?? 0, 5),
        ]);
    }
}
