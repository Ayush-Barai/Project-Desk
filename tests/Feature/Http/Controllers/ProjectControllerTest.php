<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->workspace = Workspace::factory()->create([
        'owner_id' => $this->user->id,
    ]);

    // Add user to workspace
    $this->user->workspaces()->attach($this->workspace->id, ['role' => 'admin']);

    session(['workspace_id' => $this->workspace->id]);
});

it('displays projects', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
    ]);

    // Give user access to project
    $project->members()->attach($this->user->id, ['role' => 'member']);

    actingAs($this->user)
        ->get(route('projects.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.projects.index')
        ->assertViewHas('projects');
});

it('displays the create project page', function (): void {
    actingAs($this->user)
        ->get(route('projects.create'))
        ->assertSuccessful()
        ->assertViewIs('pages.projects.create');
});

it('stores a new project', function (): void {
    $data = [
        'name' => 'New Project',
        'description' => 'Project Description',
        'budget_hours' => 10,
        'status' => 'Planning',
    ];

    actingAs($this->user)
        ->post(route('projects.store'), $data)
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseHas('projects', [
        'name' => 'New Project',
        'workspace_id' => $this->workspace->id,
        'slug' => 'new-project',
        'color' => 'Blue',
    ]);
});

it('displays a specific project', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
    ]);

    $project->members()->attach($this->user->id, ['role' => 'member']);

    actingAs($this->user)
        ->get(route('projects.show', $project))
        ->assertSuccessful()
        ->assertViewIs('pages.projects.show')
        ->assertViewHas('project');
});

it('displays the edit project page', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
    ]);

    $project->members()->attach($this->user->id, ['role' => 'Project Manager']);

    actingAs($this->user)
        ->get(route('projects.setting', $project))
        ->assertSuccessful()
        ->assertViewIs('pages.projects.edit')
        ->assertViewHas('project');
});

it('updates a project', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
        'name' => 'Old Name',
    ]);

    $project->members()->attach($this->user->id, ['role' => 'Project Manager']);

    $data = [
        'name' => 'Updated Name',
        'budget_hours' => 20,
    ];

    actingAs($this->user)
        ->patch(route('projects.update', $project), $data)
        ->assertRedirect(route('projects.show', $project));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated Name',
    ]);
});

it('soft deletes a project', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
    ]);

    $project->members()->attach($this->user->id, ['role' => 'Project Manager']);

    actingAs($this->user)
        ->patch(route('projects.archive', $project))
        ->assertRedirect(route('projects.index'));

    $this->assertSoftDeleted('projects', ['id' => $project->id]);
});

it('force deletes a project', function (): void {
    $project = Project::factory()->create([
        'workspace_id' => $this->workspace->id,
    ]);

    $project->members()->attach($this->user->id, ['role' => 'Project Manager']);

    actingAs($this->user)
        ->delete(route('projects.destroy', $project), ['_method' => 'delete'])
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});
