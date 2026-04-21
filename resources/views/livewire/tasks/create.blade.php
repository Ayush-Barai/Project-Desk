<div class="border rounded-lg p-4 space-y-4 text-white">

    <h2 class="text-lg font-semibold">Create Task</h2>

    <!-- Title -->
    <div>
        <label class="block text-sm mb-1">Task Title</label>
        <input type="text" wire:model="form.title" class="border p-2 w-full">
        @error('form.title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm mb-1">Description</label>
        <textarea wire:model="form.description" class="border p-2 w-full"></textarea>
        @error('form.description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Status & Priority -->
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label>Status</label>
            <select wire:model="form.status" class="border p-2 w-full">
                <option value="">Select Status</option>
                <option value="Backlog">Backlog</option>
                <option value="Todo">Todo</option>
                <option value="InProgress">In Progress</option>
                <option value="InReview">In Review</option>
                <option value="Done">Done</option>
            </select>
            @error('form.status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Priority</label>
            <select wire:model="form.priority" class="border p-2 w-full">
                <option value="">Select Priority</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>
            @error('form.priority') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Due Date -->
    <div>
        <label>Due Date</label>
        <input type="date" wire:model="form.due_date" class="border p-2 w-full">
        @error('form.due_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Assign Member -->
    <div>
        <label>Assign To</label>
        <select wire:model="form.assigned_to" class="border p-2 w-full">
            <option value="">Select Team Member</option>
            @foreach($this->members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>
        @error('form.assigned_to') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Estimated Hours -->
    <div>
        <label>Estimated Hours</label>
        <input type="number" wire:model="form.estimated_hours" class="border p-2 w-full">
        @error('form.estimated_hours') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- ✅ File Upload -->
    <div>
        <label class="block text-sm mb-1">Attachments</label>

        <input type="file" wire:model="form.files" multiple class="border p-2 w-full">

        @error('form.files.*')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <div wire:loading wire:target="form.files" class="text-sm text-gray-400">
            Uploading...
        </div>
    </div>

    <!-- Button -->
    <button wire:click="create" class="bg-blue-600 px-4 py-2 rounded">
        Create Task
    </button>

</div>