<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Models\Project;
use Livewire\Component;

final class ListTasks extends Component
{
    public Project $project;

    protected $listeners = ['task-created' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function getTasksProperty()
    {
        return $this->project->tasks()->latest()->get();
    }

    public function delete($taskId)
    {
        $this->project->tasks()->where('id', $taskId)->delete();
    }

    public function render()
    {
        return view('livewire.tasks.list');
    }
}
