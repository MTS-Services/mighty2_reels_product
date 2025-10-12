<?php 

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('app', ['title' => 'Verify Emails', 'page_slug' => 'verify-emails'])]
class VerifyEmails extends Component
{

    public function render()
    {
        return view('livewire.auth.verify-emails');
    }
}



