<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\LabelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Label Model
 *
 * Represents labels or tags that can be applied to tasks for categorization and organization.
 * Labels belong to a workspace and can be associated with multiple tasks through a many-to-many relationship.
 *
 * @property int $id
 * @property int $workspace_id Workspace this label belongs to
 * @property string $name Label name
 * @property string|null $color Hex color code for UI representation
 * @property string|null $description Optional description of the label
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Label extends Model
{
    /**
     * @use HasFactory<LabelFactory>
     */
    use HasFactory;

    /**
     * Retrieve the workspace this label belongs to.
     *
     * Establishes the inverse of a one-to-many relationship with Workspace,
     * scoping the label to its parent workspace for organizational isolation.
     *
     * @return BelongsTo<Workspace, $this> The workspace this label is part of
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', 'id');
    }

    /**
     * Retrieve all tasks tagged with this label.
     *
     * Establishes a many-to-many relationship with Task through the 'label_task' pivot table,
     * allowing a label to be applied to multiple tasks and enabling task filtering by labels.
     *
     * @return BelongsToMany<Task, $this> All tasks associated with this label
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'label_task', 'label_id', 'task_id');
    }
}
