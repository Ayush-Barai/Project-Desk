<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\TimeEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TimeEntry Model
 *
 * Represents time tracking entries where users log hours spent on tasks.
 * Enables time tracking and helps with workload management and project estimation.
 *
 * @property int $id
 * @property int $task_id Task being worked on
 * @property int $user_id User logging the time
 * @property CarbonInterface $date Date of the time entry
 * @property float $hours Number of hours recorded
 * @property string|null $description Optional notes about the work performed
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class TimeEntry extends Model
{
    /**
     * @use HasFactory<TimeEntryFactory>
     */
    use HasFactory;

    /**
     * Retrieve the task this time entry is logged against.
     *
     * Establishes the inverse of a one-to-many relationship with Task,
     * linking the time entry to the specific task work was performed on.
     *
     * @return BelongsTo<Task, $this> The task associated with this time entry
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Retrieve the user who logged this time entry.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying who recorded the work hours.
     *
     * @return BelongsTo<User, $this> The user who logged this time
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
