<?php

use App\Http\Controllers\Auth\{LoginController, LogoutController, RegisterController};

Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

Route::post('logout', LogoutController::class)
    ->middleware(['auth'])
    ->name('logout');
