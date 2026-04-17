<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class Members extends Component
{
    public Workspace $workspace;

    public string $email = '';

    /** @var Collection<int,User> */
    public Collection $suggestions;

    /**
     * Search for users by email to provide suggestions.
     * Triggered when the email input field is updated.
     */
    public function updatedEmail(): void
    {
        if (mb_strlen($this->email) > 1) {
            $this->suggestions = User::query()->where('email', 'like', $this->email.'%')
                ->limit(5)
                ->get();
        } else {
            $this->suggestions = new User()->newCollection();
        }
    }

    public function selectEmail(string $email): void
    {
        $this->email = $email;
        $this->suggestions = new User()->newCollection();
    }

    public function mount(Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }

    public function addMember(): void
    {
        // Authorization check
        $this->authorize('manageMembers', $this->workspace);

        $user = User::query()->where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'User not found');

            return;
        }

        if ($this->workspace->members()->find($user->id)) {
            $this->addError('email', 'User is already a member');

            return;
        }

        $this->workspace->members()->attach($user->id, [
            'role' => WorkspaceRole::Member->value,
        ]);

        $this->email = '';
    }

    /**
     * Update the role of a workspace member.
     * Validates that the role is valid before updating the pivot record.
     */
    public function updateRole(string $userId, string $role): void
    {
        $workspace = $this->workspace;

        // Only owner can assign roles
        $this->authorize('assignRole', $workspace);

        // Validate role
        if (! WorkspaceRole::tryFrom($role)) {
            $this->addError('role', 'Invalid role');

            return;
        }

        $workspace->members()->updateExistingPivot($userId, [
            'role' => $role,
        ]);
    }

    /**
     * Remove a user from the workspace.
     * Detaches the user record from the workspace_user pivot table.
     */
    public function removeUser(string $userId): void
    {
        $workspace = $this->workspace;

        // Authorization check: Only owner and admin can manage members
        $this->authorize('manageMembers', $workspace);

        $workspace->members()->detach($userId);
    }

    public function render(): View
    {
        return view('livewire.workspaces.members');
    }
}
