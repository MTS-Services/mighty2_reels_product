<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Models\FavouretReel;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Home extends Component
{
    public $currentVideoId = null;
    public $showVideo = true;
    public $favourite = [];
    public $volume = 1.0;
    public $isMuted = false;
    public $videos = [];
    
    // Search properties
    public $searchTerm = '';
    public $searchRoom = '';
    public $searchCity = '';
    public $searchType = '';
    public $noResults = false;
    public $searchApplied = false;

    protected $listeners = ['search-updated' => 'handleSearch'];

    public function mount()
    {
        $this->loadVideos();
    }

    public function loadVideos($search = null)
    {
        $query = Product::with(['reels', 'images', 'city'])->active()->orderBy('sort_order');
        
        // Apply search filters if provided
        if ($search) {
            $this->searchTerm = $search['search'] ?? '';
            $this->searchRoom = $search['room'] ?? '';
            $this->searchCity = $search['city'] ?? '';
            $this->searchType = $search['type'] ?? '';
            
            // Search by title or description
            if (!empty($this->searchTerm)) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
                });
            }
            
            // Filter by room count
            if (!empty($this->searchRoom)) {
                $query->where('room', $this->searchRoom);
            }
            
            // Filter by city
            if (!empty($this->searchCity)) {
                $query->where('city_id', $this->searchCity);
            }
            
            // Filter by type (rent/sale)
            if ($this->searchType !== '') {
                $query->where('type', $this->searchType);
            }
            
            $this->searchApplied = !empty($this->searchTerm) || !empty($this->searchRoom) || !empty($this->searchCity) || $this->searchType !== '';
        } else {
            $this->searchApplied = false;
        }
        
        $products = $query->get();
        
        $this->noResults = $products->isEmpty() && $this->searchApplied;
        
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = FavouretReel::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        $this->videos = [];
        foreach ($products as $product) {
            $isFavorite = in_array($product->id, $userFavorites);
            $this->favourite[$product->id] = $isFavorite;
            
            $this->videos[] = [
                'id' => $product->id,
                'file' => $product->reels->first()?->file
                    ? Storage::url($product->reels->first()->file)
                    : null,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => $isFavorite,
                'room' => $product->room,
                'city' => $product->city?->name ?? 'N/A',
                'type' => $product->type_label,
                'price' => $product->price,
            ];
        }

        if (!empty($this->videos)) {
            $this->currentVideoId = $this->videos[0]['id'];
        } else {
            $this->currentVideoId = null;
        }
        
        // Dispatch event to update the video player
        $this->dispatch('videos-updated');
    }

    public function handleSearch($searchData)
    {
        $this->loadVideos($searchData);
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchRoom = '';
        $this->searchCity = '';
        $this->searchType = '';
        $this->searchApplied = false;
        $this->noResults = false;
        
        $this->loadVideos();
        
        // Notify header to clear its search fields
        $this->dispatch('searchCleared');
    }

    public function updateCurrentVideo($videoId)
    {
        $this->currentVideoId = $videoId;
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
                    break;
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

    public function getSearchSummary()
    {
        $summary = [];
        
        if (!empty($this->searchTerm)) {
            $summary[] = "'{$this->searchTerm}'";
        }
        
        if (!empty($this->searchRoom)) {
            $summary[] = "{$this->searchRoom} " . ($this->searchRoom > 1 ? 'rooms' : 'room');
        }
        
        if (!empty($this->searchCity)) {
            $city = City::find($this->searchCity);
            if ($city) {
                $summary[] = "in {$city->name}";
            }
        }
        
        if ($this->searchType !== '') {
            $summary[] = "for " . ($this->searchType == Product::TYPE_RENT ? 'rent' : 'sale');
        }
        
        return empty($summary) ? '' : 'Showing results ' . implode(', ', $summary);
    }

    public function render()
    {
        return view('livewire.frontend.home', [
            'videos' => $this->videos,
            'searchSummary' => $this->getSearchSummary(),
        ]);
    }
}