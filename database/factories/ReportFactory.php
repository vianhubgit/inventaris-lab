<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Item;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        $lab = Lab::factory();

        return [
            'user_id' => User::factory()->sekretaris(),
            'type' => fake()->randomElement(ReportType::cases()),
            'lab_id' => $lab,
            'lab_group_id' => null,
            'lab_table_id' => null,
            'item_id' => Item::factory(),
            'jumlah' => fake()->numberBetween(1, 5),
            'keterangan' => fake()->sentence(),
            'foto' => null,
            'status' => ReportStatus::DILAPORKAN,
            'reported_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function rusak(): static
    {
        return $this->state(fn () => ['type' => ReportType::RUSAK]);
    }

    public function hilang(): static
    {
        return $this->state(fn () => ['type' => ReportType::HILANG]);
    }
}
