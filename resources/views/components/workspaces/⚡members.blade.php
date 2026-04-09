<?php

use Livewire\Component;
use App\Models\Workspace;
use App\Models\User;

new class extends Component {

    public Workspace $workspace;
    public $email = '';
    public $suggestions =  [];

    public function updatedEmail()
    {
        if (strlen($this->email) > 1) {
            $this->suggestions = User::where('email', 'like', $this->email . '%')
                ->limit(5)
                ->get();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectEmail($email)
    {
        $this->email = $email;
        $this->suggestions = [];
    }

    public function mount(Workspace $workspace) : void
    {
        $this->workspace = $workspace;
    }

    public function addMember() : void
    {
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'User not found');
            return;
        }
        else if($this->workspace->members()->find($user->id))
        {
            $this->addError('email', 'User is already a member');
            return;
        }
      
        $this->workspace->members()->attach($user->id, [
            'role' => 'Member'
        ]);

        $this->email = '';
    }

    public function updateRole($userId, $role)  : void
    {
        $this->workspace->members()->updateExistingPivot($userId, [
            'role' => $role
        ]);
    }

};

?>
<div class="p-6 max-w-2xl mx-auto text-white">
    
    <!-- Header -->
    <h1 class="text-2xl font-semibold mb-6">Workspace Members</h1>

    <!-- Add Member Card -->
    <div class="bg-gray-900 border border-gray-700 rounded-xl p-4 mb-6 shadow">

        <label class="block text-sm text-gray-400 mb-2">Invite Member</label>

        <div class="relative">
            <input type="email"
                   wire:model.live.debounce.400ms="email"
                   placeholder="Type email..."
                   class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Suggestions Dropdown -->
            @if(!empty($suggestions))
                <ul class="absolute z-10 w-full bg-gray-800 border border-gray-600 rounded-lg mt-1 max-h-40 overflow-y-auto shadow-lg">
                    @foreach($suggestions as $user)
                        <li wire:click="selectEmail('{{ $user->email }}')"
                            class="px-3 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition">
                            {{ $user->email }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Button + Error -->
        <div class="flex items-center justify-between mt-3">
            <button wire:click="addMember"
                    class="bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-lg font-medium">
                Add Member
            </button>

            @error('email')
                <span class="text-red-400 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow divide-y divide-gray-700">
        
        @forelse($workspace->members as $user)
            <div class="flex items-center justify-between p-4 hover:bg-gray-800 transition">

                <!-- User Info -->
                <div>
                    <p class="text-sm font-medium">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400">Member</p>
                </div>

                <!-- Role Selector -->
                <select wire:change="updateRole({{ $user->id }}, $event.target.value)"
                        class="bg-gray-800 border border-gray-600 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                    
                    <option value="Member" @selected($user->pivot->role === 'member')>Member</option>
                    <option value="Admin" @selected($user->pivot->role === 'admin')>Admin</option>
                    <option value="Owner" @selected($user->pivot->role === 'owner')>Owner</option>
                </select>
            </div>

        @empty
            <div class="p-4 text-gray-400 text-sm text-center">
                No members yet
            </div>
        @endforelse

    </div>
</div>