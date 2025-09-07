<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Product', 'page_slug' => 'Product'])]
class Product extends Component
{
    

    public $showVideoDescription = true;
    public $favourite = false;
    public function toggleVideoDescription()
    {
        $this->showVideoDescription = !$this->showVideoDescription;
    }
    public function toggleFavourite()
    {
        $this->favourite = !$this->favourite;
    }
    public function render()
    {
        // dd($this->showVideoDescription);
        return view('livewire.frontend.product');
    }
}
