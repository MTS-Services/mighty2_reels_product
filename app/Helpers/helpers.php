<?php

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
