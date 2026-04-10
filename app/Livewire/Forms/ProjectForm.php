<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector ;
use Illuminate\Support\Str;
use Livewire\Form;

final class ProjectForm extends Form
{
    
    public string $name = '';

    public string $description = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public int $budget_hours = 0;

    public string $status = 'Planning';

    public string $color = 'Gray';

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

    

    public function create(): Redirector|RedirectResponse
    {
        $this->validate();
        $project = Project::query()->create([
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

        return to_route('projects.index');
    }
};