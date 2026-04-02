<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Activity Model
 *
 * Represents an activity log entry that tracks changes and actions within the application.
 * Activities are associated with users, projects, and optionally tasks to maintain an audit trail.
 *
 * @property int $id
 * @property int $user_id User who performed the activity
 * @property int $project_id Project the activity belongs to
 * @property int|null $task_id Optional task the activity is related to
 * @property string $action Type of action performed
 * @property string|null $description Details about the activity
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Activity extends Model
{
    /**
     * @use HasFactory<ActivityFactory>
     */
    use HasFactory;

    /**
     * Retrieve the user who performed this activity.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying who initiated the action being logged.
     *
     * @return BelongsTo<User, $this> The user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Retrieve the project this activity is associated with.
     *
     * Establishes the inverse of a one-to-many relationship with Project,
     * contextualizing the activity within a specific project.
     *
     * @return BelongsTo<Project, $this> The project this activity occurred in
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Retrieve the task this activity is related to.
     *
     * Establishes the inverse of a one-to-many relationship with Task,
     * providing optional task-level context for the activity (nullable).
     *
     * @return BelongsTo<Task, $this> The task this activity is related to (if applicable)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
