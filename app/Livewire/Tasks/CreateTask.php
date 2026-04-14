<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;

final class CreateTask extends Component {

    public Project $project;

    public string $title = '';
    public string $description = '';
    public string $status = 'Todo';
    public string $priority = 'Medium';
    public $due_date;
    public $assigned_to;
    public $estimated_hours = 0;
    public function mount(Project $project)
    {
        // if ($project->workspace_id !== session('workspace_id')) {
        //     abort(403);
        // }

        $this->project = $project;
    }


    public function create()
    {
        Task::create([
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'created_by' => auth()->id(),
            'due_date' => $this->due_date,
            'estimated_hours' => $this->estimated_hours,
        ]);
        $this->reset(['title', 'description', 'estimated_hours']);

        return redirect()->route('task.list', $this->project);
    }


    public function getMembersProperty()
    {
        return $this->project->members;
    }

    public function render()
    {
        return view('livewire.tasks.create');
    }
};