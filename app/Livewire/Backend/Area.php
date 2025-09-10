<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('app', ['title' => 'Area', 'page_slug' => 'Area'])]
class Area extends Component
{
    

    public $showModal = false;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function createArea()
    {
        $this->validate();

        // In a real application, you would save this to a database
        // Area::create(['name' => $this->name]);

        // Reset form fields and close modal
        $this->reset(['name']);
        $this->showModal = false;

        // Emit an event to refresh the area list if needed
        $this->emit('areaAdded');
    }

    public function render()
    {
        return view('livewire.backend.area');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}
