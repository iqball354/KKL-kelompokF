<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeahlianDosenController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\KonsentrasiJurusanController; // diganti

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
    ===================================*/
   Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');
   /* Keahlian Dosen */
   Route::get('/dosen/keahlian-dosen', [KeahlianDosenController::class, 'index'])->name('keahlian.index');
   Route::post('/dosen/keahlian-dosen', [KeahlianDosenController::class, 'store'])->name('keahlian.store');
   Route::put('/dosen/keahlian-dosen/{id}', [KeahlianDosenController::class, 'update'])->name('keahlian.update');
   Route::delete('/dosen/keahlian-dosen/{id}', [KeahlianDosenController::class, 'destroy'])->name('keahlian.destroy');
   Route::delete('/dosen/keahlian-dosen/{id}/dokumen/{type}/{index}', [KeahlianDosenController::class, 'deleteDoc'])->name('keahlian.deleteDoc');

   /* ================================
       KAPRODI
    ===================================*/
   Route::get('/kaprodi/dashboard', [DashboardController::class, 'kaprodi'])->name('kaprodi.dashboard');
   /* Keahlian Dosen */
   Route::get('/kaprodi/keahlian-dosen', [KeahlianDosenController::class, 'showForKaprodi'])->name('kaprodi.keahlian.show');
   Route::post('/kaprodi/keahlian-dosen/{id}/aksi', [KeahlianDosenController::class, 'aksiKaprodi'])->name('kaprodi.keahlian.aksi');
   /* Kurikulum */
   Route::get('/kaprodi/kurikulum', [KurikulumController::class, 'showForKaprodi'])->name('kaprodi.kurikulum.showForKaprodi');
   /* Konsentrasi Jurusan */
   Route::get('/kaprodi/konsentrasi', [KonsentrasiJurusanController::class, 'index'])->name('kaprodi.konsentrasi.index');
   Route::get('/kaprodi/konsentrasi/create', [KonsentrasiJurusanController::class, 'create'])->name('kaprodi.konsentrasi.create');
   Route::post('/kaprodi/konsentrasi/store', [KonsentrasiJurusanController::class, 'store'])->name('kaprodi.konsentrasi.store');
   Route::get('/kaprodi/konsentrasi/{id}/edit', [KonsentrasiJurusanController::class, 'edit'])->name('kaprodi.konsentrasi.edit');
   Route::put('/kaprodi/konsentrasi/{id}/update', [KonsentrasiJurusanController::class, 'update'])->name('kaprodi.konsentrasi.update');
   Route::delete('/kaprodi/konsentrasi/{id}/delete', [KonsentrasiJurusanController::class, 'destroy'])->name('kaprodi.konsentrasi.destroy');

   /* ================================
       DEKAN
    ===================================*/
   Route::get('/dekan/dashboard', [DashboardController::class, 'dekan'])->name('dekan.dashboard');
   /* Keahlian Dosen */
   Route::get('/dekan/keahlian-dosen', [KeahlianDosenController::class, 'showForDekan'])->name('dekan.keahlian.showForDekan');
   /* Kurikulum */
   Route::get('/dekan/kurikulum', [KurikulumController::class, 'showForDekan'])->name('dekan.kurikulum.showForDekan');
   /* Konsentrasi Jurusan */
   Route::get('/dekan/konsentrasi', [KonsentrasiJurusanController::class, 'showForDekan'])->name('dekan.konsentrasi.show');

   /* ================================
       WAREK 1
    ===================================*/
   Route::get('/warek1/dashboard', [DashboardController::class, 'warek1'])->name('warek1.dashboard');
   /* Keahlian Dosen */
   Route::get('/warek1/keahlian-dosen', [KeahlianDosenController::class, 'showForWarek1'])->name('warek1.keahlian.showForWarek1');
   /* Kurikulum */
   Route::get('/warek1/kurikulum', [KurikulumController::class, 'showForWarek1'])->name('warek1.kurikulum.showForWarek1');
   /* Konsentrasi Jurusan */
   Route::get('/warek1/konsentrasi', [KonsentrasiJurusanController::class, 'showForWarek1'])->name('warek1.konsentrasi.show');

   /* ================================
       HRD
    ===================================*/
   Route::get('/hrd/dashboard', [DashboardController::class, 'hrd'])->name('hrd.dashboard');
   /* Keahlian Dosen */
   Route::get('/hrd/keahlian-dosen', [KeahlianDosenController::class, 'showForHRD'])->name('hrd.keahlian.showForHrd');

   /* ================================
       SDM
    ===================================*/
   Route::get('/sdm/dashboard', [DashboardController::class, 'sdm'])->name('sdm.dashboard');
   /* Keahlian Dosen */
   Route::get('/sdm/keahlian-dosen', [KeahlianDosenController::class, 'showForSdm'])->name('sdm.keahlian.showForSdm');

   /* ================================
       AKADEMIK
    ===================================*/
   Route::get('/akademik/dashboard', [DashboardController::class, 'akademik'])->name('akademik.dashboard');
   /* Keahlian Dosen */
   Route::get('/akademik/keahlian-dosen', [KeahlianDosenController::class, 'showForAkademik'])->name('akademik.keahlian.showForAkademik');
   /* Kurikulum */
   Route::get('/akademik/kurikulum', [KurikulumController::class, 'index'])->name('akademik.kurikulum.index');
   Route::post('/akademik/kurikulum/store', [KurikulumController::class, 'store'])->name('akademik.kurikulum.store');
   Route::get('/akademik/kurikulum/{id}/edit', [KurikulumController::class, 'edit'])->name('akademik.kurikulum.edit');
   Route::put('/akademik/kurikulum/{id}/update', [KurikulumController::class, 'update'])->name('akademik.kurikulum.update');
   Route::delete('/akademik/kurikulum/{id}/delete', [KurikulumController::class, 'destroy'])->name('akademik.kurikulum.destroy');
   /* Konsentrasi Jurusan - Verifikasi */
   Route::get('/akademik/konsentrasi', [KonsentrasiJurusanController::class, 'verifikasiIndex'])->name('akademik.konsentrasi.index');
   Route::put('/akademik/konsentrasi/{id}/verifikasi', [KonsentrasiJurusanController::class, 'verifikasiUpdate'])->name('akademik.konsentrasi.verifikasi');
});
