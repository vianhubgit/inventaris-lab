<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (kontrol penuh)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Lab',
                'password' => Hash::make('admin123'),
                'role' => UserRole::ADMIN,
                'is_active' => true,
            ]
        );

        // Sekretaris (satu akun)
        User::updateOrCreate(
            ['username' => 'sekretaris'],
            [
                'name' => 'Sekretaris Lab',
                'password' => Hash::make('sekretaris123'),
                'role' => UserRole::SEKRETARIS,
                'is_active' => true,
            ]
        );
    }
}
