<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tidak dibuat unique karena satu dosen dapat memiliki beberapa akun
            // dengan role berbeda, misalnya akun dosen dan akun anggota GKM.
            $table->foreignId('dosen_id')
                ->nullable()
                ->after('role_id')
                ->constrained('dosens')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dosen_id');
        });
    }
};
