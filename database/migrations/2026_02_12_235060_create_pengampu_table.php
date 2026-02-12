<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengampu', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk');
            $table->string('nip');
            $table->string('tahun_akademik');
            $table->string('semester_akademik');
            $table->string('status');
            $table->timestamps();

            $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->cascadeOnDelete();
            $table->foreign('nip')->references('nip')->on('dosen')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengampu');
    }
};
