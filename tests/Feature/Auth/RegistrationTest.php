<?php

use App\Models\Dosen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Support\RoleSlug;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Storage::fake('public');

    $response = $this->post('/register', [
        'nama_dosen' => 'Test Dosen',
        'nip' => '123456789',
        'nidn' => '987654321',
        'file_penelitian' => UploadedFile::fake()->create('penelitian.pdf', 100, 'application/pdf'),
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('roles', [
        'slug' => RoleSlug::DOSEN,
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);

    $dosen = Dosen::query()->where('nama_dosen', 'Test Dosen')->first();

    $this->assertNotNull($dosen);
    $this->assertSame('123456789', $dosen->nip);
    $this->assertSame('987654321', $dosen->nidn);
    Storage::disk('public')->assertExists($dosen->file_penelitian);

    $this->assertTrue(auth()->user()->hasRole(RoleSlug::DOSEN));
    $this->assertSame($dosen->id, auth()->user()->dosen_id);
});
