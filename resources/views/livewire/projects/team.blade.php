<div class="p-6 space-y-6 text-white">

    <!-- Header -->
    <div class="flex justify-between">
        <h1 class="text-xl font-bold">Project Team</h1>

        <a href="{{ route('projects.show', $project->id) }}"
           class="text-blue-500">
            ← Back
        </a>
    </div>

    <!-- Add Member -->
    <div class="border p-4 space-y-2">
        <h2 class="font-semibold">Add Member</h2>

        <div x-data="{ open: true }" @click.away="open = false" class="relative">

            <!-- Input -->
            <input type="email"
                   wire:model.live.debounce.150ms="email"
                   @focus="open = true"
                   placeholder="Enter email"
                   class="border p-2 w-full text-white rounded ">

            <!-- Suggestions Dropdown -->
            @if(!empty($suggestions))
                <div x-show="open"
                     class="absolute z-10 text-white w-full border mt-1 rounded shadow max-h-40 overflow-y-auto bg-blue-950">

                    @foreach($suggestions as $user)
                        <div wire:click="selectSuggestion('{{ $user->email }}')"
                             @click="open = false"
                             class="p-2 hover:bg-gray-200 cursor-pointer">

                            <p class="text-sm font-medium ">{{ $user->email }}</p>
                        </div>
                    @endforeach

                </div>
            @endif

        </div>

        <!-- Add Button -->
        <button wire:click="addMember"
                class="bg-blue-500 text-white px-3 py-1 rounded">
            Add
        </button>

        @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Members List -->
    <div class="space-y-2">
        @foreach($this->projectMembers as $member)
            <div class="border p-3 flex justify-between items-center rounded">

                <!-- Member Info -->
                <div>
                    <p>{{ $member->email }}</p>
                    <p class="text-sm text-gray-400">
                        {{ $member->pivot->role }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 items-center">

                    <!-- Role Change -->
                    <select wire:change="updateRole('{{ $member->id }}', $event.target.value)"
                            class="border p-1 rounded bg-blue-950 text-white">

                        <option value="Project Manager"
                                @selected($member->pivot->role === 'Project Manager')>
                            Manager
                        </option>

                        <option value="Contributor"
                                @selected($member->pivot->role === 'Contributor')>
                            Contributor
                        </option>

                        <option value="Viewer"
                                @selected($member->pivot->role === 'Viewer')>
                            Viewer
                        </option>
                    </select>

                    <!-- Remove -->
                    <button wire:click="removeMember('{{ $member->id }}')"
                            class="p-1 text-red-500 hover:text-red-700 border border-red-200 rounded">
                        Remove
                    </button>

                </div>

            </div>
        @endforeach
    </div>

</div>