<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\Project;
use App\Models\Workspace;
use Illuminate\View\View;
use Livewire\Component;

final class ShowWorkspace extends Component
{
    public Workspace $workspace;

    public function mount(Workspace $workspace): void
    {
        // Security check: User must be a member of the workspace
        $this->authorize('view', $workspace);

        $this->workspace = $workspace;

        // set current workspace in session
        session(['workspace_id' => $workspace->id]);
    }

    /**
     * Get all members of the workspace.
     * Includes the role from the pivot table.
     */
    public function getMembersProperty(): mixed
    {
        return $this->workspace->members()->withPivot('role')->get();
    }

    /**
     * Get all projects associated with the current workspace.
     * Results are ordered by most recent creation date.
     */
    public function getProjectsProperty(): mixed
    {
        return Project::query()->where('workspace_id', $this->workspace->id)->latest()->get();
    }

    /**
     * Get aggregate statistics for the workspace.
     * Returns counts of members and projects.
     *
     * @return array{members: int, projects: int}
     */
    public function getStatsProperty(): array
    {
        return [
            'members' => $this->workspace->members()->count(),
            'projects' => Project::query()->where('workspace_id', $this->workspace->id)->count(),
        ];
    }

    public function deleteWorkspace(int $id): mixed
    {
        $workspace = Workspace::query()->findOrFail($id);

        $this->authorize('delete', $workspace);

        $workspace->forceDelete();

        return to_route('workspaces.index');
    }

    public function render(): View
    {
        return view('livewire.workspaces.show');
    }
}
