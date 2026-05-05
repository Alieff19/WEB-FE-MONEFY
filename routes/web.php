<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;

// ── Auth Routes (tidak perlu login) ──────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// ── Protected Routes (harus login dulu) ──────────────────────────────────────
Route::middleware(['checkApi'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Semua fitur di bawah ini harus login dulu
    Route::get('/history',  [HistoryController::class,  'index'])->name('history');
    Route::get('/analytic', [AnalyticController::class, 'index'])->name('analytic');
    Route::get('/profile',  [ProfileController::class,  'index'])->name('profile');
    Route::get('/help',     [HelpController::class,     'index'])->name('help');

    // Bills
    Route::get('/bills',          [BillController::class, 'index'])->name('bills');
    Route::post('/bills',         [BillController::class, 'store'])->name('bills.store');
    Route::put('/bills/{id}',     [BillController::class, 'update'])->name('bills.update');
    Route::delete('/bills/{id}',  [BillController::class, 'destroy'])->name('bills.destroy');

    // Savings
    Route::get('/saving',          [SavingController::class, 'index'])->name('saving');
    Route::post('/saving',         [SavingController::class, 'store'])->name('savings.store');
    Route::put('/saving/{id}',     [SavingController::class, 'update'])->name('savings.update');
    Route::delete('/saving/{id}',  [SavingController::class, 'destroy'])->name('savings.destroy');

    // Logout
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

