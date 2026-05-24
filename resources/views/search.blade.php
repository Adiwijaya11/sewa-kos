@extends('layouts.app')
@section('title', 'Cari Kos Modern & Terpercaya - KosinAja')

@section('content')
<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 1.6s infinite linear;
    }
</style>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8"
     x-data="searchPage()"
     @set-loading.window="isLoading = true">

    <!-- Premium Header Section -->
    <div class="mb-6 bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-xl shadow-slate-950/10 border border-slate-800/80 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <!-- Decorative background blur orbs -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 space-y-2.5">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase tracking-widest">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Anti-Scam Verified System</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white leading-none">
                Cari Hunian <span class="bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Kos Modern</span>
            </h1>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Dapatkan rekomendasi kos terdekat terverifikasi aman bebas penipuan dengan jaminan pemilik KTP terverifikasi.
            </p>
        </div>

        <!-- Action Buttons & Quick Stats Area -->
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-4 lg:self-center flex-shrink-0">
            
            <!-- Quick Feature Badge (Hidden on mobile) -->
            <div class="hidden sm:flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-2.5 backdrop-blur-sm">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Aman & Nyaman</p>
                    <p class="text-[11px] font-extrabold text-white">100% Terverifikasi</p>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Mobile: Filter Drawer Toggle -->
                <button @click="filterDrawer = true"
                        class="lg:hidden flex-1 sm:flex-initial flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl border border-white/10 bg-white/10 text-white text-xs font-bold shadow-sm hover:bg-white/20 transition-all backdrop-blur-sm">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Filter</span>
                    @if(request()->hasAny(['city','gender_type','min_price','max_price','facilities','verified','sort']))
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                    @endif
                </button>
                
                <!-- Map Toggle -->
                <button @click="mapToggle = !mapToggle; $nextTick(() => { if(window.searchMap) { window.searchMap.invalidateSize(); } })"
                        class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-3 border rounded-xl text-xs font-bold transition-all shadow-md backdrop-blur-sm"
                        :class="mapToggle ? 'bg-emerald-500 border-emerald-500 text-white shadow-emerald-950/20' : 'bg-white/10 border-white/10 text-white hover:bg-white/20'">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Peta Lokasi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Drawer Backdrop -->
    <div x-show="filterDrawer" x-cloak
         @click="filterDrawer = false"
         class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Mobile Filter Drawer Panel -->
    <div x-show="filterDrawer" x-cloak
         class="fixed inset-y-0 left-0 z-50 w-80 max-w-[90vw] bg-white shadow-2xl lg:hidden flex flex-col"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <h2 class="text-sm font-bold text-slate-800 flex items-center">
                <i class="fa-solid fa-sliders text-emerald-500 mr-2"></i>Filter Pencarian
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('search') }}" @click.prevent="resetFilters()" class="text-[10px] font-semibold text-slate-400 hover:text-red-500 transition-colors flex items-center">
                    <i class="fa-solid fa-rotate-left mr-1"></i>Reset
                </a>
                <button @click="filterDrawer = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        <div id="mobile-filter-container" class="overflow-y-auto flex-1 px-5 py-4 space-y-6 no-scrollbar">
            @include('partials.search-filter')
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8 items-start">
        
        <!-- SIDEBAR FILTERS (Column 1) -->
        <!-- Desktop Sidebar Filter (hidden on mobile) -->
        <div class="hidden lg:flex bg-white rounded-2xl border border-slate-100 shadow-sm h-fit flex-col flex-shrink-0">
            
            <!-- Filter Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                <h2 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-sliders text-emerald-500 mr-2"></i>Filter Pencarian
                </h2>
                <a href="{{ route('search') }}" @click.prevent="resetFilters()" class="text-[10px] font-semibold text-slate-400 hover:text-red-500 transition-colors flex items-center">
                    <i class="fa-solid fa-rotate-left mr-1"></i>Reset
                </a>
            </div>

            <!-- Filter body (fully displayed, no internal scroll) -->
            <div id="desktop-filter-container" class="px-5 py-5">
                @include('partials.search-filter')
            </div>
        </div>


        <!-- LISTINGS GRID & MAP (Column 2-4) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Map Section (Visible when mapToggle is active) -->
            <div x-show="mapToggle" x-cloak class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm relative z-0">
                <div id="search-map" class="w-full h-80 rounded-2xl"></div>
                <div class="absolute bottom-6 left-6 z-[1000] bg-slate-900/90 text-white px-3.5 py-1.5 rounded-full text-[10px] font-bold shadow-md flex items-center">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-ping mr-2"></div>
                    <span>Lokasi Kos Terverifikasi Aktif</span>
                </div>
                <!-- Premium Map Loading Blur Shimmer Overlay -->
                <div x-show="isLoading" x-cloak class="absolute inset-4 bg-slate-100/80 backdrop-blur-sm rounded-2xl flex items-center justify-center z-[1001] transition-all duration-300">
                    <div class="flex flex-col items-center space-y-3">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-4 border-emerald-100"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-t-emerald-500 animate-spin"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-700 animate-pulse">Memuat Peta Lokasi...</span>
                    </div>
                </div>
            </div>

            <div id="search-results-container">
                <!-- List of properties -->
                <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($listings as $listing)
                        <!-- Card Component -->
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-lg hover:border-slate-200/80 group flex flex-col h-full transition-all duration-300">
                        <div class="relative h-44 overflow-hidden bg-slate-100">
                            @if(($listing->available_rooms ?? 0) <= 0)
                                <div class="absolute inset-0 bg-slate-900/65 backdrop-blur-[1px] flex items-center justify-center z-10 transition-all duration-300">
                                    <div class="px-3.5 py-2 rounded-xl bg-red-600 text-white text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 border border-red-500/20 shadow-lg shadow-red-950/20">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                        <span>Sudah Terbooking</span>
                                    </div>
                                </div>
                            @endif

                            @if($listing->images->count() > 0)
                                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                     src="{{ asset($listing->images->first()->image) }}" 
                                     alt="{{ $listing->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-image text-3xl"></i>
                                </div>
                            @endif

                            <!-- Gender Badge -->
                            <span class="absolute top-4 left-4 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md {{ $listing->gender_type === 'putra' ? 'bg-blue-600 text-white' : ($listing->gender_type === 'putri' ? 'bg-pink-600 text-white' : 'bg-purple-600 text-white') }}">
                                Kos {{ $listing->gender_type }}
                            </span>

                            <!-- Verified Badge -->
                            @if($listing->is_verified)
                                <span class="absolute top-4 right-4 bg-emerald-500 text-slate-900 text-[9px] font-extrabold px-2 py-0.5 rounded-md flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>VERIFIED</span>
                                </span>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col flex-grow space-y-2">
                            <div class="text-[10px] text-slate-400 flex items-center justify-between">
                                <span class="flex items-center"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $listing->city }}, {{ $listing->province }}</span>
                                <span class="flex items-center font-medium bg-slate-50 border border-slate-100 px-1.5 py-0.5 rounded text-slate-400" title="Populer: Sudah {{ $listing->views }}x dilihat penyewa">
                                    <i class="fa-regular fa-eye mr-1 text-[9px]"></i>{{ $listing->views }}
                                </span>
                            </div>

                            <h3 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 min-h-[35px]">
                                <a href="{{ route('listings.show', $listing->slug) }}" class="hover:text-emerald-600 transition-all">
                                    {{ $listing->title }}
                                </a>
                            </h3>

                            <!-- Mini facility list icons & Room Size -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-50 min-h-[30px]">
                                <div class="flex items-center space-x-1.5">
                                    @foreach($listing->facilities->take(3) as $fac)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 text-[10px]" title="{{ $fac->name }}">
                                            <i class="fa-solid fa-{{ $fac->icon }}"></i>
                                        </span>
                                    @endforeach
                                    @if($listing->facilities->count() > 3)
                                        <span class="text-[8px] font-bold text-slate-400" title="Dan {{ $listing->facilities->count() - 3 }} fasilitas lainnya">+{{ $listing->facilities->count() - 3 }}</span>
                                    @endif
                                </div>
                                @if($listing->room_size)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-[4px] bg-slate-50 border border-slate-100/80 text-slate-500 text-[9px] font-bold uppercase tracking-wider" title="Ukuran Kamar: {{ $listing->room_size }}">
                                        <i class="fa-solid fa-expand text-[8px] text-slate-400 mr-1"></i>{{ $listing->room_size }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex justify-between items-baseline pt-4 border-t border-slate-100 mt-auto">
                                <span class="text-[10px] text-slate-400">Mulai</span>
                                <span class="text-sm font-black text-emerald-600">Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="text-[10px] font-semibold text-slate-500">/bln</span></span>
                            </div>
                        </div>

                        <!-- Card Footer actions -->
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[9px] text-slate-500 font-medium flex items-center">
                                Owner: {{ $listing->owner->name }}
                                @if($listing->owner->is_verified)
                                    <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                @endif
                            </span>
                            <a href="{{ route('listings.show', $listing->slug) }}" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center">
                                Detail <i class="fa-solid fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 bg-white rounded-2xl border border-dashed border-slate-200">
                        <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-4 block"></i>
                        <p class="text-sm text-slate-500 font-bold">Kos tidak ditemukan</p>
                        <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau bersihkan filter pencarian Anda.</p>
                    </div>
                @endforelse
            </div>

            <!-- Skeleton Loader Grid (Premium Shimmer) -->
            <div x-show="isLoading" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm flex flex-col h-full animate-pulse">
                        <!-- Image Shimmer -->
                        <div class="relative h-44 bg-slate-200 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shimmer" style="background-size: 200% 100%;"></div>
                            <!-- Badges Shimmer -->
                            <div class="absolute top-4 left-4 w-20 h-5 bg-slate-300 rounded-md"></div>
                            <div class="absolute top-4 right-4 w-16 h-5 bg-slate-300 rounded-md"></div>
                        </div>

                        <!-- Content Shimmer -->
                        <div class="p-4 flex flex-col flex-grow space-y-3">
                            <!-- Location Shimmer -->
                            <div class="flex items-center space-x-1.5">
                                <div class="w-3.5 h-3.5 bg-slate-200 rounded-full"></div>
                                <div class="w-24 h-3 bg-slate-200 rounded-md"></div>
                            </div>

                            <!-- Title Shimmer -->
                            <div class="space-y-2">
                                <div class="w-full h-4 bg-slate-200 rounded-md"></div>
                                <div class="w-3/4 h-4 bg-slate-200 rounded-md"></div>
                            </div>

                            <!-- Facilities Shimmer -->
                            <div class="flex items-center space-x-2 pt-2 border-t border-slate-50">
                                <div class="w-6 h-6 rounded-lg bg-slate-100"></div>
                                <div class="w-6 h-6 rounded-lg bg-slate-100"></div>
                                <div class="w-6 h-6 rounded-lg bg-slate-100"></div>
                                <div class="w-12 h-3 bg-slate-100 rounded-md ml-auto"></div>
                            </div>

                            <!-- Price Shimmer -->
                            <div class="flex justify-between items-baseline pt-4 border-t border-slate-100 mt-auto">
                                <div class="w-8 h-3 bg-slate-200 rounded-md"></div>
                                <div class="w-28 h-5 bg-emerald-100/70 rounded-md"></div>
                            </div>
                        </div>

                        <!-- Footer Shimmer -->
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <div class="w-24 h-3 bg-slate-200 rounded-md"></div>
                            <div class="w-12 h-3.5 bg-slate-200 rounded-md"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Pagination Block -->
            @if($listings->hasPages())
            <div x-show="!isLoading" class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                {{-- Info hasil --}}
                <p class="text-xs text-slate-400">
                    Menampilkan 
                    <span class="font-semibold text-slate-600">{{ $listings->firstItem() }}–{{ $listings->lastItem() }}</span>
                    dari 
                    <span class="font-semibold text-slate-600">{{ $listings->total() }}</span>
                    kos ditemukan
                </p>

                {{-- Pagination Buttons --}}
                <div class="flex items-center gap-1.5">

                    {{-- Prev --}}
                    @if($listings->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed text-sm">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $listings->previousPageUrl() }}" onclick="window.dispatchEvent(new CustomEvent('set-loading'))" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all text-sm shadow-sm">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $currentPage = $listings->currentPage();
                        $lastPage    = $listings->lastPage();
                        $window      = 2; // pages shown on each side of current
                        $start       = max(1, $currentPage - $window);
                        $end         = min($lastPage, $currentPage + $window);
                    @endphp

                    {{-- First page + ellipsis --}}
                    @if($start > 1)
                        <a href="{{ $listings->url(1) }}" onclick="window.dispatchEvent(new CustomEvent('set-loading'))" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm">1</a>
                        @if($start > 2)
                            <span class="w-9 h-9 flex items-center justify-center text-slate-300 text-xs">···</span>
                        @endif
                    @endif

                    {{-- Page window --}}
                    @for($page = $start; $page <= $end; $page++)
                        @if($page === $currentPage)
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-200">{{ $page }}</span>
                        @else
                            <a href="{{ $listings->url($page) }}" onclick="window.dispatchEvent(new CustomEvent('set-loading'))" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm">{{ $page }}</a>
                        @endif
                    @endfor

                    {{-- Ellipsis + last page --}}
                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)
                            <span class="w-9 h-9 flex items-center justify-center text-slate-300 text-xs">···</span>
                        @endif
                        <a href="{{ $listings->url($lastPage) }}" onclick="window.dispatchEvent(new CustomEvent('set-loading'))" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm">{{ $lastPage }}</a>
                    @endif

                    {{-- Next --}}
                    @if($listings->hasMorePages())
                        <a href="{{ $listings->nextPageUrl() }}" onclick="window.dispatchEvent(new CustomEvent('set-loading'))" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all text-sm shadow-sm">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-100 text-slate-300 cursor-not-allowed text-sm">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                    @endif

                </div>
            @endif

            <!-- Hidden element for dynamic map listings coordinates data -->
            <div id="map-listings-data" class="hidden" data-listings="{{ json_encode($mapListings) }}"></div>
            </div>

        </div>
    </div>
</div>

<!-- LEAFLET MAP SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Map
        const map = L.map('search-map', { preferCanvas: true });
        window.searchMap = map; // Expose globally for Alpine resize sync

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Initialize Marker Group
        window.searchMarkerGroup = L.layerGroup().addTo(map);

        // Global Marker Update Function
        window.updateMapMarkers = function (locations) {
            if (!window.searchMap || !window.searchMarkerGroup) return;

            // Clear existing markers
            window.searchMarkerGroup.clearLayers();

            if (locations.length > 0) {
                const bounds = [];
                locations.forEach(function (loc) {
                    const latVal = typeof loc.lat !== 'undefined' ? loc.lat : loc.latitude;
                    const lngVal = typeof loc.lng !== 'undefined' ? loc.lng : loc.longitude;

                    if (latVal && lngVal) {
                        const priceText = typeof loc.price === 'number'
                            ? 'Rp ' + new Intl.NumberFormat('id-ID').format(loc.price) + '/bln'
                            : loc.price;

                        const showUrl = loc.url || `/kos/${loc.slug}`;

                        const popupContent = `
                            <div class="p-2 space-y-1.5" style="min-width: 140px;">
                                <h4 class="text-xs font-bold text-slate-800 leading-snug">${loc.title}</h4>
                                <p class="text-[10px] text-emerald-600 font-bold">${priceText}</p>
                                <a href="${showUrl}" class="text-[9px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-2 py-1 rounded block text-center mt-1 select-none">Buka Kos</a>
                            </div>
                        `;

                        const marker = L.marker([latVal, lngVal]).bindPopup(popupContent);
                        window.searchMarkerGroup.addLayer(marker);
                        bounds.push([latVal, lngVal]);
                    }
                });

                // Adjust view to fit all markers beautifully
                if (bounds.length === 1) {
                    window.searchMap.setView(bounds[0], 14);
                } else if (bounds.length > 1) {
                    window.searchMap.fitBounds(bounds, { padding: [45, 45], maxZoom: 15 });
                } else {
                    window.searchMap.setView([-2.5489, 118.0149], 5);
                }
            } else {
                // Default view centered in Indonesia if no listings are found
                window.searchMap.setView([-2.5489, 118.0149], 5);
            }
        };

        // Render initial markers
        const initialLocations = [
            @foreach($mapListings as $item)
                @if($item->latitude && $item->longitude)
                {
                    title: "{{ $item->title }}",
                    price: {{ $item->price }},
                    slug: "{{ $item->slug }}",
                    lat: {{ $item->latitude }},
                    lng: {{ $item->longitude }}
                },
                @endif
            @endforeach
        ];

        window.updateMapMarkers(initialLocations);

        // Trigger map resize if loaded
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });
</script>

