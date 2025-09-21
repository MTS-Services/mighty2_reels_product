<?php

namespace App\Livewire\Frontend;

use App\Models\FavouretReel;
use App\Models\Product as ModelsProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'product'])]
class Product extends Component
{
    public $productDetails = true;
    public $favourite = [];
    public $activeVideo = null;
    public $videos = [];
    public $images = [];
    public $allMedia = [];
    public $selectedProductId = null;
    public $selectedReelId = null;
    public $showMergedView = false;
    public $productData = null;
    public $scrollToIndex = null;

    /**
     * Initialize videos and set the default active video based on the passed ID
     */
    public function mount($id = null, $reel_id = null)
    {
        $this->selectedProductId = $id;
        $this->selectedReelId = $reel_id;

        if ($id) {
            $this->loadProductData($id);
        }
    }

    /**
     * Load specific product data with all its media using unified file system
     */
    private function loadProductData($productId)
    {
        // Load product with unified files relationship
        $product = ModelsProduct::with(['files', 'city'])->active()->find($productId);

        if (!$product) {
            abort(404, 'Product not found');
        }

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

        // Collect all media (images and videos) for this product using unified files
        $allMedia = [];

        // Get all active files for this product
        $files = $product->files()->active()->orderBy('sort_order')->orderBy('id')->get();

        foreach ($files as $file) {
            $mediaItem = [
                'id' => $product->id,
                'file_id' => $file->id,
                'type' => $file->file_type == 1 ? 'image' : 'video',
                'url' => $file->file ? Storage::url($file->file) : null,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => $isFavorite,
                'sort_order' => $file->sort_order ?? rand(1, 1000),
            ];

            // Add specific ID based on file type for compatibility
            if ($file->file_type == 1) { // Image
                $mediaItem['image_id'] = $file->id;
            } else { // Video/Reel
                $mediaItem['reel_id'] = $file->id;
            }

            $allMedia[] = $mediaItem;
        }

        // Sort by sort_order, then by ID if same sort_order
        usort($allMedia, function ($a, $b) {
            if ($a['sort_order'] == $b['sort_order']) {
                return $a['file_id'] <=> $b['file_id'];
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
        
        // Separate videos and images for compatibility
        $this->videos = array_filter($allMedia, function ($media) {
            return $media['type'] === 'video';
        });
        
        $this->images = array_filter($allMedia, function ($media) {
            return $media['type'] === 'image';
        });

        // Reset array keys
        $this->videos = array_values($this->videos);
        $this->images = array_values($this->images);
    }

    /**
     * Toggle merged view (up arrow functionality) and reset scroll index.
     */
    public function toggleMergedView()
    {
        $this->showMergedView = !$this->showMergedView;
        $this->scrollToIndex = null;
    }

    /**
     * Switch to the regular view and set the index to scroll to.
     */
    public function switchToRegularView($index)
    {
        $this->scrollToIndex = $index;
        $this->showMergedView = false;
    }

    /**
     * Toggle favorite status for a product
     */
    public function toggleFavourite($productId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to view your favorites');
        }

        $targetId = $productId ?? $this->selectedProductId;
        if (!$targetId) {
            session()->flash('error', 'Product not found');
            return;
        }

        // Initialize favourite array if not exists
        if (!array_key_exists($targetId, $this->favourite)) {
            $this->favourite[$targetId] = false;
        }

        $userId = Auth::id();
        $isFavorite = $this->favourite[$targetId];

        try {
            if ($isFavorite) {
                // Remove from favorites
                FavouretReel::where('user_id', $userId)
                    ->where('product_id', $targetId)
                    ->delete();
                $this->favourite[$targetId] = false;
                session()->flash('message', 'Removed from favorites');
            } else {
                // Add to favorites
                FavouretReel::updateOrCreate([
                    'user_id' => $userId,
                    'product_id' => $targetId,
                ], [
                    'created_by' => $userId,
                ]);
                $this->favourite[$targetId] = true;
                session()->flash('message', 'Added to favorites');
            }

            // Update all media items with the same product ID
            foreach ($this->allMedia as &$media) {
                if ($media['id'] == $targetId) {
                    $media['favourite'] = $this->favourite[$targetId];
                }
            }
            unset($media);

            // Update videos and images arrays
            foreach ($this->videos as &$video) {
                if ($video['id'] == $targetId) {
                    $video['favourite'] = $this->favourite[$targetId];
                }
            }
            unset($video);

            foreach ($this->images as &$image) {
                if ($image['id'] == $targetId) {
                    $image['favourite'] = $this->favourite[$targetId];
                }
            }
            unset($image);

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating favorites');
            Log::error('Toggle favorite error: ' . $e->getMessage());
        }
    }

    /**
     * Get the current product's favorite status
     */
    public function getCurrentProductFavorite()
    {
        return $this->favourite[$this->selectedProductId] ?? false;
    }

    /**
     * Load more products (for future pagination/infinite scroll)
     */
    public function loadMoreProducts()
    {
        // Implementation for loading more products if needed
        // This could be used for infinite scroll or pagination
    }

    /**
     * Share product (future feature)
     */
    public function shareProduct($productId = null)
    {
        $targetId = $productId ?? $this->selectedProductId;
        // Implementation for sharing functionality
        session()->flash('message', 'Share link copied to clipboard');
    }

    /**
     * Report product (future feature)
     */
    public function reportProduct($productId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to report content');
        }

        $targetId = $productId ?? $this->selectedProductId;
        // Implementation for reporting functionality
        session()->flash('message', 'Product reported successfully');
    }

    /**
     * Get product statistics
     */
    public function getProductStats()
    {
        if (!$this->productData) {
            return [
                'total_files' => 0,
                'images_count' => 0,
                'videos_count' => 0,
                'favorites_count' => 0
            ];
        }

        return [
            'total_files' => $this->productData->getTotalFilesCount(),
            'images_count' => $this->productData->getImagesCount(),
            'videos_count' => $this->productData->getVideosCount(),
            'favorites_count' => $this->productData->favorites_count ?? 0
        ];
    }

    /**
     * Check if product has mixed media (both images and videos)
     */
    public function hasMixedMedia()
    {
        return count($this->images) > 0 && count($this->videos) > 0;
    }

    /**
     * Get media summary text
     */
    public function getMediaSummary()
    {
        $imageCount = count($this->images);
        $videoCount = count($this->videos);
        
        if ($imageCount > 0 && $videoCount > 0) {
            return "{$imageCount} image(s) and {$videoCount} video(s)";
        } elseif ($imageCount > 0) {
            return "{$imageCount} image(s)";
        } elseif ($videoCount > 0) {
            return "{$videoCount} video(s)";
        }
        
        return "No media available";
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
            'productStats' => $this->getProductStats(),
            'mediaSummary' => $this->getMediaSummary(),
            'hasMixedMedia' => $this->hasMixedMedia(),
        ]);
    }
}