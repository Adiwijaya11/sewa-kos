@extends('layouts.dashboard')

@section('title', 'Moderasi Kos - KosinAja')
@section('header_title', 'Kelola Moderasi Kos')

@section('content')
<style>
    .scroll-table::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .scroll-table::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 99px;
    }
    .scroll-table::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 99px;
    }
    .scroll-table::-webkit-scrollbar-thumb:hover {
        background: #10b981;
        border-radius: 99px;
    }
    .scroll-table {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }
</style>

<div class="space-y-6" x-data="{ 
    modalOpen: false, 
    activeListing: {
        id: null,
        title: '',
        price: 0,
        city: '',
        province: '',
        address: '',
        gender_type: '',
        room_size: '',
        max_people: 1,
        total_rooms: 0,
        available_rooms: 0,
        description: '',
        status: '',
        is_verified: false,
        owner: { name: '', email: '', phone: '', is_verified: false },
        facilities: [],
        images: []
    }
}">
    <!-- Header Page Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-800">Daftar Semua Kos Terdaftar</h2>
            <p class="text-xs text-slate-400 mt-0.5 hidden sm:block">Verifikasi status kos, inspeksi kelayakan kamar, atau tangguhkan listing dummy yang mencurigakan</p>
        </div>
        <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full self-start sm:self-center">
            {{ $listings->total() }} listing terdaftar
        </span>
    </div>

    <!-- Search & Filter Bar -->
    <form action="{{ route('admin.listings.index') }}" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full">
        <div class="relative flex-1 max-w-sm">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kos, kota, owner..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition placeholder-slate-400">
        </div>
        <select name="status" onchange="this.form.submit()" class="px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition text-slate-600">
            <option value="">Semua Status</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>✓ Terverifikasi</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Belum Verifikasi</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Tayang</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-500/10">
            Cari & Filter
        </button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.listings.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                Reset
            </a>
        @endif
        <div class="sm:ml-auto flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-2 rounded-xl whitespace-nowrap">
                Halaman {{ $listings->currentPage() }} dari {{ $listings->lastPage() }}
            </span>
            <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-2 rounded-xl whitespace-nowrap">
                Total {{ $listings->total() }} kos
            </span>
        </div>
    </form>

    <!-- Listings Board Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col" style="height: calc(100vh - 260px); min-height: 400px;">
        <div class="scroll-table overflow-x-auto overflow-y-auto flex-1 rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10">
                    <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50">
                        <th class="py-3 px-5">Info Properti</th>
                        <th class="py-3 px-5">Tipe</th>
                        <th class="py-3 px-5">Pemilik (Owner)</th>
                        <th class="py-3 px-5">Harga</th>
                        <th class="py-3 px-5">Status Moderasi</th>
                        <th class="py-3 px-5 text-right">Tindakan Moderasi</th>
                    </tr>
                </thead>
                <tbody id="listingTableBody" class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($listings as $listing)
                        <tr class="listing-row hover:bg-slate-50/30 transition-colors"
                            data-search="{{ strtolower($listing->title . ' ' . $listing->city . ' ' . $listing->province . ' ' . $listing->owner->name . ' ' . $listing->owner->email . ' ' . $listing->gender_type) }}"
                            data-verified="{{ $listing->is_verified ? 'verified' : 'pending' }}"
                            data-status="{{ $listing->status }}">
                            
                            <!-- Info Properti -->
                            <td class="py-4 px-5">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 relative shadow-sm">
                                        @if($listing->images->isNotEmpty())
                                            <img src="{{ asset($listing->images->first()->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-50"><i class="fa-solid fa-hotel"></i></div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-1.5">
                                            <p class="font-bold text-slate-800 truncate max-w-[200px]">{{ $listing->title }}</p>
                                            @if($listing->is_verified)
                                                <i class="fa-solid fa-circle-check text-emerald-500" title="Verified Kos"></i>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[200px]"><i class="fa-solid fa-location-dot mr-1 text-slate-300"></i>{{ $listing->city }}, {{ $listing->province }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Tipe -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $listing->gender_type === 'putra' ? 'bg-sky-50 text-sky-700 border border-sky-100' : ($listing->gender_type === 'putri' ? 'bg-pink-50 text-pink-700 border border-pink-100' : 'bg-purple-50 text-purple-700 border border-purple-100') }}">
                                    {{ $listing->gender_type }}
                                </span>
                            </td>
                            
                            <!-- Pemilik -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-semibold text-slate-800 flex items-center">
                                        {{ $listing->owner->name }}
                                        @if($listing->owner->is_verified)
                                            <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $listing->owner->email }}</p>
                                </div>
                            </td>
                            
                            <!-- Harga -->
                            <td class="py-4 px-5 font-bold text-slate-800">
                                Rp {{ number_format($listing->price, 0, ',', '.') }}<span class="text-[9px] text-slate-400 font-normal">/bln</span>
                            </td>
                            
                            <!-- Status Moderasi -->
                            <td class="py-4 px-5">
                                <div class="flex flex-col space-y-1">
                                    <!-- Verified -->
                                    <div>
                                        @if($listing->is_verified)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <i class="fa-solid fa-circle-check mr-1"></i>Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                                <i class="fa-solid fa-clock-rotate-left mr-1"></i>Belum Verifikasi
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Status -->
                                    <div>
                                        @if($listing->status === 'suspended')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-red-50 text-red-700 border border-red-100">
                                                <i class="fa-solid fa-circle-exclamation mr-1"></i>Ditangguhkan
                                            </span>
                                        @elseif($listing->status === 'inactive')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                <i class="fa-solid fa-circle-pause mr-1"></i>Tidak Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                                <i class="fa-solid fa-circle-play mr-1"></i>Aktif Tayang
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Tindakan Moderasi -->
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Pop-up Detail Inspeksi Modal Trigger -->
                                    <button type="button" 
                                            @click="activeListing = {{ json_encode([
                                                'id' => $listing->id,
                                                'title' => $listing->title,
                                                'price' => (int) $listing->price,
                                                'city' => $listing->city,
                                                'province' => $listing->province,
                                                'address' => $listing->address,
                                                'gender_type' => $listing->gender_type,
                                                'room_size' => $listing->room_size,
                                                'max_people' => (int) $listing->max_people,
                                                'total_rooms' => (int) $listing->total_rooms,
                                                'available_rooms' => (int) $listing->available_rooms,
                                                'description' => $listing->description,
                                                'status' => $listing->status,
                                                'is_verified' => (bool) $listing->is_verified,
                                                'owner' => [
                                                    'name' => $listing->owner->name,
                                                    'email' => $listing->owner->email,
                                                    'phone' => $listing->owner->phone ?? 'Tidak ada',
                                                    'is_verified' => (bool) $listing->owner->is_verified
                                                ],
                                                'facilities' => $listing->facilities->pluck('name')->toArray(),
                                                'images' => $listing->images->pluck('image')->map(fn($img) => asset($img))->toArray()
                                            ]) }}; modalOpen = true"
                                            class="w-8 h-8 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-600 hover:text-emerald-600 transition-colors shadow-sm" title="Buka Detail Inspeksi Properti">
                                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                    </button>

                                    <!-- Toggle Verification -->
                                    <form action="{{ route('admin.listings.verify', $listing->id) }}" method="POST" class="inline confirm-form"
                                          data-confirm-title="{{ $listing->is_verified ? 'Batal Verifikasi Kos?' : 'Beri Verifikasi Kos?' }}"
                                          data-confirm-text="{{ $listing->is_verified ? 'Apakah Anda yakin ingin membatalkan status terverifikasi properti kos \''.$listing->title.'\'?' : 'Apakah Anda yakin ingin memberikan lencana Terverifikasi (Verified badge) ke properti kos \''.$listing->title.'\'?' }}"
                                          data-confirm-button="Ya, Ubah Status"
                                          data-confirm-color="{{ $listing->is_verified ? '#f59e0b' : '#10b981' }}"
                                          data-confirm-icon="question">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg border {{ $listing->is_verified ? 'border-amber-100 hover:bg-amber-50 text-amber-600' : 'bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-100' }} text-[10px] font-bold transition-all shadow-sm">
                                            @if($listing->is_verified)
                                                Batal Verif
                                            @else
                                                Beri Verif
                                            @endif
                                        </button>
                                    </form>

                                    <!-- View detail public -->
                                    <a href="{{ route('listings.show', $listing->slug) }}" target="_blank" 
                                       class="w-8 h-8 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors" title="Lihat detail halaman publik">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                    
                                    <!-- Delete with SweetAlert2 confirmation -->
                                    <form action="{{ route('admin.listings.destroy', $listing->id) }}" method="POST" class="inline confirm-form"
                                          data-confirm-title="Hapus Kos Secara Permanen?"
                                          data-confirm-text="Apakah Anda yakin ingin menghapus properti kos '{{ $listing->title }}' secara permanen dari server database? Tindakan ini tidak dapat dibatalkan!"
                                          data-confirm-button="Ya, Hapus Permanen"
                                          data-confirm-color="#ef4444"
                                          data-confirm-icon="warning">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg border border-red-100 hover:bg-red-50 text-red-500 flex items-center justify-center transition-colors" title="Hapus Kos Administratif">
                                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-hotel text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Properti Kos</p>
                                <p class="text-xs text-slate-400 mt-1">Belum ada kos terdaftar yang diunggah oleh owner.</p>
                            </td>
                        </tr>
                    @endforelse
                    
                    @if($listings->isEmpty() && request()->anyFilled(['search', 'status']))
                    <!-- No results after search -->
                    <tr id="noSearchResult">
                        <td colspan="6" class="py-16 text-center text-slate-400">
                            <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3 border border-slate-100">
                                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                            </div>
                            <p class="font-bold text-slate-600 text-sm">Tidak ditemukan hasil</p>
                            <p class="text-xs text-slate-400 mt-1">Coba kata kunci atau filter yang berbeda</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Server-side pagination controls -->
    <div class="mt-4">
        {{ $listings->links() }}
    </div>

    <!-- Glassmorphic Detail Inspeksi Modal -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto scroll-table flex flex-col" @click.away="modalOpen = false">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-20">
                <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Inspeksi Properti Administratif</span>
                    <h3 class="text-base font-extrabold text-slate-800 leading-tight" x-text="activeListing.title">Detail Kos</h3>
                </div>
                <button @click="modalOpen = false" class="w-8 h-8 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Content Body -->
            <div class="p-6 space-y-6">
                <!-- Main Thumbnail / Image Gallery -->
                <div class="relative h-64 w-full rounded-2xl bg-slate-100 overflow-hidden border border-slate-200">
                    <template x-if="activeListing.images.length > 0">
                        <img :src="activeListing.images[0]" alt="Properti" class="w-full h-full object-cover">
                    </template>
                    <template x-if="activeListing.images.length === 0">
                        <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-50"><i class="fa-solid fa-hotel text-4xl"></i></div>
                    </template>
                    <!-- Gender Badge -->
                    <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-900/80 text-white backdrop-blur-md" x-text="activeListing.gender_type"></span>
                </div>

                <!-- Price, Size, Capacity, and Room Counts Row -->
                <div class="grid grid-cols-2 sm:grid-cols-5 p-4 bg-slate-50/50 rounded-2xl border border-slate-100 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Harga Sewa</span>
                        <span class="text-xs font-extrabold text-emerald-600">Rp <span x-text="new Intl.NumberFormat('id-ID').format(activeListing.price)"></span> <span class="text-[9px] text-slate-400 font-normal">/ bln</span></span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Dimensi Kamar</span>
                        <span class="text-xs font-extrabold text-slate-800" x-text="activeListing.room_size || 'Tidak ditentukan'"></span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Maks. Penghuni</span>
                        <span class="text-xs font-extrabold text-slate-800" x-text="(activeListing.max_people || 1) + ' orang'"></span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Kamar</span>
                        <span class="text-xs font-extrabold text-slate-800" x-text="(activeListing.total_rooms || 0) + ' kamar'"></span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Kamar Tersedia</span>
                        <span class="text-xs font-extrabold" :class="(activeListing.available_rooms || 0) == 0 ? 'text-red-500' : 'text-slate-800'" x-text="(activeListing.available_rooms || 0) + ' kamar'"></span>
                    </div>
                </div>

                <!-- Description & Details -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Deskripsi Properti</span>
                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50/20 p-4 rounded-xl border border-slate-100/50 whitespace-pre-line" x-text="activeListing.description || 'Tidak ada deskripsi.'"></p>
                </div>

                <!-- Address -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat & Lokasi</span>
                    <p class="text-xs text-slate-600 leading-relaxed"><i class="fa-solid fa-location-dot text-slate-400 mr-1.5"></i><span class="font-semibold text-slate-800" x-text="activeListing.address"></span>, <span x-text="activeListing.city"></span>, <span x-text="activeListing.province"></span></p>
                </div>

                <!-- Facilities -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Fasilitas Tersedia</span>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="facility in activeListing.facilities" :key="facility">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/50">
                                <i class="fa-solid fa-circle-check text-emerald-500 mr-1 text-[9px]"></i><span x-text="facility"></span>
                            </span>
                        </template>
                        <template x-if="activeListing.facilities.length === 0">
                            <p class="text-xs text-slate-400 italic">Tidak ada fasilitas terdaftar.</p>
                        </template>
                    </div>
                </div>

                <!-- Owner Details -->
                <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Informasi Pemilik (Owner)</span>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-800 flex items-center">
                                <span x-text="activeListing.owner.name"></span>
                                <template x-if="activeListing.owner.is_verified">
                                    <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-xs" title="Owner Terverifikasi"></i>
                                </template>
                            </p>
                            <p class="text-[10px] text-slate-400" x-text="activeListing.owner.email"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Telepon / WhatsApp</p>
                            <p class="text-xs font-bold text-slate-700" x-text="activeListing.owner.phone"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="p-6 border-t border-slate-100 flex items-center justify-between sticky bottom-0 bg-white z-20">
                <!-- Left Action: Verif Toggle -->
                <div>
                    <form :action="'/admin/listings/' + activeListing.id + '/verify'" method="POST" class="inline confirm-form"
                          data-confirm-title="Ubah Status Verifikasi?"
                          data-confirm-text="Apakah Anda yakin ingin mengubah status verifikasi properti ini?"
                          data-confirm-button="Ya, Ubah Status"
                          data-confirm-color="#10b981"
                          data-confirm-icon="question">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-all shadow-md flex items-center"
                                :class="activeListing.is_verified ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/10' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/10'">
                            <i class="fa-solid fa-circle-check mr-1.5"></i>
                            <span x-text="activeListing.is_verified ? 'Batalkan Verifikasi' : 'Setujui Verifikasi'"></span>
                        </button>
                    </form>
                </div>

                <!-- Right Actions: Close & Delete -->
                <div class="flex items-center space-x-2">
                    <form :action="'/admin/listings/' + activeListing.id" method="POST" class="inline confirm-form"
                          data-confirm-title="Hapus Kos Secara Permanen?"
                          data-confirm-text="Apakah Anda yakin ingin menghapus properti ini secara permanen dari server database?"
                          data-confirm-button="Ya, Hapus"
                          data-confirm-color="#ef4444"
                          data-confirm-icon="warning">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-red-50 hover:bg-red-500 text-red-500 hover:text-white border border-red-100 text-xs font-bold transition-all flex items-center">
                            <i class="fa-regular fa-trash-can mr-1.5"></i>
                            Hapus Kos
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
