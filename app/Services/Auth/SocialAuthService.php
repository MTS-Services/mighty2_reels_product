<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthService
{
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        $socialUser = Socialite::driver('google')->stateless()->user();

        // Debug info, remove after testing
        // dd($socialUser);

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'provider'          => $provider,
                'provider_id'       => $socialUser->getId(),
                'first_name'        => $socialUser->user['given_name'] ?? null,
                'last_name'         => $socialUser->user['family_name'] ?? null,
                'email_verified_at' => $socialUser->user['email_verified'] ? now() : null,
                'photo'             => $socialUser->getAvatar(),
                'password'          => bcrypt(Str::random(16)),
            ]
        );


        Auth::guard('web')->login($user, true);
        return $user;
    }
}
