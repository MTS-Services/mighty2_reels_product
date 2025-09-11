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

        .animate-fade-in-up-delayed {
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }
    </style>
    <div class="relative max-w-lg w-full mx-auto h-fit">
        @if (!$productDetails)
            <div x-data="{ liked: false }" class="absolute top-4 lg:right-4 right-2 z-[5000] rounded-full">
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

            <div class="absolute bottom-15 lg:bottom-[100px] z-50 lg:right-4 right-2">
                <button wire:click="toggleVideoDetails"
                    @click="document.getElementById('details-{{ 0 }}').scrollIntoView({ behavior: 'smooth' }), product_details = !product_details"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600">
                    <flux:icon name="arrow-down" class="w-5 h-5" />
                </button>
            </div>
        @else
            <div x-data="{ liked: false }" class="absolute top-4 right-4 z-[5000] rounded-full">
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

            <div class="absolute top-68 lg:top-99 z-50 right-4">
                <button wire:click="toggleVideoDetails"
                    @click="document.getElementById('details-{{ 0 }}').scrollIntoView({ behavior: 'smooth' }), product_details = !product_details"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                </button>
            </div>
        @endif

        {{-- Video Feed --}}
        <div class="overflow-y-scroll scrollbar-hide snap-y snap-mandatory h-[calc(100vh-165px)] scroll-smooth"
            x-data="{ product_details: false }">
            @foreach ($videos as $index => $video)
                {{-- Product Details Section --}}
                @if ($productDetails)
                    <div id="details-{{ $index }}">
                        <div class="w-full max-w-lg h-[calc(100vh-165px)] mx-auto snap-start" x-data="{
                            isFavorited: @json($video['favourite']),
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
                            x-init="images = JSON.parse(atob('{{ base64_encode(json_encode($video['images'] ?? [])) }}'))">
                            <div
                                class="relative h-[50vh] flex items-center justify-center overflow-hidden rounded-xl border">
                                <img :src="images[currentIndex]"
                                    class="w-full h-full object-cover transition-all duration-500" alt="Product Image">

                                <button @click="prevImage"
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                                    <flux:icon name="chevron-left" class="w-5 h-5 text-gray-700" />
                                </button>
                                <button @click="nextImage"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                                    <flux:icon name="chevron-right" class="w-5 h-5 text-gray-700" />
                                </button>

                                {{-- <div x-data="{ liked: false }" class="absolute top-4 right-4 z-[5000] rounded-full">
                                    <button @click="liked = !liked"
                                        :class="liked
                                            ?
                                            'bg-red-400 text-white' :
                                            'bg-white text-black hover:bg-white/70'"
                                        class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                                        :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                                        <flux:icon name="heart" class="w-5 h-5" />
                                    </button>
                                </div> --}}

                                {{-- 
                                <span wire:click="toggleVideoDetails"
                                    @click="document.getElementById('video-{{ $index }}').scrollIntoView({ behavior: 'smooth' }), product_details = !product_details"
                                    class="absolute bottom-4 right-4 bg-orange-500 hover:bg-orange-600 text-white rounded-full p-3 shadow-lg cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 10l7-7m0 0l7 7m-7-7v18">
                                        </path>
                                    </svg>
                                </span> --}}
                            </div>

                            <div class="py-6">
                                <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                                    {{ $video['title'] }}</h1>
                                <div class="space-y-4 text-gray-700 leading-relaxed">
                                    <p>{{ $video['description'] }}</p>
                                    <div x-show="isExpanded" x-transition>
                                        <p>Extended product details here (dummy text)</p>
                                    </div>
                                    <button @click="toggleDescription"
                                        class="text-orange-500 hover:text-orange-600 font-medium"
                                        x-text="isExpanded ? 'See less' : 'See more'"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Video Section --}}
                    <div id="video-{{ $index }}" class="snap-start pb-1" x-data="videoController()">
                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl overflow-hidden relative">
                                <video x-ref="mainVideo" @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                    @play="handlePlay()" @pause="handlePause()" @click="togglePlay()"
                                    @touchend="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                    class="w-full h-full object-cover" loop autoplay playsinline>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                </video>
                                <button wire:click="toggleFavourite({{ $video['id'] }})" wire:loading.attr="disabled"
                                    class="transition-all duration-200 bg-white rounded-full p-2 shadow-lg hover:shadow-xl
                                     {{ $video['favourite'] ? 'text-red-500' : 'text-gray-800' }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24"
                                        fill="{{ $video['favourite'] ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </button>


                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-50">
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

                                        <div wire:loading.remove class="inline-block z-50">
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

                                        <p class="text-white mt-4 text-sm z-50">Loading video...</p>
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


                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                                    <div class="animation-container">
                                        <h2
                                            class="text-white text-2xl mb-2 leading-tight font-bold animate-fade-in-up">
                                            {{ $video['title'] }}
                                        </h2>
                                        <p class="text-gray-300 mb-2 text-sm animate-fade-in-up-delayed">
                                            {{ $video['description'] }}
                                        </p>
                                    </div>
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
                @endif
            @endforeach
        </div>
    </div>
</div>

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
            clickTimeout: null,

            initVideo() {
                const video = this.$refs.mainVideo;
                this.progress = 0;
                this.duration = video.duration;
                this.videoLoaded = true;
                this.videoError = false;
            },

            togglePlay(event) {
                if (!this.videoLoaded) return;
                const video = this.$refs.mainVideo;

                if (this.clickTimeout) clearTimeout(this.clickTimeout);

                this.clickTimeout = setTimeout(() => {
                    if (video.paused) {
                        video.play();
                    } else {
                        video.pause();
                    }
                }, 250);
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

            handleDoubleClick(event) {
                if (!this.videoLoaded) return;
                const video = this.$refs.mainVideo;

                if (this.clickTimeout) clearTimeout(this.clickTimeout);

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
                    video.load();
                }
            }
        }
    }
</script>
