<?php

namespace Database\Factories;

use App\Models\LabGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LabTable>
 */
class LabTableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lab_group_id' => LabGroup::factory(),
            'nomor' => fake()->unique()->numberBetween(1, 100),
            'nama' => null,
        ];
    }
}
