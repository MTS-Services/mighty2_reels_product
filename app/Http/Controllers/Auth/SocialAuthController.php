<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;

class SocialAuthController extends Controller
{
    public function __construct(private SocialAuthService $socialAuthService) {}

    public function redirect(string $provider): RedirectResponse
    {
        return $this->socialAuthService->redirectToProvider($provider);
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->socialAuthService->handleProviderCallback($provider);

        return redirect()->route('home');
    }
}
