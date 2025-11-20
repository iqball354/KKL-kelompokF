<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman default → langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman login (hanya untuk guest)
Route::get('/login', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

// Logout (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman dashboard global (harus login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Route khusus per role (harus login)
Route::middleware(['auth'])->group(function () {

    // Dosen
    Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');

    // Kaprodi
    Route::get('/kaprodi/dashboard', [DashboardController::class, 'kaprodi'])->name('kaprodi.dashboard');

    // Dekan
    Route::get('/dekan/dashboard', [DashboardController::class, 'dekan'])->name('dekan.dashboard');

    // Warek 1
    Route::get('/warek1/dashboard', [DashboardController::class, 'warek1'])->name('warek1.dashboard');

    // HRD
    Route::get('/hrd/dashboard', [DashboardController::class, 'hrd'])->name('hrd.dashboard');

    // SDM
    Route::get('/sdm/dashboard', [DashboardController::class, 'sdm'])->name('sdm.dashboard');

    // Akademik
    Route::get('/akademik/dashboard', [DashboardController::class, 'akademik'])->name('akademik.dashboard');
});
