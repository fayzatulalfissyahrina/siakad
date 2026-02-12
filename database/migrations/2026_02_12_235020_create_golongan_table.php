<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->id('id_gol');
            $table->string('nama_gol');
            $table->string('program_studi');
            $table->string('angkatan');
            $table->string('dosen_wali')->nullable();
            $table->integer('kapasitas');
            $table->timestamps();

            $table->foreign('dosen_wali')->references('nip')->on('dosen')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongan');
    }
};
