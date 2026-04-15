<?php

declare(strict_types=1);

use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ListProject;
use App\Livewire\Projects\Settings;
use App\Livewire\Projects\ShowProject;
use App\Livewire\Projects\Team;
use App\Livewire\Tasks\CreateTask;
use App\Livewire\Tasks\ListTasks;
use App\Livewire\Tasks\ShowTask;
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

        Route::get('/projects', ListProject::class)
            ->name('index');

        Route::get('/projects/create', CreateProject::class)
            ->name('create');

        Route::get('/projects/{project}', ShowProject::class)
            ->name('show');

        Route::get('/projects/{project}/team', Team::class)
            ->name('team');

        Route::get('/projects/{project}/setting', Settings::class)
            ->name('setting');

    });

Route::get('/{project}/tasks', ListTasks::class)->name('task.list');
Route::get('/{project}/task', CreateTask::class)->name('task.create');
Route::get('/{project}/task/{task}', ShowTask::class)->name('task.show');

require __DIR__.'/auth.php';
