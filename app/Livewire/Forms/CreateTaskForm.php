<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Rules\Tasks\ProjectMemberRule;
use App\Rules\Tasks\TaskBudgetRule;
use App\Rules\Tasks\ValidTaskDueDate;
use Illuminate\Validation\Rules\Enum;
use Livewire\Form;
use Livewire\WithFileUploads;

final class CreateTaskForm extends Form
{
    use WithFileUploads;

    public Project $project;

    public Task $task;

    public string $title = '';

    public string $description = '';

    public string $status = 'Todo';

    public string $priority = 'Medium';

    public $due_date;

    public $assigned_to;

    public int $estimated_hours = 0;

    public int $project_id;

    public array $files = [];

    public function rules(): array
    {

        return [
            'project_id' => ['required', 'exists:projects,id'],

            'title' => ['required', 'string', 'min:3', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => ['required', new Enum(TaskStatus::class)],

            'priority' => ['required', new Enum(TaskPriority::class)],

            'due_date' => [
                'date',
                new ValidTaskDueDate($this->project),
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
                new ProjectMemberRule($this->project),
            ],

            'estimated_hours' => [
                'integer',
                'min:0',
                new TaskBudgetRule($this->project),
            ],
            'files.*' => ['file', 'max:10240'], // 10MB per file
        ];
    }

    public function create(Project $project, ?Task $task): void
    {
        $this->project = $project;
        $this->project_id = $this->project->id;
        $this->validate(); // This line use the rules() method for validation
        $this->task = Task::query()->create([
            'parent_task_id' => $task->id ?? null,
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'assigned_to' => $this->assigned_to,
            'estimated_hours' => $this->estimated_hours,
            'created_by' => auth()->id(),
        ]);

        foreach ($this->files as $file) {
            $path = $file->store('tasks', 'public');
            $this->task->attachments()->create([
                'user_id' => auth()->id(),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'attachable_type' => '1',
                'attachable_id' => $this->task->id,
            ]);

        }

        $this->reset([
            'title',
            'description',
            'due_date',
            'assigned_to',
            'estimated_hours',
        ]);

        // $this->dispatch('task-created');
    }
}
