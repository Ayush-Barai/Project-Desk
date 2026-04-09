<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'workspace'])->group(function (): void {

    Route::livewire('/workspaces/create', 'workspaces.create')
        ->name('workspaces.create');

    Route::livewire('/workspaces/{workspace}', 'workspaces.show')
        ->name('workspaces.show');

    Route::livewire('/workspaces/{workspace}/members', 'workspaces.members')
        ->name('workspaces.members');
});

require __DIR__.'/auth.php';
