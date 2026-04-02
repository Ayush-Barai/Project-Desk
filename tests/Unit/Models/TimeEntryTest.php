<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;

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
