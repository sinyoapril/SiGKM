<?php

use App\Models\EvaluasiIndikator;
use App\Models\IndikatorKinerjaKegiatan;
use App\Models\IndikatorKinerjaKegiatanSatuan;
use App\Models\IndikatorKinerjaUtama;
use App\Models\SasaranStrategis;
use App\Models\Semester;
use App\Models\TahunAkademik;

it('connects an IKKS evaluation to the shared evaluation workflow', function () {
    $year = TahunAkademik::create([
        'nama' => '2026/2027',
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2027-07-31',
    ]);
    $semester = Semester::create([
        'tahun_akademik_id' => $year->id,
        'nama' => 'ganjil',
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2027-01-31',
    ]);
    $sasaran = SasaranStrategis::create(['kode_sasaran' => 'SS-01', 'uraian_sasaran' => 'Sasaran']);
    $iku = IndikatorKinerjaUtama::create([
        'sasaran_strategis_id' => $sasaran->id,
        'kode_iku' => 'IKU-01',
        'uraian_iku' => 'Indikator utama',
    ]);
    $ikk = IndikatorKinerjaKegiatan::create([
        'indikator_kinerja_utama_id' => $iku->id,
        'kode_ikk' => 'IKK-01',
        'uraian_ikk' => 'Indikator kegiatan',
    ]);
    $ikks = IndikatorKinerjaKegiatanSatuan::create([
        'indikator_kinerja_kegiatan_id' => $ikk->id,
        'kode_ikks' => 'IKKS-01',
        'uraian_ikks' => 'Indikator satuan',
    ]);

    $evaluation = EvaluasiIndikator::create([
        'semester_id' => $semester->id,
        'evaluatable_type' => 'ikks',
        'evaluatable_id' => $ikks->id,
        'status_capaian' => 'belum_tercapai',
    ]);

    expect($evaluation->evaluatable)->toBeInstanceOf(IndikatorKinerjaKegiatanSatuan::class)
        ->and($evaluation->sumber_kode)->toBe('IKKS-01')
        ->and($evaluation->sumber_jenis)->toBe('Program Studi')
        ->and($ikk->indikatorKinerjaKegiatanSatuan->is($ikks))->toBeTrue();
});
