<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\MilestoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Milestone Model
 *
 * Represents project milestones or key checkpoints within a project timeline.
 * Milestones help organize tasks and provide clear targets for project progress tracking.
 *
 * @property int $id
 * @property int $project_id Project this milestone belongs to
 * @property string $title Milestone title
 * @property string|null $description Optional milestone description
 * @property CarbonInterface|null $start_date When the milestone begins
 * @property CarbonInterface|null $due_date When the milestone is due
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Milestone extends Model
{
    /**
     * @use HasFactory<MilestoneFactory>
     */
    use HasFactory;

    /**
     * Retrieve the project this milestone belongs to.
     *
     * Establishes the inverse of a one-to-many relationship with Project,
     * allowing milestone to access its parent project for context and organization.
     *
     * @return BelongsTo<Project, $this> The project this milestone is associated with
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
