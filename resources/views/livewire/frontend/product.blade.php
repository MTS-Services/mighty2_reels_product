<section class="max-w-7xl mx-auto px-4 pt-1 sm:px-6 lg:px-8" x-data="videoApp()">

    <div class="relative h-full max-w-lg mx-auto">
        <div x-show="isHorizontalMode">
            <div class="absolute bottom-4 right-4 z-50">
                <button @click="toggleHorizontalMode()"
                    class="bg-orange-500 text-white p-3 rounded-full shadow 
                     transition-all duration-300 transform hover:scale-110">
                    <flux:icon name="arrow-up" class="w-5 h-5" />
                    </button>
                </div>
            <div x-data="{ liked: false }" class="absolute top-4 lg:right-4 right-2 z-[5000] rounded-full">
                <button @click="liked = !liked"
                    :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                    class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                    :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                    
                    <flux:icon name="heart" class="w-5 h-5" />
                    
                </button>
                </div>
            </div>

        <div class="relative w-full mx-auto h-full scrollbar-hide"
            :class="isHorizontalMode ? 'max-w-none ' : 'max-w-lg'">
            <div x-show="!isHorizontalMode">
                <div class="absolute bottom-[100px] z-50 right-8">
                    <button @click="toggleHorizontalMode()"
                        class="bg-orange-500 text-white p-3 rounded-full shadow hover:bg-orange-600 transition-all duration-300 transform hover:scale-110">
                        <flux:icon name="arrow-right" class="w-5 h-5" />
                        </button>
                    </div>
                <div x-data="{ liked: false }"
                    class="absolute top-4 lg:right-8 right-2 z-[5000] rounded-full">
                    <button @click="liked = !liked"
                        :class="liked ? 'bg-red-400 text-white' : 'bg-white text-black hover:bg-white/70'"
                        
                        class="transition-colors duration-300 rounded-full p-3 shadow-lg hover:shadow-xl transform"
                        :style="liked ? 'animation: heart-pop 0.3s ease forwards;' : ''">
                        
                        <flux:icon name="heart" class="w-5 h-5" />
                        
                    </button>
                    </div>
                </div>

            <div
                :class="isHorizontalMode
                    ?
                    'flex flex-col items-center overflow-y-auto space-y-4 scrollbar-hide mx-auto max-w-7xl snap-y snap-mandatory p-4 scroll-smooth' :
                    'overflow-y-scroll snap-y scrollbar-hide snap-mandatory h-[calc(100vh-165px)] pt-0.5 space-y-6 scroll-smooth'">

                <template x-for="video in videos" :key="video.id">
                    <div class="video-item"
                        :class="isHorizontalMode ? 'flex-shrink-0 w-[200px]! snap-start cursor-pointer' : 'snap-start pb-0.5'"
                        x-data="videoController(video.id, $root)"
                        x-intersect:enter="!isHorizontalMode && autoplayOnScroll()"
                        x-intersect:leave="!isHorizontalMode && pauseVideo()"
                        @click="isHorizontalMode && selectVideo(video.id)">

                        <div class="flex flex-col justify-center items-center relative"
                            :class="isHorizontalMode ? 'mb-4' : ''">

                            <div
                                class="w-full rounded-xl overflow-hidden shadow-2xl transition-all duration-600"
                                
                                :class="isHorizontalMode ? 'h-52 w-[200px]!' : 'h-[calc(100vh-165px)] max-w-lg rounded-xl'">

                                <video x-ref="mainVideo" :data-video-id="video.id"
                                    @loadedmetadata="initVideo()"
                                    @timeupdate="updateProgress()" @play="handlePlay()" @pause="handlePause()"
                                    @click.stop="!isHorizontalMode && togglePlay()"
                                    
                                    @touchend.prevent="!isHorizontalMode && handleTouchEnd($event)"
                                    @dblclick="!isHorizontalMode && handleDoubleClick($event)"
                                    @volumechange="updateVolumeDisplay()"
                                    class="w-full h-full object-cover" loop
                                    playsinline autoplay muted>
                                    
                                    <source :src="video.videoUrl" type="video/mp4">
                                    
                                </video>

                                <div
                                    class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent to-black/30 z-0">
                                    </div>

                                <button @click.stop="togglePlay()"
                                    
                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-20 bg-white/90 backdrop-blur-md"
                                    
                                    :style="{ opacity: (isPlaying && !isHorizontalMode) ? '0' : '1' }"
                                    :class="isHorizontalMode ? 'opacity-100 p-1' : 'p-4'">
                                    <svg x-show="!isPlaying" width="24"
                                        height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        
                                        <path d="M8 5v14l11-7z" />
                                        
                                    </svg>
                                    <svg x-show="isPlaying" width="24"
                                        height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-gray-800">
                                        
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                        
                                    </svg>
                                    </button>

                                <div
                                    class="absolute bottom-0 left-0 right-0 z-10 p-6 bg-gradient-to-t from-black/70 to-transparent"
                                    :class="isHorizontalMode ? 'p-3' : ''">
                                    <div x-data="{ visible: false }"
                                        x-intersect:enter="visible = true"
                                        x-intersect:leave="visible = false">
                                        <h2 x-show="visible"
                                            x-transition.opacity.duration.500ms
                                            class="text-white mb-2 leading-tight font-bold animate-fade-in-up"
                                            
                                            :class="isHorizontalMode ? 'text-sm' : 'text-2xl'" x-text="video.title">
                                            </h2>
                                        <p x-show="visible && !isHorizontalMode"
                                            x-transition.opacity.duration.700ms
                                            class="text-gray-300 mb-2 text-sm animate-fade-in-up"
                                            x-text="video.description">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            <div x-show="isHorizontalMode && selectedVideoId"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="mt-8 bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 max-w-4xl mx-auto">
                <template x-if="selectedVideo">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-4"
                            x-text="selectedVideo.title"></h3>
                        <p class="text-gray-300 leading-relaxed"
                            x-text="selectedVideo.detailedDescription"></p>
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
                // --- NEW ---
                // Add a new property to store the ID of the video currently in view
                activeVideoIdInView: null,
                showInfoPanel: false,
                videos: [{
                        id: 1,
                        title: 'Timeless Elegance',
                        description: 'Luxury watches with modern and traditional craftsmanship.',
                        detailedDescription: 'Discover the art of horology with our Timeless Elegance collection, blending modern innovation with traditional craftsmanship in every intricate detail.',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        favourite: true
                    },
                    {
                        id: 2,
                        title: 'Minimalist Masterpiece',
                        description: 'Zenith Series focuses on clean lines and elegance.',
                        detailedDescription: 'The Zenith Series embodies minimalist design, combining sleek aesthetics with Swiss precision to create a masterpiece of understated elegance.',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                        favourite: false
                    },
                    {
                        id: 3,
                        title: 'Sporty Precision',
                        description: 'The Racer Collection features durable outdoor watches.',
                        detailedDescription: 'Engineered for adventure, the Racer Collection offers rugged durability and sporty precision, making it the perfect companion for any challenge.',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                        favourite: false
                    },
                ],
                // --- MODIFIED ---
                toggleHorizontalMode() {
                    // --- Add this line for debugging ---
                    console.log('Just clicked! Active ID in view is:', this.activeVideoIdInView);

                    if (!this.isHorizontalMode && this.activeVideoIdInView) {
                        this.selectedVideoId = this.activeVideoIdInView;
                    } else {
                        this.selectedVideoId = null;
                    }

                    // --- And add this line too ---
                    console.log('After logic, selectedVideoId is now:', this.selectedVideoId);

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
                get selectedVideo() {
                    return this.videos.find(v => v.id === this.selectedVideoId);
                }
            }
        }

        function videoController(videoId, root) {
            return {
                isPlaying: false,
                initVideo() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    this.isPlaying = !video.paused;
                },
                togglePlay() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;
                    if (video.paused) video.play();
                    else video.pause();
                    this.isPlaying = !video.paused;
                },
                // --- MODIFIED ---
                // This function now also updates the main component with the current video's ID.
                autoplayOnScroll() {
                    const video = this.$refs.mainVideo;
                    if (!video) return;

                    // Update the new state variable in the parent component (`root`).
                    root.activeVideoIdInView = videoId;

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
                updateProgress() {},
                handlePlay() {
                    this.isPlaying = true;
                },
                handlePause() {
                    this.isPlaying = false;
                },
                handleTouchEnd() {},
                handleDoubleClick() {},
                updateVolumeDisplay() {},
            }
        }
    </script>
    </section>
