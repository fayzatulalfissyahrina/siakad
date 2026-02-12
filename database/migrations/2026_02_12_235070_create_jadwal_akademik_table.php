<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->string('kode_mk');
            $table->string('id_ruang');
            $table->unsignedBigInteger('id_gol');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tahun_akademik');
            $table->string('semester_akademik');
            $table->timestamps();

            $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->cascadeOnDelete();
            $table->foreign('id_ruang')->references('id_ruang')->on('ruang')->cascadeOnDelete();
            $table->foreign('id_gol')->references('id_gol')->on('golongan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_akademik');
    }
};
