<?php

use App\Http\Controllers\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', function () {
    return User::all();
});

// region Authenticated
Route::middleware('auth:sanctum')->group(function () {
    // region Questions
    Route::post('questions', Question\StoreController::class)->name('questions.store');
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.delete');
    Route::delete('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('questions/{question}/restore', Question\RestoreController::class)->name('questions.restore');

    // endregion
});
// endregion
