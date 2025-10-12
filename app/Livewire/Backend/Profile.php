<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('app', ['title' => 'Profile', 'page_slug' => 'profile'])]
class Profile extends Component
{
    use WithFileUploads;

    public $first_name, $last_name, $email, $phone, $gender, $date_of_birth;
    public $street, $suburb, $postcode, $state;
    public $password, $password_confirmation;
    public $photo;

    public function mount()
    {
        $user = Auth::user();
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->gender = $user->gender;
        $this->date_of_birth = $user->date_of_birth;
        $this->street = $user->street;
        $this->suburb = $user->suburb;
        $this->postcode = $user->postcode;
        $this->state = $user->state;
    }

    public function updateProfile()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'street' => 'nullable|string',
            'suburb' => 'nullable|string',
            'postcode' => 'nullable|string',
            'state' => 'nullable|string',
            'password' => 'nullable|string|confirmed|min:6',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        // Handle the photo upload
        if ($this->photo) {
            // Delete the old photo if it exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            // Store new photo
            $filename = time() . '_' . uniqid() . '.' . $this->photo->getClientOriginalExtension();
            $user->photo = $this->photo->storeAs('profiles', $filename, 'public');
        }

        // Update all other fields
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->gender = $this->gender;
        $user->date_of_birth = $this->date_of_birth;
        $user->street = $this->street;
        $user->suburb = $this->suburb;
        $user->postcode = $this->postcode;
        $user->state = $this->state;

        // Handle password update
        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

          if ($user instanceof User) {
            $user->save();
        }
        
        session()->flash('success', 'Profile updated successfully!');
    }

    protected $listeners = ['logout-requested' => 'logout'];
    
    public function logout()
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.profile');
    }
}