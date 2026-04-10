<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\View\View;
use Livewire\Component;

final class Members extends Component
{
    public Workspace $workspace;

    public string $email = '';

    public array $suggestions = [];

    public function updatedEmail(): void
    {
        if (mb_strlen($this->email) > 1) {
            $this->suggestions = User::where('email', 'like', $this->email.'%')
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectEmail(string $email): void
    {
        $this->email = $email;
        $this->suggestions = [];
    }

    public function mount(Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }

    public function addMember(): void
    {
        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'User not found');

            return;
        }
        if ($this->workspace->members()->find($user->id)) {
            $this->addError('email', 'User is already a member');

            return;
        }

        $this->workspace->members()->attach($user->id, [
            'role' => 'Member',
        ]);

        $this->email = '';
    }

    public function updateRole(int $userId, string $role): void
    {
        $this->workspace->members()->updateExistingPivot($userId, [
            'role' => $role,
        ]);
    }

    public function removeUser(int $userId): void
    {
        $this->workspace->members()->detach($userId);
    }

    public function render(): View
    {
        return view('livewire.workspaces.members');
    }
}
