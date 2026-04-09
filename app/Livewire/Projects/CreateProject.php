<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

final class CreateProject extends Component
{
    public string $name = '';

    public string $description = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public int $budget_hours = 0;

    public string $status = 'Planning';

    public string $color = 'Gray';

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'name.min' => 'Project name must be at least 3 characters',
            'end_date.after_or_equal' => 'End date must be after start date',
            'budget_hours.min' => 'Budget cannot be zero or less',
        ];
    }

    public function create(): RedirectResponse
    {
        $this->validate();
        $project = Project::create([
            'workspace_id' => session('workspace_id'),
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description ?? null,
            'start_date' => $this->start_date ?? null,
            'end_date' => $this->end_date ?? null,
            'budget_hours' => $this->budget_hours,
            'status' => $this->status ?? 'Planning',
            'color' => $this->color ?? 'Blue',
        ]);

        $project->members()->attach(auth()->id(), [
            'role' => 'Project Manager',
        ]);

        return redirect()->route('projects.index');
    }

    public function render(): View
    {
        return view('livewire.projects.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget_hours' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'in:Planning,Active,OnHold,Completed,Archived'],
        ];
    }
}
