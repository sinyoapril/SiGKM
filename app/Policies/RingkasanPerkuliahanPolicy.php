<?php

namespace App\Policies;

use App\Models\RingkasanPerkuliahan;
use App\Models\User;

class RingkasanPerkuliahanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('ketua_gkm')
            || $user->hasRole('anggota_gkm')
            || $user->hasRole('dosen');
    }

    public function view(User $user, RingkasanPerkuliahan $ringkasan): bool
    {
        if ($user->hasRole('ketua_gkm') || $user->hasRole('anggota_gkm')) {
            return true;
        }

        if ($user->hasRole('dosen')) {
            return $ringkasan->mengajar?->dosen?->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('anggota_gkm');
    }

    public function update(User $user, RingkasanPerkuliahan $ringkasan): bool
    {
        return $user->hasRole('anggota_gkm')
            && in_array($ringkasan->status, ['draft', 'ditolak'], true);
    }

    public function delete(User $user, RingkasanPerkuliahan $ringkasan): bool
    {
        return $user->hasRole('anggota_gkm')
            && $ringkasan->status === 'draft';
    }

    public function submit(User $user, RingkasanPerkuliahan $ringkasan): bool
    {
        return $user->hasRole('anggota_gkm')
            && in_array($ringkasan->status, ['draft', 'ditolak'], true);
    }
}