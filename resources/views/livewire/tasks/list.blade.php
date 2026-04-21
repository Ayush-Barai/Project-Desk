<div class="space-y-6 p-6 text-white">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Tasks</h2>

        <a href="{{ route('projects.show', $project->id) }}"
           class="text-blue-600">
            Back
        </a>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-4 gap-4">

        <!-- Search -->
        <input
            type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Search tasks..."
            class="border p-2 rounded"
        >

        <!-- Status -->
        <select wire:model.live="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option>Backlog</option>
            <option>Todo</option>
            <option>InProgress</option>
            <option>InReview</option>
            <option>Done</option>
        </select>

        <!-- Priority -->
        <select wire:model.live="priority" class="border p-2 rounded">
            <option value="">All Priority</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            <option>Urgent</option>
        </select>

        <!-- Assignee -->
        <select wire:model.live="assignee" class="border p-2 rounded">
            <option value="">All Members</option>

            @foreach($project->members as $member)
                <option value="{{ $member->id }}">
                    {{ $member->name }}
                </option>
            @endforeach
        </select>

    </div>

    <!-- Task List -->
    <div class="space-y-4">

        @forelse($this->tasks as $task)
            <div class="border rounded-lg p-4 flex justify-between items-center">

                <div>
                    <a href="{{ route('task.show', ['project' => $project, 'task' => $task]) }}"
                       class="font-semibold text-lg text-blue-600">
                        {{ $task->title }}
                    </a>

                    <div class="text-sm text-gray-500 mt-1">
                        {{ $task->status }}
                        •
                        {{ $task->priority }}
                    </div>

                    <div class="text-sm text-gray-400 mt-1">
                        Assigned:
                        {{ $task->assignee?->name ?? 'Unassigned' }}
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        Subtasks: {{ $task->subtasks->count() }}
                    </div>
                </div>

                <a href="{{ route('task.show', ['project' => $project, 'task' => $task]) }}"
                   class="text-blue-500">
                    View
                </a>

            </div>

        @empty
            <div class="text-gray-500">
                No tasks found.
            </div>
        @endforelse

    </div>

    <!-- Pagination -->
    <div>
        {{ $this->tasks->links() }}
    </div>

</div>