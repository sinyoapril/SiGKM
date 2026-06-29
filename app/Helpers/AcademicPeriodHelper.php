<?php

namespace App\Helpers;

use App\Models\Semester;
use App\Models\TahunAkademik;

final class AcademicPeriodHelper
{
    public static function tahunAkademikAktif(): ?TahunAkademik
    {
        return TahunAkademik::query()->active()->first();
    }

    public static function semesterAktif(): ?Semester
    {
        return Semester::query()
            ->with('tahunAkademik')
            ->active()
            ->first();
    }
}
