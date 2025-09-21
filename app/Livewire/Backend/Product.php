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
    public $showGalleryModal = false; // New property for gallery modal
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
    public $images = []; // For image files only
    public $videos = []; // For video files only
    public $existingImages = []; // For edit mode - existing images from database
    public $existingVideos = []; // For edit mode - existing videos from database
    public $imagesToDelete = []; // Track images marked for deletion
    public $videosToDelete = []; // Track videos marked for deletion

    // Gallery data
    public $allImages = []; // All images for gallery display
    public $allVideos = []; // All videos for gallery display

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'room' => 'required|integer|min:1',
        'city_id' => 'required|exists:cities,id',
        'type' => 'required|in:0,1',
        'status' => 'required|in:0,1',
        'images.*' => 'nullable|image|max:10240', // Max 10MB for images
        'videos.*' => 'nullable|mimes:mp4,avi,mov|max:51200', // Max 50MB for videos
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
        $this->loadAllMediaFiles($productId);
        $this->showGalleryModal = true;
    }

    public function closeGalleryModal()
    {
        $this->showGalleryModal = false;
        $this->allImages = [];
        $this->allVideos = [];
    }

    public $allMedia = []; // Combined media array
    private function loadAllMediaFiles($productId)
    {
        // Get images
        $images = ProductFile::where('product_id', $productId)
            ->where('file_type', ProductFile::TYPE_IMAGE)
            ->where('status', ProductFile::STATUS_ACTIVE)
            ->get()
            ->map(fn($file) => [
                'id' => $file->id,
                'file' => $file->file,
                'type' => 'image',
                'sort_order' => $file->sort_order,
                'created_at' => $file->created_at,
            ])
            ->toArray();

        // Get videos
        $videos = ProductFile::where('product_id', $productId)
            ->where('file_type', ProductFile::TYPE_REEL)
            ->where('status', ProductFile::STATUS_ACTIVE)
            ->get()
            ->map(fn($file) => [
                'id' => $file->id,
                'file' => $file->file,
                'type' => 'video',
                'sort_order' => $file->sort_order,
                'created_at' => $file->created_at,
            ])
            ->toArray();

        // Merge images & videos and sort by sort_order ascending
        $this->allMedia = collect(array_merge($images, $videos))
            ->sortBy('sort_order')
            ->values()
            ->toArray();
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
        $this->validate();

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

            // Reset page to refresh the product list
            $this->resetPage();
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while saving the product.');
        }
    }

    private function handleFileUploads($productId)
    {
        try {
            // First, delete files marked for deletion (only in edit mode)
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
            throw $e; // Re-throw to be caught by saveProduct
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
                    // Delete physical file from storage
                    if (Storage::disk('public')->exists($productFile->file)) {
                        Storage::disk('public')->delete($productFile->file);
                    }

                    // Delete database record
                    $productFile->delete();

                    Log::info("Successfully deleted file: {$productFile->file}");
                }
            } catch (\Exception $e) {
                Log::error("Error deleting file {$fileId}: " . $e->getMessage());
                // Continue with other files even if one fails
            }
        }

        // Clear the deletion arrays
        $this->imagesToDelete = [];
        $this->videosToDelete = [];
    }

    private function processAndSaveFile(UploadedFile $file, $productId, $fileType)
    {
        try {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;

            // Determine storage path based on file type
            $storagePath = $fileType === ProductFile::TYPE_IMAGE
                ? 'products/images'
                : 'products/videos';

            // Store the file
            $path = $file->storeAs($storagePath, $filename, 'public');

            // Calculate sort order for this product
            $lastSortOrder = ProductFile::where('product_id', $productId)
                ->max('sort_order');

            $sortOrder = is_null($lastSortOrder) ? 0 : $lastSortOrder + 1;

            // Create database record
            ProductFile::create([
                'product_id' => $productId,
                'file' => $path,
                'file_type' => $fileType,
                'status' => ProductFile::STATUS_ACTIVE,
                'sort_order' => $sortOrder,
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
            $product = ProductModel::with(['images', 'reels'])->findOrFail($productId);

            $this->editMode = true;
            $this->productId = $productId;

            $this->title = $product->title;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->room = $product->room;
            $this->city_id = $product->city_id;
            $this->type = $product->type;
            $this->status = $product->status;

            // Separate existing files by type
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

            // Reset file-related arrays
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

            // Delete all associated files
            $allFiles = collect($product->images)->merge($product->reels);
            foreach ($allFiles as $file) {
                if (Storage::disk('public')->exists($file->file)) {
                    Storage::disk('public')->delete($file->file);
                }
                $file->forceDelete();
            }

            $product->forceDelete();

            session()->flash('success', 'Product deleted successfully!');
            $this->resetPage(); // Reset pagination to refresh the list

        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while deleting the product.');
        }
    }

    public function removeNewImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Re-index array
        }
    }

    public function removeNewVideo($index)
    {
        if (isset($this->videos[$index])) {
            unset($this->videos[$index]);
            $this->videos = array_values($this->videos); // Re-index array
        }
    }

    public function removeExistingImage($imageId)
    {
        // Mark image for deletion
        if (!in_array($imageId, $this->imagesToDelete)) {
            $this->imagesToDelete[] = $imageId;
        }

        // Remove from existing images display
        $this->existingImages = array_filter($this->existingImages, function ($image) use ($imageId) {
            return $image['id'] != $imageId;
        });

        $this->existingImages = array_values($this->existingImages); // Re-index array
    }

    public function removeExistingVideo($videoId)
    {
        // Mark video for deletion
        if (!in_array($videoId, $this->videosToDelete)) {
            $this->videosToDelete[] = $videoId;
        }

        // Remove from existing videos display
        $this->existingVideos = array_filter($this->existingVideos, function ($video) use ($videoId) {
            return $video['id'] != $videoId;
        });

        $this->existingVideos = array_values($this->existingVideos); // Re-index array
    }

    public function updatedImages()
    {
        // Validate images when they are updated
        $this->validateOnly('images.*');

        // Optional: Validate total image count
        $totalImages = count($this->images) + count($this->existingImages);
        if ($totalImages > 10) { // Adjust limit as needed
            session()->flash('error', 'Maximum 10 images allowed per product.');
            $this->images = [];
        }
    }

    public function updatedVideos()
    {
        // Validate videos when they are updated
        $this->validateOnly('videos.*');

        // Optional: Validate total video count
        $totalVideos = count($this->videos) + count($this->existingVideos);
        if ($totalVideos > 5) { // Adjust limit as needed
            session()->flash('error', 'Maximum 5 videos allowed per product.');
            $this->videos = [];
        }
    }

    public function render()
    {
        $products = ProductModel::with(['city', 'images', 'reels'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $cities = City::where('status', 1)->orderBy('name')->get();

        return view('livewire.backend.product', [
            'products' => $products,
            'cities' => $cities
        ]);
    }
}
