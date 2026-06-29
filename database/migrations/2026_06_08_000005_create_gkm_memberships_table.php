<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gkm_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->restrictOnDelete();
            $table->enum('peran', ['ketua', 'anggota']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['peran', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gkm_memberships');
    }
};
