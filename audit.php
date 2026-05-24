<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Payment;
use App\Models\Listing;
use App\Models\User;
use App\Models\OwnerVerification;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Chat;

echo "=== SISTEM AUDIT ===" . PHP_EOL . PHP_EOL;

// Users
echo "--- USERS ---" . PHP_EOL;
echo "Total users   : " . User::count() . PHP_EOL;
echo "Role user     : " . User::where('role','user')->count() . PHP_EOL;
echo "Role owner    : " . User::where('role','owner')->count() . PHP_EOL;
echo "Role admin    : " . User::where('role','admin')->count() . PHP_EOL;
echo "Verified owner: " . User::where('is_verified',true)->count() . PHP_EOL;
echo PHP_EOL;

// Listings
echo "--- LISTINGS ---" . PHP_EOL;
echo "Total kos     : " . Listing::count() . PHP_EOL;
echo "Active        : " . Listing::where('status','active')->count() . PHP_EOL;
echo "Inactive      : " . Listing::where('status','inactive')->count() . PHP_EOL;
echo "Verified      : " . Listing::where('is_verified',true)->count() . PHP_EOL;
echo "Unverified    : " . Listing::where('is_verified',false)->count() . PHP_EOL;
echo "Has lat/lng   : " . Listing::whereNotNull('latitude')->whereNotNull('longitude')->count() . PHP_EOL;
echo "No lat/lng    : " . Listing::whereNull('latitude')->orWhereNull('longitude')->count() . PHP_EOL;
echo "Has images    : " . Listing::has('images')->count() . PHP_EOL;
echo "No images     : " . Listing::doesntHave('images')->count() . PHP_EOL;
echo "available_rooms < 0: " . Listing::where('available_rooms','<',0)->count() . PHP_EOL;
echo "available > total : " . Listing::whereRaw('available_rooms > total_rooms')->count() . PHP_EOL;
echo PHP_EOL;

// Payments
echo "--- PAYMENTS ---" . PHP_EOL;
echo "Total         : " . Payment::count() . PHP_EOL;
echo "success       : " . Payment::where('payment_status','success')->count() . PHP_EOL;
echo "completed     : " . Payment::where('payment_status','completed')->count() . PHP_EOL;
echo "pending       : " . Payment::where('payment_status','pending')->count() . PHP_EOL;
echo "cancelled     : " . Payment::where('payment_status','cancelled')->count() . PHP_EOL;
echo "failed        : " . Payment::where('payment_status','failed')->count() . PHP_EOL;
echo "Orphan (no user)   : " . Payment::whereDoesntHave('user')->count() . PHP_EOL;
echo "Orphan (no listing): " . Payment::whereDoesntHave('listing')->count() . PHP_EOL;
echo "Total amount (all) : Rp " . number_format(Payment::sum('amount')) . PHP_EOL;
echo PHP_EOL;

// Verifications
echo "--- VERIFICATIONS ---" . PHP_EOL;
echo "Total         : " . OwnerVerification::count() . PHP_EOL;
echo "Pending       : " . OwnerVerification::where('status','pending')->count() . PHP_EOL;
echo "Approved      : " . OwnerVerification::where('status','approved')->count() . PHP_EOL;
echo "Rejected      : " . OwnerVerification::where('status','rejected')->count() . PHP_EOL;
echo PHP_EOL;

// Reports
echo "--- REPORTS ---" . PHP_EOL;
echo "Total         : " . Report::count() . PHP_EOL;
echo "Pending       : " . Report::where('status','pending')->count() . PHP_EOL;
echo "Resolved      : " . Report::where('status','resolved')->count() . PHP_EOL;
echo "Dismissed     : " . Report::where('status','dismissed')->count() . PHP_EOL;
echo PHP_EOL;

// Chats
echo "--- CHATS ---" . PHP_EOL;
echo "Total messages: " . Chat::count() . PHP_EOL;
echo "Unread        : " . Chat::where('is_read',false)->count() . PHP_EOL;
echo PHP_EOL;

// Settings
echo "--- SETTINGS ---" . PHP_EOL;
echo "Commission fee: " . Setting::get('platform_commission_fee','NOT SET') . "%" . PHP_EOL;
echo PHP_EOL;

// Data integrity checks
echo "=== INTEGRITY CHECKS ===" . PHP_EOL;

// Listings with no owner
$noOwner = Listing::whereDoesntHave('owner')->count();
echo "Listings without owner : $noOwner" . PHP_EOL;

// Payments amount = 0
$zeroAmt = Payment::where('amount',0)->orWhereNull('amount')->count();
echo "Payments amount 0/null : $zeroAmt" . PHP_EOL;

// Users without email
$noEmail = User::whereNull('email')->orWhere('email','')->count();
echo "Users without email    : $noEmail" . PHP_EOL;

// Owner listings count
$ownerNoListing = User::where('role','owner')->doesntHave('listings')->count();
echo "Owners with 0 listings : $ownerNoListing" . PHP_EOL;

echo PHP_EOL . "=== AUDIT SELESAI ===" . PHP_EOL;
