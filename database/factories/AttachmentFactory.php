<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
final class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attachable_id' => null,
            'attachable_type' => null,
            'user_id' => User::factory(),
            'path' => $this->faker->filePath(),
            'disk' => 'local',
            'original_name' => $this->faker->word().'.txt',
            'mime_type' => 'text/plain',
            'size' => $this->faker->numberBetween(100, 5000),
        ];
    }
}
