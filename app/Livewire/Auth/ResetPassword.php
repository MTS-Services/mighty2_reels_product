<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
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
        
        // Validate token and get user
        if (empty($this->token)) {
            session()->flash('error', 'Invalid or expired reset link.');
            $this->redirectRoute('password.request', navigate: true);
            return;
        }

        // Find user with this OTP token
        $user = User::where('otp_token', $this->token)->first();
        
        if (!$user) {
            session()->flash('error', 'Invalid or expired reset link.');
            $this->redirectRoute('password.request', navigate: true);
            return;
        }

        // Check if OTP has expired (optional - you might want to allow password reset even if OTP expired after verification)
        // if ($user->expires_at && Carbon::now()->isAfter($user->expires_at)) {
        //     session()->flash('error', 'Reset link has expired. Please request a new one.');
        //     $this->redirectRoute('password.request', navigate: true);
        //     return;
        // }

        $this->email = $user->email;
    }

    protected function rules(): array
    {
        return [
            'token' => ['required', 'string'],
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
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function resetPassword(): void
    {
        $this->validate();

        // Find user with this token
        $user = User::where('otp_token', $this->token)->first();

        if (!$user) {
            $this->addError('token', 'Invalid or expired reset link.');
            return;
        }

        // Update user password and clear OTP data
        $user->forceFill([
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
            'otp' => null,
            'expires_at' => null,
            'otp_token' => null,
            'updated_by' => null, // Since this is a password reset, no authenticated user
        ])->save();

        // Fire the password reset event
        event(new PasswordReset($user));

        session()->flash('status', 'Your password has been reset successfully! You can now login with your new password.');
        
        // Clear the form
        $this->reset(['password', 'password_confirmation']);
        
        // Redirect to login page
        $this->redirectRoute('login', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}