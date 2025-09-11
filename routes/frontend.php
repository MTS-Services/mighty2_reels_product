<?php

use App\Livewire\Buttons;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\Product;
use App\Livewire\Frontend\User\UserProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/buttons', Buttons::class)->name('buttons');

Route::get('/product/{id?}', Product::class)->name('product');
