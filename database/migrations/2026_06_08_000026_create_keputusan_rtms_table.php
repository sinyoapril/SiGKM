<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keputusan_rtms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulen_rtm_id')->constrained('notulen_rtms')->cascadeOnDelete();
            $table->foreignId('rencana_tindak_lanjut_id')->constrained('rencana_tindak_lanjuts')->restrictOnDelete();
            $table->text('uraian_keputusan');
            $table->text('strategi')->nullable();
            $table->date('target_selesai')->nullable();
            $table->enum('status', ['belum_dikerjakan', 'proses', 'selesai'])->default('belum_dikerjakan');
            $table->timestamps();

            $table->index('status');
            $table->unique(['notulen_rtm_id', 'rencana_tindak_lanjut_id'], 'keputusan_rtm_notulen_rtl_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keputusan_rtms');
    }
};
