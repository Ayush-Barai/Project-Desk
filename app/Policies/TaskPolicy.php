<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

final class TaskPolicy
{
    public function view(User $user, Task $task, Project $project): bool
    {

        return $task->project
            ->members()
            ->where('user_id', $user->id)
            ->exists()
            &&
            ($task->project->id === $project->id); // this will ensure that the task belongs to the project being accessed
    }

    public function create(User $user, Project $project): bool
    {
        return $project->members()
            ->where('user_id', $user->id)
            ->whereIn('role', ['Project Manager', 'Contributor'])
            ->exists();
    }

    public function update(User $user, Task $task): bool
    {
        return $task->project->members()
            ->where('user_id', $user->id)
            ->whereIn('role', ['Project Manager', 'Contributor'])
            ->exists();
    }

    public function delete(User $user, Task $task): bool
    {
        return $task->project->members()
            ->where('user_id', $user->id)
            ->where('role', 'Project Manager')
            ->exists();
    }
}
