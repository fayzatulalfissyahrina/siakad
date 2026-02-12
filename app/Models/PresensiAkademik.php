<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiAkademik extends Model
{
    use HasFactory;

    protected $table = 'presensi_akademik';

    protected $fillable = [
        'hari',
        'tanggal',
        'nim',
        'kode_mk',
        'status_kehadiran',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
        'pertemuan_ke',
        'metode_presensi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}
