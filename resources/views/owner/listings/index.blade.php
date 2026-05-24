@extends('layouts.dashboard')

@section('title', 'Kos Saya - KosinAja')
@section('header_title', 'Kelola Properti Kos')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions & Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-grow">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Properti Kos Anda</h2>
                <p class="text-[11px] text-slate-400">Total {{ $listings->count() }} kos terdaftar atas nama Anda</p>
            </div>
            
            <!-- Search Form -->
            <form action="{{ route('owner.listings.index') }}" method="GET" class="flex-grow max-w-md w-full sm:ml-4">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Cari nama kos, kota, atau alamat..." 
                           class="w-full h-10 pl-10 pr-10 rounded-xl border border-slate-200/80 bg-slate-50/50 text-xs font-medium focus:outline-none focus:border-emerald-500 focus:bg-white transition-all text-slate-700 placeholder-slate-400">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    @if(!empty($search))
                        <a href="{{ route('owner.listings.index') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
        
        <a href="{{ route('owner.listings.create') }}" class="px-4 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center justify-center shadow-md shadow-emerald-600/10 flex-shrink-0">
            <i class="fa-solid fa-circle-plus mr-1.5"></i>Pasang Kos Baru
        </a>
    </div>

    @if(!auth()->user()->is_verified)
        <!-- Premium Identity Verification Mandatory Warning Banner -->
        <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-200/60 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="w-11 h-11 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg flex-shrink-0 shadow-md shadow-amber-500/15">
                    <i class="fa-solid fa-shield-halved animate-pulse"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-800 text-sm">Verifikasi Identitas Akun Diperlukan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-2xl">
                        Demi menjaga ekosistem KosinAja bebas dari penipuan (Anti-Scam), pemilik kos <span class="font-semibold text-slate-700">wajib melengkapi verifikasi identitas resmi (KTP & Selfie Wajah)</span> sebelum diperbolehkan menambahkan atau mengiklankan kos baru.
                    </p>
                </div>
            </div>
            <a href="{{ route('owner.verification.index') }}" class="px-4 h-10 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition-all shadow-md shadow-amber-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-id-card-clip mr-1.5"></i>Lengkapi Verifikasi Akun
            </a>
        </div>
    @endif

    <!-- Listings Board Card -->
    <!-- Desktop Table View (Hidden on Mobile/Tablet) -->
    <div class="hidden lg:block bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden w-full">
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/50">
                    <th class="py-3 px-5 w-5/12">Info Properti</th>
                    <th class="py-3 px-5 w-2/12">Harga Sewa</th>
                    <th class="py-3 px-5 w-2/12">Verifikasi</th>
                    <th class="py-3 px-5 w-2/12">Ketersediaan</th>
                    <th class="py-3 px-5 w-1/12 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                @forelse($listings as $listing)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <!-- Info Properti (Combines: Thumb, Title, Verified badge, Gender, Location, Views) -->
                        <td class="py-4 px-5 align-middle">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 relative shadow-sm">
                                    @if($listing->images->isNotEmpty())
                                        <img src="{{ asset($listing->images->first()->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-50"><i class="fa-solid fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1 space-y-1">
                                    <div class="flex items-center space-x-1.5">
                                        <p class="font-bold text-slate-800 truncate text-sm leading-snug" title="{{ $listing->title }}">{{ $listing->title }}</p>
                                        @if($listing->is_verified)
                                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs flex-shrink-0" title="Verified Kos"></i>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px] text-slate-400">
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded-full font-bold uppercase tracking-wider {{ $listing->gender_type === 'putra' ? 'bg-sky-50 text-sky-700 border border-sky-100' : ($listing->gender_type === 'putri' ? 'bg-pink-50 text-pink-700 border border-pink-100' : 'bg-purple-50 text-purple-700 border border-purple-100') }} flex-shrink-0">
                                            {{ $listing->gender_type }}
                                        </span>
                                        <span class="flex-shrink-0">•</span>
                                        <span class="truncate max-w-[90px]" title="{{ $listing->city }}"><i class="fa-solid fa-location-dot mr-1 flex-shrink-0"></i>{{ $listing->city }}</span>
                                        <span class="flex-shrink-0">•</span>
                                        <span class="flex-shrink-0"><i class="fa-regular fa-eye mr-1 flex-shrink-0"></i>{{ $listing->views }}x</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Harga Sewa -->
                        <td class="py-4 px-5 align-middle font-bold text-slate-800 whitespace-nowrap">
                            Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="text-[9px] text-slate-400 font-normal">/bln</span>
                        </td>
                        
                        <!-- Status Verifikasi -->
                        <td class="py-4 px-5 align-middle whitespace-nowrap">
                            @if($listing->is_verified)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i>Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    <i class="fa-solid fa-clock-rotate-left mr-1 text-amber-500"></i>Review
                                </span>
                            @endif
                        </td>
                        
                        <!-- Status Ketersediaan (On/Off Toggle) -->
                        <td class="py-4 px-5 align-middle whitespace-nowrap">
                            @if($listing->status === 'suspended')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-red-50 text-red-700 border border-red-100" title="Properti ini ditangguhkan sementara karena keluhan scam/keamanan.">
                                    <i class="fa-solid fa-circle-exclamation mr-1 text-red-500"></i>Suspended
                                </span>
                            @else
                                <form action="{{ route('owner.listings.toggle-status', $listing->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider transition-all border {{ $listing->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100 hover:border-emerald-400' : 'bg-slate-100 text-slate-500 border-slate-300 hover:bg-slate-200 hover:border-slate-400' }}"
                                            title="Klik untuk mengubah ketersediaan kos (Tersedia / Penuh)">
                                        <i class="fa-solid {{ $listing->status === 'active' ? 'fa-toggle-on text-emerald-600 text-sm' : 'fa-toggle-off text-slate-400 text-sm' }} -ml-0.5"></i>
                                        <span>{{ $listing->status === 'active' ? 'Tersedia' : 'Penuh' }}</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                        
                        <!-- Aksi -->
                        <td class="py-4 px-5 align-middle text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" 
                                   class="w-8 h-8 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors" title="Lihat Halaman Publik">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                                <a href="{{ route('owner.listings.edit', $listing->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 transition-colors" title="Ubah Detail">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </a>
                                <form action="{{ route('owner.listings.destroy', $listing->id) }}" method="POST" class="inline confirm-form"
                                      data-confirm-title="Hapus Iklan Kos Permanen?"
                                      data-confirm-text="Apakah Anda yakin ingin menghapus properti kos '{{ $listing->title }}' secara permanen?"
                                      data-confirm-button="Ya, Hapus Permanen"
                                      data-confirm-color="#ef4444"
                                      data-confirm-icon="warning">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg border border-red-100 hover:bg-red-50 text-red-500 flex items-center justify-center transition-colors" title="Hapus Kos Permanen">
                                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-slate-400">
                            @if(!empty($search))
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Hasil Pencarian Tidak Ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Tidak ada kos yang cocok dengan kata kunci "{{ $search }}".</p>
                                <a href="{{ route('owner.listings.index') }}" class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl mt-4 transition-colors">
                                    <i class="fa-solid fa-rotate-left mr-1.5"></i>Reset Pencarian
                                </a>
                            @else
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-hotel text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Belum Ada Properti Kos</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Anda belum mendaftarkan kos apapun. Silakan buat kos baru.</p>
                                <a href="{{ route('owner.listings.create') }}" class="inline-flex items-center text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-xl mt-4 transition-colors shadow-md shadow-emerald-600/10">
                                    <i class="fa-solid fa-circle-plus mr-1.5"></i>Pasang Kos Pertama Anda
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile/Tablet Card View (Hidden on Desktop) -->
    <div class="block lg:hidden space-y-4">
        @forelse($listings as $listing)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm space-y-3.5 flex flex-col">
                <!-- Header: Thumbnail + Info -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 relative shadow-sm">
                        @if($listing->images->isNotEmpty())
                            <img src="{{ asset($listing->images->first()->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-image"></i></div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-grow">
                        <div class="flex items-center space-x-1.5">
                            <h3 class="font-bold text-slate-800 truncate text-xs">{{ $listing->title }}</h3>
                            @if($listing->is_verified)
                                <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]" title="Verified Kos"></i>
                            @endif
                        </div>
                        <p class="text-[9px] text-slate-400 mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i>{{ $listing->city }}</p>
                    </div>
                </div>

                <!-- Specs Grid -->
                <div class="grid grid-cols-2 gap-3 py-3 border-t border-b border-slate-50 text-[10px] text-slate-500">
                    <div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold block">Tipe Kos</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider {{ $listing->gender_type === 'putra' ? 'bg-sky-50 text-sky-700' : ($listing->gender_type === 'putri' ? 'bg-pink-50 text-pink-700' : 'bg-purple-50 text-purple-700') }} mt-1">
                            {{ $listing->gender_type }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold block">Harga Sewa</span>
                        <span class="font-bold text-slate-800 mt-1 block">Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="font-normal text-slate-400">/bln</span></span>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold block">Verifikasi</span>
                        <div class="mt-1">
                            @if($listing->is_verified)
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Verified</span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">Review</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold block">Ketersediaan</span>
                        <div class="mt-1">
                            @if($listing->status === 'suspended')
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-semibold bg-red-50 text-red-700 border border-red-100">Suspended</span>
                            @else
                                <form action="{{ route('owner.listings.toggle-status', $listing->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider transition-all border {{ $listing->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                        <i class="fa-solid {{ $listing->status === 'active' ? 'fa-toggle-on text-emerald-600' : 'fa-toggle-off text-slate-400' }}"></i>
                                        <span>{{ $listing->status === 'active' ? 'Tersedia' : 'Penuh' }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer details -->
                <div class="flex items-center justify-between text-[10px] pt-1">
                    <span class="text-slate-400"><i class="fa-regular fa-eye mr-1"></i>{{ $listing->views }}x dilihat</span>
                    
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" class="w-7 h-7 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors" title="Lihat">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>
                        <a href="{{ route('owner.listings.edit', $listing->id) }}" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 transition-colors" title="Edit">
                            <i class="fa-solid fa-pen text-[9px]"></i>
                        </a>
                        <form action="{{ route('owner.listings.destroy', $listing->id) }}" method="POST" class="inline confirm-form"
                              data-confirm-title="Hapus Iklan Kos Permanen?"
                              data-confirm-text="Apakah Anda yakin ingin menghapus properti kos '{{ $listing->title }}' secara permanen?"
                              data-confirm-button="Ya, Hapus"
                              data-confirm-color="#ef4444"
                              data-confirm-icon="warning">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 rounded-lg border border-red-100 hover:bg-red-50 text-red-500 flex items-center justify-center transition-colors" title="Hapus">
                                <i class="fa-regular fa-trash-can text-[9px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center text-slate-400">
                @if(!empty($search))
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-2 border border-slate-100">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </div>
                    <p class="font-bold text-slate-700 text-xs">Hasil Pencarian Tidak Ditemukan</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tidak cocok dengan kata kunci "{{ $search }}".</p>
                    <a href="{{ route('owner.listings.index') }}" class="inline-flex items-center text-[10px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl mt-3 transition-colors">
                        <i class="fa-solid fa-rotate-left mr-1.5"></i>Reset
                    </a>
                @else
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-2 border border-slate-100">
                        <i class="fa-solid fa-hotel text-lg"></i>
                    </div>
                    <p class="font-bold text-slate-700 text-xs">Belum Ada Properti Kos</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Silakan pasang iklan kos baru.</p>
                    <a href="{{ route('owner.listings.create') }}" class="inline-flex items-center text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 rounded-xl mt-3 transition-colors shadow-md shadow-emerald-600/10">
                        <i class="fa-solid fa-circle-plus mr-1.5"></i>Pasang Kos Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>
</div>
@endsection
