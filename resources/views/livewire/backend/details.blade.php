<section>
    <div class="bg-gray-50 min-h-[calc(100vh-100px)]">
        <!-- Header -->
        <div>
            <div class="max-w-6xl mx-auto px-4 py-4">
                <x-button variant="secondary" size="sm" href="{{ route('admin.products') }}" wire:navigate>
                    <flux:icon name="arrow-left" class="h-5 w-5" />
                    Back to home screen
                </x-button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid md:grid-cols-2 gap-12">

                <div>
                    <!-- Main Active Media -->
                    <div class="bg-gradient-to-br rounded-2xl mb-4 flex items-center justify-center shadow-sm">
                        @if ($activeType === 'image')
                            <img src="{{ storage_url($activeMedia) }}" alt="{{ $product->title }}"
                                class="w-full h-80 object-contain rounded">
                        @elseif ($activeType === 'video')
                            <video class="w-full h-80 object-contain rounded" controls muted autoplay>
                                <source src="{{ storage_url($activeMedia) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>

                    <!-- Thumbnails Slider -->
                    <div class="relative flex items-center">
                        <div id="thumbSlider"
                            class="flex space-x-3 overflow-x-auto scrollbar-hide w-full px-6 py-4 shadow-sm scroll-smooth snap-x snap-mandatory">
                            @foreach ($files as $file)
                                @if ($file->file_type == \App\Models\ProductFile::TYPE_IMAGE)
                                    <div class="thumb-item flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors relative snap-start
                                        {{ $activeMedia === $file->file && $activeType === 'image' ? 'border-orange-500 border-2' : 'border-gray-200' }}"
                                        data-file="{{ $file->file }}" data-type="image">
                                        @if ($file->isprimary)
                                            <span
                                                class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs px-1 py-0.5 rounded-full text-[10px] font-bold">
                                                P
                                            </span>
                                        @endif
                                        <img src="{{ storage_url($file->file) }}"
                                            class="w-full h-full object-contain rounded">
                                    </div>
                                @elseif ($file->file_type == \App\Models\ProductFile::TYPE_REEL)
                                    <div class="thumb-item flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors relative snap-start
                                        {{ $activeMedia === $file->file && $activeType === 'video' ? 'border-orange-500 border-2' : 'border-gray-200' }}"
                                        data-file="{{ $file->file }}" data-type="video">
                                        @if ($file->isprimary)
                                            <span
                                                class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs px-1 py-0.5 rounded-full text-[10px] font-bold">
                                                P
                                            </span>
                                        @endif
                                        <video class="w-full h-full object-contain rounded">
                                            <source src="{{ storage_url($file->file) }}" type="video/mp4">
                                        </video>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>

                    <div class="bg-white rounded-xl p-6 shadow-sm border mb-6" x-data="{
                        showDescription: false,
                        fullDescription: `{{ $product->description }}`,
                        get truncatedDescription() {
                            return this.fullDescription.length > 650 ? this.fullDescription.substring(0, 650) + '...' : this.fullDescription;
                        },
                        get shouldShowButton() {
                            return this.fullDescription.length > 650;
                        }
                    }">
                        <p class="text-gray-700 leading-relaxed mb-3"
                            x-text="showDescription ? fullDescription : truncatedDescription">
                        </p>

                        <template x-if="shouldShowButton">
                            <button
                                class="text-orange-500 font-medium hover:text-orange-600 transition-colors duration-200"
                                @click="showDescription = !showDescription"
                                x-text="showDescription ? 'Show Less' : 'Show More'">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const slider = document.getElementById('thumbSlider');

            // Drag-to-scroll
            let isDown = false, startX, scrollLeft;
            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            slider.addEventListener('mouseleave', () => isDown = false);
            slider.addEventListener('mouseup', () => isDown = false);
            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 1.5;
                slider.scrollLeft = scrollLeft - walk;
            });

            // Hover auto-scroll
            let hoverInterval;
            slider.addEventListener('mousemove', (e) => {
                const { left, right, width } = slider.getBoundingClientRect();
                const mouseX = e.clientX;
                const edgeSize = 60; // edge zone size

                clearInterval(hoverInterval);
                if (mouseX < left + edgeSize) {
                    hoverInterval = setInterval(() => slider.scrollLeft -= 5, 10);
                } else if (mouseX > right - edgeSize) {
                    hoverInterval = setInterval(() => slider.scrollLeft += 5, 10);
                }
            });
            slider.addEventListener('mouseleave', () => clearInterval(hoverInterval));

            // Click-to-center and change media
            document.querySelectorAll('.thumb-item').forEach(item => {
                item.addEventListener('click', () => {
                    const file = item.dataset.file;
                    const type = item.dataset.type;
                    @this.changeMedia(file, type);

                    // Smooth scroll to center
                    const sliderRect = slider.getBoundingClientRect();
                    const itemRect = item.getBoundingClientRect();
                    const offset = (itemRect.left - sliderRect.left) - (sliderRect.width / 2) + (itemRect.width / 2);
                    slider.scrollBy({ left: offset, behavior: 'smooth' });
                });
            });
        </script>
    </div>
</section>
