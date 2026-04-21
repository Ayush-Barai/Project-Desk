<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Carbon\CarbonInterface;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Project Model
 *
 * Represents a project within a workspace. Projects are the main organizational unit containing tasks,
 * milestones, team members, and other related entities. They support complex relationships for managing
 * team collaboration, time tracking, and project progress.
 *
 * @property int $id
 * @property int $workspace_id Workspace this project belongs to
 * @property string $name Project name
 * @property string|null $slug URL-friendly identifier for the project
 * @property string|null $description Project description
 * @property ProjectStatus $status Current status of the project
 * @property CarbonInterface|null $start_date When the project begins
 * @property CarbonInterface|null $end_date When the project is scheduled to end
 * @property float|null $budget_hours Total estimated hours allocated for the project
 * @property string|null $color Hex color code for UI representation
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Workspace $workspace
 */
final class Project extends Model
{
    /**
     * @use HasFactory<ProjectFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be safely filled using the create() or fill() methods
     * without explicit whitelisting in the request or instantiation.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget_hours',
        'color',
    ];

    /**
     * Retrieve the workspace this project belongs to.
     *
     * Establishes the inverse of a one-to-many relationship with Workspace,
     * contextualizing the project within its parent workspace.
     *
     * @return BelongsTo<Workspace, $this> The workspace containing this project
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', 'id');
    }

    /**
     * Retrieve all members assigned to this project.
     *
     * Establishes a many-to-many relationship with User through the 'project_user' pivot table,
     * including role assignments for each team member. Tracks creation/update timestamps on the pivot.
     *
     * @return BelongsToMany<User, $this> All team members assigned to this project with roles
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Retrieve all tasks in this project.
     *
     * Establishes a one-to-many relationship with Task,
     * allowing access to all work items within the project scope.
     *
     * @return HasMany<Task, $this> All tasks in this project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    /**
     * Retrieve all milestones in this project.
     *
     * Establishes a one-to-many relationship with Milestone,
     * enabling organization of tasks into key checkpoints and deliverables.
     *
     * @return HasMany<Milestone, $this> All milestones in this project
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'project_id', 'id');
    }

    /**
     * Retrieve all comments on this project.
     *
     * Establishes a polymorphic one-to-many relationship with Comment,
     * enabling team discussion and collaboration at the project level.
     *
     * @return MorphMany<Comment, $this> All comments attached to this project
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    /**
     * Retrieve all activity records for this project.
     *
     * Establishes a one-to-many relationship with Activity,
     * providing an audit trail of all actions and changes within the project.
     *
     * @return HasMany<Activity, $this> All activity logs for this project
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'project_id', 'id');
    }

    /**
     * Retrieve the most recent activity for this project.
     *
     * Establishes a one-to-one relationship with Activity using latestOfMany(),
     * providing quick access to the latest activity without additional queries.
     *
     * @return HasOne<Activity, $this> The most recent activity in this project
     */
    public function latestActivity(): HasOne
    {
        return $this->hasOne(Activity::class, 'project_id', 'id')->latestOfMany();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
        ];
    }

    /**
     * Scope a query to only include active projects.
     *
     * This scope filters projects based on their status, allowing retrieval of only those that are currently active. It can be used in query builder chains for convenient filtering.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this> if the project is active, false otherwise
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Active);
    }
}
