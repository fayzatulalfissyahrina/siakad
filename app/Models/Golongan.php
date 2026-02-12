<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongan';
    protected $primaryKey = 'id_gol';

    protected $fillable = [
        'nama_gol',
        'program_studi',
        'angkatan',
        'dosen_wali',
        'kapasitas',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_gol', 'id_gol');
    }

    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'id_gol', 'id_gol');
    }

    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'dosen_wali', 'nip');
    }
}
