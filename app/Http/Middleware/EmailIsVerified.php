<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasVerifiedEmail()) {
            session(['verification_email' => $request->user()->email]);
            
            // Generate OTP if not already generated
            if (!$request->user()->otp || $request->user()->isOtpExpired()) {
                $request->user()->generateOtp();
            }
            
            return redirect()->route('verify.otp');
        }

        return $next($request);
    }
}
