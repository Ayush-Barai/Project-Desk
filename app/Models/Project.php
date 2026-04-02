<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Project extends Model
{
    /**
     * @use HasFactory<ProjectFactory>
     * Enables factory usage for this model
     */
    use HasFactory;

    // Defines which attributes can be mass-assigned (e.g., via create() or fill())
    protected $fillable = [
        'workspace_id', // Foreign key linking to workspace
        'name', // Project name
        'slug', // URL-friendly identifier
        'description', // Project description
        'status', // Project status (enum)
        'start_date', // Start date of project
        'end_date', // End date of project
        'budget_hours', // Estimated hours for project
        'color', // Color identifier (UI purpose)
    ];

    // Defines attribute casting (automatic conversion)
    protected $casts = [
        'status' => ProjectStatus::class, // Casts status to enum ProjectStatus
    ];

    /**
     * Defines relationship: Project belongs to a Workspace
     *
     * @return BelongsTo<Workspace, $this>
     */
    public function workspace(): BelongsTo
    {
        // Links project to workspace using workspace_id as foreign key
        return $this->belongsTo(Workspace::class, 'workspace_id', 'id');
    }

    /**
     * Defines relationship: many-to-many between Project and User
     *
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        // Uses pivot table 'project_user' with project_id and user_id
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
            ->withPivot('role') // Include 'role' from pivot table
            ->withTimestamps(); // Manage timestamps on pivot table
    }

    /**
     * Defines relationship: one project has many tasks
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        // Links tasks using project_id as foreign key
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    /**
     * Defines relationship: one project has many milestones
     *
     * @return HasMany<Milestone, $this>
     */
    public function milestones(): HasMany
    {
        // Links milestones using project_id as foreign key
        return $this->hasMany(Milestone::class, 'project_id', 'id');
    }

    /**
     * Defines polymorphic relationship: project can have many comments
     *
     * @return MorphMany<Comment, $this>
     */
    public function comments(): MorphMany
    {
        // Links comments using polymorphic relation (commentable_id and commentable_type)
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    /**
     * Defines relationship: one project has many activities
     *
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        // Links activities using project_id as foreign key
        return $this->hasMany(Activity::class, 'project_id', 'id');
    }

    /**
     * Defines relationship: one project has one latest activity
     *
     * @return HasOne<Activity, $this>
     */
    public function latestActivity(): HasOne
    {
        // Gets only the most recent activity using latestOfMany()
        return $this->hasOne(Activity::class, 'project_id', 'id')->latestOfMany();
    }
}
