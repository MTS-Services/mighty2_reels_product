<?php

use App\Livewire\Backend\Dashboard;
use App\Livewire\Backend\ProductDetails;
use App\Livewire\Backend\Product;
use App\Livewire\Backend\Profile;
use App\Livewire\Backend\Reel;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->name('admin.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('products', Product::class)->name('products');
    Route::get('product/details', ProductDetails::class)->name('product.details');
    Route::get('reels', Reel::class)->name('reels');
    Route::get('profile', Profile::class)->name('profile');
});
