<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Workspace;
use App\Policies\WorkspacePolicy;

beforeEach(function (): void {
    $this->policy = new WorkspacePolicy();
});

// VIEW METHOD TESTS
it('allows members to view workspace', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $user->workspaces()->attach($workspace->id, ['role' => 'member']);

    expect($this->policy->view($user, $workspace))->toBeTrue();
});

// UPDATE METHOD TESTS
it('allows owner to update workspace', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => 'owner']);

    expect($this->policy->update($user, $workspace))->toBeTrue();
});

it('denies non-owner from updating workspace', function (): void {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

    expect($this->policy->update($user, $workspace))->toBeFalse();
});

// DELETE METHOD TESTS
it('allows owner to delete workspace', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => 'owner']);

    expect($this->policy->delete($user, $workspace))->toBeTrue();
});

it('denies non-owner from deleting workspace', function (): void {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

    expect($this->policy->delete($user, $workspace))->toBeFalse();
});

// MANAGE MEMBERS METHOD TESTS
it('allows owner to manage members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'owner']);

    expect($this->policy->manageMembers($user, $workspace))->toBeTrue();
});

it('allows admin to manage members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'admin']);

    expect($this->policy->manageMembers($user, $workspace))->toBeTrue();
});

it('denies regular member from managing members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->manageMembers($user, $workspace))->toBeFalse();
});

it('denies non-member from managing members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    expect($this->policy->manageMembers($user, $workspace))->toBeFalse();
});

// ASSIGN ROLE METHOD TESTS
it('allows owner to assign roles', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => 'owner']);

    expect($this->policy->assignRole($user, $workspace))->toBeTrue();
});

it('denies non-owner from assigning roles', function (): void {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

    expect($this->policy->assignRole($user, $workspace))->toBeFalse();
});

// VIEW PROJECTS METHOD TESTS
it('allows members to view projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->viewProjects($user, $workspace))->toBeTrue();
});

it('denies non-members from viewing projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    expect($this->policy->viewProjects($user, $workspace))->toBeFalse();
});

// CREATE PROJECT METHOD TESTS
it('allows owner to create projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'owner']);

    expect($this->policy->createProject($user, $workspace))->toBeTrue();
});

it('allows admin to create projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'admin']);

    expect($this->policy->createProject($user, $workspace))->toBeTrue();
});

it('denies regular member from creating projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    $workspace->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->createProject($user, $workspace))->toBeFalse();
});

it('denies non-member from creating projects', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    expect($this->policy->createProject($user, $workspace))->toBeFalse();
});
