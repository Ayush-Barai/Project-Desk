<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

final class ShowTask extends Component
{
    public Task $task;

    public function mount(Task $task)
    {
        $this->task = $task;
    }

    public function render()
    {
        return view('livewire.tasks.show');
    }
}
