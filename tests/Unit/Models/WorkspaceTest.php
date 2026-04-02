<?php

declare(strict_types=1);

use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('belongs to an owner', function (): void {
    $owner = User::factory()->create();

    $workspace = Workspace::factory()->create([
        'owner_id' => $owner->id,
    ]);

    expect($workspace->owner)
        ->toBeInstanceOf(User::class)
        ->id->toBe($owner->id);
});

it('has many members', function (): void {
    $workspace = Workspace::factory()->create();
    $users = User::factory()->count(3)->create();

    $workspace->members()->attach(
        $users->pluck('id')->mapWithKeys(fn ($id): array => [
            $id => ['role' => 'member'], // required column
        ])
    );

    expect($workspace->members)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(User::class);
});

it('has many projects', function (): void {
    $workspace = Workspace::factory()->create();

    $projects = Project::factory()
        ->count(2)
        ->create(['workspace_id' => $workspace->id]);

    expect($workspace->projects)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Project::class);
});

it('has many labels', function (): void {
    $workspace = Workspace::factory()->create();

    $labels = Label::factory()
        ->count(2)
        ->create(['workspace_id' => $workspace->id]);

    expect($workspace->labels)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Label::class);
});

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
        ->toHaveCount(4) // 2 projects * 2 tasks each
        ->each->toBeInstanceOf(Task::class);
});
