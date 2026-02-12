<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->integer('semester');
            $table->unsignedBigInteger('id_gol');
            $table->string('foto')->nullable();
            $table->string('status');
            $table->string('tahun_masuk');
            $table->timestamps();

            $table->foreign('id_gol')->references('id_gol')->on('golongan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
