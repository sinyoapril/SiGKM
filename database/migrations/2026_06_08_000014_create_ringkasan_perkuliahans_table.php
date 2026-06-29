<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ringkasan_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_monev_id')->constrained('jadwal_monevs')->restrictOnDelete();
            $table->foreignId('perkuliahan_id')->constrained('perkuliahans')->restrictOnDelete();
            $table->unsignedTinyInteger('jumlah_pertemuan')->default(0);
            $table->enum('kesesuaian_materi', ['sesuai', 'sebagian', 'tidak_sesuai'])->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft');
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->unique(['jadwal_monev_id', 'perkuliahan_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ringkasan_perkuliahans');
    }
};
