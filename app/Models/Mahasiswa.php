<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'alamat',
        'no_hp',
        'email',
        'semester',
        'id_gol',
        'foto',
        'status',
        'tahun_masuk',
    ];

    public function getRouteKeyName()
    {
        return 'nim';
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_gol', 'id_gol');
    }

    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'nim', 'nim');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'nim', 'nim');
    }

    public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'krs', 'nim', 'kode_mk')
            ->withPivot(['tahun_akademik', 'semester_akademik', 'status_krs', 'nilai_akhir', 'nilai_angka', 'status_lulus'])
            ->withTimestamps();
    }
}
