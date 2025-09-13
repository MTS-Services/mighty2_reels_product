<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Models\FavouretReel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Home extends Component
{
    public $currentVideoId = null; // Track the current video ID
    public $showVideo = true;
    public $favourite = [];
    public $volume = 1.0;
    public $isMuted = false;
    public $videos = [];

    public function mount()
    {
        // Load products with reels
        $products = Product::with(['reels', 'images'])->active()->orderBy('sort_order')->get();
        
        // Get user's favorites if logged in
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = FavouretReel::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

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
            ];
        }

        // Set the first video as current if available
        if (!empty($this->videos)) {
            $this->currentVideoId = $this->videos[0]['id'];
        }
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

        // Debug information
        Log::info('toggleFavourite called', [
            'productId' => $productId,
            'currentVideoId' => $this->currentVideoId,
            'videos_count' => count($this->videos)
        ]);

        // Use current video ID if no specific ID provided
        $targetId = $productId ?? $this->currentVideoId;
        
        if (!$targetId) {
            Log::warning('No target ID available for toggleFavourite');
            session()->flash('error', 'Unable to identify current video');
            return;
        }

        if (!array_key_exists($targetId, $this->favourite)) {
            Log::warning('Target ID not found in favourite array', ['targetId' => $targetId]);
            session()->flash('error', 'Video not found');
            return;
        }

        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('message', 'Please login to add favorites');
            return;
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
                    break;
                }
            }
            unset($video);

        } catch (\Exception $e) {
            Log::error('Error toggling favorite', [
                'error' => $e->getMessage(),
                'targetId' => $targetId,
                'userId' => $userId
            ]);
            session()->flash('error', 'An error occurred while updating favorites');
        }
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
        return view('livewire.frontend.home', [
            'videos' => $this->videos,
        ]);
    }
}