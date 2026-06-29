<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkuliahan_id')->constrained('perkuliahans')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['perkuliahan_id', 'dosen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajars');
    }
};
