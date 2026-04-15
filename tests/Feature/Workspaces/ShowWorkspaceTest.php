<?php

declare(strict_types=1);

use App\Livewire\Workspaces\ShowWorkspace;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Tests the ShowWorkspace component.
 * Verifies that a user can see workspace details if they are a member.
 */
test('can show workspace details for member', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => 'owner']);

    Livewire::actingAs($user)
        ->test(ShowWorkspace::class, ['workspace' => $workspace])
        ->assertSee($workspace->name);
});

/**
 * Tests project listing in workspace view.
 * Verifies that projects belonging to the workspace are visible.
 */
test('can see workspace projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => 'owner']);

    $project = Project::factory()->create(['workspace_id' => $workspace->id]);

    Livewire::actingAs($user)
        ->test(ShowWorkspace::class, ['workspace' => $workspace])
        ->assertSee($project->name);
});
