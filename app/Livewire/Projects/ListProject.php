<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class ListProject extends Component
{
    public function getProjectsProperty(): Collection
    {
        return Project::where('workspace_id', session('workspace_id'))->get();
    }

    public function render(): View
    {
        return view('livewire.projects.list');
    }
}
