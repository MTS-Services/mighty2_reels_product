<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <h1
            class="text-lg sm:text-xl lg:text-3xl font-semibold text-gray-900 sm:text-center lg:text-center md:text-left">
            Manage your products and reels
        </h1>

        <div class="flex flex-col sm:flex-row gap-4">
            <x-button wire:click="openModal">
                <flux:icon name="plus-circle" class="h-5 w-5" />
                Add new product
            </x-button>
        </div>
    </div>

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

    <!-- Success Modal -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="closeSuccessModal">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-4">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-medium text-center text-gray-900 mb-2">Success!</h3>
                <p class="text-sm text-center text-gray-600 mb-6">{{ $successMessage }}</p>
                <div class="flex justify-center">
                    <x-button wire:click="closeSuccessModal" variant="secondary">
                        Close
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm flex flex-col">
                <!-- Image Wrapper -->
                <div class="bg-gray-100 flex items-center justify-center relative w-full h-60 p-2">
                    <span class="badge badge-soft {{ $product->status_color }} absolute top-3 left-3 z-10">
                        {{ $product->status_label }}
                    </span>

                    @if ($product->files?->first() && $product->files->first()->file_type == \App\Models\ProductFile::TYPE_IMAGE)
                        <a href="{{ route('admin.product.details', ['productId' => $product->id]) }}"
                            class="w-full h-full flex items-center justify-center">
                            <img src="{{ asset('storage/' . $product->files->first()->file) }}"
                                alt="{{ $product->title }}" class="max-h-full max-w-full object-contain rounded" />
                        </a>
                    @elseif ($product->files?->first() && $product->files->first()->file_type == \App\Models\ProductFile::TYPE_REEL)
                        <a href="{{ route('admin.product.details', ['productId' => $product->id]) }}"
                            class="w-full h-full flex items-center justify-center">
                            <video class="max-h-full max-w-full object-contain rounded">
                                <source src="{{ asset('storage/' . $product->files->first()->file) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>
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

                <!-- Product Content -->
                <div class="p-4 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('admin.product.details', ['productId' => $product->id]) }}">
                            <h3 class="font-semibold text-gray-900">{{ $product->title }}</h3>
                        </a>
                        <span
                            class="px-2 py-1 text-xs rounded-full {{ $product->type == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $product->type == 1 ? 'Rent' : 'Sale' }}
                        </span>

                        <span>
                            <a wire:click="openGalleryModal({{ $product->id }})"
                                class="inline-flex items-center justify-center px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-black text-sm transition-colors duration-200 cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z" />
                                </svg>
                            </a>
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="mt-auto flex items-center justify-between gap-3">
                        <x-button wire:click="editProduct({{ $product->id }})" variant="secondary">
                            <flux:icon name="pencil" class="w-4 h-4" />
                            Edit
                        </x-button>
                        <x-button wire:click="deleteProduct({{ $product->id }})"
                            wire:confirm="Are you sure you want to delete this product?" variant="secondary">
                            <flux:icon name="trash" class="w-4 h-4" />
                            Delete
                        </x-button>
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
                <x-button wire:click="openModal">
                    <flux:icon name="plus-circle" class="h-5 w-5" />
                    Add new product
                </x-button>
            </div>
        @endforelse
    </div>

    @if ($products->hasPages())
        <div class="flex justify-between items-center">
            <span class="text-sm text-orange-500">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                results
            </span>
            <div class="flex gap-2">
                {{ $products->links() }}
            </div>
        </div>
    @endif

    <!-- Gallery Modal with Sortable Functionality -->
    @if ($showGalleryModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50  p-2 sm:p-4"
            wire:click.self="closeGalleryModal" x-data="sortableGallery()" x-effect="document.body.classList.toggle('overflow-hidden', open)">
            <div
                class="bg-white rounded-lg shadow-xl w-full sm:max-w-xl md:max-w-4xl lg:max-w-7xl max-h-[95vh] flex flex-col overflow-hidden">

                <!-- Header -->
                <div class="p-4 sm:p-6 border-b flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <h2 class="text-sm sm:text-xl md:text-2xl font-semibold text-gray-900">Product Media Gallery
                        </h2>
                        <div class="p-1 sm:p-2 text-xs sm:text-sm text-white bg-orange-400/80 rounded-full">
                            Total: {{ count($allMedia) }} items
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="text-xs sm:text-sm text-gray-600 bg-gray-100 px-2 sm:px-3 py-1 rounded-full hidden sm:inline-block">
                            Drag to reorder
                        </span>
                        <x-button variant="secondary" wire:click="closeGalleryModal">
                            <flux:icon name="x-mark" class="h-3 w-3 sm:h-6 sm:w-6" />
                        </x-button>
                    </div>
                </div>

                <!-- Scrollable Media Grid -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 scroll-smooth">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
                        id="sortable-media" x-ref="sortableContainer">
                        @forelse ($allMedia as $index => $media)
                            <div class="group relative cursor-move transition-transform hover:scale-105"
                                data-media-id="{{ $media['id'] }}">

                                <!-- Index / Primary Badge / Delete Button / Media -->
                                <span
                                    class="absolute top-2 right-2 bg-black/70 text-white font-semibold text-xs px-2 py-1 rounded-full shadow-md z-10">
                                    {{ $index }}
                                </span>

                                @if ($media['is_primary'])
                                    <span
                                        class="absolute top-2 left-2 bg-yellow-500 text-white font-semibold text-xs px-2 py-1 rounded-full shadow-md z-10">
                                        PRIMARY
                                    </span>
                                @endif

                                <button wire:click="deleteMediaFile({{ $media['id'] }})"
                                    wire:confirm="Are you sure you want to delete this media file?"
                                    class="absolute top-2 right-12 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>

                                @if ($media['type'] === 'image')
                                    <img src="{{ asset('storage/' . $media['file']) }}" alt="Image"
                                        class="w-full h-32 sm:h-48 object-cover rounded-lg border hover:shadow-md transition-shadow">
                                    <span
                                        class="absolute bottom-2 left-2 bg-green-500 text-white text-[10px] sm:text-xs px-2 py-1 rounded">IMG</span>
                                @else
                                    <video
                                        class="w-full h-32 sm:h-48 object-cover rounded-lg border hover:shadow-md transition-shadow"
                                        controls>
                                        <source src="{{ asset('storage/' . $media['file']) }}" type="video/mp4">
                                    </video>
                                    <span
                                        class="absolute bottom-2 left-2 bg-blue-500 text-white text-[10px] sm:text-xs px-2 py-1 rounded">VID</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 col-span-full">
                                <flux:icon name="photo" class="h-12 w-12 mx-auto mb-2 text-gray-300" />
                                <p>No media found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    @endif

    <!-- Product Form Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/20 bg-opacity-50 flex items-center justify-center z-50"
            wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $editMode ? 'Edit Product' : 'Add New Product' }}
                        </h2>
                        <x-button variant="secondary" wire:click="closeModal">
                            <flux:icon name="x-mark" class="h-6 w-6" />
                        </x-button>
                    </div>
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form wire:submit="saveProduct" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
                                <input wire:model.lazy="title" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="Enter product title">
                                @error('title')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
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

                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                                <input wire:model.lazy="price" type="number" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="0.00">
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Rooms *</label>
                                <input wire:model.lazy="room" type="number" min="1"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="1">
                                @error('room')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
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

                            <div class="md:col-span-1">
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

                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model.lazy="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    placeholder="Enter product description"></textarea>
                                @error('description')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Image Upload Section -->
                            <div class="md:col-span-2" x-data="imageUpload()">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center relative"
                                    :class="{ 'border-green-500 bg-green-50': isDragging }"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event, 'images')">
                                    <input wire:model="images" type="file" multiple accept="image/*"
                                        class="hidden" id="image-upload">
                                    <label for="image-upload" class="cursor-pointer">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Click to upload images or drag and drop</p>
                                        {{-- <p class="text-xs text-gray-400">PNG, JPG, GIF, WebP, SVG (max 10MB each)</p> --}}
                                    </label>
                                </div>
                                @error('images')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Video Upload Section -->
                            <div class="md:col-span-2" x-data="videoUpload()">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Videos/Reels</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center relative"
                                    :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event, 'videos')">
                                    <input wire:model="videos" type="file" multiple accept="video/*"
                                        class="hidden" id="video-upload">
                                    <label for="video-upload" class="cursor-pointer">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Click to upload videos or drag and drop</p>
                                        {{-- <p class="text-xs text-gray-400">MP4, AVI, MOV (max 50MB each)</p> --}}
                                    </label>
                                </div>
                                @error('videos')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Display New Images -->
                            @if (!empty($images))
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">New Images to Upload</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($images as $index => $image)
                                            <div class="relative">
                                                <img src="{{ $image->temporaryUrl() }}"
                                                    class="w-full h-24 object-cover rounded border">
                                                <span
                                                    class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1 rounded">IMG</span>
                                                <button type="button"
                                                    wire:click="removeNewImage({{ $index }})"
                                                    class="absolute cursor-pointer -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Display New Videos -->
                            @if (!empty($videos))
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">New Videos to Upload</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($videos as $index => $video)
                                            <div class="relative">
                                                <video class="w-full h-24 object-cover rounded border" controls>
                                                    <source src="{{ $video->temporaryUrl() }}"
                                                        type="{{ $video->getMimeType() }}">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <span
                                                    class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 rounded">VID</span>
                                                <button type="button"
                                                    wire:click="removeNewVideo({{ $index }})"
                                                    class="absolute cursor-pointer -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Display Existing Images in Edit Mode -->
                            @if ($editMode && !empty($existingImages))
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Existing Images</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($existingImages as $image)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $image['file']) }}"
                                                    class="w-full h-24 object-cover rounded border">
                                                <span
                                                    class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1 rounded">IMG</span>
                                                <button type="button"
                                                    wire:click="removeExistingImage({{ $image['id'] }})"
                                                    class="absolute cursor-pointer -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Display Existing Videos in Edit Mode -->
                            @if ($editMode && !empty($existingVideos))
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Existing Videos</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($existingVideos as $video)
                                            <div class="relative">
                                                <video class="w-full h-24 object-cover rounded border" controls>
                                                    <source src="{{ asset('storage/' . $video['file']) }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <span
                                                    class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 rounded">VID</span>
                                                <button type="button"
                                                    wire:click="removeExistingVideo({{ $video['id'] }})"
                                                    class="absolute cursor-pointer -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <x-button wire:click="closeModal" variant="secondary">
                                Cancel
                            </x-button>
                            <x-button type="submit" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $editMode ? 'Update' : 'Create' }}</span>
                                <span wire:loading>Saving...</span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include SortableJS from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

