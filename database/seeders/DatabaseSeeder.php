<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\LabLayoutSeeder;
use Database\Seeders\RoleUserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleUserSeeder::class,
            CategorySeeder::class,
            LabLayoutSeeder::class,
        ]);

        if (! app()->environment('production') || env('SEED_DEMO', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}
