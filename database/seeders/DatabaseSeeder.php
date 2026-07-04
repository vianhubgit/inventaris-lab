<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Data inti yang selalu diperlukan (aman dijalankan di production).
        // Semua idempotent (updateOrCreate) sehingga aman diulang.
        $this->call([
            RoleUserSeeder::class,   // akun admin & sekretaris
            CategorySeeder::class,   // kategori barang
            LabLayoutSeeder::class,  // struktur Lab A, Lab B, TEFA + item standar
        ]);

        // Data demo (laporan & pengajuan dummy) HANYA untuk pengembangan/pengujian.
        // Tidak ikut ter-seed di production agar database awal tetap bersih.
        // Paksa dengan: SEED_DEMO=true php artisan db:seed
        if (! app()->environment('production') || env('SEED_DEMO', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}
