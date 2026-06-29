<?php

namespace App\Models;

use App\Support\RoleSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nidn',
        'nama_dosen',
        'file_penelitian',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function akunDosen(): HasOne
    {
        return $this->hasOne(User::class)
            ->whereHas('role', fn ($query) => $query->where('slug', RoleSlug::DOSEN));
    }

    public function akunAnggotaGkm(): HasOne
    {
        return $this->hasOne(User::class)
            ->whereHas('role', fn ($query) => $query->where('slug', RoleSlug::ANGGOTA_GKM));
    }

    public function akunKetuaGkm(): HasOne
    {
        return $this->hasOne(User::class)
            ->whereHas('role', fn ($query) => $query->where('slug', RoleSlug::KETUA_GKM));
    }

    public function akunKoordinatorProdi(): HasOne
    {
        return $this->hasOne(User::class)
            ->whereHas('role', fn ($query) => $query->where('slug', RoleSlug::KOORDINATOR_PRODI));
    }

    public function gkmMemberships(): HasMany
    {
        return $this->hasMany(GkmMembership::class);
    }

    public function activeGkmMembership(): HasOne
    {
        return $this->hasOne(GkmMembership::class)
            ->where('is_active', true)
            ->latestOfMany();
    }

    public function pengajars(): HasMany
    {
        return $this->hasMany(Pengajar::class);
    }

    public function temuansPenanggungJawab(): HasMany
    {
        return $this->hasMany(Temuan::class);
    }

    public function perkuliahans(): BelongsToMany
    {
        return $this->belongsToMany(Perkuliahan::class, 'pengajars')
            ->withPivot('is_koordinator')
            ->withTimestamps();
    }

    public function akunUntukRole(string $roleSlug): ?User
    {
        return $this->users()
            ->whereHas('role', fn ($query) => $query->where('slug', $roleSlug))
            ->first();
    }
}
