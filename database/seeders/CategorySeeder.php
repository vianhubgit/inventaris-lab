<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Komputer', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'Switch',
            'Router', 'Access Point', 'Kabel LAN', 'RJ45', 'Tang Crimping',
            'LAN Tester', 'Proyektor', 'UPS', 'Speaker', 'MikroTik', 'Rak Server',
        ];

        foreach ($categories as $nama) {
            Category::updateOrCreate(['nama' => $nama]);
        }
    }
}
