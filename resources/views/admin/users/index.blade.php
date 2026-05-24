@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna - KosinAja')
@section('header_title', 'Kelola Semua Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna Sistem</h2>
            <p class="text-xs text-slate-400 font-medium">Pantau akun renter/owner, ubah status verifikasi, atau bekukan akun yang melanggar ketentuan</p>
        </div>
        <div class="mt-4 sm:mt-0 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200/60 text-slate-600 text-xs font-semibold flex items-center space-x-1.5">
            <i class="fa-solid fa-users text-emerald-500"></i>
            <span>Total: {{ $users->total() }} Akun</span>
        </div>
    </div>

    <!-- Advanced Search & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="relative md:col-span-2">
                <label for="search" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Cari Pengguna</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, email, atau nomor HP..." 
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all placeholder:text-slate-400 text-slate-700 bg-slate-50/50">
                </div>
            </div>

            <!-- Filter Role -->
            <div>
                <label for="role" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Peran (Role)</label>
                <select name="role" id="role" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-slate-600 bg-slate-50/50">
                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua Peran</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Renter (Penyewa)</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner (Pemilik)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Filter Verification -->
            <div class="flex items-end space-x-2">
                <div class="flex-grow">
                    <label for="verification" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Verifikasi</label>
                    <select name="verification" id="verification" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-slate-600 bg-slate-50/50">
                        <option value="all" {{ request('verification') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Belum Verif</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-1.5">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/10 flex items-center h-[34px]">
                        <i class="fa-solid fa-filter mr-1.5"></i>Filter
                    </button>
                    @if(request()->anyFilled(['search', 'role', 'verification']))
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center h-[34px]" title="Reset Filter">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Users Board Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/50">
                        <th class="py-3.5 px-5">Profil Pengguna</th>
                        <th class="py-3.5 px-5">Peran & Verifikasi</th>
                        <th class="py-3.5 px-5 text-center">Properti Owned</th>
                        <th class="py-3.5 px-5 text-right">Volume Transaksi</th>
                        <th class="py-3.5 px-5">Tanggal Bergabung</th>
                        <th class="py-3.5 px-5 text-right">Tindakan Kepatuhan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            
                            <!-- Profil Pengguna -->
                            <td class="py-4 px-5">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs overflow-hidden shadow-sm flex-shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm flex items-center">
                                            {{ $user->name }}
                                            @if($user->is_verified)
                                                <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-xs" title="Verified Owner"></i>
                                            @endif
                                            <span class="ml-1.5 text-[8px] bg-slate-100 px-1 py-0.5 rounded text-slate-400">UID: #{{ $user->id }}</span>
                                        </p>
                                        <p class="text-[10px] text-slate-500 font-medium mt-0.5 flex items-center">
                                            <i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $user->email }}
                                        </p>
                                        <p class="text-[10px] text-slate-500 font-medium mt-0.5 flex items-center">
                                            <i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $user->phone ?? 'Tidak ada' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Peran & Verifikasi -->
                            <td class="py-4 px-5">
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : ($user->role === 'owner' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-sky-50 text-sky-700 border border-sky-100') }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                                <div class="mt-1.5">
                                    @if($user->is_verified)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <i class="fa-solid fa-circle-check mr-1 text-[9px]"></i>Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-400 border border-slate-200">
                                            <i class="fa-solid fa-circle-minus mr-1 text-[9px]"></i>Belum Verif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Properti Owned -->
                            <td class="py-4 px-5 text-center">
                                @if($user->role === 'owner')
                                    <span class="font-extrabold text-slate-800 text-xs">{{ $user->listings_count }} Kos</span>
                                    <a href="{{ route('admin.listings.index') }}?search={{ urlencode($user->name) }}" class="block text-[8px] font-bold text-emerald-600 hover:text-emerald-700 mt-1 uppercase tracking-wide">
                                        Lihat Kos <i class="fa-solid fa-arrow-up-right-from-square ml-0.5 text-[7px]"></i>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-semibold italic text-xs">-</span>
                                @endif
                            </td>
                            
                            <!-- Volume Transaksi -->
                            <td class="py-4 px-5 text-right">
                                <span class="font-extrabold text-slate-800 text-xs">Rp {{ number_format($user->financial_volume, 0, ',', '.') }}</span>
                                @if($user->role === 'owner')
                                    <span class="block text-[8px] font-bold text-emerald-600 uppercase tracking-wide mt-1">Pendapatan</span>
                                @elseif($user->role === 'user')
                                    <span class="block text-[8px] font-bold text-indigo-500 uppercase tracking-wide mt-1">Pengeluaran</span>
                                @else
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wide mt-1">Superadmin</span>
                                @endif
                            </td>
                            
                            <!-- Tanggal Bergabung -->
                            <td class="py-4 px-5">
                                <div class="text-slate-500 font-semibold">
                                    <p class="text-xs">{{ $user->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</p>
                                    <p class="text-[9px] text-slate-400 mt-0.5">{{ $user->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
                                </div>
                            </td>
                            
                            <!-- Tindakan Kepatuhan -->
                            <td class="py-4 px-5 text-right">
                                @if($user->role !== 'admin')
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <!-- Suspend / Activate Toggle -->
                                        @if($user->is_verified)
                                            <!-- Suspend action -->
                                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" class="inline confirm-form"
                                                  data-confirm-title="Tangguhkan Akun?"
                                                  data-confirm-text="Apakah Anda yakin ingin membekukan (suspend) akun '{{ $user->name }}'? Seluruh properti kos milik owner ini (jika ada) akan dinonaktifkan."
                                                  data-confirm-button="Ya, Tangguhkan"
                                                  data-confirm-color="#ef4444"
                                                  data-confirm-icon="warning">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-red-100 hover:bg-red-50 text-red-600 text-[10px] font-bold transition-all shadow-sm" title="Bekukan akun dan suspend semua iklan kos">
                                                    Suspend Akun
                                                </button>
                                            </form>
                                        @else
                                            <!-- Activate action -->
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="inline confirm-form"
                                                  data-confirm-title="Aktifkan Kembali Akun?"
                                                  data-confirm-text="Apakah Anda yakin ingin mengaktifkan kembali akun '{{ $user->name }}'? Properti kos milik owner ini akan aktif kembali."
                                                  data-confirm-button="Ya, Aktifkan"
                                                  data-confirm-color="#10b981"
                                                  data-confirm-icon="question">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-100 text-[10px] font-bold transition-all shadow-sm" title="Aktifkan kembali akun dan iklannya">
                                                    Aktifkan Akun
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Send Chat -->
                                        <a href="{{ route('chats.conversation', $user->id) }}" class="w-8 h-8 rounded-lg border border-slate-100 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors" title="Kirim Chat Peringatan">
                                            <i class="fa-regular fa-comment text-xs"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-semibold italic">Akses Superadmin</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100 shadow-inner">
                                    <i class="fa-solid fa-users text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Pengguna Cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Tidak ada renter/owner yang sesuai dengan kriteria pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
