<?php

declare(strict_types=1);

/**
 * ActivityTest
 *
 * Tests for the Activity model's relationships and functionality.
 * Verifies that activities correctly associate with users, projects, and optionally tasks.
 * Validates proper handling of optional relationships (like nullable project_id).
 */

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

// Test: Verify activity correctly belongs to user and project
// Description: Ensures that an activity record properly maintains relationships with both
// a user who performed the action and the project in which it occurred
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

// Test: Verify activity can optionally belong to a task
// Description: Ensures that an activity can have a task association, used when actions
// directly relate to a specific task within a project
it('can belong to task', function (): void {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'task_id' => $task->id,
    ]);

    expect($activity->task)->toBeInstanceOf(Task::class);
});

// Test: Verify activity can have null project relationship
// Description: Ensures activities handle nullable project_id gracefully, allowing for
// workspace-level or system-level activities without a specific project context
it('can have null project', function (): void {
    $activity = Activity::factory()->create(['project_id' => null]);

    expect($activity->project)->toBeNull();
});
