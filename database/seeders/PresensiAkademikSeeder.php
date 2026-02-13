<?php

namespace Database\Seeders;

use App\Models\PresensiAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PresensiAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $presensiList = [
            [
                'tanggal' => $today,
                'nim' => 'MHS001',
                'kode_mk' => 'IF102',
                'status_kehadiran' => 'hadir',
                'jam_masuk' => '08:05:00',
                'jam_keluar' => '10:00:00',
                'pertemuan_ke' => 5,
                'metode_presensi' => 'QR',
            ],
            [
                'tanggal' => $today,
                'nim' => 'MHS002',
                'kode_mk' => 'IF102',
                'status_kehadiran' => 'hadir',
                'jam_masuk' => '08:08:00',
                'jam_keluar' => '10:00:00',
                'pertemuan_ke' => 5,
                'metode_presensi' => 'QR',
            ],
            [
                'tanggal' => $today,
                'nim' => 'MHS003',
                'kode_mk' => 'IF102',
                'status_kehadiran' => 'izin',
                'jam_masuk' => null,
                'jam_keluar' => null,
                'pertemuan_ke' => 5,
                'metode_presensi' => 'Manual',
            ],
            [
                'tanggal' => $today,
                'nim' => 'MHS004',
                'kode_mk' => 'IF102',
                'status_kehadiran' => 'hadir',
                'jam_masuk' => '08:02:00',
                'jam_keluar' => '10:00:00',
                'pertemuan_ke' => 5,
                'metode_presensi' => 'QR',
            ],
            [
                'tanggal' => $today,
                'nim' => 'MHS005',
                'kode_mk' => 'IF102',
                'status_kehadiran' => 'sakit',
                'jam_masuk' => null,
                'jam_keluar' => null,
                'pertemuan_ke' => 5,
                'metode_presensi' => 'Manual',
            ],
            [
                'tanggal' => $yesterday,
                'nim' => 'MHS001',
                'kode_mk' => 'IF101',
                'status_kehadiran' => 'hadir',
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '10:00:00',
                'pertemuan_ke' => 4,
                'metode_presensi' => 'QR',
            ],
            [
                'tanggal' => $yesterday,
                'nim' => 'MHS002',
                'kode_mk' => 'IF101',
                'status_kehadiran' => 'hadir',
                'jam_masuk' => '08:03:00',
                'jam_keluar' => '10:00:00',
                'pertemuan_ke' => 4,
                'metode_presensi' => 'QR',
            ],
        ];

        foreach ($presensiList as $item) {
            PresensiAkademik::updateOrCreate(
                [
                    'tanggal' => $item['tanggal'],
                    'nim' => $item['nim'],
                    'kode_mk' => $item['kode_mk'],
                    'pertemuan_ke' => $item['pertemuan_ke'],
                ],
                [
                    'hari' => $this->mapHari($item['tanggal']),
                    'status_kehadiran' => $item['status_kehadiran'],
                    'jam_masuk' => $item['jam_masuk'],
                    'jam_keluar' => $item['jam_keluar'],
                    'keterangan' => null,
                    'metode_presensi' => $item['metode_presensi'],
                ]
            );
        }
    }

    private function mapHari(Carbon $tanggal): string
    {
        $map = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $map[$tanggal->format('l')] ?? 'Senin';
    }
}
