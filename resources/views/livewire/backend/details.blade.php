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
                    <div class="bg-gradient-to-br rounded-2xl  mb-4 flex items-center justify-center shadow-sm">
                        @if ($activeType === 'image')
                            <img src="{{ storage_url($activeMedia) }}" alt="{{ $product->title }}"
                                class="w-full h-80 object-contain rounded">
                        @elseif ($activeType === 'video')
                            <video class="w-full h-80 object-contain rounded" controls muted autoplay>
                                <source src="{{ storage_url($activeMedia) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>

                    <div class="relative flex items-center">
                        <!-- Thumbnails Slider -->
                        <div id="thumbSlider"
                            class="flex space-x-3 overflow-x-auto py-2 scrollbar-hide w-full px-8 shadow-sm">
                            <!-- Images Thumbnails -->
                            @foreach ($product->images as $image)
                                <div wire:click="changeMedia('{{ $image->file }}', 'image')"
                                    class="flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors
                 {{ $activeMedia === $image->file && $activeType === 'image' ? 'border-orange-500 border-2' : 'border-gray-200' }}">
                                    <img src="{{ storage_url($image->file) }}"
                                        class="w-full h-full object-contain rounded">
                                </div>
                            @endforeach

                            <!-- Videos Thumbnails -->
                            @foreach ($videos as $video)
                                <div wire:click="changeMedia('{{ $video->file }}', 'video')"
                                    class="flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors
                 {{ $activeMedia === $video->file && $activeType === 'video' ? 'border-orange-500 border-2' : 'border-gray-200' }}">
                                    <video class="w-full h-full object-contain rounded" autoplay muted>
                                        <source src="{{ storage_url($video->file) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endforeach
                        </div>
                    </div>




                </div>

                <!-- Product Details Section -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>

                    <!-- Product Description -->


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

                        <!-- Only show button if description is longer than 200 characters -->
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

        <!-- Footer -->
        {{-- <footer class="bg-[#EFEFEF] p-2 mt-30">
            <div class="max-w-6xl mx-auto px-4 py-2">
                <p class="text-center text-orange-500 font-medium">© 2025 Reelshop. All rights reserved.</p>
            </div>
        </footer> --}}

        <script>
            // Quantity selector functionality
            const decreaseBtn = document.querySelector('button:contains("-")');
            const increaseBtn = document.querySelector('button:contains("+")');
            const quantitySpan = document.querySelector('span:contains("1")');

            let quantity = 1;

            // Simple quantity management
            document.addEventListener('click', function(e) {
                if (e.target.textContent === '-' && quantity > 1) {
                    quantity--;
                    updateQuantityDisplay();
                } else if (e.target.textContent === '+') {
                    quantity++;
                    updateQuantityDisplay();
                }
            });

            function updateQuantityDisplay() {
                const quantityElement = document.querySelector('.border-x');
                if (quantityElement) {
                    quantityElement.textContent = quantity;
                }
            }

            // Add to cart feedback
            document.querySelector('button:contains("Add to Cart")')?.addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Added to Cart!';
                this.classList.add('bg-green-600');
                this.classList.remove('bg-red-600');

                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-green-600');
                    this.classList.add('bg-red-600');
                }, 2000);
            });
        </script>
    </div>

    </html>
</section>
