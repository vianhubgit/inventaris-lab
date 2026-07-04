<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->admin(),
            'action' => fake()->randomElement(['created', 'updated', 'deleted', 'login']),
            'subject_type' => null,
            'subject_id' => null,
            'description' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
