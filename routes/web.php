<?php

use Illuminate\Support\Facades\Route;

/*
public sections:
*/
Route::get('/', App\Http\Controllers\WelcomeController::class)->name('home');

/*
authenticated sections:
*/
Route::get('/workspace', [App\Http\Controllers\WorkspaceController::class, 'index'])->name('workspace.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';
