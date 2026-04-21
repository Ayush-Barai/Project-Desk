<div class="p-6 max-w-3xl mx-auto text-white space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold">Your Workspaces</h1>
            <p class="text-sm text-gray-400 mt-1">
                Switch between your workspaces or create a new one
            </p>
        </div>

        <a href="{{ route('workspaces.create') }}"
           class="bg-green-600 hover:bg-green-700 transition px-4 py-2 rounded-lg font-medium shadow">
            + New Workspace
        </a>
    </div>

    <!-- Workspace List -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl shadow divide-y divide-gray-800">

        @forelse($this->workspaces as $workspace)
            <div class="flex items-center justify-between p-4 hover:bg-gray-600 transition rounded-lg">
                <!-- Left -->
                <a href="{{ route('workspaces.show', $workspace) }}"
                   class="flex-1">
                    
                    <p class="text-sm font-medium">{{ $workspace->name }}</p>
                    
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $workspace->description ?? 'No description' }}
                    </p>
                </a>

            </div>
        @empty
            <div class="p-6 text-center text-gray-400 text-sm">
                No workspaces yet 🚀
            </div>
        @endforelse

    </div>
    <!-- Pagination -->
    <div class="mt-4">
        {{ $this->workspaces->links() }}
    </div>

</div>