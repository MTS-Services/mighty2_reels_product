<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('app', ['title' => 'Login', 'page_slug' => 'login'])]
class Login extends Component
{
    #[Validate('required|string|email|exists:users,email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'These credentials do not match our records.');
            return;
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            session(['verification_email' => $user->email]);
            $user->generateOtp();
            session()->flash('error', 'Please verify your email first. We have sent you a new OTP.');
            $this->redirect(route('verify.otp'), navigate: true);
            return;
        }

        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            $this->addError('email', 'These credentials do not match our records.');
            return;
        }

        session()->regenerate();
        $this->redirect(route('admin.products', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
