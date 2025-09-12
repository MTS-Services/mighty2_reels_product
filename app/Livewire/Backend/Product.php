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
    public $images = [];
    public $videos;
    public $tempImages = [];
    public $tempVideos;

    protected $rules = [
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'price' => 'sometimes|required|numeric|min:0',
        'room' => 'sometimes|required|integer|min:1',
        'city_id' => 'sometimes|required|exists:cities,id',
        'type' => 'sometimes|required|in:0,1',
        'status' => 'sometimes|required|in:0,1',
        'images.*' => 'sometimes|required|image|mimes:jpg,jpeg,png|max:10240', // 10MB
        'videos' => 'sometimes|required|mimes:mp4,avi,mov|max:51200', // 50MB
    ];

    protected $messages = [
        'title.required' => 'Product title is required.',
        'price.required' => 'Price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'room.required' => 'Number of rooms is required.',
        'city_id.required' => 'City selection is required.',
        'images.*.image' => 'All uploaded files must be valid images.',
        'images.*.max' => 'Images must not exceed 10MB.',
        'videos.mimes' => 'The video must be in MP4, AVI, or MOV format.',
        'videos.max' => 'The video must not exceed 50MB.',
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
        $this->videos = null;
        $this->tempImages = [];
        $this->tempVideos = null;
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

            $this->handleImages($product->id);
            $this->handleVideo($product->id);

            $this->closeModal();
            session()->flash('success', $this->editMode ? 'Product updated successfully!' : 'Product created successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An error occurred while saving the product.');
        }
    }

    private function handleImages($productId)
    {
        // Delete removed existing images
        foreach ($this->tempImages as $key => $image) {
            if (!in_array($image['id'], array_column($this->tempImages, 'id'))) {
                Storage::disk('public')->delete($image['file']);
                ProductImage::find($image['id'])->delete();
            }
        }

        // Upload new images
        foreach ($this->images as $image) {
            if ($image instanceof \Illuminate\Http\UploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
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

    private function handleVideo($productId)
    {
        if (!$this->videos)
            return;

        // Delete existing video
        if ($this->editMode && $this->tempVideos) {
            Storage::disk('public')->delete($this->tempVideos['file']);
            ProductReel::find($this->tempVideos['id'])->delete();
        }

        // Upload new video
        if ($this->videos instanceof \Illuminate\Http\UploadedFile) {
            $filename = time() . '_' . uniqid() . '.' . $this->videos->getClientOriginalExtension();
            $path = $this->videos->storeAs('products/videos', $filename, 'public');

            ProductReel::create([
                'product_id' => $productId,
                'file' => $path,
                'status' => 1,
                'created_at' => now(),
                'created_by' => user()->id,
            ]);
        }
    }

    public function editProduct($productId)
    {
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

        $this->tempImages = $product->images->toArray();
        $this->tempVideos = $product->reels->first()?->toArray();

        $this->showModal = true;
    }

    public function deleteProduct($productId)
    {
        try {
            $product = ProductModel::with(['images', 'reels'])->findOrFail($productId);

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->file);
                $image->forceDelete();
            }

            foreach ($product->reels as $reel) {
                Storage::disk('public')->delete($reel->file);
                $reel->forceDelete();
            }

            $product->forceDelete();

            session()->flash('success', 'Product deleted successfully!');
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

        if (isset($this->tempImages[$index])) {
            $image = $this->tempImages[$index];
            Storage::disk('public')->delete($image['file']);
            ProductImage::find($image['id'])->delete();
            unset($this->tempImages[$index]);
            $this->tempImages = array_values($this->tempImages);
        }
    }

    public function removeVideo()
    {
        $this->videos = null;

        if ($this->editMode && $this->tempVideos) {
            Storage::disk('public')->delete($this->tempVideos['file']);
            ProductReel::find($this->tempVideos['id'])->delete();
            $this->tempVideos = null;
        }
    }

    public function updatedVideos()
    {
        $this->validateOnly('videos');
    }

    public function updatedImages()
    {
        $this->validateOnly('images.*');
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
