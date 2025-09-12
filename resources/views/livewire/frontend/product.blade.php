<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" 
    x-data="{
        currentPlayingId: null,
        productId: @entangle('productId').live,
        favourite: @entangle('favourite').live,
        isHorizontalMode: false,
        selectedVideoId: null,
        showInfoPanel: false,

        toggleHorizontalMode() {
            this.isHorizontalMode = !this.isHorizontalMode;
            this.selectedVideoId = null;
            
            // Pause all videos when switching modes
            document.querySelectorAll('video').forEach(video => {
                video.pause();
            });
            
            // Reset current playing state
            this.currentPlayingId = null;
        },

        selectVideo(videoId) {
            this.selectedVideoId = this.selectedVideoId === videoId ? null : videoId;
        }
    }">

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
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
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

        .horizontal-mode .video-container {
            width: 200px !important;
            height: 300px !important;
        }

        .info-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
        }

        .info-panel.expanded {
            max-height: 300px;
            opacity: 1;
        }

        .arrow-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateX(0); }
            40% { transform: translateX(-5px); }
            60% { transform: translateX(-3px); }
        }
    </style>

    <div>
        <div class="relative max-w-lg w-full mx-auto h-full scrollbar-hide"
            :class="isHorizontalMode ? 'max-w-none' : ''">

            <!-- Heart/Favorite button -->
            <div x-data="{ liked: false }" 
                 class="absolute top-4 right-8 z-[5000] rounded-full"
                 x-show="!isHorizontalMode">
                <button @click="liked = !liked"
                    :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    <flux:icon name="heart" class="w-5 h-5" />
                </button>
            </div>

            <!-- Navigation button -->
            <div class="absolute bottom-[100px] z-50 right-8" x-show="!isHorizontalMode">
                <button @click="toggleHorizontalMode()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110 arrow-bounce">
                    <flux:icon name="arrow-right" class="w-5 h-5" />
                </button>
            </div>

            <!-- Back button for horizontal mode -->
            <div x-show="isHorizontalMode" class="absolute top-4 left-4 z-50">
                <button @click="toggleHorizontalMode()"
                    class="bg-white/90 text-gray-800 p-3 rounded-full shadow hover:bg-white transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-left" class="w-5 h-5" />
                </button>
            </div>

            <!-- Horizontal mode header -->
            <div x-show="isHorizontalMode" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0" 
                 class="mb-6 pt-16">
                <h1 class="text-black text-3xl font-bold mb-2">Watch Collection</h1>
                <p class="text-gray-600">Discover our premium timepieces</p>
            </div>

            <!-- Videos Container -->
            <div class="video-transform"
                :class="isHorizontalMode ? 'flex flex-wrap gap-6 justify-center pt-4' :
                    'overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth'">

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

                @foreach ($videos as $video)
                    <div class="video-item" 
                         :class="isHorizontalMode ? 'w-64 cursor-pointer' : 'snap-start pb-0.5'"
                         x-data="videoController('{{ $video['id'] }}', $root, {{ json_encode($video) }})" 
                         x-intersect:enter="!isHorizontalMode && autoplayOnScroll()"
                         x-intersect:leave="!isHorizontalMode && pauseVideo()"
                         @click="isHorizontalMode && selectVideo('{{ $video['id'] }}')">

                        <div class="flex flex-col justify-center items-center relative"
                            :class="isHorizontalMode ? 'mb-4' : ''">

                            <div class="video-container w-full relative overflow-hidden shadow-2xl transition-all duration-600"
                                :class="isHorizontalMode ? 'h-80 w-64 rounded-xl' :
                                    'h-[calc(100vh-165px)] max-w-lg rounded-xl'">

                                <!-- Top-left Volume Control (hidden in horizontal mode) -->
                                <div class="absolute top-4 left-4 z-[5000]"
                                    x-show="!isHorizontalMode">
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

                                <!-- Video -->
                                <video x-ref="mainVideo" data-video-id="{{ $video['id'] }}"
                                    @loadedmetadata="initVideo()" @timeupdate="updateProgress()" @play="handlePlay()"
                                    @pause="handlePause()" @click.stop="!isHorizontalMode && togglePlay()"
                                    @touchend.prevent="!isHorizontalMode && handleTouchEnd($event)" 
                                    @dblclick="!isHorizontalMode && handleDoubleClick($event)"
                                    @volumechange="updateVolumeDisplay()" 
                                    class="w-full h-full object-cover" loop playsinline autoplay muted>
                                    <source src="{{ $video['url'] }}" type="video/mp4">
                                </video>

                                <!-- Loading -->
                                <div x-show="!videoLoaded"
                                    class="absolute inset-0 bg-gray-900 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div
                                            class="inline-block w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin">
                                        </div>
                                        <p class="text-white mt-4 text-sm">Loading video...</p>
                                    </div>
                                </div>

                                <!-- Overlay -->
                                <div class="video-overlay absolute inset-0 pointer-events-none z-0"></div>

                                <!-- Center Play/Pause Button (hidden in horizontal mode) -->
                                <button @click.stop="togglePlay()"
                                    class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110 z-20"
                                    :style="{ opacity: (isPlaying && !isHorizontalMode) ? '0' : '1' }"
                                    :class="isHorizontalMode ? 'pointer-events-none' : ''">
                                    <svg x-show="!isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg x-show="isPlaying" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>

                                <!-- Title & Description (modified for horizontal mode) -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent z-10"
                                    :class="isHorizontalMode ? 'p-3' : ''">
                                    <div x-data="{ visible: false }" x-intersect:enter="visible = true"
                                        x-intersect:leave="visible = false">
                                        <h2 x-show="visible" x-transition.opacity.duration.500ms
                                            class="text-white mb-2 leading-tight font-bold animate-fade-in-up"
                                            :class="isHorizontalMode ? 'text-sm' : 'text-2xl'">
                                            {{ $video['title'] }}
                                        </h2>
                                        <p x-show="visible && !isHorizontalMode"
                                            x-transition.opacity.duration.700ms
                                            class="text-gray-300 mb-2 text-sm animate-fade-in-up-delayed">
                                            {{ $video['description'] }}
                                        </p>
                                    </div>

                                    <!-- Progress Bar (hidden in horizontal mode) -->
                                    <div class="relative" x-show="!isHorizontalMode">
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
                @endforeach
            </div>

            <!-- Information Panel (for horizontal mode) -->
            <div x-show="isHorizontalMode && selectedVideoId"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="mt-8 bg-gray-900/50 backdrop-blur-sm rounded-xl p-6">
                @php
                    $detailedVideos = [
                        '1' => [
                            'id' => '1',
                            'title' => 'Timeless Elegance: The Chronos Collection',
                            'description' => 'A curated selection of classic watches that blend modern design with traditional craftsmanship. Each timepiece in this collection represents decades of horological expertise, featuring Swiss movements, sapphire crystal displays, and premium leather straps. Perfect for the discerning collector who appreciates both heritage and innovation.',
                            'price' => '$1,299',
                            'features' => ['Swiss Movement', 'Sapphire Crystal', 'Water Resistant', 'Premium Leather Strap']
                        ],
                        '2' => [
                            'id' => '2',
                            'title' => 'Urban Edge: The City Explorer',
                            'description' => 'Durable and stylish watches built for the urban adventurer. Perfect for those on the move. These robust timepieces combine functionality with metropolitan style, featuring shock-resistant cases, luminous dials for low-light visibility, and versatile designs that transition seamlessly from boardroom to weekend adventures.',
                            'price' => '$899',
                            'features' => ['Shock Resistant', 'Luminous Dial', 'Multi-Function', 'Steel Case']
                        ],
                        '3' => [
                            'id' => '3',
                            'title' => 'Minimalist Masterpiece: The Zenith Series',
                            'description' => 'Sleek, simple, and sophisticated. These watches redefine understated luxury. The Zenith Series embodies the philosophy that true elegance lies in simplicity. With clean lines, refined proportions, and carefully selected materials, these timepieces make a subtle yet powerful statement.',
                            'price' => '$2,199',
                            'features' => ['Minimalist Design', 'Premium Materials', 'Ultra-Thin Profile', 'Limited Edition']
                        ]
                    ];
                @endphp

                <template x-for="videoId in ['1', '2', '3']" :key="videoId">
                    <div x-show="selectedVideoId === videoId" class="text-white">
                        @foreach($detailedVideos as $id => $detailedVideo)
                            <div x-show="selectedVideoId === '{{ $id }}'">
                                <h3 class="text-2xl font-bold mb-4">{{ $detailedVideo['title'] }}</h3>
                                <p class="text-gray-300 mb-6 leading-relaxed">{{ $detailedVideo['description'] }}</p>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-lg font-semibold mb-3">Key Features</h4>
                                        <ul class="space-y-2">
                                            @foreach($detailedVideo['features'] as $feature)
                                                <li class="flex items-center text-gray-300">
                                                    <svg class="w-4 h-4 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="flex flex-col justify-center">
                                        <div class="text-3xl font-bold text-orange-500 mb-4">{{ $detailedVideo['price'] }}</div>
                                        <button class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>
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
                volume: 1.0,
                isMuted: false,
                lastVolume: 1.0,
                isDraggingVolume: false,
                lastTap: 0,
                videoData: videoData,

                initVideo() {
                    const video = this.$refs.mainVideo;
                    this.progress = 0;
                    this.duration = video.duration;
                    this.videoLoaded = true;
                    this.videoError = false;
                    this.isPlaying = video.autoplay;
                    this.volume = video.volume;
                    this.isMuted = video.muted;

                    if (root.currentPlayingId === this.videoId) {
                        video.play();
                        this.isPlaying = true;
                    }
                },

                autoplayOnScroll() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    document.querySelectorAll('video').forEach(v => {
                        if (v !== video) v.pause();
                    });
                    video.play();
                    this.isPlaying = true;
                    root.currentPlayingId = this.videoId;
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
                    if (video.paused) video.play();
                    else video.pause();
                    this.isPlaying = !video.paused;
                },

                updateProgress() {
                    const video = this.$refs.mainVideo;
                    if (!video || !video.duration) return;
                    this.currentTime = video.currentTime;
                    this.progress = (video.currentTime / video.duration) * 100;
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
                    }
                },

                updateVolumeDisplay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    this.volume = video.volume;
                    this.isMuted = video.muted;
                },

                handleDoubleClick(event) {
                    const video = this.$refs.mainVideo;
                    const rect = video.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    if (x < rect.width / 2) video.currentTime = Math.max(0, video.currentTime - 10);
                    else video.currentTime = Math.min(video.duration, video.currentTime + 10);
                    this.updateProgress();
                },

                handleTouchEnd(event) {
                    const currentTime = new Date().getTime();
                    const tapDiff = currentTime - this.lastTap;
                    if (tapDiff < 300) this.handleDoubleClick(event);
                    else this.togglePlay();
                    this.lastTap = currentTime;
                },

                selectVideo(videoId) {
                    root.selectedVideoId = videoId;
                    // Pause all videos and play selected one
                    document.querySelectorAll('video').forEach(v => v.pause());
                    const video = this.$refs.mainVideo;
                    if (video && this.videoId === videoId) {
                        video.play();
                        this.isPlaying = true;
                    }
                }
            };
        }
    </script>
</section>