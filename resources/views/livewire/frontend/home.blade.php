<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="{
    productId: @entangle('productId').live,
    favourite: @entangle('favourite').live,
    currentVideoId: null // New: To track the ID of the video currently in view
}">
    <style>
        .progress-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--progress, 0%),
                    #4a4a4a var(--progress, 0%),
                    #4a4a4a 100%);
        }

        .video-overlay {
            background: linear-gradient(to bottom,
                    transparent 0%,
                    rgba(0, 0, 0, 0.3) 100%);
        }

        .play-button {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }


        @keyframes heart-pop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.4);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Define the keyframes for the animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Apply the animation to the title */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Apply the same animation to the description, but with a delay */
        .animate-fade-in-up-delayed {
            /* Inherit the same animation */
            animation: fadeInUp 0.8s ease-out forwards;
            /* Add a delay to start after the title */
            animation-delay: 0.3s;
            /* Start with 0 opacity to prevent it from flashing before the animation starts */
            opacity: 0;
        }
    </style>

    <div class="relative max-w-lg w-full mx-auto h-fit">
        {{-- It now dynamically updates based on `currentVideoId` --}}
        <div x-data="{ liked: false }" class="absolute top-4 right-8  z-[5000] rounded-full">
            <button @click="liked = !liked"
                :class="liked
                    ?
                    'bg-red-400 text-white' :
                    'bg-white text-black hover:bg-white/70'"
                class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                <flux:icon name="heart" class="w-5 h-5" />
            </button>
        </div>

        <div class="absolute bottom-[100px] z-50 right-8">
            <a wire:navigate href="{{ route('product') }}"
                class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 inline-block">
                <flux:icon name="arrow-right" class="w-5 h-5" />
            </a>
        </div>
        <div class="overflow-y-scroll snap-y snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth"
            x-init="$nextTick(() => {
                // Initialize currentVideoId with the first video's ID on load
                if ($refs.videosContainer.children.length > 0) {
                    currentVideoId = $refs.videosContainer.children[0].querySelector('video').dataset.videoId;
                }
            })" x-ref="scrollContainer">

            {{-- Dynamically populated videos --}}
            @php
                // This array should ideally be passed from the Livewire component.
                // For demonstration, it's hardcoded here.
