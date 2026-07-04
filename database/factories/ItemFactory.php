<?php

namespace Database\Factories;

use App\Enums\ItemStatus;
use App\Models\Category;
use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->words(2, true),
            'category_id' => Category::factory(),
            'lab_id' => Lab::factory(),
            'lab_table_id' => null,
            'jumlah_total' => fake()->numberBetween(1, 50),
            'status' => ItemStatus::BAIK,
            'keterangan' => fake()->optional()->sentence(),
        ];
    }
}
