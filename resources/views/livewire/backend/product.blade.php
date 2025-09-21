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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm flex flex-col">
                <!-- Image Wrapper -->
                <div class="bg-gray-100 flex items-center justify-center relative w-full h-60 p-2">
                    <span class="badge badge-soft {{ $product->status_color }} absolute top-3 left-3 z-10">
                        {{ $product->status_label }}
                    </span>
                    @if ($product->images->count() > 0)
                        <a href="{{ route('admin.product.details', $product->id) }}"
                            class="w-full h-full flex items-center justify-center">
                            <img src="{{ storage_url($product->images->first()->file) }}" alt="{{ $product->title }}"
                                class="max-h-full max-w-full object-contain rounded" />
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

                            <!-- File Upload Section -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Files (Images &
                                    Videos)</label>
                                <div
                                    class="border-2 border-dashed border-gray-300 text-black rounded-lg p-6 text-center">
                                    <input wire:model="files" type="file" multiple accept="image/*,video/*"
                                        class="hidden" id="file-upload">
                                    <label for="file-upload" class="cursor-pointer">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Click to upload files or drag and drop</p>
                                        <p class="text-xs text-gray-400">Images: PNG, JPG, GIF, WebP, SVG (max 10MB
                                            each)</p>
                                        <p class="text-xs text-gray-400">Videos: MP4, AVI, MOV (max 50MB each)</p>
                                    </label>
                                </div>
                                @error('files.*')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Display New Files -->
                            @if (!empty($files))
                                <div class="md:col-span-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">New Files to Upload</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach ($files as $index => $file)
                                            <div class="relative">
                                                @if (str_starts_with($file->getMimeType(), 'image/'))
                                                    <img src="{{ $file->temporaryUrl() }}"
                                                        class="w-full h-30 object-cover rounded border">
                                                    <span
                                                        class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1 rounded">IMG</span>
                                                @elseif (str_starts_with($file->getMimeType(), 'video/'))
                                                    <video class="w-full h-30 object-cover rounded border" controls>
                                                        <source src="{{ $file->temporaryUrl() }}"
                                                            type="{{ $file->getMimeType() }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <span
                                                        class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 rounded">VID</span>
                                                @endif

                                                <button type="button"
                                                    wire:click="removeNewFile({{ $index }})"
                                                    class="absolute cursor-pointer -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <!-- Display Existing Files in Edit Mode -->
                            @if ($editMode && !empty($existingFiles))
                                <div class="md:col-span-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Existing Files</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach ($existingFiles as $file)
                                            <div class="relative">
                                                @if ($file['file_type'] == 1)
                                                    {{-- Image --}}
                                                    <img src="{{ asset('storage/' . $file['file']) }}"
                                                        class="w-full h-30 object-cover rounded border">
                                                    <span
                                                        class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1 rounded">IMG</span>
                                                @elseif ($file['file_type'] == 2)
                                                    {{-- Video --}}
                                                    <video class="w-full h-30 object-cover rounded border" controls>
                                                        <source src="{{ asset('storage/' . $file['file']) }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <span
                                                        class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 rounded">VID</span>
                                                @endif
                                                <button type="button"
                                                    wire:click="removeExistingFile({{ $file['id'] }})"
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
