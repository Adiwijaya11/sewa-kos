@extends('layouts.app')
@section('title', 'KosinAja - Hunian Kos Modern, Aman & Terpercaya')

@section('content')
<!-- 1. HERO SECTION -->
<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0px) rotate(0.5deg); }
        50% { transform: translateY(-10px) rotate(-0.5deg); }
    }
    @keyframes float-medium {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-14px) rotate(1deg); }
    }
    @keyframes float-fast {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(-1.5deg); }
    }
    .animate-float-slow {
        animation: float-slow 7s ease-in-out infinite;
    }
    .animate-float-medium {
        animation: float-medium 6s ease-in-out infinite;
    }
    .animate-float-fast {
        animation: float-fast 5s ease-in-out infinite;
    }
</style>

<div class="relative bg-gradient-to-b from-slate-50 via-white to-white overflow-hidden pt-8 pb-16 sm:pt-10 sm:pb-20 lg:pt-12 lg:pb-24 border-b border-slate-100">
    <!-- Grid Dot Pattern and Glowing Blob Backgrounds -->
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:32px_32px] opacity-25 -z-10"></div>
    <div class="absolute -top-10 right-0 w-[600px] h-[600px] bg-gradient-to-tr from-emerald-500/10 via-teal-400/10 to-transparent rounded-full blur-3xl opacity-85 -z-10 animate-pulse" style="animation-duration: 8s"></div>
    <div class="absolute -left-20 top-20 w-[450px] h-[450px] bg-gradient-to-br from-blue-500/5 to-transparent rounded-full blur-3xl opacity-60 -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- LEFT COLUMN: Content & Form (lg:col-span-7) -->
            <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                <!-- Trust Badge -->
                <div class="inline-flex items-center space-x-2 bg-emerald-50 text-emerald-800 px-4 py-2 rounded-full text-xs font-bold border border-emerald-200/60 shadow-sm shadow-emerald-50">
                    <i class="fa-solid fa-shield-halved text-emerald-600 animate-pulse"></i>
                    <span>Jaminan Keamanan Rekening Bersama & Jaminan Anti-Scam</span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Temukan Kos Impian <br class="hidden sm:inline" />
                    Secara <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Aman & Praktis</span>
                </h1>

                <!-- Description -->
                <p class="text-sm sm:text-base text-slate-500 max-w-xl leading-relaxed">
                    Sewa kos modern dengan KTP owner terverifikasi resmi, foto properti 100% akurat sesuai survei, dan perlindungan penuh menggunakan transaksi rekening bersama (escrow system).
                </p>

                <!-- QUICK SEARCH BAR -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 max-w-xl relative">
                    <form action="{{ route('search') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-3">
                        <div class="flex-grow">
                            <label for="search" class="sr-only">Cari Lokasi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                                </span>
                                <input type="text" name="search" id="search"
                                    class="block w-full pl-10 pr-3 py-3.5 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-slate-400 font-medium"
                                    placeholder="Cari kota, jalan, atau nama kos...">
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit"
                                class="w-full sm:w-auto px-7 py-3.5 border border-transparent text-sm font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all duration-300 cursor-pointer">
                                Cari Sekarang
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Stats Counters -->
                <div class="grid grid-cols-3 gap-2 sm:flex sm:items-center sm:space-x-8 pt-4">
                    <div class="hover:scale-105 transition-transform duration-300 text-center sm:text-left">
                        <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalVerifiedKos }}</p>
                        <p class="text-[9px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider leading-none">Kos Verified</p>
                    </div>
                    <div class="hidden sm:block w-px h-10 bg-slate-200"></div>
                    <div class="hover:scale-105 transition-transform duration-300 text-center sm:text-left">
                        <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalPencariKos }}</p>
                        <p class="text-[9px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider leading-none">Pencari Aktif</p>
                    </div>
                    <div class="hidden sm:block w-px h-10 bg-slate-200"></div>
                    <div class="hover:scale-105 transition-transform duration-300 text-center sm:text-left">
                        <p class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight flex items-center justify-center sm:justify-start">
                            {{ $safetyRate }}% <i class="fa-solid fa-shield text-[10px] sm:text-xs ml-1 text-emerald-500"></i>
                        </p>
                        <p class="text-[9px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider leading-none">Keamanan</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Premium Layered Visual Composition (lg:col-span-5) -->
            <div class="lg:col-span-5 relative w-full flex justify-center items-center mt-12 lg:mt-0">
                <!-- Glowing Circle blobs behind image -->
                <div class="absolute -right-4 -top-8 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-8 -bottom-8 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl"></div>
                
                <!-- Graphic dot card grid mockup -->
                <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px] opacity-[0.06] -z-10 rounded-3xl"></div>

                <!-- MAIN IMAGE: Modern Premium Apartment Space -->
                <div class="relative w-[85%] z-20 flex justify-end">
                    <img class="w-full aspect-[4/5] object-cover rounded-[2.5rem] border-8 border-white shadow-[0_25px_60px_-15px_rgba(15,23,42,0.15)] hover:scale-[1.02] transition-transform duration-500" 
                         src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80" 
                         alt="Modern Cosy Living Room">
                </div>

                <!-- SECONDARY IMAGE: Cozy Room Interior Details (Overlapping Offset) -->
                <div class="absolute -left-4 bottom-4 z-30 w-44 h-44 rounded-3xl border-4 border-white shadow-[0_20px_40px_-10px_rgba(15,23,42,0.12)] hidden sm:block hover:scale-105 transition-transform duration-500 overflow-hidden">
                    <img class="w-full h-full object-cover" 
                         src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=400&q=80" 
                         alt="Cosy Desk Detail">
                </div>

                <!-- FLOATING BADGE 1: Trust Audit Lulus -->
                <div class="absolute -top-4 left-2 sm:left-6 z-40 animate-float-slow bg-white/95 backdrop-blur-md border border-slate-100 px-4 py-2.5 rounded-2xl shadow-[0_15px_30px_-5px_rgba(15,23,42,0.08)] flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Owner Verified</p>
                        <p class="text-xs font-bold text-slate-800">KTP & Selfie Lolos Audit</p>
                    </div>
                </div>

                <!-- FLOATING BADGE 2: Safe Rekber Deposit -->
                <div class="absolute -bottom-2 right-2 sm:right-12 z-40 animate-float-medium bg-white/95 backdrop-blur-md border border-slate-100 px-4 py-2.5 rounded-2xl shadow-[0_15px_30px_-5px_rgba(15,23,42,0.08)] flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                        <i class="fa-solid fa-lock text-xs animate-bounce" style="animation-duration: 3s"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Transaksi Aman</p>
                        <p class="text-xs font-bold text-slate-800">Sistem Escrow Aktif</p>
                    </div>
                </div>

                <!-- FLOATING BADGE 3: Highly Rated with Avatar Stack -->
                <div class="absolute top-16 -right-2 sm:-right-6 z-40 animate-float-fast bg-white/95 backdrop-blur-md border border-slate-100 p-3 rounded-2xl shadow-[0_15px_30px_-5px_rgba(15,23,42,0.08)] space-y-1.5 flex flex-col items-center hidden sm:flex">
                    <div class="flex items-center space-x-1">
                        <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                        <span class="text-xs font-black text-slate-800">4.9/5</span>
                        <span class="text-[9px] font-semibold text-slate-400">(1.2k+)</span>
                    </div>
                    
                    <!-- Avatar bubble stack -->
                    <div class="flex -space-x-1.5 overflow-hidden">
                        <img class="inline-block h-5 w-5 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="User 1">
                        <img class="inline-block h-5 w-5 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="User 2">
                        <img class="inline-block h-5 w-5 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80" alt="User 3">
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>

