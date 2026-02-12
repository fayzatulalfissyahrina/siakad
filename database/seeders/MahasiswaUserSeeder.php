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
        $gol = Golongan::updateOrCreate(
            ['nama_gol' => 'TI-1A', 'program_studi' => 'Teknik Informatika', 'angkatan' => '2024'],
            [
                'dosen_wali' => 'DSN001',
                'kapasitas' => 40,
            ]
        );

        Mahasiswa::updateOrCreate(
            ['nim' => 'MHS001'],
            [
                'nama' => 'Mahasiswa Satu',
                'alamat' => 'Jl. Mahasiswa No. 1',
                'no_hp' => '081298765432',
                'email' => 'mhs1@example.com',
                'semester' => 2,
                'id_gol' => $gol->id_gol,
                'foto' => null,
                'status' => 'aktif',
                'tahun_masuk' => '2024',
            ]
        );

        User::updateOrCreate(
            ['username' => 'MHS001'],
            [
                'name' => 'Mahasiswa Satu',
                'email' => 'mhs1@example.com',
                'role' => 'mahasiswa',
                'nim' => 'MHS001',
                'nip' => null,
                'password' => Hash::make('mhs123'),
            ]
        );
    }
}
