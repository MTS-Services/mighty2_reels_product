<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Forgot Password', 'page_slug' => 'forgot-password'])]
class ForgotPassword extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    // public function sendPasswordResetLink(): void
    // {
    //     $this->validate([
    //         'email' => ['required', 'string', 'email'],
    //     ]);

    //     Password::sendResetLink($this->only('email'));

    //     session()->flash('status', __('A reset link will be sent if the account exists.'));
    // }

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Generate token
        $token = Password::createToken(User::where('email', $this->email)->first());

        if ($token) {
            // Create reset link
            $link = url(route('password.reset', [
                'token' => $token,
                'email' => $this->email,
            ], false));

            // Log the link instead of sending email
            Log::info("Password reset link for {$this->email}: {$link}");

            session()->flash('status', __('Password reset link has been logged.'));
        } else {
            session()->flash('status', __('No account found with this email.'));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
