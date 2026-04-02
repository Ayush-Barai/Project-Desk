<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read string $email
 * @property-read CarbonInterface|null $email_verified_at
 * @property-read string $password
 * @property-read string|null $remember_token
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class User extends Authenticatable implements MustVerifyEmail
{
    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory;

    use HasRoles;
    use HasUuids;
    use Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * These sensitive attributes are removed from model arrays and JSON
     * to prevent exposure in API responses or logs.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * Defines type casting for model attributes including hashed passwords,
     * datetime conversions, and string normalizations.
     *
     * @return array<string, string> The cast definitions
     */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'avatar' => 'string',
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Retrieve all workspaces owned by this user.
     *
     * Establishes a one-to-many relationship with Workspace,
     * returning only workspaces where this user is the designated owner.
     *
     * @return HasMany<Workspace, $this> All workspaces created and owned by this user
     */
    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id', 'id');
    }

    /**
     * Retrieve all workspaces this user is a member of.
     *
     * Establishes a many-to-many relationship with Workspace through the 'workspace_user' pivot table,
     * including role information for each membership. Tracks creation/update timestamps on the pivot.
     *
     * @return BelongsToMany<Workspace, $this> All workspaces this user belongs to with roles
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user', 'user_id', 'workspace_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Retrieve all projects this user is assigned to.
     *
     * Establishes a many-to-many relationship with Project through the 'project_user' pivot table,
     * including role information for each project membership. Tracks creation/update timestamps on the pivot.
     *
     * @return BelongsToMany<Project, $this> All projects this user is a member of with roles
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Retrieve all tasks assigned to this user.
     *
     * Establishes a one-to-many relationship with Task using the assigned_to foreign key,
     * returning all tasks where this user is the assignee.
     *
     * @return HasMany<Task, $this> All tasks assigned to this user for completion
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to', 'id');
    }

    /**
     * Retrieve all tasks created by this user.
     *
     * Establishes a one-to-many relationship with Task using the created_by foreign key,
     * returning all tasks where this user is the creator.
     *
     * @return HasMany<Task, $this> All tasks created by this user
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by', 'id');
    }

    /**
     * Retrieve all time entries logged by this user.
     *
     * Establishes a one-to-many relationship with TimeEntry,
     * returning all hours tracked for tasks completed by this user.
     *
     * @return HasMany<TimeEntry, $this> All time entries recorded by this user
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'user_id', 'id');
    }

    /**
     * Retrieve all comments created by this user.
     *
     * Establishes a one-to-many relationship with Comment,
     * returning all comments authored by this user across all projects and tasks.
     *
     * @return HasMany<Comment, $this> All comments created by this user
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'user_id', 'id');
    }
}
