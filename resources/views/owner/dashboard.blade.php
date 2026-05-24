@extends('layouts.dashboard')

@section('title', 'Dashboard Owner - KosinAja')
@section('header_title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-tr from-slate-900 to-emerald-950 p-6 text-white shadow-md">
        <!-- Back-glow effect -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-emerald-500/10 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 mb-2">
                    <i class="fa-solid fa-house-chimney-user mr-1.5"></i>Mitra Renter Resmi
                </span>
                <h2 class="text-xl font-bold tracking-tight">Selamat Datang Kembali, {{ auth()->user()->name }}!</h2>
                <p class="text-xs text-slate-400 mt-1 max-w-xl">
                    Kelola kos Anda dengan mudah. Pastikan kos Anda memiliki lencana <span class="text-emerald-400 font-semibold">Verified</span> untuk melipatgandakan minat penyewa hingga 5x lipat.
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                @if(!auth()->user()->is_verified)
                    <a href="{{ route('owner.verification.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 text-xs font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>Verifikasi KTP Anda
                    </a>
                @else
                    <div class="px-4 py-2 bg-emerald-500 text-slate-900 text-xs font-bold rounded-xl flex items-center shadow-md shadow-emerald-500/10">
                        <i class="fa-solid fa-circle-check mr-1.5"></i>Akun Terverifikasi
                    </div>
                @endif
                
                <a href="{{ route('owner.listings.create') }}" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold rounded-xl transition-all flex items-center shadow-md">
                    <i class="fa-solid fa-circle-plus mr-1.5 text-emerald-600"></i>Tambah Kos Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard Counter Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Widget 1: Total Listing -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block">Total Properti</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalListings }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Semua tipe kamar kos</span>
            </div>
        </div>
        
        <!-- Widget 2: Active Listing -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block">Aktif Tayang</span>
                <span class="text-2xl font-bold text-slate-800">{{ $activeListings }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Siap disewa pengguna</span>
            </div>
        </div>
        
        <!-- Widget 3: Total Views -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-regular fa-eye"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block">Total Dilihat</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalViews }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Pengunjung mencari kos</span>
            </div>
        </div>
        
        <!-- Widget 4: Unread Chats -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-regular fa-comment-dots"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide block">Chat Belum Dibaca</span>
                <span class="text-2xl font-bold text-slate-800">{{ $unreadChats }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Butuh respon segera</span>
            </div>
        </div>
        
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Recent Listings Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Kos Yang Baru Ditambahkan</h3>
                    <p class="text-[11px] text-slate-400">Daftar kos terakhir yang Anda tayangkan</p>
                </div>
                <a href="{{ route('owner.listings.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/30">
                            <th class="py-3 px-5">Kos</th>
                            <th class="py-3 px-5">Tipe</th>
                            <th class="py-3 px-5">Harga Bulanan</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs">
                        @forelse($recentListings as $listing)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                            @if($listing->images->isNotEmpty())
                                                <img src="{{ asset($listing->images->first()->image) }}" alt="Preview" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-image"></i></div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-700 truncate max-w-[180px]">{{ $listing->title }}</p>
                                            <p class="text-[10px] text-slate-400 truncate max-w-[180px]"><i class="fa-solid fa-location-dot mr-1"></i>{{ $listing->city }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide {{ $listing->gender_type === 'putra' ? 'bg-sky-50 text-sky-700' : ($listing->gender_type === 'putri' ? 'bg-pink-50 text-pink-700' : 'bg-purple-50 text-purple-700') }}">
                                        {{ $listing->gender_type }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 font-bold text-slate-700">
                                    Rp {{ number_format($listing->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-5">
                                    <div class="flex items-center space-x-1.5">
                                        @if($listing->is_verified)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500" title="Verified"></span>
                                            <span class="text-[10px] font-semibold text-emerald-600">Verified</span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-300" title="Pending"></span>
                                            <span class="text-[10px] font-semibold text-slate-400">Review</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-5 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" class="w-7 h-7 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors" title="Lihat detail">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                        <a href="{{ route('owner.listings.edit', $listing->id) }}" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    <p class="font-medium">Belum ada listing kos tayang</p>
                                    <a href="{{ route('owner.listings.create') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold underline mt-1 block">Pasang Kos Sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Tip & Security Guide Card -->
        <div class="space-y-5">
            <!-- Verify Guide -->
            @if(!auth()->user()->is_verified)
                <div class="bg-gradient-to-tr from-amber-500/10 to-amber-600/5 rounded-2xl border border-amber-200/50 p-5 shadow-sm">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-900 flex items-center justify-center text-base flex-shrink-0 shadow-md shadow-amber-500/10">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Akun Belum Terverifikasi</h4>
                            <p class="text-xs text-slate-500 leading-relaxed mt-1">
                                Demi keamanan, kos yang Anda unggah hanya akan berstatus <span class="font-semibold">Review</span> dan tidak mendapat lencana Verified sebelum identitas KTP Anda dikonfirmasi oleh Admin kami.
                            </p>
                            <a href="{{ route('owner.verification.index') }}" class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-800 mt-2.5 bg-amber-500/10 px-3 py-1.5 rounded-lg border border-amber-200">
                                Lengkapi Dokumen KTP <i class="fa-solid fa-chevron-right ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Anti-Scam Security Escrow Tips -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <h4 class="font-bold text-slate-800 text-sm flex items-center mb-3">
                    <i class="fa-solid fa-lock text-emerald-500 mr-2"></i>Panduan Keamanan Escrow
                </h4>
                <ul class="space-y-3 text-[11px] text-slate-500 leading-relaxed">
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                        <span>Gunakan sistem <span class="font-semibold text-slate-700">KosinAja Escrow (Rekening Bersama)</span> untuk menampung dana sewa sampai penyewa melakukan check-in demi keamanan transaksi Anda.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                        <span>Jangan pernah meminta transfer langsung atau DP ke rekening pribadi di luar sistem KosinAja demi menghindari kesalahpahaman.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 mr-2 flex-shrink-0"></i>
                        <span>Semua chat dan kesepakatan tertulis di dalam platform KosinAja diakui secara sah jika terjadi komplain atau sengketa.</span>
                    </li>
                </ul>
            </div>
            
            <!-- Quick Link Cards -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('chats.index') }}" class="p-4 rounded-xl border border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 text-center shadow-sm transition-all group block">
                    <i class="fa-regular fa-comments text-xl text-emerald-500 mb-1 group-hover:scale-110 transition-transform block"></i>
                    <span class="text-xs font-bold text-slate-700 block">Obrolan Penyewa</span>
                </a>
                <a href="{{ route('owner.payments') }}" class="p-4 rounded-xl border border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 text-center shadow-sm transition-all group block">
                    <i class="fa-solid fa-wallet text-xl text-emerald-500 mb-1 group-hover:scale-110 transition-transform block"></i>
                    <span class="text-xs font-bold text-slate-700 block">Log Pendapatan</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
