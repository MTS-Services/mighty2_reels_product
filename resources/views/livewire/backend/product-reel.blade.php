<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <h1
            class="text-lg sm:text-xl lg:text-3xl font-semibold text-gray-900 sm:text-center lg:text-center md:text-left">
            Manage your reels
        </h1>

        <div class="flex flex-col sm:flex-row gap-4">
            <x-button wire:click="openModal">
                <flux:icon name="plus-circle" class="h-5 w-5" />
                Add new reel
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

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @forelse($reels as $reel)
            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm h-[400px]  flex flex-col">
                <div class="aspect-video bg-gray-100 relative">
                    {{-- <span
                        class="badge badge-soft {{ $reel->status_color }} absolute top-1 left-1">{{ $reel->status_label }}</span> --}}
                    @if ($reel->file)
                        <video controls class="w-full h-56 object-contain rounded-t-lg">
                            <source src="{{ Storage::url($reel->file) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div class="w-full h-full bg-gray-200 rounded-t-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-900 line-clamp-2">
                                {{ $reel->product->title ?? 'N/A' }}
                            </h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                            {{ Str::limit($reel->product->description ?? '', 100) }}
                        </p>
                    </div>
                    <div class="mt-auto flex items-center justify-between space-x-2">
                        <x-button wire:click="editReel({{ $reel->id }})" variant="secondary" class="flex-1">
                            <flux:icon name="pencil" class="h-5 w-5 mr-1" />
                            Edit
                        </x-button>
                        <x-button wire:click="deleteReel({{ $reel->id }})" variant="secondary"
                            wire:confirm="Are you sure you want to delete this reel?" class="flex-1">
                            <flux:icon name="trash" class="h-5 w-5 mr-1" />
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
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No reels found</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first reel.</p>
                <x-button wire:click="openModal">
                    <flux:icon name="plus-circle" class="h-5 w-5 mr-1" />
                    Add new reel
                </x-button>
            </div>
        @endforelse
    </div>

    @if ($reels->hasPages())
        <div class="flex justify-between items-center">
            <span class="text-sm text-orange-500">
                Showing {{ $reels->firstItem() }} to {{ $reels->lastItem() }} of {{ $reels->total() }} results
            </span>
            <div class="flex gap-2">
                {{ $reels->links() }}
            </div>
        </div>
    @endif

    <!-- Main Modal for Add/Edit Reel -->
  @if ($showModal)
    <div class="fixed inset-0 bg-black/20 bg-opacity-50 flex items-center justify-center z-50"
        wire:click.self="closeModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $editMode ? 'Edit Reel' : 'Add New Reel' }}
                    </h2>
                    <x-button variant="secondary" wire:click="closeModal">
                        <flux:icon name="x-mark" class="h-5 w-5" />
                    </x-button>
                </div>

                <form wire:submit="{{ $editMode ? 'updateReel' : 'saveReel' }}" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                        <select wire:model.lazy="productId"
                            class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Select a product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('productId')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Video</label>
                        <div x-data="videoUpload()"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event, 'videos')"
                            :class="{ 'border-orange-500': isDragging }"
                            class="border-2 border-dashed border-gray-300 text-black rounded-lg p-6 text-center relative transition-colors duration-200">
                            <input wire:model="file" type="file" accept="video/*" class="hidden"
                                id="video-upload">

                            @if (!$file && !$tempFile)
                                <label for="video-upload" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload video or drag and drop</p>
                                    {{-- <p class="text-xs text-gray-400">MP4, AVI, MOV up to 50MB</p> --}}
                                </label>
                            @endif

                            @if ($file)
                                <div class="relative">
                                    <video controls class="w-full rounded-md h-50 object-contain"
                                        src="{{ $file->temporaryUrl() }}"></video>
                                    <x-button variant="secondary" class="!absolute -top-2 -right-2"
                                        wire:click="removeVideo">
                                        <flux:icon name="x-mark" class="h-4 w-4" />
                                    </x-button>
                                </div>
                            @elseif ($editMode && $tempFile)
                                <div class="relative">
                                    <video controls class="w-full rounded-md object-contain">
                                        <source src="{{ asset('storage/' . $tempFile) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <x-button variant="secondary" class="!absolute -top-2 -right-2"
                                        wire:click="removeVideo">
                                        <flux:icon name="x-mark" class="h-4 w-4" />
                                    </x-button>
                                </div>
                            @endif
                        </div>
                        @error('file')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <x-button variant="secondary" wire:click="closeModal">
                            Cancel
                        </x-button>
                        <x-button type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ $editMode ? 'Update Reel' : 'Add Reel' }}</span>
                            <span wire:loading>Saving...</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

    <!-- Primary Reel Confirmation Modal -->
    @if ($showPrimaryModal)
        <div class="fixed inset-0 bg-black/50 bg-opacity-75 flex items-center justify-center z-60">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">
                                Replace Primary Reel?
                            </h3>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">
                            This product already has a primary reel. Do you want to replace the existing primary reel
                            with this new one?
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            The current primary reel will remain but won't be marked as primary anymore.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-button variant="secondary" wire:click="cancelReplacePrimary">
                            No, Cancel
                        </x-button>
                        <x-button wire:click="confirmReplacePrimary"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white">
                            Yes, Replace
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
   
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
                    @this.upload('file', file); // Use 'file' to match your Livewire property
                });
            }
        }
    }
</script>