<script>
    function imageUpload() {
        return {
            isDragging: false,
            handleDrop(event, type) {
                this.isDragging = false;
                const droppedFiles = event.dataTransfer.files;

                const validFiles = Array.from(droppedFiles).filter(file => {
                    if (type === 'images') {
                        return file.type.startsWith('image/');
                    }
                    return false;
                });

                validFiles.forEach(file => {
                    @this.upload('images', file);
                });
            }
        }
    }

    function videoUpload() {
        return {
            isDragging: false,
            handleDrop(event, type) {
                this.isDragging = false;
                const droppedFiles = event.dataTransfer.files;

                const validFiles = Array.from(droppedFiles).filter(file => {
                    if (type === 'videos') {
                        return file.type.startsWith('video/');
                    }
                    return false;
                });

                validFiles.forEach(file => {
                    @this.upload('videos', file);
                });
            }
        }
    }

    function sortableGallery() {
        return {
            sortable: null,

            init() {
                this.$nextTick(() => {
                    this.initSortable();
                });
            },

            initSortable() {
                const container = this.$refs.sortableContainer;
                if (!container) return;

                this.sortable = new Sortable(container, {
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    forceFallback: true,
                    fallbackClass: 'sortable-fallback',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,

                    onStart: function(evt) {
                        evt.item.style.opacity = '0.7';
                        evt.item.style.transform = 'rotate(5deg)';
                    },

                    onEnd: (evt) => {
                        evt.item.style.opacity = '';
                        evt.item.style.transform = '';

                        if (evt.oldIndex !== evt.newIndex) {
                            this.updateOrder();
                        }
                    }
                });

                this.addCustomStyles();
            },

            updateOrder() {
                const container = this.$refs.sortableContainer;
                const mediaItems = container.querySelectorAll('[data-media-id]');

                const orderedIds = Array.from(mediaItems).map(item => {
                    return parseInt(item.getAttribute('data-media-id'));
                });

                this.showLoadingState(true);

                @this.call('updateMediaOrder', orderedIds).then(() => {
                    this.showLoadingState(false);
                    this.updateSortOrderBadges();
                }).catch((error) => {
                    console.error('Error updating media order:', error);
                    this.showLoadingState(false);
                });
            },

            updateSortOrderBadges() {
                const container = this.$refs.sortableContainer;
                const mediaItems = container.querySelectorAll('[data-media-id]');

                mediaItems.forEach((item, index) => {
                    const badge = item.querySelector('.absolute.top-2.right-2');
                    if (badge) {
                        badge.textContent = index;
                    }
                });
            },

            showLoadingState(isLoading) {
                const container = this.$refs.sortableContainer;
                if (isLoading) {
                    container.style.pointerEvents = 'none';
                    container.style.opacity = '0.7';
                } else {
                    container.style.pointerEvents = '';
                    container.style.opacity = '';
                }
            },

            addCustomStyles() {
                if (document.getElementById('sortable-styles')) return;

                const styles = document.createElement('style');
                styles.id = 'sortable-styles';
                styles.textContent = `
                    .sortable-ghost {
                        opacity: 0.4 !important;
                        background: #f3f4f6 !important;
                        border: 2px dashed #d1d5db !important;
                        border-radius: 0.5rem !important;
                    }
                    
                    .sortable-chosen {
                        transform: scale(1.05) !important;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                        z-index: 1000 !important;
                    }
                    
                    .sortable-drag {
                        opacity: 0.8 !important;
                        transform: rotate(3deg) !important;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                    }
                    
                    .sortable-fallback {
                        display: none !important;
                    }
                    
                    #sortable-media [data-media-id] {
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    
                    #sortable-media [data-media-id]:hover {
                        transform: translateY(-2px);
                    }
                `;
                document.head.appendChild(styles);
            }
        }
    }
</script>
