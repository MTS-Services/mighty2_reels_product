<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    

    public $productDetails = true;
    public $favourite = false;
    public function toggleVideoDescription()
    {
        $this->productDetails = !$this->productDetails;
    }
    public function toggleFavourite()
    {
        $this->favourite = !$this->favourite;
    }
    public function render()
    {
        // dd($this->productDetails);
        return view('livewire.frontend.product');
    }
}
