@extends('layouts.dashboard')

@section('title', 'Log Pendapatan - KosinAja')
@section('header_title', 'Uang Masuk / Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions & Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-grow">
            <div>
                <h2 class="text-base font-bold text-slate-800">Log Pendapatan Sewa Properti</h2>
                <p class="text-[11px] text-slate-400">Total riwayat dana sewa dari calon penyewa properti Anda</p>
            </div>
            
            <!-- Search Form (Date Filter with Direct Submit on Change) -->
            <form action="{{ route('owner.payments') }}" method="GET" id="dateFilterForm" class="flex-grow max-w-xs w-full sm:ml-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <i class="fa-solid fa-calendar-day text-xs"></i>
                    </span>
                    <input type="date" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           onchange="document.getElementById('dateFilterForm').submit()"
                           class="w-full h-10 pl-10 pr-10 rounded-xl border border-slate-200/80 bg-slate-50/50 text-xs font-semibold focus:outline-none focus:border-emerald-500 focus:bg-white transition-all text-slate-700 cursor-pointer placeholder-slate-400"
                           title="Pilih tanggal untuk menyaring pendapatan">
                    @if(!empty($search))
                        <a href="{{ route('owner.payments') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-red-500 transition-colors" title="Hapus Filter Tanggal">
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Escrow Protection Alert -->
    <div class="p-4 rounded-2xl bg-gradient-to-tr from-emerald-600/10 to-teal-500/5 border border-emerald-200/50 shadow-sm flex items-start space-x-3.5">
        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-600 flex-shrink-0">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div>
            <h4 class="font-bold text-slate-800 text-xs">Sistem KosinAja Escrow Bersama Aman</h4>
            <p class="text-[11px] text-slate-500 leading-relaxed mt-1">
                Demi keamanan bersama dan pencegahan tindak scam, seluruh pembayaran uang sewa (deposit/bulan pertama) ditampung di <span class="font-semibold text-slate-700">Rekening Bersama KosinAja</span> terlebih dahulu. 
                Dana sewa <span class="font-bold text-emerald-600">dikurangi biaya layanan platform 2%</span> akan diteruskan secara otomatis ke rekening bank Anda dalam waktu <span class="font-semibold text-slate-700">24 Jam</span> setelah penyewa melakukan check-in lokasi dan mengonfirmasi kesesuaian kamar.
            </p>
        </div>
    </div>

    <!-- Payments Card Board -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/50">
                        <th class="py-3 px-5">ID Transaksi</th>
                        <th class="py-3 px-5">Kos Properti</th>
                        <th class="py-3 px-5">Calon Penyewa</th>
                        <th class="py-3 px-5 text-right">Jumlah Bayar</th>
                        <th class="py-3 px-5 text-right">Potongan Platform (2%)</th>
                        <th class="py-3 px-5 text-right">Bersih Diterima</th>
                        <th class="py-3 px-5">Metode</th>
                        <th class="py-3 px-5">Tanggal Masuk</th>
                        <th class="py-3 px-5">Status Escrow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($payments as $payment)
                        @php
                            $isCancelled = in_array($payment->payment_status, ['cancelled', 'failed']);
                            $fee = $isCancelled ? 0 : (int) round($payment->amount * 0.02);
                            $net = $isCancelled ? 0 : $payment->amount - $fee;
                        @endphp
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <span class="font-mono text-xs font-semibold text-slate-600 uppercase select-all">{{ $payment->transaction_id }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="max-w-[200px]">
                                    <a href="{{ route('listings.show', $payment->listing->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-emerald-600 truncate block">
                                        {{ $payment->listing->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i>{{ $payment->listing->city }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-[10px] overflow-hidden">
                                        @if($payment->user->avatar)
                                            <img src="{{ asset($payment->user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            {{ Str::upper(Str::substr($payment->user->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 leading-none">{{ $payment->user->name }}</p>
                                        <a href="{{ route('chats.conversation', $payment->user->id) }}" class="text-[9px] text-emerald-600 hover:underline font-bold mt-0.5 block flex items-center">
                                            <i class="fa-regular fa-comment mr-1"></i>Kirim Pesan
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-right font-medium {{ $isCancelled ? 'text-slate-300 line-through' : 'text-slate-500' }}">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5 text-right font-medium {{ $isCancelled ? 'text-slate-300' : 'text-red-500' }}">
                                @if($isCancelled) <span class="text-slate-300">—</span> @else - Rp {{ number_format($fee, 0, ',', '.') }} @endif
                            </td>
                            <td class="py-4 px-5 text-right font-black {{ $isCancelled ? 'text-slate-300' : 'text-emerald-600 bg-emerald-50/10' }}">
                                @if($isCancelled) <span class="text-slate-300">—</span> @else Rp {{ number_format($net, 0, ',', '.') }} @endif
                            </td>
                            <td class="py-4 px-5 capitalize font-semibold text-slate-600">
                                {{ str_replace('_', ' ', $payment->payment_type) }}
                            </td>
                            <td class="py-4 px-5 font-medium text-slate-500">
                                {{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </td>
                            <td class="py-4 px-5">
                                @if($payment->payment_status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200" title="Penyewa telah check-in. Dana dicairkan ke rekening Anda.">
                                        <i class="fa-solid fa-house-circle-check mr-1.5 text-emerald-600"></i>Dana Dicairkan
                                    </span>
                                @elseif($payment->payment_status === 'success')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100" title="Dana sukses diverifikasi di rekening bersama. Siap dicairkan setelah penyewa check-in.">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-emerald-500"></i>Dana Ditahan
                                    </span>
                                @elseif($payment->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100" title="Menunggu penyelesaian transfer bank oleh penyewa.">
                                        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-amber-500"></i>Menunggu Transfer
                                    </span>
                                @elseif($payment->payment_status === 'cancelled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <i class="fa-solid fa-ban mr-1.5 text-slate-400"></i>Dibatalkan
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
                            <td colspan="9" class="py-16 text-center text-slate-400">
                                @if(!empty($search))
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">Tidak Ada Transaksi Ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Tidak ada pendapatan masuk pada tanggal "{{ \Carbon\Carbon::parse($search)->format('d/m/Y') }}".</p>
                                    <a href="{{ route('owner.payments') }}" class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl mt-4 transition-colors">
                                        <i class="fa-solid fa-rotate-left mr-1.5"></i>Reset Filter Tanggal
                                    </a>
                                @else
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">Belum Ada Pembayaran Masuk</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Ketika penyewa menyetujui kesepakatan dan memesan kos via rekening bersama, rincian pembayaran akan tercatat di halaman ini.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show 2% service fee information welcome popup ONLY once per session
        if (!sessionStorage.getItem('fee_popup_shown')) {
            sessionStorage.setItem('fee_popup_shown', 'true');
            Swal.fire({
                title: 'Informasi Biaya Layanan',
                html: `
                    <div class="space-y-3.5 text-left">
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center space-x-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-base flex-shrink-0 shadow-sm shadow-emerald-500/10">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Potongan 2% per Transaksi</p>
                                <p class="text-[10px] text-slate-500">Pemeliharaan platform escrow aman</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Halo Mitra Owner! Harap dicatat bahwa untuk setiap pembayaran sewa kos yang berhasil diselesaikan melalui sistem <b>KosinAja Escrow (Rekening Bersama)</b>, platform kami mengenakan biaya layanan administrasi sebesar <b>2%</b> dari total pendapatan transaksi Anda.
                        </p>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Biaya 2% ini dipotong secara otomatis saat dana sewa dicairkan ke rekening bank Anda setelah penyewa berhasil melakukan check-in di properti Anda. Potongan ini digunakan untuk mendukung keandalan verifikasi anti-scam dan pengembangan berkelanjutan.
                        </p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#10b981', // Emerald
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                    title: 'text-sm font-bold text-slate-800',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-500/10'
                },
                buttonsStyling: true
            });
        }
    });
</script>
@endsection
