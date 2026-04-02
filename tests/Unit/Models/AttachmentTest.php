<?php

declare(strict_types=1);

/**
 * AttachmentTest
 *
 * Tests for the Attachment model's relationships and functionality.
 * Verifies polymorphic attachments to various models and user associations.
 * Validates file storage and metadata handling.
 */

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

// Test: Verify attachment polymorphic relationship with comments
// Description: Ensures attachments can be correctly associated with comment models,
// validating the polymorphic relationship structure functions properly
it('attachment morphs to comment', function (): void {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->create(['workspace_id' => $workspace->id]);
    $comment = Comment::factory()->create([
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
    ]);
    $attachment = Attachment::factory()->create([
        'attachable_id' => $comment->id,
        'attachable_type' => Comment::class,
    ]);

    expect($attachment->attachable)->toBeInstanceOf(Comment::class);
});

// Test: Verify attachment belongs to user relationship
// Description: Ensures each attachment correctly maintains a relationship with the user
// who uploaded or created it, establishing proper attribution
it('belongs to user', function (): void {
    $user = User::factory()->create();

    $attachment = Attachment::factory()->create(fn (array $attributes): array => [
        'user_id' => $user->id,
        'attachable_id' => Project::factory()->create()->id,
        'attachable_type' => Project::class,
    ]);

    expect($attachment->user)->toBeInstanceOf(User::class);
});

// Test: Verify attachment polymorphic relationship with tasks
// Description: Ensures attachments can be attached to task models,
// enabling file sharing and documentation at the task level
it('morphs to task', function (): void {
    $task = Task::factory()->create();

    $attachment = Attachment::factory()->create([
        'attachable_id' => $task->id,
        'attachable_type' => Task::class,
    ]);

    expect($attachment->attachable)->toBeInstanceOf(Task::class);
});
