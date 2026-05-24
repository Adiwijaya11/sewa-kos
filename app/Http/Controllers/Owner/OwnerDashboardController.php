<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $owner = auth()->user();

        // Calculate statistics
        $listingsQuery = Listing::where('owner_id', $owner->id);
        $totalListings = (clone $listingsQuery)->count();
        $activeListings = (clone $listingsQuery)->active()->count();
        $totalViews = (clone $listingsQuery)->sum('views');
        
        $unreadChats = Chat::where('receiver_id', $owner->id)
            ->where('is_read', false)
            ->count();

        // Recent listings
        $recentListings = (clone $listingsQuery)->latest()->take(5)->get();

        return view('owner.dashboard', compact(
            'totalListings',
            'activeListings',
            'totalViews',
            'unreadChats',
            'recentListings'
        ));
    }

    public function payments(Request $request)
    {
        $owner = auth()->user();
        $search = $request->input('search');

        // Fetch payments made to owner's listings
        $payments = Payment::with(['user', 'listing'])
            ->whereHas('listing', function ($q) use ($owner) {
                $q->where('owner_id', $owner->id);
            })
            ->when($search, function ($query, $search) {
                return $query->whereDate('created_at', $search);
            })
            ->latest()
            ->get();

        return view('owner.payments', compact('payments', 'search'));
    }

    public function reports()
    {
        $owner = auth()->user();

        // Fetch reports against owner's listings
        $reports = Report::with(['reporter', 'listing'])
            ->whereHas('listing', function ($q) use ($owner) {
                $q->where('owner_id', $owner->id);
            })
            ->latest()
            ->paginate(10);

        return view('owner.reports', compact('reports'));
    }
}
