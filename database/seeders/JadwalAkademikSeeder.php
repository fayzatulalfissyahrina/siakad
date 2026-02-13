<?php

namespace Database\Seeders;

use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\Ruang;
use Illuminate\Database\Seeder;

class JadwalAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAkademik = '2025/2026';
        $semesterAkademik = 'Genap';

        $golA = Golongan::updateOrCreate(
            ['nama_gol' => 'TI-1A', 'program_studi' => 'Teknik Informatika', 'angkatan' => '2024'],
            ['dosen_wali' => 'DSN001', 'kapasitas' => 40]
        );

        $golB = Golongan::updateOrCreate(
            ['nama_gol' => 'TI-1B', 'program_studi' => 'Teknik Informatika', 'angkatan' => '2024'],
            ['dosen_wali' => 'DSN002', 'kapasitas' => 40]
        );

        $ruangLab = Ruang::updateOrCreate(
            ['id_ruang' => 'R101'],
            [
                'nama_ruang' => 'Lab Komputer 1',
                'gedung' => 'Gedung A',
                'lantai' => '1',
                'kapasitas' => 40,
                'fasilitas' => 'PC, Proyektor, AC',
                'status' => 'aktif',
            ]
        );

        $ruangKelas = Ruang::updateOrCreate(
            ['id_ruang' => 'R201'],
            [
                'nama_ruang' => 'Kelas Teori 1',
                'gedung' => 'Gedung B',
                'lantai' => '2',
                'kapasitas' => 45,
                'fasilitas' => 'Proyektor, Whiteboard',
                'status' => 'aktif',
            ]
        );

        $jadwalList = [
            [
                'hari' => 'Senin',
                'kode_mk' => 'IF101',
                'id_ruang' => $ruangLab->id_ruang,
                'id_gol' => $golA->id_gol,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'hari' => 'Selasa',
                'kode_mk' => 'IF102',
                'id_ruang' => $ruangLab->id_ruang,
                'id_gol' => $golA->id_gol,
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
            ],
            [
                'hari' => 'Rabu',
                'kode_mk' => 'IF103',
                'id_ruang' => $ruangKelas->id_ruang,
                'id_gol' => $golB->id_gol,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'hari' => 'Kamis',
                'kode_mk' => 'IF104',
                'id_ruang' => $ruangLab->id_ruang,
                'id_gol' => $golB->id_gol,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
            ],
            [
                'hari' => 'Jumat',
                'kode_mk' => 'IF105',
                'id_ruang' => $ruangKelas->id_ruang,
                'id_gol' => $golA->id_gol,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
            ],
        ];

        foreach ($jadwalList as $jadwal) {
            JadwalAkademik::updateOrCreate(
                [
                    'hari' => $jadwal['hari'],
                    'kode_mk' => $jadwal['kode_mk'],
                    'id_ruang' => $jadwal['id_ruang'],
                    'id_gol' => $jadwal['id_gol'],
                    'jam_mulai' => $jadwal['jam_mulai'],
                    'jam_selesai' => $jadwal['jam_selesai'],
                    'tahun_akademik' => $tahunAkademik,
                    'semester_akademik' => $semesterAkademik,
                ],
                []
            );
        }
    }
}
