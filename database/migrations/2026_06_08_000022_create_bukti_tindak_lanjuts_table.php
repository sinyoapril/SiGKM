<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_tindak_lanjut_id')->constrained('rencana_tindak_lanjuts')->cascadeOnDelete();
            $table->string('file_path');
            $table->text('keterangan')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_tindak_lanjuts');
    }
};
