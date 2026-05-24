@extends('layouts.dashboard')

@section('title', 'Lacak Transaksi - Admin KosinAja')
@section('header_title', 'Lacak Transaksi')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- Hero Search --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-950 rounded-3xl p-7 shadow-2xl">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
                    <i class="fa-solid fa-magnifying-glass-location text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-black text-lg">Lacak ID Transaksi</h2>
                    <p class="text-indigo-300/70 text-xs mt-0.5">Masukkan ID transaksi untuk melihat seluruh detail pembayaran secara real-time</p>
                </div>
            </div>
            <form action="{{ route('admin.tracking') }}" method="GET" class="flex gap-3">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                        <i class="fa-solid fa-hashtag text-xs"></i>
                    </span>
                    <input type="text" name="q" value="{{ $query ?? '' }}"
                        placeholder="Contoh: TRX-20240101-ABCD1234 ..."
                        autocomplete="off"
                        class="w-full h-12 pl-10 pr-4 rounded-xl bg-white/10 border border-white/20 text-sm font-semibold text-white placeholder-white/30 focus:outline-none focus:border-indigo-400 focus:bg-white/15 transition-all">
                </div>
                <button type="submit" class="px-7 h-12 bg-indigo-500 hover:bg-indigo-400 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-900/50 flex items-center space-x-2 flex-shrink-0">
                    <i class="fa-solid fa-radar"></i>
                    <span>Lacak</span>
                </button>
                @if($query)
                <a href="{{ route('admin.tracking') }}" class="px-4 h-12 bg-white/10 hover:bg-white/20 text-white/70 rounded-xl transition-all flex items-center justify-center flex-shrink-0 border border-white/10" title="Reset">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </form>
            <p class="text-indigo-300/40 text-[10px] mt-3">
                <i class="fa-solid fa-circle-info mr-1"></i>
                ID transaksi bisa ditemukan di halaman riwayat pembayaran penyewa atau log transaksi admin.
            </p>
        </div>
    </div>

    {{-- Not Found State --}}
    @if($notFound)
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-file-circle-xmark text-3xl text-red-300"></i>
        </div>
        <p class="font-black text-slate-800 text-base">Transaksi Tidak Ditemukan</p>
        <p class="text-xs text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">
            ID <span class="font-mono font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded select-all">{{ $query }}</span>
            tidak terdaftar dalam sistem. Periksa kembali penulisan ID transaksi.
        </p>
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center mt-5 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
            <i class="fa-solid fa-receipt mr-1.5"></i>Cari di Log Transaksi
        </a>
    </div>
    @endif

    {{-- Empty State --}}
    @if(!$query && !$payment)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-radar text-3xl text-indigo-300"></i>
        </div>
        <p class="font-black text-slate-700 text-base">Siap Melacak</p>
        <p class="text-xs text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">
            Tempelkan ID transaksi di kotak pencarian di atas untuk melihat detail lengkap transaksi tersebut.
        </p>
        <div class="mt-5 flex flex-wrap justify-center gap-2">
            <span class="text-[10px] bg-slate-100 text-slate-400 px-3 py-1.5 rounded-full font-mono">TRX-YYYYMMDD-XXXXXX</span>
            <span class="text-[10px] bg-slate-100 text-slate-400 px-3 py-1.5 rounded-full font-mono">ORDER-XXXXXXXXXX</span>
        </div>
    </div>
    @endif

    {{-- ═══ RESULT ═══ --}}
    @if($payment)
        @php
            $isCancelled = in_array($payment->payment_status, ['cancelled', 'failed']);
            $fee = $isCancelled ? 0 : (int) round($payment->amount * 0.02);
            $net = $isCancelled ? 0 : $payment->amount - $fee;

            $statusConfig = match($payment->payment_status) {
                'completed' => ['label'=>'Dana Dicairkan',    'icon'=>'fa-house-circle-check','from'=>'from-emerald-500','to'=>'to-teal-500',   'ring'=>'ring-emerald-300'],
                'success'   => ['label'=>'Dana Ditahan',      'icon'=>'fa-circle-check',      'from'=>'from-emerald-400','to'=>'to-emerald-600', 'ring'=>'ring-emerald-200'],
                'pending'   => ['label'=>'Menunggu Transfer', 'icon'=>'fa-clock-rotate-left', 'from'=>'from-amber-400',  'to'=>'to-orange-500',  'ring'=>'ring-amber-200'],
                'cancelled' => ['label'=>'Dibatalkan',        'icon'=>'fa-ban',               'from'=>'from-slate-400',  'to'=>'to-slate-600',   'ring'=>'ring-slate-200'],
                default     => ['label'=>'Gagal',             'icon'=>'fa-circle-xmark',      'from'=>'from-red-400',    'to'=>'to-red-600',     'ring'=>'ring-red-200'],
            };
            $typeLabel = match($payment->payment_type) {
                'bank_transfer'=>'Transfer Bank','credit_card'=>'Kartu Kredit',
                'ewallet'=>'E-Wallet','cash'=>'Tunai',
                default=>ucfirst(str_replace('_',' ',$payment->payment_type)),
            };
        @endphp

        {{-- Status Banner --}}
        <div class="bg-gradient-to-r {{ $statusConfig['from'] }} {{ $statusConfig['to'] }} rounded-2xl p-5 shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='white' fill-opacity='1'%3E%3Cpath d='M20 20.5V18H0v5h5v5H0v5h20v-9.5zm-2 4.5h-1v-1h1v1zm1-3h-1v-1h1v1zm3-3h-1v-1h1v1zm1 3h-1v-1h1v1z'/%3E%3C/g%3E%3C/svg%3E\")"></div>
            <div class="relative flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 ring-4 {{ $statusConfig['ring'] }} ring-opacity-50 flex items-center justify-center text-white shadow-inner">
                        <i class="fa-solid {{ $statusConfig['icon'] }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">Status Transaksi</p>
                        <p class="text-white font-black text-xl leading-tight">{{ $statusConfig['label'] }}</p>
                        <p class="text-white/60 font-mono text-xs mt-0.5 select-all">{{ $payment->transaction_id }}</p>
                    </div>
                </div>
                <div class="text-right bg-white/10 rounded-xl px-5 py-3">
                    <p class="text-white/60 text-[10px] font-bold uppercase">Jumlah Bayar</p>
                    <p class="text-white font-black text-2xl">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <p class="text-white/60 text-[10px] mt-0.5">via {{ $typeLabel }}</p>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT: Detail + Timeline --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Transaction Detail --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice text-indigo-500 text-xs"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Rincian Transaksi</h3>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-slate-50">
                        <div class="divide-y divide-slate-50">
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">ID Transaksi</p>
                                <p class="font-mono text-[11px] font-bold text-slate-800 select-all break-all leading-relaxed">{{ $payment->transaction_id }}</p>
                            </div>
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Jenis Pembayaran</p>
                                <p class="text-xs font-bold text-slate-800">{{ $typeLabel }}</p>
                            </div>
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Tanggal & Waktu</p>
                                <p class="text-xs font-bold text-slate-800">{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $payment->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
                            </div>
                            @if($payment->va_number)
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Nomor VA</p>
                                <p class="font-mono text-xs font-bold text-slate-800 select-all">{{ $payment->va_number }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="divide-y divide-slate-50">
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Jumlah Bayar</p>
                                <p class="text-base font-black text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="px-5 py-3.5">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Potongan Platform (2%)</p>
                                @if($isCancelled)
                                    <p class="text-sm text-slate-300 font-bold">—</p>
                                @else
                                    <p class="text-sm font-bold text-red-500">- Rp {{ number_format($fee, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <div class="px-5 py-3.5 {{ $isCancelled ? '' : 'bg-emerald-50/50' }}">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Bersih Diterima Owner</p>
                                @if($isCancelled)
                                    <p class="text-sm text-slate-300 font-bold">—</p>
                                @else
                                    <p class="text-base font-black text-emerald-600">Rp {{ number_format($net, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            @if($payment->cancellation_fee && $payment->cancellation_fee > 0)
                            <div class="px-5 py-3.5 bg-red-50/50">
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Biaya Pembatalan</p>
                                <p class="text-sm font-bold text-red-600">Rp {{ number_format($payment->cancellation_fee, 0, ',', '.') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($payment->cancellation_reason)
                    <div class="px-5 py-3.5 border-t border-red-50 bg-red-50/30">
                        <p class="text-[10px] text-slate-400 font-medium mb-1">Alasan Pembatalan</p>
                        <p class="text-xs text-red-700 leading-relaxed bg-red-50 border border-red-100 rounded-lg p-3">{{ $payment->cancellation_reason }}</p>
                    </div>
                    @endif
                </div>

                {{-- Timeline --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center">
                            <i class="fa-solid fa-timeline text-violet-500 text-xs"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Garis Waktu Transaksi</h3>
                    </div>
                    <div class="p-6">
                        <ol class="relative border-l-2 border-slate-100 ml-3 space-y-6">

                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-indigo-100 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-plus text-indigo-600 text-[10px]"></i>
                                </span>
                                <div class="bg-indigo-50/60 border border-indigo-100/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-slate-800">Transaksi Dibuat</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    <p class="text-[10px] text-indigo-500 font-semibold mt-1">Penyewa memulai proses pembayaran</p>
                                </div>
                            </li>

                            @if(in_array($payment->payment_status, ['success', 'completed']))
                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-emerald-100 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                                </span>
                                <div class="bg-emerald-50/60 border border-emerald-100/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-slate-800">Pembayaran Diterima</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $payment->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    <p class="text-[10px] text-emerald-600 font-semibold mt-1">Dana masuk ke Rekening Bersama KosinAja</p>
                                </div>
                            </li>
                            @elseif($payment->payment_status === 'pending')
                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-amber-100 border-2 border-white rounded-full flex items-center justify-center shadow-sm animate-pulse">
                                    <i class="fa-solid fa-clock text-amber-600 text-[10px]"></i>
                                </span>
                                <div class="bg-amber-50/60 border border-amber-100/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-slate-800">Menunggu Transfer</p>
                                    <p class="text-[10px] text-amber-600 font-semibold mt-1">Penyewa belum menyelesaikan pembayaran</p>
                                </div>
                            </li>
                            @endif

                            @if($payment->checked_in_at)
                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-teal-100 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-house-circle-check text-teal-600 text-[10px]"></i>
                                </span>
                                <div class="bg-teal-50/60 border border-teal-100/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-slate-800">Penyewa Check-In</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $payment->checked_in_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    <p class="text-[10px] text-teal-600 font-semibold mt-1">Kesesuaian kamar telah dikonfirmasi penyewa</p>
                                </div>
                            </li>
                            @endif

                            @if($payment->payment_status === 'completed')
                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-money-bill-transfer text-white text-[10px]"></i>
                                </span>
                                <div class="bg-emerald-100/60 border border-emerald-200/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-emerald-800">✅ Dana Dicairkan ke Owner</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $payment->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    <p class="text-[10px] text-emerald-700 font-bold mt-1">Rp {{ number_format($net, 0, ',', '.') }} berhasil dikirim ke rekening owner</p>
                                </div>
                            </li>
                            @endif

                            @if($payment->cancelled_at)
                            <li class="ml-6">
                                <span class="absolute -left-[17px] w-8 h-8 bg-red-100 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-ban text-red-500 text-[10px]"></i>
                                </span>
                                <div class="bg-red-50/60 border border-red-100/60 rounded-xl p-3.5">
                                    <p class="text-xs font-bold text-red-700">Transaksi Dibatalkan</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $payment->cancelled_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>

            {{-- RIGHT: People + Kos + Actions --}}
            <div class="space-y-5">

                {{-- Penyewa --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-sky-100 flex items-center justify-center">
                            <i class="fa-solid fa-user text-sky-500 text-xs"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Data Penyewa</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-13 h-13 w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-100 to-indigo-100 border border-sky-200/50 flex items-center justify-center text-slate-700 font-black text-base overflow-hidden flex-shrink-0">
                                @if($payment->user->avatar)
                                    <img src="{{ asset($payment->user->avatar) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ Str::upper(Str::substr($payment->user->name, 0, 2)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-slate-800 text-sm truncate">{{ $payment->user->name }}</p>
                                <p class="text-[10px] text-slate-400 truncate">{{ $payment->user->email }}</p>
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold uppercase">{{ $payment->user->role }}</span>
                                    @if($payment->user->is_verified)
                                        <span class="text-[9px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold"><i class="fa-solid fa-circle-check mr-0.5"></i>Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($payment->user->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $payment->user->phone) }}" target="_blank"
                           class="flex items-center space-x-2.5 w-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-emerald-700 transition-all">
                            <i class="fa-brands fa-whatsapp text-base text-emerald-500"></i>
                            <span>{{ $payment->user->phone }}</span>
                        </a>
                        @endif
                        <p class="text-[10px] text-slate-400 text-center mt-3">Bergabung {{ $payment->user->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                {{-- Kos --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class="fa-solid fa-house text-emerald-500 text-xs"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Data Kos</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('listings.show', $payment->listing->slug) }}" target="_blank"
                           class="font-bold text-slate-800 text-sm hover:text-emerald-600 transition-colors leading-snug block">
                            {{ $payment->listing->title }}
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-1 text-slate-300"></i>
                        </a>
                        <p class="text-[11px] text-slate-400 flex items-start space-x-1.5">
                            <i class="fa-solid fa-location-dot text-red-400 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $payment->listing->address }}, {{ $payment->listing->city }}</span>
                        </p>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div class="bg-slate-50 rounded-xl p-2.5 text-center">
                                <p class="text-[9px] text-slate-400 uppercase tracking-wide">Harga/Bulan</p>
                                <p class="text-xs font-black text-slate-800 mt-0.5">Rp {{ number_format($payment->listing->price / 1000, 0, ',', '.') }}rb</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-2.5 text-center">
                                <p class="text-[9px] text-slate-400 uppercase tracking-wide">Tipe Kos</p>
                                <p class="text-xs font-black text-slate-800 mt-0.5 capitalize">{{ $payment->listing->gender_type ?? '-' }}</p>
                            </div>
                        </div>
                        @if($payment->listing->owner)
                        <div class="flex items-center space-x-2.5 pt-2 border-t border-slate-50">
                            <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-600 overflow-hidden flex-shrink-0">
                                @if($payment->listing->owner->avatar)
                                    <img src="{{ asset($payment->listing->owner->avatar) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ Str::upper(Str::substr($payment->listing->owner->name, 0, 2)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] text-slate-400">Pemilik Kos</p>
                                <p class="text-xs font-bold text-slate-700 truncate">{{ $payment->listing->owner->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 space-y-2">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">Aksi Cepat Admin</p>
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center space-x-2.5 w-full bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 transition-all">
                        <i class="fa-solid fa-receipt text-slate-400 w-4"></i><span>Log Semua Transaksi</span>
                    </a>
                    <a href="{{ route('admin.earnings') }}" class="flex items-center space-x-2.5 w-full bg-violet-50 hover:bg-violet-100 border border-violet-100 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-violet-700 transition-all">
                        <i class="fa-solid fa-chart-pie text-violet-400 w-4"></i><span>Penghasilan Platform</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 w-full bg-sky-50 hover:bg-sky-100 border border-sky-100 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-sky-700 transition-all">
                        <i class="fa-solid fa-users text-sky-400 w-4"></i><span>Kelola Pengguna</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
