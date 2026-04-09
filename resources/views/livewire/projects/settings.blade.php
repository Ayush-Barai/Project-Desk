<div class="p-6 space-y-6 text-white">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Project Settings</h1>

        <a href="{{ route('projects.show', $project->id) }}"
           class="text-blue-500">
            ← Back
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2">
            {{ session('success') }}
        </div>
    @endif

    <!-- Update Form -->
    <form wire:submit="update" class="space-y-4 border p-4">

        <h2 class="font-semibold">Edit Project</h2>

        <input type="text" wire:model="name"
               class="border p-2 w-full" placeholder="Project Name">

        <textarea wire:model="description"
                  class="border p-2 w-full" placeholder="Description"></textarea>

        <div class="grid grid-cols-2 gap-2">
            <input type="date" wire:model="start_date" class="border p-2">
            <input type="date" wire:model="end_date" class="border p-2">
        </div>

        <input type="number" wire:model="budget_hours"
               class="border p-2 w-full" placeholder="Budget Hours">
        @error('budget_hours') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror


        <select wire:model="status" class="border p-2 w-full">
            <option>Planning</option>
            <option>Active</option>
            <option>OnHold</option>
            <option>Completed</option>
            <option>Archived</option>
        </select>

        <button class="bg-blue-500 text-white px-4 py-2" wire:click="update">
            Save Changes
        </button>

    </form>

    <!-- Danger Zone -->
    <div class="border p-4 space-y-4">

        <h2 class="font-semibold text-red-500">Danger Zone</h2>

        <!-- Archive -->
        @if(!$project->trashed())
            <button wire:click="archive"
                    wire:confirm="Are you sure you want to archive this project?"
                    class="bg-yellow-500 text-white px-4 py-2">
                Archive Project
            </button>
        @else
            <!-- Restore -->
            <button wire:click="restore"
                    class="bg-green-500 text-white px-4 py-2">
                Restore Project
            </button>
        @endif

        <!-- Permanent Delete -->
        <button wire:click="delete"
                wire:confirm="This will permanently delete the project!"
                class="bg-red-600 text-white px-4 py-2">
            Delete Permanently
        </button>

    </div>

</div>