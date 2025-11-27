<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeahlianDosenController;

// Halaman login
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root ke login
Route::get('/', function () {
   return redirect()->route('login');
});

// Routes khusus role, harus login
Route::middleware(['auth'])->group(function () {

   /* ================================
       DOSEN
    ==================================*/
   Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');
   Route::get('/dosen/keahlian-dosen', [KeahlianDosenController::class, 'index'])->name('keahlian.index');
   Route::post('/dosen/keahlian-dosen', [KeahlianDosenController::class, 'store'])->name('keahlian.store');
   Route::put('/dosen/keahlian-dosen/{id}', [KeahlianDosenController::class, 'update'])->name('keahlian.update');
   Route::delete('/dosen/keahlian-dosen/{id}', [KeahlianDosenController::class, 'destroy'])->name('keahlian.destroy');
   Route::delete('/dosen/keahlian-dosen/{id}/dokumen/{type}/{index}', [KeahlianDosenController::class, 'deleteDoc'])->name('keahlian.deleteDoc');

   /* ================================
       KAPRODI
    ==================================*/
   Route::get('/kaprodi/dashboard', [DashboardController::class, 'kaprodi'])->name('kaprodi.dashboard');
   Route::get('/kaprodi/keahlian-dosen', [KeahlianDosenController::class, 'showForKaprodi'])->name('kaprodi.keahlian.show');
   Route::post('/kaprodi/keahlian-dosen/{id}/aksi', [KeahlianDosenController::class, 'aksiKaprodi'])->name('kaprodi.keahlian.aksi');

   /* ================================
       DEKAN
    ==================================*/
   Route::get('/dekan/dashboard', [DashboardController::class, 'dekan'])->name('dekan.dashboard');
   Route::get('/dekan/keahlian-dosen', [KeahlianDosenController::class, 'showForDekan'])->name('dekan.keahlian.showForDekan');

   /* ================================
       WAREK 1
    ==================================*/
   Route::get('/warek1/dashboard', [DashboardController::class, 'warek1'])->name('warek1.dashboard');
   Route::get('/warek1/keahlian-dosen', [KeahlianDosenController::class, 'showForWarek1'])->name('warek1.keahlian.showForWarek1');

   /* ================================
       HRD
    ==================================*/
   Route::get('/hrd/dashboard', [DashboardController::class, 'hrd'])->name('hrd.dashboard');
   Route::get('/hrd/keahlian-dosen', [KeahlianDosenController::class, 'showForHRD'])->name('hrd.keahlian.show');

   /* ================================
       SDM
    ==================================*/
   Route::get('/sdm/dashboard', [DashboardController::class, 'sdm'])->name('sdm.dashboard');
   Route::get('/sdm/keahlian-dosen', [KeahlianDosenController::class, 'showForSdm'])->name('sdm.keahlian.showForSdm');

   /* ================================
       AKADEMIK
    ==================================*/
   Route::get('/akademik/dashboard', [DashboardController::class, 'akademik'])->name('akademik.dashboard');
   Route::get('/akademik/keahlian-dosen', [KeahlianDosenController::class, 'showForAkademik'])->name('akademik.keahlian.show');
   Route::post('/akademik/keahlian-dosen/{id}/terima', [KeahlianDosenController::class, 'aksiAkademik'])->name('akademik.keahlian.terima');
});
