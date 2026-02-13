<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrPresensi extends Model
{
    use HasFactory;

    protected $table = 'qr_presensi';

    protected $fillable = [
        'kode_mk',
        'nip',
        'id_gol',
        'tahun_akademik',
        'semester_akademik',
        'tanggal',
        'pertemuan_ke',
        'token',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'expires_at' => 'datetime',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_gol', 'id_gol');
    }
}
