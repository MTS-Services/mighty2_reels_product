<?php

use App\Livewire\Backend\Admin\AdminEditProfile;
use App\Livewire\Backend\Area;
use App\Livewire\Backend\Dashboard;
use App\Livewire\Backend\ProductDetails;
use App\Livewire\Backend\Product;
use App\Livewire\Backend\Details;
use App\Livewire\Backend\ProductReel;
use App\Livewire\Backend\Reel;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('products', Product::class)->name('products');
    Route::get('product/details', ProductDetails::class)->name('product.details');
    Route::get('reels', ProductReel::class)->name('product.reels');
    Route::get('details', Details::class)->name('details');
    // Route::get('reels', Reel::class)->name('reels');
    Route::get('area', Area::class)->name('area');
    Route::get('/admin-profile', AdminEditProfile::class)->name('profile');
});

