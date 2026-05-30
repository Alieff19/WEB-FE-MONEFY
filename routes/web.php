<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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
    Route::post('/profile/upload', [ProfileController::class, 'uploadAvatar'])->name('profile.upload');
    Route::get('/help',     [HelpController::class,     'index'])->name('help');

    // Bills
    Route::get('/bills',          [BillController::class, 'index'])->name('bills');
    Route::post('/bills',         [BillController::class, 'store'])->name('bills.store');
    Route::put('/bills/{id}',     [BillController::class, 'update'])->name('bills.update');
    Route::post('/bills/{id}/pay', [BillController::class, 'pay'])->name('bills.pay');
    Route::delete('/bills/{id}',  [BillController::class, 'destroy'])->name('bills.destroy');

    // Wishlist
    Route::get('/wishlist',          [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist',         [WishlistController::class, 'store'])->name('wishlist.store');
    Route::put('/wishlist/{id}',     [WishlistController::class, 'update'])->name('wishlist.update');
    Route::post('/wishlist/{id}/pay', [WishlistController::class, 'pay'])->name('wishlist.pay');
    Route::delete('/wishlist/{id}',  [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Transactions & Wallets (AJAX endpoints — dipakai oleh Add Transaction modal)
    Route::post('/transactions',  [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/wallets',       [TransactionController::class, 'storeWallet'])->name('wallet.store');
    Route::get('/api/wallets',    [TransactionController::class, 'wallets'])->name('wallet.list');

    // Wallet Pages (halaman dedicated seperti mobile)
    Route::get('/wallet',            [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/create',     [WalletController::class, 'create'])->name('wallet.create');
    Route::post('/wallet',           [WalletController::class, 'store'])->name('wallet.store.page');
    Route::delete('/wallet/{id}',    [WalletController::class, 'destroy'])->name('wallet.destroy');

    // AI Chat
    Route::get('/ai-assistant', [\App\Http\Controllers\AiController::class, 'index'])->name('ai.index');
    Route::post('/ai-assistant/chat', [\App\Http\Controllers\AiController::class, 'chat'])->name('ai.chat');

    // Logout
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

