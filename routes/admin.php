<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Livewire\Backend\Area;
use App\Livewire\Backend\ProductDetails;
use App\Livewire\Backend\Product;
use App\Livewire\Backend\Details;
use App\Livewire\Backend\Favorites;
use App\Livewire\Backend\ProductReel;
use App\Livewire\Backend\Profile;
use Illuminate\Support\Facades\Route;


Route::get('auth/redirect/{provider}', [SocialAuthController::class, 'redirect'])
    ->where('provider', 'google|facebook')
    ->name('auth.social.redirect');

Route::get('auth/callback/{provider}', [SocialAuthController::class, 'callback'])
    ->where('provider', 'google|facebook')
    ->name('auth.social.callback');
Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('products', Product::class)->name('products');
    Route::get('product/details', ProductDetails::class)->name('product.details');
    Route::get('reels', ProductReel::class)->name('product.reels');
    Route::get('details', Details::class)->name('details');
    Route::get('area', Area::class)->name('area');
});
Route::get('favorites', Favorites::class)->name('favorites')->middleware('auth');
Route::get('/profile', Profile::class)->name('profile')->middleware('auth');
