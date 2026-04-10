<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class ShowWorkspace extends Component
{
    public Workspace $workspace;

    public function mount(Workspace $workspace): void
    {
        // Security check (extra safety)
        if (auth()->user()->id !== $workspace->owner_id) {
            abort(403);
        }

        $this->workspace = $workspace;

        // set current workspace in session
        session(['workspace_id' => $workspace->id]);
    }

    // Computed: Members
    public function getMembersProperty(): Collection
    {
        return $this->workspace->members()->withPivot('role')->get();
    }

    // Computed: Projects
    public function getProjectsProperty(): Collection
    {
        return Project::where('workspace_id', $this->workspace->id)->latest()->get();
    }

    // Computed: Stats
    public function getStatsProperty(): array
    {
        return [
            'members' => $this->workspace->members()->count(),
            'projects' => Project::where('workspace_id', $this->workspace->id)->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.workspaces.show');
    }
}
