<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Audit inventaris: membandingkan jumlah tercatat vs fisik.
        Schema::create('stock_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('jumlah_tercatat');
            $table->unsignedInteger('jumlah_fisik');
            $table->integer('selisih');
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->index(['item_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_audits');
    }
};
