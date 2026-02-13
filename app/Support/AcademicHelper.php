<?php

namespace App\Support;

use Illuminate\Support\Str;

class AcademicHelper
{
    public static function semesterKelas(?string $angkatan, ?string $tahunAkademik, ?string $semesterAkademik): ?int
    {
        if (empty($angkatan) || empty($tahunAkademik) || empty($semesterAkademik)) {
            return null;
        }

        $parts = explode('/', $tahunAkademik);
        $startYear = (int) trim($parts[0] ?? '');
        $angkatanYear = (int) preg_replace('/\D/', '', $angkatan);

        if ($startYear <= 0 || $angkatanYear <= 0) {
            return null;
        }

        $sem = Str::lower($semesterAkademik);
        $isGenap = str_contains($sem, 'genap');

        $semester = ($startYear - $angkatanYear) * 2 + ($isGenap ? 2 : 1);

        return $semester > 0 ? $semester : null;
    }
}
