<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Workspace>
 */
final class WorkspaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'name' => $name,
            'description' => $this->faker->optional()->sentence(),
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 9999),
            'owner_id' => User::factory(),
        ];
    }
}
