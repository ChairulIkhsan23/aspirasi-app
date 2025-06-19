<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\AspirasiExportController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

/*
|--------------------------------------------------------------------------
| Halaman Dashboard (Hanya untuk user yang login & sudah verifikasi email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AspirasiController::class, 'index'])->name('dashboard');
    Route::post('/aspirasi/{id}/vote', [AspirasiController::class, 'vote'])->name('aspirasi.vote');
});
/*
|--------------------------------------------------------------------------
| Auth Routes - Login, Register, Logout
|--------------------------------------------------------------------------
*/
// Halaman login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// Halaman register
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Verifikasi Email
|--------------------------------------------------------------------------
*/
// Halaman menunggu verifikasi email
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

// Verifikasi email dari link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Menandai email sudah diverifikasi
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Profil (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Default Auth Routes (Laravel Breeze / Fortify)
|--------------------------------------------------------------------------
*/
Route::get('/aspirasi/export/pdf', [AspirasiExportController::class, 'pdf'])->name('aspirasi.export.pdf');
Route::get('/aspirasi/export/excel', [AspirasiExportController::class, 'excel'])->name('aspirasi.export.excel');

/*
|--------------------------------------------------------------------------
| Default Auth Routes (Laravel Breeze / Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
