<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengajuan penambahan barang oleh sekretaris.
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->boolean('is_new_item')->default(false);
            $table->string('nama_barang_baru')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->text('alasan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'sudah_dibeli'])
                ->default('menunggu')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
