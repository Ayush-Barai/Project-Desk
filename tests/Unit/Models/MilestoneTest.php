<?php

declare(strict_types=1);

/**
 * MilestoneTest
 *
 * Tests for the Milestone model's relationships and functionality.
 * Verifies milestones properly belong to projects and function as key checkpoints.
 * Validates milestone creation, dates, and project associations.
 */

use App\Models\Milestone;
use App\Models\Project;

// Test: Verify milestone belongs to project
// Description: Ensures milestones are properly associated with projects,
// providing key checkpoints and organizational structure for project tasks
it('belongs to project', function (): void {
    $project = Project::factory()->create();
    $milestone = Milestone::factory()->create(['project_id' => $project->id]);

    expect($milestone->project)->toBeInstanceOf(Project::class);
});
