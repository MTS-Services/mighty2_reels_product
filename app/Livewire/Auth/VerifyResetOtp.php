<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Verify OTP', 'page_slug' => 'verify-reset-otp'])]
class VerifyResetOtp extends Component
{
    public string $otp = '';
    public string $email = '';
    public string $token = '';
    public int $resendCooldown = 0;

    public function mount(): void
    {
        // Get email and token from session
        $this->email = session('reset_email', '');
        $this->token = session('reset_token', '');

        // Redirect to forgot password if session data is missing
        if (empty($this->email) || empty($this->token)) {
            session()->flash('error', 'Invalid request. Please start the password reset process again.');
            $this->redirectRoute('password.request', navigate: true);
        }

        // Check if user exists
        $user = User::where('email', $this->email)
                   ->where('otp_token', $this->token)
                   ->first();

        if (!$user) {
            session()->flash('error', 'Invalid request. Please start the password reset process again.');
            $this->redirectRoute('password.request', navigate: true);
        }
    }

    protected function rules(): array
    {
        return [
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ];
    }

    protected function messages(): array
    {
        return [
            'otp.required' => 'Please enter the 6-digit OTP.',
            'otp.size' => 'OTP must be exactly 6 digits.',
            'otp.regex' => 'OTP must contain only numbers.',
        ];
    }

    public function verifyOtp(): void
    {
        $this->validate();

        $user = User::where('email', $this->email)
                   ->where('otp_token', $this->token)
                   ->first();

        if (!$user) {
            session()->flash('error', 'Invalid request. Please start the password reset process again.');
            $this->redirectRoute('password.request', navigate: true);
            return;
        }

        // Check if OTP exists and hasn't expired
        if (!$user->otp || !$user->expires_at) {
            $this->addError('otp', 'No OTP found. Please request a new one.');
            return;
        }

        // Check if OTP has expired
        if (Carbon::now()->isAfter($user->expires_at)) {
            $this->addError('otp', 'OTP has expired. Please request a new one.');
            return;
        }

        // Verify OTP
        if ($user->otp !== $this->otp) {
            $this->addError('otp', 'Invalid OTP. Please check and try again.');
            return;
        }

        // OTP is valid, generate reset token and redirect to reset password page
        $resetToken = Str::random(64);
        
        // Update user with reset token (keep OTP for now, will be cleared after password reset)
        $user->update([
            'otp_token' => $resetToken,
        ]);

        session()->flash('status', 'OTP verified successfully! You can now reset your password.');
        session()->forget(['reset_email', 'reset_token']);
        
        // Redirect to reset password page with token
        $this->redirectRoute('password.reset', ['token' => $resetToken], navigate: true);
    }

    public function resendOtp(): void
    {
        if ($this->resendCooldown > 0) {
            return;
        }

        $user = User::where('email', $this->email)
                   ->where('status', User::STATUS_ACTIVE)
                   ->first();

        if (!$user) {
            $this->addError('otp', 'User not found.');
            return;
        }

        // Generate new 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate new token for this reset session
        $otpToken = Str::random(60);

        // Update user with new OTP and expiration (10 minutes from now)
        $user->update([
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(2),
            'otp_token' => $otpToken,
        ]);

        // Update session with new token
        session()->put('reset_token', $otpToken);
        $this->token = $otpToken;

        // Log the new OTP (for development)
        Log::info("Resent password reset OTP for {$this->email}: {$otp} (Token: {$otpToken})");

        session()->flash('status', 'A new OTP has been sent to your email address.');
        
        // Set cooldown for 60 seconds
        $this->resendCooldown = 60;
        $this->dispatch('start-cooldown');
    }

    public function decrementCooldown(): void
    {
        if ($this->resendCooldown > 0) {
            $this->resendCooldown--;
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-reset-otp');
    }
}