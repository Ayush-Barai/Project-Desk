<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
final class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'parent_task_id' => null,
            'milestone_id' => Milestone::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(array_column(TaskStatus::cases(), 'value')),
            'priority' => $this->faker->randomElement(array_column(TaskPriority::cases(), 'value')),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'due_date' => $this->faker->optional()->date(),
            'estimated_hours' => $this->faker->optional()->randomFloat(2, 0, 100),
            'completed_at' => $this->faker->optional()->dateTime(),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
