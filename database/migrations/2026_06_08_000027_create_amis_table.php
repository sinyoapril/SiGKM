<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademiks')->restrictOnDelete();
            $table->text('temuan');
            $table->text('rekomendasi');
            $table->text('tindak_lanjut')->nullable();
            $table->date('target_selesai')->nullable();
            $table->date('tanggal_pelaksanaan');
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();

            $table->index(['tanggal_pelaksanaan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amis');
    }
};
