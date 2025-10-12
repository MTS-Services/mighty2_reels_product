<?php 

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Verify OTP', 'page_slug' => 'verify-otp'])]
class VerifyOtp extends Component
{
    public string $otp = '';
    public string $email = '';
    public int $resendCount = 0;
    public int $maxResendAttempts = 3;

    public function mount()
    {
        $this->email = session('verification_email', '');

        if (empty($this->email)) {
            $this->redirect(route('register'), navigate: true);
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Initialize resend count from session with email-specific key
        $resendKey = 'otp_resend_count_' . $this->email;
        $this->resendCount = session($resendKey, 0);

        // Show success message only if coming from registration
        if (session()->has('otp_sent')) {
            session()->flash('success', 'OTP has been sent to your email successfully!');
            session()->forget('otp_sent');
        }
    }

    public function verify(): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        if ($user->verifyOtp($this->otp)) {
            // Clear resend count on successful verification
            $resendKey = 'otp_resend_count_' . $this->email;
            session()->forget($resendKey);
            session()->forget('verification_email');

            // Log the user in after successful verification
            Auth::login($user);

            session()->flash('verified', 'Email verified successfully!');
            $this->redirect(route('admin.products', absolute: false), navigate: true);
        } else {
            // Check if OTP is expired or invalid
            if ($user->isOtpExpired()) {
                session()->flash('error', 'OTP has expired. Please click "Resend Code" to get a new one.');
            } else {
                session()->flash('error', 'Invalid OTP code. Please check and try again.');
            }
        }
    }

    public function resendOtp(): void
    {
        // Check if max attempts reached
        if ($this->resendCount >= $this->maxResendAttempts) {
            session()->flash('error', 'Maximum resend attempts reached. Please wait 1 hour or contact support.');
            return;
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Generate new OTP
            $user->generateOtp();
            
            // Increment resend count
            $this->resendCount++;
            $resendKey = 'otp_resend_count_' . $this->email;
            session()->put($resendKey, $this->resendCount);
            
            // Set flash message
            $remainingAttempts = $this->maxResendAttempts - $this->resendCount;
            session()->flash('success', "New OTP has been sent to your email! You have {$remainingAttempts} resend(s) remaining.");
            
            // Reset OTP input
            $this->reset('otp');
            
            // Dispatch event to reset countdown timer and clear inputs
            $this->dispatch('otp-resent');
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-otp');
    }
}