<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

final class ShowTask extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public array $newFiles = [];

    public Task $task;

    public string $title;

    public string $status;

    public string $priority;

    public $assigned_to;

    public function mount(Task $task, Project $project): void
    {
        $this->authorize('view', [$task, $project]);

        $this->task = $task->load([
            'project.members',
            'assignee',
            'creator',
            'subtasks',
            'attachments',
        ]);

        $this->title = $task->title;
        $this->status = $task->status->value;
        $this->priority = $task->priority->value;
        $this->assigned_to = $task->assigned_to;
    }

    public function updated($field): void
    {
        $this->authorize('update', $this->task);

        $this->validate([
            'title' => ['required', 'string', 'min:3'],
            'status' => ['required'],
            'priority' => ['required'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $this->task->update([
            'title' => $this->title,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
        ]);

        $this->task->refresh();
    }

    public function uploadFiles(): void
    {
        $this->validate([
            'newFiles.*' => 'file|max:10240',
        ]);

        foreach ($this->newFiles as $file) {
            $path = $file->store('tasks', 'public');
            $this->task->attachments()->create([
                'user_id' => auth()->id(),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'attachable_type' => Task::class,
                'attachable_id' => $this->task->id,
            ]);
        }

        $this->reset('newFiles');
    }

    public function downloadAttachment($fileId)
    {
        $file = $this->task->attachments()->findOrFail($fileId);
        return response()->download(storage_path('app/public/'.$file->path), $file->original_name);
    }

    public function deleteFile($fileId): void
    {
        $file = $this->task->attachments()->findOrFail($fileId);

        Storage::disk('public')->delete($file->path);

        $file->delete();
    }

    public function render(): Factory|View
    {
        return view('livewire.tasks.show', [
            'project' => $this->task->project,
        ]);
    }
}
