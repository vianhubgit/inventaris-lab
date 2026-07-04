<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Laporan barang RUSAK & HILANG disatukan (kolom type).
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['rusak', 'hilang'])->index();
            $table->foreignId('lab_id')->constrained('labs')->restrictOnDelete();
            $table->foreignId('lab_group_id')->nullable()->constrained('lab_groups')->nullOnDelete();
            $table->foreignId('lab_table_id')->nullable()->constrained('lab_tables')->nullOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->unsignedInteger('jumlah')->default(1);
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai'])->default('dilaporkan')->index();
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['lab_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
