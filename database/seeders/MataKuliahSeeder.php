<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliahList = [
            [
                'kode_mk' => 'IF101',
                'nama_mk' => 'Algoritma dan Pemrograman',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'Wajib',
                'deskripsi' => 'Dasar pemrograman dan algoritma.',
                'silabus' => null,
            ],
            [
                'kode_mk' => 'IF102',
                'nama_mk' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2,
                'jenis' => 'Wajib',
                'deskripsi' => 'Struktur data dan implementasinya.',
                'silabus' => null,
            ],
            [
                'kode_mk' => 'IF103',
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'semester' => 2,
                'jenis' => 'Wajib',
                'deskripsi' => 'Konsep basis data dan SQL.',
                'silabus' => null,
            ],
            [
                'kode_mk' => 'IF104',
                'nama_mk' => 'Pemrograman Web',
                'sks' => 3,
                'semester' => 3,
                'jenis' => 'Wajib',
                'deskripsi' => 'Pengembangan web dasar.',
                'silabus' => null,
            ],
            [
                'kode_mk' => 'IF105',
                'nama_mk' => 'Sistem Operasi',
                'sks' => 3,
                'semester' => 3,
                'jenis' => 'Wajib',
                'deskripsi' => 'Konsep sistem operasi.',
                'silabus' => null,
            ],
        ];

        foreach ($mataKuliahList as $mk) {
            MataKuliah::updateOrCreate(
                ['kode_mk' => $mk['kode_mk']],
                $mk
            );
        }
    }
}
