<?php

namespace App\Helpers;

use App\Models\Temuan;

final class CodeGenerator
{
    public static function kodeTemuan(?int $tahun = null): string
    {
        $tahun ??= now()->year;

        $nomorTerakhir = Temuan::query()
            ->where('kode_temuan', 'like', "TEM-{$tahun}-%")
            ->orderByDesc('kode_temuan')
            ->value('kode_temuan');

        $urutan = $nomorTerakhir
            ? ((int) substr($nomorTerakhir, -4)) + 1
            : 1;

        return sprintf('TEM-%d-%04d', $tahun, $urutan);
    }
}
