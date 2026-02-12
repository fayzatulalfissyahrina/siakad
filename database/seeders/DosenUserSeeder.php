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
        Dosen::updateOrCreate(
            ['nip' => 'DSN001'],
            [
                'nama' => 'Dosen Satu',
                'alamat' => 'Jl. Kampus No. 1',
                'no_hp' => '081234567890',
                'email' => 'dosen1@example.com',
                'foto' => null,
                'status' => 'aktif',
            ]
        );

        User::updateOrCreate(
            ['username' => 'DSN001'],
            [
                'name' => 'Dosen Satu',
                'email' => 'dosen1@example.com',
                'role' => 'dosen',
                'nim' => null,
                'nip' => 'DSN001',
                'password' => Hash::make('dosen123'),
            ]
        );
    }
}
