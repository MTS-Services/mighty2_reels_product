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

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ];
    }

    protected function messages(): array
    {
        return [
            'email.exists' => 'This email address is not registered in our system.',
        ];
    }

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate();

        // Check if user exists and is active
        $user = User::where('email', $this->email)
                   ->where('status', User::STATUS_ACTIVE)
                   ->first();

        if (!$user) {
            session()->flash('status', 'If an active account exists with this email, a reset link will be sent.');
            return;
        }

        // Generate token
        $token = Password::createToken($user);

        if ($token) {
            // Create reset link
            $link = url(route('password.reset', [
                'token' => $token,
                'email' => $this->email,
            ], false));

            // Log the link instead of sending email (for development)
            Log::info("Password reset link for {$this->email}: {$link}");

            session()->flash('status', 'Password reset link has been logged. Check your application logs.');
            
            // Clear the email field after successful submission
            $this->reset('email');
        } else {
            session()->flash('status', 'If an active account exists with this email, a reset link will be sent.');
        }
    }

    /**
     * Alternative method for production - actually send emails
     * Uncomment this method and comment the above method when ready for production
     */
    /*
    public function sendPasswordResetLink(): void
    {
        $this->validate();

        // Check if user exists and is active
        $user = User::where('email', $this->email)
                   ->where('status', User::STATUS_ACTIVE)
                   ->first();

        if (!$user) {
            // Always show the same message for security
            session()->flash('status', 'If an active account exists with this email, a reset link will be sent.');
            return;
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'A password reset link has been sent to your email address.');
            $this->reset('email');
        } else {
            session()->flash('status', 'If an active account exists with this email, a reset link will be sent.');
        }
    }
    */

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}