<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\AttachmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Attachment Model
 *
 * Represents file attachments that can be associated with various models through polymorphic relationships.
 * Files are stored and linked to users, enabling attachments on projects, tasks, comments, and more.
 *
 * @property int $id
 * @property int $user_id User who uploaded the attachment
 * @property string $file_path Path to the stored file
 * @property string $file_name Original file name
 * @property string $mime_type MIME type of the file
 * @property int $file_size Size of the file in bytes
 * @property string $attachable_type Model class of the parent entity
 * @property int $attachable_id ID of the parent entity
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Attachment extends Model
{
    /**
     * @use HasFactory<AttachmentFactory>
     */
    use HasFactory;

    /**
     * Retrieve the parent model this attachment is associated with.
     *
     * Establishes a polymorphic relationship allowing attachments to be associated
     * with multiple model types (Project, Task, Comment, etc.) through a single relationship.
     *
     * @return MorphTo<Model, $this> The parent model this file is attached to
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo('attachable', 'attachable_type', 'attachable_id');
    }

    /**
     * Retrieve the user who uploaded this attachment.
     *
     * Establishes the inverse of a one-to-many relationship with User,
     * identifying who uploaded the file.
     *
     * @return BelongsTo<User, $this> The user who uploaded this attachment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
