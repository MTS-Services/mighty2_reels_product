<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="{
    productId: @entangle('productId').live,
    favourite: @entangle('favourite').live,
    currentVideoId: null,
    showVolumeSlider: false
}">

    <style>
        .progress-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--progress, 0%),
                    #4a4a4a var(--progress, 0%),
                    #4a4a4a 100%);
        }

        /* Volume bar styling similar to progress bar */
        .volume-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--volume, 100%),
                    #4a4a4a var(--volume, 100%),
                    #4a4a4a 100%);
        }

        /* Top-left volume control styling */
        .top-volume-control {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .top-volume-control:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .top-volume-slider {
            width: 60px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            position: relative;
            cursor: pointer;
        }

        .top-volume-progress {
            height: 100%;
            background: white;
            border-radius: 2px;
            transition: width 0.1s ease;
        }

        .top-volume-handle {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            cursor: grab;
            transition: all 0.1s ease;
        }

        .top-volume-handle:hover {
            transform: translate(-50%, -50%) scale(1.2);
        }

        .top-volume-handle:active {
            cursor: grabbing;
            transform: translate(-50%, -50%) scale(1.3);
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
            display: none !important;
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

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-up-delayed {
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }

        .volume-slider {
            transition: all 0.3s ease-in-out;
        }

        .volume-slider.show {
            opacity: 1;
            transform: translateY(0);
        }

        .volume-slider.hide {
            opacity: 0;
            transform: translateY(10px);
        }
    </style>

    <div class="relative max-w-lg w-full mx-auto h-full scrollbar-hide">
        {{-- Heart/Favorite button --}}
        <div x-data="{ liked: false }" class="absolute top-4 right-8 z-[5000] rounded-full">
            <button @click="liked = !liked"
                :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                <flux:icon name="heart" class="w-5 h-5" />
            </button>
        </div>

        {{-- Navigation button --}}
        <div class="absolute bottom-[100px] z-50 right-8">
            <a wire:navigate href="{{ route('product') }}"
                class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 inline-block">
                <flux:icon name="arrow-right" class="w-5 h-5" />
            </a>
        </div>

        <div class="overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth"
            x-init="$nextTick(() => {
                if ($refs.videosContainer.children.length > 0) {
                    currentVideoId = $refs.videosContainer.children[0].querySelector('video').dataset.videoId;
                }
            })" x-ref="scrollContainer">

            @php
                $videos = [
                    [
                        'id' => '1',
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

            <div x-ref="videosContainer">
                @foreach ($videos as $video)
                    <div class="snap-start pb-0.5" x-data="videoController('{{ $video['id'] }}')"
                        x-intersect.enter="
                                currentVideoId = '{{ $video['id'] }}';
                                let container = $el.querySelector('.animation-container');
                                container.classList.remove('animate-fade-in-up', 'animate-fade-in-up-delayed');
                                void container.offsetWidth; // Force reflow to restart animation
                                container.classList.add('animate-fade-in-up', 'animate-fade-in-up-delayed');
                            "
                        x-intersect.leave="
                                let container = $el.querySelector('.animation-container');
                                container.classList.remove('animate-fade-in-up', 'animate-fade-in-up-delayed');
                            ">

                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="video-container w-full h-[calc(100vh-165px)] max-w-lg rounded-xl relative overflow-hidden shadow-2xl">

                                {{-- Top-left Volume Control --}}
                                <div class="absolute top-4 left-4 z-[5000]">
                                    <div class="group flex items-center gap-2">
                                        {{-- Volume Icon Button --}}
                                        <button @click.stop="toggleMute()"
                                            class="flex-shrink-0 text-orange-500 hover:text-orange-400 transition-colors">
                                            {{-- Volume High Icon --}}
                                            <svg x-show="volume > 0.5 && !isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                                            </svg>
                                            {{-- Volume Medium Icon --}}
                                            <svg x-show="volume > 0 && volume <= 0.5 && !isMuted" width="24"
                                                height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
                                            </svg>
                                            {{-- Volume Muted Icon --}}
                                            <svg x-show="volume === 0 || isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
                                            </svg>
                                        </button>

                                        {{-- Volume Slider --}}
                                        <div class="relative w-0 group-hover:w-[80px] h-4 rounded-full transition-all duration-300
                              bg-gray-700/50 flex items-center overflow-hidden"
                                            @click="setTopVolume($event)" @mousedown="startTopVolumeDrag($event)"
                                            x-ref="topVolumeSlider">
                                            <div class="absolute top-1/2  -translate-y-1/2 left-1.5 h-1 rounded-full bg-orange-500 transition-all duration-300"
                                                :style="{ width: (isMuted ? 0 : volume * 100) + '%' }"></div>
                                            <div class="absolute w-4 h-4 rounded-full bg-white transition-all duration-300"
                                                :style="{ left: `calc(${(isMuted ? 0 : volume * 100)}% - 0.5rem)` }">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Video Element --}}
                                <video x-ref="mainVideo" data-video-id="{{ $video['id'] }}"
                                    @loadedmetadata="initVideo()" @timeupdate="updateProgress()" @play="handlePlay()"
                                    @pause="handlePause()" @click.stop="togglePlay()"
                                    @touchend.prevent="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                    @volumechange="updateVolumeDisplay()" class="w-full h-full object-cover" loop
                                    playsinline autoplay>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>

                                {{-- Loading Spinner --}}
                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
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

                                {{-- Title & Description --}}
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent z-10">
                                    <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                                        x-intersect:leave="visible = false">

                                        <h2 x-show="visible" x-transition.opacity.duration.500ms
                                            class="text-white text-2xl mb-2 leading-tight font-bold animate-fade-in-up">
                                            {{ $video['title'] }}
                                        </h2>

                                        <p x-show="visible" x-transition.opacity.duration.700ms
                                            class="text-gray-300 mb-2 text-sm animate-fade-in-up-delayed">
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
        function videoController(videoId) {
            return {
                isPlaying: false,
                progress: 0,
                duration: 0,
                currentTime: 0,
                videoLoaded: false,
                videoError: false,
                clickTimeout: null,
                videoId: videoId,
                volume: 1.0,
                isMuted: false,
                lastVolume: 1.0,
                isDraggingVolume: false,

                initVideo() {
                    const video = this.$refs.mainVideo;
                    this.progress = 0;
                    this.duration = video.duration;
                    this.videoLoaded = true;
                    this.videoError = false;
                    this.isPlaying = video.autoplay;
                    this.volume = video.volume;
                    this.isMuted = video.muted;
                },

                togglePlay(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;

                    if (this.clickTimeout) clearTimeout(this.clickTimeout);

                    this.clickTimeout = setTimeout(() => {
                        if (video.paused) {
                            video.play();
                        } else {
                            video.pause();
                        }
                    }, 200);
                },

                handlePlay() {
                    this.isPlaying = true;
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
                    this.updateCurrentVideoId();
                },

                setProgress(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const progressBar = this.$refs.progressBar;
                    if (!video || !progressBar) return;

                    const rect = progressBar.getBoundingClientRect();
                    const clientX = event.clientX || (event.touches && event.touches[0].clientX);

                    if (clientX === undefined) return;

                    const pos = (clientX - rect.left) / rect.width;
                    if (video.duration) {
                        video.currentTime = Math.max(0, Math.min(video.duration, pos * video.duration));
                        this.updateProgress();
                    }
                },

                // Top volume control methods
                setTopVolume(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const volumeSlider = this.$refs.topVolumeSlider;
                    if (!video || !volumeSlider) return;

                    const rect = volumeSlider.getBoundingClientRect();
                    const clientX = event.clientX || (event.touches && event.touches[0].clientX);

                    if (clientX === undefined) return;

                    const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                    this.volume = pos;
                    video.volume = pos;

                    if (pos > 0 && this.isMuted) {
                        this.isMuted = false;
                        video.muted = false;
                    }

                    if (pos === 0) {
                        this.isMuted = true;
                        video.muted = true;
                    }
                },

                startTopVolumeDrag(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    this.isDraggingVolume = true;

                    const handleDrag = (e) => {
                        if (!this.isDraggingVolume) return;

                        e.preventDefault();
                        this.setTopVolume(e);
                    };

                    const stopDrag = () => {
                        this.isDraggingVolume = false;
                        document.removeEventListener('mousemove', handleDrag);
                        document.removeEventListener('mouseup', stopDrag);
                    };

                    document.addEventListener('mousemove', handleDrag);
                    document.addEventListener('mouseup', stopDrag);

                    // Set initial volume
                    this.setTopVolume(event);
                },

                setVolume(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const volumeBar = this.$refs.volumeBar;
                    if (!video || !volumeBar) return;

                    const rect = volumeBar.getBoundingClientRect();
                    const clientX = event.clientX || (event.touches && event.touches[0].clientX);

                    if (clientX === undefined) return;

                    const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                    this.volume = pos;
                    video.volume = pos;

                    if (pos > 0 && this.isMuted) {
                        this.isMuted = false;
                        video.muted = false;
                    }

                    if (pos === 0) {
                        this.isMuted = true;
                        video.muted = true;
                    }
                },

                toggleMute() {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;

                    if (this.isMuted) {
                        this.isMuted = false;
                        video.muted = false;
                        this.volume = this.lastVolume > 0 ? this.lastVolume : 0.5;
                        video.volume = this.volume;
                    } else {
                        this.lastVolume = this.volume;
                        this.isMuted = true;
                        video.muted = true;
                        video.volume = 0;
                    }
                },

                updateVolumeDisplay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;

                    this.volume = video.volume;
                    this.isMuted = video.muted;
                },

                handleDoubleClick(event) {
                    if (!this.videoLoaded || this.videoError) return;

                    const video = this.$refs.mainVideo;
                    const rect = video.getBoundingClientRect();
                    const x = event.clientX - rect.left;

                    if (this.clickTimeout) clearTimeout(this.clickTimeout);

                    if (x < rect.width / 2) {
                        video.currentTime = Math.max(0, video.currentTime - 10);
                    } else {
                        video.currentTime = Math.min(video.duration, video.currentTime + 10);
                    }
                    this.updateProgress();
                },

                handleTouchEnd(event) {
                    if (!this.videoLoaded || this.videoError) return;
                    const currentTime = new Date().getTime();
                    const tapDiff = currentTime - this.lastTap;

                    if (tapDiff < 300) {
                        this.handleDoubleClick(event);
                    } else {
                        this.togglePlay();
                    }
                    this.lastTap = currentTime;
                },

                handleVideoError() {
                    this.videoError = true;
                    this.videoLoaded = false;
                    console.error('Video failed to load:', this.videoId);
                },

                retryVideo() {
                    this.videoError = false;
                    this.videoLoaded = false;
                    const video = this.$refs.mainVideo;
                    if (video) {
                        video.load();
                    }
                },

                updateCurrentVideoId() {
                    if (this.$el && this.$el.querySelector('[data-video-id]')) {
                        this.currentVideoId = this.$el.querySelector('[data-video-id]').dataset.videoId;
                    }
                }
            }
        }
    </script>

</section>
