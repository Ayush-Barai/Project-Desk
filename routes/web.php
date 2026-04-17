<?php

declare(strict_types=1);

use App\Http\Controllers\ProjectController;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\Settings;
use App\Livewire\Projects\ShowProject;
use App\Livewire\Projects\AddMember;
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

Route::middleware(['auth' ])
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

Route::middleware('auth')
    ->prefix('/projects')
    ->name('projects.')
    ->group(function (): void {

        Route::get('', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('setting');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::patch('/{project}/archive', [ProjectController::class, 'archive'])->name('archive');
        Route::patch('/{project}/restore', [ProjectController::class, 'restore'])->name('restore');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');

        Route::get('/{project}/add-member', AddMember::class)
            ->name('add-member');
    });



require __DIR__.'/auth.php';
