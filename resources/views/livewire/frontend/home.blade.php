{{-- <div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <style>
            .video-container {
                background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            }

            .heart-icon {
                transition: all 0.2s ease;
            }

            .heart-icon:hover {
                transform: scale(1.1);
            }

            .progress-bar {
                background: linear-gradient(90deg, #ff6b35 0%, #ff6b35 var(--progress, 0%), #4a4a4a var(--progress, 0%), #4a4a4a 100%);
            }

            .video-overlay {
                background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
            }

            .play-button {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
            }
        </style>
        <div class="flex flex-col justify-center items-center">
            <div
                class="video-container w-full h-[calc(100vh-100px)] md:h-[calc(100vh-200px)] lg:h-[calc(100vh-300px)] max-w-sm  rounded-xl overflow-hidden shadow-2xl relative">
                <!-- Heart Icon -->
                <div class="absolute top-4 right-4 z-20">
                    <button class="heart-icon bg-white rounded-full p-2 shadow-lg hover:shadow-xl">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" class="text-gray-800">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Video Section -->
                <div class="relative overflow-hidden bg-black h-full">
                    <video id="mainVideo" class="w-full h-full object-cover" loop muted playsinline>
                        <source src="{{ asset('assets/videos/PinDown.io_@malinmp4.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Video Overlay -->
                    <div class="video-overlay absolute inset-0 pointer-events-none"></div>

                    <!-- Play Button -->
                    <button id="playButton"
                        class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110">
                        <svg id="playIcon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                            class="text-gray-800">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg id="pauseIcon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                            class="text-gray-800 hidden">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                        <!-- Content Section -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-white text-2xl font-boldleading-tight">Discover Timeless Style</h2>
                                <button
                                    class="bg-orange-normal text-white p-2 rounded-full shadow hover:bg-orange-dark transition-colors text-sm">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Explore our curated watch collection — where fashion meets precision
                            </p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="relative">
                            <div class="flex items-center space-x-3">
                                <button id="playToggle" class="text-white hover:text-orange-500 transition-colors">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>

                                <div class="flex-1 relative">
                                    <div class="progress-bar h-1 rounded-full cursor-pointer" id="progressBar"
                                        style="--progress: 0%">
                                    </div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg transition-all duration-200"
                                        id="progressHandle" style="left: 0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const video = document.getElementById('mainVideo');
            const playButton = document.getElementById('playButton');
            const playToggle = document.getElementById('playToggle');
            const playIcon = document.getElementById('playIcon');
            const pauseIcon = document.getElementById('pauseIcon');
            const progressBar = document.getElementById('progressBar');
            const progressHandle = document.getElementById('progressHandle');

            let isPlaying = false;
            let playPromise = null;

            function togglePlay() {
                if (isPlaying) {
                    if (playPromise !== null) {
                        playPromise.then(() => {
                            video.pause();
                        }).catch(() => {});
                    } else {
                        video.pause();
                    }
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                    playButton.style.opacity = '1';
                    isPlaying = false;
                } else {
                    playPromise = video.play();

                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            playIcon.classList.add('hidden');
                            pauseIcon.classList.remove('hidden');
                            playButton.style.opacity = '0';
                            isPlaying = true;
                            playPromise = null;
                        }).catch((error) => {
                            console.log('[v0] Video play failed:', error);
                            playIcon.classList.remove('hidden');
                            pauseIcon.classList.add('hidden');
                            playButton.style.opacity = '1';
                            isPlaying = false;
                            playPromise = null;
                        });
                    }
                }
            }

            function updateProgress() {
                if (video.duration) {
                    const progress = (video.currentTime / video.duration) * 100;
                    progressBar.style.setProperty('--progress', `${progress}%`);
                    progressHandle.style.left = `${progress}%`;
                }
            }

            function setProgress(e) {
                const rect = progressBar.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                if (video.duration) {
                    video.currentTime = pos * video.duration;
                }
            }

            playButton.addEventListener('click', togglePlay);
            playToggle.addEventListener('click', togglePlay);
            video.addEventListener('timeupdate', updateProgress);
            progressBar.addEventListener('click', setProgress);

            video.addEventListener('ended', () => {
                isPlaying = false;
                playPromise = null;
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                playButton.style.opacity = '1';
            });

            video.addEventListener('play', () => {
                setTimeout(() => {
                    if (isPlaying) {
                        playButton.style.opacity = '0';
                    }
                }, 1000);
            });

            video.addEventListener('mouseenter', () => {
                if (!isPlaying) {
                    playButton.style.opacity = '1';
                }
            });

            video.addEventListener('error', (e) => {
                console.log('[v0] Video error:', e);
            });
        </script>
    </div>
</div> --}}


