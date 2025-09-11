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
            $this->productDetails = !$this->productDetails;

        // if ($videoId) {
        //     $this->activeVideo = collect($this->videos)->firstWhere('id', $videoId);
        //     $this->productDetails = true;
        // } else {
        //     $this->activeVideo = null;
        //     $this->productDetails = false;
        // }
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
     * Render the Blade view
     */
    public function render()
    {
        return view('livewire.frontend.product');
    }
}
