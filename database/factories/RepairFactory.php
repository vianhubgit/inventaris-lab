<?php

namespace Database\Factories;

use App\Enums\RepairStatus;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Repair>
 */
class RepairFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'report_id' => null,
            'user_id' => User::factory()->admin(),
            'tanggal' => fake()->dateTimeBetween('-2 months', 'now'),
            'deskripsi' => fake()->sentence(),
            'biaya' => fake()->optional()->numberBetween(10000, 500000),
            'status' => RepairStatus::PROSES,
        ];
    }
}
