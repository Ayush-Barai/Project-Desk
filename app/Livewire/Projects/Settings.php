<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

// use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
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

    /**
     * Summary of rules
     *
     * @return array<string,array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return (new ProjectRequest())->rules();
    }

    /**
     * Summary of rules
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return (new ProjectRequest())->messages();
    }

    public function mount(Project $project): void
    {
        // (workspace check)
        abort_if($project->workspace_id !== session('workspace_id'), 403);

        // Only Project Manager allowed (basic check)
        $role = $project->members()
            ->where('user_id', auth()->id())
            ->first()
            ?->pivot
            ->role;

        abort_if($role !== 'Project Manager', 403);

        $this->project = $project;

        // preload values
        $this->name = $project->name;
        $this->description = $project->description ?? '';
        $this->start_date = (string) $project->start_date;
        $this->end_date = (string) $project->end_date;
        $this->budget_hours = (int) $project->budget_hours;
        $this->status = $project->status->value;
    }

    // UPDATE PROJECT
    public function update(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->validate();

        $this->project->update($data);

        session()->flash('success', 'Project updated successfully!');
    }

    // ARCHIVE (Soft Delete)
    public function archive(): Redirector|RedirectResponse
    {
        $this->project->delete();

        return to_route('projects.index');
    }

    // RESTORE
    public function restore(): void
    {
        $this->project->restore();
    }

    // DELETE (Permanent)
    public function delete(): Redirector|RedirectResponse
    {
        $this->project->forceDelete();

        return to_route('projects.index');
    }

    public function render(): View
    {
        return view('livewire.projects.settings');
    }
}
