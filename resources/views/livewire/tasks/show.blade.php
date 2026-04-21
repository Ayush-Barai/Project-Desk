<div class="space-y-6 p-6 text-white">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="w-full">
            <input
                type="text"
                wire:model.live.debounce.500ms="title"
                class="text-2xl font-bold border-none focus:ring-0 w-full"
            />
            @error('title')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <p class="text-gray-500 mt-1">
                Project: {{ $task->project->name }}
            </p>
        </div>

        <a href="{{ route('projects.show', $task->project_id) }}"
           class="text-blue-600">
            Back
        </a>
    </div>

    <!-- Editable Info -->
    <div class="border rounded-lg p-4 space-y-4">

        <div class="grid grid-cols-2 gap-4">

            <!-- Status -->
            <div>
                <label class="text-sm text-gray-500">Status</label>
                <select wire:model.live="status" class="border p-2 rounded w-full">
                    <option>Backlog</option>
                    <option>Todo</option>
                    <option>InProgress</option>
                    <option>InReview</option>
                    <option>Done</option>
                </select>
            </div>

            <!-- Priority -->
            <div>
                <label class="text-sm text-gray-500">Priority</label>
                <select wire:model.live="priority" class="border p-2 rounded w-full">
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                    <option>Urgent</option>
                </select>
            </div>

            <!-- Assignee -->
            <div>
                <label class="text-sm text-gray-500">Assignee</label>
                <select wire:model.live="assigned_to" class="border p-2 rounded w-full">
                    <option value="">Unassigned</option>

                    @foreach($task->project->members as $member)
                        <option value="{{ $member->id }}">
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Creator -->
            <div>
                <label class="text-sm text-gray-500">Created By</label>
                <p class="mt-2">{{ $task->creator?->name }}</p>
            </div>

        </div>

    </div>

    <!-- Description -->
    <div class="border rounded-lg p-4">
        <h2 class="font-semibold mb-2">Description</h2>
        <p>{{ $task->description ?: 'No description available.' }}</p>
    </div>

    <!-- ✅ Files Section -->
    <div class="border rounded-lg p-4">
        <h2 class="font-semibold mb-3">Attachments</h2>

        @forelse($task->attachments as $file)
            <div class="flex justify-between items-center py-2 border-b">

                <a
                    href="/storage/{{ $file->path }}"
                    wire:click.prevent="openAttachment({{ $file->id }})"
                    target="_blank"
                    class="text-blue-400 text-sm hover:underline"
                >
                    {{ basename($file->original_name) }}
                </a>

                <!-- ✅ Delete File -->
                <button
                    wire:click="deleteFile({{ $file->id }})"
                    class="text-red-400 text-xs hover:underline"
                >
                    Delete
                </button>

            </div>
        @empty
            <p class="text-gray-500">No attachments uploaded.</p>
        @endforelse

        <!-- ✅ Upload More Files -->
        <div class="mt-4 space-y-2">
            <input type="file" wire:model="newFiles" multiple class="border p-2 w-full">

            @error('newFiles.*')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <div wire:loading wire:target="newFiles" class="text-sm text-gray-400">
                Uploading...
            </div>

            @if ($newFiles)
                @foreach ($newFiles as $index => $file)
                    <div class="flex justify-between text-sm">
                        <span>{{ $file->getClientOriginalName() }}</span>

                        <button
                            type="button"
                            wire:click="removeUpload('newFiles', '{{ $file->getFilename() }}', {{ $index }})"
                            class="text-red-400 text-xs"
                        >
                            Remove
                        </button>
                    </div>
                @endforeach

                <button
                    wire:click="uploadFiles"
                    class="bg-green-600 px-3 py-1 rounded text-sm"
                >
                    Upload Files
                </button>
            @endif
        </div>
    </div>

    <!-- Subtasks -->
    <div class="border rounded-lg p-4">
        <h2 class="font-semibold mb-3">Subtasks</h2>

          <a 
            href = "{{ route('task.create' , [$project , $task] ) }}"
            class="bg-blue-500 text-white px-3 py-1">
            Create Task
        </a>


        @forelse($task->subtasks as $subtask)
            <div class="flex justify-between py-2 border-b">
                <div>
                    <p>{{ $subtask->title }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $subtask->status }}
                    </p>
                </div>
                <a href="{{ route('task.show',  ['project' => $project, 'task' => $subtask]) }}"
                   class="text-blue-500 text-sm">
                    View
                </a>
            </div>
        @empty
            <p class="text-gray-500">No subtasks available.</p>
        @endforelse
    </div>

</div>