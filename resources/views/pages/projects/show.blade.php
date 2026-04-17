<x-app>
    <div class="p-6 space-y-6 text-white">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
            <p class="text-gray-500">{{ $project->description }}</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 border-b pb-2">
            <a href="{{ route('projects.show', $project->id) }}"
            class="px-3 py-1 {{ request()->routeIs('projects.show') ? 'border-b-2 border-blue-500' : '' }}">
                Overview
            </a>

             <a href="{{ route('projects.add-member', $project->id) }}"
                class="px-3 py-1 {{ request()->routeIs('projects.add-member') ? 'border-b-2 border-blue-500' : '' }}">
                Add Members
            </a>


            <a href="{{ route('projects.setting', $project->id) }}"
            class="px-3 py-1 {{ request()->routeIs('projects.settings') ? 'border-b-2 border-blue-500' : '' }}">
                Settings
            </a>
        </div>

        <!-- Overview Content -->
        <div class="space-y-4">

            <div class="border p-4">
                <h2 class="font-semibold">Status</h2>
                <p>{{ $project->status }}</p>
            </div>

            <div class="border p-4">
                <h2 class="font-semibold">Dates</h2>
                <p>{{ $project->start_date }} → {{ $project->end_date }}</p>
            </div>

            <div class="border p-4">
                <h2 class="font-semibold">Budget</h2>
                <p>{{ $project->budget_hours }} hours</p>
            </div>

        </div>

    </div>
</x-app>