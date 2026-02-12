<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'deskripsi',
        'silabus',
    ];

    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'kode_mk', 'kode_mk');
    }

    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'kode_mk', 'kode_mk');
    }

    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'kode_mk', 'kode_mk');
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'pengampu', 'kode_mk', 'nip')
            ->withPivot(['tahun_akademik', 'semester_akademik', 'status'])
            ->withTimestamps();
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'krs', 'kode_mk', 'nim')
            ->withPivot(['tahun_akademik', 'semester_akademik', 'status_krs', 'nilai_akhir', 'nilai_angka', 'status_lulus'])
            ->withTimestamps();
    }
}
