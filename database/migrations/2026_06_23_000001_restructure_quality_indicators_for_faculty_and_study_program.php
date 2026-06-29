<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sasaran_strategis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sasaran', 50)->nullable()->unique();
            $table->text('uraian_sasaran');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('indikator_kinerja_utamas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sasaran_strategis_id')->constrained('sasaran_strategis')->restrictOnDelete();
            $table->string('kode_iku', 50)->nullable();
            $table->text('uraian_iku');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['sasaran_strategis_id', 'kode_iku'], 'iku_sasaran_kode_unique');
        });

        Schema::create('indikator_kinerja_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_kinerja_utama_id')->constrained('indikator_kinerja_utamas')->restrictOnDelete();
            $table->string('kode_ikk', 50)->nullable();
            $table->text('uraian_ikk');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['indikator_kinerja_utama_id', 'kode_ikk'], 'ikk_iku_kode_unique');
        });

        Schema::create('indikator_kinerja_kegiatan_satuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indikator_kinerja_kegiatan_id');
            $table->string('kode_ikks', 50)->nullable();
            $table->text('uraian_ikks');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('indikator_kinerja_kegiatan_id', 'ikks_ikk_foreign')
                ->references('id')->on('indikator_kinerja_kegiatans')->restrictOnDelete();
            $table->unique('indikator_kinerja_kegiatan_id', 'ikks_ikk_unique');
        });

        Schema::table('evaluasi_indikators', function (Blueprint $table) {
            $table->string('evaluatable_type')->nullable()->after('indikator_mutu_id');
            $table->unsignedBigInteger('evaluatable_id')->nullable()->after('evaluatable_type');
        });

        DB::table('evaluasi_indikators')->whereNotNull('indikator_mutu_id')->update([
            'evaluatable_type' => 'indikator_mutu',
            'evaluatable_id' => DB::raw('indikator_mutu_id'),
        ]);

        Schema::table('evaluasi_indikators', function (Blueprint $table) {
            $table->index('semester_id', 'evaluasi_semester_index');
            $table->dropIndex('evaluasi_indikators_lookup_index');
            $table->dropConstrainedForeignId('indikator_mutu_id');
            $table->unique(['semester_id', 'evaluatable_type', 'evaluatable_id'], 'evaluasi_target_semester_unique');
            $table->index(['evaluatable_type', 'evaluatable_id'], 'evaluasi_target_index');
        });

        Schema::table('standar_mutus', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }

    public function down(): void
    {
        Schema::table('standar_mutus', function (Blueprint $table) {
            $table->enum('level', ['fakultas', 'program_studi'])->default('fakultas')->after('nama_standar');
        });

        Schema::table('evaluasi_indikators', function (Blueprint $table) {
            $table->dropUnique('evaluasi_target_semester_unique');
            $table->dropIndex('evaluasi_target_index');
            $table->foreignId('indikator_mutu_id')->nullable()->after('semester_id')->constrained('indikator_mutus')->restrictOnDelete();
        });

        DB::table('evaluasi_indikators')
            ->where('evaluatable_type', 'indikator_mutu')
            ->update(['indikator_mutu_id' => DB::raw('evaluatable_id')]);

        Schema::table('evaluasi_indikators', function (Blueprint $table) {
            $table->dropColumn(['evaluatable_type', 'evaluatable_id']);
            $table->index(['semester_id', 'indikator_mutu_id'], 'evaluasi_indikators_lookup_index');
            $table->dropIndex('evaluasi_semester_index');
        });

        Schema::dropIfExists('indikator_kinerja_kegiatan_satuans');
        Schema::dropIfExists('indikator_kinerja_kegiatans');
        Schema::dropIfExists('indikator_kinerja_utamas');
        Schema::dropIfExists('sasaran_strategis');
    }
};
