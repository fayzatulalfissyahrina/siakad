<?php

namespace Database\Seeders;

use App\Models\Pengampu;
use Illuminate\Database\Seeder;

class PengampuSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAkademik = '2025/2026';
        $semesterAkademik = 'Genap';

        $pengampuList = [
            ['kode_mk' => 'IF101', 'nip' => 'DSN001'],
            ['kode_mk' => 'IF102', 'nip' => 'DSN001'],
            ['kode_mk' => 'IF103', 'nip' => 'DSN002'],
            ['kode_mk' => 'IF104', 'nip' => 'DSN002'],
            ['kode_mk' => 'IF105', 'nip' => 'DSN003'],
        ];

        foreach ($pengampuList as $item) {
            Pengampu::updateOrCreate(
                [
                    'kode_mk' => $item['kode_mk'],
                    'nip' => $item['nip'],
                    'tahun_akademik' => $tahunAkademik,
                    'semester_akademik' => $semesterAkademik,
                ],
                [
                    'status' => 'aktif',
                ]
            );
        }
    }
}
