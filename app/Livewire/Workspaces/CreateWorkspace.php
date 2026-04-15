<?php

declare(strict_types=1);

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportRedirects\Redirector ;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

final class CreateWorkspace extends Component
{
    #[Validate('required|min:3|max:50')]
    public string $name = '';

    #[Validate('required|min:5|max:200')]
    public string $description = '';

    public function create() : RedirectResponse|Redirector
    {
        $this->validate();

        $workspace = Workspace::query()->create([
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


        return to_route('workspaces.show', $workspace);
    }

    public function render(): View
    {
        return view('livewire.workspaces.create');
    }
}
