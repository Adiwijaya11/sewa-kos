@extends('layouts.app')

@section('title', 'SewaKos - Hunian Premium Praktis & Nyaman')

@section('content')

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:bg-zinc-950 transition-colors duration-300">
        
        <!-- Background decorative gradients -->
        <div class="absolute inset-0 z-0 overflow-hidden opacity-30 dark:opacity-20 pointer-events-none">
            <div class="absolute -top-[40%] -left-[10%] w-[600px] h-[600px] rounded-full bg-emerald-400 dark:bg-emerald-600 blur-[120px]"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[500px] h-[500px] rounded-full bg-teal-300 dark:bg-teal-700 blur-[100px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-20 lg:pt-12 lg:pb-28 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Hero Left: Headlines & Form (7 cols) -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/50 dark:border-emerald-800/40 text-emerald-600 dark:text-emerald-400 text-xs font-semibold tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $totalVerifiedKos ?? 122 }}+ Kamar Kos Terverifikasi Nasional
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white leading-[1.1]">
                        Cari Hunian Kos <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent">
                            Premium & Eksklusif
                        </span>
                        Impianmu
                    </h1>
                    
                    <p class="text-lg text-zinc-500 dark:text-zinc-400 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        Temukan kos nyaman dengan fasilitas lengkap, mulai dari Wifi cepat, AC dingin, kamar mandi dalam, hingga pengamanan 24 jam. Tinggal klik, survei online, sewa langsung!
                    </p>

                    <!-- Property Search Filter Card -->
                    <div id="search" class="p-4 sm:p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/80 dark:border-zinc-800/80 shadow-xl shadow-zinc-100/50 dark:shadow-none text-left max-w-2xl mx-auto lg:mx-0 relative z-20">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            
                            <!-- Location filter -->
                            <div class="space-y-1.5">
                                <label for="location" class="block text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Lokasi Kota</label>
                                <div class="relative">
                                    <select id="location" class="w-full pl-3 pr-8 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                        <option value="">Pilih Kota...</option>
                                        <option value="jakarta">Jakarta</option>
                                        <option value="bandung">Bandung</option>
                                        <option value="yogyakarta">Yogyakarta</option>
                                        <option value="surabaya">Surabaya</option>
                                        <option value="malang">Malang</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Gender type filter -->
                            <div class="space-y-1.5">
                                <label for="gender" class="block text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Tipe Penghuni</label>
                                <div class="relative">
                                    <select id="gender" class="w-full pl-3 pr-8 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                        <option value="">Semua Tipe</option>
                                        <option value="putra">Putra (Pria)</option>
                                        <option value="putri">Putri (Wanita)</option>
                                        <option value="campur">Campur / Pasutri</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Budget filter -->
                            <div class="space-y-1.5">
                                <label for="budget" class="block text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Maks. Budget</label>
                                <div class="relative">
                                    <select id="budget" class="w-full pl-3 pr-8 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                        <option value="">Semua Budget</option>
                                        <option value="1">Di bawah 1.5 Jt/bln</option>
                                        <option value="2">1.5 Jt - 2.5 Jt/bln</option>
                                        <option value="3">Di atas 2.5 Jt/bln</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Action Search Button -->
                        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-4">
                            <span class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">Bisa bayar bulanan / tahunan</span>
                            <button type="button" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hero Right: Interactive visual layout (5 cols) -->
                <div class="lg:col-span-5 relative hidden lg:block">
                    <!-- Elegant architectural abstract showcase card representing boardhouse construction -->
                    <div class="relative mx-auto w-[380px] h-[480px] rounded-3xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 shadow-2xl relative overflow-hidden group">
                        
                        <!-- Premium gradient backdrop inside the card -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500/10 to-teal-500/5 dark:from-emerald-950/20 dark:to-teal-950/10 transition-opacity"></div>
                        
                        <!-- Nested graphic container -->
                        <div class="w-full h-[65%] rounded-2xl bg-gradient-to-br from-emerald-400/90 to-teal-600/90 flex flex-col items-center justify-center p-6 relative overflow-hidden shadow-lg">
                            <!-- Abstract layout house lines -->
                            <div class="absolute inset-0 opacity-15">
                                <svg class="w-full h-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 100 M100 0 L0 100" stroke="currentColor" stroke-width="1"/></svg>
                            </div>
                            
                            <svg class="w-20 h-20 text-white filter drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="mt-4 text-white font-extrabold text-lg tracking-wider">Premium Residence</span>
                            <span class="text-emerald-100 text-xs mt-1">Est. Jakarta Menteng</span>
                        </div>

                        <!-- Card text properties simulating a live listing detail view -->
                        <div class="mt-6 space-y-3.5 px-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-lg">Exclusive Putra</span>
                                <span class="flex items-center gap-1 text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    4.9 <span class="text-zinc-400 text-xs font-medium">(280 Ulasan)</span>
                                </span>
                            </div>
                            <h3 class="text-base font-extrabold text-zinc-900 dark:text-white group-hover:text-emerald-500 transition-colors">Kostel Menteng Signature</h3>
                            <div class="flex items-center gap-4 text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a9 9 0 0112.728 0M12 4v1" /></svg>
                                    Wi-Fi Kencang
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" /></svg>
                                    AC & Kamar Mandi
                                </span>
                            </div>
                        </div>
                        
                        <!-- Floating Badge for pricing -->
                        <div class="absolute top-8 right-8 px-4 py-2 bg-zinc-950 text-white dark:bg-white dark:text-zinc-950 font-extrabold text-sm rounded-xl shadow-lg leading-tight">
                            Rp 2,5 Jt <br> <span class="text-[10px] font-medium opacity-80">per bulan</span>
                        </div>
                    </div>

                    <!-- Tiny overlay stats decoration card -->
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-500">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">Hunian Aman</p>
                            <p class="text-sm font-bold text-zinc-800 dark:text-zinc-150">100% Lolos Verifikasi</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Popular Cities Navigation Showcase -->
    <section class="py-16 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200/50 dark:border-zinc-900/60 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center md:text-left md:flex items-end justify-between mb-10">
                <div class="space-y-2">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white">Jelajahi Kota Terpopuler</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">Banyak pilihan boarding house mewah terdekat dengan kampus & perkantoran.</p>
                </div>
                <a href="#" class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 mt-4 md:mt-0 transition-colors">
                    Lihat Semua Kota
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Grid of City Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $popularCitiesWelcome = [
                        ['name' => 'Jakarta', 'listings' => ($jakartaCount ?? 3240) . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Bandung', 'listings' => ($bandungCount ?? 1820) . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Yogyakarta', 'listings' => ($jogjaCount ?? 2110) . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Surabaya', 'listings' => ($surabayaCount ?? 1480) . ' Kos Aktif', 'bg' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=400&q=80'],
                    ];
                @endphp

                @foreach($popularCitiesWelcome as $city)
                    <a href="{{ route('search', ['city' => $city['name'] === 'Jakarta' ? 'Jakarta Selatan' : $city['name']]) }}" class="relative group h-48 rounded-[1.8rem] overflow-hidden shadow-md border border-zinc-200 dark:border-zinc-800 block transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl dark:hover:shadow-emerald-950/20">
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
    </section>

    <!-- Featured Boarding Houses ("Rekomendasi Terpopuler") -->
    <section id="featured" class="py-20 bg-white dark:bg-zinc-950 border-t border-zinc-200/50 dark:border-zinc-900/60 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/50 px-3.5 py-1.5 rounded-full">Hunian Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white">Rekomendasi Kos Eksklusif</h2>
                <p class="text-sm sm:text-base text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">Kamar kos premium terbaik yang paling diminati oleh mahasiswa & pekerja kantoran bulan ini.</p>
            </div>

            <!-- Listings Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Kos Card 1 -->
                <div class="group bg-zinc-50 dark:bg-zinc-900/50 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300">
                    <!-- Image Area with Pure-CSS Premium Gradients & Badges -->
                    <div class="relative w-full h-52 rounded-2xl bg-gradient-to-tr from-amber-400/20 to-orange-400/20 dark:from-amber-950/30 dark:to-orange-950/20 flex items-center justify-center overflow-hidden">
                        
                        <!-- House Outline Artwork Vector overlay -->
                        <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                        </svg>

                        <!-- Float Tags -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg shadow-sm">Putri</span>
                            <span class="px-3 py-1.5 bg-zinc-900/80 backdrop-blur-md text-white text-xs font-semibold rounded-lg">Terlaris</span>
                        </div>

                        <div class="absolute bottom-4 right-4 px-3 py-1 bg-white dark:bg-zinc-900 border border-zinc-200/50 dark:border-zinc-800 rounded-lg text-xs font-bold text-zinc-800 dark:text-zinc-200">
                            Yogyakarta
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="mt-5 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <!-- Ratings -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200">4.8</span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">(120+ Survei)</span>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-zinc-900 dark:text-white">Rp 1.450.000</span>
                                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 font-bold">/ bln</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white leading-tight">Griya Urban Living Gejayan</h3>
                        
                        <!-- Facility Tags -->
                        <div class="flex flex-wrap gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">AC Dingin</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Kamar Mandi Dalam</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Wifi 100Mbps</span>
                        </div>

                        <!-- CTA Book Now -->
                        <div class="pt-4 border-t border-zinc-200/60 dark:border-zinc-800 flex items-center justify-between gap-4 mt-2">
                            <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">Sisa 2 Kamar kosong</span>
                            <a href="#" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-colors">Lihat Kamar</a>
                        </div>
                    </div>
                </div>

                <!-- Kos Card 2 -->
                <div class="group bg-zinc-50 dark:bg-zinc-900/50 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300">
                    <!-- Image Area with Pure-CSS Premium Gradients & Badges -->
                    <div class="relative w-full h-52 rounded-2xl bg-gradient-to-tr from-cyan-400/20 to-teal-400/20 dark:from-cyan-950/30 dark:to-teal-950/20 flex items-center justify-center overflow-hidden">
                        
                        <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                        </svg>

                        <!-- Float Tags -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-lg shadow-sm">Putra</span>
                            <span class="px-3 py-1.5 bg-emerald-500 text-white text-xs font-semibold rounded-lg">Verifikator Plus</span>
                        </div>

                        <div class="absolute bottom-4 right-4 px-3 py-1 bg-white dark:bg-zinc-900 border border-zinc-200/50 dark:border-zinc-800 rounded-lg text-xs font-bold text-zinc-800 dark:text-zinc-200">
                            Bandung
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="mt-5 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <!-- Ratings -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200">4.9</span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">(150+ Survei)</span>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-zinc-900 dark:text-white">Rp 1.850.000</span>
                                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 font-bold">/ bln</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white leading-tight">D'Lovera Signature Dago</h3>
                        
                        <!-- Facility Tags -->
                        <div class="flex flex-wrap gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Kamar Mandi Dalam</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">AC & Water Heater</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Parking Lot</span>
                        </div>

                        <!-- CTA Book Now -->
                        <div class="pt-4 border-t border-zinc-200/60 dark:border-zinc-800 flex items-center justify-between gap-4 mt-2">
                            <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">Sisa 5 Kamar kosong</span>
                            <a href="#" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-colors">Lihat Kamar</a>
                        </div>
                    </div>
                </div>

                <!-- Kos Card 3 -->
                <div class="group bg-zinc-50 dark:bg-zinc-900/50 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300">
                    <!-- Image Area with Pure-CSS Premium Gradients & Badges -->
                    <div class="relative w-full h-52 rounded-2xl bg-gradient-to-tr from-purple-400/20 to-pink-400/20 dark:from-purple-950/30 dark:to-pink-950/20 flex items-center justify-center overflow-hidden">
                        
                        <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                        </svg>

                        <!-- Float Tags -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1.5 bg-violet-600 text-white text-xs font-bold rounded-lg shadow-sm">Campur</span>
                            <span class="px-3 py-1.5 bg-zinc-900/80 backdrop-blur-md text-white text-xs font-semibold rounded-lg">Exclusive</span>
                        </div>

                        <div class="absolute bottom-4 right-4 px-3 py-1 bg-white dark:bg-zinc-900 border border-zinc-200/50 dark:border-zinc-800 rounded-lg text-xs font-bold text-zinc-800 dark:text-zinc-200">
                            Jakarta
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="mt-5 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <!-- Ratings -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200">4.7</span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">(98+ Survei)</span>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-zinc-900 dark:text-white">Rp 2.900.000</span>
                                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 font-bold">/ bln</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white leading-tight">Kostel Menteng Signature</h3>
                        
                        <!-- Facility Tags -->
                        <div class="flex flex-wrap gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Private Kitchen</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Full Furnished</span>
                            <span class="px-2 py-1 bg-zinc-200/50 dark:bg-zinc-800 rounded-lg">Gym & Swimming Pool</span>
                        </div>

                        <!-- CTA Book Now -->
                        <div class="pt-4 border-t border-zinc-200/60 dark:border-zinc-800 flex items-center justify-between gap-4 mt-2">
                            <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">Sisa 1 Kamar kosong</span>
                            <a href="#" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-colors">Lihat Kamar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Why Choose Us / Trust Badges -->
    <section id="why-us" class="py-24 bg-zinc-100 dark:bg-zinc-900/60 border-t border-zinc-200/50 dark:border-zinc-800/60 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                
                <!-- Description (5 cols) -->
                <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Keunggulan Layanan</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white leading-tight">
                        Mengapa Menyewa Kos <br class="hidden sm:inline">
                        Lewat <span class="bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent">SewaKos</span>?
                    </h2>
                    <p class="text-sm sm:text-base text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">
                        Kami merancang setiap alur pencarian agar sesederhana mungkin. Anda tidak perlu berputar-putar di jalanan mencari spanduk kosong, cukup buka gawai Anda.
                    </p>
                    <div class="pt-4 flex justify-center lg:justify-start">
                        <a href="#" class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all">
                            Cari Hunian Terdekat
                        </a>
                    </div>
                </div>

                <!-- Features Cards Grid (7 cols) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- Feature 1 -->
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/50 dark:border-zinc-800 shadow-sm hover:border-emerald-500 dark:hover:border-emerald-400 hover:scale-[1.02] transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-500 mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">100% Terverifikasi</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2.5 leading-relaxed">
                            Semua properti sewaan difoto langsung oleh tim lapangan dan melalui proses verifikasi sertifikat kepemilikan.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/50 dark:border-zinc-800 shadow-sm hover:border-emerald-500 dark:hover:border-emerald-400 hover:scale-[1.02] transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-500 mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Harga Jujur & Pas</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2.5 leading-relaxed">
                            Tidak ada biaya agen tersembunyi. Harga yang tertera adalah harga murni langsung dari pemilik boarding house.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/50 dark:border-zinc-800 shadow-sm hover:border-emerald-500 dark:hover:border-emerald-400 hover:scale-[1.02] transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-500 mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Pembayaran Mudah</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2.5 leading-relaxed">
                            Mendukung berbagai metode pembayaran instan: Bank Transfer (VA), e-Wallet (OVO, GoPay), hingga QRIS.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/50 dark:border-zinc-800 shadow-sm hover:border-emerald-500 dark:hover:border-emerald-400 hover:scale-[1.02] transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-500 mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">CS Pendamping Survei</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2.5 leading-relaxed">
                            Tim CS kami siap membantu mendampingi Anda saat ingin melakukan survei langsung ke lapangan kapan pun dibutuhkan.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection
