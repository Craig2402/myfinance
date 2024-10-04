<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\lendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\recieveController;
use App\Http\Controllers\savingsController;
use App\Http\Controllers\transactionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/filter', [dashboardController::class, 'filter'])->name('dashboard.filter');

    Route::get('/lend', [lendController::class, 'index'])->name('lend.index');
    Route::get('/lend/create', [lendController::class, 'create'])->name('lend.create');
    Route::post('/lend', [lendController::class, 'store'])->name('lend.store');
    // Route::get('/lend/{lendModel}', [lendController::class, 'show'])->name('lend.show');
    // Route::get('/lend/{lendModel}/edit', [lendController::class, 'edit'])->name('lend.edit');
    // Route::put('/lend/{lendModel}', [lendController::class, 'update'])->name('lend.update');
    // Route::delete('/lend/{lendModel}', [lendController::class, 'destroy'])->name('lend.destroy');
    Route::patch('/lend/{lendModel}/mark-as-paid', [lendController::class, 'markAsPaid'])->name('lend.markAsPaid');


    Route::get('/transactions', [transactionsController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [transactionsController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [transactionsController::class, 'store'])->name('transactions.store');
    // Route::get('/transactions/{transactionsModel}', [transactionsController::class, 'show'])->name('transactions.show');
    // Route::get('/transactions/{transactionsModel}/edit', [transactionsController::class, 'edit'])->name('transactions.edit');
    // Route::put('/transactions/{transactionsModel}', [transactionsController::class, 'update'])->name('transactions.update');
    // Route::delete('/transactions/{transactionsModel}', [transactionsController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/savings', [savingsController::class, 'index'])->name('savings.index');
    Route::get('/savings/create', [savingsController::class, 'create'])->name('savings.create');
    Route::post('/savings', [savingsController::class, 'store'])->name('savings.store');
    Route::post('/savings/{SavingsTarget}', [savingsController::class, 'savingsstore'])->name('savings.payments.store');
    Route::get('/savings/{SavingsTarget}', [savingsController::class, 'show'])->name('savings.show');
    Route::get('/savings/{SavingsTarget}/save', [savingsController::class, 'save'])->name('savings.save');

    Route::get('/receive', [recieveController::class, 'index'])->name('receive.index');
    Route::get('/receive/create', [recieveController::class, 'create'])->name('receive.create');
    Route::post('/receive', [recieveController::class, 'store'])->name('receive.store');
    Route::get('/receive/{recieveModel}', [recieveController::class, 'show'])->name('receive.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
