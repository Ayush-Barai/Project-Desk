<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Carbon\CarbonInterface;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Task Model
 *
 * Represents individual tasks within a project. Tasks form the core work units and support
 * complex relationships including subtasks, dependencies, time tracking, comments, and attachments.
 * Tasks have status and priority enums for workflow management.
 *
 * @property int $id
 * @property int $project_id Project this task belongs to
 * @property int|null $parent_task_id Parent task for subtask relationships
 * @property int|null $assigned_to User assigned to this task
 * @property int|null $milestone_id Associated milestone
 * @property int|null $created_by User who created the task
 * @property string $title Task title
 * @property string|null $description Task description
 * @property TaskStatus $status Current status of the task
 * @property TaskPriority $priority Priority level of the task
 * @property CarbonInterface|null $start_date When the task begins
 * @property CarbonInterface|null $due_date When the task is due
 * @property float|null $estimated_hours Estimated hours to complete
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Task extends Model
{
    /**
     * @use HasFactory<TaskFactory>
     */
    use HasFactory;

    /**
     * Retrieve the project this task belongs to.
     *
     * Establishes the inverse of a one-to-many relationship with Project,
     * allowing the task to access its parent project for context.
     *
     * @return BelongsTo<Project, $this> The project containing this task
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Retrieve the parent task if this is a subtask.
     *
     * Establishes the self-referential parent relationship in a hierarchical task structure,
     * allowing subtasks to access their parent task. Returns null if this is a top-level task.
     *
     * @return BelongsTo<Task, $this> The parent task (if this is a subtask)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_task_id', 'id');
    }

    /**
     * Retrieve all subtasks of this task.
     *
     * Establishes the self-referential one-to-many relationship for hierarchical task structures,
     * allowing a task to have multiple child subtasks.
     *
     * @return HasMany<Task, $this> All subtasks of this task
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_task_id', 'id');
    }

    /**
     * Retrieve the user assigned to this task.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying who is responsible for completing this task.
     *
     * @return BelongsTo<User, $this> The user assigned to work on this task
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Retrieve the user who created this task.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying who originally created the task.
     *
     * @return BelongsTo<User, $this> The user who created this task
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Retrieve the milestone associated with this task.
     *
     * Establishes the inverse of a one-to-many relationship with Milestone,
     * optionally grouping the task within a project milestone.
     *
     * @return BelongsTo<Milestone, $this> The milestone this task is associated with
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    /**
     * Retrieve all labels assigned to this task.
     *
     * Establishes a many-to-many relationship with Label through the 'label_task' pivot table,
     * allowing multiple labels to be applied for categorization and filtering.
     *
     * @return BelongsToMany<Label, $this> All labels assigned to this task
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'label_task');
    }

    /**
     * Retrieve all time entries logged against this task.
     *
     * Establishes a one-to-many relationship with TimeEntry,
     * enabling time tracking and workload analysis for the task.
     *
     * @return HasMany<TimeEntry, $this> All time entries tracked for this task
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Retrieve all comments on this task.
     *
     * Establishes a polymorphic one-to-many relationship with Comment,
     * enabling discussion and collaboration directly on the task.
     *
     * @return MorphMany<Comment, $this> All comments attached to this task
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Retrieve all attachments associated with this task.
     *
     * Establishes a polymorphic one-to-many relationship with Attachment,
     * allowing files to be attached for reference and documentation.
     *
     * @return MorphMany<Attachment, $this> All files attached to this task
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Retrieve all tasks that are blocking this task.
     *
     * Establishes a many-to-many relationship through the 'task_dependencies' table,
     * representing tasks that must be completed before this task can proceed.
     *
     * @return BelongsToMany<Task, $this> All tasks blocking this task's progress
     */
    public function blockers(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'task_dependencies',
            'task_id',
            'blocked_by_task_id'
        );
    }

    /**
     * Retrieve all tasks that this task is blocking.
     *
     * Establishes the reverse many-to-many relationship through the 'task_dependencies' table,
     * representing tasks that cannot proceed until this task is completed.
     *
     * @return BelongsToMany<Task, $this> All tasks blocked by this task
     */
    public function blockedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'task_dependencies',
            'blocked_by_task_id',
            'task_id'
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
        ];
    }
}
