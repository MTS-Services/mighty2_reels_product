<?php

namespace App\Livewire\Backend;

use App\Models\City;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('app', ['title' => 'Area', 'page_slug' => 'Area'])]
class Area extends Component
{
    public $showModal = false;
    public $name;
    public $status = 1;
    public $cityId = null;
    public $showDetailsModal = false;
    public $createdAt;
    public $createdBy;
    public $updatedAt;
    public $updatedBy;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    protected $listeners = ['areaAdded' => '$refresh'];

    public function createArea()
    {
        $this->validate();

        City::create([
            'name'       => $this->name,
            'status'     => $this->status,
            'created_at' => now(),
            'created_by' => user()->id,
        ]);

        $this->reset(['name', 'status', 'cityId']);
        $this->showModal = false;

        $this->dispatch('areaAdded');
        session()->flash('success', 'Area added successfully!');
    }

    public function edit($encryptedId)
    {
        $id = decrypt($encryptedId);
        $city = City::findOrFail($id);

        $this->cityId = $city->id;
        $this->name = $city->name;
        $this->showModal = true;
    }

    public function updateArea()
    {
        $this->validate();

        $city = City::findOrFail($this->cityId);
        $city->update([
            'name'       => $this->name,
            'updated_at' => now(),
            'updated_by' => user()->id,
        ]);

        $this->reset(['name', 'cityId']);
        $this->showModal = false;

        session()->flash('success', 'Area updated successfully!');
    }

    public function delete($encryptedId)
    {
        $id = decrypt($encryptedId);
        City::findOrFail($id)->forceDelete();
        session()->flash('success', 'Area deleted successfully!');
    }

    public function details($encryptedId)
    {
        $id = decrypt($encryptedId);
        $city = City::findOrFail($id);

        $this->cityId = $city->id;
        $this->name = $city->name;
        $this->status = $city->status;
        $this->createdAt = $city->created_at;
        $this->createdBy = $city->createdBy->full_name ?? 'N/A';
        $this->updatedAt = $city->updated_at;
        $this->updatedBy = $city->updatedBy->full_name ?? 'N/A';

        $this->showDetailsModal = true;
    }

    public function toggleStatus($encryptedId)
    {
        $id = decrypt($encryptedId);
        $city = City::findOrFail($id);
        $city->update(['status' => !$city->status]);
    }

    public function closeModal()
    {
        $this->reset(['name', 'status', 'cityId']);
        $this->showModal = false;
    }

    public function closeDetailsModal()
    {
        $this->reset(['name', 'status', 'cityId']);
        $this->showDetailsModal = false;
    }

    public function render()
    {
        return view('livewire.backend.area', [
            'cities' => City::latest()->get(),
        ]);
    }
}
