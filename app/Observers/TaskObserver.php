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
    public function created(Task $task)
    {
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'created task',
            'subject_id' => $task->id,
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    // TaskObserver.php
    public function deleting(Task $task)
    {
        if ($task->timeEntries()->exists()) {
            throw new Exception('Cannot delete task with time entries');
        }
    }
}
