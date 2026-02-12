<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'alamat',
        'no_hp',
        'email',
        'foto',
        'status',
    ];

    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'nip', 'nip');
    }

    public function golonganWali()
    {
        return $this->hasMany(Golongan::class, 'dosen_wali', 'nip');
    }

    public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'pengampu', 'nip', 'kode_mk')
            ->withPivot(['tahun_akademik', 'semester_akademik', 'status'])
            ->withTimestamps();
    }
}
