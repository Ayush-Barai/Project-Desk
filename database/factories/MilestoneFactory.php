<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
final class MilestoneFactory extends Factory
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
            'title' => $this->faker->sentence(4),
            'due_date' => $this->faker->date(),
            'description' => $this->faker->optional()->paragraph(),
            'is_completed' => $this->faker->boolean(20), // 20% chance completed
        ];
    }
}
