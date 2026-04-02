<?php

declare(strict_types=1);

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Label;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;

it('belongs to project', function (): void {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    expect($task->project)->toBeInstanceOf(Project::class);
});

it('handles self relationship (parent and subtasks)', function (): void {
    $parent = Task::factory()->create();
    $child = Task::factory()->create(['parent_task_id' => $parent->id]);

    expect($child->parent->id)->toBe($parent->id)
        ->and($parent->subtasks)->toHaveCount(1);
});

it('belongs to assignee and creator', function (): void {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'assigned_to' => $user->id,
        'created_by' => $user->id,
    ]);

    expect($task->assignee->id)->toBe($user->id)
        ->and($task->creator->id)->toBe($user->id);
});

it('has many labels', function (): void {
    $task = Task::factory()->create();
    $label = Label::factory()->create();

    $task->labels()->attach($label->id);

    expect($task->labels)->toHaveCount(1);
});

it('handles task dependencies', function (): void {
    $task1 = Task::factory()->create();
    $task2 = Task::factory()->create();

    $task1->blockers()->attach($task2->id);

    expect($task1->blockers)->toHaveCount(1)
        ->and($task2->blockedBy)->toHaveCount(1);
});

it('handles nullable fields correctly', function (): void {
    $task = Task::factory()->create([
        'assigned_to' => null,
    ]);

    expect($task->assignee)->toBeNull();
});

it('has many comments', function (): void {
    $task = Task::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $task->id,
        'commentable_type' => Task::class,
    ]);

    expect($task->comments)->toHaveCount(1);
});

it('has many attachments', function (): void {
    $task = Task::factory()->create();

    Attachment::factory()->create([
        'attachable_id' => $task->id,
        'attachable_type' => Task::class,
    ]);

    expect($task->attachments)->toHaveCount(1);
});

it('has many time entries', function (): void {
    $task = Task::factory()->create();

    TimeEntry::factory()->count(2)->create([
        'task_id' => $task->id,
    ]);

    expect($task->timeEntries)->toHaveCount(2);
});

it('belongs to milestone (if exists)', function (): void {
    $milestone = Milestone::factory()->create();
    $task = Task::factory()->create(['milestone_id' => $milestone->id]);

    expect($task->milestone)->toBeInstanceOf(Milestone::class);
});
