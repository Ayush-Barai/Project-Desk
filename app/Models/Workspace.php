<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

// Relation type: indirect one-to-many

final class Workspace extends Model
{
    /**
     * @use HasFactory<WorkspaceFactory>
     * Enables factory usage for this model
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * Defines the owner relationship (a workspace belongs to one user)
     *
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        // Links this workspace to a User using 'owner_id' as foreign key
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Defines members relationship (many-to-many between workspace and users)
     *
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        // Defines pivot table 'workspace_user' with foreign keys workspace_id and user_id
        return $this->belongsToMany(User::class, 'workspace_user', 'workspace_id', 'user_id')
            ->withPivot('role') // Include 'role' attribute from pivot table in results
            ->withTimestamps(); // Automatically manage created_at and updated_at on pivot table
    }

    /**
     * Defines projects relationship (one workspace has many projects)
     *
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        // Links workspace to projects using workspace_id as foreign key
        return $this->hasMany(Project::class, 'workspace_id', 'id');
    }

    /**
     * Defines labels relationship (one workspace has many labels)
     *
     * @return HasMany<Label, $this>
     */
    public function labels(): HasMany
    {
        // Links workspace to labels using workspace_id as foreign key
        return $this->hasMany(Label::class, 'workspace_id', 'id');
    }

    /**
     * Defines tasks relationship through projects (indirect relationship)
     *
     * @return HasManyThrough<Task, Project, $this>
     */
    public function tasks(): HasManyThrough
    {
        // Gets tasks through projects:
        // Task model → final model we want
        // Project model → intermediate model
        // 'workspace_id' → foreign key on projects table
        // 'project_id' → foreign key on tasks table
        // 'id' → local key on workspace
        // 'id' → local key on project
        return $this->hasManyThrough(Task::class, Project::class, 'workspace_id', 'project_id', 'id', 'id');
    }
}
