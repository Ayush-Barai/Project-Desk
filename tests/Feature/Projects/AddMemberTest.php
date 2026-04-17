<?php

declare(strict_types=1);

use App\Livewire\Projects\AddMember;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create([
        'workspace_id' => $workspace->id,
    ]);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->assertStatus(200);
});

it('aborts if project does not belong to the session workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create([
        'workspace_id' => $workspace->id,
    ]);

    session(['workspace_id' => $workspace->id + 1]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->assertStatus(403);
});

it('provides suggestions when email is updated', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);

    $user1 = User::factory()->create(['email' => 'test1@example.com']);
    $user2 = User::factory()->create(['email' => 'test2@example.com']);
    $otherUser = User::factory()->create(['email' => 'other@example.com']);

    $workspace->members()->attach([
        $user1->id => ['role' => 'member'],
        $user2->id => ['role' => 'member'],
        $otherUser->id => ['role' => 'member'],
    ]);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'te')
        ->assertCount('suggestions', 2)
        ->set('email', 't')
        ->assertCount('suggestions', 0);
});

it('clears suggestions when a suggestion is selected', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'test@example.com')
        ->call('selectSuggestion', 'test@example.com')
        ->assertSet('email', 'test@example.com')
        ->assertCount('suggestions', 0);
});

it('adds a member to the project', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create(['email' => 'newmember@example.com']);
    $workspace->members()->attach($user->id, ['role' => 'member']);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'newmember@example.com')
        ->call('addMember')
        ->assertSet('email', '');

    expect($project->members()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('fails to add a non-existent user', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'nonexistent@example.com')
        ->call('addMember')
        ->assertHasErrors(['email' => 'User not found']);
});

it('fails to add a user not in the workspace', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create(['email' => 'notinworkspace@example.com']);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'notinworkspace@example.com')
        ->call('addMember')
        ->assertHasErrors(['email' => 'User is not part of workspace']);
});

it('fails to add an existing member', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create(['email' => 'existing@example.com']);
    $workspace->members()->attach($user->id, ['role' => 'member']);
    $project->members()->attach($user->id, ['role' => 'Contributor']);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->set('email', 'existing@example.com')
        ->call('addMember')
        ->assertHasErrors(['email' => 'Already in project']);
});

it('updates a member role', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create();
    $workspace->members()->attach($user->id, ['role' => 'member']);
    $project->members()->attach($user->id, ['role' => 'Contributor']);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->call('updateRole', $user->id, 'Admin');

    expect($project->members()->where('user_id', $user->id)->first()->pivot->role)->toBe('Admin');
});

it('removes a member from the project', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create();
    $workspace->members()->attach($user->id, ['role' => 'member']);
    $project->members()->attach($user->id, ['role' => 'Contributor']);

    session(['workspace_id' => $workspace->id]);

    Livewire::test(AddMember::class, ['project' => $project])
        ->call('removeMember', $user->id);

    expect($project->members()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('exposes workspace and project members properties', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $user = User::factory()->create();
    $workspace->members()->attach($user->id, ['role' => 'member']);
    $project->members()->attach($user->id, ['role' => 'Contributor']);

    session(['workspace_id' => $workspace->id]);

    $component = Livewire::test(AddMember::class, ['project' => $project]);

    expect($component->instance()->workspaceMembers)->toHaveCount(1)
        ->and($component->instance()->projectMembers)->toHaveCount(1);
});
