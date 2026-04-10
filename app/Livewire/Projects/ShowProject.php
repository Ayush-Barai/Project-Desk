<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\View\View;
use Livewire\Component;

final class ShowProject extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        // Ensure project belongs to current workspace
        abort_if($project->workspace_id !== session('workspace_id'), 403);

        $this->project = $project;
    }

    public function render(): View
    {
        return view('livewire.projects.show');
    }
}
