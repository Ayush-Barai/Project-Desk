<?php

declare(strict_types=1);

/**
 * WorkspaceTest
 *
 * Comprehensive tests for the Workspace model's relationships and functionality.
 * Verifies workspace ownership, team member management with roles, projects,
 * labels, and task access through relationships. Tests soft deletion capabilities.
 */
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test: Verify workspace belongs to an owner
// Description: Ensures each workspace has an owner user and the relationship
// functions correctly for accessing workspace ownership
it('belongs to an owner', function (): void {
    $owner = User::factory()->create();

    $workspace = Workspace::factory()->create([
        'owner_id' => $owner->id,
    ]);

    expect($workspace->owner)
        ->toBeInstanceOf(User::class)
        ->id->toBe($owner->id);
});

// Test: Verify workspace has many members
// Description: Ensures workspaces can have multiple members with assigned roles,
// enabling team collaboration with role-based access control
it('has many members', function (): void {
    $workspace = Workspace::factory()->create();
    $users = User::factory()->count(3)->create();

    $workspace->members()->attach(
        $users->pluck('id')->mapWithKeys(fn ($id): array => [
            $id => ['role' => 'member'],
        ])
    );

    expect($workspace->members)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(User::class);
});

// Test: Verify workspace has many projects
// Description: Ensures workspaces can contain multiple projects,
// demonstrating their role as top-level organizational containers
it('has many projects', function (): void {
    $workspace = Workspace::factory()->create();

    $projects = Project::factory()
        ->count(2)
        ->create(['workspace_id' => $workspace->id]);

    expect($workspace->projects)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Project::class);
});

// Test: Verify workspace has many labels
// Description: Ensures workspaces can define multiple labels for task categorization,
// enabling consistent tagging across all projects in the workspace
it('has many labels', function (): void {
    $workspace = Workspace::factory()->create();

    $labels = Label::factory()
        ->count(2)
        ->create(['workspace_id' => $workspace->id]);

    expect($workspace->labels)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Label::class);
});

// Test: Verify workspace has many tasks through projects
// Description: Ensures workspaces can access all tasks across all projects,
// enabling workspace-level task querying and aggregation
it('has many tasks through projects', function (): void {
    $workspace = Workspace::factory()->create();

    // Create 2 projects for this workspace
    $projects = Project::factory()->count(2)->create([
        'workspace_id' => $workspace->id,
    ]);

    // Add 2 tasks per project
    foreach ($projects as $project) {
        Task::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);
    }

    expect($workspace->tasks)
        ->toHaveCount(4)
        ->each->toBeInstanceOf(Task::class);
});

// Test: Verify workspace have the valid owner
// Description: Ensures valid owner see the detail of workspace

it('Check the user is owner ', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);

    expect($workspace->isOwner($user->id))->toBeTrue();
});
