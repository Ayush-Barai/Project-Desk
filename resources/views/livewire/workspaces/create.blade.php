<div class="min-h-screen flex items-center justify-center bg-gray-950 text-white px-4">

    <div class="w-full max-w-lg bg-gray-900 border border-gray-800 rounded-2xl shadow-xl p-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-semibold">Create Workspace</h1>
            <p class="text-sm text-gray-400 mt-1">
                Set up a new workspace to manage your projects and team.
            </p>
        </div>

        <!-- Form -->
        <form wire:submit="create" class="space-y-5">

            <!-- Name -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">
                    Workspace Name
                </label>

                <input type="text"workspace
                       wire:model="name"
                       placeholder="e.g. Acme Inc"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">
                    Description (optional)
                </label>

                <textarea wire:model="description"
                          rows="3"
                          placeholder="What is this workspace about?"
                          class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium shadow">
                Create Workspace
            </button>

        </form>

    </div>

</div>