@extends('layouts.dashboard')

@section('title', 'Pengaturan Kebijakan & Aturan Sistem - KosinAja')
@section('header_title', 'Pusat Pengaturan & Kebijakan Sistem')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Konfigurasi Aturan & Parameter Platform</h2>
        <p class="text-xs text-slate-400 mt-0.5">Kelola batasan anti-spam booking, persentase bagi hasil platform, serta pengaturan transaksi escrow</p>
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Settings Form (Column 7) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 confirm-form"
                      data-confirm-title="Simpan Konfigurasi Sistem?"
                      data-confirm-text="Apakah Anda yakin ingin memperbarui aturan kebijakan platform dan pengaturan transaksi?"
                      data-confirm-button="Ya, Simpan"
                      data-confirm-color="#10b981"
                      data-confirm-icon="question">
                    @csrf

                    <!-- Section 1: Financial & Escrow Commission -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2"><i class="fa-solid fa-hand-holding-dollar text-emerald-500 mr-2"></i> Finansial & Pembagian Hasil</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="platform_commission_fee" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Persentase Komisi Platform (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.1" min="0" max="100" name="platform_commission_fee" id="platform_commission_fee" value="{{ $platformFee }}"
                                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition bg-slate-50/30 text-slate-700 font-bold">
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                </div>
                                <span class="text-[9px] text-slate-400 block mt-1">Potongan biaya sewa rekber yang diambil oleh platform dari Owner.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Anti-Spam (Rate Limits) -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2"><i class="fa-solid fa-shield-halved text-indigo-500 mr-2"></i> Proteksi Anti-Spam (Batas Reservasi)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Max Pending Bookings -->
                            <div>
                                <label for="max_pending_bookings" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Batas Maksimal Pending Booking</label>
                                <input type="number" min="1" max="50" name="max_pending_bookings" id="max_pending_bookings" value="{{ $maxPending }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                                <span class="text-[9px] text-slate-400 block mt-1">Jumlah reservasi gantung (belum dibayar) maksimal yang boleh dimiliki renter.</span>
                            </div>

                            <!-- Max Daily Failures -->
                            <div>
                                <label for="max_daily_failures" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Batas Kegagalan Harian (Penalti 24 Jam)</label>
                                <input type="number" min="1" max="50" name="max_daily_failures" id="max_daily_failures" value="{{ $maxDailyFailures }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                                <span class="text-[9px] text-slate-400 block mt-1">Kegagalan/pembatalan booking dalam 24 jam sebelum renter diblokir sementara.</span>
                            </div>

                            <!-- Max Total Failures -->
                            <div>
                                <label for="max_total_failures" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Batas Akumulasi Gagal (Blokir Permanen)</label>
                                <input type="number" min="1" max="100" name="max_total_failures" id="max_total_failures" value="{{ $maxTotalFailures }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                                <span class="text-[9px] text-slate-400 block mt-1">Total batas kegagalan transaksi sepanjang sejarah akun sebelum diblokir permanen.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Payment Gateway Mode -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2"><i class="fa-solid fa-credit-card text-sky-500 mr-2"></i> Payment Gateway Midtrans</h3>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Mode Transaksi Finansial</label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center space-x-2 text-xs text-slate-700 font-medium cursor-pointer">
                                    <input type="radio" name="midtrans_sandbox_mode" value="1" {{ $sandboxMode ? 'checked' : '' }}
                                           class="text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <span>Midtrans Sandbox (Simulasi Uji Coba)</span>
                                </label>
                                <label class="flex items-center space-x-2 text-xs text-slate-700 font-medium cursor-pointer">
                                    <input type="radio" name="midtrans_sandbox_mode" value="0" {{ !$sandboxMode ? 'checked' : '' }}
                                           class="text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <span>Production Mode (Uang Asli Nyata)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-50 flex items-center justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/10 flex items-center space-x-2">
                            <i class="fa-regular fa-floppy-disk text-sm"></i>
                            <span>Simpan Aturan Sistem</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Information & System Diagnosis (Column 5) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Explain Policies -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm space-y-3.5">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center"><i class="fa-solid fa-circle-info text-sky-500 mr-2"></i> Mengapa Aturan Ini Penting?</h4>
                <div class="space-y-3 text-xs text-slate-500 leading-relaxed">
                    <p>
                        <strong class="text-slate-800">1. Pencegahan Troll & Spammer:</strong> Pembatasan pending booking memaksa pencari kos menyelesaikan pembayaran sebelum mereka mengunci ketersediaan properti lain.
                    </p>
                    <p>
                        <strong class="text-slate-800">2. Keamanan Bisnis Owner:</strong> Penangguhan sementara dan permanen mengidentifikasi pelaku *gabut* yang sengaja merugikan pemilik kos dengan menimbun pesanan kosong.
                    </p>
                    <p>
                        <strong class="text-slate-800">3. Pendapatan Berkelanjutan:</strong> Komisi platform 5% secara otomatis membiayai operasional Rekening Bersama (Rekber) platform escrow KosinAja.
                    </p>
                </div>
            </div>

            <!-- Midtrans Connection Diagnosis -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center"><i class="fa-solid fa-circle-nodes text-emerald-500 mr-2"></i> Diagnosis Server Midtrans</h4>
                
                <div class="space-y-3 text-xs">
                    @php
                        $serverKey = config('services.midtrans.server_key');
                        $clientKey = config('services.midtrans.client_key');
                        $isConfigured = !empty($serverKey) && !empty($clientKey);
                    @endphp
                    
                    <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-500 font-medium">Status Integrasi API</span>
                        @if($isConfigured)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i class="fa-solid fa-circle-check mr-1"></i>Tersambung
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Belum Konfigurasi
                            </span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Midtrans Server Key</span>
                        <code class="block p-2 rounded bg-slate-50 font-mono text-[10px] text-slate-600 truncate">
                            @if($serverKey)
                                {{ substr($serverKey, 0, 15) }}...********************
                            @else
                                KOSONG (Silakan isi di file .env Anda!)
                            @endif
                        </code>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Midtrans Client Key</span>
                        <code class="block p-2 rounded bg-slate-50 font-mono text-[10px] text-slate-600 truncate">
                            @if($clientKey)
                                {{ substr($clientKey, 0, 15) }}...********************
                            @else
                                KOSONG (Silakan isi di file .env Anda!)
                            @endif
                        </code>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- Closes Main Workspace Grid -->

</div>
@endsection
