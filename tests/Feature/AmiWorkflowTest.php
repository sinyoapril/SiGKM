<?php

use App\Models\Ami;
use App\Models\Role;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function amiUser(string $role): User
{
    $roleModel = Role::create(['name' => $role, 'slug' => $role]);

    return User::factory()->create(['role_id' => $roleModel->id]);
}

it('stores AMI by academic year', function () {
    $member = amiUser('anggota-gkm');
    $year = TahunAkademik::create(['nama' => '2026/2027']);

    $this->actingAs($member)->post(route('ami.store'), [
        'tahun_akademik_id' => $year->id,
        'tanggal_pelaksanaan' => '2027-06-01',
        'temuan' => 'Temuan audit.',
        'rekomendasi' => 'Rekomendasi audit.',
        'status' => 'draft',
    ])->assertRedirect(route('ami.index'));

    $this->assertDatabaseHas('amis', [
        'tahun_akademik_id' => $year->id,
        'temuan' => 'Temuan audit.',
    ]);
});

it('accepts either a Google Drive link or an uploaded file as AMI evidence', function () {
    Storage::fake('public');
    $member = amiUser('anggota-gkm');
    $year = TahunAkademik::create(['nama' => '2026/2027']);
    $ami = Ami::create([
        'tahun_akademik_id' => $year->id,
        'tanggal_pelaksanaan' => '2027-06-01',
        'temuan' => 'Temuan audit.',
        'rekomendasi' => 'Rekomendasi audit.',
        'status' => 'draft',
    ]);

    $this->actingAs($member)->post(route('ami.dokumen.store', $ami), [
        'nama_dokumen' => 'Folder Google Drive',
        'link_url' => 'https://drive.google.com/drive/folders/example',
    ])->assertRedirect();

    $this->actingAs($member)->post(route('ami.dokumen.store', $ami), [
        'nama_dokumen' => 'Berita Acara',
        'document_file' => UploadedFile::fake()->create('berita-acara.pdf', 100, 'application/pdf'),
    ])->assertRedirect();

    $this->assertDatabaseHas('dokumen_amis', [
        'ami_id' => $ami->id,
        'link_url' => 'https://drive.google.com/drive/folders/example',
        'file_path' => null,
    ]);

    expect($ami->dokumenAmis()->whereNotNull('file_path')->exists())->toBeTrue();
});

it('allows the coordinator to view AMI but not modify it', function () {
    $coordinator = amiUser('koordinator-prodi');

    $this->actingAs($coordinator)->get(route('ami.index'))->assertOk();
    $this->actingAs($coordinator)->get(route('ami.create'))->assertForbidden();
});
