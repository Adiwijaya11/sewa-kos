<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OwnerListingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $listings = Listing::where('owner_id', auth()->id())
            ->with(['images', 'facilities'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('city', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();

        return view('owner.listings.index', compact('listings', 'search'));
    }

    public function create()
    {
        if (!auth()->user()->is_verified) {
            return redirect()->route('owner.verification.index')
                ->with('warning', 'Anda wajib memverifikasi akun Anda (KTP & Selfie) terlebih dahulu sebelum dapat menambahkan kos baru.');
        }

        $facilities = Facility::all();
        return view('owner.listings.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_verified) {
            return redirect()->route('owner.verification.index')
                ->with('warning', 'Anda wajib memverifikasi akun Anda (KTP & Selfie) terlebih dahulu sebelum dapat menambahkan kos baru.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:10000'],
            'gender_type' => ['required', 'in:' . implode(',', \App\Models\Setting::getGenderTypes())],
            'room_size' => ['required', 'string', 'max:50'],
            'max_people' => ['required', 'integer', 'min:1', 'max:50'],
            'total_rooms' => ['required', 'integer', 'min:1', 'max:1000'],
            'available_rooms' => ['required', 'integer', 'min:0', 'lte:total_rooms'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'near_campus' => ['nullable', 'string', 'max:255'],
            'near_mall' => ['nullable', 'string', 'max:255'],
            'near_hospital' => ['nullable', 'string', 'max:255'],
            'near_station' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'facilities' => ['required', 'array'],
            'facilities.*' => ['exists:facilities,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:3072'], // Max 3MB
        ]);

        $owner = auth()->user();

        // Anti-scam: Listings are NOT verified by default. Must wait for admin or verified owner status
        $isVerified = $owner->is_verified;

        $slug = Str::slug($request->title) . '-' . Str::lower(Str::random(5));

        $listing = Listing::create([
            'owner_id' => $owner->id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'gender_type' => $request->gender_type,
            'room_size' => $request->room_size,
            'max_people' => $request->max_people,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'near_campus' => $request->near_campus,
            'near_mall' => $request->near_mall,
            'near_hospital' => $request->near_hospital,
            'near_station' => $request->near_station,
            'is_verified' => $isVerified,
            'status' => 'active',
            'views' => 0,
        ]);

        // Attach facilities
        $listing->facilities()->sync($request->facilities);

        // Upload images
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/listings');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = 'listing_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image' => 'uploads/listings/' . $filename,
                ]);
            }
        }

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing kos baru Anda berhasil dibuat!' . (!$isVerified ? ' Kos akan segera ditinjau oleh Admin agar mendapatkan lencana Verified.' : ''));
    }

    public function edit(Listing $listing)
    {
        // Guard listing access
        if ($listing->owner_id !== auth()->id()) {
            abort(403);
        }

        $facilities = Facility::all();
        $listing->load(['facilities', 'images']);

        return view('owner.listings.edit', compact('listing', 'facilities'));
    }

    public function update(Request $request, Listing $listing)
    {
        // Guard listing access
        if ($listing->owner_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:10000'],
            'gender_type' => ['required', 'in:' . implode(',', \App\Models\Setting::getGenderTypes())],
            'room_size' => ['required', 'string', 'max:50'],
            'max_people' => ['required', 'integer', 'min:1', 'max:50'],
            'total_rooms' => ['required', 'integer', 'min:1', 'max:1000'],
            'available_rooms' => ['required', 'integer', 'min:0', 'lte:total_rooms'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'near_campus' => ['nullable', 'string', 'max:255'],
            'near_mall' => ['nullable', 'string', 'max:255'],
            'near_hospital' => ['nullable', 'string', 'max:255'],
            'near_station' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'facilities' => ['required', 'array'],
            'facilities.*' => ['exists:facilities,id'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        // If title changed, update slug
        if ($listing->title !== $request->title) {
            $slug = Str::slug($request->title) . '-' . Str::lower(Str::random(5));
            $listing->slug = $slug;
        }

        $listing->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'gender_type' => $request->gender_type,
            'room_size' => $request->room_size,
            'max_people' => $request->max_people,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'near_campus' => $request->near_campus,
            'near_mall' => $request->near_mall,
            'near_hospital' => $request->near_hospital,
            'near_station' => $request->near_station,
        ]);

        // Sync facilities
        $listing->facilities()->sync($request->facilities);

        // Upload additional images if any
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/listings');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = 'listing_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image' => 'uploads/listings/' . $filename,
                ]);
            }
        }

        return redirect()->route('owner.listings.index')
            ->with('success', 'Kos Anda berhasil diperbarui!');
    }

    public function destroy(Listing $listing)
    {
        // Guard listing access
        if ($listing->owner_id !== auth()->id()) {
            abort(403);
        }

        // Delete physical files
        foreach ($listing->images as $img) {
            $path = public_path($img->image);
            if (File::exists($path)) {
                File::delete($path);
            }
            $img->delete();
        }

        $listing->delete();

        return redirect()->route('owner.listings.index')
            ->with('success', 'Kos Anda berhasil dihapus.');
    }

    public function toggleStatus(Listing $listing)
    {
        // Guard listing access
        if ($listing->owner_id !== auth()->id()) {
            abort(403);
        }

        // Suspended listings cannot be activated by owner
        if ($listing->status === 'suspended') {
            return back()->with('error', 'Kos ini sedang ditangguhkan oleh Admin dan tidak dapat diubah statusnya.');
        }

        // Toggle between active and inactive
        $newStatus = $listing->status === 'active' ? 'inactive' : 'active';
        $listing->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Properti kos Anda berhasil diaktifkan (Tersedia / Kosong) dan sekarang muncul kembali di pencarian!' 
            : 'Properti kos Anda berhasil dinonaktifkan (Penuh / Terisi) dan disembunyikan dari pencarian.';

        return back()->with('success', $message);
    }
}
