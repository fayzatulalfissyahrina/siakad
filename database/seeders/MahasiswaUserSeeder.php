<?php

namespace Database\Seeders;

use App\Models\Golongan;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaUserSeeder extends Seeder
{
    public function run(): void
    {
        $golA = Golongan::updateOrCreate(
            ['nama_gol' => 'TI-1A', 'program_studi' => 'Teknik Informatika', 'angkatan' => '2024'],
            [
                'dosen_wali' => 'DSN001',
                'kapasitas' => 40,
            ]
        );

        $golB = Golongan::updateOrCreate(
            ['nama_gol' => 'TI-1B', 'program_studi' => 'Teknik Informatika', 'angkatan' => '2024'],
            [
                'dosen_wali' => 'DSN002',
                'kapasitas' => 40,
            ]
        );

        $mahasiswaList = [
            [
                'nim' => 'MHS001',
                'nama' => 'Raka Pratama',
                'alamat' => 'Jl. Mahasiswa No. 1',
                'no_hp' => '081298765432',
                'email' => 'raka.pratama@example.com',
                'semester' => 2,
                'id_gol' => $golA->id_gol,
                'tahun_masuk' => '2024',
                'password' => 'mhs123',
            ],
            [
                'nim' => 'MHS002',
                'nama' => 'Alya Putri',
                'alamat' => 'Jl. Mahasiswa No. 2',
                'no_hp' => '081298765433',
                'email' => 'alya.putri@example.com',
                'semester' => 2,
                'id_gol' => $golA->id_gol,
                'tahun_masuk' => '2024',
                'password' => 'mhs123',
            ],
            [
                'nim' => 'MHS003',
                'nama' => 'Nadia Salsabila',
                'alamat' => 'Jl. Mahasiswa No. 3',
                'no_hp' => '081298765434',
                'email' => 'nadia.salsabila@example.com',
                'semester' => 2,
                'id_gol' => $golB->id_gol,
                'tahun_masuk' => '2024',
                'password' => 'mhs123',
            ],
            [
                'nim' => 'MHS004',
                'nama' => 'Fajar Hidayat',
                'alamat' => 'Jl. Mahasiswa No. 4',
                'no_hp' => '081298765435',
                'email' => 'fajar.hidayat@example.com',
                'semester' => 2,
                'id_gol' => $golB->id_gol,
                'tahun_masuk' => '2024',
                'password' => 'mhs123',
            ],
            [
                'nim' => 'MHS005',
                'nama' => 'Dimas Saputra',
                'alamat' => 'Jl. Mahasiswa No. 5',
                'no_hp' => '081298765436',
                'email' => 'dimas.saputra@example.com',
                'semester' => 2,
                'id_gol' => $golA->id_gol,
                'tahun_masuk' => '2024',
                'password' => 'mhs123',
            ],
        ];

        foreach ($mahasiswaList as $mhs) {
            Mahasiswa::updateOrCreate(
                ['nim' => $mhs['nim']],
                [
                    'nama' => $mhs['nama'],
                    'alamat' => $mhs['alamat'],
                    'no_hp' => $mhs['no_hp'],
                    'email' => $mhs['email'],
                    'semester' => $mhs['semester'],
                    'id_gol' => $mhs['id_gol'],
                    'foto' => null,
                    'status' => 'aktif',
                    'tahun_masuk' => $mhs['tahun_masuk'],
                ]
            );

            User::updateOrCreate(
                ['username' => $mhs['nim']],
                [
                    'name' => $mhs['nama'],
                    'email' => $mhs['email'],
                    'role' => 'mahasiswa',
                    'nim' => $mhs['nim'],
                    'nip' => null,
                    'password' => Hash::make($mhs['password']),
                ]
            );
        }
    }
}
