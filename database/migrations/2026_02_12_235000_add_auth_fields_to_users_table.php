<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->string('role')->default('mahasiswa')->after('username');
            $table->string('nim')->nullable()->unique()->after('role');
            $table->string('nip')->nullable()->unique()->after('nim');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropUnique(['nim']);
            $table->dropUnique(['nip']);
            $table->dropColumn(['username', 'role', 'nim', 'nip']);
        });
    }
};