<!-- 3. POPULAR CITIES GRID -->
<div class="bg-slate-50 py-16 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12 space-y-2">
            <div class="inline-flex items-center space-x-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-[10px] font-bold border border-emerald-100 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Destinasi Terpopuler</span>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Jelajahi Kota Populer</h2>
            <p class="text-xs text-slate-500">Temukan kos premium di dekat kampus & area perkantoran kota tujuan Anda dengan cepat.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $popularCities = [
                    ['name' => 'Yogyakarta', 'listings' => $jogjaCount . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Bandung', 'listings' => $bandungCount . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Jakarta Selatan', 'listings' => $jakartaCount . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Surabaya', 'listings' => $surabayaCount . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=400&q=80'],
                ];
            @endphp

            @foreach($popularCities as $city)
                <a href="{{ route('search', ['city' => $city['name']]) }}" class="relative group h-48 rounded-[1.8rem] overflow-hidden shadow-md shadow-slate-100 border border-slate-100 block transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-emerald-950/5">
                    <!-- Zooming background image -->
                    <img src="{{ $city['bg'] }}" alt="{{ $city['name'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <!-- Overlay dark gradient -->
                    <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/45 transition-colors duration-500"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/30 to-transparent flex flex-col justify-end p-4">
                        
                        <!-- Premium Frosted Glassmorphism Capsule -->
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-3.5 rounded-2xl transition-all duration-300 group-hover:bg-white/15 group-hover:border-white/30 shadow-lg">
                            <h3 class="text-sm font-extrabold text-white tracking-tight flex items-center justify-between">
                                <span>{{ $city['name'] }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-emerald-400 opacity-0 -translate-x-1 translate-y-1 group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 transition-all duration-300"></i>
                            </h3>
                            <p class="text-[9px] font-bold text-emerald-400 mt-1 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>{{ $city['listings'] }}</span>
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- 4. FEATURED VERIFIED LISTINGS -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <div class="inline-flex items-center space-x-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold border border-emerald-200/50 mb-2">
                    <i class="fa-solid fa-square-check"></i>
                    <span>Prioritas Mutu & Kepercayaan</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900">Kos Pilihan ✅ Verified</h2>
                <p class="text-xs text-slate-500">Pemilik lolos seleksi KTP & Video Verifikasi, kos jaminan bebas penipuan.</p>
            </div>
            <a href="{{ route('search', ['sort' => 'popular']) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center transition-colors">
                Lihat Semua <i class="fa-solid fa-arrow-right-long ml-1.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($featured as $listing)
                <!-- Kos Card -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-lg shadow-slate-200/40 group flex flex-col h-full hover:shadow-xl hover:border-slate-200/80 transition-all duration-300">
                    <!-- Image Area -->
                    <div class="relative h-48 sm:h-52 overflow-hidden bg-slate-100">
                        @if($listing->images->count() > 0)
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                 src="{{ asset($listing->images->first()->image) }}" 
                                 alt="{{ $listing->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="fa-regular fa-image text-3xl"></i>
                            </div>
                        @endif
                        
                        <!-- Gender Type Badge -->
                        <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm {{ $listing->gender_type === 'putra' ? 'bg-blue-600 text-white' : ($listing->gender_type === 'putri' ? 'bg-pink-600 text-white' : 'bg-purple-600 text-white') }}">
                            Kos {{ $listing->gender_type }}
                        </span>

                        <!-- Verified Badge -->
                        @if($listing->is_verified)
                            <span class="absolute top-4 right-4 bg-emerald-500 text-slate-900 text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-sm flex items-center space-x-1">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>VERIFIED</span>
                            </span>
                        @endif
                    </div>

                    <!-- Detail Area -->
                    <div class="p-5 flex flex-col flex-grow space-y-3">
                        <div class="text-xs text-slate-400 flex items-center">
                            <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $listing->city }}, {{ $listing->province }}
                        </div>
                        
                        <h3 class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 min-h-[40px]">
                            <a href="{{ route('listings.show', $listing->slug) }}" class="hover:text-emerald-600 transition-colors">
                                {{ $listing->title }}
                            </a>
                        </h3>

                        <!-- Price / Month -->
                        <div class="flex justify-between items-baseline pt-2">
                            <span class="text-xs text-slate-400">Mulai dari</span>
                            <span class="text-base font-black text-emerald-600">Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="text-xs font-semibold text-slate-500">/bln</span></span>
                        </div>

                        <!-- Action Button inside Card -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                    {{ Str::upper(Str::substr($listing->owner->name, 0, 2)) }}
                                </div>
                                <span class="text-[11px] font-semibold text-slate-600 truncate max-w-[100px] flex items-center">
                                    {{ $listing->owner->name }}
                                    @if($listing->owner->is_verified)
                                        <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                    @endif
                                </span>
                            </div>

                            <a href="{{ route('listings.show', $listing->slug) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lihat Detail <i class="fa-solid fa-chevron-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm text-slate-500 font-semibold">Belum ada kos pilihan terverifikasi saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- 5. WHY CHOOSE US (Anti-Scam System) -->
