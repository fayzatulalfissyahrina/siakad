<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_presensi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk');
            $table->string('nip');
            $table->unsignedBigInteger('id_gol');
            $table->string('tahun_akademik');
            $table->string('semester_akademik');
            $table->date('tanggal');
            $table->integer('pertemuan_ke');
            $table->string('token')->unique();
            $table->dateTime('expires_at');
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->cascadeOnDelete();
            $table->foreign('nip')->references('nip')->on('dosen')->cascadeOnDelete();
            $table->foreign('id_gol')->references('id_gol')->on('golongan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_presensi');
    }
};
