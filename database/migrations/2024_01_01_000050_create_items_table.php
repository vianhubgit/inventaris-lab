<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('lab_id')->constrained('labs')->restrictOnDelete();
            // Lokasi detail opsional (meja) untuk Lab A/B; null untuk TEFA / inventaris umum.
            $table->foreignId('lab_table_id')->nullable()->constrained('lab_tables')->nullOnDelete();
            $table->unsignedInteger('jumlah_total')->default(0);
            $table->enum('status', ['baik', 'rusak', 'hilang', 'perbaikan'])->default('baik')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['lab_id', 'category_id']);
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