<!-- ALPINE SEARCH AJAX CONTROLLER -->
<script>
    function searchPage() {
        return {
            mapToggle: false,
            filterDrawer: false,
            isLoading: false,

            init() {
                // Watch mapToggle for invalidateSize
                this.$watch('mapToggle', value => {
                    if (value) {
                        this.$nextTick(() => {
                            setTimeout(() => { if (window.searchMap) { window.searchMap.invalidateSize({ animate: true }); } }, 50);
                            setTimeout(() => { if (window.searchMap) { window.searchMap.invalidateSize({ animate: true }); } }, 150);
                            setTimeout(() => { if (window.searchMap) { window.searchMap.invalidateSize({ animate: true }); } }, 350);
                        });
                    }
                });

                // Bind filters on initial load
                this.bindFilters();

                // Intercept pagination clicks dynamically
                this.$nextTick(() => {
                    const container = document.getElementById('search-results-container');
                    if (container) {
                        container.addEventListener('click', (e) => {
                            const anchor = e.target.closest('a');
                            if (anchor && anchor.href && anchor.href.includes('/search')) {
                                e.preventDefault();

                                const targetUrl = anchor.href;
                                window.history.pushState({}, '', targetUrl);
                                this.fetchUrl(targetUrl);

                                // Smooth scroll
                                const scrollTarget = document.getElementById('search-results-container');
                                if (scrollTarget) {
                                    scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            }
                        });
                    }
                });
            },

            bindFilters() {
                const forms = document.querySelectorAll('form[action*="/search"], form[action*="search"]');
                forms.forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.fetchResults(form);
                    });

                    // Override submit method for programmatically triggered submissions (e.g. this.form.submit())
                    form.submit = () => {
                        this.fetchResults(form);
                    };
                });
            },

            fetchResults(form) {
                const formData = new FormData(form);
                const params = new URLSearchParams();

                for (const [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }

                const baseUrl = form.getAttribute('action') || '{{ route("search") }}';
                const queryString = params.toString();
                const url = queryString ? `${baseUrl}?${queryString}` : baseUrl;

                window.history.pushState({}, '', url);
                this.fetchUrl(url);
            },

            resetFilters() {
                const url = '{{ route("search") }}';
                window.history.pushState({}, '', url);
                this.fetchUrl(url);
            },

            fetchUrl(url) {
                this.isLoading = true;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Replace results container
                    const newResults = doc.getElementById('search-results-container');
                    const oldResults = document.getElementById('search-results-container');
                    if (newResults && oldResults) {
                        oldResults.innerHTML = newResults.innerHTML;
                    }

                    // 2. Replace desktop filter container
                    const newDesktopFilter = doc.getElementById('desktop-filter-container');
                    const oldDesktopFilter = document.getElementById('desktop-filter-container');
                    if (newDesktopFilter && oldDesktopFilter) {
                        oldDesktopFilter.innerHTML = newDesktopFilter.innerHTML;
                    }

                    // 3. Replace mobile filter container
                    const newMobileFilter = doc.getElementById('mobile-filter-container');
                    const oldMobileFilter = document.getElementById('mobile-filter-container');
                    if (newMobileFilter && oldMobileFilter) {
                        oldMobileFilter.innerHTML = newMobileFilter.innerHTML;
                    }

                    // 4. Update map markers
                    const mapDataEl = doc.getElementById('map-listings-data');
                    if (mapDataEl) {
                        const listingsData = JSON.parse(mapDataEl.getAttribute('data-listings'));
                        if (window.updateMapMarkers) {
                            window.updateMapMarkers(listingsData);
                        }
                    }

                    // 5. Re-bind search form listeners
                    this.bindFilters();

                    this.isLoading = false;
                })
                .catch(err => {
                    console.error('Error fetching search results:', err);
                    this.isLoading = false;
                });
            }
        };
    }
</script>
@endsection
