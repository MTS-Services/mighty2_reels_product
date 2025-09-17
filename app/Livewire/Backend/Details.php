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
        $this->product = Product::with('images')->findOrFail($productId);


        // Load videos from ProductReel
        $this->videos = $this->product->reels;

        if ($this->product->images->isNotEmpty()) {
            $this->activeMedia = $this->product->images->first()->file;
            $this->activeType = 'image';
        } elseif ($this->videos->isNotEmpty()) {
            $this->activeMedia = $this->videos->first()->file;
            $this->activeType = 'video';
        } else {
            $this->activeMedia = 'assets/images/placeholder.png';
            $this->activeType = 'image';
        }
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
