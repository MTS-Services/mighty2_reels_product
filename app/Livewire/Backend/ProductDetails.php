<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'ProductDetails', 'page_slug' => 'ProductDetails'])]
class ProductDetails extends Component
{
    public function render()
    {
        return view('livewire.backend.product-details');
    }
}
