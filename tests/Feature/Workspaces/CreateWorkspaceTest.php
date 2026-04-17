<?php

declare(strict_types=1);

use App\Livewire\Workspaces\CreateWorkspace;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Tests the CreateWorkspace component.
 * Verifies that a workspace can be created with valid data.
 */
test('can create a workspace', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateWorkspace::class)
        ->set('name', 'New Workspace')
        ->set('description', 'Workspace Description')
        ->call('create')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('workspaces', [
        'name' => 'New Workspace',
        'owner_id' => $user->id,
    ]);
});

/**
 * Tests validation in the CreateWorkspace component.
 * Verifies that name is required and has a minimum length.
 */
test('name is required and min 3 chars', function (): void {
    Livewire::test(CreateWorkspace::class)
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['name' => 'required'])
        ->set('name', 'ab')
        ->call('create')
        ->assertHasErrors(['name' => 'min']);
});
