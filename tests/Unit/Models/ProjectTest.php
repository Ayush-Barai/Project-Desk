<?php

declare(strict_types=1);

/**
 * ProjectTest
 *
 * Comprehensive tests for the Project model's relationships and functionality.
 * Verifies workspace ownership, team member assignments with roles, tasks, milestones,
 * comments, and activity tracking. Tests both simple and polymorphic relationships.
 */

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

// Test: Verify project belongs to workspace
// Description: Ensures projects are properly associated with their parent workspace,
// maintaining organizational structure and workspace isolation
it('belongs to workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);

    expect($project->workspace)->toBeInstanceOf(Workspace::class);
});

// Test: Verify project members maintain pivot role information
// Description: Ensures team members can be assigned to projects with specific roles,
// enabling role-based access control and permission management
it('has members with pivot role', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $project->members()->attach($user->id, ['role' => 'viewer']);

    expect($project->members->first()->pivot->role)->toBe('viewer');
});

// Test: Verify project has many tasks
// Description: Ensures projects can contain multiple tasks and all tasks
// are retrievable through the relationship, validating work item organization
it('has many tasks', function (): void {
    $project = Project::factory()->create();
    Task::factory()->count(4)->create(['project_id' => $project->id]);

    expect($project->tasks)->toHaveCount(4);
});

// Test: Verify project has many milestones
// Description: Ensures projects can organize multiple milestones for tracking key deliverables,
// validating hierarchical project structure
it('has many milestones', function (): void {
    $project = Project::factory()->create();

    Milestone::factory()->count(2)->create([
        'project_id' => $project->id,
    ]);

    expect($project->milestones)->toHaveCount(2);
});

// Test: Verify project has polymorphic comments
// Description: Ensures comments can be attached directly to projects,
// enabling team collaboration and discussion at the project level
it('has morph many comments', function (): void {
    $project = Project::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
    ]);

    expect($project->comments)->toHaveCount(1);
});

// Test: Verify project has many activities
// Description: Ensures all activities occurring within a project are tracked and retrievable,
// providing an audit trail for project changes and user actions
it('has many activities', function (): void {
    $project = Project::factory()->create();

    Activity::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    expect($project->activities)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Activity::class);
});

// Test: Verify project returns latest activity
// Description: Ensures projects can quickly access the most recent activity,
// enabling real-time updates and activity feeds
it('returns latest activity', function (): void {
    $project = Project::factory()->create();

    Activity::factory()->create(['project_id' => $project->id]);
    $latest = Activity::factory()->create(['project_id' => $project->id]);

    expect($project->latestActivity->id)->toBe($latest->id);
});
