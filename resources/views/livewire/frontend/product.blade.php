<section class="max-w-7xl mx-auto px-4 pt-1 sm:pt-6 sm:px-6 lg:px-8" x-data="videoApp()">
    <style>
        .progress-bar {
            background: linear-gradient(90deg, #ff6b35 0%, #ff6b35 var(--progress, 0%), #4a4a4a var(--progress, 0%), #4a4a4a 100%);
        }

        .volume-bar {
            background: linear-gradient(90deg, #ff6b35 0%, #ff6b35 var(--volume, 100%), #4a4a4a var(--volume, 100%), #4a4a4a 100%);
        }

        .video-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
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

        .video-transform {
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .arrow-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateX(0);
            }

            40% {
                transform: translateX(-5px);
            }

            60% {
                transform: translateX(-3px);
            }
        }

        /* Highlight selected video in horizontal mode */
        .selected-video {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
            border: 2px solid #ff6b35;
        }

        /* Merged view styles */
    </style>

    <div class="relative h-full  max-w-lg mx-auto">

        <!-- Controls for different views -->
        <div x-show="!$wire.showMergedView">
            <!-- Up Arrow Button - Toggle Merged View -->
            <div class="absolute bottom-[100px] z-50 right-8">
                <button @click="$wire.toggleMergedView()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                </button>
            </div>

            <!-- Heart/Favorite Button -->
            <div class="absolute top-4 lg:right-8 right-2 z-[5000] rounded-full">
                <button @click="$wire.toggleFavourite(activeVideoIdInView || (displayVideos[0] && displayVideos[0].id))"
                    :class="getCurrentVideoFavourite() ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="getCurrentVideoFavourite() ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Merged View Controls -->
        <div x-show="$wire.showMergedView">
            <div class="absolute bottom-[63px]! right-4 z-[5000] ">
                <!-- Close Merged View Button -->
                <button @click="$wire.toggleMergedView()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-down" class="w-5 h-5" />
                </button>
            </div>

            <div class="absolute top-4 lg:right-4 right-2 z-[5000] rounded-full">
                <!-- Heart/Favorite Button -->
                <button @click="$wire.toggleFavourite($wire.selectedProductId)"
                    :class="getCurrentVideoFavourite() ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="relative w-full mx-auto h-full  scrollbar-hide"
            :class="$wire.showMergedView ? 'max-w-none' : 'max-w-lg'">

            <!-- Regular Video View -->
            <div x-show="!$wire.showMergedView"
                class="overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-120px)] pt-0.5 space-y-6 scroll-smooth">

                <template x-for="video in displayVideos" :key="video.id + '-' + (video.reel_id || 'default')">
                    <div class="video-item snap-start pb-0.5" x-data="videoController(video.id, $root)"
                        x-intersect:enter="autoplayOnScroll()" x-intersect:leave="pauseVideo()">

                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="w-full rounded-xl overflow-hidden shadow-2xl transition-all duration-600 relative h-[calc(100vh-120px)] max-w-lg">

                                <!-- Volume Control -->
                                <div class="absolute top-4 left-4 z-[5000]">
                                    <div class="group flex items-center gap-2">
                                        <button @click.stop="toggleMute()"
                                            class="flex-shrink-0 text-orange-500 hover:text-orange-400 transition-colors">
                                            <svg x-show="volume > 0.5 && !isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                                            </svg>
                                            <svg x-show="volume > 0 && volume <= 0.5 && !isMuted" width="24"
                                                height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
                                            </svg>
                                            <svg x-show="volume === 0 || isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
                                            </svg>
                                        </button>

                                        <div class="relative w-0 group-hover:w-[80px] h-4 rounded-full transition-all duration-300 bg-gray-700/50 flex items-center overflow-hidden"
                                            @click="setTopVolume($event)" @mousedown="startTopVolumeDrag($event)"
                                            x-ref="topVolumeSlider">
                                            <div class="absolute top-1/2 -translate-y-1/2 left-1.5 h-1 rounded-full bg-orange-500 transition-all duration-300"
                                                :style="{ width: (isMuted ? 0 : volume * 100) + '%' }"></div>
                                            <div class="absolute w-4 h-4 rounded-full bg-white transition-all duration-300"
                                                :style="{ left: `calc(${(isMuted ? 0 : volume * 100)}% - 0.5rem)` }">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Element -->
                                <template x-if="video.url && video.type === 'video'">
                                    <video x-ref="mainVideo" :data-video-id="video.id" :data-reel-id="video.reel_id"
                                        @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                        @play="handlePlay()" @pause="handlePause()" @click.stop="togglePlay()"
                                        @touchend.prevent="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                        @volumechange="updateVolumeDisplay()" class="w-full h-full object-cover" loop
                                        playsinline autoplay muted>
                                        <source :src="video.url" type="video/mp4">
                                    </video>
                                </template>

                                <!-- Image Element -->
                                <template x-if="video.url && video.type === 'image'">
                                    <img :src="video.url" :alt="video.title"
                                        class="w-full h-full object-cover">
                                </template>

                                <template x-if="!video.url">
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        <div class="text-center">
                                            <flux:icon name="film" class="w-16 h-16 text-gray-600 mx-auto mb-4" />
                                            <p class="text-gray-400 text-lg">No media available</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Loading State -->
                                <div x-show="!videoLoaded && video.url && video.type === 'video'"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div
                                            class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                        </div>
                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                <!-- Video Overlay -->
                                <div
                                    class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent to-black/30 z-0">
                                </div>

                                <!-- Play/Pause Button (only for videos) -->
                                <button x-show="video.type === 'video'" @click.stop="togglePlay()"
                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-20 bg-white/90 backdrop-blur-md p-4"
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

                                <!-- Media Info Overlay -->
                                <div
                                    class="absolute bottom-0 left-0 right-0 z-10 p-6 bg-gradient-to-t from-black/70 to-transparent">
                                    <!-- Progress Bar (only for videos) -->
                                    <div x-show="video.type === 'video'" class="relative">
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
                </template>
            </div>

            <!-- Merged View -->
            <div x-show="$wire.showMergedView" x-transition:enter="transition transform ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-95 translate-y-6"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition transform ease-in duration-500"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-6"
                class="h-[calc(100vh-120px)] flex flex-col max-w-lg">
                <div class="flex-1 overflow-y-auto scrollbar-hide space-y-3 gap-4">
                    <template x-for="(media, index) in allMediaItems"
                        :key="media.id + '-' + media.type + '-' + (media.reel_id || media.image_id || index)">
                        <div @click="selectMedia(index)"
                            class="relative rounded-2xl overflow-hidden mb-2  transition-all duration-300 cursor-pointer transform hover:scale-[1.02] active:scale-[0.98]"
                            :class="{
                                'bg-white': selectedMediaIndex ===
                                    index
                            }">

                            <template x-if="media.type === 'video'">
                                <div class="relative w-full h-44">
                                    <video :src="media.url"
                                        class="w-[70%] mx-auto h-full hover:shadow-lg object-cover rounded-2xl" muted
                                        loop autoplay controls></video>

                                    <!-- Play icon in the center -->
                                    {{-- <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="bg-black/60 rounded-full p-4 shadow-lg">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div> --}}

                                </div>
                            </template>


                            <template x-if="media.type === 'image'">
                                <div class="relative w-[70%] mx-auto  hover:shadow-lg h-44">
                                    <img :src="media.url" :alt="media.title"
                                        class="w-full h-full object-cover rounded-2xl">
                                    <div class="absolute top-2 left-2 bg-black/50 rounded-full p-2 shadow">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                                            class="text-white">
                                            <path
                                                d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2s2 .9 2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                        </svg>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="bg-gray-900/95 backdrop-blur-md p-6 border-t border-gray-700 shadow-lg">
                    @if ($productData)
                        <div class="max-w-4xl mx-auto">
                            <h2 class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $productData->title }}
                            </h2>
                            <div x-data="{ expanded: false }" class="space-y-2">
                                <p x-bind:class="expanded ? '' : 'line-clamp-2'"
                                    class="text-gray-300 leading-relaxed mb-1 text-lg transition-all duration-300">
                                    {{ $productData->description }}
                                </p>

                                <!-- Show See more / See less button only if description is long -->
                                @if (strlen($productData->description) > 100)
                                    <button @click="expanded = !expanded"
                                        class="text-orange-400 hover:underline text-sm font-medium">
                                        <span x-text="expanded ? 'See less' : 'See more'"></span>
                                    </button>
                                @endif
                            </div>

                            <!-- Add Tailwind plugin for line-clamp in your tailwind.config.js -->
                            <!-- npm install @tailwindcss/line-clamp -->

                            <div class="flex flex-col mt-2 sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="text-orange-400 font-semibold text-2xl">
                                    @if ($productData->price)
                                        ${{ number_format($productData->price, 2) }}
                                    @endif
                                </div>
                                @if ($productData->type !== null)
                                    <div
                                        class="mt-2 inline-flex items-center gap-2 bg-gray-800 px-4 py-2 rounded-xl text-sm text-gray-300">
                                        <span class="uppercase tracking-wide text-gray-400">Type:</span>
                                        <span class="text-orange-300 font-medium">{{ $productData->type_label }}</span>
                                    </div>
                                @endif
                                <div class="text-gray-400 text-sm">
                                    {{ $productData->city ? $productData->city->name : '' }}
                                </div>
                            </div>


                        </div>
                    @else
                        <div class="text-center text-gray-500 italic">
                            <p>Product details not available</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function videoApp() {
            return {
                currentPlayingId: null,
                activeVideoIdInView: null,
                displayVideos: @js($videos),
                allMediaItems: @js($allMedia),
                selectedMediaIndex: 0,

                init() {
                    this.displayVideos = @js($videos);
                    this.allMediaItems = @js($allMedia);

                    if (this.displayVideos.length > 0) {
                        this.activeVideoIdInView = this.displayVideos[0].id;
                    }
                },

                getCurrentVideoFavourite() {
                    const currentVideo = this.displayVideos.find(v => v.id === this.activeVideoIdInView);
                    return currentVideo ? currentVideo.favourite : false;
                },

                selectMedia(index) {
                    this.selectedMediaIndex = index;
                }
            }
        }

        function videoController(videoId, root) {
            return {
                isPlaying: false,
                progress: 0,
                duration: 0,
                currentTime: 0,
                videoLoaded: false,
                volume: 1.0,
                isMuted: false,
                lastVolume: 1.0,
                isDraggingVolume: false,
                lastTap: 0,

                initVideo() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;

                    this.progress = 0;
                    this.duration = video.duration;
                    this.videoLoaded = true;
                    this.isPlaying = video.autoplay;
                    this.volume = video.volume;
                    this.isMuted = video.muted;

                    if (this.isMuted) {
                        this.volume = 0;
                    } else {
                        this.lastVolume = this.volume;
                    }
                },

                togglePlay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    if (video.paused) {
                        video.play();
                        this.isPlaying = true;
                        root.currentPlayingId = videoId;
                        root.activeVideoIdInView = videoId;
                    } else {
                        video.pause();
                        this.isPlaying = false;
                    }
                },

                autoplayOnScroll() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;

                    root.activeVideoIdInView = videoId;

                    document.querySelectorAll('video').forEach(v => {
                        if (v !== video) v.pause();
                    });

                    video.play().then(() => {
                        this.isPlaying = true;
                        root.currentPlayingId = videoId;
                    }).catch(error => {
                        console.error("Autoplay failed:", error);
                        this.isPlaying = false;
                    });
                },

                pauseVideo() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    video.pause();
                    this.isPlaying = false;
                },

                updateProgress() {
                    const video = this.$refs.mainVideo;
                    if (!video || !video.duration) return;
                    this.currentTime = video.currentTime;
                    if (video.duration > 0) {
                        this.progress = (video.currentTime / video.duration) * 100;
                    } else {
                        this.progress = 0;
                    }
                },

                setProgress(event) {
                    const video = this.$refs.mainVideo;
                    const progressBar = this.$refs.progressBar;
                    if (!video || !progressBar) return;
                    const rect = progressBar.getBoundingClientRect();
                    const clientX = event.clientX || (event.touches && event.touches[0].clientX);
                    if (clientX === undefined) return;
                    const pos = (clientX - rect.left) / rect.width;
                    video.currentTime = Math.max(0, Math.min(video.duration, pos * video.duration));
                    this.updateProgress();
                },

                toggleMute() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
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
                        this.volume = 0;
                    }
                    this.updateVolumeDisplay();
                },

                updateVolumeDisplay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    this.volume = video.volume;
                    this.isMuted = video.muted;
                    if (this.isMuted) {
                        this.volume = 0;
                    }
                },

                handlePlay() {
                    this.isPlaying = true;
                    root.activeVideoIdInView = videoId;
                },

                handlePause() {
                    this.isPlaying = false;
                },

                handleTouchEnd(event) {
                    const currentTime = new Date().getTime();
                    const tapDiff = currentTime - this.lastTap;
                    if (tapDiff < 300) {
                        this.handleDoubleClick(event);
                    } else {
                        this.togglePlay();
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
                    this.updateProgress();
                },

                startTopVolumeDrag(event) {
                    this.isDraggingVolume = true;
                    document.addEventListener('mousemove', this.handleTopVolumeDrag.bind(this));
                    document.addEventListener('mouseup', this.stopTopVolumeDrag.bind(this));
                    event.preventDefault();
                },

                handleTopVolumeDrag(event) {
                    if (!this.isDraggingVolume) return;
                    const slider = this.$refs.topVolumeSlider;
                    if (!slider) return;
                    const rect = slider.getBoundingClientRect();
                    const clientX = event.clientX;
                    const pos = (clientX - rect.left) / rect.width;
                    this.volume = Math.max(0, Math.min(1, pos));
                    this.$refs.mainVideo.volume = this.volume;
                    this.$refs.mainVideo.muted = false;
                    this.isMuted = false;
                    this.lastVolume = this.volume;
                    this.updateVolumeDisplay();
                },

                stopTopVolumeDrag() {
                    this.isDraggingVolume = false;
                    document.removeEventListener('mousemove', this.handleTopVolumeDrag.bind(this));
                    document.removeEventListener('mouseup', this.stopTopVolumeDrag.bind(this));
                },

                setTopVolume(event) {
                    const slider = this.$refs.topVolumeSlider;
                    const video = this.$refs.mainVideo;
                    if (!slider || !video || this.isDraggingVolume) return;

                    const rect = slider.getBoundingClientRect();
                    const clientX = event.clientX;
                    const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));

                    this.volume = pos;
                    video.volume = this.volume;
                    video.muted = this.volume === 0;
                    this.isMuted = this.volume === 0;

                    if (this.volume > 0) {
                        this.lastVolume = this.volume;
                    }
                }
            };
        }
    </script>
</section>
