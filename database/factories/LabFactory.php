<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Lab>
 */
class LabFactory extends Factory
{
    public function definition(): array
    {
        $nama = 'Lab '.fake()->unique()->randomLetter();

        return [
            'nama' => $nama,
            'kode' => Str::upper(Str::slug($nama, '_')).'_'.fake()->unique()->numberBetween(1, 9999),
            'has_groups' => true,
            'keterangan' => fake()->optional()->sentence(),
        ];
    }

    public function withoutGroups(): static
    {
        return $this->state(fn () => ['has_groups' => false]);
    }
}
