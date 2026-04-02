<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

it('belongs to workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);

    expect($project->workspace)->toBeInstanceOf(Workspace::class);
});

it('has members with pivot role', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $project->members()->attach($user->id, ['role' => 'viewer']);

    expect($project->members->first()->pivot->role)->toBe('viewer');
});

it('has many tasks', function (): void {
    $project = Project::factory()->create();
    Task::factory()->count(4)->create(['project_id' => $project->id]);

    expect($project->tasks)->toHaveCount(4);
});

it('has many milestones', function (): void {
    $project = Project::factory()->create();

    Milestone::factory()->count(2)->create([
        'project_id' => $project->id,
    ]);

    expect($project->milestones)->toHaveCount(2);
});

it('has morph many comments', function (): void {
    $project = Project::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
    ]);

    expect($project->comments)->toHaveCount(1);
});

it('has many activities', function (): void {
    $project = Project::factory()->create();

    Activity::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    expect($project->activities)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Activity::class);
});

it('returns latest activity', function (): void {
    $project = Project::factory()->create();

    Activity::factory()->create(['project_id' => $project->id]);
    $latest = Activity::factory()->create(['project_id' => $project->id]);

    expect($project->latestActivity->id)->toBe($latest->id);
});
