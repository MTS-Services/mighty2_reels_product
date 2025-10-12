<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app', ['title' => 'Register', 'page_slug' => 'register'])]
class Register extends Component
{
    public bool $nextStep = false;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $date_of_birth = '';
    public string $gender = '';
    public string $street = '';
    public string $status = '';
    public string $suburb = ''; 
    public string $postcode = '';
    public string $state = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function next(): void
    {
        $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'numeric', 'digits_between:5,15'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $this->nextStep = true;
    }
    
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed'],
            'phone' => ['nullable', 'numeric', 'digits_between:5,15'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:' . implode(',', array_keys(User::GENDERS))],
            'street' => ['nullable', 'string', 'max:255'],
            'suburb' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
        ]);
         
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        
        event(new Registered($user));

        // Generate and send OTP
        $user->generateOtp();
        
        // Store email in session for OTP verification
        session([
            'verification_email' => $user->email,
            'otp_sent' => true  // Flag to show success message
        ]);

        // Redirect to OTP verification page
        $this->redirect(route('verify.otp', absolute: false), navigate: true);
    }
}