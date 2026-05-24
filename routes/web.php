<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/kos/{slug}', [PublicController::class, 'detail'])->name('listings.show');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// Authenticated Actions
Route::middleware('auth')->group(function () {
    // Google Maps Short URL Resolver
    Route::get('/resolve-maps-url', [PublicController::class, 'resolveMapsUrl'])->name('maps.resolve');

    // Favorites
    Route::get('/favorites', [PublicController::class, 'favorites'])->name('listings.favorites');
    Route::post('/kos/{listing}/favorite', [PublicController::class, 'toggleFavorite'])->name('listings.favorite');
    
    // Reports
    Route::post('/kos/{listing}/report', [PublicController::class, 'submitReport'])->name('listings.report');

    // Payments (Mock system for anti-scam protection)
    Route::get('/checkout/{listing}', [PublicController::class, 'checkout'])->name('payments.checkout');
    Route::post('/checkout/{listing}/pay', [PublicController::class, 'processPayment'])->name('payments.process');
    Route::post('/checkout/{listing}/complete', [PublicController::class, 'completePayment'])->name('payments.complete');
    Route::get('/payment/success/{payment}', [PublicController::class, 'paymentSuccess'])->name('payments.success');
    Route::get('/checkout/instruksi/{payment}', [PublicController::class, 'paymentInstruction'])->name('payments.instruction');
    Route::post('/checkout/instruksi/{payment}/confirm', [PublicController::class, 'confirmPaymentManual'])->name('payments.confirm_manual');
    Route::get('/transaksi', [PublicController::class, 'paymentHistory'])->name('payments.history');
    Route::post('/checkout/instruksi/{payment}/cancel', [PublicController::class, 'cancelBooking'])->name('payments.cancel');
    Route::post('/checkout/instruksi/{payment}/confirm-checkin', [PublicController::class, 'confirmCheckin'])->name('payments.confirm_checkin');

    // Real-time Chat
    Route::prefix('chats')->name('chats.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/user/{userId}', [ChatController::class, 'conversation'])->name('conversation');
        Route::post('/send', [ChatController::class, 'send'])->name('send');
    });
});

// Import modules
require __DIR__.'/auth.php';
require __DIR__.'/owner.php';
require __DIR__.'/admin.php';
