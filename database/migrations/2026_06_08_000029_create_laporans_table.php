<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_laporan', ['perkuliahan', 'pencapaian_mutu', 'rtl', 'rtm', 'ami']);
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->string('judul');
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->index(['jenis_laporan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