$videos = [
    [
        'id' => '1', // Ensure IDs are strings for easy keying in Alpine
        'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
        'title' => 'Timeless Elegance: The Chronos Collection',
        'description' =>
            'A curated selection of classic watches that blend modern design with traditional craftsmanship.',
        'favourite' => false,
    ],
    [
        'id' => '2',
        'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
        'title' => 'Urban Edge: The City Explorer',
        'description' =>
            'Durable and stylish watches built for the urban adventurer. Perfect for those on the move.',
        'favourite' => true,
    ],
    [
        'id' => '3',
        'url' =>
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
        'title' => 'Minimalist Masterpiece: The Zenith Series',
        'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury.',
        'favourite' => false,
                    ],
                ];
            @endphp

            <div x-ref="videosContainer"> {{-- Container for scrollspy logic --}}
                @foreach ($videos as $video)
                    <div class="snap-start pb-0.5" x-data="videoController('{{ $video['id'] }}')"
                        x-intersect.once="currentVideoId = '{{ $video['id'] }}'"> {{-- When a video enters viewport, update currentVideoId --}}
                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl relative overflow-hidden shadow-2xl">

                                {{-- Video Element --}}
                                <video x-ref="mainVideo" data-video-id="{{ $video['id'] }}" {{-- Data attribute to easily get video ID --}}
                                    @loadedmetadata="initVideo()" @timeupdate="updateProgress()" @play="handlePlay()"
                                    @pause="handlePause()" @click.stop="togglePlay()" {{-- Use .stop to prevent parent click propagation --}}
                                    @touchend.prevent="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                    class="w-full h-full object-cover" loop playsinline autoplay>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>

                                {{-- Loading Spinner --}}
                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        {{-- Use Tailwind CSS spinner or your preferred loader --}}
                                        <div
                                            class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                        </div>
                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                {{-- Overlay --}}
                                <div class="video-overlay absolute inset-0 pointer-events-none z-0"></div>

                                {{-- Center Play/Pause Button --}}
                                <button @click.stop="togglePlay()"
                                    class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110 z-20"
                                    :style="{ opacity: isPlaying ? '0' : '1' }">
                                    <svg x-show="!isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg x-show="isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>

                                {{-- Product Link Button --}}


                                {{-- Title & Description --}}
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent z-10">
                                    <div class="animation-container">
                                        <h2 class="text-white text-2xl mb-2 leading-tight font-bold animate-fade-in-up">
                                            {{ $video['title'] }}
                                        </h2>
                                        <p class="text-gray-300 mb-2 text-sm animate-fade-in-up-delayed">
                                            {{ $video['description'] }}
                                        </p>
                                    </div>

                                    {{-- Progress Bar & Controls --}}
                                    <div class="relative">
                                        <div class="flex items-center space-x-3">
                                            <button @click.stop="togglePlay()"
                                                class="text-white hover:text-orange-500 transition-colors">
                                                <svg x-show="!isPlaying" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                                <svg x-show="isPlaying" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor" class="text-orange-500">
                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                                </svg>
                                            </button>

                                            <div class="flex-1 relative">
                                                <div class="progress-bar h-1.5 rounded-full cursor-pointer"
                                                    @click="setProgress($event)" x-ref="progressBar"
                                                    :style="{ '--progress': progress + '%' }"></div>
                                                <div class="absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-white rounded-full shadow-lg transition-all duration-200"
                                                    :style="{ left: progress + '%' }"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Alpine.js component for video control
        function videoController(videoId) {
            return {
                isPlaying: false,
                progress: 0,
                duration: 0,
                currentTime: 0,
                videoLoaded: false, // Start as false to show loading state
                videoError: false,
                clickTimeout: null,
                videoId: videoId, // Store the video ID passed from Blade

                initVideo() {
                    const video = this.$refs.mainVideo;
                    this.progress = 0;
                    this.duration = video.duration;
                    this.videoLoaded = true; // Mark as loaded
                    this.videoError = false; // Reset error state
                    this.isPlaying = video.autoplay; // Set initial state based on autoplay attribute
                },

                togglePlay(event) {
                    // Only toggle play if the video is loaded and not an error
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;

                    // Clear any pending double-click/tap delay
                    if (this.clickTimeout) clearTimeout(this.clickTimeout);

                    // Use a small timeout to differentiate single tap from double tap
                    this.clickTimeout = setTimeout(() => {
                        if (video.paused) {
                            video.play();
                        } else {
                            video.pause();
                        }
                    }, 200); // Adjust timeout as needed
                },

                handlePlay() {
                    this.isPlaying = true;
                    // When playing, set the currentVideoId for the favorite button
                    // The `x-intersect.once` directive handles the initial setting
                    // but this ensures it's updated if play is triggered manually
                    this.updateCurrentVideoId();
                },

                handlePause() {
                    this.isPlaying = false;
                },

                updateProgress() {
                    const video = this.$refs.mainVideo;
                    if (!video || !video.duration) return;

                    this.currentTime = video.currentTime;
                    this.progress = (video.currentTime / video.duration) * 100;

                    // Update currentVideoId when time updates, in case of manual seeking
                    this.updateCurrentVideoId();
                },

                setProgress(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const progressBar = this.$refs.progressBar;
                    if (!video || !progressBar) return;

                    const rect = progressBar.getBoundingClientRect();
                    const clientX = event.clientX || (event.touches && event.touches[0].clientX); // Handle touch events

                    if (clientX === undefined) return; // Ensure we have a clientX

                    const pos = (clientX - rect.left) / rect.width;
                    if (video.duration) {
                        video.currentTime = Math.max(0, Math.min(video.duration, pos * video.duration));
                        this.updateProgress(); // Update progress immediately after seeking
                    }
                },

                handleDoubleClick(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const rect = video.getBoundingClientRect();
                    const x = event.clientX - rect.left; // Position relative to video container

                    // Clear the single-click timeout to prevent play/pause on double-click
                    if (this.clickTimeout) clearTimeout(this.clickTimeout);

                    if (x < rect.width / 2) { // Left half of the video
                        video.currentTime = Math.max(0, video.currentTime - 10);
                    } else { // Right half of the video
                        video.currentTime = Math.min(video.duration, video.currentTime + 10);
                    }
                    // Force update progress after seeking
                    this.updateProgress();
                },

                handleTouchEnd(event) {
                    // This is a fallback for touch devices to ensure play/pause works
                    // and to differentiate from potential swipe gestures if you implement them later.
                    if (!this.videoLoaded || this.videoError) return;
                    const currentTime = new Date().getTime();
                    const tapDiff = currentTime - this.lastTap;

                    if (tapDiff < 300) { // Likely a double tap if within 300ms
                        this.handleDoubleClick(event);
                    } else { // Single tap
                        this.togglePlay(); // Use togglePlay for single tap
                    }
                    this.lastTap = currentTime;
                },

                handleVideoError() {
                    this.videoError = true;
                    this.videoLoaded = false;
                    console.error('Video failed to load:', this.videoId);
                    // Potentially try to load a fallback or show an error message
                },

                retryVideo() {
                    this.videoError = false;
                    this.videoLoaded = false;
                    const video = this.$refs.mainVideo;
                    if (video) {
                        video.load();
                    }
                },

                // Helper to update the currentVideoId Alpine.js variable
                updateCurrentVideoId() {
                    // Ensure we are in the context of the correct video element
                    if (this.$el && this.$el.querySelector('[data-video-id]')) {
                        this.currentVideoId = this.$el.querySelector('[data-video-id]').dataset.videoId;
                    }
                }
            }
        }
    </script>
</section>
