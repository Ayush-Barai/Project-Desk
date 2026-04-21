<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Livewire\Forms\CreateTaskForm;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

final class CreateTask extends Component
{
    use WithFileUploads;

    public Project $project;

    public CreateTaskForm $form;

    public Task $task;

    public function mount(Project $project, ?Task $task): void
    {
        $this->authorize('create', [Task::class, $project]);
        $this->project = $project;
        $this->task = $task;
    }

    public function create()
    {
        $this->form->create($this->project, $this->task);

        return to_route('tasks.list', $this->project);
    }

    // Get project members for task assignment show in create task form
    public function getMembersProperty()
    {
        return $this->project->members;
    }

    public function render(): Factory|View
    {
        return view('livewire.tasks.create');
    }
}
