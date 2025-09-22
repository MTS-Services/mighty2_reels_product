<?php

namespace App\Livewire\Backend;

use App\Models\Product;
use App\Models\ProductReel; // video model
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product Details', 'page_slug' => 'product-details'])]
class Details extends Component
{
    public $product;
    public $files; // ProductReel videos
    public $activeMedia; // image or video file
    public $activeType;  // 'image' or 'video'

    public function mount($productId)
    {
        $this->product = Product::with(['files' => function ($query) {
            $query->orderBy('is_primary', 'desc') // Primary files first
                ->orderBy('sort_order', 'asc');   // Then by sort order
        }])->findOrFail($productId);

        // Load files from the product
        $this->files = $this->product->files;

        // Set active media - prioritize primary file first
        $this->setInitialActiveMedia();
    }

    private function setInitialActiveMedia()
    {
        // First, check if there's a primary file (any type)
        $primaryFile = $this->files->where('is_primary', true)->first();

        if ($primaryFile) {
            $this->activeMedia = $primaryFile->file;
            $this->activeType = $primaryFile->file_type == \App\Models\ProductFile::TYPE_IMAGE ? 'image' : 'video';
            return;
        }

        // If no primary file, get the first file with sort_order = 0
        $firstSortedFile = $this->files->where('sort_order', 0)->first();

        if ($firstSortedFile) {
            $this->activeMedia = $firstSortedFile->file;
            $this->activeType = $firstSortedFile->file_type == \App\Models\ProductFile::TYPE_IMAGE ? 'image' : 'video';
            return;
        }

        // If no file with sort_order = 0, get the first available file
        if ($this->files->isNotEmpty()) {
            $firstFile = $this->files->first();
            $this->activeMedia = $firstFile->file;
            $this->activeType = $firstFile->file_type == \App\Models\ProductFile::TYPE_IMAGE ? 'image' : 'video';
            return;
        }

        // Fallback to placeholder
        $this->activeMedia = 'assets/images/placeholder.png';
        $this->activeType = 'image';
    }

    public function changeMedia($file, $type)
    {
        $this->activeMedia = $file;
        $this->activeType = $type;
    }

    public function render()
    {
        return view('livewire.backend.details');
    }
}