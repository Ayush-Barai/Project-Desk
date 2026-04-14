<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Project;
use App\Models\Task;
use Livewire\Form;

final class CreateTaskForm extends Form
{
    #[Validate('required|min:5')]
    public string $title = '';

    #[Validate('required|min:5')]
    public string $description = '';
    public string $status = 'Todo';
    public string $priority = 'Medium';
    public $due_date;
    public $assigned_to;
    public $estimated_hours = 0;


    // public function create()
    // {
    //     $this->validate();
    //     Task::create([
    //         'project_id' => '1',
    //         'title' => $this->title,
    //         'description' => $this->description,
    //         'status' => $this->status,
    //         'priority' => $this->priority,
    //         'assigned_to' => $this->assigned_to,
    //         'created_by' => auth()->id(),
    //         'due_date' => $this->due_date,
    //         'estimated_hours' => $this->estimated_hours,
    //     ]);

    //     return redirect()->route('task.list', $project);
    // }
}
