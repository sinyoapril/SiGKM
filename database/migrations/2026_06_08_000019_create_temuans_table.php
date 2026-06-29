<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_temuan', 50)->unique();
            $table->foreignId('evaluasi_indikator_id')->nullable()->constrained('evaluasi_indikators')->nullOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->restrictOnDelete();
            $table->text('pernyataan');
            $table->text('rencana_awal')->nullable();
            $table->date('target_selesai')->nullable();
            $table->enum('status', ['draft', 'terbuka', 'ditutup'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('dosen_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temuans');
    }
};
