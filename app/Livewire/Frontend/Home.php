<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Home extends Component
{
    public $productId = null;
    public $showVideo = true;
    public $currentActiveVideoId = null; // Track currently active video

    /**
     * Stores the favorite status for each product ID.
     * Example: ['1' => true, '2' => false]
     */
    public $favourite = [];

    public $volume = 1.0; // Default volume
    public $isMuted = false; // Default not muted

    public $videos = []; // Store video data for frontend

    public function mount()
    {
        // Load products with reels
        $products = Product::with(['reels', 'images'])->active()->orderBy('sort_order')->get();

        foreach ($products as $product) {
            $this->favourite[$product->id] = false; // Default favorite status

            $this->videos[] = [
                'id' => $product->id,
                'file' => $product->reels->first()?->file
                    ? Storage::url($product->reels->first()->file)
                    : null,
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => $this->favourite[$product->id],
            ];
        }
    }

    public function toggleVideoDescription()
    {
        $this->showVideo = !$this->showVideo;
    }

    public function toggleFavourite($productId)
    {
        if (!array_key_exists($productId, $this->favourite)) return;

        $this->favourite[$productId] = !$this->favourite[$productId];
        foreach ($this->videos as &$video) {
            if ($video['id'] == $productId) {
                $video['favourite'] = $this->favourite[$productId];
                break;
            }
        }
        unset($video); // Unset the reference
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
     * Navigate to product page with the currently active video ID
     */
    public function goToProduct()
    {
        $productId = $this->currentActiveVideoId ?? ($this->videos[0]['id'] ?? 1);
        return redirect()->route('product', ['id' => $productId]);
    }

    /**
     * Update the currently active video ID (called from frontend)
     */
    public function setActiveVideo($videoId)
    {
        $this->currentActiveVideoId = $videoId;
    }

    public function render()
    {
        return view('livewire.frontend.home', [
            'videos' => $this->videos,
        ]);
    }
}