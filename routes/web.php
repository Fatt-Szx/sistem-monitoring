<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminMagangController;
use App\Http\Controllers\Admin\AdminMahasiswaController;
use App\Http\Controllers\Admin\AdminPembimbingController;
use App\Http\Controllers\Admin\AdminPenempatanController;
use App\Http\Controllers\Admin\AdminPengaturanController;
use App\Http\Controllers\Admin\AdminProdiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DosenBimbinganController;
use App\Http\Controllers\Dosen\DosenLaporanController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaJadwalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //Master
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('prodi', AdminProdiController::class);
        Route::resource('mahasiswa', AdminMahasiswaController::class);
        Route::resource('pembimbing', AdminPembimbingController::class);
        Route::resource('magang', AdminMagangController::class);
    });

    //Penjadwalan dan Penempatan
    Route::prefix('penjadwalan')->name('penjadwalan.')->group(function () {
        Route::resource('jadwal', AdminJadwalController::class);
        Route::resource('penempatan', AdminPenempatanController::class);
    });

    Route::resource('laporan', AdminLaporanController::class);
    Route::resource('pengaturan', AdminPengaturanController::class);


    //Laporan
});

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/bimbingan', DosenBimbinganController::class);
    Route::resource('/jadwal', DosenBimbinganController::class);
    Route::resource('/laporan', DosenLaporanController::class);
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/jadwal', MahasiswaJadwalController::class);
});
