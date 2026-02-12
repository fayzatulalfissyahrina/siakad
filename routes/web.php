<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
        Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');
    });

    Route::middleware('role:dosen')->group(function () {
        Route::get('/dosen/jadwal', [JadwalController::class, 'index'])->name('dosen.jadwal');
        Route::get('/dosen/presensi', [PresensiController::class, 'index'])->name('dosen.presensi');
        Route::get('/dosen/nilai', [NilaiController::class, 'index'])->name('dosen.nilai');
        Route::get('/dosen/laporan', [LaporanController::class, 'index'])->name('dosen.laporan');
    });

    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/jadwal', [JadwalController::class, 'index'])->name('mahasiswa.jadwal');
        Route::get('/mahasiswa/krs', [KrsController::class, 'index'])->name('mahasiswa.krs');
        Route::get('/mahasiswa/presensi', [PresensiController::class, 'index'])->name('mahasiswa.presensi');
    });
});
