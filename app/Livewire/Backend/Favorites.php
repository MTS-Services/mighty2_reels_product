<?php

namespace App\Livewire\Backend;

use App\Models\FavouretReel;
use App\Models\Product;
use App\Models\ProductFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        $products = Product::with(['reels' => function ($query) {
                // Only get video/reel type files, not images
                $query->where('file_type', ProductFile::TYPE_REEL)
                      ->where('status', ProductFile::STATUS_ACTIVE)
                      // Sort by is_primary DESC (primary first), then by sort_order ASC
                      ->orderByRaw('is_primary DESC, sort_order ASC');
            }])
            ->whereIn('id', $favoriteProductIds)
            ->active()
            ->get();

        foreach ($products as $product) {
            $this->favourite[$product->id] = true;

            // Get the first reel based on the sorting we applied
            // (primary first, then by sort_order)
            $selectedReel = $product->reels->first();

            if ($selectedReel) {
                $this->videos[] = [
                    'id' => $product->id,
                    'reel_id' => $selectedReel->id,
                    'file' => Storage::url($selectedReel->file),
                    'title' => $product->title,
                    'description' => $product->description,
                    'favourite' => true,
                ];
            }
        }

        // Randomize order of final videos (so products show randomly, but each product shows its correct reel)
        $this->videos = collect($this->videos)->shuffle()->toArray();

        // Set first video as current
        if (!empty($this->videos)) {
            $this->currentVideoId = $this->videos[0]['id'];
        }
    }

    protected $listeners = ['logout-requested' => 'logout'];

    public function logout()
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return $this->redirectRoute('home', navigate: true);
    }

    public function goToProduct()
    {
        if (!$this->currentVideoId) {
            return redirect()->route('home'); // fallback
        }

        $video = collect($this->videos)->firstWhere('id', $this->currentVideoId);

        if ($video && isset($video['reel_id'])) {
            return redirect()->route('product', [
                'id' => $video['id'],
                'reel_id' => $video['reel_id'],
            ]);
        }

        return redirect()->route('product', ['id' => $this->currentVideoId]);
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
        $this->videos = array_filter($this->videos, function ($video) use ($targetId) {
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