<?php

use App\Http\Controllers\AccesoController;
use Illuminate\Support\Facades\Route;

/*Route::resource('/', AccesoController::class)
->only('index', 'store');*/

Route::get('/', \App\Livewire\HomePage::class);


Route::resource('/teclado', AccesoController::class);


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
