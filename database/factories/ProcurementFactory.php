<?php

namespace Database\Factories;

use App\Enums\ProcurementStatus;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Procurement>
 */
class ProcurementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->sekretaris(),
            'category_id' => null,
            'item_id' => Item::factory(),
            'is_new_item' => false,
            'nama_barang_baru' => null,
            'jumlah' => fake()->numberBetween(1, 10),
            'alasan' => fake()->sentence(),
            'status' => ProcurementStatus::MENUNGGU,
            'catatan_admin' => null,
            'requested_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function newItem(): static
    {
        return $this->state(fn () => [
            'is_new_item' => true,
            'item_id' => null,
            'nama_barang_baru' => fake()->words(2, true),
        ]);
    }
}
