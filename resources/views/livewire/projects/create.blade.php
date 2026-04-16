<div class="max-w-2xl mx-auto p-6 text-white">

    <!-- Card -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Create New Project</h1>
            <p class="text-gray-400 text-sm mt-1">
                Fill in the details to create a new project in your workspace.
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="create" class="space-y-5">

            <!-- Project Name -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Project Name</label>
                <input wire:model="form.name"
                       type="text"
                       placeholder="e.g. Website Redesign"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Description</label>
                <textarea wire:model="form.description"
                          rows="3"
                          placeholder="Short description about the project..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Start Date</label>
                    <input type="date"
                           wire:model="form.start_date"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('start_date') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">End Date</label>
                    <input type="date"
                           wire:model="form.end_date"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('end_date') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Budget -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Budget Hours</label>
                <input type="number"
                       wire:model="form.budget_hours"
                       placeholder="e.g. 120"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('budget_hours') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">

                <a href="{{ url()->previous() }}"
                   class="px-4 py-2 rounded-lg border border-gray-700 hover:bg-gray-800 text-sm">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-medium">
                    Create Project
                </button>

            </div>

        </form>
    </div>

</div>