<?php

namespace App\Livewire\Backend;

use App\Models\FavouretReel;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Favorites', 'page_slug' => 'Favorites'])]
class Favorites extends Component
{
    public $currentVideoId = null;
    public $showVideo = true;
    public $favourite = [];
    public $volume = 1.0;
    public $isMuted = false;
    public $videos = [];
    
    public function mount()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to view your favorites');
        }

        // Load user's favorite products with reels
        $favoriteProductIds = FavouretReel::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();

        if (empty($favoriteProductIds)) {
            $this->videos = [];
            return;
        }

        $products = Product::with(['reels', 'images'])
            ->whereIn('id', $favoriteProductIds)
            ->active()
            ->orderBy('sort_order')
            ->get();

        foreach ($products as $product) {
            $this->favourite[$product->id] = true; // All are favorites on this page
            
            $this->videos[] = [
                'id' => $product->id,
                'file' => $product->reels->first()?->file
                    ? Storage::url($product->reels->first()->file)
                    : null,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => true,
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

    public function removeFavorite($productId = null)
    {
        // Use current video ID if no specific ID provided
        $targetId = $productId ?? $this->currentVideoId;
        
        if (!$targetId || !Auth::check()) {
            return;
        }

        // Remove from database
        FavouretReel::where('user_id', Auth::id())
            ->where('product_id', $targetId)
            ->forceDelete();

        // Remove from local arrays
        unset($this->favourite[$targetId]);
        $this->videos = array_filter($this->videos, function($video) use ($targetId) {
            return $video['id'] != $targetId;
        });

        // Reindex the array
        $this->videos = array_values($this->videos);

        // Update current video ID if the removed video was current
        if ($this->currentVideoId == $targetId) {
            $this->currentVideoId = !empty($this->videos) ? $this->videos[0]['id'] : null;
        }

        session()->flash('message', 'Removed from favorites');
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
        return view('livewire.backend.favorites', [
            'videos' => $this->videos,
        ]);
    }
}
