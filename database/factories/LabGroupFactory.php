<?php

namespace Database\Factories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LabGroup>
 */
class LabGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lab_id' => Lab::factory(),
            'nomor' => fake()->unique()->numberBetween(1, 100),
            'nama' => null,
        ];
    }
}
