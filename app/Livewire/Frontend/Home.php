<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('app', ['title' => 'Home', 'page_slug' => 'home'])]

class Home extends Component
{
    public $message = '';
    public $formData = [
        'name' => '',
        'email' => '',
        'message' => ''
    ];

    public function saveData()
    {
        sleep(1); // Simulate processing time
        $this->message = 'Data saved successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function deleteItem()
    {
        sleep(1); // Simulate processing time
        $this->message = 'Item deleted successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function refreshPage()
    {
        sleep(1); // Simulate processing time
        $this->message = 'Page refreshed!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function submitForm()
    {
        $this->validate([
            'formData.name' => 'required|min:2',
            'formData.email' => 'required|email',
            'formData.message' => 'required|min:10',
        ]);

        sleep(1); // Simulate processing time

        $this->message = 'Form submitted successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');

        // Reset form
        $this->formData = ['name' => '', 'email' => '', 'message' => ''];
    }

    public function handleFormSubmit()
    {
        $this->submitForm();
    }

    public function cancelForm()
    {
        $this->formData = ['name' => '', 'email' => '', 'message' => ''];
        $this->message = 'Form cancelled.';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function logout()
    {
        // In real app: auth()->logout();
        $this->message = 'Logout action triggered!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function refreshData()
    {
        sleep(1);
        $this->message = 'Data refreshed!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function complexAction()
    {
        sleep(2); // Longer processing time
        $this->message = 'Complex action completed!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function slowAction()
    {
        sleep(2);
        $this->message = 'Slow action completed!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function uploadFile()
    {
        sleep(3); // Simulate file upload
        $this->message = 'File uploaded successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function saveChanges()
    {
        sleep(1);
        $this->message = 'Changes saved!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function addToCart()
    {
        sleep(1);
        $this->message = 'Item added to cart!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function buyNow()
    {
        sleep(1);
        $this->message = 'Redirecting to checkout...';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function addToWishlist()
    {
        sleep(1);
        $this->message = 'Added to wishlist!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function shareProduct()
    {
        $this->message = 'Share dialog opened!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function uploadFiles()
    {
        sleep(2);
        $this->message = 'Files uploaded successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function clearFiles()
    {
        $this->message = 'Files cleared!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function previewFiles()
    {
        $this->message = 'Preview opened!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function selectAll()
    {
        $this->message = 'All items selected!';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function deleteSelected()
    {
        sleep(1);
        $this->message = 'Selected items deleted!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function confirmDelete()
    {
        sleep(1);
        $this->message = 'Item deleted successfully!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function cancelDelete()
    {
        $this->message = 'Delete action cancelled.';
        $this->dispatch('show-toast', message: $this->message, type: 'info');
    }

    public function saveDraft()
    {
        sleep(1);
        $this->message = 'Draft saved!';
        $this->dispatch('show-toast', message: $this->message, type: 'success');
    }

    public function render()
    {
        return view('livewire.frontend.home');
    }
}