<div class="bg-slate-900 text-white py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/10 text-emerald-400 px-3.5 py-1.5 rounded-full text-xs font-bold border border-emerald-500/20">
                    <i class="fa-solid fa-lock text-sm"></i>
                    <span>100% Anti-Scam Safe Rental</span>
                </div>
                
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Sistem Pengamanan Cerdas Anti-Penipuan Properti</h2>
                <p class="text-sm leading-relaxed text-slate-400">
                    Banyak penipuan properti kos fiktif di media sosial dengan meminta DP transfer terlebih dahulu. KosinAja hadir memberantas praktik tersebut dengan sistem verifikasi berlapis demi keamanan Anda.
                </p>

                <div class="space-y-4 pt-4">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold">Pemilik Kos Wajib Verifikasi KTP & Selfie</h4>
                            <p class="text-xs text-slate-400 mt-1">Seluruh owner diwajibkan mengunggah berkas KTP resmi dan selfie pencocokan wajah sebelum dapat mengiklankan properti mereka.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold">Uang Jaminan Booking di Rekening Bersama</h4>
                            <p class="text-xs text-slate-400 mt-1">Uang deposit pemesanan kos Anda ditahan oleh KosinAja dan baru disalurkan kepada pemilik setelah Anda resmi check-in dan serah terima kunci secara sah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualization card overlay -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-700 shadow-2xl relative max-w-sm">
                    <!-- Secure lock badge -->
                    <div class="absolute -top-6 right-2 sm:-right-6 w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center text-slate-950 font-black shadow-lg shadow-emerald-500/20 text-2xl border-4 border-slate-900">
                        <i class="fa-solid fa-shield-halved text-slate-900"></i>
                    </div>
                    
                    <h3 class="text-base font-bold text-white mb-4">Sertifikasi Keamanan Kos</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">Verifikasi Berkas KTP</span>
                            <span class="text-emerald-400 font-bold flex items-center"><i class="fa-solid fa-circle-check mr-1"></i>Lulus Audit</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">Pencocokan Video Selfie</span>
                            <span class="text-emerald-400 font-bold flex items-center"><i class="fa-solid fa-circle-check mr-1"></i>Cocok 100%</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">Akurasi Koordinat GPS</span>
                            <span class="text-emerald-400 font-bold flex items-center"><i class="fa-solid fa-circle-check mr-1"></i>Tersinkron</span>
                        </div>
                        
                        <div class="border-t border-slate-700 pt-4 flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></div>
                            <span class="text-[10px] text-slate-300 font-semibold uppercase tracking-wider">Perlindungan Pembayaran Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6. INTERACTIVE FAQ & SAFE BOOKING GUIDE -->
