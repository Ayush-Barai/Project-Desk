<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class WorkspacePolicy
{
    /**
     * Check if user is a member of workspace
     */
    public function view(User $user, Workspace $workspace): bool
    {
        return $workspace->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Only owner can update workspace details
     */
    public function update(User $user, Workspace $workspace): bool
    {
        return $user->id === (string) ($workspace->owner_id);
    }

    /**
     * Only owner can delete workspace
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->id === (string) $workspace->owner_id;
    }

    /**
     * Owner + Admin can manage members
     */
    public function manageMembers(User $user, Workspace $workspace): bool
    {
        $member = $workspace->members()
            ->where('user_id', $user->id)
            ->first();

        if (! $member) {
            return false;
        }

        /** @var Pivot&object{role: string} $pivot */
        $pivot = $member->pivot;

        return in_array($pivot->role, ['owner', 'admin']);
    }

    /**
     * Only owner can assign roles
     */
    public function assignRole(User $user, Workspace $workspace): bool
    {

        return $user->id === (string) $workspace->owner_id;
    }

    /**
     * Any member can view projects
     */
    public function viewProjects(User $user, Workspace $workspace): bool
    {
        return $workspace->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Admin + Owner can create projects
     */
    public function createProject(User $user, Workspace $workspace): bool
    {
        $member = $workspace->members()
            ->where('user_id', $user->id)
            ->first();

        if (! $member) {
            return false;
        }

        /** @var Pivot&object{role: string} $pivot */
        $pivot = $member->pivot;

        return in_array($pivot->role, ['owner', 'admin']);
    }
}
