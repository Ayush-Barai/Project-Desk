<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
final class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $start = $this->faker->date();
        $end = $this->faker->date('Y-m-d', $start);

        return [
            'workspace_id' => Workspace::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 9999),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(array_column(ProjectStatus::cases(), 'value')),
            'start_date' => $start,
            'end_date' => $end,
            'budget_hours' => $this->faker->randomFloat(2, 1, 1000),
            'color' => $this->faker->hexColor(),
        ];
    }
}
