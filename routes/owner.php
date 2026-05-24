<?php

use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerListingController;
use App\Http\Controllers\Owner\OwnerVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Listings management
    Route::get('/listings', [OwnerListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/create', [OwnerListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [OwnerListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [OwnerListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [OwnerListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [OwnerListingController::class, 'destroy'])->name('listings.destroy');
    Route::post('/listings/{listing}/toggle-status', [OwnerListingController::class, 'toggleStatus'])->name('listings.toggle-status');

    // Verification portal
    Route::get('/verification', [OwnerVerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [OwnerVerificationController::class, 'store'])->name('verification.store');
    Route::post('/bank-account', [OwnerVerificationController::class, 'updateBankAccount'])->name('bank-account.update');
    Route::post('/bank-account/check', [OwnerVerificationController::class, 'checkBankAccount'])->name('bank-account.check');

    // Reports list
    Route::get('/reports', [OwnerDashboardController::class, 'reports'])->name('reports');

    // Payments list
    Route::get('/payments', [OwnerDashboardController::class, 'payments'])->name('payments');
});
