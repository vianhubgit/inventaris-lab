<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StockAudit>
 */
class StockAuditFactory extends Factory
{
    public function definition(): array
    {
        $tercatat = fake()->numberBetween(1, 50);
        $fisik = fake()->numberBetween(0, 50);

        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory()->admin(),
            'jumlah_tercatat' => $tercatat,
            'jumlah_fisik' => $fisik,
            'selisih' => $fisik - $tercatat,
            'keterangan' => fake()->optional()->sentence(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
