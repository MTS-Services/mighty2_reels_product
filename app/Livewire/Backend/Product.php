<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'product'])]
class Product extends Component
{
    public function render()
    {
        return view('livewire.backend.product');
    }
}
