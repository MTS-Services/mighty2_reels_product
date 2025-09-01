<?php

use App\Livewire\Backend\Product;
use App\Livewire\Backend\Profile;
use App\Livewire\Backend\Reel;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->name('admin.')->group(function () {
    Route::get('products', Product::class)->name('products');
    Route::get('reels', Reel::class)->name('reels');
    Route::get('profile', Profile::class)->name('profile');
});
