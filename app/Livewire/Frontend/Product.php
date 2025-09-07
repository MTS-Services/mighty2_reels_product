<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    public $productDetails = true; // Show product details first
    public $favourite = false;
    public $activeVideo = null; // Current selected video/product
    public $videos = [];        // All videos/products

    /**
     * Initialize videos and set the default active video
     */
    public function mount()
    {
        $this->videos = [
            [
                'id' => 1,
                'url' => asset('assets/videos/PinDown.io_@malinmp4.mp4'),
                'title' => 'Timeless Elegance: The Chronos Collection',
                'description' => 'A curated selection of classic watches that blend modern design with traditional craftsmanship.',
                'favourite' => false,
                'images' => [
                    asset('assets/images/frontend/product1.png'),
                    asset('assets/images/frontend/product2.png')
                ],
            ],
            [
                'id' => 2,
                'url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'title' => 'Urban Edge: The City Explorer',
                'description' => 'Durable and stylish watches built for the urban adventurer. Perfect for those on the move.',
                'favourite' => true,
                'images' => [
                    asset('assets/images/frontend/product3.png'),
                    asset('assets/images/frontend/product4.png')
                ],
            ],
            [
                'id' => 3,
                'url' => 'https://assets.mixkit.co/videos/preview/mixkit-young-man-walking-in-a-forest-in-autumn-24535-large.mp4',
                'title' => 'Minimalist Masterpiece: The Zenith Series',
                'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury.',
                'favourite' => false,
                'images' => [
                    asset('assets/images/frontend/product5.png'),
                    asset('assets/images/frontend/product6.png')
                ],
            ],
        ];

        // Set the default active video/product
        $this->activeVideo = $this->videos[0];
    }

    /**
     * Toggle between product details and video feed
     *
     * @param int|null $videoId Optional video/product ID to show
     */
    public function toggleVideoDetails($videoId = null)
    {
        if ($videoId) {
            $this->activeVideo = collect($this->videos)->firstWhere('id', $videoId);
            $this->productDetails = true;
        } else {
            $this->activeVideo = null;
            $this->productDetails = false;
        }
    }

    /**
     * Toggle favourite for the current product/video
     */
    public function toggleFavourite()
    {
        if ($this->activeVideo) {
            $this->activeVideo['favourite'] = !$this->activeVideo['favourite'];
        } else {
            $this->favourite = !$this->favourite;
        }
    }

    /**
     * Render the Blade view
     */
    public function render()
    {
        return view('livewire.frontend.product');
    }
}
