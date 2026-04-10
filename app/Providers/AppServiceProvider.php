<?php

declare(strict_types=1);

namespace App\Providers;

use App\Livewire\Workspaces\ListWorkspaces;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('workspaces.index', ListWorkspaces::class);
    }

    public function register(): void
    {
        //
    }
}
