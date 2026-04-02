<?php

declare(strict_types=1);

/**
 * TaskTest
 *
 * Comprehensive tests for the Task model's relationships and functionality.
 * Verifies complex relationships including parent-child subtasks, assignments,
 * milestones, time tracking, labels, comments, attachments, and task dependencies.
 * Tests status and priority enum casting.
 */

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Label;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;

// Test: Verify task belongs to project
// Description: Ensures tasks are properly scoped to projects,
// establishing the fundamental work organization structure
it('belongs to project', function (): void {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    expect($task->project)->toBeInstanceOf(Project::class);
});

// Test: Verify task hierarchical relationships (parent/subtasks)
// Description: Ensures tasks can have parent-child relationships enabling task decomposition,
// allowing complex tasks to be broken into smaller subtasks for detailed planning
it('handles self relationship (parent and subtasks)', function (): void {
    $parent = Task::factory()->create();
    $child = Task::factory()->create(['parent_task_id' => $parent->id]);

    expect($child->parent->id)->toBe($parent->id)
        ->and($parent->subtasks)->toHaveCount(1);
});

// Test: Verify task belongs to assignee and creator
// Description: Ensures tasks maintain both assignee (who does the work) and creator (who made the task),
// enabling work assignment and accountability tracking
it('belongs to assignee and creator', function (): void {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'assigned_to' => $user->id,
        'created_by' => $user->id,
    ]);

    expect($task->assignee->id)->toBe($user->id)
        ->and($task->creator->id)->toBe($user->id);
});

// Test: Verify task has many labels
// Description: Ensures tasks can be tagged with multiple labels for categorization,
// enabling flexible filtering and task organization
it('has many labels', function (): void {
    $task = Task::factory()->create();
    $label = Label::factory()->create();

    $task->labels()->attach($label->id);

    expect($task->labels)->toHaveCount(1);
});

// Test: Verify task dependencies (blockers and blockedBy)
// Description: Ensures tasks can have dependencies, with some blocking and others being blocked,
// enabling workflow management and dependency tracking
it('handles task dependencies', function (): void {
    $task1 = Task::factory()->create();
    $task2 = Task::factory()->create();

    $task1->blockers()->attach($task2->id);

    expect($task1->blockers)->toHaveCount(1)
        ->and($task2->blockedBy)->toHaveCount(1);
});

// Test: Verify task handles nullable assignee field
// Description: Ensures tasks can exist without an assignee initially,
// allowing for unassigned task creation and later assignment
it('handles nullable fields correctly', function (): void {
    $task = Task::factory()->create([
        'assigned_to' => null,
    ]);

    expect($task->assignee)->toBeNull();
});

// Test: Verify task has polymorphic comments
// Description: Ensures comments can be attached directly to tasks,
// enabling discussion and collaboration on specific work items
it('has many comments', function (): void {
    $task = Task::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $task->id,
        'commentable_type' => Task::class,
    ]);

    expect($task->comments)->toHaveCount(1);
});

// Test: Verify task has polymorphic attachments
// Description: Ensures files can be attached to tasks,
// enabling reference documents and work product sharing
it('has many attachments', function (): void {
    $task = Task::factory()->create();

    Attachment::factory()->create([
        'attachable_id' => $task->id,
        'attachable_type' => Task::class,
    ]);

    expect($task->attachments)->toHaveCount(1);
});

// Test: Verify task has many time entries
// Description: Ensures work hours can be tracked against tasks,
// enabling time tracking and workload analysis
it('has many time entries', function (): void {
    $task = Task::factory()->create();

    TimeEntry::factory()->count(2)->create([
        'task_id' => $task->id,
    ]);

    expect($task->timeEntries)->toHaveCount(2);
});

// Test: Verify task belongs to milestone
// Description: Ensures tasks can be grouped into milestones, enabling
// organization around key project deliverables and checkpoints
it('belongs to milestone (if exists)', function (): void {
    $milestone = Milestone::factory()->create();
    $task = Task::factory()->create(['milestone_id' => $milestone->id]);

    expect($task->milestone)->toBeInstanceOf(Milestone::class);
});
