<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Comment Model
 *
 * Represents comments that can be attached to various models through polymorphic relationships.
 * Comments enable discussion and collaboration on projects, tasks, and other entities within the system.
 *
 * @property int $id
 * @property int $user_id User who created the comment
 * @property string $content The comment text content
 * @property string $commentable_type Model class of the parent entity
 * @property int $commentable_id ID of the parent entity
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Comment extends Model
{
    /**
     * @use HasFactory<CommentFactory>
     */
    use HasFactory;

    /**
     * Retrieve the parent model this comment is attached to.
     *
     * Establishes a polymorphic relationship allowing comments to be attached to multiple
     * model types (Project, Task, etc.) through a single relationship definition.
     *
     * @return MorphTo<Model, $this> The parent model this comment belongs to
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo('commentable', 'commentable_type', 'commentable_id');
    }

    /**
     * Retrieve the user who created this comment.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying the author of the comment.
     *
     * @return BelongsTo<User, $this> The user who authored this comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Retrieve all attachments associated with this comment.
     *
     * Establishes a polymorphic one-to-many relationship with Attachment,
     * allowing files to be attached directly to comments for reference and information sharing.
     *
     * @return MorphMany<Attachment, $this> All files attached to this comment
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable', 'attachable_type', 'attachable_id');
    }
}
