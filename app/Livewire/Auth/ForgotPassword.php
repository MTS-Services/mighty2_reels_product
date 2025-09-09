<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
     * Generate and send OTP for password reset
     */
    public function sendPasswordResetOtp(): void
    {
        $this->validate();

        // Check if user exists and is active
        $user = User::where('email', $this->email)
                   ->where('status', User::STATUS_ACTIVE)
                   ->first();

        if (!$user) {
            session()->flash('status', 'If an active account exists with this email, an OTP will be sent.');
            return;
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate unique token for this reset session
        $otpToken = Str::random(60);

        // Update user with OTP and expiration (10 minutes from now)
        $user->update([
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(2),
            'otp_token' => $otpToken,
        ]);

        // Log the OTP instead of sending email (for development)
        Log::info("Password reset OTP for {$this->email}: {$otp} (Token: {$otpToken})");

        session()->flash('status', 'A 6-digit OTP has been logged. Check your application logs.');
        session()->put('reset_email', $this->email);
        session()->put('reset_token', $otpToken);
        
        // Redirect to OTP verification page
        $this->redirectRoute('password.verify-otp', navigate: true);
    }

    /**
     * Alternative method for production - actually send OTP via email/SMS
     * Uncomment this method and comment the above method when ready for production
     */
    /*
    public function sendPasswordResetOtp(): void
    {
        $this->validate();

        // Check if user exists and is active
        $user = User::where('email', $this->email)
                   ->where('status', User::STATUS_ACTIVE)
                   ->first();

        if (!$user) {
            session()->flash('status', 'If an active account exists with this email, an OTP will be sent.');
            return;
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate unique token for this reset session
        $otpToken = Str::random(60);

        // Update user with OTP and expiration (10 minutes from now)
        $user->update([
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'otp_token' => $otpToken,
        ]);

        // Send OTP via email (implement your email service here)
        // Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
        
        // Or send via SMS if phone number exists
        // if ($user->phone) {
        //     SmsService::send($user->phone, "Your password reset OTP is: {$otp}");
        // }

        session()->flash('status', 'A 6-digit OTP has been sent to your email address.');
        session()->put('reset_email', $this->email);
        session()->put('reset_token', $otpToken);
        
        // Redirect to OTP verification page
        $this->redirectRoute('password.verify-otp', navigate: true);
    }
    */

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}