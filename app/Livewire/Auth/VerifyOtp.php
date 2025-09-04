<?php 

namespace App\Livewire\Auth;

use Livewire\Component;

class VerifyOtp extends Component
{
    public $page_slug = 'verify-otp';
    public function render()
    {
        return view('livewire.auth.verify-otp')
        ->layout('app', ['page_slug' => $this->page_slug]);
    }
}