<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('kode_mk');
            $table->string('tahun_akademik');
            $table->string('semester_akademik');
            $table->string('status_krs');
            $table->string('nilai_akhir')->nullable();
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->string('status_lulus');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->cascadeOnDelete();
            $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
