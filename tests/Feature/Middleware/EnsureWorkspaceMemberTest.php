<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    // Fake route using middleware
    Route::middleware(['auth', 'workspace']) // your middleware name
        ->get('/test-workspace', fn () => response()->json(['ok' => true]));
});

it('allows access if user belongs to workspace', function (): void {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    // Attach user to workspace
    $user->workspaces()->attach($workspace->id, ['role' => 'owner']);

    $this->actingAs($user)
        ->withSession(['workspace_id' => $workspace->id])
        ->get('/test-workspace')
        ->assertOk()
        ->assertJson(['ok' => true]);
});
