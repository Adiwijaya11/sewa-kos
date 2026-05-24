@extends('layouts.app')
@section('title', $listing->title . ' - KosinAja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
     x-data="{ 
         reportModal: false,
         lightboxOpen: false,
         activeImageIndex: 0,
         imagesCount: {{ $listing->images->count() }},
         images: [
             @foreach($listing->images as $img)
                 '{{ asset($img->image) }}',
             @endforeach
         ],
         openLightbox(index) {
             this.activeImageIndex = index;
             this.lightboxOpen = true;
         },
         nextImage() {
             this.activeImageIndex = (this.activeImageIndex + 1) % this.imagesCount;
         },
         prevImage() {
             this.activeImageIndex = (this.activeImageIndex - 1 + this.imagesCount) % this.imagesCount;
         }
     }">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-slate-400 mb-6 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('search') }}" class="hover:text-emerald-600 transition-colors">Cari Kos</a>
        <span>/</span>
        <span class="text-slate-600 truncate max-w-[200px]">{{ $listing->title }}</span>
    </nav>

    <!-- 1. IMAGE GRID GALLERY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-slate-100 mb-6 sm:mb-8 bg-slate-100">
        <!-- Main Big Image -->
        <div class="md:col-span-2 h-56 sm:h-80 md:h-[420px] lg:h-[480px] relative overflow-hidden group">
            @if($listing->images->count() > 0)
                <img src="{{ asset($listing->images->first()->image) }}" 
                     alt="{{ $listing->title }}" 
                     class="w-full h-full object-cover cursor-pointer hover:scale-[1.01] transition-transform duration-500"
                     @click="openLightbox(0)">
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-slate-900/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer pointer-events-none">
                    <div class="w-12 h-12 rounded-full bg-white/25 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-100 transition-all duration-300">
                        <i class="fa-solid fa-expand text-base"></i>
                    </div>
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                    <i class="fa-regular fa-image text-5xl"></i>
                </div>
            @endif
        </div>

        <!-- Smaller images column (stacked, hidden on mobile) -->
        <div class="hidden md:flex flex-col gap-3 h-[420px] lg:h-[480px]">
            @if($listing->images->count() > 1)
                @foreach($listing->images->skip(1)->take(2) as $img)
                    <div class="relative h-[232px] overflow-hidden group">
                        <img src="{{ asset($img->image) }}" 
                             alt="Gallery Item" 
                             class="w-full h-full object-cover cursor-pointer hover:scale-[1.02] transition-transform duration-500"
                             @click="openLightbox({{ $loop->index + 1 }})">
                        @if($loop->index == 1 && $listing->images->count() > 3)
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex flex-col items-center justify-center text-white cursor-pointer select-none transition-all hover:bg-slate-900/50" @click="openLightbox(2)">
                                <span class="text-lg font-black">+{{ $listing->images->count() - 3 }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Foto Lainnya</span>
                            </div>
                        @else
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-slate-900/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer pointer-events-none">
                                <div class="w-10 h-10 rounded-full bg-white/25 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-100 transition-all duration-300">
                                    <i class="fa-solid fa-expand text-sm"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="h-full flex items-center justify-center bg-slate-200 text-slate-400">
                    <i class="fa-regular fa-image text-3xl"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- 2. SPLIT LAYOUT (Content + Widget) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- LEFT: Kos Details (takes 2 cols) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Header Title -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full text-white {{ $listing->gender_type === 'putra' ? 'bg-blue-600' : ($listing->gender_type === 'putri' ? 'bg-pink-600' : 'bg-purple-600') }}">
                        Kos {{ $listing->gender_type }}
                    </span>
                    
                    @if($listing->is_verified)
                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center space-x-1">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span>✅ Verified Kos</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                    {{ $listing->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <div class="flex items-center">
                        <i class="fa-solid fa-map-location-dot text-slate-400 mr-2 text-base"></i>{{ $listing->address }}, {{ $listing->city }}, {{ $listing->province }}
                    </div>
                    <div class="flex items-center text-slate-400 bg-slate-50 border border-slate-100 px-2 py-0.5 rounded-lg" title="Listing kos ini sangat populer">
                        <i class="fa-regular fa-eye mr-1.5 text-xs text-slate-400"></i>
                        <span class="font-bold text-[10px] text-slate-500">{{ $listing->views }}x dilihat</span>
                    </div>
                </div>
            </div>

            <!-- Spesifikasi Utama (Ukuran Kamar, Hunian, Kapasitas, Kamar, Status) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3.5 bg-slate-50 p-4 rounded-2xl border border-slate-100/60 shadow-sm">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-expand"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Ukuran Kamar</span>
                        <span class="text-xs font-bold text-slate-800">{{ $listing->room_size ?? '3x4 m' }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-venus-mars"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Tipe Hunian</span>
                        <span class="text-xs font-bold text-slate-800 capitalize">Kos {{ $listing->gender_type }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Maks. Penghuni</span>
                        <span class="text-xs font-bold text-slate-800">{{ $listing->max_people ?? 1 }} orang</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Total Kamar</span>
                        <span class="text-xs font-bold text-slate-800">{{ $listing->total_rooms ?? 10 }} kamar</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Kamar Tersedia</span>
                        <span class="text-xs font-bold {{ ($listing->available_rooms ?? 0) == 0 ? 'text-red-500' : 'text-slate-800' }}">{{ $listing->available_rooms ?? 0 }} kamar</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Status Properti</span>
                        <span class="text-xs font-bold text-emerald-600">{{ $listing->is_verified ? 'Terverifikasi' : 'Review Admin' }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Deskripsi Properti</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </p>
            </div>

            <!-- Facilities Grid -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Fasilitas Properti</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-3">
                    @forelse($listing->facilities as $fac)
                        <div class="flex items-center space-x-2.5 p-3 rounded-xl border border-slate-50 bg-slate-50/50">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-{{ $fac->icon }}"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">{{ $fac->name }}</span>
                        </div>
                    @empty
                        <span class="text-xs text-slate-400 italic">Belum ada fasilitas terdaftar.</span>
                    @endforelse
                </div>
            </div>

            <!-- Nearby Places (SEO & Access) -->
            @if($listing->near_campus || $listing->near_mall || $listing->near_hospital || $listing->near_station)
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Akses & Lokasi Terdekat</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @if($listing->near_campus)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] text-slate-400 uppercase font-extrabold block">Dekat Kampus</span>
                                    <span class="text-xs font-bold text-slate-700 truncate block">{{ $listing->near_campus }}</span>
                                </div>
                            </div>
                        @endif

                        @if($listing->near_mall)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] text-slate-400 uppercase font-extrabold block">Dekat Pusat Belanja</span>
                                    <span class="text-xs font-bold text-slate-700 truncate block">{{ $listing->near_mall }}</span>
                                </div>
                            </div>
                        @endif

                        @if($listing->near_hospital)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                    <i class="fa-solid fa-house-medical"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] text-slate-400 uppercase font-extrabold block">Dekat Rumah Sakit</span>
                                    <span class="text-xs font-bold text-slate-700 truncate block">{{ $listing->near_hospital }}</span>
                                </div>
                            </div>
                        @endif

                        @if($listing->near_station)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                    <i class="fa-solid fa-train"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] text-slate-400 uppercase font-extrabold block">Dekat Stasiun / Transit</span>
                                    <span class="text-xs font-bold text-slate-700 truncate block">{{ $listing->near_station }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Leaflet Interactive Map -->
            @if($listing->latitude && $listing->longitude)
                <div class="border-t border-slate-100 pt-6 z-0 relative">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800">Lokasi Kos (Akurat GPS)</h3>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $listing->latitude }},{{ $listing->longitude }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-600 text-[11px] font-bold shadow-sm transition-all">
                            <i class="fa-solid fa-map-location-dot text-emerald-500"></i>
                            <span>Buka di Google Maps</span>
                        </a>
                    </div>
                    <div id="detail-map" class="w-full h-72 rounded-2xl border border-slate-100 z-0"></div>
                </div>
            @endif
        </div>

        <!-- RIGHT: Booking Widget — desktop sticky, mobile appears naturally in flow -->
        <div class="space-y-4">
            <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 space-y-5 lg:sticky lg:top-24">
                
                <!-- Price Widget -->
                <div class="space-y-1">
                    @if($isBooked || ($listing->available_rooms ?? 0) == 0)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black border border-red-100 uppercase tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span>Penuh / Sudah Tersewa</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black border border-emerald-100 uppercase tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Tersedia (Sisa {{ $listing->available_rooms }} Kamar)</span>
                        </div>
                    @endif
                    <span class="text-xs text-slate-400 block mt-1">Harga Sewa Bulanan:</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-black text-slate-900">Rp {{ number_format($listing->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-400">/ Bulan</span>
                    </div>
                </div>

                <!-- Favorite / Share Buttons -->
                <div class="flex space-x-2 pt-2 border-t border-slate-100">
                    <!-- Favorite Toggle Button -->
                    @auth
                        @php
                            $isFav = \App\Models\Favorite::where('user_id', auth()->id())->where('listing_id', $listing->id)->exists();
                        @endphp
                        <form action="{{ route('listings.favorite', $listing->id) }}" method="POST" class="flex-grow">
                            @csrf
                            <button type="submit" 
                                    class="w-full h-10 border rounded-xl text-xs font-bold flex items-center justify-center space-x-1.5 transition-all {{ $isFav ? 'bg-red-50 border-red-200 text-red-500 shadow-sm shadow-red-50' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                                <i class="fa-solid fa-heart {{ $isFav ? 'text-red-500' : '' }}"></i>
                                <span>{{ $isFav ? 'Favorit Saya' : 'Simpan Kos' }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full h-10 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-xl text-xs font-bold flex items-center justify-center space-x-1.5 transition-all">
                            <i class="fa-regular fa-heart"></i>
                            <span>Simpan Kos</span>
                        </a>
                    @endauth
                </div>

                <!-- Anti-Scam safe booking prompt -->
                <div class="p-4 bg-emerald-50 border border-emerald-100/50 rounded-2xl flex items-start space-x-3 text-emerald-800">
                    <i class="fa-solid fa-shield-halved text-lg mt-0.5 text-emerald-600 flex-shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold">100% Booking Aman Bebas Scam</h4>
                        <p class="text-[10px] text-slate-500 leading-normal mt-1">Uang sewa ditahan KosinAja dan baru dilepas ke Owner 1 hari setelah Anda sukses check-in!</p>
                    </div>
                </div>

                <!-- Owner Profile Section -->
                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Kontak Pemilik (Owner)</span>
                    <div class="flex items-center space-x-3.5">
                        <!-- Profile Photo -->
                        <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 shadow-sm bg-slate-50 flex-shrink-0 relative">
                            @if($listing->owner->avatar)
                                <img src="{{ asset($listing->owner->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-extrabold text-sm uppercase">
                                    {{ substr($listing->owner->name, 0, 2) }}
                                </div>
                            @endif
                            @if($listing->owner->is_verified)
                                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[7px] text-white" title="Verified Owner">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            @endif
                        </div>
                        
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-black text-slate-800 leading-snug truncate flex items-center gap-1">
                                <span>{{ $listing->owner->name }}</span>
                                @if($listing->owner->is_verified)
                                    <span class="inline-flex items-center px-1 rounded bg-emerald-100 text-emerald-700 text-[8px] font-bold">Verif</span>
                                @endif
                            </h4>
                            <p class="text-[9px] text-slate-400 truncate mt-0.5">{{ $listing->owner->email }}</p>
                        </div>
                    </div>

                    <!-- Direct WhatsApp Button -->
                    @if($listing->owner->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $listing->owner->phone) }}?text=Halo%20{{ urlencode($listing->owner->name) }},%20saya%20tertarik%20dengan%20kos%20Anda%20'{{ urlencode($listing->title) }}'%20di%20KosinAja." 
                           target="_blank" 
                           class="w-full h-9 flex items-center justify-center gap-2 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white text-[11px] font-bold shadow-md shadow-emerald-500/10 transition-all select-none">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Hubungi via WhatsApp</span>
                        </a>
                    @else
                        <button class="w-full h-9 flex items-center justify-center gap-2 rounded-xl bg-slate-100 text-slate-400 text-[11px] font-bold cursor-not-allowed" disabled>
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>No. WhatsApp Tidak Tersedia</span>
                        </button>
                    @endif
                </div>

                <!-- Primary actions (Book, Chat) -->
                <div class="space-y-3 pt-2">
                    @auth
                        @if(auth()->user()->role === 'user')
                            @if($isBooked || ($listing->available_rooms ?? 0) == 0)
                                <button class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-slate-400 bg-slate-100 cursor-not-allowed shadow-none" disabled>
                                    <i class="fa-solid fa-lock mr-2 text-slate-400"></i>Kamar Kos Penuh
                                </button>
                            @else
                                <a href="{{ route('payments.checkout', $listing->id) }}" 
                                   class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-all">
                                    Ajukan Booking Aman
                                </a>
                            @endif
                            
                            @if(auth()->id() !== $listing->owner_id)
                                <a href="{{ route('chats.conversation', $listing->owner_id) }}" 
                                   class="w-full flex justify-center items-center py-3 border border-emerald-600 text-sm font-semibold rounded-xl text-emerald-600 bg-white hover:bg-emerald-50 transition-all">
                                    <i class="fa-regular fa-comments mr-2"></i>Tanya Pemilik
                                </a>
                            @endif
                        @else
                            <div class="text-center p-3 bg-slate-50 rounded-xl text-xs text-slate-400 font-semibold italic border">
                                Booking dinonaktifkan untuk akun Owner / Admin
                            </div>
                        @endif
                    @else
                        @if($isBooked || ($listing->available_rooms ?? 0) == 0)
                            <button class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-slate-400 bg-slate-100 cursor-not-allowed shadow-none" disabled>
                                <i class="fa-solid fa-lock mr-2 text-slate-400"></i>Kamar Kos Penuh
                            </button>
                        @else
                            <a href="{{ route('login') }}" 
                               class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all">
                                Login Untuk Booking
                            </a>
                        @endif
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center items-center py-3 border border-emerald-600 text-sm font-semibold rounded-xl text-emerald-600 bg-white hover:bg-emerald-50 transition-all">
                            <i class="fa-regular fa-comments mr-2"></i>Tanya Pemilik
                        </a>
                    @endauth
                </div>

                <!-- Report system -->
                <div class="border-t border-slate-100 pt-4 flex justify-center">
                    @auth
                        <button @click="reportModal = true" class="text-[10px] font-bold text-red-500 hover:text-red-600 flex items-center transition-colors">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>Laporkan Iklan Ini (Anti-Scam)
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="text-[10px] font-bold text-slate-400 hover:text-red-500 flex items-center transition-colors">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>Laporkan Iklan Ini
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- 3. REPORT LISTING MODAL (Alpine.js overlay) -->
    <div x-show="reportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak>
        <div @click.away="reportModal = false" class="bg-white max-w-md w-full rounded-3xl overflow-hidden border border-slate-100 shadow-2xl p-6 space-y-6" x-transition>
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mr-2"></i>Laporkan Iklan Kos
                </h3>
                <button @click="reportModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('listings.report', $listing->id) }}" method="POST" class="space-y-4 confirm-form"
                  data-confirm-title="Kirim Laporan Pengaduan?"
                  data-confirm-text="Apakah Anda yakin ingin mengirim laporan pengaduan untuk kos '{{ $listing->title }}'? Laporan palsu dapat dikenakan sanksi."
                  data-confirm-button="Ya, Laporkan"
                  data-confirm-color="#ef4444"
                  data-confirm-icon="warning">
                @csrf
                <!-- Reason Selection -->
                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan Laporan</label>
                    <select name="reason" id="reason" required 
                            class="block w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all bg-white text-slate-600">
                        <option value="scam">Penipuan / Fiktif / Minta Transfer DP duluan</option>
                        <option value="fake_owner">Akun Palsu / Mengaku Pemilik Asli</option>
                        <option value="fake_photos">Foto Palsu / Mengambil dari Kos Lain</option>
                        <option value="harassment">Gangguan / Pelecehan Verbal</option>
                        <option value="wrong_location">Lokasi salah / GPS tidak akurat</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Penjelasan Detail</label>
                    <textarea name="description" id="description" rows="4" required minlength="10"
                              class="block w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all placeholder-slate-400"
                              placeholder="Jelaskan secara jelas mengapa listing ini dicurigai bermasalah..."></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="reportModal = false" class="w-1/2 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl text-xs hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="w-1/2 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl text-xs shadow-md shadow-red-100 transition-all">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. IMAGE LIGHTBOX / GALLERY CAROUSEL (Alpine.js overlay) -->
    <div x-show="lightboxOpen" 
         x-cloak 
         class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 sm:p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.window.escape="lightboxOpen = false"
         @keydown.window.arrow-right="nextImage()"
         @keydown.window.arrow-left="prevImage()">
        
        <!-- Close Button (Premium Floating Circle) -->
        <button @click="lightboxOpen = false" 
                class="absolute top-6 right-6 z-[110] w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition-all focus:outline-none backdrop-blur-sm shadow-lg">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <!-- Main Slider Content Container -->
        <div class="relative w-full max-w-5xl flex-1 flex items-center justify-center">
            
            <!-- Prev Button -->
            <button @click="prevImage()" 
                    class="absolute left-0 sm:left-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white flex items-center justify-center transition-all focus:outline-none backdrop-blur-sm shadow-md"
                    x-show="imagesCount > 1">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>

            <!-- Active Large Image Card -->
            <div class="w-full max-h-[70vh] flex items-center justify-center p-2">
                <template x-for="(imgSrc, idx) in images" :key="idx">
                    <img x-show="activeImageIndex === idx" 
                         :src="imgSrc" 
                         alt="Kos Photo Large" 
                         class="max-w-full max-h-[70vh] object-contain rounded-2xl shadow-2xl transition-all duration-300"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                </template>
            </div>

            <!-- Next Button -->
            <button @click="nextImage()" 
                    class="absolute right-0 sm:right-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white flex items-center justify-center transition-all focus:outline-none backdrop-blur-sm shadow-md"
                    x-show="imagesCount > 1">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>
        </div>

        <!-- Thumbnails and Stats Footer -->
        <div class="w-full max-w-5xl mt-4 flex flex-col items-center gap-4 border-t border-white/5 pt-4">
            <!-- Counter -->
            <div class="text-xs font-bold text-slate-400">
                Foto <span class="text-white" x-text="activeImageIndex + 1"></span> dari <span class="text-white" x-text="imagesCount"></span>
            </div>

            <!-- Thumbnail list -->
            <div class="flex items-center gap-2.5 overflow-x-auto max-w-full no-scrollbar px-4 py-2" x-show="imagesCount > 1">
                <template x-for="(imgSrc, idx) in images" :key="idx">
                    <button @click="activeImageIndex = idx" 
                            class="w-16 h-12 rounded-lg overflow-hidden border-2 flex-shrink-0 transition-all focus:outline-none shadow-sm"
                            :class="activeImageIndex === idx ? 'border-emerald-500 scale-105 shadow-emerald-500/20' : 'border-white/10 opacity-50 hover:opacity-80'">
                        <img :src="imgSrc" alt="Thumbnail" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

<!-- LEAFLET GPS MAP SCRIPT -->
@if($listing->latitude && $listing->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $listing->latitude }};
        const lng = {{ $listing->longitude }};
        const title = "{{ $listing->title }}";

        const map = L.map('detail-map', { preferCanvas: true }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(`
                <div class="p-1 space-y-1">
                    <p class="text-xs font-bold text-slate-800 leading-tight">${title}</p>
                    <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" class="inline-flex items-center text-[10px] font-extrabold text-emerald-600 hover:text-emerald-700 mt-1 select-none">
                        <i class="fa-solid fa-map-location-dot mr-1"></i>Buka di Google Maps
                    </a>
                </div>
            `)
            .openPopup();

        // Invalidate map size to correct layout
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });
</script>
@endif
@endsection
