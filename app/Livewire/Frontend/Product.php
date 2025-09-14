<?php

namespace App\Livewire\Frontend;

use App\Models\Product as ProductModel;
use App\Models\FavouretReel;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    public $productDetails = true; // Start with product details view
    public $favourite = [];
    public $activeProduct = null;
    public $videos = [];
    public $productId;
    public $volume = 1.0;
    public $isMuted = false;
    public $currentVideoId = null;
    
    // Search properties
    public $searchTerm = '';
    public $searchRoom = '';
    public $searchCity = '';
    public $searchType = '';
    public $noResults = false;
    public $searchApplied = false;
    public $originalProductId = null;

    protected $listeners = ['search-updated' => 'handleSearch'];

    public function mount($id = null)
    {
        $this->originalProductId = $id;
        $this->loadProducts();
    }

    public function loadProducts($search = null)
    {
        $query = ProductModel::with(['reels', 'images', 'city'])->active()->orderBy('sort_order');
        
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
        
        if ($products->isEmpty()) {
            if ($this->searchApplied) {
                $this->noResults = true;
                $this->videos = [];
                $this->currentVideoId = null;
                $this->activeProduct = null;
                return;
            } else {
                abort(404, 'No products found');
            }
        }

        $this->noResults = false;

        // Determine which product to set as active
        if ($this->searchApplied) {
            // If searching, use the first result
            $this->productId = $products->first()->id;
        } else {
            // If not searching, use original ID or first product
            $this->productId = $this->originalProductId ?? $products->first()->id;
        }
        
        // Verify the active product exists in results
        $this->activeProduct = $products->firstWhere('id', $this->productId);
        if (!$this->activeProduct) {
            // If original product not in results, use first available
            $this->activeProduct = $products->first();
            $this->productId = $this->activeProduct->id;
        }

        // Get user favorites
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = FavouretReel::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        // Build videos array with active product first, then others
        $sortedProducts = $products->sortBy(function ($product) {
            return $product->id == $this->productId ? 0 : 1;
        });

        $this->videos = [];
        foreach ($sortedProducts as $product) {
            $isFavorite = in_array($product->id, $userFavorites);
            $this->favourite[$product->id] = $isFavorite;
            
            $this->videos[] = [
                'id' => $product->id,
                'url' => $product->reels->first()?->file
                    ? Storage::url($product->reels->first()->file)
                    : null,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'room' => $product->room,
                'city' => $product->city?->name,
                'type_label' => $product->type_label,
                'favourite' => $isFavorite,
                'images' => $product->images->map(function ($image) {
                    return Storage::url($image->file);
                })->toArray(),
            ];
        }

        // Set current video ID to active product
        $this->currentVideoId = $this->productId;
        
        // Dispatch event to update the video player
        $this->dispatch('videos-updated');
    }

    public function handleSearch($searchData)
    {
        $this->loadProducts($searchData);
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchRoom = '';
        $this->searchCity = '';
        $this->searchType = '';
        $this->searchApplied = false;
        $this->noResults = false;
        
        $this->loadProducts();
        
        // Notify header to clear its search fields
        $this->dispatch('searchCleared');
    }

    public function updateCurrentVideo($videoId)
    {
        $this->currentVideoId = $videoId;
        
        // Update active product when video changes
        $this->activeProduct = ProductModel::with(['reels', 'images', 'city'])->find($videoId);
        $this->productId = $videoId;
    }

    public function toggleVideoDetails()
    {
        $this->productDetails = !$this->productDetails;
    }

    public function toggleFavourite($productId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to view your favorites');
        }

        $targetId = $productId ?? $this->currentVideoId;
        if (!$targetId || !array_key_exists($targetId, $this->favourite)) {
            session()->flash('error', 'Product not found');
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

            // Update videos array
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

    public function switchToProduct($productId)
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
            $summary[] = "for " . ($this->searchType == ProductModel::TYPE_RENT ? 'rent' : 'sale');
        }
        
        return empty($summary) ? '' : 'Showing results ' . implode(', ', $summary);
    }

    public function render()
    {
        return view('livewire.frontend.product', [
            'videos' => $this->videos,
            'searchSummary' => $this->getSearchSummary(),
        ]);
    }
}