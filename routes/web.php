<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesanGrupController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/home', [LandingController::class, 'index'])->name('home');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.detail');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| MEMBER ROUTES (AUTHENTICATED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard Auto-Redirect
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Komunitas
    Route::get('/komunitas', [KomunitasController::class, 'index'])->name('komunitas.index');
    Route::get('/komunitas/{id}', [KomunitasController::class, 'show'])->name('komunitas.show');
    Route::post('/komunitas/join', [KomunitasController::class, 'join'])->name('komunitas.join');
    Route::get('/komunitas-saya', [KomunitasController::class, 'myCommunities'])->name('komunitas.my');
    Route::get('/komunitas/{id}/events', [KomunitasController::class, 'events'])->name('komunitas.events');

    // Events & XP
    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
    Route::get('/events/riwayat', [EventsController::class, 'riwayat'])->name('events.riwayat');
    Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');
    Route::get('/events/{id}/daftar', [EventsController::class, 'showRegisterForm'])->name('events.register');
    Route::post('/events/{id}/daftar', [EventsController::class, 'storeRegistration'])->name('events.storeRegistration');
    Route::post('/events/{id}/klaim', [EventsController::class, 'klaimXP'])->name('events.klaim');

    // Pembayaran
    Route::get('/events/{id}/bayar', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/events/{id}/bayar', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Chat (Non-Realtime)
    Route::get('/grup/{id}/chat', [PesanGrupController::class, 'chat'])->name('grup.chat');
    Route::post('/grup/{id}/chat', [PesanGrupController::class, 'sendMessage'])->name('grup.chat.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

    // 2. Manajemen Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verify'])->name('pembayaran.verify');

    // 3. Manajemen Lomba (EventsController)
    Route::get('/lomba', [EventsController::class, 'adminIndex'])->name('lomba');
    Route::post('/lomba/store', [EventsController::class, 'store'])->name('lomba.store');
    Route::get('/kelola-lomba/{id}', [EventsController::class, 'adminShow'])->name('lomba.show');
    Route::post('/kelola-lomba/{id}/update', [EventsController::class, 'adminUpdate'])->name('lomba.update');
    Route::post('/kelola-lomba/{id}/finish', [EventsController::class, 'adminFinish'])->name('lomba.finish');

    // 4. Manajemen Komunitas
    Route::get('/komunitas', [KomunitasController::class, 'adminList'])->name('komunitas');
    Route::post('/komunitas/store', [KomunitasController::class, 'store'])->name('komunitas.store');

    // 5. Manajemen Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan'); // <-- Ubah jadi 'laporan' saja
    Route::post('/laporan/{id}/resolve', [LaporanController::class, 'resolve'])->name('laporan.resolve');

    // 6. Manajemen Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::resource('berita', BeritaController::class)->except(['index']);
});

/*
|--------------------------------------------------------------------------
| MODERATOR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('moderator/komunitas/{id}')->name('moderator.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'moderatorIndex'])->name('index'); 
    Route::get('/chat', [DashboardController::class, 'moderatorChat'])->name('chat');
    Route::get('/events', [DashboardController::class, 'moderatorEvents'])->name('events');
});

/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/
Route::view('/tentang_kami', 'tentang_kami');
Route::view('/reset-password', 'reset-password');