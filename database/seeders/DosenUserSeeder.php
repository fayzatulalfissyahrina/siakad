<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenUserSeeder extends Seeder
{
    public function run(): void
    {
        $dosenList = [
            [
                'nip' => 'DSN001',
                'nama' => 'Dosen Satu',
                'alamat' => 'Jl. Kampus No. 1',
                'no_hp' => '081234567890',
                'email' => 'dosen1@example.com',
                'password' => 'dosen123',
            ],
            [
                'nip' => 'DSN002',
                'nama' => 'Dosen Dua',
                'alamat' => 'Jl. Kampus No. 2',
                'no_hp' => '081234567891',
                'email' => 'dosen2@example.com',
                'password' => 'dosen123',
            ],
            [
                'nip' => 'DSN003',
                'nama' => 'Dosen Tiga',
                'alamat' => 'Jl. Kampus No. 3',
                'no_hp' => '081234567892',
                'email' => 'dosen3@example.com',
                'password' => 'dosen123',
            ],
        ];

        foreach ($dosenList as $dosen) {
            Dosen::updateOrCreate(
                ['nip' => $dosen['nip']],
                [
                    'nama' => $dosen['nama'],
                    'alamat' => $dosen['alamat'],
                    'no_hp' => $dosen['no_hp'],
                    'email' => $dosen['email'],
                    'foto' => null,
                    'status' => 'aktif',
                ]
            );

            User::updateOrCreate(
                ['username' => $dosen['nip']],
                [
                    'name' => $dosen['nama'],
                    'email' => $dosen['email'],
                    'role' => 'dosen',
                    'nim' => null,
                    'nip' => $dosen['nip'],
                    'password' => Hash::make($dosen['password']),
                ]
            );
        }
    }
}
