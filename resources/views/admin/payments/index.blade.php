@extends('layouts.dashboard')

@section('title', 'Log Transaksi - KosinAja')
@section('header_title', 'Semua Transaksi Escrow Platform')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="space-y-6">
    <!-- Header Page Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Buku Besar Transaksi Escrow Platform</h2>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Total riwayat mutasi dana sewa calon penyewa di rekening bersama KosinAja</p>
        </div>
        <div class="px-3.5 py-2 rounded-xl bg-slate-100 border border-slate-200/50 text-slate-600 text-xs font-semibold flex items-center space-x-1.5 self-start sm:self-center">
            <i class="fa-solid fa-receipt text-emerald-500"></i>
            <span>{{ $payments->count() }} Transaksi Terdaftar</span>
        </div>
    </div>

    <!-- Advanced Search & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Keyword -->
            <div class="relative md:col-span-3">
                <label for="search" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Cari Transaksi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="ID TRX, penyewa, owner, kos..." 
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all placeholder:text-slate-400 text-slate-700 bg-slate-50/50">
                </div>
            </div>

            <!-- Start Date -->
            <div class="md:col-span-3">
                <label for="start_date" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-slate-600 bg-slate-50/50">
            </div>

            <!-- End Date -->
            <div class="md:col-span-3">
                <label for="end_date" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-slate-600 bg-slate-50/50">
            </div>

            <!-- Status Escrow -->
            <div class="md:col-span-2">
                <label for="status" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Status Escrow</label>
                <select name="status" id="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-slate-600 bg-slate-50/50">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Ditahan di Escrow</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Dana Cair ke Pemilik</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-1 flex items-end space-x-1.5 justify-end">
                <button type="submit" class="w-full px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/10 flex items-center justify-center h-[34px]" title="Filter Data">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->anyFilled(['search', 'start_date', 'end_date', 'status']))
                    <a href="{{ route('admin.payments.index') }}" class="w-full px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center h-[34px]" title="Reset Filter">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Payments Card Board -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="max-h-[600px] overflow-y-auto overflow-x-auto custom-scrollbar relative">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="sticky top-0 bg-slate-900 text-slate-200 text-[10px] uppercase font-bold tracking-wider z-10 shadow-sm">
                        <th class="py-3.5 px-5">ID Transaksi</th>
                        <th class="py-3.5 px-5">Penyewa (Renter)</th>
                        <th class="py-3.5 px-5">Kos Properti</th>
                        <th class="py-3.5 px-5">Penerima (Owner)</th>
                        <th class="py-3.5 px-5">Jumlah Dana</th>
                        <th class="py-3.5 px-5">Metode Bayar</th>
                        <th class="py-3.5 px-5">Tanggal Mutasi</th>
                        <th class="py-3.5 px-5">Status Escrow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            
                            <!-- ID Transaksi -->
                            <td class="py-4 px-5">
                                <span class="font-mono text-xs font-semibold text-slate-600 uppercase select-all">{{ $payment->transaction_id }}</span>
                            </td>
                            
                            <!-- Penyewa -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-semibold text-slate-800 leading-none">{{ $payment->user->name }}</p>
                                    <p class="text-[9px] text-slate-400 mt-0.5">{{ $payment->user->email }}</p>
                                </div>
                            </td>
                            
                            <!-- Kos Properti -->
                            <td class="py-4 px-5">
                                <div class="max-w-[180px]">
                                    <a href="{{ route('listings.show', $payment->listing->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-emerald-600 truncate block">
                                        {{ $payment->listing->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i>{{ $payment->listing->city }}</span>
                                </div>
                            </td>
                            
                            <!-- Penerima (Owner) -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-semibold text-slate-800 leading-none flex items-center">
                                        {{ $payment->listing->owner->name }}
                                        @if($payment->listing->owner->is_verified)
                                            <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                        @endif
                                    </p>
                                    <p class="text-[9px] text-slate-400 mt-0.5">{{ $payment->listing->owner->email }}</p>
                                </div>
                            </td>
                            
                            <!-- Jumlah Dana -->
                            <td class="py-4 px-5 font-bold text-slate-800">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            
                            <!-- Metode Bayar -->
                            <td class="py-4 px-5 capitalize font-semibold text-slate-600">
                                {{ str_replace('_', ' ', $payment->payment_type) }}
                            </td>
                            
                            <!-- Tanggal Mutasi -->
                            <td class="py-4 px-5 font-medium text-slate-500">
                                {{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </td>
                            
                            <!-- Status Escrow -->
                            <td class="py-4 px-5">
                                @if($payment->payment_status === 'success')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100" title="Dana ditahan aman di Rekening Bersama. Cair ke owner 24 jam pasca check-in.">
                                        <i class="fa-solid fa-vault mr-1.5 text-[10px]"></i>Ditahan di Escrow
                                    </span>
                                @elseif($payment->payment_status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100" title="Sewa selesai & dana telah diteruskan ke owner.">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-[10px]"></i>Cair ke Pemilik
                                    </span>
                                @elseif($payment->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100" title="Menunggu penyelesaian transfer bank oleh penyewa.">
                                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Menunggu Pembayaran
                                    </span>
                                @elseif($payment->payment_status === 'cancelled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200" title="Transaksi dibatalkan.">
                                        <i class="fa-solid fa-ban mr-1.5"></i>Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-100">
                                        <i class="fa-solid fa-circle-xmark mr-1.5"></i>Gagal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-vault text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Transaksi Ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci atau rentang tanggal lain untuk memperluas pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
