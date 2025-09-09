<div class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8">
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
    </style>

    <section
        class="overflow-y-scroll scrollbar-hide snap-y snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth">
        @php
            $videos = [
                [
                    'id' => 1,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                    'title' => 'Timeless Elegance: The Chronos Collection',
                    'description' =>
                        'A curated selection of classic watches that blend modern design with traditional craftsmanship.',
                    'favourite' => false,
                ],
                [
                    'id' => 2,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                    'title' => 'Urban Edge: The City Explorer',
                    'description' =>
                        'Durable and stylish watches built for the urban adventurer. Perfect for those on the move.',
                    'favourite' => true,
                ],
                [
                    'id' => 3,
                    'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                    'title' => 'Minimalist Masterpiece: The Zenith Series',
                    'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury.',
                    'favourite' => false,
                ],
            ];
        @endphp

        @foreach ($videos as $video)
            <div class="snap-start pb-0.5" x-data="videoController()">
                <div class="flex flex-col justify-center items-center">
                    <div
                        class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl overflow-hidden shadow-2xl relative">

                        <!-- Favourite Button -->
                        {{-- <div class="absolute top-4 right-4 z-20">
                            <button @click="$wire.toggleFavourite('{{ $video['id'] }}')"
                                class="transition-all duration-200 bg-white rounded-full p-2 shadow-lg hover:shadow-xl"
                                :class="$wire.favourite['{{ $video['id'] }}'] ? 'text-red-500' : 'text-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24"
                                    :fill="$wire.favourite['{{ $video['id'] }}'] ? 'currentColor' : 'none'"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <!-- Video Wrapper -->
                        <div class="relative overflow-hidden bg-black h-full">
                            <video x-ref="mainVideo" @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                @play="handlePlay()" @pause="handlePause()" class="w-full h-full object-cover" loop
                                playsinline>
                                <source src="{{ $video['url'] }}" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>

                            <!-- Left click area -->
                            <div class="absolute inset-y-0 left-0 w-1/2 -z-1 cursor-cell"
                                @click="$refs.mainVideo.currentTime = Math.max(0, $refs.mainVideo.currentTime - 10)">
                            </div>

                            <!-- Right click area -->
                            <div class="absolute inset-y-0 right-0 w-1/2 -z-1 cursor-pointer"
                                @click="$refs.mainVideo.currentTime = Math.min($refs.mainVideo.duration, $refs.mainVideo.currentTime + 10)">
                            </div>

                            <!-- Overlay -->
                            <div class="video-overlay absolute inset-0 pointer-events-none"></div>

                            <!-- Center Play/Pause Button -->
                            <button @click="togglePlay()"
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

                            <!-- Title & Description -->
                            <div
                                class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h2 class="text-white text-2xl font-bold leading-tight">
                                            {{ $video['title'] }}
                                        </h2>
                                        <a href="{{ route('product') }}"
                                            class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-colors text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12">
                                                </line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </a>
                                    </div>
                                    <p class="text-gray-300 text-sm leading-relaxed">
                                        {{ $video['description'] }}
                                    </p>
                                </div>

                                <!-- Progress Bar -->
                                <div class="relative">
                                    <div class="flex items-center space-x-3">
                                        <button @click="togglePlay()"
                                            class="text-white hover:text-orange-500 transition-colors">
                                            <svg x-show="!isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                            <svg x-show="isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                                fill="currentColor" class="text-orange-500">
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
                        </div> --}}
                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl overflow-hidden relative">
                                <video x-ref="mainVideo" @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                    @play="handlePlay()" @pause="handlePause()" @click="togglePlay()"
                                    @touchend="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                    class="w-full h-full object-cover" loop autoplay playsinline>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                </video>

                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div wire:loading.delay class="inline-block">
                                            <svg class="animate-spin -ml-1 mr-3 h-12 w-12 text-orange-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>

                                        <div wire:loading.remove class="inline-block">
                                            <div class="relative">
                                                <div
                                                    class="w-16 h-16 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                                </div>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-orange-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path d="M8 5v10l8-5-8-5z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                <div class="video-overlay absolute inset-0 pointer-events-none"></div>

                                <button @click="togglePlay()"
                                    class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110"
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

                                <div class="absolute bottom-[100px] z-50 right-6">
                                    <a wire:navigate href="{{ route('product') }}"
                                        class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12">
                                            </line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>

                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                                    <h2 class="text-white text-2xl mb-2 leading-tight font-bold">{{ $video['title'] }}
                                    </h2>
                                    <p class="text-gray-300 mb-2 text-sm">{{ $video['description'] }}</p>
                                    <div class="relative">
                                        <div class="flex items-center space-x-3">
                                            <button @click="togglePlay()"
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
                </div>
            </div>
        @endforeach
    </section>

    {{-- <script>
        function videoController() {
            return {
                isPlaying: false,
                progress: 0,
                lastTap: 0,

                initVideo() {
                    this.progress = 0;
                },

                togglePlay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    if (video.paused) {
                        video.play();
                    } else {
                        video.pause();
                    }
                },

                handlePlay() {
                    this.isPlaying = true;
                },

                handlePause() {
                    this.isPlaying = false;
                },

                updateProgress() {
                    const video = this.$refs.mainVideo;
                    if (!video || !video.duration) return;
                    this.progress = (video.currentTime / video.duration) * 100;
                },

                setProgress(event) {
                    const video = this.$refs.mainVideo;
                    const progressBar = this.$refs.progressBar;
                    if (!video || !progressBar) return;
                    const rect = progressBar.getBoundingClientRect();
                    const pos = (event.clientX - rect.left) / rect.width;
                    if (video.duration) {
                        video.currentTime = pos * video.duration;
                    }
                },

                handleTouchEnd(event) {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    const currentTime = new Date().getTime();
                    const tapLength = currentTime - this.lastTap;

                    if (tapLength < 300 && tapLength > 0) {
                        const rect = video.getBoundingClientRect();
                        const x = event.changedTouches[0].clientX - rect.left;
                        if (x < rect.width / 2) {
                            video.currentTime = Math.max(0, video.currentTime - 10);
                        } else {
                            video.currentTime = Math.min(video.duration, video.currentTime + 10);
                        }
                    }
                    this.lastTap = currentTime;
                },

                handleDoubleClick(event) {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    const rect = video.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    if (x < rect.width / 2) {
                        video.currentTime = Math.max(0, video.currentTime - 10);
                    } else {
                        video.currentTime = Math.min(video.duration, video.currentTime + 10);
                    }
                }
            }
        }
    </script> --}}
    <script>
        function videoController() {
            return {
                isPlaying: false,
                progress: 0,
                lastTap: 0,
                duration: 0,
                currentTime: 0,
                videoLoaded: true,
                videoError: false,

                initVideo() {
                    const video = this.$refs.mainVideo;
                    this.progress = 0;
                    this.duration = video.duration;
                    this.videoLoaded = true;
                    this.videoError = false;
                },

                togglePlay() {
                    if (!this.videoLoaded) return;

                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    video.paused ? video.play() : video.pause();
                },

                handlePlay() {
                    this.isPlaying = true;
                },

                handlePause() {
                    this.isPlaying = false;
                },

                updateProgress() {
                    const video = this.$refs.mainVideo;
                    if (!video || !video.duration) return;
                    this.currentTime = video.currentTime;
                    this.progress = (video.currentTime / video.duration) * 100;
                },

                setProgress(event) {
                    if (!this.videoLoaded) return;

                    const video = this.$refs.mainVideo;
                    const progressBar = this.$refs.progressBar;
                    if (!video || !progressBar) return;
                    const rect = progressBar.getBoundingClientRect();
                    const pos = (event.clientX - rect.left) / rect.width;
                    if (video.duration) video.currentTime = pos * video.duration;
                },

                handleTouchEnd(event) {
                    if (!this.videoLoaded) return;

                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    const currentTime = new Date().getTime();
                    const tapLength = currentTime - this.lastTap;
                    if (tapLength < 300 && tapLength > 0) {
                        const rect = video.getBoundingClientRect();
                        const x = event.changedTouches[0].clientX - rect.left;
                        if (x < rect.width / 2) {
                            video.currentTime = Math.max(0, video.currentTime - 10);
                        } else {
                            video.currentTime = Math.min(video.duration, video.currentTime + 10);
                        }
                    }
                    this.lastTap = currentTime;
                },

                handleDoubleClick(event) {
                    if (!this.videoLoaded) return;

                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    const rect = video.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    if (x < rect.width / 2) {
                        video.currentTime = Math.max(0, video.currentTime - 10);
                    } else {
                        video.currentTime = Math.min(video.duration, video.currentTime + 10);
                    }
                },

                handleVideoError() {
                    this.videoError = true;
                    this.videoLoaded = false;
                    console.error('Video failed to load');
                },

                retryVideo() {
                    this.videoError = false;
                    this.videoLoaded = false;
                    const video = this.$refs.mainVideo;
                    if (video) {
                        video.load(); // Reload the video
                    }
                }
            }
        }
    </script>
</div>
