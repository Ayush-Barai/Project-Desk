<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Enums\WorkspaceRole;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

final class CreateWorkspace extends Component
{
    public string $name = '';

    public string $description = '';

    public function create(): RedirectResponse|Redirector
    {
        $this->validate([
            'name' => 'required|min:3',
        ]);

        $workspace = Workspace::query()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.Str::random(6),
            'description' => $this->description,
            'owner_id' => auth()->id(),
        ]);

        // Attach owner with Owner role
        $workspace->members()->attach(auth()->id(), [
            'role' => WorkspaceRole::Owner->value,
        ]);

        session(['workspace_id' => $workspace->id]);

        return to_route('workspaces.show', $workspace);
    }

    public function render(): View
    {
        return view('livewire.workspaces.create');
    }
}
