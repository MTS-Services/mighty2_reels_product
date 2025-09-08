<div class="max-w-7xl mx-auto  pt-1.5">
    <style>
        .progress-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--progress, 0%),
                    #4a4a4a var(--progress, 0%),
                    #4a4a4a 100%);
        }

        .video-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
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
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    @if ($productDetails && $activeVideo)
        <section
            class="overflow-y-scroll scrollbar-hide snap-y snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth">
            <div class="w-full max-w-lg  h-[calc(100vh-165px)]  mx-auto snap-start" x-data="{
                isFavorited: @json($activeVideo['favourite']),
                isExpanded: false,
                currentIndex: 0,
                images: [],
                toggleFavorite() {
                    this.isFavorited = !this.isFavorited;
                    $wire.toggleFavourite();
                },
                toggleDescription() { this.isExpanded = !this.isExpanded },
                prevImage() { this.currentIndex = (this.currentIndex === 0) ? this.images.length - 1 : this.currentIndex - 1 },
                nextImage() { this.currentIndex = (this.currentIndex === this.images.length - 1) ? 0 : this.currentIndex + 1 }
            }"
                x-init="images = JSON.parse(atob('{{ base64_encode(json_encode($activeVideo['images'] ?? [])) }}'))">
                <div class="relative h-[50vh] flex items-center justify-center overflow-hidden rounded-xl border">
                    <img :src="images[currentIndex]" class="w-full h-full object-cover transition-all duration-500"
                        alt="Product Image">

                    {{-- Prev / Next --}}
                    <button @click="prevImage"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

                        </svg>
                    </button>
                    <button @click="nextImage"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />

                        </svg>
                    </button>

                    {{-- Favourite --}}
                    <button @click="toggleFavorite"
                        class="absolute top-4 right-4 bg-white rounded-full p-3 shadow-lg hover:shadow-xl"
                        :class="isFavorited ? 'text-red-500' : 'text-gray-800'">
                        <svg class="w-6 h-6" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />

                        </svg>
                    </button>

                    {{-- Back Button --}}
                    <span @click="$wire.toggleVideoDetails()"
                        class="absolute bottom-4 right-4 bg-orange-500 hover:bg-orange-600 text-white rounded-full p-3 shadow-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </span>
                </div>
                {{-- <div wire:loading wire:target="toggleFavourite,toggleVideoDetails"
                    class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                    <div class="w-16 h-16 border-4 border-t-orange-500 border-gray-200 rounded-full animate-spin"></div>
                </div> --}}
                <div class="py-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $activeVideo['title'] }}</h1>
                    <div class="space-y-4 text-gray-700 leading-relaxed">
                        <p>{{ $activeVideo['description'] }}</p>
                        <div x-show="isExpanded" x-transition>
                            <p>Extended product details here (dummy text)</p>
                        </div>
                        <button @click="toggleDescription" class="text-orange-500 hover:text-orange-600 font-medium"
                            x-text="isExpanded ? 'See less' : 'See more'"></button>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-lg mx-auto snap-start h-[calc(100vh-100px)]" x-data="{
                isFavorited: @json($activeVideo['favourite']),
                isExpanded: false,
                currentIndex: 0,
                images: [],
                toggleFavorite() {
                    this.isFavorited = !this.isFavorited;
                    $wire.toggleFavourite();
                },
                toggleDescription() { this.isExpanded = !this.isExpanded },
                prevImage() { this.currentIndex = (this.currentIndex === 0) ? this.images.length - 1 : this.currentIndex - 1 },
                nextImage() { this.currentIndex = (this.currentIndex === this.images.length - 1) ? 0 : this.currentIndex + 1 }
            }"
                x-init="images = JSON.parse(atob('{{ base64_encode(json_encode($activeVideo['images'] ?? [])) }}'))">
                <div class="relative h-[50vh] flex items-center justify-center overflow-hidden rounded-xl border">
                    <img :src="images[currentIndex]" class="w-full h-full object-cover transition-all duration-500"
                        alt="Product Image">

                    {{-- Prev / Next --}}
                    <button @click="prevImage"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

                        </svg>
                    </button>
                    <button @click="nextImage"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />

                        </svg>
                    </button>

                    {{-- Favourite --}}
                    <button @click="toggleFavorite"
                        class="absolute top-4 right-4 bg-white rounded-full p-3 shadow-lg hover:shadow-xl"
                        :class="isFavorited ? 'text-red-500' : 'text-gray-800'">
                        <svg class="w-6 h-6" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />

                        </svg>
                    </button>

                    {{-- Back Button --}}
                    <span @click="$wire.toggleVideoDetails()"
                        class="absolute bottom-4 right-4 bg-orange-500 hover:bg-orange-600 text-white rounded-full p-3 shadow-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </span>
                </div>

                <div class="py-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $activeVideo['title'] }}</h1>
                    <div class="space-y-4 text-gray-700 leading-relaxed">
                        <p>{{ $activeVideo['description'] }}</p>
                        <div x-show="isExpanded" x-transition>
                            <p>Extended product details here (dummy text)</p>
                        </div>
                        <button @click="toggleDescription" class="text-orange-500 hover:text-orange-600 font-medium"
                            x-text="isExpanded ? 'See less' : 'See more'"></button>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-lg mx-auto snap-start h-[calc(100vh-100px)]" x-data="{
                isFavorited: @json($activeVideo['favourite']),
                isExpanded: false,
                currentIndex: 0,
                images: [],
                toggleFavorite() {
                    this.isFavorited = !this.isFavorited;
                    $wire.toggleFavourite();
                },
                toggleDescription() { this.isExpanded = !this.isExpanded },
                prevImage() { this.currentIndex = (this.currentIndex === 0) ? this.images.length - 1 : this.currentIndex - 1 },
                nextImage() { this.currentIndex = (this.currentIndex === this.images.length - 1) ? 0 : this.currentIndex + 1 }
            }"
                x-init="images = JSON.parse(atob('{{ base64_encode(json_encode($activeVideo['images'] ?? [])) }}'))">
                <div class="relative h-[50vh] flex items-center justify-center overflow-hidden rounded-xl border">
                    <img :src="images[currentIndex]" class="w-full h-full object-cover transition-all duration-500"
                        alt="Product Image">

                    {{-- Prev / Next --}}
                    <button @click="prevImage"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />

                        </svg>
                    </button>
                    <button @click="nextImage"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />

                        </svg>
                    </button>

                    {{-- Favourite --}}
                    <button @click="toggleFavorite"
                        class="absolute top-4 right-4 bg-white rounded-full p-3 shadow-lg hover:shadow-xl"
                        :class="isFavorited ? 'text-red-500' : 'text-gray-800'">
                        <svg class="w-6 h-6" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />

                        </svg>
                    </button>

                    {{-- Back Button --}}
                    <span @click="$wire.toggleVideoDetails()"
                        class="absolute bottom-4 right-4 bg-orange-500 hover:bg-orange-600 text-white rounded-full p-3 shadow-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </span>
                </div>

                <div class="py-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $activeVideo['title'] }}</h1>
                    <div class="space-y-4 text-gray-700 leading-relaxed">
                        <p>{{ $activeVideo['description'] }}</p>
                        <div x-show="isExpanded" x-transition>
                            <p>Extended product details here (dummy text)</p>
                        </div>
                        <button @click="toggleDescription" class="text-orange-500 hover:text-orange-600 font-medium"
                            x-text="isExpanded ? 'See less' : 'See more'"></button>
                    </div>
                </div>
            </div>
        </section>
    @else
        {{-- Video Feed --}}
        <section class="overflow-y-scroll scrollbar-hide snap-y snap-mandatory h-[calc(100vh-165px)]   scroll-smooth">
            @foreach ($videos as $video)
                <div class="snap-start pb-0.5" x-data="videoController()">
                    <div class="flex flex-col justify-center items-center relative">
                        <div
                            class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl overflow-hidden shadow-2xl relative">
                            <video x-ref="mainVideo" @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                @play="handlePlay()" @pause="handlePause()" @click="togglePlay()"
                                @touchend="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                class="w-full h-full object-cover" loop muted playsinline>
                                <source src="{{ $video['url'] }}" type="video/mp4">
                            </video>

                            {{-- Video Loading State --}}
                            <div x-show="!videoLoaded"
                                class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                <div class="text-center">
                                    {{-- Livewire Default Spinner --}}
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

                                    {{-- Custom Video Loading Spinner --}}
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
                                <button wire:click="toggleVideoDetails({{ $video['id'] }})"
                                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg>
                                </button>
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
            @endforeach
        </section>
    @endif
</div>

<script>
    function videoController() {
        return {
            isPlaying: false,
            progress: 0,
            lastTap: 0,
            duration: 0,
            currentTime: 0,
            videoLoaded: false,
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
