@extends('layouts.dashboard')

@section('title', 'Penghasilan Platform - Admin KosinAja')
@section('header_title', 'Penghasilan Platform')

@section('content')
<div class="space-y-6">

    {{-- ═══ HERO HEADER ═══ --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 rounded-3xl p-7 shadow-2xl">
        <div class="absolute -top-20 -right-10 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 left-1/2 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-400/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300">
                    <i class="fa-solid fa-chart-pie text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-black text-xl">Penghasilan Platform</h2>
                    <p class="text-emerald-300/70 text-xs mt-0.5">Analitik pendapatan lengkap KosinAja — komisi, pencairan owner, dan tren bulanan</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 bg-white/10 border border-white/20 rounded-xl px-4 py-2.5">
                <i class="fa-solid fa-percent text-emerald-300 text-sm"></i>
                <div>
                    <p class="text-white/60 text-[9px] font-bold uppercase">Komisi Platform</p>
                    <p class="text-white font-black text-base leading-none">{{ $commissionRate }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Total Masuk --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center mb-3 shadow-sm shadow-emerald-500/20">
                    <i class="fa-solid fa-arrow-trend-up text-white text-sm"></i>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Uang Masuk</p>
                <p class="text-2xl font-black text-slate-800 mt-1 leading-none">
                    Rp {{ number_format($totalSuccess / 1000000, 1, ',', '.') }}<span class="text-sm font-bold text-slate-400">jt</span>
                </p>
                <p class="text-[10px] text-emerald-500 font-semibold mt-2 flex items-center">
                    <i class="fa-solid fa-circle-check mr-1"></i>success + completed
                </p>
            </div>
        </div>

        {{-- Cair ke Owner --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 w-24 h-24 bg-sky-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center mb-3 shadow-sm shadow-sky-500/20">
                    <i class="fa-solid fa-building-columns text-white text-sm"></i>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Dicairkan ke Owner</p>
                <p class="text-2xl font-black text-slate-800 mt-1 leading-none">
                    Rp {{ number_format($ownerPayouts / 1000000, 1, ',', '.') }}<span class="text-sm font-bold text-slate-400">jt</span>
                </p>
                <p class="text-[10px] text-sky-500 font-semibold mt-2 flex items-center">
                    <i class="fa-solid fa-minus mr-1"></i>setelah potongan {{ $commissionRate }}%
                </p>
            </div>
        </div>

        {{-- Komisi Platform --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center mb-3 shadow-sm shadow-violet-500/20">
                    <i class="fa-solid fa-percent text-white text-sm"></i>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Komisi KosinAja</p>
                <p class="text-2xl font-black text-slate-800 mt-1 leading-none">
                    Rp {{ number_format($platformEarned / 1000, 0, ',', '.') }}<span class="text-sm font-bold text-slate-400">rb</span>
                </p>
                <p class="text-[10px] text-violet-500 font-semibold mt-2 flex items-center">
                    <i class="fa-solid fa-percent mr-1"></i>{{ $commissionRate }}% dari dana cair
                </p>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center mb-3 shadow-sm shadow-amber-500/20">
                    <i class="fa-solid fa-receipt text-white text-sm"></i>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-black text-slate-800 mt-1 leading-none">{{ number_format($totalTrx) }}</p>
                <p class="text-[10px] text-amber-500 font-semibold mt-2 flex items-center">
                    <i class="fa-solid fa-ban mr-1"></i>{{ $totalCancelled }} dibatalkan/gagal
                </p>
            </div>
        </div>
    </div>

    {{-- ═══ CHART + STATUS BREAKDOWN ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Monthly Bar Chart (12 months) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-black text-slate-800">Grafik Pendapatan 12 Bulan</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Total transaksi berhasil per bulan</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-chart-bar text-emerald-500 text-sm"></i>
                </div>
            </div>
            @php $maxMonth = max(array_column($months, 'amount')) ?: 1; @endphp
            <div class="space-y-3">
                @foreach($months as $m)
                    @php $pct = ($m['amount'] / $maxMonth) * 100; @endphp
                    <div class="flex items-center space-x-3">
                        <span class="text-[10px] text-slate-400 font-bold w-10 flex-shrink-0 text-right">{{ $m['short'] }}</span>
                        <div class="flex-grow relative">
                            <div class="bg-slate-100 rounded-full h-5 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center transition-all duration-700 min-w-[4px]"
                                     style="width: {{ max($pct, 1) }}%">
                                    @if($m['amount'] > 0 && $pct > 20)
                                        <span class="text-white text-[8px] font-bold pl-2 truncate">Rp {{ number_format($m['amount']/1000, 0, ',', '.') }}rb</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="w-20 text-right flex-shrink-0">
                            @if($m['amount'] > 0)
                                <span class="text-[10px] font-black text-slate-600">Rp {{ number_format($m['amount']/1000, 0, ',', '.') }}rb</span>
                            @else
                                <span class="text-[10px] text-slate-300">—</span>
                            @endif
                        </div>
                        <span class="text-[9px] text-slate-300 w-10 text-right flex-shrink-0">{{ $m['trx_count'] }}x</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-black text-slate-800">Status Transaksi</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Distribusi seluruh pembayaran</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                    <i class="fa-solid fa-chart-donut text-violet-500 text-sm"></i>
                </div>
            </div>
            @php
                $colorMap = [
                    'emerald' => ['bg'=>'bg-emerald-500','light'=>'bg-emerald-100','text'=>'text-emerald-700'],
                    'teal'    => ['bg'=>'bg-teal-500',   'light'=>'bg-teal-100',   'text'=>'text-teal-700'],
                    'amber'   => ['bg'=>'bg-amber-500',  'light'=>'bg-amber-100',  'text'=>'text-amber-700'],
                    'slate'   => ['bg'=>'bg-slate-400',  'light'=>'bg-slate-100',  'text'=>'text-slate-700'],
                    'red'     => ['bg'=>'bg-red-500',    'light'=>'bg-red-100',    'text'=>'text-red-700'],
                ];
                $totalAll = max($totalTrx, 1);
            @endphp
            <div class="space-y-3">
                @foreach($statusBreakdown as $s)
                    @php
                        $c = $colorMap[$s['color']];
                        $pct2 = round(($s['count'] / $totalAll) * 100);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full {{ $c['bg'] }} flex-shrink-0"></span>
                                <span class="text-[11px] font-semibold text-slate-700">{{ $s['label'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] font-black text-slate-800">{{ $s['count'] }}</span>
                                <span class="text-[9px] text-slate-400 ml-1">({{ $pct2 }}%)</span>
                            </div>
                        </div>
                        <div class="bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $c['bg'] }} h-full rounded-full transition-all duration-700" style="width: {{ $pct2 }}%"></div>
                        </div>
                        @if($s['amount'] > 0)
                        <p class="text-[9px] text-slate-400 mt-0.5 text-right">Rp {{ number_format($s['amount']/1000, 0, ',', '.') }}rb</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ TOP LISTINGS + RECENT ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Top 10 Kos --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h3 class="text-sm font-black text-slate-800">🏆 Top Kos Penghasilan</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">10 kos dengan transaksi terbesar</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-amber-500 text-sm"></i>
                </div>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topListings as $i => $tl)
                    @php
                        $medals = ['🥇','🥈','🥉','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
                        $listingTitle = $tl->listing->title ?? 'Kos Dihapus';
                        $ownerName = $tl->listing->owner->name ?? '-';
                    @endphp
                    <div class="flex items-center space-x-3 px-5 py-3.5 hover:bg-slate-50/50 transition-colors">
                        <span class="text-lg leading-none w-7 flex-shrink-0">{{ $medals[$i] }}</span>
                        <div class="min-w-0 flex-grow">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $listingTitle }}</p>
                            <p class="text-[10px] text-slate-400 truncate">
                                <i class="fa-solid fa-user text-xs mr-1"></i>{{ $ownerName }} &bull; {{ $tl->trx_count }} transaksi
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-black text-emerald-600">Rp {{ number_format($tl->total_income / 1000, 0, ',', '.') }}rb</p>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i class="fa-solid fa-trophy text-slate-200 text-3xl mb-3"></i>
                        <p class="text-xs text-slate-400">Belum ada data transaksi berhasil</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h3 class="text-sm font-black text-slate-800">Transaksi Terbaru</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">10 pembayaran berhasil terakhir</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-[10px] text-emerald-600 font-bold hover:underline">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentPayments as $rp)
                    @php
                        $rpFee = (int) round($rp->amount * 0.02);
                        $rpNet = $rp->amount - $rpFee;
                        $isCompleted = $rp->payment_status === 'completed';
                    @endphp
                    <div class="flex items-center space-x-3 px-5 py-3 hover:bg-slate-50/50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-600 overflow-hidden flex-shrink-0">
                            @if($rp->user->avatar)
                                <img src="{{ asset($rp->user->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ Str::upper(Str::substr($rp->user->name, 0, 2)) }}
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow">
                            <p class="text-[11px] font-bold text-slate-800 truncate">{{ $rp->user->name }}</p>
                            <p class="text-[9px] text-slate-400 truncate">{{ $rp->listing->title ?? '-' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[11px] font-black text-emerald-600">Rp {{ number_format($rpNet / 1000, 0, ',', '.') }}rb</p>
                            <p class="text-[9px] text-slate-400">{{ $rp->created_at->timezone('Asia/Jakarta')->format('d M, H:i') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($isCompleted)
                                <span class="w-2 h-2 rounded-full bg-emerald-500 block"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-teal-400 block"></span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i class="fa-solid fa-receipt text-slate-200 text-3xl mb-3"></i>
                        <p class="text-xs text-slate-400">Belum ada transaksi berhasil</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══ PENDING WARNING ═══ --}}
    @if($totalPending > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start space-x-4">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-amber-800">Ada Dana Tertahan Menunggu Transfer</p>
            <p class="text-xs text-amber-600 mt-0.5 leading-relaxed">
                Terdapat <span class="font-black">Rp {{ number_format($totalPending, 0, ',', '.') }}</span> dari transaksi dengan status <span class="font-bold">Menunggu Transfer</span>. Dana ini belum masuk ke sistem karena penyewa belum menyelesaikan pembayaran.
            </p>
        </div>
    </div>
    @endif

</div>
@endsection
