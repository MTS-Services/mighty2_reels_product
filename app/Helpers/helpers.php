<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        return Auth::user()?->is_admin == User::IS_ADMIN ? true : false;
    }
}

if (!function_exists('storage_url')) {
    function storage_url($urlOrArray)
    {
        $image = asset('assets/images/profile.jpg');
        if (is_array($urlOrArray) || is_object($urlOrArray)) {
            $result = '';
            $count = 0;
            $itemCount = count($urlOrArray);
            foreach ($urlOrArray as $index => $url) {

                $result .= $url ? (Str::startsWith($url, 'https://') ? $url : asset('storage/' . $url)) : $image;


                if ($count === $itemCount - 1) {
                    $result .= '';
                } else {
                    $result .= ', ';
                }
                $count++;
            }
            return $result;
        } else {
            return $urlOrArray ? (Str::startsWith($urlOrArray, 'https://') ? $urlOrArray : asset('storage/' . $urlOrArray)) : $image;
        }
    }
}
