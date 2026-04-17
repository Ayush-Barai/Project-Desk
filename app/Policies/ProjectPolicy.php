<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

final class ProjectPolicy
{
    // View projects list
    public function viewAny(User $user): bool
    {
        $workspaceId = session('workspace_id');

        return $user->workspaces()
            ->where('workspace_id', $workspaceId)
            ->exists();
    }

    // View project (must be project member)
    public function view(User $user, Project $project): bool
    {
        return $project->members()
            ->where('user_id', $user->id)
            ->exists();
    }

    // Create project (must be workspace member)
    public function create(User $user): bool
    {
        $workspaceId = session('workspace_id');

        return $user->workspaces()
            ->where('workspace_id', $workspaceId)
            ->exists();
    }

    // Update project (only Project Manager)
    public function update(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Manage settings (same as update)
    public function manage(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Archive project
    public function delete(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Restore project
    public function restore(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Force delete
    public function forceDelete(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Manage team (add/remove/change role)
    public function manageTeam(User $user, Project $project): bool
    {
        return $this->isManager($user, $project);
    }

    // Helper method
    private function isManager(User $user, Project $project): bool
    {
        return $project->members()
            ->where('user_id', $user->id)
            ->where('role', 'Project Manager')
            ->exists();
    }
}
