<?php

namespace App\Livewire\Backend;

use App\Models\Product as ProductModel;
use App\Models\ProductImage;
use App\Models\ProductReel;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'product'])]
class Product extends Component
{
    use WithFileUploads, WithPagination;

    // Modal state
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
    public $images = [];
    public $videos = [];
    public $tempImages = []; 
    public $tempVideos = []; 

    // Validation rules
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'room' => 'required|integer|min:1',
        'city_id' => 'required|exists:cities,id',
        'type' => 'required|in:0,1',
        'status' => 'required|in:0,1',
        'images.*' => 'nullable|image|max:10240', // 10MB max (10240 KB)
        'videos.*' => 'nullable|mimes:mp4,avi,mov|mimetypes:video/mp4,video/x-m4v,video/avi,video/quicktime|max:51200',
    ];

    // Custom messages for validation errors
    protected $messages = [
        'title.required' => 'Product title is required.',
        'price.required' => 'Price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'room.required' => 'Number of rooms is required.',
        'city_id.required' => 'City selection is required.',
        'images.*.image' => 'All uploaded files must be valid images.',
        'images.*.max' => 'Images must not exceed 10MB.', 
        'videos.*.mimes' => 'Videos must be in MP4, AVI, or MOV format.',
        'videos.*.mimetypes' => 'The video file type is not allowed.', 
        'videos.*.max' => 'Videos must not exceed 50MB.', 
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
        $this->images = []; 
        $this->videos = []; 
        $this->tempImages = []; 
        $this->tempVideos = []; 
    }

    public function saveProduct()
    {
        // Validate all fields, including temporary file uploads
        $this->validate();

        try {
            $productData = [
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->price,
                'room' => $this->room,
                'city_id' => $this->city_id,
                'type' => $this->type,
                'status' => $this->status,
                'created_at' => now(),
                'created_by' => user()->id, // Assuming 'user()' is a helper function to get the current user
            ];

            if ($this->editMode && $this->productId) {
                $product = ProductModel::findOrFail($this->productId);
                $product->update(array_merge($productData, [
                    'updated_at' => now(),
                    'updated_by' => user()->id
                ]));
                $productId = $product->id; // Store for image/video association
            } else {
                $product = ProductModel::create($productData);
                $productId = $product->id; // Store for image/video association
            }

            // Handle image uploads
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    // Ensure the file is a temporary uploaded file instance before storing
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        // Store in 'public/products/images' and make it accessible via public disk
                        $path = $image->storeAs('products/images', $filename, 'public');

                        ProductImage::create([
                            'product_id' => $productId,
                            'file' => $path,
                            'status' => 1,
                            'created_at' => now(),
                            'created_by' => user()->id,
                        ]);
                    }
                }
            }

            // Handle video uploads
            if (!empty($this->videos)) {
                foreach ($this->videos as $video) {
                    // Ensure the file is a temporary uploaded file instance before storing
                    if ($video instanceof \Illuminate\Http\UploadedFile) {
                        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                        // Store in 'public/products/videos' and make it accessible via public disk
                        $path = $video->storeAs('products/videos', $filename, 'public');

                        ProductReel::create([
                            'product_id' => $productId,
                            'file' => $path,
                            'status' => 1,
                            'created_at' => now(),
                            'created_by' => user()->id,
                        ]);
                    }
                }
            }

            $this->closeModal();
            session()->flash('success', $this->editMode ? 'Product updated successfully!' : 'Product created successfully!');

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error saving product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while saving the product. Please try again.');
        }
    }

    public function editProduct($productId)
    {
        $product = ProductModel::findOrFail($productId);

        $this->editMode = true;
        $this->productId = $productId;
        $this->title = $product->title;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->room = $product->room;
        $this->city_id = $product->city_id;
        $this->type = $product->type;
        $this->status = $product->status;

        $this->showModal = true;
    }

    public function deleteProduct($productId)
    {
        try {
            $product = ProductModel::findOrFail($productId);
            $product->delete(); 

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->file);
                $image->delete(); 
            }
            foreach ($product->reels as $reel) {
                Storage::disk('public')->delete($reel->file);
                $reel->delete(); 
            }


            session()->flash('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while deleting the product.');
        }
    }

    public function updatedVideos()
    {
        // Debug video uploads
        foreach ($this->videos as $index => $video) {
            Log::info('Video upload debug:', [
                'index' => $index,
                'original_name' => $video->getClientOriginalName(),
                'size' => $video->getSize(),
                'mime_type' => $video->getMimeType(),
                'extension' => $video->getClientOriginalExtension(),
            ]);
        }

        $this->validateOnly('videos.*');
    }

    public function updatedImages()
    {
        // Validate only the 'images.*' property
        $this->validateOnly('images.*');
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images); // Re-index the array
    }

    public function removeVideo($index)
    {
        unset($this->videos[$index]);
        $this->videos = array_values($this->videos); // Re-index the array
    }

    public function render()
    {
        $products = ProductModel::query()
            ->with(['city', 'images', 'reels'])
            ->orderBy('created_at', 'desc')
            ->paginate(6); 

        $cities = City::where('status', 1)->orderBy('name')->get(); 

        return view('livewire.backend.product', [
            'products' => $products,
            'cities' => $cities
        ]);
    }
}