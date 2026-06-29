<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temuan_id')->constrained('temuans')->restrictOnDelete();
            $table->text('uraian_rencana_tindak_lanjut')->nullable();
            $table->text('uraian_tindak_koreksi')->nullable();
            $table->date('target_selesai')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->unique('temuan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_tindak_lanjuts');
    }
};
