<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
final class ActivityFactory extends Factory
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
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
