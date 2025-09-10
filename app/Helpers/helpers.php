<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (!function_exists('site_name')) {
    function site_name()
    {
        return config('app.name', 'REEL SHOP');
    }
}
if (!function_exists('site_short_name')) {
    function site_short_name()
    {
        return config('app.short_name', 'RS');
    }
}

if (!function_exists('site_logo')) {
    function site_logo()
    {
        return config('app.logo', asset('assets/images/logo.svg'));
    }
}
if (!function_exists('favicon')) {
    function favicon()
    {
        return config('app.favicon', asset('assets/images/favicon.svg'));
    }
}

if (!function_exists('time_format')) {

    function time_format($time)
    {
        return Carbon::parse($time)->format('d M, Y ,h:i A');
    }
}

if (!function_exists('time_format_human')) {
    function time_format_human($time)
    {
        return Carbon::parse($time)->diffForHumans();
    }
}

if (!function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return Auth::user()->is_admin == User::IS_ADMIN;
    }
}
