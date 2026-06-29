<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->restrictOnDelete();
            $table->foreignId('indikator_mutu_id')->constrained('indikator_mutus')->restrictOnDelete();
            $table->enum('status_capaian', ['tercapai', 'hampir_tercapai', 'belum_tercapai']);
            $table->string('bukti_capaian')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(
                ['semester_id', 'indikator_mutu_id'],
                'evaluasi_indikators_lookup_index'
            );
            $table->index('status_capaian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_indikators');
    }
};
