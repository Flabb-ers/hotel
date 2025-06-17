<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\Admin\TamuController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\AuthController as TamuAuthController;
use App\Http\Controllers\Frontend\DashboardController as TamuDashboardController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/booking/{kamar}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Tamu Auth Routes
Route::prefix('tamu')->name('tamu.')->group(function () {
    // Guest routes
    Route::middleware('guest:tamu')->group(function () {
        Route::get('login', [TamuAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [TamuAuthController::class, 'login']);
        Route::get('register', [TamuAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [TamuAuthController::class, 'register']);
    });

    // Authenticated routes
    Route::middleware('tamu.auth')->group(function () {
        Route::get('dashboard', [TamuDashboardController::class, 'index'])->name('dashboard');
        Route::get('transaksi/{id}', [TamuDashboardController::class, 'showTransaksi'])->name('transaksi.show');
        Route::get('profile', [TamuDashboardController::class, 'profile'])->name('profile');
        Route::put('profile', [TamuDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('logout', [TamuAuthController::class, 'logout'])->name('logout');
    });
});

// Payment Routes
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('create/{transaksi}', [PaymentController::class, 'createPayment'])->name('create');
    Route::post('notification', [PaymentController::class, 'handleNotification'])->name('notification');
    Route::get('finish', [PaymentController::class, 'paymentFinish'])->name('finish');
    Route::get('unfinish', [PaymentController::class, 'paymentUnfinish'])->name('unfinish');
    Route::get('error', [PaymentController::class, 'paymentError'])->name('error');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Test login route
    Route::get('test-login', function() {
        $petugas = \App\Models\Petugas::where('username', 'admin')->first();
        if ($petugas) {
            \Illuminate\Support\Facades\Auth::guard('petugas')->login($petugas);
            return redirect()->route('admin.dashboard')->with('success', 'Test login berhasil!');
        }
        return redirect()->route('admin.login')->with('error', 'Test login gagal!');
    })->name('test-login');

    // Protected admin routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('kamar', KamarController::class);
        Route::resource('tamu', TamuController::class);
        Route::resource('transaksi', TransaksiController::class);
    });
});
