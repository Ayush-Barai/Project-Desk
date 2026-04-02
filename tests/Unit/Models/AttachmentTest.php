<?php

declare(strict_types=1);
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

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

it('belongs to user', function (): void {
    $user = User::factory()->create();

    $attachment = Attachment::factory()->create(fn (array $attributes): array => [
        'user_id' => $user->id,
        'attachable_id' => Project::factory()->create()->id,
        'attachable_type' => Project::class,
    ]);

    expect($attachment->user)->toBeInstanceOf(User::class);
});

it('morphs to task', function (): void {
    $task = Task::factory()->create();

    $attachment = Attachment::factory()->create([
        'attachable_id' => $task->id,
        'attachable_type' => Task::class,
    ]);

    expect($attachment->attachable)->toBeInstanceOf(Task::class);
});
