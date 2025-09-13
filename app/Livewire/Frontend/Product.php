<?php

namespace App\Livewire\Frontend;

use App\Models\Product as ProductModel;
use App\Models\FavouretReel;
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

    public function mount($id = null)
    {
        // Get all products for the video feed
        $products = ProductModel::with(['reels', 'images', 'city'])->active()->orderBy('sort_order')->get();
        
        if ($products->isEmpty()) {
            abort(404, 'No products found');
        }

        // If ID is provided, set it as active, otherwise use first product
        $this->productId = $id ?? $products->first()->id;
        
        // Verify the product exists
        $this->activeProduct = $products->firstWhere('id', $this->productId);
        if (!$this->activeProduct) {
            abort(404, 'Product not found');
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

    public function render()
    {
        return view('livewire.frontend.product', [
            'videos' => $this->videos,
        ]);
    }
}