@extends('layouts.app')
@section('title', 'Kos Favorit Saya - KosinAja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-4 sm:pb-5">
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center">
            <i class="fa-solid fa-heart text-emerald-600 mr-2.5 animate-pulse"></i>Favorit Saya
        </h1>
        <p class="text-xs text-slate-500 mt-1 hidden sm:block">Simpan dan pantau kos pilihan Anda untuk memudahkan perbandingan sebelum melakukan pemesanan sewa aman.</p>
    </div>

    <!-- Grid List of Favorites -->
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($favorites as $fav)
                @php
                    $listing = $fav->listing;
                @endphp
                @if($listing)
                    <!-- Property Card -->
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-lg hover:border-slate-200/80 group flex flex-col h-full transition-all duration-300">
                        <div class="relative h-44 overflow-hidden bg-slate-100">
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
                            <div class="text-[10px] text-slate-400 flex items-center">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $listing->city }}, {{ $listing->province }}
                            </div>

                            <h3 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 min-h-[35px]">
                                <a href="{{ route('listings.show', $listing->slug) }}" class="hover:text-emerald-600 transition-all">
                                    {{ $listing->title }}
                                </a>
                            </h3>

                            <!-- Mini facility list icons -->
                            <div class="flex items-center space-x-2 pt-2 border-t border-slate-50 min-h-[30px]">
                                @foreach($listing->facilities->take(3) as $fac)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 text-[10px]" title="{{ $fac->name }}">
                                        <i class="fa-solid fa-{{ $fac->icon }}"></i>
                                    </span>
                                @endforeach
                                @if($listing->facilities->count() > 3)
                                    <span class="text-[9px] font-bold text-slate-400">+{{ $listing->facilities->count() - 3 }} lainnya</span>
                                @endif
                            </div>

                            <div class="flex justify-between items-baseline pt-4 border-t border-slate-100 mt-auto">
                                <span class="text-[10px] text-slate-400">Mulai</span>
                                <span class="text-sm font-black text-emerald-600">Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="text-[10px] font-semibold text-slate-500">/bln</span></span>
                            </div>
                        </div>

                        <!-- Card Footer actions -->
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <!-- Toggle favorite directly in favorites list -->
                            <form action="{{ route('listings.favorite', $listing->id) }}" method="POST" class="inline confirm-form"
                                  data-confirm-title="Hapus dari Favorit?"
                                  data-confirm-text="Kos ini akan dihapus dari daftar favorit Anda."
                                  data-confirm-button="Ya, Hapus"
                                  data-confirm-color="#ef4444"
                                  data-confirm-icon="warning">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center transition-colors">
                                    <i class="fa-solid fa-heart-crack mr-1"></i>Hapus
                                </button>
                            </form>

                            <a href="{{ route('listings.show', $listing->slug) }}" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center">
                                Detail <i class="fa-solid fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-8">
            {{ $favorites->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200 max-w-xl mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-100">
                <i class="fa-solid fa-heart-crack text-3xl"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Belum Ada Kos Favorit</h3>
            <p class="text-xs text-slate-400 mt-1 mb-6 max-w-xs mx-auto">Anda belum menambahkan kos apa pun ke daftar favorit Anda. Temukan kos impian sekarang juga!</p>
            <a href="{{ route('search') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-100 transition-all cursor-pointer">
                <i class="fa-solid fa-magnifying-glass mr-1.5"></i>Cari Kos Sekarang
            </a>
        </div>
    @endif
</div>
@endsection
