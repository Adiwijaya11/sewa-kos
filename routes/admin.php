<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verification approvals
    Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::post('/verifications/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    // Listing Moderation & Verification
    Route::get('/listings', [AdminDashboardController::class, 'listings'])->name('listings.index');
    Route::post('/listings/{listing}/verify', [AdminDashboardController::class, 'verifyListing'])->name('listings.verify');
    Route::delete('/listings/{listing}', [AdminDashboardController::class, 'deleteListing'])->name('listings.destroy');

    // Reports Management
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports.index');
    Route::post('/reports/{report}/resolve', [AdminDashboardController::class, 'resolveReport'])->name('reports.resolve');
    Route::post('/reports/{report}/dismiss', [AdminDashboardController::class, 'dismissReport'])->name('reports.dismiss');

    // Users Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::post('/users/{user}/suspend', [AdminDashboardController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{user}/activate', [AdminDashboardController::class, 'activateUser'])->name('users.activate');

    // Payments monitoring
    Route::get('/payments', [AdminDashboardController::class, 'payments'])->name('payments.index');

    // Transaction Tracking
    Route::get('/tracking', [AdminDashboardController::class, 'tracking'])->name('tracking');

    // Earnings / Income Analytics
    Route::get('/earnings', [AdminDashboardController::class, 'earnings'])->name('earnings');

    // Security Center
    Route::get('/security', [AdminDashboardController::class, 'securityIndex'])->name('security.index');
    Route::post('/security/blacklist', [AdminDashboardController::class, 'blacklistIp'])->name('security.blacklist');
    Route::delete('/security/blacklist/{ip}', [AdminDashboardController::class, 'unblacklistIp'])->name('security.unblacklist');

    // System Settings Center
    Route::get('/settings', [AdminDashboardController::class, 'settingsIndex'])->name('settings.index');
    Route::get('/settings/attributes', [AdminDashboardController::class, 'attributesIndex'])->name('settings.attributes');
    Route::post('/settings/update', [AdminDashboardController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/facilities', [AdminDashboardController::class, 'storeFacility'])->name('settings.facilities.store');
    Route::delete('/settings/facilities/{facility}', [AdminDashboardController::class, 'destroyFacility'])->name('settings.facilities.destroy');
    Route::post('/settings/gender-types', [AdminDashboardController::class, 'storeGenderType'])->name('settings.gender-types.store');
    Route::delete('/settings/gender-types/{type}', [AdminDashboardController::class, 'destroyGenderType'])->name('settings.gender-types.destroy');
});
