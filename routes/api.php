<?php

use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');

// region Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', fn (Request $request) => $request->user());

    // region Questions
    Route::post('questions', Question\StoreController::class)->name('questions.store');
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.delete');
    Route::delete('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('questions/{question}/restore', Question\RestoreController::class)->name('questions.restore');
    Route::put('questions/{question}/publish', Question\PublishController::class)->name('questions.publish');
    // endregion
});
// endregion
