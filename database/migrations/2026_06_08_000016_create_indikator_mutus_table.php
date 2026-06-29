<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_mutus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standar_mutu_id')->constrained('standar_mutus')->cascadeOnDelete();
            $table->string('kode_indikator', 50)->nullable();
            $table->text('isi_indikator');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['standar_mutu_id', 'kode_indikator']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_mutus');
    }
};
