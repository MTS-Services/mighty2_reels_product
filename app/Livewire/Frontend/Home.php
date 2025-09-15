<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Models\FavouretReel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
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

    public function mount()
    {
        // Fetch products with reels in random order
        $products = Product::with(['reels', 'images'])
            ->active()
            ->has('reels') // Only get products that have reels
            ->inRandomOrder()
            ->get();

        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = FavouretReel::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        foreach ($products as $product) {
            $isFavorite = in_array($product->id, $userFavorites);
            $this->favourite[$product->id] = $isFavorite;

            // Get only ONE random active reel for this product
            $randomReel = $product->reels()->active()->inRandomOrder()->first();

            if ($randomReel) {
                $this->videos[] = [
                    'id' => $product->id, // Product ID for navigation
                    'reel_id' => $randomReel->id, // Reel ID for tracking
                    'file' => $randomReel->file ? Storage::url($randomReel->file) : null,
                    'title' => $product->title,
                    'description' => $product->description,
                    'favourite' => $isFavorite,
                ];
            }
        }

        // Shuffle the final videos array for additional randomization
        $this->videos = collect($this->videos)->shuffle()->toArray();

        if (!empty($this->videos)) {
            $this->currentVideoId = $this->videos[0]['id'];
            $this->currentReelId = $this->videos[0]['reel_id'];
        }
    }

    public function updateCurrentVideo($videoId)
    {
        $this->currentVideoId = $videoId;
        // Find the reel_id for this video
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

            // Update all videos with the same product ID
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

    /**
     * Navigate to product page with the currently active video ID and reel ID
     */
    public function goToProduct()
    {
        $productId = $this->currentActiveVideoId ?? ($this->videos[0]['id'] ?? 1);
        $reelId = $this->currentReelId ?? ($this->videos[0]['reel_id'] ?? null);
        
        // Pass both product ID and reel ID to the product page
        if ($reelId) {
            return redirect()->route('product', [
                'id' => $productId,
                'reel_id' => $reelId
            ]);
        } else {
            return redirect()->route('product', ['id' => $productId]);
        }
    }

    /**
     * Update the currently active video ID (called from frontend)
     */
    public function setActiveVideo($videoId)
    {
        $this->currentActiveVideoId = $videoId;
        // Also update the current reel ID
        $video = collect($this->videos)->firstWhere('id', $videoId);
        if ($video) {
            $this->currentReelId = $video['reel_id'];
        }
    }

    /**
     * Refresh the videos with new random order
     */
    public function refreshReels()
    {
        $this->mount(); // Re-run the mount method to get new random order
    }

    public function render()
    {
        return view('livewire.frontend.home', [
            'videos' => $this->videos,
        ]);
    }
}