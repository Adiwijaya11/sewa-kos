@extends('layouts.app')
@section('title', 'Instruksi Pembayaran Aman - KosinAja')

@section('content')
@php
    $provider = 'BCA';
    if (Str::contains(strtolower($payment->payment_type), 'bca')) $provider = 'BCA';
    elseif (Str::contains(strtolower($payment->payment_type), 'mandiri')) $provider = 'MANDIRI';
    elseif (Str::contains(strtolower($payment->payment_type), 'bri')) $provider = 'BRI';
    elseif (Str::contains(strtolower($payment->payment_type), 'gopay')) $provider = 'GOPAY';
    elseif (Str::contains(strtolower($payment->payment_type), 'ovo')) $provider = 'OVO';
    elseif (Str::contains(strtolower($payment->payment_type), 'dana')) $provider = 'DANA';
    
    $vaNumber = $payment->va_number ?? '88301827492019';
@endphp
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white text-center space-y-2">
            <div class="w-12 h-12 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mx-auto border border-amber-500/30 animate-pulse">
                <i class="fa-solid fa-clock"></i>
            </div>
            <h1 class="text-lg sm:text-xl font-black tracking-tight">Selesaikan Pembayaran Anda</h1>
            <p class="text-[10px] text-slate-300 uppercase tracking-widest font-semibold">ID Transaksi: {{ $payment->transaction_id }}</p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Safe Protection Badge -->
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start space-x-3 text-emerald-800">
                <i class="fa-solid fa-shield-halved text-xl mt-0.5 text-emerald-600 flex-shrink-0"></i>
                <div class="space-y-0.5">
                    <h4 class="text-xs font-bold">100% Proteksi Dana Terjamin</h4>
                    <p class="text-[10px] text-slate-500 leading-normal">Dana Anda disimpan aman di Rekber KosinAja. Pemilik kos tidak akan menerima sepeser pun sebelum survey serah terima kunci berhasil!</p>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center space-y-1">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Batas Waktu Pembayaran</span>
                <div class="text-lg font-black text-slate-700 flex items-center justify-center gap-1.5" id="countdown-timer">
                    23:59:59
                </div>
            </div>

            <!-- Nominal Billing details -->
            <div class="border border-slate-100 rounded-2xl p-5 space-y-3.5 bg-slate-50/50">
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Kos Terpesan:</span>
                    <span class="font-extrabold text-slate-800 truncate max-w-[200px]" title="{{ $payment->listing->title }}">{{ $payment->listing->title }}</span>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Pemilik Kos:</span>
                    <span class="font-bold text-slate-800">{{ $payment->listing->owner->name }}</span>
                </div>
                <div class="border-t border-slate-100/80 pt-3.5 flex justify-between items-baseline">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Nominal Transfer:</span>
                    <div class="flex flex-col items-end">
                        <span class="text-xl font-black text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        <span class="text-[9px] text-slate-400 mt-0.5">*Transfer tepat sesuai nominal hingga digit terakhir</span>
                    </div>
                </div>
            </div>

            <!-- Transfer Method Instructions Details -->
            @if(Str::contains($payment->payment_type, 'E-Wallet') || Str::contains($payment->payment_type, 'Wallet'))
                <!-- QRIS / E-Wallet Dynamic Instruction -->
                <div class="border border-slate-100 rounded-2xl p-5 space-y-5 text-center bg-slate-50/50">
                    <div class="space-y-1 text-center">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest text-[9px]">Metode Pembayaran QRIS</h3>
                        <p class="text-xs font-bold text-slate-700">Scan QR Code dengan GoPay / OVO / Dana / LinkAja / Mobile Banking</p>
                    </div>

                    <!-- Premium dynamic QRIS design -->
                    <div class="w-56 bg-white border border-slate-100 rounded-3xl p-5 shadow-lg mx-auto flex flex-col items-center gap-3">
                        <!-- QRIS Header -->
                        <div class="flex items-center justify-center bg-red-600 text-white font-extrabold px-3 py-1 rounded text-xs tracking-wider select-none">
                            QRIS
                        </div>
                        
                        <!-- Real QR Code Image -->
                        <div class="w-36 h-36 border border-slate-100 rounded-xl p-2 bg-slate-50 flex items-center justify-center shadow-inner">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://kosinaja.com/pay/{{ $payment->transaction_id }}" 
                                 alt="QRIS Code" 
                                 class="w-full h-full object-contain select-none">
                        </div>
                        
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">NMID: ID1020210202910</span>
                        <span class="text-[8px] font-semibold text-slate-400">DIPERSEMBAHKAN REKBER KOSINAJA</span>
                    </div>

                    <!-- Download Button -->
                    <button type="button" 
                            onclick="downloadQRIS('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://kosinaja.com/pay/{{ $payment->transaction_id }}', 'QRIS_KosinAja_{{ $payment->transaction_id }}.png')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-600 text-[10px] font-bold shadow-sm transition-all mt-1">
                        <i class="fa-solid fa-download text-emerald-500"></i>
                        <span>Unduh Kode QRIS</span>
                    </button>
                    
                    <p class="text-[10px] text-slate-400">Silakan scan kode QRIS di atas untuk menyelesaikan transaksi sewa kos Anda secara instan.</p>
                </div>
            @else
                <!-- Bank Virtual Account Instruction -->
                <div class="border border-slate-100 rounded-2xl p-5 space-y-4 bg-slate-50/50">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div class="text-xs">
                            <h3 class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Metode Pembayaran Bank</h3>
                            <p class="font-bold text-slate-800 mt-0.5">Transfer Virtual Account (VA)</p>
                        </div>
                        <!-- Bank Badge -->
                        <span class="px-3 py-1 rounded bg-blue-50 border border-blue-100 text-[10px] font-bold text-blue-600 uppercase">
                            {{ $provider }} Virtual Account
                        </span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Nomor Rekening Virtual Account</span>
                        <div class="flex items-center justify-between p-3.5 bg-white border border-slate-100 rounded-xl shadow-sm">
                            <span class="text-base font-black text-slate-700 tracking-widest" id="va-number">{{ $vaNumber }}</span>
                            <button type="button" onclick="copyVA()" class="text-emerald-600 hover:text-emerald-700 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                <i class="fa-regular fa-copy"></i>
                                <span>Salin</span>
                            </button>
                        </div>
                    </div>

                    <div class="text-left text-xs text-slate-500 space-y-2 pt-2">
                        <p class="font-bold text-slate-700">Langkah Transfer M-Banking:</p>
                        
                        @if($provider === 'BCA')
                            <ol class="list-decimal pl-4 space-y-1.5 text-[11px] leading-relaxed">
                                <li>Buka aplikasi **m-BCA** di smartphone Anda.</li>
                                <li>Masukkan kode akses, lalu pilih menu **m-Transfer** &gt; **BCA Virtual Account**.</li>
                                <li>Masukkan nomor VA BCA: **{{ $vaNumber }}**.</li>
                                <li>Pastikan nama penerima tertera: **Rekber KosinAja**.</li>
                                <li>Masukkan nominal transfer tepat: **Rp {{ number_format($payment->amount, 0, ',', '.') }}**. Selesaikan transaksi.</li>
                            </ol>
                        @elseif($provider === 'MANDIRI')
                            <ol class="list-decimal pl-4 space-y-1.5 text-[11px] leading-relaxed">
                                <li>Buka aplikasi **Livin' by Mandiri** di smartphone Anda.</li>
                                <li>Login, lalu pilih menu **Transfer &amp; Bayar** &gt; **Virtual Account**.</li>
                                <li>Masukkan nomor VA Mandiri: **{{ $vaNumber }}**.</li>
                                <li>Pastikan nama penerima/institusi tertera: **Rekber KosinAja**.</li>
                                <li>Masukkan nominal transfer tepat: **Rp {{ number_format($payment->amount, 0, ',', '.') }}**. Selesaikan transaksi.</li>
                            </ol>
                        @else
                            <ol class="list-decimal pl-4 space-y-1.5 text-[11px] leading-relaxed">
                                <li>Buka aplikasi **BRImo** di smartphone Anda.</li>
                                <li>Login, lalu pilih menu **BRIVA** &gt; **Pembayaran Baru**.</li>
                                <li>Masukkan nomor BRIVA BRI: **{{ $vaNumber }}**.</li>
                                <li>Pastikan nama penerima tertera: **Rekber KosinAja**.</li>
                                <li>Masukkan nominal transfer tepat: **Rp {{ number_format($payment->amount, 0, ',', '.') }}**. Selesaikan transaksi.</li>
                            </ol>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Safe Action Commands -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <form action="{{ route('payments.confirm_manual', $payment->id) }}" method="POST" class="confirm-form"
                      data-confirm-title="Konfirmasi Pembayaran?"
                      data-confirm-text="Apakah Anda sudah benar-benar mentransfer dana ke rekening yang tertera sesuai nominal? Sistem akan memverifikasi pembayaran Anda."
                      data-confirm-button="Ya, Saya Sudah Transfer"
                      data-confirm-color="#10b981"
                      data-confirm-icon="question">
                    @csrf
                    
                    <button type="submit" 
                            class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Saya Sudah Melakukan Pembayaran</span>
                    </button>
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('payments.history') }}" 
                       class="w-1/2 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs text-center transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-receipt text-slate-400"></i>
                        <span>Lihat Riwayat Transaksi</span>
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="w-1/2 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs text-center transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-house text-slate-400"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Copy Virtual Account number function
    function copyVA() {
        const vaNum = document.getElementById('va-number').innerText;
        navigator.clipboard.writeText(vaNum).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Nomor VA berhasil disalin!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    // Countdown Timer logic
    document.addEventListener('DOMContentLoaded', function () {
        let totalSeconds = 24 * 60 * 60; // 24 hours
        const timerEl = document.getElementById('countdown-timer');

        const interval = setInterval(function () {
            if (totalSeconds <= 0) {
                clearInterval(interval);
                timerEl.innerText = "EXPIRED";
                return;
            }

            totalSeconds--;

            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            const formattedHours = hours.toString().padStart(2, '0');
            const formattedMinutes = minutes.toString().padStart(2, '0');
            const formattedSeconds = seconds.toString().padStart(2, '0');

            timerEl.innerHTML = `<i class="fa-regular fa-clock text-slate-400 text-base mr-1 animate-pulse"></i> ${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
        }, 1000);
    });

    // Dynamic Cross-Origin Blob Download Helper
    function downloadQRIS(url, filename) {
        // Show a brief premium loading notification
        Swal.fire({
            title: 'Menyiapkan Unduhan...',
            text: 'Mengunduh kode QRIS Anda.',
            icon: 'info',
            showConfirmButton: false,
            timer: 1000,
            toast: true,
            position: 'top-end'
        });

        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                const blobURL = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobURL;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(blobURL);
            })
            .catch(err => {
                console.error('Failed to download QR code blob, falling back:', err);
                // Fallback: Open image in new tab if blob fetch fails
                window.open(url, '_blank');
            });
    }
</script>
@endsection
