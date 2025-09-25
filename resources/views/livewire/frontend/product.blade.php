<section class=" max-w-7xl mx-auto   sm:pt-6 sm:px-6 lg:px-8" x-data="videoApp()">
    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
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

            /* Thumbnail Preview Styles */
            .thumbnail-preview {
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%) translateY(-8px);
                width: 160px;
                height: 90px;
                background: rgba(0, 0, 0, 0.95);
                border: 2px solid #ff6b35;
                border-radius: 8px;
                overflow: hidden;
                display: none;
                pointer-events: none;
                z-index: 1000;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
                transition: opacity 0.15s ease, transform 0.15s ease;
            }


            .thumbnail-preview canvas {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .thumbnail-time {
                position: absolute;
                bottom: 4px;
                right: 4px;
                background: rgba(0, 0, 0, 0.9);
                color: white;
                font-size: 11px;
                padding: 2px 6px;
                border-radius: 3px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-weight: 500;
                letter-spacing: 0.5px;
            }

            /* Enhanced Progress Bar Styles */
            .progress-container {
                position: relative;
                height: 4px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .progress-container:hover {
                height: 6px;
                background: rgba(255, 255, 255, 0.4);
            }

            .progress-fill {
                height: 100%;
                background: #ff6b35;
                border-radius: 3px;
                transition: width 0.1s linear;
                position: relative;
            }

            .progress-handle {
                position: absolute;
                top: 50%;
                right: -6px;
                transform: translateY(-50%);
                width: 12px;
                height: 12px;
                background: white;
                border: 2px solid #ff6b35;
                border-radius: 50%;
                cursor: grab;
                opacity: 0;
                transition: opacity 0.2s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .progress-container:hover .progress-handle {
                opacity: 1;
            }

            .progress-handle:active {
                cursor: grabbing;
                transform: translateY(-50%) scale(1.2);
            }

            .progress-buffer {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 3px;
                transition: width 0.3s ease;
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
                    transform: translateY(0);
                }

                40% {
                    transform: translateY(-8px);
                }

                60% {
                    transform: translateY(-3px);
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
    @endpush

    <div class=" relative h-full  max-w-lg mx-auto">
        <div x-show="!$wire.showMergedView">
            <div class="absolute bottom-[110px] md:bottom-[70px] z-40 right-4 md:right-8 arrow-bounce">
                <button @click="$wire.toggleMergedView()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                </button>
            </div>

            <div x-data="{ liked: false }" x-init="// When the component is loaded, sync liked state based on product's favourite
            liked = @js($favourite[$productData->id] ?? false);
            
            // Optional: If you have a watcher for activeVideoId, update liked dynamically
            $watch('activeVideoIdInView', value => {
                if (value && displayVideos) {
                    const video = displayVideos.find(v => v.id == value);
                    liked = video ? video.favourite : liked;
                }
            });"
                class="absolute bottom-[160px] sm:top-4 lg:right-8 right-4 z-5 rounded-full">

                <button
                    @click="$wire.toggleFavourite(activeVideoIdInView || (displayVideos[0] && displayVideos[0].id));
                    if (activeVideoIdInView && displayVideos) {
                        const video = displayVideos.find(v => v.id == activeVideoIdInView);
                        if (video) {
                            video.favourite = !video.favourite;
                            liked = video.favourite;
                        }
                    } else {
                        liked = !liked;
                    }"
                    :class="liked ? 'bg-red-400 text-white' :
                        'bg-[#FFE8D9] sm:bg-white sm:border-none border border-orange-600 sm:text-black text-orange-600 hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>

        </div>

        <div x-show="$wire.showMergedView">
            <div class="absolute bottom-24! right-4 z-40 arrow-bounce">
                <button @click="$wire.toggleMergedView()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-down" class="w-5 h-5" />
                </button>
            </div>

            <div class="absolute top-0 right-0   rounded-full">
                <div x-data="{ liked: false }" x-init="// When the component is loaded, sync liked state based on product's favourite
                liked = @js($favourite[$productData->id] ?? false);
                
                // Optional: If you have a watcher for activeVideoId, update liked dynamically
                $watch('activeVideoIdInView', value => {
                    if (value && displayVideos) {
                        const video = displayVideos.find(v => v.id == value);
                        liked = video ? video.favourite : liked;
                    }
                });"
                    class="absolute top-[500px] sm:top-4 lg:right-6 right-4 z-50! rounded-full">

                    <button
                        @click="$wire.toggleFavourite(activeVideoIdInView || (displayVideos[0] && displayVideos[0].id));
                    if (activeVideoIdInView && displayVideos) {
                        const video = displayVideos.find(v => v.id == activeVideoIdInView);
                        if (video) {
                            video.favourite = !video.favourite;
                            liked = video.favourite;
                        }
                    } else {
                        liked = !liked;
                    }"
                        :class="liked ? 'bg-red-400 text-white' :
                            'bg-[#FFE8D9] sm:bg-white sm:border-none border border-orange-600 sm:text-black text-orange-600 hover:bg-white/70'"
                        class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                        :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                        <flux:icon name="heart" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        <div class="relative w-full mx-auto h-full  sm:mt-0 scrollbar-hide"
            :class="$wire.showMergedView ? 'max-w-none' : 'max-w-lg'">
            <div x-show="!$wire.showMergedView"
                class="overflow-y-scroll snap-y scrollbar-hide snap-mandatory  h-[95vh] sm:h-[calc(100vh-120px)]  space-y-6 scroll-smooth">

                <template x-for="(video, index) in (allMediaItems || [])"
                    :key="`media-${video.id}-${video.type}-${video.reel_id || video.image_id || index}-${index}`">
                    <div class="video-item snap-start pb-0.5 video-item-dvh" x-data="videoController(video.id, $root, video)"
                        x-intersect:enter="autoplayOnScroll()" x-intersect:leave="pauseVideo()"
                        :id="`media-item-${index}`">

                        <div class="flex flex-col justify-center items-center relative">
                            <div
                                class="w-full rounded-xl overflow-hidden shadow-2xl transition-all duration-600 relative video-container-dvh sm:h-[calc(100vh-120px)]! max-w-lg max-h-[1100px]">

                                <template x-if="video.type === 'video'">
                                    <div class="absolute top-4 left-4 z-50">
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
                                </template>

                                <!-- Dual Video Template for Videos -->
                                <template x-if="video.url && video.type === 'video'">
                                    <div :data-video-id="video.id" :data-reel-id="video.reel_id"
                                        class="w-full h-full flex items-center justify-center">

                                        <!-- Background/Overlay Video (blurred) -->
                                        <video x-ref="backgroundVideo" @loadeddata="initBackgroundVideo()"
                                            class="absolute w-full h-full object-cover filter blur-sm transform scale-150 z-1"
                                            loop playsinline muted>
                                            <source :src="video.url" type="video/mp4">
                                        </video>

                                        <!-- Main/Original Video (clear) -->
                                        <video x-ref="mainVideo" :data-video-id="video.id"
                                            :data-reel-id="video.reel_id" @loadedmetadata="initVideo()"
                                            @loadeddata="generateThumbnails()" @timeupdate="updateProgress()"
                                            @play="handlePlay()" @pause="handlePause()" @click.stop="togglePlay()"
                                            @touchend.prevent="handleTouchEnd($event)"
                                            @dblclick="handleDoubleClick($event)" @volumechange="updateVolumeDisplay()"
                                            class="w-full h-full object-contain relative z-2" loop playsinline autoplay>
                                            <source :src="video.url" type="video/mp4">
                                        </video>
                                    </div>
                                </template>

                                <template x-if="video.url && video.type === 'image'">
                                    <div :data-image-id="video.image_id || video.id"
                                        class="w-full h-full flex items-center justify-center">

                                        <!-- Background/Overlay Image (blurred) -->
                                        <img :src="video.url" :alt="video.title"
                                            class="absolute w-full h-full object-cover filter blur-sm transform scale-150 z-1">

                                        <!-- Main/Original Image (clear) -->
                                        <img :src="video.url" :alt="video.title"
                                            class="w-full h-full object-contain relative z-2">
                                    </div>
                                </template>

                                <template x-if="!video.url">
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        <div class="text-center">
                                            <flux:icon name="film" class="w-16 h-16 text-gray-600 mx-auto mb-4" />
                                            <p class="text-gray-400 text-lg">No media available</p>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="!videoLoaded && video.url && video.type === 'video'"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div
                                            class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                        </div>
                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                <!-- Video overlay gradient -->
                                <div class="video-overlay absolute inset-0 pointer-events-none z-0"></div>

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

                                <div
                                    class="absolute bottom-0 left-0 right-0 z-10 p-6 bg-gradient-to-t from-black/70 to-transparent">

                                    <div x-show="video.type === 'video'" class="relative mb-2">
                                        <div class="flex items-start space-x-3">
                                            <button @click="togglePlay()"
                                                class="text-white hover:text-orange-500 transition-colors">
                                                <svg x-show="!isPlaying" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                                <svg x-show="isPlaying" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="currentColor" class="text-orange-500">
                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                                </svg>
                                            </button>

                                            <div class="flex-1 relative mt-1">
                                                <div class="thumbnail-preview" x-ref="thumbnailPreview"
                                                    x-show="showThumbnail">
                                                    <canvas x-ref="thumbnailCanvas" width="120"
                                                        height="80"></canvas>
                                                    <div class="thumbnail-time" x-text="formatTime(thumbnailTime)">
                                                    </div>
                                                </div>

                                                <div class="progress-container" x-ref="progressContainer"
                                                    @mouseenter="handleProgressHover(true)"
                                                    @mouseleave="handleProgressHover(false)"
                                                    @mousemove="handleProgressMouseMove($event)"
                                                    @mousedown="startProgressDrag($event)"
                                                    @click="setProgress($event)">

                                                    <div class="progress-buffer"
                                                        :style="{ width: bufferProgress + '%' }"></div>

                                                    <div class="progress-fill" :style="{ width: progress + '%' }">
                                                        <div class="progress-handle" x-show="showProgressHandle">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between text-xs text-gray-300 mt-1">
                                                    <span x-text="formatTime(currentTime)">00:00</span>
                                                    <span x-text="formatTime(duration)">00:00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="$wire.showMergedView" x-transition:enter="transition transform ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-95 translate-y-6"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition transform ease-in duration-500"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-6"
                class="h-[92vh] sm:h-[calc(100vh-120px)] flex flex-col max-w-lg">
                <div class="flex-1 overflow-y-auto scrollbar-hide space-y-3 gap-4">
                    <template x-for="(media, index) in allMediaItems"
                        :key="media.id + '-' + media.type + '-' + (media.reel_id || media.image_id || index)">
                        <div @click="viewMediaItem(index)"
                            class="relative rounded-2xl overflow-hidden mb-2 transition-all duration-300 cursor-pointer transform hover:scale-[1.02] active:scale-[0.98]"
                            :class="{ 'bg-white': selectedMediaIndex === index }">

                            <!-- Video Template -->
                            <template x-if="media.type === 'video'">
                                <div class="relative w-full h-44">
                                    <video :src="media.url"
                                        class="w-[70%] mx-auto h-full object-contain rounded-2xl" muted loop></video>

                                    <!-- Play Button Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="bg-black/40 rounded-full p-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Image Template -->
                            <template x-if="media.type === 'image'">
                                <div class="relative w-[70%] mx-auto hover:shadow-lg h-44">
                                    <img :src="media.url" :alt="media.title"
                                        class="w-full h-full object-contain rounded-2xl">
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

                <div class=" p-4 shadow-sm rounded-2xl  overflow-y-auto" x-data="{ expanded: false, toggleDescription() { this.expanded = !this.expanded } }">

                    @if ($productData)
                        <div class="max-w-3xl mx-auto">

                            <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight animate-fade-in-up  ">
                                {{ $productData->title }}
                            </h2>

                            <div class="flex flex-wrap items-center gap-3 mb-3 animate-fade-in-up-delayed">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                    {{ $productData->room }} {{ $productData->room > 1 ? 'Rooms' : 'Room' }}
                                </span>

                                @if ($productData->city)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                                        {{ $productData->city->name }}
                                    </span>
                                @endif

                                @if ($productData->type !== null)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-medium">
                                        {{ $productData->type_label }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-2xl font-bold text-orange-600 mb-2 animate-fade-in-up-delayed">
                                @if ($productData->price)
                                    ${{ number_format($productData->price, 2) }}
                                @endif
                            </div>

                            <div class="space-y-2 text-gray-700 leading-relaxed animate-fade-in-up-delayed">
                                <p x-bind:class="expanded ? '' : 'line-clamp-1'" class="transition-all duration-300">
                                    {{ $productData->description }}
                                </p>

                                @if (strlen($productData->description) > 100)
                                    <button @click="toggleDescription"
                                        class="text-orange-500 hover:text-orange-600 font-medium text-sm">
                                        <span x-text="expanded ? 'See less' : 'See more'"></span>
                                    </button>
                                @endif
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

    @push('scripts')
        <script>
            function videoApp() {
                return {
                    currentPlayingId: null,
                    activeVideoIdInView: null,
                    displayVideos: @js($videos),
                    allMediaItems: @js($allMedia),
                    selectedMediaIndex: 0,

                    init() {

                        try {
                            this.displayVideos = @js($videos) || [];
                            this.allMediaItems = @js($allMedia) || [];

                            if (!Array.isArray(this.displayVideos)) this.displayVideos = [];
                            if (!Array.isArray(this.allMediaItems)) this.allMediaItems = [];

                            if (this.displayVideos.length > 0) {
                                this.activeVideoIdInView = this.displayVideos[0].id;
                            }
                        } catch (error) {
                            console.error('Error initializing:', error);
                            this.displayVideos = [];
                            this.allMediaItems = [];
                        }

                        // Watch for the scrollToIndex property from Livewire to change
                        this.$watch('$wire.scrollToIndex', (value) => {
                            if (value !== null) {
                                // Use $nextTick to wait for the DOM to be updated
                                this.$nextTick(() => {
                                    const element = document.getElementById(`media-item-${value}`);
                                    if (element) {
                                        // Scroll instantly to the element
                                        element.scrollIntoView({
                                            block: 'start'
                                        });
                                        // Reset the property on the server to prevent re-scrolling
                                        this.$wire.set('scrollToIndex', null);
                                    }
                                });
                            }
                        });
                    },

                    getCurrentVideoFavourite() {
                        const currentVideo = this.displayVideos.find(v => v.id === this.activeVideoIdInView);
                        return currentVideo ? currentVideo.favourite : false;
                    },

                    // This function is called when a media item is clicked in the merged view
                    viewMediaItem(index) {
                        this.selectedMediaIndex = index; // Optional: for instant feedback
                        this.$wire.switchToRegularView(index);
                    }
                }
            }

            function videoController(videoId, root, videoData) {
                return {
                    // Existing properties
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
                    videoData: videoData,

                    // New properties for enhanced progress bar and thumbnails
                    bufferProgress: 0,
                    showProgressHandle: false,
                    isDraggingProgress: false,
                    showThumbnail: false,
                    thumbnailTime: 0,
                    thumbnails: [],
                    thumbnailsGenerated: false,
                    wasPlayingBeforeDrag: false,
                    wasPlayingBeforeProgressDrag: false,

                    initVideo() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

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

                        // Sync background video with main video
                        if (bgVideo) {
                            this.syncBackgroundVideo();
                        }

                        if (root.currentPlayingId === videoId) {
                            this.playBothVideos();
                            this.isPlaying = true;
                        }
                    },

                    initBackgroundVideo() {
                        const bgVideo = this.$refs.backgroundVideo;
                        const mainVideo = this.$refs.mainVideo;

                        if (!bgVideo || !mainVideo) return;

                        // Keep background video muted always
                        bgVideo.muted = true;
                        bgVideo.volume = 0;

                        // Sync with main video if it's loaded
                        if (mainVideo.readyState >= 1) {
                            this.syncBackgroundVideo();
                        }
                    },

                    syncBackgroundVideo() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (!video || !bgVideo) return;

                        // Sync current time
                        bgVideo.currentTime = video.currentTime;

                        // Sync play/pause state
                        if (!video.paused && bgVideo.paused) {
                            bgVideo.play().catch(() => {});
                        } else if (video.paused && !bgVideo.paused) {
                            bgVideo.pause();
                        }
                    },

                    playBothVideos() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (!video) return;

                        // Ensure volume is set correctly before playing
                        if (!this.isMuted) {
                            video.volume = this.volume;
                            video.muted = false;
                        } else {
                            video.muted = true;
                        }

                        video.play().then(() => {
                            this.isPlaying = true;
                            root.currentPlayingId = videoId;

                            // Play background video in sync
                            if (bgVideo) {
                                bgVideo.currentTime = video.currentTime;
                                bgVideo.muted = true; // Always keep background muted
                                bgVideo.play().catch(() => {});
                            }
                        }).catch(error => {
                            console.error("Autoplay failed:", error);
                            this.isPlaying = false;
                        });
                    },

                    pauseBothVideos() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (video) video.pause();
                        if (bgVideo) bgVideo.pause();
                        this.isPlaying = false;
                    },

                    // Enhanced thumbnail generation
                    generateThumbnails() {
                        if (this.thumbnailsGenerated) return;

                        const video = this.$refs.mainVideo;
                        if (!video || !video.duration) return;

                        this.thumbnailsGenerated = true;
                        this.thumbnails = [];

                        // Pre-generate thumbnails at key intervals for smooth preview
                        const thumbnailCount = 50; // Number of thumbnails to generate

                        // Add buffer progress tracking
                        video.addEventListener('progress', () => {
                            if (video.buffered.length > 0) {
                                const buffered = video.buffered.end(video.buffered.length - 1);
                                this.bufferProgress = (buffered / video.duration) * 100;
                            }
                        });

                        // Generate thumbnails after video is fully loaded
                        video.addEventListener('loadeddata', () => {
                            this.preGenerateThumbnails(thumbnailCount);
                        });
                    },

                    preGenerateThumbnails(count) {
                        const video = this.$refs.mainVideo;
                        if (!video || !video.duration) return;

                        const interval = video.duration / count;
                        let index = 0;

                        const generateNext = () => {
                            if (index >= count) return;

                            const time = index * interval;
                            const canvas = document.createElement('canvas');
                            canvas.width = 160;
                            canvas.height = 90;
                            const ctx = canvas.getContext('2d');

                            // Create a temporary video element for thumbnail generation
                            const tempVideo = document.createElement('video');
                            tempVideo.src = video.src;
                            tempVideo.currentTime = time;
                            tempVideo.muted = true;
                            tempVideo.style.display = 'none';
                            document.body.appendChild(tempVideo);

                            tempVideo.addEventListener('seeked', () => {
                                try {
                                    ctx.drawImage(tempVideo, 0, 0, canvas.width, canvas.height);
                                    this.thumbnails[index] = {
                                        time: time,
                                        canvas: canvas,
                                        dataUrl: canvas.toDataURL()
                                    };
                                } catch (error) {
                                    // Fallback thumbnail
                                    ctx.fillStyle = '#4a4a4a';
                                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                                    ctx.fillStyle = '#ff6b35';
                                    ctx.font = '12px Arial';
                                    ctx.textAlign = 'center';
                                    ctx.fillText(this.formatTime(time), canvas.width / 2, canvas.height / 2);

                                    this.thumbnails[index] = {
                                        time: time,
                                        canvas: canvas,
                                        dataUrl: canvas.toDataURL()
                                    };
                                }

                                document.body.removeChild(tempVideo);
                                index++;

                                // Generate next thumbnail with small delay for smooth performance
                                setTimeout(generateNext, 50);
                            }, {
                                once: true
                            });

                            tempVideo.load();
                        };

                        generateNext();
                    },

                    // Enhanced progress bar methods
                    handleProgressHover(isHovering) {
                        this.showProgressHandle = isHovering || this.isDraggingProgress;

                        if (!isHovering && !this.isDraggingProgress) {
                            // Smooth hide animation for thumbnail
                            const preview = this.$refs.thumbnailPreview;
                            if (preview) {
                                preview.style.opacity = '0';
                                preview.style.transform = 'translateX(0) translateY(-4px)';
                                setTimeout(() => {
                                    if (!this.showThumbnail) {
                                        preview.style.display = 'none';
                                    }
                                    this.showThumbnail = false;
                                }, 150);
                            }
                        }
                    },

                    handleProgressMouseMove(event) {
                        if (!this.showProgressHandle && !this.isDraggingProgress) return;

                        const container = this.$refs.progressContainer;
                        const video = this.$refs.mainVideo;
                        if (!container || !video) return;

                        const rect = container.getBoundingClientRect();
                        const pos = (event.clientX - rect.left) / rect.width;
                        const time = Math.max(0, Math.min(video.duration, pos * video.duration));

                        this.thumbnailTime = time;
                        this.showThumbnailPreview(event.clientX - rect.left, time);
                    },

                    showThumbnailPreview(x, time) {
                        const preview = this.$refs.thumbnailPreview;
                        const canvas = this.$refs.thumbnailCanvas;
                        const container = this.$refs.progressContainer;

                        if (!preview || !canvas || !container) return;

                        // Position the preview
                        const containerRect = container.getBoundingClientRect();
                        const previewWidth = 160;
                        let leftPos = x - (previewWidth / 2);

                        // Keep preview within bounds
                        leftPos = Math.max(10, Math.min(containerRect.width - previewWidth - 10, leftPos));

                        preview.style.left = leftPos + 'px';
                        preview.style.display = 'block';
                        preview.style.opacity = '1';
                        preview.style.transform = 'translateX(0) translateY(-8px)';
                        this.showThumbnail = true;

                        // Update thumbnail without affecting video playback
                        this.updateThumbnailCanvas(time);
                    },

                    updateThumbnailCanvas(time) {
                        const canvas = this.$refs.thumbnailCanvas;
                        if (!canvas) return;

                        const ctx = canvas.getContext('2d');

                        // Find the closest pre-generated thumbnail
                        if (this.thumbnails && this.thumbnails.length > 0) {
                            let closestThumbnail = this.thumbnails[0];
                            let minDiff = Math.abs(time - closestThumbnail.time);

                            for (let thumbnail of this.thumbnails) {
                                if (!thumbnail) continue;
                                const diff = Math.abs(time - thumbnail.time);
                                if (diff < minDiff) {
                                    minDiff = diff;
                                    closestThumbnail = thumbnail;
                                }
                            }

                            if (closestThumbnail && closestThumbnail.canvas) {
                                ctx.clearRect(0, 0, canvas.width, canvas.height);
                                ctx.drawImage(closestThumbnail.canvas, 0, 0);
                                return;
                            }
                        }

                        // Fallback: generate thumbnail on the fly (without affecting main video)
                        this.generateInstantThumbnail(time, ctx);
                    },

                    generateInstantThumbnail(time, ctx) {
                        const video = this.$refs.mainVideo;
                        if (!video) return;

                        // Create temporary canvas for thumbnail
                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = 160;
                        tempCanvas.height = 90;
                        const tempCtx = tempCanvas.getContext('2d');

                        // Use video's current frame as base and apply time-based effects
                        try {
                            tempCtx.drawImage(video, 0, 0, tempCanvas.width, tempCanvas.height);

                            // Add time overlay
                            tempCtx.fillStyle = 'rgba(0, 0, 0, 0.3)';
                            tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                            // Copy to main canvas
                            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                            ctx.drawImage(tempCanvas, 0, 0);
                        } catch (error) {
                            // Fallback placeholder
                            ctx.fillStyle = '#4a4a4a';
                            ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                            ctx.fillStyle = '#ff6b35';
                            ctx.font = '14px Arial';
                            ctx.textAlign = 'center';
                            ctx.fillText('Preview', ctx.canvas.width / 2, ctx.canvas.height / 2 - 10);
                            ctx.fillStyle = '#ffffff';
                            ctx.font = '12px Arial';
                            ctx.fillText(this.formatTime(time), ctx.canvas.width / 2, ctx.canvas.height / 2 + 10);
                        }
                    },

                    startProgressDrag(event) {
                        this.isDraggingProgress = true;
                        this.showProgressHandle = true;

                        // Store current play state
                        const video = this.$refs.mainVideo;
                        this.wasPlayingBeforeProgressDrag = video && !video.paused;

                        // Keep thumbnail visible during drag
                        this.showThumbnail = true;

                        document.addEventListener('mousemove', this.handleProgressDrag.bind(this));
                        document.addEventListener('mouseup', this.stopProgressDrag.bind(this));

                        // Prevent text selection while dragging and stop propagation
                        event.preventDefault();
                        event.stopPropagation();
                        document.body.style.userSelect = 'none';
                    },

                    handleProgressDrag(event) {
                        if (!this.isDraggingProgress) return;

                        const container = this.$refs.progressContainer;
                        const video = this.$refs.mainVideo;
                        if (!container || !video) return;

                        const rect = container.getBoundingClientRect();
                        const pos = Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
                        const newTime = pos * video.duration;

                        // Update progress visually without seeking
                        this.currentTime = newTime;
                        this.progress = pos * 100;
                        this.thumbnailTime = newTime;

                        // Update thumbnail preview during drag
                        this.showThumbnailPreview(event.clientX - rect.left, newTime);

                        event.preventDefault();
                        event.stopPropagation();
                    },

                    stopProgressDrag(event) {
                        this.isDraggingProgress = false;

                        // Actually seek the video only when drag stops
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (video) {
                            video.currentTime = this.currentTime;

                            // Sync background video
                            if (bgVideo) {
                                bgVideo.currentTime = this.currentTime;
                            }

                            // Restore play state if it was playing before drag
                            if (this.wasPlayingBeforeProgressDrag && video.paused) {
                                this.playBothVideos();
                            }
                        }

                        // Hide thumbnail after short delay
                        setTimeout(() => {
                            const preview = this.$refs.thumbnailPreview;
                            if (preview && !this.showProgressHandle) {
                                preview.style.opacity = '0';
                                setTimeout(() => {
                                    if (!this.showThumbnail) {
                                        preview.style.display = 'none';
                                    }
                                    this.showThumbnail = false;
                                }, 150);
                            }
                        }, 300);

                        document.removeEventListener('mousemove', this.handleProgressDrag.bind(this));
                        document.removeEventListener('mouseup', this.stopProgressDrag.bind(this));

                        // Re-enable text selection
                        document.body.style.userSelect = '';

                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    },

                    // Utility function to format time
                    formatTime(seconds) {
                        if (!seconds || isNaN(seconds)) return '00:00';

                        const mins = Math.floor(seconds / 60);
                        const secs = Math.floor(seconds % 60);
                        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    },

                    togglePlay() {
                        const video = this.$refs.mainVideo;
                        if (!video) return;

                        if (video.paused) {
                            this.playBothVideos();
                            root.currentPlayingId = videoId;
                            root.activeVideoIdInView = videoId;
                        } else {
                            this.pauseBothVideos();
                        }
                    },

                    autoplayOnScroll() {
                        const video = this.$refs.mainVideo;
                        if (!video) return;

                        root.activeVideoIdInView = videoId;

                        document.querySelectorAll('video').forEach(v => {
                            if (v !== video && !v.classList.contains('filter')) v.pause();
                        });

                        this.playBothVideos();
                    },

                    pauseVideo() {
                        this.pauseBothVideos();
                        this.showThumbnail = false; // Hide thumbnail when video not visible
                    },

                    updateProgress() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (!video || !video.duration || this.isDraggingProgress) return;

                        this.currentTime = video.currentTime;
                        if (video.duration > 0) {
                            this.progress = (video.currentTime / video.duration) * 100;
                        } else {
                            this.progress = 0;
                        }

                        // Keep background video in sync
                        if (bgVideo && Math.abs(bgVideo.currentTime - video.currentTime) > 0.3) {
                            bgVideo.currentTime = video.currentTime;
                        }
                    },

                    setProgress(event) {
                        if (this.isDraggingProgress) return; // Don't interfere with dragging

                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;
                        const progressBar = this.$refs.progressContainer;
                        if (!video || !progressBar) return;

                        // Store play state before seeking
                        const wasPlaying = !video.paused;

                        const rect = progressBar.getBoundingClientRect();
                        const clientX = event.clientX || (event.touches && event.touches[0].clientX);
                        if (clientX === undefined) return;

                        const pos = (clientX - rect.left) / rect.width;
                        const newTime = Math.max(0, Math.min(video.duration, pos * video.duration));

                        // Set time for both videos
                        video.currentTime = newTime;
                        if (bgVideo) {
                            bgVideo.currentTime = newTime;
                        }

                        this.updateProgress();

                        // Restore play state after seeking
                        if (wasPlaying && video.paused) {
                            this.playBothVideos();
                        }

                        event.preventDefault();
                        event.stopPropagation();
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
                        const bgVideo = this.$refs.backgroundVideo;

                        if (!video) return;

                        const rect = video.getBoundingClientRect();
                        const x = event.clientX - rect.left;
                        let newTime;

                        if (x < rect.width / 2) {
                            newTime = Math.max(0, video.currentTime - 10);
                        } else {
                            newTime = Math.min(video.duration, video.currentTime + 10);
                        }

                        // Set new time for both videos
                        video.currentTime = newTime;
                        if (bgVideo) {
                            bgVideo.currentTime = newTime;
                        }

                        this.updateProgress();

                        event.preventDefault();
                        event.stopPropagation();
                    },

                    startTopVolumeDrag(event) {
                        this.isDraggingVolume = true;

                        // Store current play state to restore it later
                        const video = this.$refs.mainVideo;
                        this.wasPlayingBeforeDrag = video && !video.paused;

                        document.addEventListener('mousemove', this.handleTopVolumeDrag.bind(this));
                        document.addEventListener('mouseup', this.stopTopVolumeDrag.bind(this));

                        // Prevent default behavior and stop event propagation
                        event.preventDefault();
                        event.stopPropagation();

                        // Prevent focus changes that could pause the video
                        document.body.style.userSelect = 'none';
                    },

                    handleTopVolumeDrag(event) {
                        if (!this.isDraggingVolume) return;

                        const slider = this.$refs.topVolumeSlider;
                        const video = this.$refs.mainVideo;
                        if (!slider || !video) return;

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

                        // Ensure video continues playing if it was playing before drag
                        if (this.wasPlayingBeforeDrag && video.paused) {
                            video.play().catch(() => {});
                        }

                        event.preventDefault();
                        event.stopPropagation();
                    },

                    stopTopVolumeDrag(event) {
                        this.isDraggingVolume = false;

                        document.removeEventListener('mousemove', this.handleTopVolumeDrag.bind(this));
                        document.removeEventListener('mouseup', this.stopTopVolumeDrag.bind(this));

                        // Re-enable text selection
                        document.body.style.userSelect = '';

                        // Restore play state if needed
                        const video = this.$refs.mainVideo;
                        if (this.wasPlayingBeforeDrag && video && video.paused) {
                            video.play().catch(() => {});
                        }

                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    },

                    setTopVolume(event) {
                        const slider = this.$refs.topVolumeSlider;
                        const video = this.$refs.mainVideo;
                        if (!slider || !video || this.isDraggingVolume) return;

                        const rect = slider.getBoundingClientRect();
                        const clientX = event.clientX;
                        const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));

                        // Store play state before volume change
                        const wasPlaying = !video.paused;

                        this.volume = pos;
                        video.volume = this.volume;
                        video.muted = this.volume === 0;
                        this.isMuted = this.volume === 0;

                        if (this.volume > 0) {
                            this.lastVolume = this.volume;
                        }

                        // Restore play state if it was playing
                        if (wasPlaying && video.paused) {
                            video.play().catch(() => {});
                        }

                        event.preventDefault();
                        event.stopPropagation();
                    },
                };
            }
        </script>
         <script src="{{ asset('assets/js/responsive.js') }}"></script>
    @endpush
</section>
