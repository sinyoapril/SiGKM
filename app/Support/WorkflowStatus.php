<?php

namespace App\Support;

final class WorkflowStatus
{
    public const DRAFT = 'draft';
    public const DIAJUKAN = 'diajukan';
    public const DIVERIFIKASI = 'diverifikasi';
    public const DITOLAK = 'ditolak';
    public const DITUTUP = 'ditutup';
    public const TERBUKA = 'terbuka';

    public const BELUM_DIKERJAKAN = 'belum_dikerjakan';
    public const PROSES = 'proses';
    public const SELESAI = 'selesai';

    public const AKTIF = 'aktif';
    public const TERJADWAL = 'terjadwal';

    public static function verifikasi(): array
    {
        return [
            self::DRAFT,
            self::DIAJUKAN,
            self::DIVERIFIKASI,
            self::DITOLAK,
        ];
    }
}
