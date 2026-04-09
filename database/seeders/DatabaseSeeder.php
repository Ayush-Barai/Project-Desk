<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Label;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(5)->create(['password' => 'password']);

        // Create workspaces with owners
        $workspaces = Workspace::factory(2)->create();

        // Attach users to workspaces
        foreach ($workspaces as $workspace) {
            $workspace->members()->attach(
                $users->random(3),
                ['role' => 'member']
            );
        }

        // Create projects in each workspace
        foreach ($workspaces as $workspace) {
            $projects = Project::factory(3)->create([
                'workspace_id' => $workspace->id,
            ]);

            // Attach users to projects
            foreach ($projects as $project) {
                $project->members()->attach(
                    $users->random(2),
                    ['role' => 'member']
                );

                // Create milestones for projects
                $milestones = Milestone::factory(3)->create([
                    'project_id' => $project->id,
                ]);

                // Create tasks for projects
                $tasks = Task::factory(5)->create([
                    'project_id' => $project->id,
                    'milestone_id' => $milestones->random()->id,
                    'assigned_to' => $users->random()->id,
                    'created_by' => $users->random()->id,
                ]);

                // Create labels for workspace
                $labels = Label::factory(3)->create([
                    'workspace_id' => $workspace->id,
                ]);

                // Attach labels to tasks
                foreach ($tasks as $task) {
                    $task->labels()->attach($labels->random(random_int(1, 2))->pluck('id')->toArray());

                    // Create time entries for tasks
                    TimeEntry::factory(random_int(2, 5))->create([
                        'task_id' => $task->id,
                        'user_id' => $users->random()->id,
                    ]);

                    // Create comments for tasks
                    Comment::factory(random_int(0, 3))->create([
                        'user_id' => $users->random()->id,
                        'commentable_id' => $task->id,
                        'commentable_type' => Task::class,
                    ]);

                    // Create activities for tasks
                    Activity::factory(random_int(1, 3))->create([
                        'project_id' => $project->id,
                        'task_id' => $task->id,
                        'user_id' => $users->random()->id,
                    ]);

                    // Create attachments for tasks
                    Attachment::factory(random_int(0, 2))->create([
                        'user_id' => $users->random()->id,
                        'attachable_id' => $task->id,
                        'attachable_type' => Task::class,
                    ]);
                }
            }
        }
    }
}
