<?php

namespace App\Livewire\Frontend;

use App\Models\FavouretReel;
use App\Models\Product as ModelsProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    public $productDetails = true; // Show product details first
    public $favourite = [];    // Favorite status for products
    public $activeVideo = null; // Current selected video/product
    public $videos = [];        // All videos/products
    public $images = [];        // All images for the product
    public $allMedia = [];      // Combined images and videos
    public $selectedProductId = null; // The product ID passed from home
    public $selectedReelId = null; // The specific reel ID from home
    public $showMergedView = false; // Toggle for merged view
    public $productData = null; // Store product details

    /**
     * Initialize videos and set the default active video based on the passed ID
     */
    public function mount($id = null, $reel_id = null)
    {
        $this->selectedProductId = $id;
        $this->selectedReelId = $reel_id;
        
            $this->loadProductData($id);
     
    }

    /**
     * Load specific product data with all its media
     */
    private function loadProductData($productId)
    {
        $product = ModelsProduct::with(['reels', 'images', 'city'])->active()->find($productId);
        
       

        $this->productData = $product;
        
        // Get user favorites
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = FavouretReel::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }
        
        $isFavorite = in_array($product->id, $userFavorites);
        $this->favourite[$product->id] = $isFavorite;

        // Collect all media (images and reels) for this product
        $allMedia = [];

        // Add reels as videos
        foreach ($product->reels()->active()->get() as $reel) {
            $allMedia[] = [
                'id' => $product->id,
                'reel_id' => $reel->id,
                'type' => 'video',
                'url' => $reel->file ? Storage::url($reel->file) : null,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => $isFavorite,
                'sort_order' => $reel->sort_order ?? rand(1, 1000),
            ];
        }

        // Add images - FIX: Use correct attribute name
        foreach ($product->images()->active()->get() as $image) {
            $imageUrl = null;
            
            // Try different possible attribute names for the image file
            if ($image->file) {
                $imageUrl = Storage::url($image->file);
            } elseif (isset($image->image) && $image->image) {
                $imageUrl = Storage::url($image->image);
            }

            $allMedia[] = [
                'id' => $product->id,
                'image_id' => $image->id,
                'type' => 'image',
                'url' => $imageUrl,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => $isFavorite,
                'sort_order' => $image->sort_order ?? rand(1, 1000),
            ];
        }

        // Sort by sort_order, then by random if sort_order is the same
        usort($allMedia, function($a, $b) {
            if ($a['sort_order'] == $b['sort_order']) {
                return rand(-1, 1); // Random if same sort_order
            }
            return $a['sort_order'] <=> $b['sort_order'];
        });

        // If a specific reel was selected, move it to the front
        if ($this->selectedReelId) {
            $selectedIndex = null;
            foreach ($allMedia as $index => $media) {
                if ($media['type'] === 'video' && isset($media['reel_id']) && $media['reel_id'] == $this->selectedReelId) {
                    $selectedIndex = $index;
                    break;
                }
            }
            
            if ($selectedIndex !== null) {
                $selectedMedia = $allMedia[$selectedIndex];
                unset($allMedia[$selectedIndex]);
                array_unshift($allMedia, $selectedMedia);
                $allMedia = array_values($allMedia);
            }
        }

        $this->allMedia = $allMedia;
        $this->videos = array_filter($allMedia, function($media) {
            return $media['type'] === 'video';
        });
        $this->images = array_filter($allMedia, function($media) {
            return $media['type'] === 'image';
        });

        // Reset array keys
        $this->videos = array_values($this->videos);
        $this->images = array_values($this->images);
    }

  

    public function toggleVideoDetails()
    {
        $this->productDetails = !$this->productDetails;
    }

    /**
     * Toggle merged view (up arrow functionality)
     */
    public function toggleMergedView()
    {
        $this->showMergedView = !$this->showMergedView;
    }

   public function toggleFavourite($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to view your favorites');
        }

        if (!$productId) {
            session()->flash('error', 'Product not found');
            return;
        }

        $userId = Auth::id();

        $check = FavouretReel::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($check) {
            $check->force();
            session()->flash('message', 'Removed from favorites');
        } else {
            FavouretReel::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'created_by' => $userId,
            ]);
            session()->flash('message', 'Added to favorites');
        }
    }
    /**
     * Set active video by ID
     */
    public function setActiveVideo($videoId)
    {
        $this->activeVideo = collect($this->videos)->firstWhere('id', $videoId);
    }
    
    /**
     * Render the Blade view
     */
    public function render()
    {
        return view('livewire.frontend.product', [
            'videos' => $this->videos,
            'images' => $this->images,
            'allMedia' => $this->allMedia,
            'productData' => $this->productData,
            'showMergedView' => $this->showMergedView,
        ]);
    }
}