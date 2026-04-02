<?php

declare(strict_types=1);

/**
 * LabelTest
 *
 * Tests for the Label model's relationships and functionality.
 * Verifies labels belong to workspaces and have many-to-many relationships with tasks.
 * Validates categorization and tagging functionality.
 */

use App\Models\Label;
use App\Models\Task;
use App\Models\Workspace;

// Test: Verify label belongs to workspace
// Description: Ensures labels are properly scoped to a workspace,
// enabling workspace-level organization and isolation of categorization tags
it('belongs to workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $label = Label::factory()->create(['workspace_id' => $workspace->id]);

    expect($label->workspace)->toBeInstanceOf(Workspace::class);
});

// Test: Verify label has many-to-many relationship with tasks
// Description: Ensures labels can be applied to multiple tasks and tasks can have multiple labels,
// enabling flexible task categorization and filtering capabilities
it('belongs to many tasks', function (): void {
    $label = Label::factory()->create();
    $task = Task::factory()->create();

    $label->tasks()->attach($task->id);

    expect($label->tasks)->toHaveCount(1);
});
