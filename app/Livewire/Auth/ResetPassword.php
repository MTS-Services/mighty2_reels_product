<?php


namespace App\Livewire\Auth;

use Livewire\Component;

class ResetPassword extends Component
{
    public $page_slug = 'reset-password'; // define the property

    public function render()
    {
        return view('livewire.auth.reset-password')
               ->layout('app', ['page_slug' => $this->page_slug]);
    }
}
