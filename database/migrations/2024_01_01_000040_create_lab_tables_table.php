<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_group_id')->constrained('lab_groups')->cascadeOnDelete();
            $table->unsignedSmallInteger('nomor');
            $table->string('nama')->nullable();
            $table->timestamps();

            $table->unique(['lab_group_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tables');
    }
};
