<?php 

namespace App\Livewire\Auth;

use Livewire\Component;

class VerifyEmails extends Component
{
    public $page_slug = 'verify-emails';


    public function render()
    {
        return view('livewire.auth.verify-emails')
            ->layout('app', ['page_slug' => $this->page_slug]);
    }
}