<div class="bg-slate-50 py-16 border-t border-slate-100" x-data="{ activeFaq: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 space-y-2">
            <div class="inline-flex items-center space-x-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold border border-emerald-200/50 mb-2 uppercase tracking-wider">
                <i class="fa-solid fa-circle-question text-xs"></i>
                <span>Panduan & Edukasi Pengguna</span>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Panduan Aman & Cerdas Sewa Kos</h2>
            <p class="text-xs text-slate-500 max-w-lg mx-auto">Pelajari bagaimana sistem escrow KosinAja bekerja untuk melindungimu dari segala bentuk modus penipuan properti.</p>
        </div>

        <div class="space-y-4">
            
            <!-- Item 1 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300"
                 :class="activeFaq === 1 ? 'border-emerald-200 ring-4 ring-emerald-500/5' : 'hover:border-slate-200'">
                <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)" 
                        class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none transition-all cursor-pointer">
                    <span class="text-sm font-bold text-slate-800 flex items-center pr-4">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-xs mr-3 flex-shrink-0">01</span>
                        Bagaimana sistem anti-scam KosinAja melindungi uang saya?
                    </span>
                    <span class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 transition-transform duration-300"
                          :class="activeFaq === 1 ? 'rotate-180 bg-emerald-50 text-emerald-500' : ''">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </button>
                <div x-show="activeFaq === 1" x-transition x-cloak>
                    <div class="px-6 pb-6 pt-1 pl-16 text-xs text-slate-500 leading-relaxed border-t border-slate-50/50">
                        Uang booking atau deposit Anda akan disimpan secara aman di **Rekening Bersama (Escrow)** KosinAja. Dana baru akan disalurkan kepada pemilik kos (owner) **24 jam setelah Anda resmi check-in** di lokasi. Jika kos ternyata fiktif atau tidak sesuai iklan, Anda berhak mengajukan komplain dan mendapatkan jaminan refund penuh 100%.
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300"
                 :class="activeFaq === 2 ? 'border-emerald-200 ring-4 ring-emerald-500/5' : 'hover:border-slate-200'">
                <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)" 
                        class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none transition-all cursor-pointer">
                    <span class="text-sm font-bold text-slate-800 flex items-center pr-4">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-xs mr-3 flex-shrink-0">02</span>
                        Apakah semua pemilik kos (owner) di platform ini terverifikasi?
                    </span>
                    <span class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 transition-transform duration-300"
                          :class="activeFaq === 2 ? 'rotate-180 bg-emerald-50 text-emerald-500' : ''">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </button>
                <div x-show="activeFaq === 2" x-transition x-cloak>
                    <div class="px-6 pb-6 pt-1 pl-16 text-xs text-slate-500 leading-relaxed border-t border-slate-50/50">
                        **Tentu saja!** Setiap owner wajib melalui proses verifikasi identitas resmi dengan mengunggah KTP asli dan melakukan pencocokan wajah (*face match*) menggunakan video selfie. Sistem kami akan memvalidasi data tersebut sebelum owner diizinkan untuk mengiklankan kos mereka di platform ini.
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300"
                 :class="activeFaq === 3 ? 'border-emerald-200 ring-4 ring-emerald-500/5' : 'hover:border-slate-200'">
                <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)" 
                        class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none transition-all cursor-pointer">
                    <span class="text-sm font-bold text-slate-800 flex items-center pr-4">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-xs mr-3 flex-shrink-0">03</span>
                        Bagaimana cara melakukan survei lokasi kos secara aman?
                    </span>
                    <span class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 transition-transform duration-300"
                          :class="activeFaq === 3 ? 'rotate-180 bg-emerald-50 text-emerald-500' : ''">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </button>
                <div x-show="activeFaq === 3" x-transition x-cloak>
                    <div class="px-6 pb-6 pt-1 pl-16 text-xs text-slate-500 leading-relaxed border-t border-slate-50/50">
                        Anda dapat menghubungi pemilik kos secara langsung melalui **fitur Chat** terintegrasi di KosinAja untuk menjadwalkan kunjungan survei fisik. Kami sangat menyarankan Anda melakukan survei langsung untuk melihat kondisi kos, dan pastikan pembayaran reservasi tetap menggunakan sistem checkout digital resmi kami agar terproteksi.
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300"
                 :class="activeFaq === 4 ? 'border-emerald-200 ring-4 ring-emerald-500/5' : 'hover:border-slate-200'">
                <button type="button" @click="activeFaq = (activeFaq === 4 ? null : 4)" 
                        class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none transition-all cursor-pointer">
                    <span class="text-sm font-bold text-slate-800 flex items-center pr-4">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-xs mr-3 flex-shrink-0">04</span>
                        Bagaimana jika kondisi kos tidak sesuai dengan foto di iklan?
                    </span>
                    <span class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 transition-transform duration-300"
                          :class="activeFaq === 4 ? 'rotate-180 bg-emerald-50 text-emerald-500' : ''">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </button>
                <div x-show="activeFaq === 4" x-transition x-cloak>
                    <div class="px-6 pb-6 pt-1 pl-16 text-xs text-slate-500 leading-relaxed border-t border-slate-50/50">
                        **Ajukan penangguhan dana secepatnya!** KosinAja menjamin kesesuaian iklan dengan unit asli. Jika saat survei check-in Anda menemukan kondisi kos tidak layak atau berbeda jauh dengan foto, laporkan ke admin kami dalam kurun waktu **maksimal 24 jam**. Kami akan langsung membekukan dana booking dan memproses penyelidikan pengembalian dana (*refund*) Anda.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- 7. CTA SECTION -->
<div class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-xl shadow-emerald-950/20">
            <div class="relative space-y-6 max-w-2xl mx-auto">
                <h2 class="text-3xl font-extrabold tracking-tight">Punya Properti Kos Tidak Terpakai?</h2>
                <p class="text-sm text-slate-100 leading-relaxed">
                    Daftarkan properti Anda sebagai Mitra Owner KosinAja secara gratis. Temukan penyewa yang tepat dengan sistem pemasaran terarah dan terpercaya.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register.owner') }}" class="px-6 py-3.5 bg-white text-emerald-700 font-bold rounded-xl text-sm hover:bg-slate-50 shadow-lg shadow-emerald-900/10 transition-all">Daftar Jadi Owner</a>
                    <a href="{{ route('search') }}" class="px-6 py-3.5 bg-emerald-500/20 border border-white/20 text-white font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cari Properti Kos</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
