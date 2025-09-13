<div class="max-w-7xl mx-auto pt-1.5">
    <style>
        /* Progress Bar Styling */
        .progress-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--progress, 0%),
                    #4a4a4a var(--progress, 0%),
                    #4a4a4a 100%);
        }

        /* Video Overlay */
        .video-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        /* Play Button */
        .play-button {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        /* Scrollbar Hiding */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        /* Animations */
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

        /* Volume Bar */
        .volume-bar {
            background: linear-gradient(90deg,
                    #ff6b35 0%,
                    #ff6b35 var(--volume, 100%),
                    #4a4a4a var(--volume, 100%),
                    #4a4a4a 100%);
        }

        /* Top Volume Control */
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

    <div class="relative max-w-lg w-full mx-auto h-fit scrollbar-hide">
        @if (!$productDetails)
            <div x-data="{ liked: false }" class="absolute top-4 lg:right-4 right-2 z-[5000] rounded-full">
                <button @click="liked = !liked"
                    :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>

            <div class="absolute bottom-15 lg:bottom-[100px] z-50 lg:right-4 right-2 arrow-bounce ">
                <button wire:click="toggleVideoDetails"
                    @click="document.getElementById('details-{{ 0 }}').scrollIntoView({ behavior: 'smooth' }), product_details = !product_details"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600">
                    <flux:icon name="arrow-down" class="w-5 h-5" />
                </button>
            </div>
        @else
            <div x-data="{ liked: false }" class="absolute top-4 right-4 z-[5000] rounded-full">
                <button @click="liked = !liked"
                    :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>

            <div class="absolute top-68 lg:top-99 z-50 right-4 arrow-bounce ">
                <button wire:click="toggleVideoDetails"
                    @click="document.getElementById('details-{{ 0 }}').scrollIntoView({ behavior: 'smooth' }), product_details = !product_details"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                </button>
            </div>
        @endif
        <div class="overflow-y-scroll scrollbar-hide snap-y snap-mandatory h-[calc(100vh-165px)] scroll-smooth"
            x-data="{ product_details: false, currentVideoId: null }">

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
                            </div>

                            <div class="py-6" x-data="{ visible: false }" x-intersect:enter="visible = true"
                                x-intersect:leave="visible = false">

                                <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight"
                                    :class="visible ? 'animate-fade-in-up' : ''">
                                    {{ $video['title'] }}
                                </h1>

                                <div class="space-y-4 text-gray-700 leading-relaxed">
                                    <p :class="visible ? 'animate-fade-in-up-delayed' : ''">{{ $video['description'] }}
                                    </p>

                                    <div x-show="isExpanded" x-transition>
                                        <p>Extended product details here (dummy text)</p>
                                    </div>

                                    <button @click="toggleDescription"
                                        class="text-orange-500 hover:text-orange-600 font-medium"
                                        x-text="isExpanded ? 'See less' : 'See more'">
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    {{-- Video Section --}}
                    <div id="video-{{ $index }}" class="snap-start pb-1" x-data="videoController('{{ $video['id'] }}')"
                        x-intersect:enter="onEnterViewport()" x-intersect:leave="onLeaveViewport()">

                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="video-container scrollbar-hide w-full h-[calc(100vh-165px)] max-w-lg rounded-xl overflow-hidden relative">

                                {{-- Top-left Volume Control --}}
                                <div class="absolute top-4 left-4 z-[5000]">
                                    {{-- Main Volume Control Container with Hover Group --}}
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

                                        {{-- Volume Slider Container --}}
                                        <div class="relative w-0 group-hover:w-[80px] h-4 rounded-full transition-all duration-300 bg-gray-700/50 flex items-center overflow-hidden"
                                            @click="setTopVolume($event)" @mousedown="startTopVolumeDrag($event)"
                                            x-ref="topVolumeSlider">
                                            {{-- Volume Progress --}}
                                            <div class="absolute top-1/2 -translate-y-1/2 left-1.5 h-1 rounded-full bg-orange-500 transition-all duration-300"
                                                :style="{ width: (isMuted ? 0 : volume * 100) + '%' }"></div>
                                            {{-- Volume Handle --}}
                                            <div class="absolute w-4 h-4 rounded-full bg-white transition-all duration-300"
                                                :style="{ left: `calc(${(isMuted ? 0 : volume * 100)}% - 0.5rem)` }">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <video x-ref="mainVideo" @loadedmetadata="initVideo()" @timeupdate="updateProgress()"
                                    @play="handlePlay()" @pause="handlePause()" @click="togglePlay()"
                                    @touchend="handleTouchEnd($event)" @dblclick="handleDoubleClick($event)"
                                    @volumechange="updateVolumeDisplay()" class="w-full h-full object-cover" loop
                                    autoplay playsinline>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>

                                {{-- Loading Indicator --}}
                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-50">
                                    <div class="text-center">
                                        <div wire:loading.delay class="inline-block">
                                            <svg class="animate-spin -ml-1 mr-3 h-12 w-12 text-orange-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
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

                                {{-- Play/Pause Button Overlay --}}
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

                                {{-- Video Info and Progress Bar --}}
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
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
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
   function videoController(videoId) {
    return {
        isPlaying: false,
        progress: 0,
        lastTap: 0,
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

            if (video.duration) {
                this.duration = video.duration;
            } else {
                video.addEventListener('durationchange', () => {
                    this.duration = video.duration;
                });
            }

            this.videoLoaded = true;
            this.videoError = false;
            this.isPlaying = !video.paused; // respect autoplay state
            this.volume = video.volume;
            this.isMuted = video.muted;
        },

        togglePlay() {
            if (!this.videoLoaded || this.videoError) return;
            const video = this.$refs.mainVideo;
            if (this.clickTimeout) clearTimeout(this.clickTimeout);

            this.clickTimeout = setTimeout(() => {
                if (video.paused) {
                    video.play().catch(e => console.error("Error playing video:", e));
                } else {
                    video.pause();
                }
            }, 200);
        },

        handlePlay() { this.isPlaying = true; },
        handlePause() { this.isPlaying = false; },

        updateProgress() {
            const video = this.$refs.mainVideo;
            if (!video || !video.duration || isNaN(video.duration)) return;
            this.currentTime = video.currentTime;
            this.progress = this.duration > 0 ? (video.currentTime / this.duration) * 100 : 0;
        },

        setProgress(event) {
            if (!this.videoLoaded || this.videoError) return;
            const video = this.$refs.mainVideo;
            const progressBar = this.$refs.progressBar;
            if (!video || !progressBar || !video.duration || isNaN(video.duration)) return;

            const rect = progressBar.getBoundingClientRect();
            const clientX = event.clientX || (event.touches?.[0]?.clientX);
            if (clientX === undefined) return;

            const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
            video.currentTime = pos * video.duration;
            this.updateProgress();
        },

        setTopVolume(event) {
            if (!this.videoLoaded || this.videoError) return;
            const video = this.$refs.mainVideo;
            const slider = this.$refs.topVolumeSlider;
            if (!video || !slider) return;

            const rect = slider.getBoundingClientRect();
            const clientX = event.clientX || (event.touches?.[0]?.clientX);
            if (clientX === undefined) return;

            const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
            this.volume = pos;
            video.volume = pos;

            this.isMuted = pos === 0;
            video.muted = pos === 0;
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
                document.removeEventListener('touchmove', handleDrag);
                document.removeEventListener('touchend', stopDrag);
            };

            document.addEventListener('mousemove', handleDrag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', handleDrag, { passive: false });
            document.addEventListener('touchend', stopDrag);

            this.setTopVolume(event);
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
                this.volume = 0;
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
            if (!video || !video.duration || isNaN(video.duration)) return;

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
            const now = new Date().getTime();
            const diff = now - this.lastTap;

            if (diff < 300) {
                this.handleDoubleClick(event);
            } else {
                this.togglePlay();
            }
            this.lastTap = now;
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
            if (video) video.load();
        },

        // --------------------------
        // Scroll-triggered autoplay
        // --------------------------
        onEnterViewport() {
            if (!this.videoLoaded || this.videoError) return;
            const video = this.$refs.mainVideo;
            // Pause all other videos
            document.querySelectorAll('video').forEach(v => {
                if (v !== video) v.pause();
            });
            video.play().catch(e => console.error("Error autoplay:", e));
            this.isPlaying = true;
        },

        onLeaveViewport() {
            if (!this.videoLoaded || this.videoError) return;
            this.$refs.mainVideo.pause();
            this.isPlaying = false;
        }
    }
}

</script>
