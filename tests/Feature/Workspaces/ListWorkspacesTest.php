<?php

declare(strict_types=1);

use App\Livewire\Workspaces\ListWorkspaces;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Tests the ListWorkspaces component.
 * Verifies that a user can see their workspaces.
 */
test('can list workspaces for user', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $user->workspaces()->attach($workspace, ['role' => 'owner']);

    Livewire::actingAs($user)
        ->test(ListWorkspaces::class)
        ->assertSee($workspace->name);
});

/**
 * Tests workspace switching.
 * Verifies that the workspace_id is changed in session.
 */
test('can switch current workspace', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(ListWorkspaces::class)
        ->call('switch', $workspace->id);

    expect(session('workspace_id'))->toBe($workspace->id);
});
