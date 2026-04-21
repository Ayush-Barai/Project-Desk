<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Models\Project;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class ListTasks extends Component
{
    use WithPagination;

    public Project $project;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $priority = '';

    #[Url]
    public string $assignee = '';

    protected $listeners = ['task-created' => '$refresh'];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function getTasksProperty()
    {
        return $this->project
            ->tasks()
            ->whereNull('parent_task_id')
            ->with(['assignee', 'subtasks'])
            ->when($this->search, function ($query): void {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->status, function ($query): void {
                $query->where('status', $this->status);
            })
            ->when($this->priority, function ($query): void {
                $query->where('priority', $this->priority);
            })
            ->when($this->assignee, function ($query): void {
                $query->where('assigned_to', $this->assignee);
            })
            ->latest()
            ->paginate(10);
    }

    public function delete($taskId): void
    {
        $this->project->tasks()->where('id', $taskId)->delete();
    }

    public function render(): Factory|View
    {
        return view('livewire.tasks.list');
    }
}
