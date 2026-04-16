<x-app>
    <div class="p-6 text-white">

        <h1 class="text-xl font-bold mb-4">Projects</h1>

        <a href="{{ route('projects.create') }}" class="bg-green-500 text-white px-3 py-1">
            + Create Project
        </a>

        <div class="mt-4 space-y-2">
            @foreach($projects as $project)
                <div class="border p-3 flex justify-between">
                    <span>{{ $project->name }}</span>

                    <a href="{{ route('projects.show', $project->id) }}"
                    class="text-blue-500">
                        View
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</x-app>

