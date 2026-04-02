<?php

declare(strict_types=1);

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has a commentable polymorphic relationship', function (): void {
    $comment = new Comment();

    expect($comment->commentable())
        ->toBeInstanceOf(MorphTo::class);
});

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
