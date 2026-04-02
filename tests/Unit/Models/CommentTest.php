<?php

declare(strict_types=1);
/**
 * CommentTest
 *
 * Tests for the Comment model's relationships and functionality.
 * Verifies polymorphic comments can be attached to projects, tasks, and other models.
 * Validates comment authorship and attachment associations.
 */
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test: Verify comment has polymorphic commentable relationship
// Description: Ensures the commentable() method returns the correct polymorphic relationship instance,
// validating the foundational relationship structure
it('has a commentable polymorphic relationship', function (): void {
    $comment = new Comment();

    expect($comment->commentable())
        ->toBeInstanceOf(MorphTo::class);
});

// Test: Verify comment can be attached to commentable models
// Description: Ensures comments can be correctly associated with different model types (projects, tasks, etc.)
// through polymorphic relationships, demonstrating the relationship functions properly
it('can belong to a commentable model', function (): void {
    $post = Project::factory()->create();

    $comment = Comment::factory()->create([
        'commentable_id' => $post->id,
        'commentable_type' => $post->getMorphClass(),
    ]);

    expect($comment->commentable)
        ->toBeInstanceOf(Project::class)
        ->id->toBe($post->id);
});

// Test: Verify comment resolves correct commentable model type
// Description: Ensures the commentable_type is correctly stored and reflects the actual model class,
// enabling polymorphic relationships to properly resolve to the correct model type
it('resolves the correct commentable model type', function (): void {
    $post = Project::factory()->create();

    $comment = Comment::factory()->create([
        'commentable_id' => $post->id,
        'commentable_type' => $post->getMorphClass(),
    ]);

    expect($comment->commentable_type)
        ->toBe($post->getMorphClass());
});

it('comment morphs to task', function (): void {
    $task = Task::factory()->create();

    $comment = Comment::factory()->create([
        'commentable_id' => $task->id,
        'commentable_type' => Task::class,
    ]);

    expect($comment->commentable)->toBeInstanceOf(Task::class);
});

it('belongs to user', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach(['user_id' => $user->id]);
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
    ]);

    expect($comment->user)->toBeInstanceOf(User::class);
});

it('has many attachments', function (): void {
    $comment = Comment::factory()->create([
        'commentable_id' => Project::factory()->create()->id,
        'commentable_type' => Project::class,
    ]);

    Attachment::factory()->create([
        'attachable_id' => $comment->id,
        'attachable_type' => Comment::class,
    ]);

    expect($comment->attachments)->toHaveCount(1);
});
