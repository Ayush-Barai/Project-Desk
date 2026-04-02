<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Workspace Model
 *
 * Represents a workspace - the top-level organizational container for projects, users, labels,
 * and all related entities. Each workspace has an owner and can have multiple members with different roles.
 * Workspaces support soft deletion for data integrity.
 *
 * @property int $id
 * @property int $owner_id User who owns this workspace
 * @property string $name Workspace name
 * @property string|null $slug URL-friendly identifier for the workspace
 * @property string|null $description Workspace description
 * @property string|null $avatar Avatar image path or URL
 * @property CarbonInterface|null $deleted_at Soft delete timestamp
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Workspace extends Model
{
    /**
     * @use HasFactory<WorkspaceFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * Retrieve the user who owns this workspace.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying the workspace's owner and administrator.
     *
     * @return BelongsTo<User, $this> The user who owns this workspace
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Retrieve all members of this workspace.
     *
     * Establishes a many-to-many relationship with User through the 'workspace_user' pivot table,
     * including role information for each member. Tracks creation/update timestamps on the pivot.
     *
     * @return BelongsToMany<User, $this> All users who are members of this workspace with assigned roles
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_user', 'workspace_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Retrieve all projects in this workspace.
     *
     * Establishes a one-to-many relationship with Project,
     * returning all projects that belong to this workspace.
     *
     * @return HasMany<Project, $this> All projects contained within this workspace
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'workspace_id', 'id');
    }

    /**
     * Retrieve all labels defined in this workspace.
     *
     * Establishes a one-to-many relationship with Label,
     * providing access to all categorization tags scoped to this workspace.
     *
     * @return HasMany<Label, $this> All labels defined for use in this workspace
     */
    public function labels(): HasMany
    {
        return $this->hasMany(Label::class, 'workspace_id', 'id');
    }

    /**
     * Retrieve all tasks across all projects in this workspace.
     *
     * Establishes a one-to-many-through relationship with Task via Project,
     * providing convenient access to all work items without explicit project filtering.
     *
     * @return HasManyThrough<Task, Project, $this> All tasks in all projects within this workspace
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Project::class, 'workspace_id', 'project_id', 'id', 'id');
    }
}
