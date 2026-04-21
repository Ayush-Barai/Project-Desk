<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;
use Exception;

final class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Activity::query()->create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'type' => 'task.created',
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    // TaskObserver.php
    public function deleting(Task $task): void
    {
        throw_if($task->timeEntries()->exists(), Exception::class, 'Cannot delete task with time entries');
    }
}
