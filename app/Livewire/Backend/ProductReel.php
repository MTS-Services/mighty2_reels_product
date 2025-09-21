<?php

namespace App\Livewire\Backend;

use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\ProductFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('app', ['title' => 'ProductReel', 'page_slug' => 'product-reel'])]
class ProductReel extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $showPrimaryModal = false; // New property for primary confirmation modal
    public $editMode = false;
    public $reelId;
    public $productId;
    public $file; // For newly uploaded files
    public $tempFile; // For existing file paths
    public $status = ProductFile::STATUS_ACTIVE;
    public $products;
    public $replacePrimary = false; // New property to track user's choice

    // Rules for validation, with the file rule being conditional
    protected $rules = [
        'productId' => 'required|exists:products,id',
        'file' => 'nullable|mimes:mp4,avi,mov|max:51200', // 50MB
        'status' => 'required|integer',
    ];

    public function mount()
    {
        $this->products = Product::all();
    }

    public function openModal()
    {
        // Reset all properties when opening the modal for a new reel
        $this->reset(['editMode', 'reelId', 'productId', 'file', 'tempFile', 'status', 'showPrimaryModal', 'replacePrimary']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        // Reset all properties when closing the modal
        $this->reset(['showModal', 'editMode', 'reelId', 'productId', 'file', 'tempFile', 'status', 'showPrimaryModal', 'replacePrimary']);
    }

    public function closePrimaryModal()
    {
        $this->showPrimaryModal = false;
        $this->replacePrimary = false;
    }

    public function saveReel()
    {
        // Specific validation for creating a new reel (file is required)
        $this->validate([
            'productId' => 'required|exists:products,id',
            'file' => 'required|mimes:mp4,avi,mov|max:51200',
            'status' => 'required|integer',
        ]);

        // Check if there's already a primary reel for this product
        $existingPrimary = ProductFile::where('product_id', $this->productId)
            ->where('file_type', ProductFile::TYPE_REEL)
            ->where('is_primary', true)
            ->first();

        if ($existingPrimary) {
            // Show confirmation modal
            $this->showPrimaryModal = true;
            return;
        }

        // No existing primary, proceed with creation
        $this->createReel(true); // Set as primary since no existing primary
    }

    public function confirmReplacePrimary()
    {
        $this->replacePrimary = true;
        $this->showPrimaryModal = false;
        $this->createReel(true); // Create as primary and update existing
    }

    public function cancelReplacePrimary()
    {
        $this->showPrimaryModal = false;
        $this->replacePrimary = false;
        // Don't create the reel, just close the modal
    }

    private function createReel($isPrimary = false)
    {
        // If replacing primary, update existing primary to false
        if ($this->replacePrimary) {
            ProductFile::where('product_id', $this->productId)
                ->where('file_type', ProductFile::TYPE_REEL)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        // Store the new file and get its path
        $path = $this->file->store('reels', 'public');

        ProductFile::create([
            'product_id' => $this->productId,
            'file' => $path,
            'status' => $this->status,
            'file_type' => ProductFile::TYPE_REEL,
            'is_primary' => $isPrimary,
        ]);

        session()->flash('success', 'Reel added successfully!');
        $this->closeModal();
    }

    public function editReel($id)
    {
        $reel = ProductFile::findOrFail($id);

        $this->editMode = true;
        $this->showModal = true;
        $this->reelId = $reel->id;
        $this->productId = $reel->product_id;
        $this->tempFile = $reel->file; // Store the file path of the existing reel
        $this->status = $reel->status;
        $this->file = null; // Ensure any previously selected new file is cleared
    }

    public function updateReel()
    {
        $rules = [
            'productId' => 'required|exists:products,id',
            'status' => 'required|integer',
            'file' => 'sometimes|required|mimes:mp4,avi,mov,m4v|max:51200',
        ];

        // Only validate the file if a new one is being uploaded
        if ($this->file) {
            $rules['file'] = 'required|mimes:mp4,avi,mov|max:51200';
        }

        $this->validate($rules);

        $reel = ProductFile::findOrFail($this->reelId);

        if ($this->file) {
            // If a new file is uploaded, delete the old one if it exists
            if ($reel->file) {
                Storage::disk('public')->delete($reel->file);
            }
            $reel->file = $this->file->store('reels', 'public');
            $this->tempFile = $reel->file; // Update tempFile to the new path
        } elseif (!$this->file && !$this->tempFile && $reel->file) {
            // If the user explicitly removed the video (no new file, no tempFile, but reel had a file)
            Storage::disk('public')->delete($reel->file);
            $reel->file = null;
            $this->tempFile = null; // Ensure tempFile is also cleared
        }

        $reel->product_id = $this->productId;
        $reel->status = $this->status;
        $reel->save();

        session()->flash('success', 'Reel updated successfully!');
        $this->closeModal();
    }

    public function deleteReel($id)
    {
        $reel = ProductFile::findOrFail($id);

        // Delete the file from storage if it exists
        if ($reel->file) {
            Storage::disk('public')->delete($reel->file);
        }

        $reel->forceDelete(); // Use forceDelete for permanent deletion

        session()->flash('success', 'Reel deleted successfully!');
    }

    public function removeVideo()
    {
        $this->file = null; // Clear the newly uploaded file
        // If we are in edit mode and were displaying an existing video, clear its path too
        if ($this->editMode && $this->tempFile) {
            $this->tempFile = null;
        }
    }

    public function render()
    {
        // Fetch only primary reels with their associated products, paginated
        $reels = ProductFile::with('product')
            ->where('file_type', ProductFile::TYPE_REEL)
            ->where('is_primary', true)
            ->paginate(10);

        return view('livewire.backend.product-reel', [
            'reels' => $reels,
        ]);
    }
}