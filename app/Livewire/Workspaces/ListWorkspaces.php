<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;

final class ListWorkspaces extends Component
{
    /**
     * Summary of getWorkspacesProperty
     *
     * @return \Illuminate\Contracts\Pagination\Paginator<int, object|object{pivot: TPivotModel|Workspace>}
     */
    public function getWorkspacesProperty(): Paginator
    {
        $user = auth()->user();

        return $user->workspaces()->latest()->where(['owner_id' => $user->id])->simplePaginate(4);
    }

    public function switch(int $id): void
    {
        session(['workspace_id' => $id]);
    }

    public function deleteWorkspace(int $id): void
    {
        abort_if(auth()->user()->can('delete'), 403, 'You can not delete the workspace !!!');

        Workspace::destroy(['id' => $id]);
    }

    public function render(): View
    {
        return view('livewire.workspaces.list');
    }
}
