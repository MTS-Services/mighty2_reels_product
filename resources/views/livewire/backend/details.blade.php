<section>

    <div class="bg-gray-50 min-h-screen">
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
                <!-- Product Image Section -->
                <div>
                    <!-- Main Product Image -->
                    {{-- <div class=" from-orange-50 to-red-50 rounded-2xl p-8 mb-6">
                        <img src="{{ storage_url($product->images->first()->file)}}" alt="{{$product->title}}">
                    </div> --}}

                    <!-- Thumbnail Navigation -->

                   <div class="bg-gradient-to-br rounded-2xl  mb-4 flex items-center justify-center">
    @if ($activeType === 'image')
        <img src="{{ storage_url($activeMedia) }}" alt="{{ $product->title }}" class="w-full h-80 object-contain rounded">
    @elseif ($activeType === 'video')
        <video class="w-full h-80 object-contain rounded" controls>
            <source src="{{ storage_url($activeMedia) }}" type="video/mp4">
        </video>
    @endif
</div>

<div class="relative flex items-center">
    <!-- Left Arrow -->
    <button onclick="document.getElementById('thumbSlider').scrollBy({ left: -100, behavior: 'smooth' })"
            class="absolute left-0 z-10  rounded-full p-2  ">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- Thumbnails Slider -->
    <div id="thumbSlider" class="flex space-x-3 overflow-x-auto py-2 scrollbar-hide w-full px-8">
        <!-- Images Thumbnails -->
        @foreach ($product->images as $image)
            <div wire:click="changeMedia('{{ $image->file }}', 'image')"
                 class="flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors
                 {{ $activeMedia === $image->file && $activeType === 'image' ? 'border-orange-500 border-2' : 'border-gray-200' }}">
                <img src="{{ storage_url($image->file) }}" class="w-full h-full object-cover rounded">
            </div>
        @endforeach

        <!-- Videos Thumbnails -->
        @foreach ($videos as $video)
            <div wire:click="changeMedia('{{ $video->file }}', 'video')"
                 class="flex-shrink-0 w-16 h-20 bg-gray-100 rounded-lg border flex items-center justify-center cursor-pointer transition-colors
                 {{ $activeMedia === $video->file && $activeType === 'video' ? 'border-orange-500 border-2' : 'border-gray-200' }}">
                <video class="w-full h-full object-cover rounded">
                    <source src="{{ storage_url($video->file) }}" type="video/mp4">
                </video>
            </div>
        @endforeach
    </div>

    <!-- Right Arrow -->
    <button onclick="document.getElementById('thumbSlider').scrollBy({ left: 100, behavior: 'smooth' })"
            class="absolute right-0 z-10  rounded-full p-2 ">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>




                </div>

                <!-- Product Details Section -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>

                    <!-- Product Description -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border mb-6">
                        <p class="text-gray-700 leading-relaxed mb-3">{{ $product->description }}
                        </p>
                        <button class="text-orange-500 font-medium hover:text-orange-600">Read more...</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-[#EFEFEF] p-2 mt-30">
            <div class="max-w-6xl mx-auto px-4 py-2">
                <p class="text-center text-orange-500 font-medium">© 2025 Reelshop. All rights reserved.</p>
            </div>
        </footer>

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
