<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <h1
            class="text-lg sm:text-xl lg:text-3xl font-semibold text-gray-900 sm:text-center lg:text-center md:text-left">
            Manage your products and reels
        </h1>

        <div class="flex flex-col sm:flex-row gap-4">
            <button wire:click="openModal"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-md sm:text-md font-medium transition-colors">
                Add a new product
            </button>
            <a href="{{ route('admin.area') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-md sm:text-md font-medium transition-colors">
                Areas
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm h-[340px] lg:h-[380px] flex flex-col">
                <div class="aspect-video bg-gray-100 p-4">
                    @if ($product->images->count() > 0)
                        <img src="{{ storage_url($product->images->first()->file) }}" alt="{{ $product->title }}"
                            class="w-full h-full object-cover rounded">
                    @else
                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-gray-900">{{ $product->title }}</h3>
                        <span
                            class="px-2 py-1 text-xs rounded-full {{ $product->type == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $product->type == 1 ? 'Rent' : 'Sale' }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="mt-auto flex items-center justify-between gap-3">
                        <button wire:click="editProduct({{ $product->id }})"
                            class="w-full flex items-center justify-center gap-1 rounded-md py-2 bg-[#FFF0E6] border border-orange-400 text-orange-500 hover:text-orange-600 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </button>
                        <button wire:click="deleteProduct({{ $product->id }})"
                            wire:confirm="Are you sure you want to delete this product?"
                            class="w-full flex items-center justify-center gap-1 rounded-md py-2 bg-[#FAE8E8] border border-[#C91C1C] text-[#C91C1C] hover:bg-red-50 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first product.</p>
                <button wire:click="openModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Add a new product
                </button>
            </div>
        @endforelse
    </div>

    {{-- <!-- Pagination -->
    @if ($products->hasPages())
        <div class="flex justify-between items-center">
            <span class="text-sm text-orange-500">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
            </span>
            <div class="flex gap-2">
                {{ $products->links() }}
            </div>
        </div>
    @endif --}}

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/20  bg-opacity-50 flex items-center justify-center z-50"
            wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $editMode ? 'Edit Product' : 'Add New Product' }}
                        </h2>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveProduct" class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
                            <input wire:model.lazy="title" type="text"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                placeholder="Enter product title">
                            @error('title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model.lazy="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                placeholder="Enter product description"></textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price and Room (Row) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                                <input wire:model.lazy="price" type="number" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="0.00">
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Rooms *</label>
                                <input wire:model.lazy="room" type="number" min="1"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="1">
                                @error('room')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- City and Type (Row) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <select wire:model.lazy="city_id"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">Select a city</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                                <select wire:model.lazy="type"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="1">For Rent</option>
                                    <option value="0">For Sale</option>
                                </select>
                                @error('type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select wire:model.lazy="status"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Upload Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
                            <div class="border-2 border-dashed border-gray-300 text-black rounded-lg p-6 text-center">
                                <input wire:model.lazy="images" type="file" multiple accept="image/*"
                                    class="hidden" id="image-upload">
                                <label for="image-upload" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload images or drag and drop</p>
                                    <p class="text-xs text-gray-400">PNG, JPG, GIF up to 2MB each</p>
                                </label>
                            </div>
                            @error('images.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Image Previews -->
                            @if (count($images) > 0)
                                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($images as $index => $image)
                                        <div class="relative">
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="w-full h-20 object-cover rounded">
                                            <button type="button" wire:click="removeImage({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Upload Videos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Videos</label>
                            <div class="border-2 border-dashed border-gray-300 text-black rounded-lg p-6 text-center">
                                <input wire:model.lazy="videos" type="file" multiple accept="video/*"
                                    class="hidden" id="video-upload">
                                <label for="video-upload" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload videos or drag and drop</p>
                                    <p class="text-xs text-gray-400">MP4, AVI, MOV up to 10MB each</p>
                                </label>
                            </div>
                            @error('videos.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Video Previews -->
                            @if (count($videos) > 0)
                                <div class="mt-4 space-y-2">
                                    @foreach ($videos as $index => $video)
                                        <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                            <span
                                                class="text-sm text-gray-600">{{ $video->getClientOriginalName() }}</span>
                                            <button type="button" wire:click="removeVideo({{ $index }})"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Form Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 border border-gray-300 text-black rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md font-medium transition-colors"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $editMode ? 'Update Product' : 'Add Product' }}</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 flex items-center space-x-3">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>
