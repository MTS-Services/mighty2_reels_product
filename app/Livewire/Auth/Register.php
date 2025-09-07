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

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        dd('register');
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'max:255'],
            'gender' => ['nullable', '', 'in:'.exlode(',', User::GENDERS->keys())],
            'street' => ['nullable', 'string', 'max:255'],
            'suburb' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('admin.products', absolute: false), navigate: true);
    }
}



