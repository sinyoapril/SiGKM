<?php

namespace App\Helpers;

use DomainException;

final class WorkflowHelper
{
    private const VERIFIKASI_TRANSITIONS = [
        'draft' => ['diajukan'],
        'diajukan' => ['diverifikasi', 'ditolak'],
        'ditolak' => ['draft', 'diajukan'],
        'diverifikasi' => [],
    ];

    private const TEMUAN_TRANSITIONS = [
        'draft' => ['diajukan'],
        'diajukan' => ['diverifikasi'],
        'diverifikasi' => ['ditutup'],
        'ditutup' => [],
    ];

    private const RTL_TRANSITIONS = [
        'belum_dikerjakan' => ['proses'],
        'proses' => ['diajukan'],
        'diajukan' => ['diverifikasi', 'ditolak'],
        'ditolak' => ['proses', 'diajukan'],
        'diverifikasi' => ['selesai'],
        'selesai' => [],
    ];

    public static function canTransition(string $workflow, string $from, string $to): bool
    {
        return in_array($to, self::transitions($workflow)[$from] ?? [], true);
    }

    public static function assertTransition(string $workflow, string $from, string $to): void
    {
        if (! self::canTransition($workflow, $from, $to)) {
            throw new DomainException("Perubahan status {$workflow} dari {$from} ke {$to} tidak diizinkan.");
        }
    }

    public static function transitions(string $workflow): array
    {
        return match ($workflow) {
            'verifikasi' => self::VERIFIKASI_TRANSITIONS,
            'temuan' => self::TEMUAN_TRANSITIONS,
            'rtl' => self::RTL_TRANSITIONS,
            default => [],
        };
    }
}
