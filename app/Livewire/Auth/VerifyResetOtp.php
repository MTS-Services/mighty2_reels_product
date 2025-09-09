<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\Auth\OtpRateLimitingService;
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
    public int $hourlyAttemptsRemaining = 500;
    public bool $canResend = true;
    public string $rateLimitMessage = '';

    protected OtpRateLimitingService $rateLimitService;

    public function boot(OtpRateLimitingService $rateLimitService): void
    {
        $this->rateLimitService = $rateLimitService;
    }

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

        // Initialize rate limit status
        $this->updateRateLimitStatus();
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

        if (!$user->otp || !$user->expires_at) {
            $this->addError('otp', 'No OTP found. Please request a new one.');
            return;
        }

        if (Carbon::now()->isAfter($user->expires_at)) {
            $this->addError('otp', 'OTP has expired. Please request a new one.');
            return;
        }

        if ($user->otp !== $this->otp) {
            $this->addError('otp', 'Invalid OTP. Please check and try again.');
            return;
        }

        $resetToken = Str::random(64);
        
        $user->update([
            'otp' => null,
            'expires_at' => null,
            'otp_token' => $resetToken,
        ]);

        // Clear rate limiting after successful verification
        $this->rateLimitService->clearRateLimit($this->email);

        session()->forget(['reset_email', 'reset_token']);
        session()->flash('status', 'OTP verified successfully! You can now reset your password.');
        
        $this->redirectRoute('password.reset', ['token' => $resetToken], navigate: true);
    }

    public function resendOtp(): void
    {
        // Check rate limiting
        if (!$this->rateLimitService->canResendOtp($this->email)) {
            $status = $this->rateLimitService->getRateLimitStatus($this->email);
            
            if ($status['is_in_cooldown']) {
                $this->rateLimitMessage = "Please wait {$status['cooldown_remaining']} seconds before requesting another code.";
            } elseif ($status['has_exceeded_hourly']) {
                $minutes = ceil($status['hourly_reset_in'] / 60);
                $this->rateLimitMessage = "You have reached your hourly limit. Try again in {$minutes} minutes.";
            }
            
            session()->flash('error', $this->rateLimitMessage);
            $this->updateRateLimitStatus();
            return;
        }

        $user = User::where('email', $this->email)
                    ->where('status', User::STATUS_ACTIVE)
                    ->first();

        if (!$user) {
            $this->addError('otp', 'User not found.');
            return;
        }

        // Record the attempt BEFORE sending
        $this->rateLimitService->recordAttempt($this->email);

        // Generate new 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate new token for this reset session
        $otpToken = Str::random(60);

        // Update user with new OTP and expiration (2 minutes from now)
        $user->update([
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(2),
            'otp_token' => $otpToken,
        ]);

        // Update session with new token
        session()->put('reset_token', $otpToken);
        $this->token = $otpToken;

        Log::info("Resent password reset OTP for {$this->email}: {$otp} (Token: {$otpToken})");

        session()->flash('status', 'A new OTP has been sent to your email address.');
        
        // Update rate limit status and dispatch cooldown event
        $this->updateRateLimitStatus();
        $this->dispatch('start-cooldown', cooldown: $this->resendCooldown);
    }

    /**
     * Update rate limit status from service
     */
    public function updateRateLimitStatus(): void
    {
        $status = $this->rateLimitService->getRateLimitStatus($this->email);
        
        $this->resendCooldown = $status['cooldown_remaining'];
        $this->hourlyAttemptsRemaining = $status['hourly_attempts_remaining'];
        $this->canResend = $status['can_resend'];
        
        // Clear rate limit message if user can resend
        if ($this->canResend) {
            $this->rateLimitMessage = '';
        }
    }

    /**
     * Get formatted rate limit info for display
     */
    public function getRateLimitInfo(): array
    {
        $status = $this->rateLimitService->getRateLimitStatus($this->email);
        
        $info = [
            'attempts_remaining' => $status['hourly_attempts_remaining'],
            'cooldown_remaining' => $status['cooldown_remaining'],
            'can_resend' => $status['can_resend'],
            'message' => ''
        ];
        
        if ($status['is_in_cooldown']) {
            $info['message'] = "Wait {$status['cooldown_remaining']}s to resend";
        } elseif ($status['has_exceeded_hourly']) {
            $minutes = ceil($status['hourly_reset_in'] / 60);
            $info['message'] = "Limit reached. Try again in {$minutes}m";
        } elseif ($status['hourly_attempts_remaining'] > 0) {
            $info['message'] = "{$status['hourly_attempts_remaining']} attempts remaining this hour";
        }
        
        return $info;
    }

    public function render()
    {
        $this->updateRateLimitStatus();
        return view('livewire.auth.verify-reset-otp');
    }
}