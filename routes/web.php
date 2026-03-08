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

    Route::resource('/websites', App\Http\Controllers\WebsiteController::class)->except(['show']);

    Route::resource('/groups', App\Http\Controllers\GroupController::class)->except(['show']);
    Route::delete('/groups/{group}/websites/{website}', [App\Http\Controllers\GroupController::class, 'removeWebsite'])->name('groups.websites.remove');

    Route::resource('/tags', App\Http\Controllers\TagController::class);
});

require __DIR__.'/auth.php';
