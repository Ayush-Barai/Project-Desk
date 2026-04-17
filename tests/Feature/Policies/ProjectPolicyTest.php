<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use App\Policies\ProjectPolicy;

beforeEach(function (): void {
    $this->policy = new ProjectPolicy();
});

test('viewAny allows workspace members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();
    $user->workspaces()->attach($workspace->id, ['role' => 'member']);

    session(['workspace_id' => $workspace->id]);

    expect($this->policy->viewAny($user))->toBeTrue();
});

test('viewAny denies non-workspace members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    session(['workspace_id' => $workspace->id]);

    expect($this->policy->viewAny($user))->toBeFalse();
});

test('view allows project members', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->view($user, $project))->toBeTrue();
});

test('view denies non-project members', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    expect($this->policy->view($user, $project))->toBeFalse();
});

test('create allows workspace members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();
    $user->workspaces()->attach($workspace->id, ['role' => 'member']);

    session(['workspace_id' => $workspace->id]);

    expect($this->policy->create($user))->toBeTrue();
});

test('create denies non-workspace members', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    session(['workspace_id' => $workspace->id]);

    expect($this->policy->create($user))->toBeFalse();
});

test('update allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->update($user, $project))->toBeTrue();
});

test('update denies regular members', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->update($user, $project))->toBeFalse();
});

test('manage allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->manage($user, $project))->toBeTrue();
});

test('delete allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->delete($user, $project))->toBeTrue();
});

test('restore allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->restore($user, $project))->toBeTrue();
});

test('forceDelete allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->forceDelete($user, $project))->toBeTrue();
});

test('manageTeam allows project managers', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'Project Manager']);

    expect($this->policy->manageTeam($user, $project))->toBeTrue();
});

test('manager-only methods deny non-managers', function (string $method): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id, ['role' => 'member']);

    expect($this->policy->$method($user, $project))->toBeFalse();
})->with([
    'update',
    'manage',
    'delete',
    'restore',
    'forceDelete',
    'manageTeam',
]);
