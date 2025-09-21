<?php

namespace App\Livewire\Backend;

use App\Models\Product;
use App\Models\ProductReel; // video model
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product Details', 'page_slug' => 'product-details'])]
class Details extends Component
{
    public $product;
    public $videos; // ProductReel videos
    public $activeMedia; // image or video file
    public $activeType;  // 'image' or 'video'

    public function mount($productId)
    {
        $this->product = Product::with(['files' => function($query) {
            // Order by isprimary first (true values first), then by created_at
            $query->orderBy('is_primary', 'desc')->orderBy('created_at', 'asc');
        }])->findOrFail($productId);

        // Load videos from ProductReel
        $this->videos = $this->product->reels;

        // Set active media - prioritize primary file first
        $this->setInitialActiveMedia();
    }

    private function setInitialActiveMedia()
    {
        // First, check if there's a primary image
        $primaryImage = $this->product->images->where('isprimary', true)->first();
        
        if ($primaryImage) {
            $this->activeMedia = $primaryImage->file;
            $this->activeType = 'image';
            return;
        }

        // If no primary image, check for any image
        if ($this->product->images->isNotEmpty()) {
            $this->activeMedia = $this->product->images->first()->file;
            $this->activeType = 'image';
            return;
        }

        // If no images, check for primary video
        $primaryVideo = $this->videos->where('isprimary', true)->first();
        
        if ($primaryVideo) {
            $this->activeMedia = $primaryVideo->file;
            $this->activeType = 'video';
            return;
        }

        // If no primary video, check for any video
        if ($this->videos->isNotEmpty()) {
            $this->activeMedia = $this->videos->first()->file;
            $this->activeType = 'video';
            return;
        }

        // Fallback to placeholder
        $this->activeMedia = 'assets/images/placeholder.png';
        $this->activeType = 'image';
    }

    public function changeMedia($file, $type)
    {
        $this->activeMedia = $file;
        $this->activeType = $type;
    }

    public function render()
    {
        return view('livewire.backend.details');
    }
}