<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Workspace;

it('has owned workspaces', function (): void {
    $user = User::factory()->create();
    Workspace::factory()->create(['owner_id' => $user->id]);

    expect($user->ownedWorkspaces)->toHaveCount(1);
});

it('belongs to many workspaces', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $user->workspaces()->attach($workspace->id, ['role' => 'member']);

    expect($user->workspaces)->toHaveCount(1);
});

it('belongs to many projects', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $user->projects()->attach($project->id, ['role' => 'member']);

    expect($user->projects)->toHaveCount(1);
});

it('has assigned tasks', function (): void {
    $user = User::factory()->create();
    Task::factory()->create(['assigned_to' => $user->id]);

    expect($user->assignedTasks)->toHaveCount(1);
});

it('has created tasks', function (): void {
    $user = User::factory()->create();
    Task::factory()->create(['created_by' => $user->id]);

    expect($user->createdTasks)->toHaveCount(1);
});

it('has time entries', function (): void {
    $user = User::factory()->create();
    TimeEntry::factory()->create(['user_id' => $user->id]);

    expect($user->timeEntries)->toHaveCount(1);
});

it('has comments', function (): void {
    $user = User::factory()->create();
    Comment::factory()->create([
        'user_id' => $user->id,
        'commentable_id' => Task::factory()->create()->id,
        'commentable_type' => Task::class,
    ]);

    expect($user->comments)->toHaveCount(1);
});

it('has activities', function (): void {
    $user = User::factory()->create();
    Activity::factory()->create(['user_id' => $user->id]);

    expect($user->activities)->toHaveCount(1);
});
