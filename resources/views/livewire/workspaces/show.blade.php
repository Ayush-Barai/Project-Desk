<div class="p-6 max-w-5xl mx-auto space-y-8 text-white">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold">{{ $workspace->name }}</h1>
            <p class="text-gray-400 mt-1 text-sm">
                {{ $workspace->description }}
            </p>
        </div>

        <a href="{{ route('workspaces.members', $workspace) }}"
           class="bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-lg font-medium shadow">
            Manage Members
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-6">
        
        <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 shadow">
            <p class="text-sm text-gray-400">Total Members</p>
            <p class="text-3xl font-bold mt-1">{{ $this->stats['members'] }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 shadow">
            <p class="text-sm text-gray-400">Total Projects</p>
            <p class="text-3xl font-bold mt-1">{{ $this->stats['projects'] }}</p>
        </div>

    </div>

    <!-- Members Section -->
    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow">
        
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold">Members</h2>
        </div>

        <div class="divide-y divide-gray-700">
            @forelse($this->members as $member)
                <div class="flex items-center justify-between p-4 hover:bg-gray-800 transition">

                    <div>
                        <p class="text-sm font-medium">{{ $member->email }}</p>
                        <p class="text-xs text-gray-400">User</p>
                    </div>

                    <span class="text-xs px-2 py-1 rounded-full 
                        @if($member->pivot->role === 'owner') bg-purple-600
                        @elseif($member->pivot->role === 'admin') bg-blue-600
                        @else bg-gray-600
                        @endif
                    ">
                        {{ $member->pivot->role }}
                    </span>

                </div>
            @empty
                <div class="p-4 text-center text-gray-400 text-sm">
                    No members found
                </div>
            @endforelse
        </div>
    </div>

    <!-- Projects Section -->
    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow">

        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Projects</h2>

            <a href="#" class="text-sm bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg">
                + New Project
            </a>
        </div>

        @if($this->projects->isEmpty())
            <div class="p-6 text-center text-gray-400 text-sm">
                No projects yet 🚀
            </div>
        @else
            <div class="divide-y divide-gray-700">
                @foreach($this->projects as $project)
                    <a href="#"
                        class="flex items-center justify-between p-4 hover:bg-gray-800 transition">

                        <div>
                            <p class="text-sm font-medium">{{ $project->name }}</p>
                            <p class="text-xs text-gray-400">Project</p>
                        </div>

                        <span class="text-xs px-2 py-1 rounded-full 
                            @if($project->status === 'completed') bg-green-600
                            @elseif($project->status === 'in_progress') bg-yellow-500
                            @else bg-gray-600
                            @endif
                        ">
                            {{  ($project->status) }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

    </div>

</div>