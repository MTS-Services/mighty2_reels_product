<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]
class Home extends Component
{
    // Use public properties for Livewire to manage them
    public $productId = null;
    public $showVideo = true;

    /**
     * @var array<string, bool>
     * Stores the favorite status for each video ID.
     * Example: ['1' => true, '2' => false]
     */
    public $favourite = [];

    /**
     * @var float The current volume level (0.0 to 1.0).
     */
    public $volume = 1.0; // Default to full volume

    /**
     * @var bool Whether the video is currently muted.
     */
    public $isMuted = false; // Default to not muted

    // Initialize the $favourite array with default values from your videos
    public function mount()
    {
        // This should ideally match the default favourite status of your videos
        // If your videos are fetched from a database, this would be done there.
        // For this example, let's assume default is false for all, and true for video '2'.
        $this->favourite = [
            '1' => false,
            '2' => true,
            '3' => false,
            // Add other video IDs here if you have more
        ];
    }

    public function toggleVideoDescription()
    {
        $this->showVideo = !$this->showVideo;
    }

    /**
     * Toggles the favorite status of a given video.
     * @param string|null $videoId The ID of the video to toggle.
     */
    public function toggleFavourite($videoId)
    {
        if ($videoId === null || !array_key_exists($videoId, $this->favourite)) {
            return;
        }

        $this->favourite[$videoId] = !$this->favourite[$videoId];
    }

    /**
     * Toggles the mute state of the video.
     */
    public function toggleMute()
    {
        $this->isMuted = !$this->isMuted;
        // If unmuting, ensure volume is not 0, otherwise set a default
        if (!$this->isMuted && $this->volume == 0) {
            $this->volume = 0.5; // Set to 50% volume when unmuting from mute
        }
    }

    /**
     * Sets the volume level.
     * @param float $level The new volume level (0.0 to 1.0).
     */
    public function setVolume($level)
    {
        // Ensure the level is within bounds
        $this->volume = max(0.0, min(1.0, (float) $level));
        $this->isMuted = ($this->volume === 0);
    }

    public function render()
    {
        $videos = [
            [
                'id' => '1',
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'title' => 'Timeless Elegance: The Chronos Collection',
                'description' => 'A curated selection of classic watches that blend modern design with traditional craftsmanship.',
                'favourite' => $this->favourite['1'] ?? false, // Use the value from the $favourite array
            ],
            [
                'id' => '2',
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'title' => 'Urban Edge: The City Explorer',
                'description' => 'Durable and stylish watches built for the urban adventurer. Perfect for those on the move.',
                'favourite' => $this->favourite['2'] ?? false,
            ],
            [
                'id' => '3',
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'title' => 'Minimalist Masterpiece: The Zenith Series',
                'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury.',
                'favourite' => $this->favourite['3'] ?? false,
            ],
        ];

        return view('livewire.frontend.home', compact('videos'));
    }
}