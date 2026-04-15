<?php

declare(strict_types=1);

namespace App\Providers;

use App\Livewire\Workspaces\ListWorkspaces;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Livewire::component('workspaces.list', ListWorkspaces::class);
    }

    public function register(): void {}
}
