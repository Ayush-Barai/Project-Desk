<?php

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

it('logs activity when task is created', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user);

    $task = Task::factory()->create([
        'project_id' => $project->id,
    ]);

    expect(Activity::where('type', 'task.created')
        ->where('task_id', $task->id)
        ->where('project_id', $project->id)
        ->exists())->toBeTrue();
});

it('prevents deletion of task with time entries', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $task->timeEntries()->create([
        'user_id' => $user->id,
        'minutes' => 120,
        'logged_date' => now(),
    ]);

    expect(fn() => $task->delete())->toThrow(Exception::class);
});
