<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

it('belongs to user and project', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'project_id' => $project->id,
    ]);

    expect($activity->user)->toBeInstanceOf(User::class)
        ->and($activity->project)->toBeInstanceOf(Project::class);
});

it('can belong to task', function (): void {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'task_id' => $task->id,
    ]);

    expect($activity->task)->toBeInstanceOf(Task::class);
});

it('can have null project', function (): void {
    $activity = Activity::factory()->create(['project_id' => null]);

    expect($activity->project)->toBeNull();
});
