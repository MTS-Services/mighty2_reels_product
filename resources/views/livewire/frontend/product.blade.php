<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="videoApp()">

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
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(0);
            }
            40% {
                transform: translateX(-5px);
            }
            60% {
                transform: translateX(-3px);
            }
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
    </style>

    <div class="relative h-full max-w-lg mx-auto">
        <!-- Horizontal Mode Controls -->
        <div x-show="isHorizontalMode">
            <div class="absolute bottom-15 right-4 z-50">
                <button @click="toggleHorizontalMode()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow 
                     transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                </button>
            </div>
            <div class="absolute top-4 lg:right-4 right-2 z-[5000] rounded-full">
                <button @click="$wire.toggleFavourite(selectedVideoId || (videos[0] && videos[0].id))"
                    :class="(selectedVideo && selectedVideo.favourite) ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="(selectedVideo && selectedVideo.favourite) ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Main Container -->
        <div class="relative w-full mx-auto h-full scrollbar-hide"
            :class="isHorizontalMode ? 'max-w-none ' : 'max-w-lg'">
            
            <!-- Vertical Mode Controls -->
            <div x-show="!isHorizontalMode">
                <div class="absolute bottom-[100px] z-50 right-8">
                    <button @click="toggleHorizontalMode()"
                        class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                        <flux:icon name="arrow-right" class="w-5 h-5" />
                    </button>
                </div>
                
                <!-- Back Button (only in vertical mode) -->
                <div class="absolute bottom-[180px] z-50 right-8">
                    <button @click="window.history.back()"
                        class="bg-gray-500 text-white p-3 rounded-full shadow hover:bg-gray-600 transition-all duration-300 transform hover:scale-110">
                        <flux:icon name="arrow-left" class="w-5 h-5" />
                    </button>
                </div>

                <div class="absolute top-4 lg:right-8 right-2 z-[5000] rounded-full">
                    <button @click="$wire.toggleFavourite(activeVideoIdInView || (videos[0] && videos[0].id))"
                        :class="getCurrentVideoFavourite() ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                        class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                        :style="getCurrentVideoFavourite() ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                        <flux:icon name="heart" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Video Container -->
            <div :class="isHorizontalMode
                ? 'flex flex-col items-center max-h-[73vh] overflow-y-auto space-y-4 scrollbar-hide mx-auto max-w-7xl snap-y snap-mandatory p-4 scroll-smooth'
                : 'overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth'">

                <template x-for="video in videos" :key="video.id">
                    <div class="video-item"
                        :class="isHorizontalMode ? 'flex-shrink-0 w-[200px]  snap-start cursor-pointer' : 'snap-start pb-0.5'"
                        x-data="videoController(video.id, $root)"
                        x-intersect:enter="!isHorizontalMode && autoplayOnScroll()"
                        x-intersect:leave="!isHorizontalMode && pauseVideo()"
                        @click="isHorizontalMode && selectVideo(video.id)">

                        <div class="flex flex-col justify-center items-center relative"
                            :class="isHorizontalMode ? 'mb-4 ' : ''">

                            <div class="w-full rounded-xl overflow-hidden shadow-2xl transition-all duration-600 relative"
                                :class="isHorizontalMode ? 'h-44 w-[200px]' : 'h-[calc(100vh-165px)] max-w-lg'">

                                <!-- Volume Control (only in vertical mode) -->
                                <div x-show="!isHorizontalMode" class="absolute top-4 left-4 z-[5000]">
                                    <div class="group flex items-center gap-2">
                                        <button @click.stop="toggleMute()"
                                            class="flex-shrink-0 text-orange-500 hover:text-orange-400 transition-colors">
                                            <svg x-show="volume > 0.5 && !isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                                            </svg>
                                            <svg x-show="volume > 0 && volume <= 0.5 && !isMuted" width="24"
                                                height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
                                            </svg>
                                            <svg x-show="volume === 0 || isMuted" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
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

                                <template x-if="video.url">
                                    <video x-ref="mainVideo" :data-video-id="video.id"
                                        @loadedmetadata="initVideo()"
                                        @timeupdate="updateProgress()" 
                                        @play="handlePlay()" 
                                        @pause="handlePause()"
                                        @click.stop="!isHorizontalMode && togglePlay()"
                                        @touchend.prevent="!isHorizontalMode && handleTouchEnd($event)"
                                        @dblclick="!isHorizontalMode && handleDoubleClick($event)"
                                        @volumechange="updateVolumeDisplay()"
                                        class="w-full h-full object-cover" 
                                        loop playsinline autoplay muted>
                                        <source :src="video.url" type="video/mp4">
                                    </video>
                                </template>

                                <template x-if="!video.url">
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        <div class="text-center">
                                            <flux:icon name="film" class="w-16 h-16 text-gray-600 mx-auto mb-4" />
                                            <p class="text-gray-400 text-lg">No video available</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Loading State -->
                                <div x-show="!videoLoaded && video.url"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                <!-- Video Overlay -->
                                <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent to-black/30 z-0"></div>

                                <!-- Play/Pause Button -->
                                <button @click.stop="togglePlay()"
                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-20 bg-white/90 backdrop-blur-md"
                                    :style="{ opacity: (isPlaying && !isHorizontalMode) ? '0' : '1' }"
                                    :class="isHorizontalMode ? 'opacity-100 p-1' : 'p-4'">
                                    <svg x-show="!isPlaying" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-gray-800">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg x-show="isPlaying" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-gray-800">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>

                                <!-- Video Info Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 z-10 p-6 bg-gradient-to-t from-black/70 to-transparent"
                                    :class="isHorizontalMode ? 'p-3' : ''">
                                    <div x-data="{ visible: false }"
                                        x-intersect:enter="visible = true"
                                        x-intersect:leave="visible = false">
                                        <h2 x-show="visible"
                                            x-transition.opacity.duration.500ms
                                            class="text-white mb-2 leading-tight font-bold animate-fade-in-up"
                                            :class="isHorizontalMode ? 'text-sm' : 'text-2xl'" 
                                            x-text="video.title">
                                        </h2>
                                        <p x-show="visible && !isHorizontalMode"
                                            x-transition.opacity.duration.700ms
                                            class="text-gray-300 mb-2 text-sm animate-fade-in-up"
                                            x-text="video.description">
                                        </p>
                                    </div>

                                    <!-- Progress Bar (only in vertical mode) -->
                                    <div x-show="!isHorizontalMode" class="relative">
                                        <div class="flex items-center space-x-3">
                                            <button @click="togglePlay()"
                                                class="text-white hover:text-orange-500 transition-colors">
                                                <svg x-show="!isPlaying" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                                <svg x-show="isPlaying" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500">
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

            <!-- Horizontal Mode Detail Panel -->
            <div x-show="isHorizontalMode && selectedVideoId"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="mt-8 bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 max-w-4xl mx-auto">
                <template x-if="selectedVideo">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-4" x-text="selectedVideo.title"></h3>
                        <p class="text-gray-300 leading-relaxed mb-6" x-text="selectedVideo.description"></p>
                       
                        <!-- Back Button in Horizontal Mode -->
                        <div class="mt-6">
                            <button @click="window.history.back()"
                                class="bg-gray-500 text-white px-6 py-3 rounded-lg shadow hover:bg-gray-600 transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                                <flux:icon name="arrow-left" class="w-5 h-5" />
                                Back to Home
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function videoApp() {
            return {
                currentPlayingId: null,
                favourite: false,
                isHorizontalMode: false,
                selectedVideoId: null,
                activeVideoIdInView: null,
                showInfoPanel: false,
                videos: @js($videos),

                init() {
                    // If we have a selected product ID from the route parameter, set it up
                    if (@js($selectedProductId)) {
                        this.activeVideoIdInView = @js($selectedProductId);
                        // Set the first video as selected initially for horizontal mode
                        if (this.videos.length > 0) {
                            this.selectedVideoId = this.videos[0].id;
                        }
                    }
                },

                toggleHorizontalMode() {
                    if (!this.isHorizontalMode && this.activeVideoIdInView) {
                        this.selectedVideoId = this.activeVideoIdInView;
                    } else {
                        this.selectedVideoId = null;
                    }

                    this.isHorizontalMode = !this.isHorizontalMode;
                    document.querySelectorAll('video').forEach(video => video.pause());
                    this.currentPlayingId = null;
                },

                selectVideo(videoId) {
                    if (this.selectedVideoId === videoId) {
                        const videoEl = document.querySelector(`[data-video-id="${videoId}"]`);
                        if (videoEl) videoEl.pause();
                        this.selectedVideoId = null;
                        this.currentPlayingId = null;
                    } else {
                        this.selectedVideoId = videoId;
                        document.querySelectorAll('video').forEach(v => v.pause());
                        const videoEl = document.querySelector(`[data-video-id="${videoId}"]`);
                        if (videoEl) videoEl.play();
                        this.currentPlayingId = videoId;
                    }
                },

                getCurrentVideoFavourite() {
                    const currentVideo = this.videos.find(v => v.id === this.activeVideoIdInView);
                    return currentVideo ? currentVideo.favourite : false;
                },

                get selectedVideo() {
                    return this.videos.find(v => v.id === this.selectedVideoId);
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
                videoError: false,
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
                    this.videoError = false;
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
