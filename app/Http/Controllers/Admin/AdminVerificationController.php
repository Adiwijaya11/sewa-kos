<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\OwnerVerification;
use Illuminate\Http\Request;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $verifications = OwnerVerification::with('user')->latest()->paginate(10);
        return view('admin.verifications.index', compact('verifications'));
    }

    public function approve(OwnerVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'verified_at' => now(),
            'notes' => 'Identitas terverifikasi oleh Admin.'
        ]);

        // Mark user as verified
        $verification->user->update([
            'is_verified' => true
        ]);

        // Auto-verify all existing listings for this owner to fast-track quality kos
        Listing::where('owner_id', $verification->user_id)->update([
            'is_verified' => true
        ]);

        return back()->with('success', 'Verifikasi identitas owner berhasil disetujui! Lencana Verified telah disematkan pada owner dan semua kos miliknya.');
    }

    public function reject(Request $request, OwnerVerification $verification)
    {
        $request->validate([
            'notes' => ['required', 'string', 'min:5'],
        ]);

        $verification->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'verified_at' => null,
        ]);

        // Demote user verification status
        $verification->user->update([
            'is_verified' => false
        ]);

        // Revoke listing verification
        Listing::where('owner_id', $verification->user_id)->update([
            'is_verified' => false
        ]);

        return back()->with('warning', 'Pengajuan verifikasi ditolak dengan catatan: ' . $request->notes);
    }
}
