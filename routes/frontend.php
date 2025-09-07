<?php

use App\Livewire\Buttons;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/buttons', Buttons::class)->name('buttons');

Route::get('/product', Product::class)->name('product');
