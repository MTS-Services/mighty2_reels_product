<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="{ currentPlayingId: null, productId: null, favourite: false, selectedVideoId: null, showInfoPanel: false }">

    <style>
        /* ... (your existing CSS styles remain the same) ... */
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

        @keyframes slideUp {
            from {
                transform: translateY(100vh);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        @keyframes expandInfo {
            from {
                max-height: 0;
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                max-height: 200px;
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes collapseInfo {
            from {
                max-height: 200px;
                opacity: 1;
                transform: translateY(0);
            }

            to {
                max-height: 0;
                opacity: 0;
                transform: translateY(-10px);
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

        .animate-slide-up {
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .animate-slide-down {
            animation: slideDown 0.5s ease-in forwards;
        }

        .animate-expand-info {
            animation: expandInfo 0.4s ease-out forwards;
        }

        .animate-collapse-info {
            animation: collapseInfo 0.3s ease-in forwards;
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
    </style>

    <div class="rounded-lg">
        <div class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="{
            currentPlayingId: null,
            productId: null,
            favourite: false,
            selectedVideoId: null,
            showInfoPanel: false,
            videos: @js($videos),
            activeVideoId: null
        }">

            <div class="relative max-w-lg w-full mx-auto h-full scrollbar-hide rounded-lg">

                <div x-data="{ liked: @entangle('favourite').live }" class="absolute top-4 right-8 z-[5000] rounded-full">
                    <button @click="$wire.toggleFavourite(activeVideoId || (videos[0] && videos[0].id)); liked = !liked"
                        :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                        class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                        :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                        <flux:icon name="heart" class="w-5 h-5" />
                    </button>
                </div>

                <div class="absolute bottom-[100px] z-50 right-8">
                    <button @click="$wire.goToProduct()"
                        class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 inline-block arrow-bounce">
                        <flux:icon name="arrow-right" class="w-5 h-5" />
                    </button>
                </div>

                <div
                    class="video-transform overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth">

                    <template x-for="(video, index) in videos" :key="video.id">
                        <div class="video-item snap-start pb-0.5 rounded-lg" x-data="videoController(video.id, $root, video)"
                            x-intersect:enter="autoplayOnScroll()" x-intersect:leave="pauseVideo()"
                            @click="selectVideo(video.id)">

                            <div class="flex flex-col justify-center items-center relative rounded-xl!">

                                <div
                                    class="video-container w-full relative overflow-hidden shadow-2xl transition-all duration-600 h-[calc(100vh-165px)] max-w-lg rounded-xl">

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

                                    <template x-if="video.file">
                                        <video x-ref="mainVideo" :data-video-id="video.id" @loadedmetadata="initVideo()"
                                            @timeupdate="updateProgress()" @play="handlePlay()" @pause="handlePause()"
                                            @click.stop="togglePlay()" @touchend.prevent="handleTouchEnd($event)"
                                            @dblclick="handleDoubleClick($event)" @volumechange="updateVolumeDisplay()"
                                            class="w-full h-full object-cover" loop playsinline autoplay>
                                            <source :src="video.file" type="video/mp4">
                                        </video>
                                    </template>

                                    <template x-if="!video.file">
                                        <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                            <div class="text-center">
                                                <flux:icon name="film" class="w-16 h-16 text-gray-600 mx-auto mb-4" />
                                                <p class="text-gray-400 text-lg">No video available</p>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="!videoLoaded && video.file"
                                        class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                        <div class="text-center">
                                            <div
                                                class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                            </div>
                                            <p class="text-white mt-4 text-sm">Loading video...</p>
                                        </div>
                                    </div>

                                    <div class="video-overlay absolute inset-0 pointer-events-none z-0"></div>

                                    <button @click="togglePlay()" x-show="video.file"
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
                                        class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent z-10">
                                        <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                                            x-intersect:leave="visible = false">
                                            <h2 x-show="visible" x-transition.opacity.duration.500ms
                                                class="text-white mb-2 leading-tight font-bold animate-fade-in-up text-2xl'">
                                                <span x-text="video.title"></span>
                                            </h2>
                                            <p x-show="visible" x-transition.opacity.duration.700ms
                                                class="text-gray-300 mb-2 text-sm animate-fade-in-up-delayed"
                                                x-text="video.description">
                                            </p>
                                        </div>

                                        <div class="relative" x-show="video.file">
                                            <div class="flex items-center space-x-3">
                                                <button @click="togglePlay()"
                                                    class="text-white hover:text-orange-500 transition-colors">
                                                    <svg x-show="!isPlaying" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                    <svg x-show="isPlaying" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="currentColor"
                                                        class="text-orange-500">
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
            </div>


        </div>
    </div>
    <script>
        function videoController(videoId, root, videoData) {
            return {
                isPlaying: false,
                progress: 0,
                duration: 0,
                currentTime: 0,
                videoLoaded: false,
                videoError: false,
                clickTimeout: null,
                videoId: videoId,
                volume: @entangle('volume').live,
                isMuted: @entangle('isMuted').live,
                lastVolume: 1.0,
                isDraggingVolume: false,
                lastTap: 0,
                videoData: videoData,
                observer: null,

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

                    if (root.currentPlayingId === this.videoId) {
                        video.play();
                        this.isPlaying = true;
                    }

                    this.observer = new IntersectionObserver(this.handleIntersection.bind(this), {
                        root: null,
                        rootMargin: '0px',
                        threshold: 0.7
                    });
                    this.observer.observe(video);
                },

                handleIntersection(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.autoplayOnScroll();
                        } else {
                            this.pauseVideo();
                        }
                    });
                },

                autoplayOnScroll() {
                    const video = this.$refs.mainVideo;
                    if (!video || this.isPlaying) return;

                    // Update the active video ID in the root component
                    root.activeVideoId = this.videoId;

                    // Pause all other videos
                    document.querySelectorAll('video').forEach(v => {
                        if (v !== video) v.pause();
                    });

                    // Play this video
                    video.play().then(() => {
                        this.isPlaying = true;
                        root.currentPlayingId = this.videoId;
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

                togglePlay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    if (video.paused) {
                        video.play();
                        this.isPlaying = true;
                        root.currentPlayingId = this.videoId;
                        root.activeVideoId = this.videoId;
                    } else {
                        video.pause();
                        this.isPlaying = false;
                    }
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
                    $wire.set('isMuted', this.isMuted);
                    $wire.set('volume', this.volume);
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

                selectVideo(videoId) {
                    this.$root.selectedVideoId = videoId;
                    root.activeVideoId = videoId;
                    document.querySelectorAll('video').forEach(v => {
                        if (v.dataset.videoId !== videoId) {
                            v.pause();
                        }
                    });
                    const video = this.$refs.mainVideo;
                    if (video && video.dataset.videoId === videoId) {
                        video.play();
                        this.isPlaying = true;
                        root.currentPlayingId = videoId;
                    }
                },

                handlePlay() {
                    this.isPlaying = true;
                    root.activeVideoId = this.videoId;
                },

                handlePause() {
                    this.isPlaying = false;
                },

                // Volume Drag Functionality
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
                    $wire.set('volume', this.volume);
                    $wire.set('isMuted', this.isMuted);
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