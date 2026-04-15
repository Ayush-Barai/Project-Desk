<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\View\View;
use Livewire\Component;

final class ListWorkspaces extends Component
{
    /**
     * Get all workspaces the user belongs to.
     * Paginates results to 4 per page.
     */
    public function getWorkspacesProperty(): mixed
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->workspaces()->latest()->where(['owner_id' => $user->id])->simplePaginate(4);
    }

    public function switch(int $id): void
    {
        session(['workspace_id' => $id]);
    }

    public function deleteWorkspace(int $id): void
    {
        $workspace = Workspace::query()->findOrFail($id);

        $this->authorize('delete', $workspace);

        $workspace->forceDelete();
    }

    public function render(): View
    {
        return view('livewire.workspaces.list');
    }
}
