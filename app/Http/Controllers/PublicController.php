<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Favorite;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $featured = Listing::active()->verified()->with(['images', 'owner'])->latest()->take(3)->get();
        $recent = Listing::active()->with(['images', 'owner'])->latest()->take(6)->get();
        $cities = Listing::active()->distinct()->pluck('city')->take(5);

        // Fetch real database statistics
        $totalVerifiedKos = Listing::active()->verified()->count();
        $totalPencariKos = \App\Models\User::where('role', 'user')->count();
        $totalActiveListings = Listing::active()->count();

        // Safety rate calculation based on reported vs resolved reports
        $totalReports = Report::count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $safetyRate = $totalReports > 0 ? round((($totalReports - $resolvedReports) / $totalReports) * 100, 1) : 99.8;

        // Fetch real active listing counts for popular cities
        $jakartaCount = Listing::active()->where('city', 'like', 'Jakarta%')->count();
        $bandungCount = Listing::active()->where('city', 'Bandung')->count();
        $jogjaCount = Listing::active()->where('city', 'Yogyakarta')->count();
        $surabayaCount = Listing::active()->where('city', 'Surabaya')->count();

        return view('home', compact(
            'featured', 
            'recent', 
            'cities', 
            'totalVerifiedKos', 
            'totalPencariKos', 
            'totalActiveListings',
            'safetyRate',
            'jakartaCount',
            'bandungCount',
            'jogjaCount',
            'surabayaCount'
        ));
    }

    public function search(Request $request)
    {
        $query = Listing::active()->with([
            'images', 
            'owner', 
            'facilities', 
            'payments' => function ($q) {
                $q->whereIn('payment_status', ['success', 'completed']);
            }
        ]);

        // Title/Address search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->filterPrice($request->min_price, $request->max_price);
        }

        // City
        if ($request->filled('city')) {
            $query->filterCity($request->city);
        }

        // Gender Type
        if ($request->filled('gender_type')) {
            $query->gender($request->gender_type);
        }

        // Room Size Filter
        if ($request->filled('room_size')) {
            $query->where('room_size', 'like', '%' . $request->room_size . '%');
        }

        // Facilities Filter
        if ($request->filled('facilities')) {
            $query->filterFacilities($request->facilities);
        }

        // Verified Only Filter
        if ($request->filled('verified') && $request->verified === '1') {
            $query->where('is_verified', true);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'popular') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Clone the query to fetch matching listings for map markers, capped to a safe limit of 300 to protect server memory and browser DOM rendering
        $mapQuery = clone $query;
        $mapListings = $mapQuery->take(300)->get(['title', 'slug', 'price', 'latitude', 'longitude']);

        $listings = $query->paginate(15)->withQueryString();
        $facilitiesList = Facility::all();
        $cities = [
            'Ambon', 'Badung', 'Bali', 'Balikpapan', 'Banda Aceh', 'Bandar Lampung', 'Bandung', 
            'Bangka', 'Bangkalan', 'Banjar', 'Banjarbaru', 'Banjarmasin', 'Bantul', 'Banyumas', 
            'Banyuwangi', 'Batam', 'Batang', 'Batu', 'Bekasi', 'Bengkulu', 'Bintan', 'Blitar', 
            'Blora', 'Bogor', 'Bojonegoro', 'Bondowoso', 'Bontang', 'Boyolali', 'Brebes', 'Bukittinggi',
            'Ciamis', 'Cianjur', 'Cilacap', 'Cilegon', 'Cimahi', 'Cirebon', 'Deli Serdang', 'Demak', 
            'Denpasar', 'Depok', 'Dumai', 'Garut', 'Gianyar', 'Gorontalo', 'Gresik', 'Indramayu', 
            'Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 
            'Jambi', 'Jember', 'Jepara', 'Karanganyar', 'Karawang', 'Kediri', 'Kendari', 'Klaten', 
            'Kudus', 'Kupang', 'Kuta', 'Lamongan', 'Lombok', 'Lumajang', 'Madiun', 'Magelang', 
            'Makassar', 'Malang', 'Manado', 'Mataram', 'Medan', 'Mojokerto', 'Nganjuk', 'Ngawi', 
            'Pacitan', 'Padang', 'Palangkaraya', 'Palembang', 'Palu', 'Pamekasan', 'Pandeglang', 
            'Pangandaran', 'Pekalongan', 'Pekanbaru', 'Pemalang', 'Penajam Paser', 'Pontianak', 
            'Probolinggo', 'Purbalingga', 'Purwakarta', 'Purworejo', 'Rembang', 'Salatiga', 'Samarinda', 
            'Semarang', 'Serang', 'Sidoarjo', 'Singkawang', 'Situbondo', 'Sleman', 'Solo (Surakarta)', 
            'Sragen', 'Subang', 'Sukabumi', 'Sukoharjo', 'Sumedang', 'Sumenep', 'Surabaya', 'Tangerang', 
            'Tangerang Selatan', 'Tarakan', 'Tasikmalaya', 'Tegal', 'Temanggung', 'Ternate', 'Tuban', 
            'Tulungagung', 'Ubud', 'Wonogiri', 'Wonosobo', 'Yogyakarta'
        ];

        return view('search', compact('listings', 'facilitiesList', 'cities', 'mapListings'));
    }

    public function detail($slug)
    {
        $listing = Listing::with(['images', 'owner', 'facilities'])->where('slug', $slug)->firstOrFail();
        
        // Anti-scam protection: restrict viewing suspended listings unless owner or admin
        if ($listing->status !== 'active') {
            $user = auth()->user();
            if (!$user || ($user->role !== 'admin' && $user->id !== $listing->owner_id)) {
                abort(404, 'Kos ini sedang ditangguhkan atau tidak aktif.');
            }
        }

        // Increment view count
        $listing->increment('views');

        // Run expire stale payments to release rooms dynamically
        $this->expireStalePayments();

        // Check if listing has no available rooms left
        $isBooked = ($listing->available_rooms ?? 0) <= 0;

        // Fetch related listings
        $related = Listing::active()
            ->where('id', '!=', $listing->id)
            ->where(function($q) use ($listing) {
                $q->where('city', $listing->city)
                  ->orWhere('gender_type', $listing->gender_type);
            })
            ->take(3)
            ->get();

        return view('detail', compact('listing', 'related', 'isBooked'));
    }

    public function favorites(Request $request)
    {
        $user = auth()->user();
        $favorites = Favorite::where('user_id', $user->id)
            ->with(['listing.images', 'listing.owner', 'listing.facilities'])
            ->latest()
            ->paginate(15);

        return view('favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request, Listing $listing)
    {
        $user = auth()->user();
        
        $favorite = Favorite::where('user_id', $user->id)->where('listing_id', $listing->id)->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
            $message = 'Kos berhasil dihapus dari favorit Anda.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'listing_id' => $listing->id,
            ]);
            $favorited = true;
            $message = 'Kos berhasil ditambahkan ke favorit Anda.';
        }

        if ($request->ajax()) {
            return response()->json([
                'favorited' => $favorited,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    public function submitReport(Request $request, Listing $listing)
    {
        $request->validate([
            'reason' => ['required', 'in:scam,fake_owner,fake_photos,harassment,wrong_location'],
            'description' => ['required', 'string', 'min:10'],
        ]);

        Report::create([
            'reporter_id' => auth()->id(),
            'listing_id' => $listing->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Auto-suspend system: if reported more than 5 times, auto-suspend listing for safety
        $pendingReportsCount = Report::where('listing_id', $listing->id)->where('status', 'pending')->count();
        if ($pendingReportsCount >= 5) {
            $listing->update(['status' => 'suspended']);
        }

        return back()->with('success', 'Laporan Anda telah berhasil dikirim. Admin akan segera meninjau listing ini demi keamanan bersama.');
    }

    public function checkout(Listing $listing)
    {
        // 1. Run expire stale payments dynamically
        $this->expireStalePayments();

        // 2. Proteksi Double-Booking: check if listing is already full
        if (($listing->available_rooms ?? 0) <= 0) {
            return redirect()->route('listings.show', $listing->slug)
                ->with('error', 'Mohon maaf, kamar kos ini sudah penuh / tidak tersedia.');
        }

        $userId = auth()->id();
        $userName = auth()->user()->name;

        // 3. Penangguhan Akun Permanen (10x failed/cancelled in total history)
        $totalFailures = \App\Models\Payment::where('user_id', $userId)
            ->whereIn('payment_status', ['failed', 'cancelled'])
            ->count();
        $maxTotal = (int) \App\Models\Setting::get('max_total_failures', 10);
        if ($totalFailures >= $maxTotal) {
            // Write to SecurityLog
            \App\Models\SecurityLog::firstOrCreate([
                'user_id' => $userId,
                'event_type' => 'permanent_suspension',
            ], [
                'description' => "Akun penyewa #{$userId} ({$userName}) ditangguhkan secara permanen karena mencapai {$totalFailures}x kegagalan/pembatalan transaksi.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'risk_level' => 'critical',
            ]);

            return redirect()->route('payments.history')
                ->with('error', "Akun Anda ditangguhkan secara permanen dari melakukan reservasi baru karena terdeteksi penyalahgunaan sistem (telah mencapai {$maxTotal}x kegagalan atau pembatalan transaksi sewa). Silakan hubungi Customer Support KosinAja.");
        }

        // 4. Sistem Penalti Blokir Sementara (Max 3 failed/cancelled in 24 hours)
        $recentFailures = \App\Models\Payment::where('user_id', $userId)
            ->whereIn('payment_status', ['failed', 'cancelled'])
            ->where('updated_at', '>=', now()->subHours(24))
            ->count();
        $maxDaily = (int) \App\Models\Setting::get('max_daily_failures', 3);
        if ($recentFailures >= $maxDaily) {
            // Write to SecurityLog
            \App\Models\SecurityLog::firstOrCreate([
                'user_id' => $userId,
                'event_type' => 'temporary_suspension',
                'description' => "Akun penyewa #{$userId} ({$userName}) terpicu pemblokiran sementara 24 jam karena {$recentFailures}x kegagalan/pembatalan transaksi dalam sehari.",
            ], [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'risk_level' => 'high',
            ]);

            return redirect()->route('payments.history')
                ->with('error', 'Akun Anda ditangguhkan sementara dari membuat reservasi baru selama 24 jam karena terdeteksi aktivitas pembatalan atau kegagalan transaksi berulang.');
        }

        // 5. Pembatasan Booking Aktif (Max 1 pending booking)
        // Clean up any stale pending transaction for this SAME listing if it's a Midtrans transaction (e.g. they closed the Midtrans Snap modal without paying)
        \App\Models\Payment::where('user_id', $userId)
            ->where('listing_id', $listing->id)
            ->where('payment_status', 'pending')
            ->where('transaction_id', 'not like', 'TRX-MANUAL-%')
            ->delete();

        $pendingCount = \App\Models\Payment::where('user_id', $userId)
            ->where('payment_status', 'pending')
            ->count();
        $maxPending = (int) \App\Models\Setting::get('max_pending_bookings', 1);
        if ($pendingCount >= $maxPending) {
            return redirect()->route('payments.history')
                ->with('warning', 'Anda masih memiliki transaksi pending yang belum diselesaikan. Harap selesaikan atau batalkan transaksi tersebut terlebih dahulu sebelum mengajukan booking baru.');
        }

        $orderId = 'TRX-' . time() . '-' . \Illuminate\Support\Str::random(5) . '-' . $listing->id;
        
        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $listing->price,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => [
                [
                    'id' => $listing->id,
                    'price' => (int) $listing->price,
                    'quantity' => 1,
                    'name' => \Illuminate\Support\Str::limit($listing->title, 50),
                ]
            ]
        ];

        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
        
        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                ->post("{$baseUrl}/snap/v1/transactions", $payload);

            if ($response->successful()) {
                $snapToken = $response->json()['token'];
                
                // Create pending transaction in DB
                \App\Models\Payment::create([
                    'user_id' => auth()->id(),
                    'listing_id' => $listing->id,
                    'amount' => $listing->price,
                    'payment_type' => 'Pending Midtrans Sandbox',
                    'payment_status' => 'pending',
                    'transaction_id' => $orderId,
                ]);

                return view('checkout', compact('listing', 'snapToken'));
            } else {
                $snapToken = null;
                $errorResponse = $response->json();
                $errorMessage = isset($errorResponse['error_messages'][0]) 
                    ? $errorResponse['error_messages'][0] 
                    : ($response->body() ?: 'Gagal terhubung ke Midtrans.');
                
                // Safe masking to protect API keys in logs/view
                $maskedServerKey = $serverKey ? (substr($serverKey, 0, 10) . '...' . substr($serverKey, -4)) : 'EMPTY';
                $clientKey = config('services.midtrans.client_key');
                $maskedClientKey = $clientKey ? (substr($clientKey, 0, 10) . '...' . substr($clientKey, -4)) : 'EMPTY';
                
                $debugInfo = " | DIAGNOSTIC -> (Server Key: {$maskedServerKey}, Client Key: {$maskedClientKey}, Production: " . ($isProduction ? 'true' : 'false') . ")";
                
                session()->flash('error', 'Koneksi Midtrans Gagal: ' . $errorMessage . $debugInfo);
                return view('checkout', compact('listing', 'snapToken'));
            }
        } catch (\Exception $e) {
            $snapToken = null;
            session()->flash('error', 'Kesalahan Sistem Midtrans: ' . $e->getMessage());
            return view('checkout', compact('listing', 'snapToken'));
        }
    }

    public function completePayment(Request $request, Listing $listing)
    {
        $request->validate([
            'transaction_id' => ['required', 'string'],
            'payment_type' => ['nullable', 'string'],
            'payment_status' => ['required', 'string', 'in:success,pending,failed'],
            'va_number' => ['nullable', 'string'],
        ]);

        $payment = \App\Models\Payment::where('transaction_id', $request->transaction_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($payment) {
            $oldStatus = $payment->payment_status;
            $payment->update([
                'payment_status' => $request->payment_status,
                'payment_type' => $request->payment_type ?? $payment->payment_type,
                'va_number' => $request->va_number ?? $payment->va_number,
            ]);

            if ($oldStatus !== 'success' && $request->payment_status === 'success') {
                $listing = $payment->listing;
                if ($listing && $listing->available_rooms > 0) {
                    $listing->decrement('available_rooms');
                }
            }
        } else {
            $payment = \App\Models\Payment::create([
                'user_id' => auth()->id(),
                'listing_id' => $listing->id,
                'amount' => $listing->price,
                'payment_type' => $request->payment_type ?? 'Midtrans Sandbox',
                'payment_status' => $request->payment_status,
                'transaction_id' => $request->transaction_id,
                'va_number' => $request->va_number,
            ]);

            if ($request->payment_status === 'success') {
                $listing = $payment->listing;
                if ($listing && $listing->available_rooms > 0) {
                    $listing->decrement('available_rooms');
                }
            }
        }

        $redirectUrl = $payment->payment_status === 'success' 
            ? route('payments.success', $payment->id) 
            : route('payments.checkout', $payment->listing_id);

        return response()->json([
            'success' => true,
            'redirect_url' => $redirectUrl
        ]);
    }

    public function processPayment(Request $request, Listing $listing)
    {
        $request->validate([
            'payment_method' => ['nullable', 'string', 'in:bank,wallet'],
            'payment_provider' => ['nullable', 'string', 'in:BCA,Mandiri,BRI,GoPay,OVO,DANA'],
            'payment_type' => ['required_without:payment_method', 'string'],
        ]);

        $paymentType = $request->payment_type;
        if ($request->filled('payment_method') && $request->filled('payment_provider')) {
            $paymentType = $request->payment_method === 'bank' 
                ? "Virtual Account (" . $request->payment_provider . ")" 
                : "E-Wallet (" . $request->payment_provider . ")";
        }

        $vaNumber = null;
        if ($request->payment_method === 'bank') {
            $provider = strtoupper($request->payment_provider);
            if ($provider === 'BCA') {
                $vaNumber = '3901' . '0812' . rand(1000, 9999) . rand(10, 99);
            } elseif ($provider === 'MANDIRI') {
                $vaNumber = '896' . '0812' . rand(1000, 9999) . rand(10, 99);
            } else { // BRI
                $vaNumber = '8079' . '0812' . rand(1000, 9999) . rand(10, 99);
            }
        }

        // Save as pending first so the user gets instructions!
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'listing_id' => $listing->id,
            'amount' => $listing->price, 
            'payment_type' => $paymentType,
            'payment_status' => 'pending', 
            'transaction_id' => 'TRX-MANUAL-' . time() . '-' . $listing->id,
            'va_number' => $vaNumber,
        ]);

        return redirect()->route('payments.instruction', $payment->id);
    }

    public function paymentInstruction(Payment $payment)
    {
        // Run expire stale payments dynamically
        $this->expireStalePayments();

        // Refresh and check if it has expired
        $payment->refresh();
        if ($payment->payment_status === 'failed') {
            return redirect()->route('payments.history')
                ->with('error', 'Transaksi ini telah kedaluwarsa karena batas waktu pembayaran habis.');
        }

        $payment->load(['listing.owner']);
        
        if ($payment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Auto-sync real Midtrans VA details if they are missing
        if (empty($payment->va_number) && !str_contains($payment->transaction_id, 'TRX-MANUAL-')) {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            
            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                    ->get("{$baseUrl}/v2/{$payment->transaction_id}/status");
                    
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Try to extract the VA number based on payment type
                    $vaNumber = null;
                    if (isset($data['va_numbers']) && count($data['va_numbers']) > 0) {
                        $vaNumber = $data['va_numbers'][0]['va_number'];
                    } elseif (isset($data['permata_va_number'])) {
                        $vaNumber = $data['permata_va_number'];
                    } elseif (isset($data['bill_key']) && isset($data['biller_code'])) {
                        $vaNumber = $data['biller_code'] . ' / ' . $data['bill_key'];
                    }
                    
                    if ($vaNumber) {
                        $payment->update([
                            'va_number' => $vaNumber,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Fail silently and log
                logger('Midtrans instruction sync failed: ' . $e->getMessage());
            }
        }

        return view('payment_instruction', compact('payment'));
    }

    public function confirmPaymentManual(Payment $payment)
    {
        if ($payment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Check if it is a real Midtrans transaction
        if (!str_contains($payment->transaction_id, 'TRX-MANUAL-')) {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            
            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                    ->get("{$baseUrl}/v2/{$payment->transaction_id}/status");
                    
                if ($response->successful()) {
                    $data = $response->json();
                    $transactionStatus = $data['transaction_status'] ?? '';
                    
                    if (in_array($transactionStatus, ['settlement', 'capture'])) {
                        $oldStatus = $payment->payment_status;
                        $payment->update([
                            'payment_status' => 'success',
                            'payment_type' => $data['payment_type'] ?? $payment->payment_type,
                        ]);
                        
                        if ($oldStatus !== 'success') {
                            $listing = $payment->listing;
                            if ($listing && $listing->available_rooms > 0) {
                                $listing->decrement('available_rooms');
                            }
                        }
                        
                        return redirect()->route('payments.success', $payment->id)
                            ->with('success', 'Pembayaran Anda berhasil diverifikasi oleh Midtrans!');
                    } elseif ($transactionStatus === 'pending') {
                        return back()->with('warning', 'Pembayaran Anda masih pending di Midtrans. Silakan selesaikan pembayaran di aplikasi Bank or E-Wallet Anda terlebih dahulu, kemudian klik konfirmasi kembali.');
                    } else {
                        $payment->update(['payment_status' => 'failed']);
                        return back()->with('error', "Pembayaran gagal atau kedaluwarsa. Status transaksi: {$transactionStatus}");
                    }
                } else {
                    return back()->with('warning', 'Status pembayaran belum terdaftar atau masih diproses oleh Midtrans. Silakan selesaikan pembayaran pada popup Midtrans Snap terlebih dahulu.');
                }
            } catch (\Exception $e) {
                return back()->with('warning', 'Gagal memverifikasi status ke Midtrans: ' . $e->getMessage());
            }
        } else {
            // This is a manual mock transaction!
            // Directly verify it successfully (since it's a mock)
            $oldStatus = $payment->payment_status;
            $payment->update([
                'payment_status' => 'success',
            ]);
            
            if ($oldStatus !== 'success') {
                $listing = $payment->listing;
                if ($listing && $listing->available_rooms > 0) {
                    $listing->decrement('available_rooms');
                }
            }
            
            return redirect()->route('payments.success', $payment->id)
                ->with('success', 'Pembayaran manual berhasil dikonfirmasi oleh sistem!');
        }
    }

    public function paymentSuccess(Payment $payment)
    {
        $payment->load(['listing.owner', 'user']);
        
        // Anti-scam protection: verify booking matches user
        if ($payment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('payment_success', compact('payment'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function paymentHistory(Request $request)
    {
        // Run expire stale payments dynamically
        $this->expireStalePayments();

        $payments = \App\Models\Payment::where('user_id', auth()->id())
            ->with(['listing.images', 'listing.owner'])
            ->latest()
            ->get();
            
        return view('payment_history', compact('payments'));
    }

    public function cancelBooking(Request $request, Payment $payment)
    {
        if ($payment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Only pending and success payments can be cancelled
        if (!in_array($payment->payment_status, ['pending', 'success'])) {
            return back()->with('error', 'Transaksi ini tidak dapat dibatalkan.');
        }

        // Calculate cancellation fee:
        // - Pending: 0.5% penalty (penyewa membatalkan)
        // - Success: 0% (kamar tidak sesuai = kesalahan owner)
        $isRoomDispute = $payment->payment_status === 'success';
        $cancellationFee = $isRoomDispute ? 0 : (int) round($payment->amount * 0.005);

        $oldStatus = $payment->payment_status;
        $payment->update([
            'payment_status' => 'cancelled',
            'cancellation_fee' => $cancellationFee,
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('reason', $isRoomDispute ? 'Kamar tidak sesuai saat survei' : 'Dibatalkan oleh penyewa'),
        ]);

        if ($oldStatus === 'success' || $oldStatus === 'completed') {
            $listing = $payment->listing;
            if ($listing) {
                $listing->increment('available_rooms');
            }
        }

        if ($isRoomDispute) {
            return redirect()->route('payments.history')
                ->with('success', 'Laporan ketidaksesuaian kamar berhasil dikirim. Tim KosinAja akan menghubungi Anda untuk proses refund.');
        }

        return redirect()->route('payments.history')
            ->with('warning', 'Booking berhasil dibatalkan. Denda pembatalan sebesar Rp ' . number_format($cancellationFee, 0, ',', '.') . ' akan ditagihkan kepada Anda.');
    }

    public function confirmCheckin(Request $request, Payment $payment)
    {
        if ($payment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Only success payments (paid & money held) can be check-in confirmed
        if ($payment->payment_status !== 'success') {
            return back()->with('error', 'Konfirmasi masuk hanya dapat dilakukan untuk transaksi yang sudah lunas.');
        }

        $payment->update([
            'payment_status' => 'completed',
            'checked_in_at' => now(),
        ]);

        return redirect()->route('payments.history')
            ->with('success', 'Konfirmasi berhasil! Terima kasih telah check-in. Dana sewa Anda akan segera kami salurkan kepada Pemilik Kos.');
    }

    /**
     * Auto-Expiry Engine: Automatically marks expired pending payments as failed
     */
    private function expireStalePayments()
    {
        // 1. Manual simulation transactions (older than 2 hours)
        \App\Models\Payment::where('payment_status', 'pending')
            ->where('transaction_id', 'like', 'TRX-MANUAL-%')
            ->where('created_at', '<', now()->subHours(2))
            ->update([
                'payment_status' => 'failed',
                'cancellation_reason' => 'Kedaluwarsa (Batas waktu pembayaran manual 2 jam habis)',
            ]);

        // 2. Midtrans transactions (older than 24 hours)
        \App\Models\Payment::where('payment_status', 'pending')
            ->where('transaction_id', 'not like', 'TRX-MANUAL-%')
            ->where('created_at', '<', now()->subHours(24))
            ->update([
                'payment_status' => 'failed',
                'cancellation_reason' => 'Kedaluwarsa (Batas waktu pembayaran Midtrans 24 jam habis)',
            ]);
    }

    /**
     * Follow Google Maps short URLs, resolve redirects, and extract GPS coordinates.
     */
    public function resolveMapsUrl(Request $request)
    {
        $url = $request->query('url');
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['success' => false, 'message' => 'URL tidak valid.']);
        }

        try {
            // Use cURL to follow redirects (maps.app.goo.gl -> full URL)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            $html = curl_exec($ch);
            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!$finalUrl || $httpCode === 0) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat mengakses URL tersebut.']);
            }

            $lat = null;
            $lng = null;

            // 1. Try @lat,lng pattern in final URL (most common for Google Maps)
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $finalUrl, $matches)) {
                $lat = $matches[1];
                $lng = $matches[2];
            }
            // 2. Try query=lat,lng or q=lat,lng in final URL
            elseif (preg_match('/[?&](q|query)=(-?\d+\.\d+),(-?\d+\.\d+)/', $finalUrl, $matches)) {
                $lat = $matches[2];
                $lng = $matches[3];
            }

            if ($lat && $lng) {
                return response()->json([
                    'success' => true,
                    'lat' => (float)$lat,
                    'lng' => (float)$lng,
                    'final_url' => $finalUrl
                ]);
            }

            // 3. Parse HTML body for coordinate patterns
            if ($html) {
                if (preg_match('/center=(-?\d+\.\d+)%2C(-?\d+\.\d+)/', $html, $matches)) {
                    $lat = $matches[1];
                    $lng = $matches[2];
                } elseif (preg_match('/"latitude":(-?\d+\.\d+),"longitude":(-?\d+\.\d+)/', $html, $matches)) {
                    $lat = $matches[1];
                    $lng = $matches[2];
                } elseif (preg_match('/meta\s+content="https:\/\/maps\.google\.com\/maps\/api\/staticmap\?center=(-?\d+\.\d+)%2C(-?\d+\.\d+)/', $html, $matches)) {
                    $lat = $matches[1];
                    $lng = $matches[2];
                }
            }

            if ($lat && $lng) {
                return response()->json([
                    'success' => true,
                    'lat' => (float)$lat,
                    'lng' => (float)$lng,
                    'final_url' => $finalUrl
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Koordinat tidak ditemukan dalam link ini. Coba gunakan koordinat langsung.',
                'final_url' => $finalUrl
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses link: ' . $e->getMessage()]);
        }
    }
}
