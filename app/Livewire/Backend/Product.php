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

    // File uploads
    public $files = []; // Combined array for all files
    public $existingFiles = []; // For edit mode - existing files from database
    public $filesToDelete = []; // Track files marked for deletion

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'room' => 'required|integer|min:1',
        'city_id' => 'required|exists:cities,id',
        'type' => 'required|in:0,1',
        'status' => 'required|in:0,1',
        'files.*' => 'nullable|file|max:51200', // Max 50MB
    ];

    protected $messages = [
        'title.required' => 'Product title is required.',
        'price.required' => 'Price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'room.required' => 'Number of rooms is required.',
        'city_id.required' => 'City selection is required.',
        'files.*.file' => 'Each upload must be a valid file.',
        'files.*.max' => 'Each file must not exceed 50MB.',
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
        $this->files = [];
        $this->existingFiles = [];
        $this->filesToDelete = [];
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

            // Process new file uploads
            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    if ($file instanceof UploadedFile) {
                        $this->processAndSaveFile($file, $productId);
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
        if (empty($this->filesToDelete)) {
            return;
        }

        foreach ($this->filesToDelete as $fileId) {
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
        
        // Clear the deletion array
        $this->filesToDelete = [];
    }

    private function processAndSaveFile(UploadedFile $file, $productId)
    {
        try {
            $mimeType = $file->getMimeType();
            $fileType = $this->determineFileType($mimeType);
            
            if (!$fileType) {
                Log::warning('Unsupported file type uploaded: ' . $mimeType);
                session()->flash('error', 'Unsupported file type: ' . $file->getClientOriginalName());
                return;
            }

            // Validate file type specific constraints
            if (!$this->validateFileType($file, $fileType)) {
                return;
            }

            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            
            // Determine storage path based on file type
            $storagePath = $fileType === ProductFile::TYPE_IMAGE 
                ? 'products/images' 
                : 'products/videos';
                
            // Store the file
            $path = $file->storeAs($storagePath, $filename, 'public');

            // Create database record
            ProductFile::create([
                'product_id' => $productId,
                'file' => $path,
                'file_type' => $fileType,
                'status' => ProductFile::STATUS_ACTIVE,
                'created_at' => now(),
                'created_by' => user()->id,
            ]);
            
            Log::info("Successfully uploaded file: {$path}");
            
        } catch (\Exception $e) {
            Log::error("Error processing file upload: " . $e->getMessage());
            session()->flash('error', 'Error uploading file: ' . $file->getClientOriginalName());
        }
    }

    private function determineFileType($mimeType)
    {
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $videoTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/quicktime', 'video/x-msvideo'];

        if (in_array($mimeType, $imageTypes)) {
            return ProductFile::TYPE_IMAGE;
        }

        if (in_array($mimeType, $videoTypes)) {
            return ProductFile::TYPE_REEL;
        }

        return null; // Unsupported file type
    }

    private function validateFileType(UploadedFile $file, $fileType)
    {
        if ($fileType === ProductFile::TYPE_IMAGE) {
            // Validate image file (max 10MB for images)
            if ($file->getSize() > 10 * 1024 * 1024) {
                session()->flash('error', 'Image files must not exceed 10MB: ' . $file->getClientOriginalName());
                return false;
            }
        } elseif ($fileType === ProductFile::TYPE_REEL) {
            // Validate video file (max 50MB for videos)
            if ($file->getSize() > 50 * 1024 * 1024) {
                session()->flash('error', 'Video files must not exceed 50MB: ' . $file->getClientOriginalName());
                return false;
            }
        }

        return true;
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

            // Combine all existing files (images and videos) with proper structure
            $this->existingFiles = collect($product->images)
                ->merge($product->reels)
                ->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'file' => $file->file,
                        'file_type' => $file->file_type,
                        'status' => $file->status,
                    ];
                })
                ->toArray();

            // Reset file-related arrays
            $this->files = [];
            $this->filesToDelete = [];

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

    public function removeNewFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files); // Re-index array
        }
    }

    public function removeExistingFile($fileId)
    {
        // Mark file for deletion
        if (!in_array($fileId, $this->filesToDelete)) {
            $this->filesToDelete[] = $fileId;
        }
        
        // Remove from existing files display
        $this->existingFiles = array_filter($this->existingFiles, function ($file) use ($fileId) {
            return $file['id'] != $fileId;
        });
        
        $this->existingFiles = array_values($this->existingFiles); // Re-index array
    }

    public function updatedFiles()
    {
        // Validate files when they are updated
        $this->validateOnly('files.*');
        
        // Optional: Validate total file count
        $totalFiles = count($this->files) + count($this->existingFiles);
        if ($totalFiles > 20) { // Adjust limit as needed
            session()->flash('error', 'Maximum 20 files allowed per product.');
            $this->files = [];
        }
    }

    public function getImageFiles()
    {
        return collect($this->existingFiles)->where('file_type', ProductFile::TYPE_IMAGE)->values();
    }

    public function getVideoFiles()
    {
        return collect($this->existingFiles)->where('file_type', ProductFile::TYPE_REEL)->values();
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