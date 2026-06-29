<?php

use App\Models\Dosen;
use App\Models\EvaluasiIndikator;
use App\Models\IndikatorMutu;
use App\Models\JadwalRtm;
use App\Models\NotulenRtm;
use App\Models\RencanaTindakLanjut;
use App\Models\Role;
use App\Models\Semester;
use App\Models\StandarMutu;
use App\Models\TahunAkademik;
use App\Models\Temuan;
use App\Models\User;

function rtmUser(string $role): User
{
    $roleModel = Role::create(['name' => $role, 'slug' => $role]);

    return User::factory()->create(['role_id' => $roleModel->id]);
}

function rtmSemester(string $name, string $start, string $end): Semester
{
    $year = TahunAkademik::firstOrCreate(
        ['nama' => substr($start, 0, 4).'/'.((int) substr($start, 0, 4) + 1)],
        ['tanggal_mulai' => $start, 'tanggal_selesai' => $end]
    );

    return Semester::create([
        'tahun_akademik_id' => $year->id,
        'nama' => $name,
        'tanggal_mulai' => $start,
        'tanggal_selesai' => $end,
    ]);
}

function verifiedRtlFor(Semester $semester, string $code): RencanaTindakLanjut
{
    $dosen = Dosen::create(['nama_dosen' => 'Dosen '.$code]);
    $standard = StandarMutu::create(['nama_standar' => 'Standar '.$code]);
    $indicator = IndikatorMutu::create([
        'standar_mutu_id' => $standard->id,
        'kode_indikator' => $code,
        'isi_indikator' => 'Indikator '.$code,
    ]);
    $evaluation = EvaluasiIndikator::create([
        'semester_id' => $semester->id,
        'evaluatable_type' => 'indikator_mutu',
        'evaluatable_id' => $indicator->id,
        'status_capaian' => 'belum_tercapai',
    ]);
    $finding = Temuan::create([
        'kode_temuan' => $code,
        'evaluasi_indikator_id' => $evaluation->id,
        'dosen_id' => $dosen->id,
        'pernyataan' => 'Temuan '.$code,
        'status' => 'ditutup',
    ]);

    return RencanaTindakLanjut::create([
        'temuan_id' => $finding->id,
        'uraian_rencana_tindak_lanjut' => 'RTL '.$code,
        'status' => 'diverifikasi',
    ]);
}

it('allows an Anggota GKM to submit a notulen and Ketua GKM to verify it', function () {
    $member = rtmUser('anggota-gkm');
    $chair = rtmUser('ketua-gkm');
    $semester = rtmSemester('ganjil', '2026-08-01', '2027-01-31');
    $schedule = JadwalRtm::create([
        'semester_id' => $semester->id,
        'judul' => 'RTM Ganjil',
        'tanggal' => '2027-02-10',
        'status' => 'terjadwal',
    ]);

    $this->actingAs($member)->post(route('notulen-rtm.store'), [
        'jadwal_rtm_id' => $schedule->id,
        'isi_notulen' => 'Pembahasan hasil RTL.',
    ])->assertRedirect(route('notulen-rtm.index'));

    $notulen = NotulenRtm::firstOrFail();
    $this->actingAs($member)->patch(route('notulen-rtm.ajukan', $notulen))->assertRedirect();
    expect($notulen->fresh()->status)->toBe('diajukan');

    $this->actingAs($member)->patch(route('notulen-rtm.verifikasi', $notulen))->assertForbidden();
    $this->actingAs($chair)->patch(route('notulen-rtm.verifikasi', $notulen))->assertRedirect();
    expect($notulen->fresh()->status)->toBe('diverifikasi')
        ->and($notulen->verified_by)->toBe($chair->id);
});

it('only accepts RTL from the immediately preceding semester for a decision', function () {
    $member = rtmUser('anggota-gkm');
    $previous = rtmSemester('ganjil', '2025-08-01', '2026-01-31');
    $current = rtmSemester('genap', '2026-02-01', '2026-07-31');
    $previousRtl = verifiedRtlFor($previous, 'TMN-PREV');
    $currentRtl = verifiedRtlFor($current, 'TMN-CURR');
    $schedule = JadwalRtm::create([
        'semester_id' => $current->id,
        'judul' => 'RTM Genap',
        'tanggal' => '2026-07-20',
        'status' => 'selesai',
    ]);
    $notulen = NotulenRtm::create([
        'jadwal_rtm_id' => $schedule->id,
        'isi_notulen' => 'Notulen terverifikasi.',
        'status' => 'diverifikasi',
        'input_by' => $member->id,
    ]);

    $payload = [
        'notulen_rtm_id' => $notulen->id,
        'uraian_keputusan' => 'Keputusan perbaikan.',
        'strategi' => 'Monitoring bulanan.',
        'status' => 'belum_dikerjakan',
    ];

    $this->actingAs($member)->post(route('keputusan-rtm.store'), $payload + [
        'rencana_tindak_lanjut_id' => $currentRtl->id,
    ])->assertSessionHasErrors('rencana_tindak_lanjut_id');

    $this->actingAs($member)->post(route('keputusan-rtm.store'), $payload + [
        'rencana_tindak_lanjut_id' => $previousRtl->id,
    ])->assertRedirect(route('keputusan-rtm.index'));

    $this->assertDatabaseHas('keputusan_rtms', [
        'rencana_tindak_lanjut_id' => $previousRtl->id,
        'strategi' => 'Monitoring bulanan.',
    ]);

    $this->actingAs($member)->post(route('keputusan-rtm.store'), $payload + [
        'rencana_tindak_lanjut_id' => $previousRtl->id,
    ])->assertSessionHasErrors('rencana_tindak_lanjut_id');
});
