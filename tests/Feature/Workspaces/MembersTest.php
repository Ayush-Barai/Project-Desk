<?php

declare(strict_types=1);

use App\Enums\WorkspaceRole;
use App\Livewire\Workspaces\Members;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Helper: attach owner to workspace
 */
function attachOwner(User $user, Workspace $workspace): void
{
    $workspace->members()->attach($user->id, [
        'role' => WorkspaceRole::Owner->value,
    ]);
}

/**
 * RENDER
 */
test('it renders successfully', function (): void {
    $workspace = Workspace::factory()->create();

    Livewire::test(Members::class, ['workspace' => $workspace])
        ->assertStatus(200);
});

/**
 * updatedEmail: returns suggestions
 */
test('it suggests users when typing email', function (): void {
    $user = User::factory()->create(['email' => 'john@example.com']);
    User::factory()->create(['email' => 'jane@example.com']);

    $workspace = Workspace::factory()->create();

    Livewire::test(Members::class, ['workspace' => $workspace])
        ->set('email', 'jo')
        ->assertSet('suggestions', fn ($s) => $s->contains('email', $user->email));
});

/**
 * updatedEmail: clears suggestions if too short
 */
test('it clears suggestions when email too short', function (): void {
    $workspace = Workspace::factory()->create();

    Livewire::test(Members::class, ['workspace' => $workspace])
        ->set('email', 'a')
        ->assertSet('suggestions', fn ($s) => $s->isEmpty());
});

/**
 * updatedEmail: limit results
 */
test('it limits suggestions to 5 users', function (): void {
    User::factory()->count(10)->create();

    $workspace = Workspace::factory()->create();

    Livewire::test(Members::class, ['workspace' => $workspace])
        ->set('email', 'a')
        ->set('email', 'ab') // trigger valid length
        ->assertSet('suggestions', fn ($s): bool => $s->count() <= 5);
});

/**
 * selectEmail
 */
test('it selects email and clears suggestions', function (): void {
    $workspace = Workspace::factory()->create();

    Livewire::test(Members::class, ['workspace' => $workspace])
        ->call('selectEmail', 'picked@example.com')
        ->assertSet('email', 'picked@example.com')
        ->assertSet('suggestions', fn ($s) => $s->isEmpty());
});

/**
 * addMember: success
 */
test('it adds a member successfully', function (): void {
    $owner = User::factory()->create();
    $user = User::factory()->create();

    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
    attachOwner($owner, $workspace);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->set('email', $user->email)
        ->call('addMember')
        ->assertSet('email', '');

    $this->assertDatabaseHas('workspace_user', [
        'workspace_id' => $workspace->id,
        'user_id' => $user->id,
        'role' => WorkspaceRole::Member->value,
    ]);
});

/**
 * addMember: user not found
 */
test('it fails if user not found', function (): void {
    $owner = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

    attachOwner($owner, $workspace);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->set('email', 'missing@example.com')
        ->call('addMember')
        ->assertHasErrors(['email']);
});

/**
 * addMember: already exists
 */
test('it prevents adding duplicate member', function (): void {
    $owner = User::factory()->create();
    $user = User::factory()->create();

    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
    attachOwner($owner, $workspace);

    $workspace->members()->attach($user->id, [
        'role' => WorkspaceRole::Member->value,
    ]);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->set('email', $user->email)
        ->call('addMember')
        ->assertHasErrors(['email']);
});

/**
 * addMember: unauthorized
 */
test('unauthorized user cannot add member', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    Livewire::actingAs($user)
        ->test(Members::class, ['workspace' => $workspace])
        ->set('email', 'test@example.com')
        ->call('addMember')
        ->assertForbidden();
});

/**
 * updateRole: success
 */
test('it updates member role', function (): void {
    $owner = User::factory()->create();
    $member = User::factory()->create();

    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
    attachOwner($owner, $workspace);

    $workspace->members()->attach($member->id, [
        'role' => WorkspaceRole::Member->value,
    ]);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->call('updateRole', (string) $member->id, WorkspaceRole::Admin->value);

    $this->assertDatabaseHas('workspace_user', [
        'user_id' => $member->id,
        'role' => WorkspaceRole::Admin->value,
    ]);
});

/**
 * updateRole: invalid role
 */
test('it fails on invalid role', function (): void {
    $owner = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

    attachOwner($owner, $workspace);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->call('updateRole', '1', 'invalid-role')
        ->assertHasErrors(['role']);
});

/**
 * updateRole: unauthorized
 */
test('non owner cannot update role', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    Livewire::actingAs($user)
        ->test(Members::class, ['workspace' => $workspace])
        ->call('updateRole', '1', WorkspaceRole::Admin->value)
        ->assertForbidden();
});

/**
 * removeUser: success
 */
test('it removes a user', function (): void {
    $owner = User::factory()->create();
    $member = User::factory()->create();

    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
    attachOwner($owner, $workspace);

    $workspace->members()->attach($member->id, [
        'role' => WorkspaceRole::Member->value,
    ]);

    Livewire::actingAs($owner)
        ->test(Members::class, ['workspace' => $workspace])
        ->call('removeUser', (string) $member->id);

    $this->assertDatabaseMissing('workspace_user', [
        'user_id' => $member->id,
    ]);
});

/**
 * removeUser: unauthorized
 */
test('unauthorized user cannot remove member', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    Livewire::actingAs($user)
        ->test(Members::class, ['workspace' => $workspace])
        ->call('removeUser', '1')
        ->assertForbidden();
});
