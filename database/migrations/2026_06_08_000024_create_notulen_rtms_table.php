<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notulen_rtms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_rtm_id')->unique()->constrained('jadwal_rtms')->cascadeOnDelete();
            $table->longText('isi_notulen');
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft');
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notulen_rtms');
    }
};
