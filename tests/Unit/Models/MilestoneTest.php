<?php

declare(strict_types=1);

use App\Models\Milestone;
use App\Models\Project;

it('belongs to project', function (): void {
    $project = Project::factory()->create();
    $milestone = Milestone::factory()->create(['project_id' => $project->id]);

    expect($milestone->project)->toBeInstanceOf(Project::class);
});
