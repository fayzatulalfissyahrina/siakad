<?php

namespace Database\Seeders;

use App\Models\Krs;
use Illuminate\Database\Seeder;

class KrsSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAkademik = '2025/2026';
        $semesterAkademik = 'Genap';

        $krsList = [
            ['nim' => 'MHS001', 'kode_mk' => 'IF101'],
            ['nim' => 'MHS001', 'kode_mk' => 'IF102'],
            ['nim' => 'MHS002', 'kode_mk' => 'IF101'],
            ['nim' => 'MHS002', 'kode_mk' => 'IF103'],
            ['nim' => 'MHS003', 'kode_mk' => 'IF102'],
            ['nim' => 'MHS003', 'kode_mk' => 'IF104'],
            ['nim' => 'MHS004', 'kode_mk' => 'IF103'],
            ['nim' => 'MHS004', 'kode_mk' => 'IF105'],
            ['nim' => 'MHS005', 'kode_mk' => 'IF101'],
            ['nim' => 'MHS005', 'kode_mk' => 'IF104'],
        ];

        foreach ($krsList as $item) {
            Krs::updateOrCreate(
                [
                    'nim' => $item['nim'],
                    'kode_mk' => $item['kode_mk'],
                    'tahun_akademik' => $tahunAkademik,
                    'semester_akademik' => $semesterAkademik,
                ],
                [
                    'status_krs' => 'disetujui',
                    'nilai_akhir' => null,
                    'nilai_angka' => null,
                    'status_lulus' => 'belum',
                ]
            );
        }
    }
}
