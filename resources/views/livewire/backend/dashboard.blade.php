<section>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            500: '#f97316',
                            600: '#ea580c'
                        }
                    }
                }
            }
        }
    </script>
    <div x-data="productManager()" class="max-w-7xl mx-auto p-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-xl lg:text-3xl font-semibold text-gray-900">Manage your products and reels</h1>
            <div class="flex gap-3">
                <button
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-md font-medium transition-colors">
                    Add a new product
                </button>
                <button
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-md font-medium transition-colors">
                    Add area
                </button>
            </div>
        </div>

        <!-- Service Areas Section -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="text-gray-700 font-medium">Service Areas</span>
            </div>

            <div class="flex flex-wrap gap-2">
                <template x-for="area in serviceAreas" :key="area.id">
                    <div class="flex items-center bg-gray-200 rounded-full px-3 py-1.5 text-sm">
                        <span x-text="area.name" class="text-gray-700"></span>
                        <button @click="removeArea(area.id)" class="ml-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <template x-for="product in products" :key="product.id">
                <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                    <div class="aspect-video bg-gray-100 p-4">
                        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2" x-text="product.name"></h3>
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed" x-text="product.description"></p>
                        <div class="flex items-center justify-between gap-3">
                            <button @click="editProduct(product.id)"
                                class="w-full flex items-center justify-center gap-1 rounded-md py-2 bg-[#FFF0E6] border border-orange-400 text-orange-500 hover:text-orange-600 text-sm font-medium">
                                {{-- edit icon --}}
                                <flux:icon name="pencil" class="w-4 h-4" />
                                Edit
                            </button>
                            <button @click="deleteProduct(product.id)"
                                class="w-full flex items-center justify-center gap-1 rounded-md py-2 bg-[#FAE8E8] border border-[#C91C1C] text-[#C91C1C] text-sm font-medium">
                                <flux:icon name="trash" class="w-4 h-4" />
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center">
            <span class="text-sm text-orange-500">Showing 1 to 6 of 4 results</span>
            <div class="flex gap-2">
                <button
                    class="px-3 py-1.5 text-sm border border-[#FF6600] text-[#FF6600] rounded-md transition-colors">
                    Previous
                </button>
                <button
                    class="px-3 py-1.5 text-sm border border-[#FF6600] text-[#FF6600] rounded-md transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>

    <script>
        function productManager() {
            return {
                serviceAreas: [{
                        id: 1,
                        name: 'Sydney'
                    },
                    {
                        id: 2,
                        name: 'Melbourne'
                    },
                    {
                        id: 3,
                        name: 'Brisbane'
                    },
                    {
                        id: 4,
                        name: 'Perth'
                    },
                    {
                        id: 5,
                        name: 'Adelaide'
                    },
                    {
                        id: 6,
                        name: 'Canberra'
                    },
                    {
                        id: 7,
                        name: 'Hobart'
                    },
                    {
                        id: 8,
                        name: 'Darwin'
                    },
                    {
                        id: 9,
                        name: 'New South Wales'
                    },
                    {
                        id: 10,
                        name: 'Victoria'
                    },
                    {
                        id: 11,
                        name: 'Queensland'
                    },
                    {
                        id: 12,
                        name: 'Western Australia'
                    },
                    {
                        id: 13,
                        name: 'Northern'
                    },
                    {
                        id: 14,
                        name: 'Ballarat'
                    },
                    {
                        id: 15,
                        name: 'Bendigo'
                    },
                    {
                        id: 16,
                        name: 'Toowoomba'
                    },
                    {
                        id: 17,
                        name: 'Mildura'
                    }
                ],
                products: [{
                        id: 1,
                        name: 'Premium Wireless Headphones',
                        description: 'Premium wireless headphones with rich sound, deep bass, and all-day comfort.',
                        image: '{{ asset('assets/images/frontend/product1.png') }}'
                    },
                    {
                        id: 2,
                        name: 'Aurora Smartwatch',
                        description: 'Smart and stylish smartwatch with health tracking, instant notifications, and up to 7 days of battery life.',
                        image: '{{ asset('assets/images/frontend/product2.png') }}'
                    },
                    {
                        id: 3,
                        name: 'Luxe Leather Wallet',
                        description: 'Handcrafted premium leather wallet with a slim design, multiple card slots, and long-lasting durability.',
                        image: '{{ asset('assets/images/frontend/product1.png') }}'
                    },
                    {
                        id: 4,
                        name: 'Pulse Wireless Earbuds',
                        description: 'Wireless earbuds with crystal-clear sound, powerful bass, and a comfortable fit for music anywhere.',
                        image: '{{ asset('assets/images/frontend/product2.png') }}'
                    },
                    {
                        id: 5,
                        name: 'EcoTherm Water Bottle',
                        description: 'Insulated stainless steel water bottle that keeps drinks hot or cold for hours, perfect for travel and workouts.',
                        image: '{{ asset('assets/images/frontend/product1.png') }}'
                    },
                    {
                        id: 6,
                        name: 'ComfyFlex Sneakers',
                        description: 'Lightweight, breathable sneakers with cushioned support for all-day comfort and casual style.',
                        image: '{{ asset('assets/images/frontend/product2.png') }}'
                    }
                ],
                removeArea(areaId) {
                    this.serviceAreas = this.serviceAreas.filter(area => area.id !== areaId);
                },
                editProduct(productId) {
                    alert(`Editing product with ID: ${productId}`);
                },
                deleteProduct(productId) {
                    if (confirm('Are you sure you want to delete this product?')) {
                        this.products = this.products.filter(product => product.id !== productId);
                    }
                }
            }
        }
    </script>
</section>
