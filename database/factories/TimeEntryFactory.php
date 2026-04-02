<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeEntry>
 */
final class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'minutes' => $this->faker->numberBetween(1, 480),
            'description' => $this->faker->optional()->sentence(),
            'logged_date' => $this->faker->date(),
        ];
    }
}