<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <style>
            .video-container {
                background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            }

            .heart-icon {
                transition: all 0.2s ease;
            }

            .heart-icon:hover {
                transform: scale(1.1);
            }

            .progress-bar {
                background: linear-gradient(90deg,
                        #ff6b35 0%,
                        #ff6b35 var(--progress, 0%),
                        #4a4a4a var(--progress, 0%),
                        #4a4a4a 100%);
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
        </style>

        <div class="flex flex-col justify-center items-center">
            <div
                class="video-container w-full h-[calc(100vh-100px)] md:h-[calc(100vh-200px)] lg:h-[calc(100vh-300px)] max-w-sm rounded-xl overflow-hidden shadow-2xl relative">
                <!-- Heart Icon -->
                <div class="absolute top-4 right-4 z-20">
                    <button class="heart-icon bg-white rounded-full p-2 shadow-lg hover:shadow-xl">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" class="text-gray-800">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Video Section -->
                <div class="relative overflow-hidden bg-black h-full">
                    <video id="mainVideo" class="w-full h-full object-cover" loop muted playsinline>
                        <source src="{{ asset('assets/videos/PinDown.io_@malinmp4.mp4') }}" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>

                    <!-- Video Overlay -->
                    <div class="video-overlay absolute inset-0 pointer-events-none"></div>

                    <!-- Play Button -->
                    <button id="playButton"
                        class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 shadow-lg transition-all duration-200 hover:scale-110">
                        <svg id="playIcon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                            class="text-gray-800">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg id="pauseIcon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                            class="text-gray-800 hidden">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                    </button>

                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                        <!-- Content Section -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-white text-2xl font-bold leading-tight">
                                    Discover Timeless Style
                                </h2>
                                <button
                                    class="bg-orange-500 text-white p-2 rounded-full shadow hover:bg-orange-600 transition-colors text-sm">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Explore our curated watch collection — where fashion meets
                                precision
                            </p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="relative">
                            <div class="flex items-center space-x-3">
                                <button id="playToggle" class="text-white hover:text-orange-500 transition-colors">
                                    <svg id="playIcon2" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg id="pauseIcon2" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="text-orange-500 hidden">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>

                                <div class="flex-1 relative">
                                    <div class="progress-bar h-1.5 rounded-full cursor-pointer" id="progressBar"
                                        style="--progress: 0%"></div>
                                    <div class="absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-white rounded-full shadow-lg transition-all duration-200"
                                        id="progressHandle" style="left: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const video = document.getElementById("mainVideo");
            const playButton = document.getElementById("playButton");
            const playToggle = document.getElementById("playToggle");
            const playIcon = document.getElementById("playIcon");
            const pauseIcon = document.getElementById("pauseIcon");
            const playIcon2 = document.getElementById("playIcon2");
            const pauseIcon2 = document.getElementById("pauseIcon2");
            const progressBar = document.getElementById("progressBar");
            const progressHandle = document.getElementById("progressHandle");

            let lastTap = 0; // mobile double-tap

            function togglePlay() {
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
            }

            // update icons
            video.addEventListener("play", () => {
                playIcon.classList.add("hidden");
                pauseIcon.classList.remove("hidden");
                playIcon2.classList.add("hidden");
                pauseIcon2.classList.remove("hidden");
                playButton.style.opacity = "0";
            });
            video.addEventListener("pause", () => {
                playIcon.classList.remove("hidden");
                pauseIcon.classList.add("hidden");
                playIcon2.classList.remove("hidden");
                pauseIcon2.classList.add("hidden");
                playButton.style.opacity = "1";
            });

            // update progress bar
            function updateProgress() {
                if (video.duration) {
                    const progress = (video.currentTime / video.duration) * 100;
                    progressBar.style.setProperty("--progress", `${progress}%`);
                    progressHandle.style.left = `${progress}%`;
                }
            }
            video.addEventListener("timeupdate", updateProgress);

            // set progress manually
            function setProgress(e) {
                const rect = progressBar.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                if (video.duration) {
                    video.currentTime = pos * video.duration;
                }
            }
            progressBar.addEventListener("click", setProgress);

            // click events
            playButton.addEventListener("click", togglePlay);
            playToggle.addEventListener("click", togglePlay);
            video.addEventListener("click", togglePlay);

            // double tap mobile
            video.addEventListener("touchend", (e) => {
                const currentTime = new Date().getTime();
                const tapLength = currentTime - lastTap;

                if (tapLength < 300 && tapLength > 0) {
                    const rect = video.getBoundingClientRect();
                    const x = e.changedTouches[0].clientX - rect.left;

                    if (x < rect.width / 2) {
                        video.currentTime = Math.max(0, video.currentTime - 10);
                    } else {
                        video.currentTime = Math.min(
                            video.duration,
                            video.currentTime + 10
                        );
                    }
                }
                lastTap = currentTime;
            });

            // double click desktop
            video.addEventListener("dblclick", (e) => {
                const rect = video.getBoundingClientRect();
                const x = e.clientX - rect.left;

                if (x < rect.width / 2) {
                    video.currentTime = Math.max(0, video.currentTime - 10);
                } else {
                    video.currentTime = Math.min(video.duration, video.currentTime + 10);
                }
            });
        </script>
    </div>
</div>
