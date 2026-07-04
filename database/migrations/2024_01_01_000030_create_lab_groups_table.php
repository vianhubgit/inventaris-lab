<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
            $table->unsignedSmallInteger('nomor');
            $table->string('nama')->nullable();
            $table->timestamps();

            $table->unique(['lab_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_groups');
    }
};
