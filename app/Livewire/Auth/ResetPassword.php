<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Reset Password', 'page_slug' => 'reset-password'])]
class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount($token = null): void
    {
        // Get token from route parameter
        $this->token = $token ?? request()->route('token') ?? '';
        
        // Get email from query parameter
        $this->email = request()->get('email', '');
        
        // Redirect to login if token or email is missing
        if (empty($this->token) || empty($this->email)) {
            session()->flash('error', 'Invalid or expired reset link.');
            $this->redirectRoute('login', navigate: true);
        }
    }

    protected function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'password_confirmation' => 'password confirmation',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.exists' => 'This email address is not registered in our system.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function resetPassword(): void
    {
        $this->validate();

        // Attempt to reset the user's password
        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'updated_by' => null, // Since this is a password reset, no authenticated user
                ])->save();

                // Fire the password reset event
                event(new PasswordReset($user));
            }
        );

        // Handle the response
        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', 'Your password has been reset successfully! You can now login with your new password.');
            
            // Clear the form
            $this->reset(['password', 'password_confirmation']);
            
            // Redirect to login page
            $this->redirectRoute('login', navigate: true);
        } else {
            // Handle different error cases
            match($status) {
                Password::INVALID_TOKEN => $this->addError('token', 'This password reset link is invalid or has expired.'),
                Password::INVALID_USER => $this->addError('email', 'We cannot find a user with that email address.'),
                Password::THROTTLED => $this->addError('email', 'Please wait before retrying.'),
                default => $this->addError('email', 'An error occurred while resetting your password. Please try again.')
            };
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}