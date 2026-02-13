<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PengampuController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RuangController;
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
        Route::resource('mahasiswa', MahasiswaController::class)->except(['show', 'create', 'edit']);
        Route::resource('dosen', DosenController::class)->except(['show', 'create', 'edit']);
        Route::resource('mata-kuliah', MataKuliahController::class)->except(['show', 'create', 'edit']);
        Route::resource('golongan', GolonganController::class)->except(['show', 'create', 'edit']);
        Route::resource('ruang', RuangController::class)->except(['show', 'create', 'edit']);
        Route::resource('pengampu', PengampuController::class)->except(['show', 'create', 'edit']);
        Route::resource('jadwal', JadwalController::class)->except(['show', 'create', 'edit']);
        Route::resource('krs', KrsController::class)->except(['show', 'create', 'edit']);
        Route::resource('presensi', PresensiController::class)->except(['show', 'create', 'edit']);
        Route::get('/admin/nilai', [NilaiController::class, 'index'])->name('admin.nilai');
        Route::post('/admin/nilai', [NilaiController::class, 'store'])->name('admin.nilai.store');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');
    });

    Route::middleware('role:dosen')->group(function () {
        Route::get('/dosen/jadwal', [JadwalController::class, 'index'])->name('dosen.jadwal');
        Route::get('/dosen/presensi', [PresensiController::class, 'index'])->name('dosen.presensi');
        Route::post('/dosen/presensi/open', [PresensiController::class, 'openSession'])->name('dosen.presensi.open');
        Route::post('/dosen/presensi/close/{session}', [PresensiController::class, 'closeSession'])->name('dosen.presensi.close');
        Route::get('/dosen/nilai', [NilaiController::class, 'index'])->name('dosen.nilai');
        Route::post('/dosen/nilai', [NilaiController::class, 'store'])->name('dosen.nilai.store');
        Route::get('/dosen/laporan', [LaporanController::class, 'index'])->name('dosen.laporan');
    });

    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/jadwal', [JadwalController::class, 'index'])->name('mahasiswa.jadwal');
        Route::get('/mahasiswa/krs', [KrsController::class, 'index'])->name('mahasiswa.krs');
        Route::get('/mahasiswa/presensi', [PresensiController::class, 'index'])->name('mahasiswa.presensi');
        Route::post('/mahasiswa/presensi/hadir', [PresensiController::class, 'clickHadir'])->name('mahasiswa.presensi.hadir');
    });
});
