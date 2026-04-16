<x-app>
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
        <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-4 border p-4">
            @csrf
            @method('PATCH')

            <h2 class="font-semibold">Edit Project</h2>

            <!-- Name -->
            <input type="text" name="name"
                   value="{{ old('name', $project->name) }}"
                   class="border p-2 w-full" placeholder="Project Name">
            @error('name') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

            <!-- Description -->
            <textarea name="description"
                      class="border p-2 w-full"
                      placeholder="Description">{{ old('description', $project->description) }}</textarea>
            @error('description') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-2">
                <input type="date" name="start_date"
                       value="{{ old('start_date', $project->start_date) }}"
                       class="border p-2">

                <input type="date" name="end_date"
                       value="{{ old('end_date', $project->end_date) }}"
                       class="border p-2">
            </div>

            <!-- Budget -->
            <input type="number" name="budget_hours"
                   value="{{ old('budget_hours', $project->budget_hours) }}"
                   class="border p-2 w-full" placeholder="Budget Hours">
            @error('budget_hours') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

            <!-- Status -->
            <select name="status" class="border p-2 w-full">
                @foreach(['Planning','Active','OnHold','Completed','Archived'] as $status)
                    <option value="{{ $status }}"
                        {{ old('status', $project->status) === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-500 text-white px-4 py-2" type="submit">
                Save Changes
            </button>
        </form>

        <!-- Danger Zone -->
        <div class="border p-4 space-y-4">

            <h2 class="font-semibold text-red-500">Danger Zone</h2>

            <!-- Archive / Restore -->
            @if(!$project->trashed())
                <form method="POST" action="{{ route('projects.archive', $project->id) }}">
                    @csrf
                    @method('PATCH')
                    <button onclick="return confirm('Are you sure?')"
                            class="bg-yellow-500 text-white px-4 py-2">
                        Archive Project
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('projects.restore', $project->id) }}">
                    @csrf
                    @method('PATCH')
                    <button class="bg-green-500 text-white px-4 py-2">
                        Restore Project
                    </button>
                </form>
            @endif

            <!-- Permanent Delete -->
            <form method="POST" action="{{ route('projects.destroy', $project->id) }}">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('This will permanently delete!')"
                        class="bg-red-600 text-white px-4 py-2">
                    Delete Permanently
                </button>
            </form>

        </div>

    </div>
</x-app>