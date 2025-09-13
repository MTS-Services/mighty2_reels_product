<?php

namespace App\Livewire\Frontend;

use App\Models\Product as ModelsProduct;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    public $productDetails = true; // Show product details first
    public $favourite = false;
    public $activeVideo = null; // Current selected video/product
    public $videos = [];        // All videos/products
    public $selectedProductId = null; // The product ID passed from home

    /**
     * Initialize videos and set the default active video based on the passed ID
     */
    public function mount($id = null)
    {
        
        $this->selectedProductId = $id;
        
        // Load products from database
        $products = ModelsProduct::with(['reels', 'images'])->active()->orderBy('sort_order')->get();
        
        // Convert products to video format for consistency
        $this->videos = [];
        foreach ($products as $product) {
            $this->videos[] = [
                'id' => $product->id,
                'url' => $product->reels->first()?->file 
                    ? Storage::url($product->reels->first()->file) 
                    : 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'title' => $product->title,
                'description' => $product->description,
                'favourite' => false,
                'images' => $product->images->map(function($image) {
                    return Storage::url($image->image);
                })->toArray() ?: [
                    'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg',
                    'https://images.pexels.com/photos/276287/pexels-photo-276287.jpeg'
                ],
            ];
        }

        // If no products found, use fallback data
        if (empty($this->videos)) {
            $this->videos = [
                [
                    'id' => 1,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                    'title' => 'Timeless Elegance: The Chronos Collection',
                    'description' => 'A curated selection of classic watches that blend modern design with traditional craftsmanship.',
                    'favourite' => false,
                    'images' => [
                        'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg',
                        'https://images.pexels.com/photos/276287/pexels-photo-276287.jpeg'
                    ],
                ],
                [
                    'id' => 2,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                    'title' => 'Urban Edge: The City Explorer',
                    'description' => 'Durable and stylish watches built for the urban adventurer. Perfect for those on the move.',
                    'favourite' => true,
                    'images' => [
                        'https://images.pexels.com/photos/277414/pexels-photo-277414.jpeg',
                        'https://images.pexels.com/photos/935985/pexels-photo-935985.jpeg'
                    ],
                ],
                [
                    'id' => 3,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                    'title' => 'Minimalist Masterpiece: The Zenith Series',
                    'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury.',
                    'favourite' => false,
                    'images' => [
                        'https://images.pexels.com/photos/277452/pexels-photo-277452.jpeg',
                        'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg'
                    ],
                ],
            ];
        }

        // Reorder videos to put the selected product first
        if ($this->selectedProductId) {
            $this->reorderVideos();
        }

        // Set the default active video/product (first in the reordered list)
        $this->activeVideo = $this->videos[0];
    }

    /**
     * Reorder videos to put the selected product first
     */
    private function reorderVideos()
    {
        $selectedIndex = null;
        
        // Find the index of the selected product
        foreach ($this->videos as $index => $video) {
            if ($video['id'] == $this->selectedProductId) {
                $selectedIndex = $index;
                break;
            }
        }
        
        // If found, move it to the beginning
        if ($selectedIndex !== null) {
            $selectedVideo = $this->videos[$selectedIndex];
            unset($this->videos[$selectedIndex]);
            array_unshift($this->videos, $selectedVideo);
            // Re-index the array
            $this->videos = array_values($this->videos);
        }
    }

    /**
     * Toggle between product details and video feed
     *
     * @param int|null $videoId Optional video/product ID to show
     */
    public function toggleVideoDetails($videoId = null)
    {
        $this->productDetails = !$this->productDetails;
    }

    /**
     * Toggle favourite for the current product/video
     */
    public function toggleFavourite($videoId)
    {
        // Find the video index inside $videos
        foreach ($this->videos as $index => $video) {
            if ($video['id'] == $videoId) {
                $this->videos[$index]['favourite'] = !$video['favourite'];

                // Also update activeVideo if it matches
                if ($this->activeVideo && $this->activeVideo['id'] === $videoId) {
                    $this->activeVideo = $this->videos[$index];
                }

                break;
            }
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
        return view('livewire.frontend.product');
    }
}