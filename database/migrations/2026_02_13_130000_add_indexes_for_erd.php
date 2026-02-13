<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan index untuk optimasi query
        DB::statement('CREATE INDEX idx_jadwal_gol_tahun_semester ON jadwal_akademik(id_gol, tahun_akademik, semester_akademik)');
        DB::statement('CREATE INDEX idx_jadwal_hari ON jadwal_akademik(hari)');
        DB::statement('CREATE INDEX idx_pengampu_mk_tahun_semester ON pengampu(kode_mk, tahun_akademik, semester_akademik)');
        DB::statement('CREATE INDEX idx_krs_nim_tahun_semester ON krs(nim, tahun_akademik, semester_akademik)');
        DB::statement('CREATE INDEX idx_presensi_nim_mk ON presensi_akademik(nim, kode_mk)');
        
        // Menambahkan unique constraint untuk mencegah duplikasi
        DB::statement('CREATE UNIQUE INDEX idx_krs_unique ON krs(nim, kode_mk, tahun_akademik, semester_akademik)');
        DB::statement('CREATE UNIQUE INDEX idx_pengampu_unique ON pengampu(kode_mk, nip, tahun_akademik, semester_akademik)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_jadwal_gol_tahun_semester ON jadwal_akademik');
        DB::statement('DROP INDEX IF EXISTS idx_jadwal_hari ON jadwal_akademik');
        DB::statement('DROP INDEX IF EXISTS idx_pengampu_mk_tahun_semester ON pengampu');
        DB::statement('DROP INDEX IF EXISTS idx_krs_nim_tahun_semester ON krs');
        DB::statement('DROP INDEX IF EXISTS idx_presensi_nim_mk ON presensi_akademik');
        DB::statement('DROP INDEX IF EXISTS idx_krs_unique ON krs');
        DB::statement('DROP INDEX IF EXISTS idx_pengampu_unique ON pengampu');
    }
};
