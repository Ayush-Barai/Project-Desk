<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

final class CreateWorkspace extends Component
{
    public string $name = '';

    public string $description = '';

    public function create(): RedirectResponse
    {
        $this->validate([
            'name' => 'required|min:3',
        ]);

        $workspace = Workspace::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'owner_id' => auth()->id(),
        ]);

        // Attach owner
        $workspace->members()->attach(auth()->id(), [
            'role' => 'owner',
        ]);

        session(['workspace_id' => $workspace->id]);

        return redirect()->route('workspaces.show', $workspace);
    }

    public function render(): View
    {
        return view('livewire.workspaces.create');
    }
}
