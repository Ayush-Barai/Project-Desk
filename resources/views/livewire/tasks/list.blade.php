<div class="space-y-3 mt-6 text-white   ">

    <h2 class="font-bold text-lg">Tasks</h2>

    @forelse($this->tasks as $task)
        <div class="border p-3 flex justify-between">

           <a href="{{ route('task.show' , ['project'=>$project , 'task'=>$task ] ) }}">
             <div>
                <p class="font-medium">{{ $task->title }}</p>
                <p class="text-sm text-gray-500">
                    {{ $task->status }} • {{ $task->priority }}
                </p>
            </div>

            <span class="text-sm">
                {{ optional($task->assignee)->email }}
            </span>
           </a>
            <button wire:click="delete({{ $task->id }})" class="text-red-500 border border-red-500 rounded px-2 py-1 cursor-pointer">
                Delete
            </button>
    </div>
    @empty
        <p class="text-gray-500">No tasks yet.</p>
    @endforelse

     <a 
        href = "{{ route('task.create' , $project ) }}"
        class="bg-blue-500 text-white px-3 py-1">
        Create Task
    </a>
    

</div>