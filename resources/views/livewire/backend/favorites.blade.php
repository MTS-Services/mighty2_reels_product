<section class="max-w-7xl mx-auto   sm:px-6 lg:px-8" x-data="{ sidebarOpen: false }">
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

            /* Thumbnail preview styles */
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

            .empty-state {
                background: linear-gradient(90deg, #ff6b35 0%, #ff6b35 var(--progress, 0%));
            }
        </style>
    @endpush

    {{-- Sidebar --}}
    <aside class="fixed inset-0 z-[999]" x-show="sidebarOpen" x-cloak
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="transform translate-x-full"
        x-transition:enter-end="transform translate-x-0" x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
        @click.outside="sidebarOpen = false">
        <div class="relative w-full h-full">
            <button class="absolute inset-0 bg-orange-light/20 backdrop-blur-lg"
                x-on:click="sidebarOpen = false"></button>
            <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto flex flex-col">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-start gap-4">
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                        <span class="text-[26px] text-orange-normal capitalize">{{ site_name() }}</span>
                    </a>
                    <x-button x-on:click="sidebarOpen = false" size="sm" variant="secondary">
                        <flux:icon name="x-mark" />
                    </x-button>
                </div>
                <flux:separator variant="subtle" class="my-2" />
                <div class="px-4 flex flex-col items-start gap-4 flex-1">
                    @if (Auth::check())
                        <x-button href="{{ route('profile') }}" wire:navigate
                            class="text-black !py-1 w-full justify-start {{ Route::is('profile') ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                            size="lg" linkText>
                            <flux:icon name="user-circle" /> Profile
                        </x-button>

                        @if (is_admin())
                            <x-button class="text-black !py-1 w-full !justify-start" size="lg"
                                href="{{ route('admin.products') }}" wire:navigate linkText>
                                <flux:icon name="box" /> Dashboard
                            </x-button>
                        @endif

                        <x-button href="{{ route('favorites') }}"
                            class="text-black !px-2 !py-1 w-full justify-start {{ Route::is('favorites') ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                            size="lg" linkText>
                            <flux:icon name="heart" class="fill-orange-normal text-orange-normal" /> Saved
                            Reels
                        </x-button>

                        <flux:spacer />
                        <flux:separator variant="subtle" class="my-2" />
                        <x-button class="w-full mb-3" variant="secondary" size="lg" wire:click="logout">
                            <flux:icon name="power" /> Logout
                        </x-button>
                    @else
                        @if (Route::is('login'))
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="register"
                                wire:navigate>
                                <flux:icon name="user-plus" /> Register
                            </x-button>
                        @elseif (Route::is('register'))
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="login"
                                wire:navigate>
                                <flux:icon name="log-in" /> Login
                            </x-button>
                        @else
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="login"
                                wire:navigate>
                                <flux:icon name="log-in" /> Login
                            </x-button>
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="register"
                                wire:navigate>
                                <flux:icon name="user-plus" /> Register
                            </x-button>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </aside>
    {{-- Sidebar --}}

    <div class="rounded-lg">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (empty($videos))
            <!-- Empty State -->
            <div
                class="empty-state rounded-xl max-w-lg!  mx-auto h-screen sm:h-[calc(100vh-75px)]! mt-2 flex-col items-center p-12 text-center text-white">
                <svg class="w-24 h-24 mx-auto mb-6 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
                <h2 class="text-2xl font-bold mb-4">No Favorites Yet</h2>
                <p class="text-lg mb-8 opacity-90">Start exploring videos and add them to your favorites!</p>
                <a href="{{ route('home') }}"
                    class="bg-white text-orange-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Browse Reels
                </a>
            </div>
        @else
            <!-- Videos Container -->
            <div class="max-w-7xl mx-auto  sm:px-6 lg:px-8" x-data="{
                currentPlayingId: null,
                currentVideoId: @entangle('currentVideoId').live,
                productId: null,
                selectedVideoId: null,
                showInfoPanel: false,
                videos: @js($videos)
            }">

                <div class="relative max-w-lg w-full mx-auto h-full scrollbar-hide sm:rounded-lg">
                    <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                        x-intersect:leave="visible = false">
                        <!-- Remove from Favorites Button -->
                        <div class="absolute top-16 sm:top-4 right-4 sm:right-8 z-5 rounded-full">
                            <button @click="$wire.removeFavorite()" x-show="visible" wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed" wire:target="removeFavorite"
                                class="bg-red-500 text-white transition-colors duration-300 animate-fade-in-up rounded-full p-3 shadow-lg hover:shadow-xl hover:bg-red-600 transform hover:scale-105 disabled:hover:scale-100">

                                <!-- Default Icon -->
                                <svg wire:loading.remove wire:target="removeFavorite" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>

                                <!-- Loading Spinner -->
                                <svg wire:loading wire:target="removeFavorite" class="w-5 h-5 animate-spin"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Product Details Button -->
                    <div class="absolute bottom-[20%] sm:bottom-[100px] z-40 right-4 sm:right-8">
                        <a @click="$wire.goToProduct()"
                            class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 inline-block arrow-bounce">
                            <flux:icon name="arrow-right" class="w-5 h-5" />
                        </a>
                    </div>

                    <div
                        class="video-transform overflow-y-scroll snap-y scrollbar-hide snap-mandatory dvh-screen sm:h-[calc(100vh-82px)]! video-scroll-container pt-0.5 space-y-6 scroll-smooth">

                        <template x-for="(video, index) in videos" :key="video.id">
                            <div class="video-item snap-start pb-0.5 rounded-lg video-item-dvh"
                                x-data="videoController(video.id, $root, video)"
                                x-intersect:enter="autoplayOnScroll(); $wire.updateCurrentVideo(video.id)"
                                x-intersect:leave="pauseVideo()" @click="selectVideo(video.id)">
                                <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                                    x-intersect:leave="visible = false">
                                    {{-- Mobile Floating Action Buttons --}}
                                    <div class="sm:hidden fixed top-4 left-0 right-0 z-40! flex items-center px-4">
                                        <!-- Logo centered -->
                                        <a href="{{ route('home') }}" wire:navigate x-show="visible"
                                            class="absolute left-1/2 transform -translate-x-1/2 bg-[#FFE8D9] rounded-md px-2 inline-flex animate-fade-in-up items-center gap-4">
                                            <span
                                                class="text-[20px] text-orange-normal capitalize">{{ site_name() }}</span>
                                        </a>

                                        <!-- Hamburger button on the right -->
                                        <div class="ml-auto animate-fade-in-up" x-show="visible">
                                            <x-button x-on:click="sidebarOpen = true" size="sm"
                                                variant="secondary" class="lg:hidden">
                                                <flux:icon name="bars-4" />
                                            </x-button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col justify-center items-center relative sm:rounded-xl">

                                    <div
                                        class="video-container w-full relative overflow-hidden shadow-2xl transition-all duration-600 sm:h-[calc(100vh-82px)]! video-container-dvh max-w-lg sm:rounded-2xl">

                                        <!-- Volume Control - ADD THIS SECTION -->
                                        <div class="absolute top-[3%] sm:top-6 left-4 z-40">
                                            <div class="group flex items-center gap-2">
                                                <button @click.stop="toggleMute()"
                                                    class="flex-shrink-0 text-orange-500 hover:text-orange-400 transition-colors">
                                                    <svg x-show="volume > 0.5 && !isMuted" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path
                                                            d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                                                    </svg>
                                                    <svg x-show="volume > 0 && volume <= 0.5 && !isMuted"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        fill="currentColor">
                                                        <path
                                                            d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
                                                    </svg>
                                                    <svg x-show="volume === 0 || isMuted" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path
                                                            d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
                                                    </svg>
                                                </button>

                                                <div class="relative w-0 group-hover:w-[80px] h-4 rounded-full transition-all duration-300 bg-gray-700/50 flex items-center overflow-hidden"
                                                    @click="setTopVolume($event)"
                                                    @mousedown="startTopVolumeDrag($event)" x-ref="topVolumeSlider">
                                                    <div class="absolute top-1/2 -translate-y-1/2 left-1.5 h-1 rounded-full bg-orange-500 transition-all duration-300"
                                                        :style="{ width: (isMuted ? 0 : volume * 100) + '%' }"></div>
                                                    <div class="absolute w-4 h-4 rounded-full bg-white transition-all duration-300"
                                                        :style="{ left: `calc(${(isMuted ? 0 : volume * 100)}% - 0.5rem)` }">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Video Elements -->
                                        <template x-if="video.file">
                                            <div :data-video-id="video.id"
                                                class="w-full h-full flex items-center justify-center">

                                                <!-- Background/Overlay Video (blurred) -->
                                                <video x-ref="backgroundVideo" @loadeddata="initBackgroundVideo()"
                                                    class="absolute w-full h-full object-cover filter blur-sm transform scale-150 z-1"
                                                    loop playsinline muted>
                                                    <source :src="video.file" type="video/mp4">
                                                </video>

                                                <!-- Main/Original Video (clear) -->
                                                <video x-ref="mainVideo" :data-video-id="video.id"
                                                    @loadedmetadata="initVideo()" @loadeddata="generateThumbnails()"
                                                    @timeupdate="updateProgress()" @play="handlePlay()"
                                                    @pause="handlePause()" @click.stop="togglePlay()"
                                                    @touchend.prevent="handleTouchEnd($event)"
                                                    @dblclick="handleDoubleClick($event)"
                                                    @volumechange="updateVolumeDisplay()"
                                                    class="w-full h-full object-contain relative z-2" loop playsinline
                                                    autoplay>
                                                    <source :src="video.file" type="video/mp4">
                                                </video>
                                            </div>
                                        </template>

                                        <template x-if="!video.file">
                                            <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                                <div class="text-center">
                                                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-gray-400 text-lg">No video available</p>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Loading Indicator -->
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

                                        <!-- Play/Pause Button -->
                                        <button @click.stop="togglePlay()" x-show="video.file"
                                            class="play-button absolute z-2 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110"
                                            :style="{ opacity: isPlaying ? '0' : '1' }">
                                            <svg x-show="!isPlaying" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor" class="text-gray-800">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                            <svg x-show="isPlaying" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor" class="text-gray-800">
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                            </svg>
                                        </button>

                                        <!-- Video Info Overlay with Enhanced Progress Bar -->
                                        <div
                                            class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent z-10">
                                            <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                                                x-intersect:leave="visible = false">
                                                <h2 x-show="visible" x-transition.opacity.duration.500ms
                                                    class="text-white mb-2 line-clamp-1 leading-tight font-bold animate-fade-in-up text-2xl">
                                                    <span x-text="video.title"></span>
                                                </h2>
                                                <p x-show="visible" x-transition.opacity.duration.700ms
                                                    class="text-gray-300 mb-4 text-sm line-clamp-1 animate-fade-in-up-delayed"
                                                    x-text="video.description"></p>
                                            </div>

                                            <!-- Enhanced Progress Bar with Thumbnails -->
                                            <div class="relative mb-2" x-show="video.file">
                                                <div class="flex items-start space-x-3">
                                                    <button @click.stop="togglePlay()"
                                                        class="text-white hover:text-orange-500 transition-colors flex-shrink-0">
                                                        <svg x-show="!isPlaying" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M8 5v14l11-7z" />
                                                        </svg>
                                                        <svg x-show="isPlaying" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="currentColor"
                                                            class="text-orange-500">
                                                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                                        </svg>
                                                    </button>

                                                    <div class="flex-1 relative mt-1">
                                                        <!-- Thumbnail Preview -->
                                                        <div class="thumbnail-preview" x-ref="thumbnailPreview"
                                                            x-show="showThumbnail">
                                                            <canvas x-ref="thumbnailCanvas" width="120"
                                                                height="80"></canvas>
                                                            <div class="thumbnail-time"
                                                                x-text="formatTime(thumbnailTime)"></div>
                                                        </div>

                                                        <!-- Progress Container with Buffer -->
                                                        <div class="progress-container" x-ref="progressContainer"
                                                            @mouseenter="handleProgressHover(true)"
                                                            @mouseleave="handleProgressHover(false)"
                                                            @mousemove="handleProgressMouseMove($event)"
                                                            @mousedown="startProgressDrag($event)"
                                                            @click="setProgress($event)">

                                                            <div class="progress-buffer"
                                                                :style="{ width: bufferProgress + '%' }"></div>

                                                            <div class="progress-fill"
                                                                :style="{ width: progress + '%' }">
                                                                <div class="progress-handle"
                                                                    x-show="showProgressHandle">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Time Display -->
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
                </div>

            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            function videoController(videoId, root, videoData) {
                return {
                    // Existing properties
                    isPlaying: false,
                    progress: 0,
                    duration: 0,
                    currentTime: 0,
                    videoLoaded: false,
                    videoError: false,
                    clickTimeout: null,
                    videoId: videoId,
                    volume: root.volume || 1.0,
                    isMuted: root.isMuted || false,
                    lastVolume: 1.0,
                    isDraggingVolume: false,
                    lastTap: 0,
                    videoData: videoData,
                    observer: null,

                    // NEW: Enhanced progress bar and thumbnail properties
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
                        this.videoError = false;
                        this.isPlaying = video.autoplay;
                        this.volume = video.volume;
                        this.isMuted = video.muted;

                        if (this.isMuted) {
                            this.volume = 0;
                        } else {
                            this.lastVolume = this.volume;
                        }

                        if (bgVideo) {
                            this.syncBackgroundVideo();
                        }

                        if (root.currentPlayingId === this.videoId) {
                            this.playBothVideos();
                            this.isPlaying = true;
                        }

                        this.observer = new IntersectionObserver(this.handleIntersection.bind(this), {
                            root: null,
                            rootMargin: '0px',
                            threshold: 0.7
                        });
                        this.observer.observe(video);
                    },

                    initBackgroundVideo() {
                        const bgVideo = this.$refs.backgroundVideo;
                        const mainVideo = this.$refs.mainVideo;

                        if (!bgVideo || !mainVideo) return;

                        bgVideo.muted = true;
                        bgVideo.volume = 0;

                        if (mainVideo.readyState >= 1) {
                            this.syncBackgroundVideo();
                        }
                    },

                    syncBackgroundVideo() {
                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (!video || !bgVideo) return;

                        bgVideo.currentTime = video.currentTime;

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

                        if (!this.isMuted) {
                            video.volume = this.volume;
                            video.muted = false;
                        } else {
                            video.muted = true;
                        }

                        video.play().then(() => {
                            this.isPlaying = true;
                            root.currentPlayingId = this.videoId;

                            if (bgVideo) {
                                bgVideo.currentTime = video.currentTime;
                                bgVideo.muted = true;
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

                    handleIntersection(entries) {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.autoplayOnScroll();
                            } else {
                                this.pauseVideo();
                                this.showThumbnail = false;
                            }
                        });
                    },

                    autoplayOnScroll() {
                        const video = this.$refs.mainVideo;
                        if (!video || this.isPlaying) return;

                        root.currentVideoId = this.videoId;

                        document.querySelectorAll('video').forEach(v => {
                            if (v !== video && !v.classList.contains('filter')) v.pause();
                        });

                        this.playBothVideos();
                    },

                    pauseVideo() {
                        this.pauseBothVideos();
                    },

                    togglePlay() {
                        const video = this.$refs.mainVideo;
                        if (!video) return;

                        if (video.paused) {
                            this.playBothVideos();
                            root.currentPlayingId = this.videoId;
                            root.currentVideoId = this.videoId;
                        } else {
                            this.pauseBothVideos();
                        }
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

                        if (bgVideo && Math.abs(bgVideo.currentTime - video.currentTime) > 0.3) {
                            bgVideo.currentTime = video.currentTime;
                        }
                    },

                    setProgress(event) {
                        if (this.isDraggingProgress) return;

                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;
                        const progressBar = this.$refs.progressContainer;

                        if (!video || !progressBar) return;

                        const wasPlaying = !video.paused;

                        const rect = progressBar.getBoundingClientRect();
                        const clientX = event.clientX || (event.touches && event.touches[0].clientX);
                        if (clientX === undefined) return;

                        const pos = (clientX - rect.left) / rect.width;
                        const newTime = Math.max(0, Math.min(video.duration, pos * video.duration));

                        video.currentTime = newTime;
                        if (bgVideo) {
                            bgVideo.currentTime = newTime;
                        }

                        this.updateProgress();

                        if (wasPlaying && video.paused) {
                            this.playBothVideos();
                        }

                        event.preventDefault();
                        event.stopPropagation();
                    },

                    // NEW: Thumbnail generation
                    generateThumbnails() {
                        if (this.thumbnailsGenerated) return;

                        const video = this.$refs.mainVideo;
                        if (!video || !video.duration) return;

                        this.thumbnailsGenerated = true;
                        this.thumbnails = [];

                        const thumbnailCount = 50;

                        video.addEventListener('progress', () => {
                            if (video.buffered.length > 0) {
                                const buffered = video.buffered.end(video.buffered.length - 1);
                                this.bufferProgress = (buffered / video.duration) * 100;
                            }
                        });

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
                                setTimeout(generateNext, 50);
                            }, {
                                once: true
                            });

                            tempVideo.load();
                        };

                        generateNext();
                    },

                    handleProgressHover(isHovering) {
                        this.showProgressHandle = isHovering || this.isDraggingProgress;

                        if (!isHovering && !this.isDraggingProgress) {
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

                        const containerRect = container.getBoundingClientRect();
                        const previewWidth = 160;
                        let leftPos = x - (previewWidth / 2);

                        leftPos = Math.max(10, Math.min(containerRect.width - previewWidth - 10, leftPos));

                        preview.style.left = leftPos + 'px';
                        preview.style.display = 'block';
                        preview.style.opacity = '1';
                        preview.style.transform = 'translateX(0) translateY(-8px)';
                        this.showThumbnail = true;

                        this.updateThumbnailCanvas(time);
                    },

                    updateThumbnailCanvas(time) {
                        const canvas = this.$refs.thumbnailCanvas;
                        if (!canvas) return;

                        const ctx = canvas.getContext('2d');

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

                        this.generateInstantThumbnail(time, ctx);
                    },

                    generateInstantThumbnail(time, ctx) {
                        const video = this.$refs.mainVideo;
                        if (!video) return;

                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = 160;
                        tempCanvas.height = 90;
                        const tempCtx = tempCanvas.getContext('2d');

                        try {
                            tempCtx.drawImage(video, 0, 0, tempCanvas.width, tempCanvas.height);
                            tempCtx.fillStyle = 'rgba(0, 0, 0, 0.3)';
                            tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                            ctx.drawImage(tempCanvas, 0, 0);
                        } catch (error) {
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

                        const video = this.$refs.mainVideo;
                        this.wasPlayingBeforeProgressDrag = video && !video.paused;

                        this.showThumbnail = true;

                        document.addEventListener('mousemove', this.handleProgressDrag.bind(this));
                        document.addEventListener('mouseup', this.stopProgressDrag.bind(this));

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

                        this.currentTime = newTime;
                        this.progress = pos * 100;
                        this.thumbnailTime = newTime;

                        this.showThumbnailPreview(event.clientX - rect.left, newTime);

                        event.preventDefault();
                        event.stopPropagation();
                    },

                    stopProgressDrag(event) {
                        this.isDraggingProgress = false;

                        const video = this.$refs.mainVideo;
                        const bgVideo = this.$refs.backgroundVideo;

                        if (video) {
                            video.currentTime = this.currentTime;
                            if (bgVideo) {
                                bgVideo.currentTime = this.currentTime;
                            }

                            if (this.wasPlayingBeforeProgressDrag && video.paused) {
                                this.playBothVideos();
                            }
                        }

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

                        document.body.style.userSelect = '';

                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    },

                    formatTime(seconds) {
                        if (!seconds || isNaN(seconds)) return '00:00';

                        const mins = Math.floor(seconds / 60);
                        const secs = Math.floor(seconds % 60);
                        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
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

                        video.currentTime = newTime;
                        if (bgVideo) {
                            bgVideo.currentTime = newTime;
                        }

                        this.updateProgress();

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
                        root.currentVideoId = this.videoId;
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

                    selectVideo(videoId) {
                        root.selectedVideoId = videoId;
                        root.currentVideoId = videoId;
                        document.querySelectorAll('video').forEach(v => {
                            if (v.dataset.videoId !== videoId && !v.classList.contains('filter')) {
                                v.pause();
                            }
                        });
                        const video = this.$refs.mainVideo;
                        if (video && video.dataset.videoId === videoId) {
                            this.playBothVideos();
                            root.currentPlayingId = videoId;
                        }
                    },

                    startTopVolumeDrag(event) {
                        this.isDraggingVolume = true;
                        const video = this.$refs.mainVideo;
                        this.wasPlayingBeforeDrag = video && !video.paused;

                        document.addEventListener('mousemove', this.handleTopVolumeDrag.bind(this));
                        document.addEventListener('mouseup', this.stopTopVolumeDrag.bind(this));

                        event.preventDefault();
                        event.stopPropagation();
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

                        document.body.style.userSelect = '';

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

                        const wasPlaying = !video.paused;

                        this.volume = pos;
                        video.volume = this.volume;
                        video.muted = this.volume === 0;
                        this.isMuted = this.volume === 0;

                        if (this.volume > 0) {
                            this.lastVolume = this.volume;
                        }

                        if (wasPlaying && video.paused) {
                            video.play().catch(() => {});
                        }

                        event.preventDefault();
                        event.stopPropagation();
                    }
                };
            }
        </script>
        <script src="{{ asset('assets/js/responsive.js') }}"></script>
    @endpush

</section>
