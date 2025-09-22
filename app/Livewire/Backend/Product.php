<?php

namespace App\Livewire\Backend;

use App\Models\Product as ProductModel;
use App\Models\ProductFile;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'product'])]
class Product extends Component
{
    use WithFileUploads, WithPagination;

    // Modal & state
    public $showModal = false;
    public $showGalleryModal = false;
    public $editMode = false;
    public $productId;

    // Form fields
    public $title = '';
    public $description = '';
    public $price = '';
    public $room = '';
    public $city_id = '';
    public $type = 1;
    public $status = 1;

    // Separate file uploads
    public $images = [];
    public $videos = [];
    public $existingImages = [];
    public $existingVideos = [];
    public $imagesToDelete = [];
    public $videosToDelete = [];

    // Gallery data
    public $allMedia = [];

    // Success modal
    public $showSuccessModal = false;
    public $successMessage = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'room' => 'required|integer|min:1',
        'city_id' => 'required|exists:cities,id',
        'type' => 'required|in:0,1',
        'status' => 'required|in:0,1',

    ];

    protected $messages = [
        'title.required' => 'Product title is required.',
        'price.required' => 'Price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'room.required' => 'Number of rooms is required.',
        'city_id.required' => 'City selection is required.',
        'images.*.image' => 'Each image must be a valid image file.',
        'images.*.max' => 'Each image must not exceed 10MB.',
        'videos.*.mimes' => 'Videos must be in MP4, AVI, or MOV format.',
        'videos.*.max' => 'Each video must not exceed 50MB.',
    ];

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function openGalleryModal($productId)
    {
        $this->productId = $productId;
        $this->loadAllMediaFiles($productId);
        $this->showGalleryModal = true;
    }

    public function closeGalleryModal()
    {
        $this->showGalleryModal = false;
        $this->allMedia = [];
        $this->productId = null;
        $this->resetValidation();
    }

    /**
     * Load all media files (images and videos) for a product
     */
    private function loadAllMediaFiles($productId)
    {
        // Get all media files (both images and videos) in one query
        $allMediaFiles = ProductFile::where('product_id', $productId)
            ->where('status', ProductFile::STATUS_ACTIVE)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($file) => [
                'id' => $file->id,
                'file' => $file->file,
                'type' => $file->file_type === ProductFile::TYPE_IMAGE ? 'image' : 'video',
                'sort_order' => $file->sort_order,
                'is_primary' => $file->is_primary ?? false,
                'created_at' => $file->created_at,
            ]);

        // Sort: primary files first (by sort_order), then non-primary files (by sort_order)
        $primaryFiles = $allMediaFiles->where('is_primary', true)->sortBy('sort_order');
        $nonPrimaryFiles = $allMediaFiles->where('is_primary', false)->sortBy('sort_order');
        // Merge with primary files first, then non-primary files
        $this->allMedia = $primaryFiles->merge($nonPrimaryFiles)->values()->toArray();
    }

    /**
     * Update the sort order of media files
     */
    public function updateMediaOrder($orderedIds)
    {
        try {
            if (!is_array($orderedIds) || empty($orderedIds)) {
                return [
                    'success' => false,
                    'message' => 'Invalid data provided'
                ];
            }

            // Get all files
            $files = ProductFile::whereIn('id', $orderedIds)->get();

            // Update sort_order for each file based on its position in the array
            foreach ($orderedIds as $index => $fileId) {
                $file = $files->firstWhere('id', $fileId);
                if ($file) {
                    $file->update(['sort_order' => $index]);
                }
            }

            // Get the current product ID from the first file if not set
            if (!$this->productId && !empty($orderedIds)) {
                $firstFile = ProductFile::find($orderedIds[0]);
                if ($firstFile) {
                    $this->productId = $firstFile->product_id;
                }
            }

            // Reload media to reflect the new order immediately
            if ($this->showGalleryModal && $this->productId) {
                $this->loadAllMediaFiles($this->productId);
            }

            // Show success modal
            $this->successMessage = 'Media order updated successfully!';
            $this->showSuccessModal = true;

            Log::info('Media order updated for files: ' . implode(', ', $orderedIds));

            return [
                'success' => true,
                'message' => 'Media order updated successfully!'
            ];
        } catch (\Exception $e) {
            Log::error('Error updating media order: ' . $e->getMessage(), [
                'exception' => $e,
                'ordered_ids' => $orderedIds
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update media order.'
            ];
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = '';
    }

    /**
     * Delete a specific media file
     */
    public function deleteMediaFile($fileId)
    {
        try {
            $productFile = ProductFile::find($fileId);

            if (!$productFile) {
                session()->flash('error', 'File not found.');
                return;
            }

            $productId = $productFile->product_id;

            // Delete physical file from storage
            if (Storage::disk('public')->exists($productFile->file)) {
                Storage::disk('public')->delete($productFile->file);
            }

            // Delete database record
            $productFile->delete();

            // Reorder remaining files
            $this->reorderRemainingFiles($productId);

            // Reload media to reflect changes
            if ($this->showGalleryModal) {
                $this->loadAllMediaFiles($productId);
            }

            session()->flash('success', 'Media file deleted successfully!');
            Log::info("Successfully deleted file: {$productFile->file}");
        } catch (\Exception $e) {
            Log::error("Error deleting file {$fileId}: " . $e->getMessage());
            session()->flash('error', 'Failed to delete media file.');
        }
    }

    /**
     * Reorder remaining files after deletion
     */
    private function reorderRemainingFiles($productId)
    {
        // Get primary files first, then non-primary files
        $primaryFiles = ProductFile::where('product_id', $productId)
            ->where('status', ProductFile::STATUS_ACTIVE)
            ->where('is_primary', true)
            ->orderBy('sort_order')
            ->get();

        $nonPrimaryFiles = ProductFile::where('product_id', $productId)
            ->where('status', ProductFile::STATUS_ACTIVE)
            ->where('is_primary', false)
            ->orderBy('sort_order')
            ->get();

        // Reorder primary files
        foreach ($primaryFiles as $index => $file) {
            $file->update(['sort_order' => $index]);
        }

        // Reorder non-primary files (continuing from where primary files ended)
        $startIndex = $primaryFiles->count();
        foreach ($nonPrimaryFiles as $index => $file) {
            $file->update(['sort_order' => $startIndex + $index]);
        }
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->productId = null;
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->room = '';
        $this->city_id = '';
        $this->type = 1;
        $this->status = 1;
        $this->images = [];
        $this->videos = [];
        $this->existingImages = [];
        $this->existingVideos = [];
        $this->imagesToDelete = [];
        $this->videosToDelete = [];
    }

    public function saveProduct()
    {
        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'room' => 'required|integer|min:1',
            'city_id' => 'required|exists:cities,id',
            'type' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'images.*' => 'mimetypes:image/jpeg,image/png,image/gif',
        ];

        // Conditional validation for videos
        if (!$this->editMode) {
            // Creating new product - videos are required
            $rules['videos'] = 'required|array|min:1';
        } else {
            // Editing existing product - videos are optional
            $rules['videos'] = 'nullable|array';
        }

        $this->validate($rules);
        try {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->price,
                'room' => $this->room,
                'city_id' => $this->city_id,
                'type' => $this->type,
                'status' => $this->status,
            ];

            if ($this->editMode && $this->productId) {
                $product = ProductModel::findOrFail($this->productId);
                $product->update(array_merge($data, ['updated_at' => now(), 'updated_by' => user()->id]));
            } else {
                $product = ProductModel::create(array_merge($data, ['created_at' => now(), 'created_by' => user()->id]));
            }

            $this->handleFileUploads($product->id);

            $this->closeModal();
            session()->flash('success', $this->editMode ? 'Product updated successfully!' : 'Product created successfully!');

            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while saving the product.');
        }
    }

    private function handleFileUploads($productId)
    {
        try {
            if ($this->editMode) {
                $this->deleteMarkedFiles();
            }

            // Process image uploads
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    if ($image instanceof UploadedFile) {
                        $this->processAndSaveFile($image, $productId, ProductFile::TYPE_IMAGE);
                    }
                }
            }

            // Process video uploads
            if (!empty($this->videos)) {
                foreach ($this->videos as $video) {
                    if ($video instanceof UploadedFile) {
                        $this->processAndSaveFile($video, $productId, ProductFile::TYPE_REEL);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling file uploads: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    private function deleteMarkedFiles()
    {
        $filesToDelete = array_merge($this->imagesToDelete, $this->videosToDelete);

        if (empty($filesToDelete)) {
            return;
        }

        foreach ($filesToDelete as $fileId) {
            try {
                $productFile = ProductFile::find($fileId);
                if ($productFile) {
                    if (Storage::disk('public')->exists($productFile->file)) {
                        Storage::disk('public')->delete($productFile->file);
                    }
                    $productFile->forceDelete();
                    Log::info("Successfully deleted file: {$productFile->file}");
                }
            } catch (\Exception $e) {
                Log::error("Error deleting file {$fileId}: " . $e->getMessage());
            }
        }

        $this->imagesToDelete = [];
        $this->videosToDelete = [];
    }

    private function processAndSaveFile(UploadedFile $file, $productId, $fileType)
    {

        try {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;

            $storagePath = $fileType === ProductFile::TYPE_IMAGE
                ? 'products/images'
                : 'products/videos';

            $path = $file->storeAs($storagePath, $filename, 'public');

            $lastSortOrder = ProductFile::where('product_id', $productId)
                ->max('sort_order');

            $sortOrder = is_null($lastSortOrder) ? 0 : $lastSortOrder + 1;

            ProductFile::create([
                'product_id' => $productId,
                'file' => $path,
                'file_type' => $fileType,
                'status' => ProductFile::STATUS_ACTIVE,
                'sort_order' => $sortOrder,
                'is_primary' => false, // New files are not primary by default
                'created_at' => now(),
                'created_by' => user()->id,
            ]);

            Log::info("Successfully uploaded file: {$path}");
        } catch (\Exception $e) {
            Log::error("Error processing file upload: " . $e->getMessage());
            session()->flash('error', 'Error uploading file: ' . $file->getClientOriginalName());
        }
    }

    public function editProduct($productId)
    {
        try {
            $product = ProductModel::with(['images', 'reels' => fn($query) => $query->where('is_primary', '!=', true)])->findOrFail($productId);

            $this->editMode = true;
            $this->productId = $productId;
            $this->title = $product->title;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->room = $product->room;
            $this->city_id = $product->city_id;
            $this->type = $product->type;
            $this->status = $product->status;

            $this->existingImages = $product->images->map(function ($file) {
                return [
                    'id' => $file->id,
                    'file' => $file->file,
                    'file_type' => $file->file_type,
                    'status' => $file->status,
                ];
            })->toArray();

            $this->existingVideos = $product->reels->map(function ($file) {
                return [
                    'id' => $file->id,
                    'file' => $file->file,
                    'file_type' => $file->file_type,
                    'status' => $file->status,
                ];
            })->toArray();

            $this->images = [];
            $this->videos = [];
            $this->imagesToDelete = [];
            $this->videosToDelete = [];

            $this->showModal = true;
        } catch (\Exception $e) {
            Log::error('Error loading product for edit: ' . $e->getMessage());
            session()->flash('error', 'Error loading product for editing.');
        }
    }

    public function deleteProduct($productId)
    {
        try {
            $product = ProductModel::with(['images', 'reels'])->findOrFail($productId);

            $allFiles = collect($product->images)->merge($product->reels);
            foreach ($allFiles as $file) {
                if (Storage::disk('public')->exists($file->file)) {
                    Storage::disk('public')->delete($file->file);
                }
                $file->forceDelete();
            }

            $product->forceDelete();

            session()->flash('success', 'Product deleted successfully!');
            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while deleting the product.');
        }
    }

    public function removeNewImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function removeNewVideo($index)
    {
        if (isset($this->videos[$index])) {
            unset($this->videos[$index]);
            $this->videos = array_values($this->videos);
        }
    }

    public function removeExistingImage($imageId)
    {
        if (!in_array($imageId, $this->imagesToDelete)) {
            $this->imagesToDelete[] = $imageId;
        }

        $this->existingImages = array_filter($this->existingImages, function ($image) use ($imageId) {
            return $image['id'] != $imageId;
        });

        $this->existingImages = array_values($this->existingImages);
    }

    public function removeExistingVideo($videoId)
    {
        if (!in_array($videoId, $this->videosToDelete)) {
            $this->videosToDelete[] = $videoId;
        }

        $this->existingVideos = array_filter($this->existingVideos, function ($video) use ($videoId) {
            return $video['id'] != $videoId;
        });

        $this->existingVideos = array_values($this->existingVideos);
    }

    public function updatedImages()
    {
        $this->validateOnly('images.*');

        $totalImages = count($this->images) + count($this->existingImages);
        if ($totalImages > 10) {
            session()->flash('error', 'Maximum 10 images allowed per product.');
            $this->images = [];
        }
    }

    public function updatedVideos()
    {
        $this->validateOnly('videos.*');

        $totalVideos = count($this->videos) + count($this->existingVideos);
        if ($totalVideos > 5) {
            session()->flash('error', 'Maximum 5 videos allowed per product.');
            $this->videos = [];
        }
    }

    public function render()
    {
        $products = ProductModel::with(['city', 'files' => function ($query) {
            $query->where('status', ProductFile::STATUS_ACTIVE)
                ->orderBy('is_primary', 'desc')
                ->orderBy('sort_order', 'asc');
        }])
            ->paginate(9);
        $cities = City::where('status', 1)->orderBy('name')->get();

        return view('livewire.backend.product', [
            'products' => $products,
            'cities' => $cities
        ]);
    }
}
