<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\OwnerVerification;
use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalListings = Listing::count();
        
        $pendingVerifications = OwnerVerification::where('status', 'pending')->count();
        $verifiedListings = Listing::where('is_verified', true)->count();
        $pendingReports = Report::where('status', 'pending')->count();
        
        // Sum successful and completed escrow payments
        $totalRevenue = Payment::whereIn('payment_status', ['success', 'completed'])->sum('amount');
        $platformFeePercent = (float) \App\Models\Setting::get('platform_commission_fee', 5);
        $platformCommission = $totalRevenue * ($platformFeePercent / 100);
        $ownerPayouts = $totalRevenue - $platformCommission;

        // Fetch recent verifications and reports
        $recentVerifications = OwnerVerification::with('user')->where('status', 'pending')->latest()->take(5)->get();
        $recentReports = Report::with(['reporter', 'listing'])->where('status', 'pending')->latest()->take(5)->get();

        // 1. Compute Monthly Revenue for the last 6 months (Line Chart)
        $monthsIndo = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        
        $revenueMonths = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $m = (int)$monthDate->format('n');
            $y = $monthDate->format('Y');
            $monthLabel = $monthsIndo[$m] . ' ' . $y;
            
            $startOfMonth = $monthDate->copy()->startOfMonth();
            $endOfMonth = $monthDate->copy()->endOfMonth();
            
            $monthlyRevenue = Payment::whereIn('payment_status', ['success', 'completed'])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');
                
            $revenueMonths[] = $monthLabel;
            $revenueData[] = (int) $monthlyRevenue;
        }

        // 2. Compute Kos Kategori Distribution (Pie Chart)
        $genderTypes = ['putra' => 'Putra', 'putri' => 'Putri', 'campur' => 'Campur'];
        $genderCounts = [];
        foreach ($genderTypes as $key => $label) {
            $genderCounts[$label] = Listing::where('gender_type', $key)->count();
        }

        // 3. Compute User Role Distribution (Doughnut Chart)
        $roleTypes = ['user' => 'Renter (Penyewa)', 'owner' => 'Owner (Pemilik)', 'admin' => 'Admin'];
        $roleCounts = [];
        foreach ($roleTypes as $key => $label) {
            $roleCounts[$label] = User::where('role', $key)->count();
        }

        // 4. Extra statistics
        $successfulPaymentsCount = Payment::whereIn('payment_status', ['success', 'completed'])->count();
        $verifiedUsersCount = User::where('is_verified', true)->count();
        
        $totalReports = Report::count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $reportResolutionRate = $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 100;

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalListings',
            'pendingVerifications',
            'verifiedListings',
            'pendingReports',
            'totalRevenue',
            'platformCommission',
            'ownerPayouts',
            'recentVerifications',
            'recentReports',
            'revenueMonths',
            'revenueData',
            'genderCounts',
            'roleCounts',
            'successfulPaymentsCount',
            'verifiedUsersCount',
            'reportResolutionRate'
        ));
    }

    public function listings(Request $request)
    {
        $query = Listing::with(['owner', 'images', 'facilities']);

        // 1. Search Query (listing title, city, province, owner name, owner email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhereHas('owner', function($oq) use ($search) {
                      $oq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter by status / verification status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($status === 'pending') {
                $query->where('is_verified', false);
            } elseif (in_array($status, ['active', 'inactive', 'suspended'])) {
                $query->where('status', $status);
            }
        }

        $listings = $query->latest()->paginate(30)->withQueryString();
        return view('admin.listings.index', compact('listings'));
    }


    public function verifyListing(Listing $listing)
    {
        $listing->update([
            'is_verified' => !$listing->is_verified
        ]);

        return back()->with('success', 'Status verifikasi kos berhasil diubah!');
    }

    public function deleteListing(Listing $listing)
    {
        $listing->delete();
        return back()->with('success', 'Listing kos berhasil dihapus secara permanen.');
    }

    public function reports()
    {
        $reports = Report::with(['reporter', 'listing.owner'])->latest()->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function resolveReport(Report $report)
    {
        $report->update(['status' => 'resolved']);
        
        // Anti-scam auto moderation: also suspend listing associated with resolved report
        $report->listing->update(['status' => 'suspended']);

        return back()->with('success', 'Laporan diselesaikan! Kos tersebut telah ditangguhkan secara otomatis demi keamanan.');
    }

    public function dismissReport(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        return back()->with('success', 'Laporan berhasil diabaikan.');
    }

    public function users(Request $request)
    {
        $query = User::query();

        // 1. Search Query (name, email, phone)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Role
        if ($request->filled('role') && $request->input('role') !== 'all') {
            $query->where('role', $request->input('role'));
        }

        // 3. Filter by Verification Status
        if ($request->filled('verification') && $request->input('verification') !== 'all') {
            $isVerified = $request->input('verification') === 'verified' ? 1 : 0;
            $query->where('is_verified', $isVerified);
        }

        // Fetch users with eager loaded listings count
        $users = $query->withCount('listings')
                       ->latest()
                       ->paginate(15)
                       ->withQueryString();

        // Dynamically compute transaction volume for each user
        foreach ($users as $user) {
            if ($user->role === 'owner') {
                // Owner: sum of successful payments made for any of their listings
                $user->financial_volume = Payment::where('payment_status', 'success')
                    ->whereIn('listing_id', function($q) use ($user) {
                        $q->select('id')->from('listings')->where('owner_id', $user->id);
                    })->sum('amount');
            } elseif ($user->role === 'user') {
                // Renter: sum of successful payments they made
                $user->financial_volume = Payment::where('payment_status', 'success')
                    ->where('user_id', $user->id)
                    ->sum('amount');
            } else {
                $user->financial_volume = 0;
            }
        }

        return view('admin.users.index', compact('users'));
    }

    public function suspendUser(User $user)
    {
        // Suspend owner/user: set verified flag to false and suspend all listings
        $user->update(['is_verified' => false]);
        
        Listing::where('owner_id', $user->id)->update(['status' => 'suspended']);

        // Create Security Log
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'listing_suspended',
            'description' => "Admin menangguhkan akun pengguna #{$user->id} ({$user->name}) dan membekukan semua listing kos miliknya.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => 'high'
        ]);

        return back()->with('success', 'Akun pengguna berhasil ditangguhkan dan semua listing miliknya dibekukan.');
    }

    public function activateUser(User $user)
    {
        $user->update(['is_verified' => true]);
        
        Listing::where('owner_id', $user->id)->update(['status' => 'active']);

        // Create Security Log
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'manual_audit',
            'description' => "Admin mengaktifkan kembali akun pengguna #{$user->id} ({$user->name}) dan memulihkan listing kos miliknya.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => 'medium'
        ]);

        return back()->with('success', 'Akun pengguna dan listing miliknya berhasil diaktifkan kembali.');
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'listing']);

        // 1. Search by Keyword (Transaction ID, Renter name/email, Owner name/email, Listing title)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('listing', function($lq) use ($search) {
                      $lq->where('title', 'like', "%{$search}%")
                         ->orWhereHas('owner', function($oq) use ($search) {
                             $oq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // 2. Filter by Date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // 3. Filter by Status Escrow
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('payment_status', $request->input('status'));
        }

        $payments = $query->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function securityIndex()
    {
        $blacklistedIps = \App\Models\IpBlacklist::with('bannedBy')->latest()->get();
        $securityLogs = \App\Models\SecurityLog::with('user')->latest()->take(100)->get();

        // Compute security statistics
        $totalBannedIps = $blacklistedIps->count();
        $totalSecurityEvents = \App\Models\SecurityLog::count();
        $criticalEventsCount = \App\Models\SecurityLog::where('risk_level', 'critical')->count();
        $riskStatus = $criticalEventsCount > 5 ? 'Risiko Tinggi' : ($criticalEventsCount > 0 ? 'Peringatan' : 'Aman');

        return view('admin.security.index', compact(
            'blacklistedIps',
            'securityLogs',
            'totalBannedIps',
            'totalSecurityEvents',
            'riskStatus'
        ));
    }

    public function blacklistIp(Request $request)
    {
        $request->validate([
            'ip_address' => ['required', 'ip'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $ip = $request->input('ip_address');

        // Prevent blocking server/admin's own IP to avoid lockout (simple safeguard)
        if ($ip === request()->ip()) {
            return back()->with('error', 'Anda tidak dapat memblokir alamat IP Anda sendiri!');
        }

        $banned = \App\Models\IpBlacklist::create([
            'ip_address' => $ip,
            'reason' => $request->input('reason') ?? 'Diblokir secara manual oleh administrator.',
            'banned_by' => auth()->id(),
        ]);

        // Log this security action
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'ip_banned',
            'description' => "Alamat IP {$ip} berhasil diblokir secara manual oleh Admin. Alasan: " . ($banned->reason),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => 'high'
        ]);

        return back()->with('success', "Alamat IP {$ip} berhasil diblokir!");
    }

    public function unblacklistIp($ip)
    {
        $blacklist = \App\Models\IpBlacklist::where('ip_address', $ip)->firstOrFail();
        $blacklist->delete();

        // Log this security action
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'ip_unbanned',
            'description' => "Blokir Alamat IP {$ip} dilepas secara manual oleh Admin.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => 'medium'
        ]);

        return back()->with('success', "Blokir Alamat IP {$ip} berhasil dilepas!");
    }

    public function settingsIndex()
    {
        // Fetch setting keys or use defaults
        $platformFee = \App\Models\Setting::get('platform_commission_fee', 5);
        $maxPending = \App\Models\Setting::get('max_pending_bookings', 1);
        $maxDailyFailures = \App\Models\Setting::get('max_daily_failures', 3);
        $maxTotalFailures = \App\Models\Setting::get('max_total_failures', 10);
        $sandboxMode = \App\Models\Setting::get('midtrans_sandbox_mode', 1);

        return view('admin.settings.index', compact(
            'platformFee',
            'maxPending',
            'maxDailyFailures',
            'maxTotalFailures',
            'sandboxMode'
        ));
    }

    public function attributesIndex()
    {
        $facilities = \App\Models\Facility::latest()->get();
        $genderTypes = \App\Models\Setting::getGenderTypes();

        return view('admin.settings.attributes', compact('facilities', 'genderTypes'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'platform_commission_fee' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_pending_bookings' => ['required', 'integer', 'min:1', 'max:50'],
            'max_daily_failures' => ['required', 'integer', 'min:1', 'max:50'],
            'max_total_failures' => ['required', 'integer', 'min:1', 'max:100'],
            'midtrans_sandbox_mode' => ['required', 'boolean'],
        ]);

        \App\Models\Setting::set('platform_commission_fee', $request->input('platform_commission_fee'));
        \App\Models\Setting::set('max_pending_bookings', $request->input('max_pending_bookings'));
        \App\Models\Setting::set('max_daily_failures', $request->input('max_daily_failures'));
        \App\Models\Setting::set('max_total_failures', $request->input('max_total_failures'));
        \App\Models\Setting::set('midtrans_sandbox_mode', $request->input('midtrans_sandbox_mode'));

        // Log this action inside security logs!
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'manual_audit',
            'description' => "Admin memperbarui kebijakan aturan platform dan pengaturan sistem.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => 'high'
        ]);

        return back()->with('success', 'Pengaturan kebijakan sistem berhasil disimpan!');
    }

    public function storeFacility(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:facilities,name'],
            'icon' => ['required', 'string', 'max:50'],
        ]);

        \App\Models\Facility::create([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return back()->with('success', "Fasilitas baru '{$request->name}' berhasil ditambahkan!");
    }

    public function destroyFacility(\App\Models\Facility $facility)
    {
        $name = $facility->name;
        $facility->delete();
        return back()->with('success', "Fasilitas '{$name}' berhasil dihapus!");
    }

    public function storeGenderType(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $name = strtolower(trim($request->name));
        \App\Models\Setting::addGenderType($name);

        return back()->with('success', "Tipe penghuni baru '{$request->name}' berhasil ditambahkan!");
    }

    public function destroyGenderType(string $type)
    {
        \App\Models\Setting::removeGenderType($type);
        return back()->with('success', "Tipe penghuni '{$type}' berhasil dihapus!");
    }

    public function tracking(Request $request)
    {
        $query = $request->input('q');
        $payment = null;
        $notFound = false;

        if ($query) {
            $payment = Payment::with(['user', 'listing.owner'])
                ->where('transaction_id', $query)
                ->first();

            if (!$payment) {
                $notFound = true;
            }
        }

        return view('admin.tracking.index', compact('payment', 'query', 'notFound'));
    }

    public function earnings()
    {
        $commissionRate = (float) \App\Models\Setting::get('platform_commission_fee', 2);

        // Summary totals
        $totalSuccess   = Payment::whereIn('payment_status', ['success', 'completed'])->sum('amount');
        $totalCompleted = Payment::where('payment_status', 'completed')->sum('amount');
        $totalPending   = Payment::where('payment_status', 'pending')->sum('amount');
        $totalCancelled = Payment::whereIn('payment_status', ['cancelled', 'failed'])->count();
        $totalTrx       = Payment::count();
        $platformEarned = $totalCompleted * ($commissionRate / 100);
        $ownerPayouts   = $totalCompleted - $platformEarned;

        // Monthly breakdown (last 12 months)
        $monthsIndo = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                       7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $m = (int)$d->format('n');
            $months[] = [
                'label'     => $monthsIndo[$m] . ' ' . $d->format('Y'),
                'short'     => $monthsIndo[$m],
                'amount'    => Payment::whereIn('payment_status', ['success', 'completed'])
                    ->whereYear('created_at', $d->year)->whereMonth('created_at', $m)->sum('amount'),
                'trx_count' => Payment::whereIn('payment_status', ['success', 'completed'])
                    ->whereYear('created_at', $d->year)->whereMonth('created_at', $m)->count(),
            ];
        }

        // Top earning listings
        $topListings = Payment::with('listing.owner')
            ->whereIn('payment_status', ['success', 'completed'])
            ->selectRaw('listing_id, SUM(amount) as total_income, COUNT(*) as trx_count')
            ->groupBy('listing_id')
            ->orderByDesc('total_income')
            ->take(10)
            ->get();

        // Status breakdown
        $statusBreakdown = [
            ['label' => 'Dana Dicairkan',    'status' => 'completed', 'color' => 'emerald'],
            ['label' => 'Dana Ditahan',      'status' => 'success',   'color' => 'teal'],
            ['label' => 'Menunggu Transfer', 'status' => 'pending',   'color' => 'amber'],
            ['label' => 'Dibatalkan',        'status' => 'cancelled', 'color' => 'slate'],
            ['label' => 'Gagal',             'status' => 'failed',    'color' => 'red'],
        ];
        foreach ($statusBreakdown as &$s) {
            $s['count']  = Payment::where('payment_status', $s['status'])->count();
            $s['amount'] = Payment::where('payment_status', $s['status'])->sum('amount');
        }

        // Recent completed payments
        $recentPayments = Payment::with(['user', 'listing'])
            ->whereIn('payment_status', ['success', 'completed'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.earnings.index', compact(
            'commissionRate', 'totalSuccess', 'totalCompleted', 'totalPending',
            'totalCancelled', 'totalTrx', 'platformEarned', 'ownerPayouts',
            'months', 'topListings', 'statusBreakdown', 'recentPayments'
        ));
    }
}
