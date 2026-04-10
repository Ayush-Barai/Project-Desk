<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class Team extends Component
{
    public Project $project;

    public string $email = '';

    /**
     * Summary of suggestions
     *
     * @var Collection<int, User>
     */
    public Collection $suggestions;

    public function mount(Project $project): void
    {
        abort_if($project->workspace_id !== session('workspace_id'), 403);

        $this->project = $project;
    }

    // Get workspace members (only these can be added)
    /**
     * @return Collection<int, User>
     */
    public function getWorkspaceMembersProperty(): Collection
    {
        return $this->project->workspace->members;
    }

    // Get project members
    /**
     * Summary of getProjectMembersProperty
     *
     * @return Collection<int, User>
     */
    public function getProjectMembersProperty(): Collection
    {
        return $this->project->members()->withPivot('role')->get();
    }

    public function updatedEmail(): void
    {
        if (mb_strlen($this->email) < 2) {
            $this->suggestions = new User()->newCollection([]);

            return;
        }

        $this->suggestions = User::query()->where('email', 'like', '%'.$this->email.'%')
            ->whereIn('id', $this->project->workspace->members->pluck('id'))
            ->limit(5)
            ->get();
    }

    public function selectSuggestion(string $email): void
    {
        $this->email = $email;
        $this->suggestions = new User()->newCollection([]);
    }

    // Add member to project
    public function addMember(): void
    {
        $user = User::query()->where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'User not found');

            return;
        }

        // Must be workspace member
        if (! $this->project->workspace->members->contains($user->id)) {
            $this->addError('email', 'User is not part of workspace');

            return;
        }

        // Prevent duplicate
        if ($this->project->members->contains($user->id)) {
            $this->addError('email', 'Already in project');

            return;
        }

        $this->project->members()->attach($user->id, [
            'role' => 'Contributor',
        ]);

        $this->email = '';
    }

    // Update role
    public function updateRole(int $userId, string $role): void
    {
        $this->project->members()->updateExistingPivot($userId, [
            'role' => $role,
        ]);
    }

    // Remove member
    public function removeMember(int $userId): void
    {
        $this->project->members()->detach($userId);
    }

    public function render(): View
    {
        return view('livewire.projects.team');
    }
}
