<?php

declare(strict_types=1);

use App\Livewire\Workspaces\CreateWorkspace;
use App\Livewire\Workspaces\ListWorkspaces;
use App\Livewire\Workspaces\Members;
use App\Livewire\Workspaces\ShowWorkspace;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])
    ->name('workspaces.')
    ->group(function (): void {

        Route::get('/workspaces', ListWorkspaces::class)
            ->name('index');

        Route::get('/workspaces/create', CreateWorkspace::class)
            ->name('create');

        Route::get('/workspaces/{workspace}', ShowWorkspace::class)
            ->name('show');

        Route::get('/workspaces/{workspace}/members', Members::class)
            ->name('members');
    });

require __DIR__.'/auth.php';
