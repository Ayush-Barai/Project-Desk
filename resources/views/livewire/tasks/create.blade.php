<div class="border p-4 space-y-3 text-white">
    <h2 class="font-bold">Create Task</h2>

    <input wire:model.live="title" placeholder="Title" class="border p-2 w-full">

    <textarea wire:model.live="description" placeholder="Description"
              class="border p-2 w-full"></textarea>

    <div class="grid grid-cols-2 gap-2">
        <select wire:model.live="status" class="border p-2 bg-gray-900">
            <option>Todo</option>
            <option>InProgress</option>
            <option>Done</option>
        </select>

        <select wire:model.live="priority" class="border p-2 bg-gray-900">
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            <option>Urgent</option>
        </select>
    </div>

    <input type="date" wire:model.live="due_date" class="border p-2 w-full">

    <select wire:model.live="assigned_to" class="border p-2 w-full">
        <option value="">Assign User</option>
        @foreach($this->members as $user)
            <option value="{{ $user->id }}">{{ $user->email }}</option>
        @endforeach
    </select>

    <input type="number" wire:model.live="estimated_hours"
           class="border p-2 w-full" placeholder="Estimated Hours">

    <button wire:click="create"
            class="bg-blue-500 text-white px-3 py-1">
        Create Task
    </button>

</div>