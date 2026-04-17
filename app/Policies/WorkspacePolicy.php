<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class WorkspacePolicy
{
    /**
     * Any member can view the workspace
     */
    public function view(User $user, Workspace $workspace): bool
    {
        return $workspace->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Only owner can update workspace settings
     */
    public function update(User $user, Workspace $workspace): bool
    {
        return $this->getUserRole($user, $workspace) === WorkspaceRole::Owner;
    }

    /**
     * Only owner can delete workspace
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        return $this->getUserRole($user, $workspace) === WorkspaceRole::Owner;
    }

    /**
     * Owner and Admin can manage members
     */
    public function manageMembers(User $user, Workspace $workspace): bool
    {
        $role = $this->getUserRole($user, $workspace);

        return $role?->isAdmin() ?? false;
    }

    /**
     * Only owner can assign roles
     */
    public function assignRole(User $user, Workspace $workspace): bool
    {
        return $this->getUserRole($user, $workspace) === WorkspaceRole::Owner;
    }

    /**
     * Any member can view projects
     */
    public function viewProjects(User $user, Workspace $workspace): bool
    {
        return $workspace->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Owner and Admin can create projects
     */
    public function createProject(User $user, Workspace $workspace): bool
    {
        $role = $this->getUserRole($user, $workspace);

        return $role?->isAdmin() ?? false;
    }

    /**
     * Get user's role in workspace
     */
    private function getUserRole(User $user, Workspace $workspace): ?WorkspaceRole
    {
        $member = $workspace->members()
            ->where('user_id', $user->id)
            ->first();

        if (! $member) {
            return null;
        }

        /** @var Pivot&object{role: string} $pivot */
        $pivot = $member->pivot;

        return WorkspaceRole::tryFrom($pivot->role);
    }
}
