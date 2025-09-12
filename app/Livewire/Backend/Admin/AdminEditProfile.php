<?php

namespace App\Livewire\Backend\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('app', ['title' => 'AdminEditProfile', 'page_slug' => 'AdminEditProfile'])]
class AdminEditProfile extends Component
{
     use WithFileUploads;

    public $first_name, $last_name, $email, $phone, $gender, $date_of_birth;
    public $street, $location, $post_code, $state;
    public $password, $password_confirmation;
    public $photo;

    public $genders = ['Male', 'Female', 'Other'];
    public $streets = ['Street A', 'Street B'];
    public $locations = ['Location 1', 'Location 2'];
    public $postCodes = ['1000', '2000'];
    public $states = ['State 1', 'State 2'];



public function mount()
{
    $user = Auth::user();
    $attributes = [
        'first_name'   => $user->first_name,
        'last_name'    => $user->last_name,
        'email'        => $user->email,
        'phone'        => $user->phone,
        'gender'       => $user->gender,
        'date_of_birth'=> $user->date_of_birth,
        'street'       => $user->street,
        'location'     => $user->location,
        'post_code'    => $user->post_code,
        'state'        => $user->state,
    ];
    foreach ($attributes as $key => $value) {
        $this->$key = $value;
    }
}

    public function updateProfile() {
        $data = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'street' => 'nullable|string',
            'location' => 'nullable|string',
            'post_code' => 'nullable|string',
            'state' => 'nullable|string',
            'password' => 'nullable|string|confirmed|min:6',
            'photo' => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        } else {
            unset($data['password']);
        }

        if ($this->photo) {
            $data['profile_photo_path'] = $this->photo->store('profile-photos','public');
        }

        foreach ($data as $key => $value) {
            $user->$key = $value;
        }


        session()->flash('success', 'Profile updated successfully!');

        return redirect()->route('profile');
    }
    public function render()
    {
        return view('livewire.backend.admin.admin-edit-profile');
    }
}
