<?php

declare(strict_types=1);

use App\Http\Controllers\ProjectController;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\Settings;
use App\Livewire\Projects\ShowProject;
use App\Livewire\Projects\Team;
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

Route::middleware('auth')
    ->name('projects.')
    ->group(function (): void {

        // Route::get('/projects', ListProject::class)
        //     ->name('index');

        // Route::get('/projects/create', CreateProject::class)
        //     ->name('create');

        // Route::get('/projects/{project}', ShowProject::class)
        //     ->name('show');

        Route::get('/projects/{project}/team', Team::class)
            ->name('team');

        // Route::get('/projects/{project}/setting', Settings::class)
        //     ->name('setting');
    });

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.setting');
Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::patch('/projects/{project}/archive', [ProjectController::class, 'archive'])->name('projects.archive');
Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

require __DIR__.'/auth.php';
