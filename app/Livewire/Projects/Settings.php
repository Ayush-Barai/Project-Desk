<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

final class Settings extends Component
{
    public Project $project;

    public string $name = '';

    public string $description = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public int $budget_hours = 0;

    public string $status = 'Planning';

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'name.min' => 'Project name must be at least 3 characters',
            'end_date.after_or_equal' => 'End date must be after start date',
            'budget_hours.min' => 'Budget cannot be zero or less',
        ];
    }

    public function mount(Project $project): void
    {
        // (workspace check)
        if ($project->workspace_id !== session('workspace_id')) {
            abort(403);
        }

        // Only Project Manager allowed (basic check)
        $role = $project->members()
            ->where('user_id', auth()->id())
            ->first()
            ?->pivot
            ->role;

        if ($role !== 'Project Manager') {
            abort(403);
        }

        $this->project = $project;

        // preload values
        $this->name = $project->name;
        $this->description = $project->description ?? '';
        $this->start_date = $project->start_date;
        $this->end_date = $project->end_date;
        $this->budget_hours = $project->budget_hours;
        $this->status = $project->status->value;
    }

    // UPDATE PROJECT
    public function update(): void
    {
        $data = $this->validate();
        $this->project->update($data);

        session()->flash('success', 'Project updated successfully!');
    }

    // ARCHIVE (Soft Delete)
    public function archive(): RedirectResponse|Redirector
    {
        $this->project->delete();

        return redirect()->route('projects.index');
    }

    // RESTORE
    public function restore(): void
    {
        $this->project->restore();
    }

    // DELETE (Permanent)
    public function delete(): RedirectResponse|Redirector
    {
        $this->project->forceDelete();

        return redirect()->route('projects.index');
    }

    public function render(): View
    {
        return view('livewire.projects.settings');
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
