<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'layouts.dashboard.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'layouts.profile.profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
