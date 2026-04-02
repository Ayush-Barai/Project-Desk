<?php

declare(strict_types=1);

use App\Models\Label;
use App\Models\Task;
use App\Models\Workspace;

it('belongs to workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $label = Label::factory()->create(['workspace_id' => $workspace->id]);

    expect($label->workspace)->toBeInstanceOf(Workspace::class);
});

it('belongs to many tasks', function (): void {
    $label = Label::factory()->create();
    $task = Task::factory()->create();

    $label->tasks()->attach($task->id);

    expect($label->tasks)->toHaveCount(1);
});
