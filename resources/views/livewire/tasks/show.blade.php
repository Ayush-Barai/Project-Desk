<div>
    <div class="border p-4 space-y-3 text-white">
        <h2 class="font-bold">{{ $task->title }}</h2>

        <p>{{ $task->description }}</p>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <h3 class="font-semibold">Status</h3>
                <p>{{ $task->status }}</p>
            </div>

            <div>
                <h3 class="font-semibold">Priority</h3>
                <p>{{ $task->priority }}</p>
            </div>
        </div>

        <div>
            <h3 class="font-semibold">Due Date</h3>
            <p>{{ $task->due_date }}</p>
        </div>

        <div>
            <h3 class="font-semibold">Assigned To</h3>
            <p>{{ optional($task->assignee)->email ?? 'Unassigned' }}</p>
        </div>

        <div>
            <h3 class="font-semibold">Estimated Hours</h3>
            <p>{{ $task->estimated_hours }} hours</p>
        </div>

    </div>
</div>