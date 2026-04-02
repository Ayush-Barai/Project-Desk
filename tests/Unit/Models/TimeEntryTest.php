<?php

declare(strict_types=1);

/**
 * TimeEntryTest
 *
 * Tests for the TimeEntry model's relationships and functionality.
 * Verifies time entries are associated with tasks and users.
 * Validates time tracking functionality for workload management.
 */

use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;

// Test: Verify time entry belongs to task and user
// Description: Ensures time entries correctly maintain relationships with both
// the task being worked on and the user who logged the time, establishing accountability
// and task-user work linkage
it('belongs to task and user', function (): void {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $entry = TimeEntry::factory()->create([
        'user_id' => $user->id,
        'task_id' => $task->id,
    ]);

    expect($entry->user)->toBeInstanceOf(User::class)
        ->and($entry->task)->toBeInstanceOf(Task::class);
});
