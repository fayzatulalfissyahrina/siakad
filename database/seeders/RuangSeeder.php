<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    public function run(): void
    {
        $ruangList = [
            [
                'id_ruang' => 'R101',
                'nama_ruang' => 'Lab Komputer 1',
                'gedung' => 'Gedung A',
                'lantai' => '1',
                'kapasitas' => 40,
                'fasilitas' => 'PC, Proyektor, AC',
                'status' => 'aktif',
            ],
            [
                'id_ruang' => 'R102',
                'nama_ruang' => 'Lab Komputer 2',
                'gedung' => 'Gedung A',
                'lantai' => '1',
                'kapasitas' => 40,
                'fasilitas' => 'PC, Proyektor, AC',
                'status' => 'aktif',
            ],
            [
                'id_ruang' => 'R201',
                'nama_ruang' => 'Kelas Teori 1',
                'gedung' => 'Gedung B',
                'lantai' => '2',
                'kapasitas' => 45,
                'fasilitas' => 'Proyektor, Whiteboard',
                'status' => 'aktif',
            ],
        ];

        foreach ($ruangList as $ruang) {
            Ruang::updateOrCreate(
                ['id_ruang' => $ruang['id_ruang']],
                $ruang
            );
        }
    }
}
