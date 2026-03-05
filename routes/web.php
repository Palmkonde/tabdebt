<?php

use Illuminate\Support\Facades\Route;

/*
public sections:
*/
Route::get('/', App\Http\Controllers\WelcomeController::class)->name('home');

/*
authenticated sections:
*/
Route::middleware('auth')->group(function () {
    Route::get('/workspace', [App\Http\Controllers\WorkspaceController::class, 'index'])->name('workspace.index');

    Route::resource('/websites', App\Http\Controllers\WebsiteController::class);
});

require __DIR__.'/auth.php';
