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
                    <div class=" from-orange-50 to-red-50 rounded-2xl p-8 mb-6">
                        <img src="{{ asset('assets/images/Frames.png') }}" alt="">
                    </div>

                    <!-- Thumbnail Navigation -->
                    <div class="flex space-x-3 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-8 h-6 w-6 text-orange-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div
                            class="w-16 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center hover:border-orange-300 cursor-pointer">
                            <div class="w-10 h-12 bg-gradient-to-r from-orange-400 to-green-400 rounded">
                                <img src="{{ asset('assets/images/Frames.png') }}" alt="Frame">

                            </div>
                        </div>
                        <div
                            class="w-16 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center hover:border-orange-300 cursor-pointer">
                            <div class="w-10 h-12 bg-gradient-to-r from-orange-400 to-green-400 rounded">
                                <img src="{{ asset('assets/images/Frames.png') }}" alt="Frame">

                            </div>
                        </div>
                        <div
                            class="w-16 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center hover:border-orange-300 cursor-pointer">
                            <div class="w-10 h-12 bg-gradient-to-r from-orange-400 to-green-400 rounded">
                                <img src="{{ asset('assets/images/Frames.png') }}" alt="Frame">

                            </div>
                        </div>

                        <div
                            class="w-16 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center hover:border-orange-300 cursor-pointer">

                            <div class="w-10 h-12 bg-gradient-to-r from-orange-400 to-green-400 rounded">
                                <img src="{{ asset('assets/images/Frames.png') }}" alt="Frame">

                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-8 h-6 w-6 text-orange-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </div>
                </div>

                <!-- Product Details Section -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Vitamin C-100ML-Original</h1>

                    <!-- Product Description -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border mb-6">
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Vitamin C, also known as ascorbic acid, is a vital nutrient for various bodily functions,
                            including the maintenance of healthy skin, blood vessels, bones, and cartilage. It also
                            helps with wound healing and acts as an antioxidant.
                            Read more...Vitamin C, also known as ascorbic acid, is a vital nutrient for various bodily
                            functions, including the maintenance of healthy skin, blood vessels, bones, and cartilage.
                            It also helps with wound healing and acts as an antioxidant.Vitamin C, also known as
                            ascorbic acid, is a vital nutrient for various bodily functions, including the maintenance
                            of healthy skin, blood vessels, bones, and cartilage. It also helps with wound healing and
                            acts as an antioxidant.Vitamin C, also known as ascorbic acid, .
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
