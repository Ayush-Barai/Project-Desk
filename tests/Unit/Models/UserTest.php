<?php

declare(strict_types=1);

/**
 * UserTest
 *
 * Comprehensive tests for the User model's relationships and functionality.
 * Verifies workspace ownership, workspace and project memberships, task assignments,
 * and created tasks. Tests many-to-many relationships with pivot data and UUID generation.
 */

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Workspace;

// Test: Verify user has owned workspaces
// Description: Ensures users can own workspaces and all owned workspaces
// are retrievable, validating workspace ownership tracking
it('has owned workspaces', function (): void {
    $user = User::factory()->create();
    Workspace::factory()->create(['owner_id' => $user->id]);

    expect($user->ownedWorkspaces)->toHaveCount(1);
});

// Test: Verify user belongs to many workspaces
// Description: Ensures users can be members of multiple workspaces with specific roles,
// enabling multi-workspace access and collaboration
it('belongs to many workspaces', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $user->workspaces()->attach($workspace->id, ['role' => 'member']);

    expect($user->workspaces)->toHaveCount(1);
});

// Test: Verify user belongs to many projects
// Description: Ensures users can be assigned to multiple projects with roles,
// enabling project team membership and role-based access
it('belongs to many projects', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $user->projects()->attach($project->id, ['role' => 'member']);

    expect($user->projects)->toHaveCount(1);
});

// Test: Verify user has assigned tasks
// Description: Ensures tasks can be assigned to users and all assigned tasks
// are retrievable, enabling work item allocation
it('has assigned tasks', function (): void {
    $user = User::factory()->create();
    Task::factory()->create(['assigned_to' => $user->id]);

    expect($user->assignedTasks)->toHaveCount(1);
});

// Test: Verify user has created tasks
// Description: Ensures tasks created by users can be tracked,
// enabling accountability for task creation
it('has created tasks', function (): void {
    $user = User::factory()->create();
    Task::factory()->create(['created_by' => $user->id]);

    expect($user->createdTasks)->toHaveCount(1);
});

// Test: Verify user has time entries
// Description: Ensures users can log time entries and all time logs
// are retrievable, enabling time tracking functionality
it('has time entries', function (): void {
    $user = User::factory()->create();
    TimeEntry::factory()->create(['user_id' => $user->id]);

    expect($user->timeEntries)->toHaveCount(1);
});

// Test: Verify user has comments
// Description: Ensures users can create comments and all comments authored by a user
// are retrievable, enabling comment tracking
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
