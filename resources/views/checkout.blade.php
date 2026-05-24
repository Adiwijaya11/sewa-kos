@extends('layouts.app')
@section('title', 'Booking Aman Kos - KosinAja')

@section('content')
<style>
    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(0.95); opacity: 1; }
    }
    .animate-ring {
        animation: pulse-ring 2s infinite ease-in-out;
    }
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="space-y-6">
        
        <!-- Header Page Title -->
        <div class="flex items-center space-x-3.5 border-b border-slate-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 shadow-sm">
                <i class="fa-solid fa-receipt text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Ajukan Booking Aman</h1>
                <p class="text-xs text-slate-400 mt-0.5">Selesaikan pemesanan kamar kos Anda dengan jaminan perlindungan rekber KosinAja.</p>
            </div>
        </div>
        
        <!-- Secure Booking Warning Box -->
        <div class="p-5 bg-gradient-to-tr from-emerald-50 to-teal-50/30 border border-emerald-100/80 rounded-3xl flex items-start space-x-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
            <div class="w-11 h-11 bg-white border border-emerald-100 rounded-2xl flex items-center justify-center shadow-sm shrink-0 text-emerald-600">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-xs sm:text-sm font-bold text-slate-800">Kebijakan Perlindungan Penyewa KosinAja</h4>
                <p class="text-[11px] sm:text-xs text-slate-500 leading-relaxed">
                    Uang sewa Anda akan ditahan oleh sistem Rekening Bersama (Escrow) kami. Kami **tidak akan menyalurkan dana ke Pemilik Kos** sampai 24 jam setelah Anda sukses melakukan survey masuk (check-in) dan menyetujui kecocokan kamar di lokasi. Ini adalah jaminan 100% anti-scam dan aman!
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            
            <!-- Property summary card (1 Col) -->
            <div class="md:col-span-1 bg-white p-5 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Detail Hunian Kos</h3>
                
                <div class="flex flex-row md:flex-col gap-4">
                    <!-- Image -->
                    <div class="w-20 h-20 md:w-full md:h-36 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shrink-0 shadow-inner relative group">
                        @if($listing->images->count() > 0)
                            <img src="{{ asset($listing->images->first()->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @endif
                        <span class="absolute top-2.5 left-2.5 text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md shadow bg-emerald-500 text-white">
                            Kos {{ $listing->gender_type }}
                        </span>
                    </div>

                    <!-- Info text -->
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 leading-snug line-clamp-2 hover:text-emerald-600 transition-colors">
                            {{ $listing->title }}
                        </h3>
                        <p class="text-[10px] text-slate-400 flex items-center gap-1">
                            <i class="fa-solid fa-location-dot text-rose-500"></i>
                            <span class="truncate">{{ $listing->city }}, {{ $listing->province }}</span>
                        </p>
                        
                        <div class="border-t border-slate-100 pt-2.5 mt-2.5 flex justify-between items-baseline">
                            <span class="text-[10px] text-slate-400 font-medium">Biaya Sewa / Bln:</span>
                            <span class="text-sm font-black text-emerald-600">Rp {{ number_format($listing->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment selection / Summary (2 Cols) -->
            <div class="md:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Informasi Pembayaran</h3>
                
                @if(isset($snapToken) && $snapToken)
                    <!-- MIDTRANS FLOW -->
                    <div class="space-y-6">
                        
                        <!-- Midtrans Secured Notice -->
                        <div class="p-4.5 bg-gradient-to-tr from-slate-50 to-emerald-50/20 border border-slate-100 rounded-2xl space-y-3 relative overflow-hidden shadow-sm">
                            <div class="absolute -right-8 -top-8 w-20 h-20 bg-emerald-500/5 rounded-full blur-xl"></div>
                            <div class="flex items-center space-x-2 text-emerald-700">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest">Midtrans Secured Gateway</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                                Transaksi booking kos dijamin 100% aman oleh Midtrans dan Rekber KosinAja. Anda dapat memilih metode bayar QRIS (GoPay/ShopeePay), Transfer Virtual Account (BCA, Mandiri, BRI, dll), atau kartu kredit pada portal aman Midtrans.
                            </p>
                        </div>

                        <!-- Price summary lines -->
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4.5 space-y-2.5">
                            <div class="flex justify-between text-xs text-slate-500 font-medium">
                                <span>Tagihan Deposit Booking:</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($listing->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-slate-500 font-medium">
                                <span>Biaya Layanan Rekber:</span>
                                <span class="text-emerald-600 font-black">Gratis</span>
                            </div>
                            <div class="border-t border-slate-200/80 pt-3 flex justify-between text-xs sm:text-sm font-black text-slate-800">
                                <span>Total Pembayaran:</span>
                                <span class="text-emerald-600 text-sm sm:text-base">Rp {{ number_format($listing->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button" id="pay-button"
                                    class="w-full h-13 flex items-center justify-center gap-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 font-extrabold text-sm shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all cursor-pointer">
                                <i class="fa-solid fa-lock"></i>
                                <span>Bayar Aman Sekarang</span>
                            </button>
                            <p class="text-[10px] text-slate-400 text-center font-medium">
                                <i class="fa-solid fa-circle-info mr-0.5"></i>
                                Jika portal pembayaran tertutup secara tidak sengaja, halaman ini akan memuat ulang secara otomatis untuk memulihkan transaksi.
                            </p>
                        </div>
                    </div>
                @else
                    <!-- FALLBACK MOCK PAYMENT FLOW (MANUAL FALLBACK) -->
                    <form action="{{ route('payments.process', $listing->id) }}" method="POST" class="space-y-6 confirm-form font-sans"
                          data-confirm-title="Lanjutkan Transaksi Sewa?"
                          data-confirm-text="Apakah Anda yakin ingin menyelesaikan pembayaran booking aman untuk kos '{{ $listing->title }}'? Dana Anda akan ditahan secara aman di Rekber KosinAja sampai 1 hari setelah sukses check-in."
                          data-confirm-button="Ya, Bayar Sekarang"
                          data-confirm-color="#10b981"
                          data-confirm-icon="question"
                          x-data="{ method: 'bank', provider: 'BCA' }">
                        @csrf
                        
                        <input type="hidden" name="payment_method" :value="method">
                        <input type="hidden" name="payment_provider" :value="provider">
                        <input type="hidden" name="payment_type" :value="method === 'bank' ? 'Virtual Account (' + provider + ')' : 'E-Wallet (' + provider + ')'">

                        <div class="space-y-4">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Metode Transaksi</label>
                            
                            <!-- Main Method Selection Tabs -->
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" @click="method = 'bank'; provider = 'BCA'"
                                        class="py-3 px-4 border-2 rounded-2xl font-bold text-xs flex flex-col items-center gap-2 transition-all cursor-pointer focus:outline-none"
                                        :class="method === 'bank' ? 'border-emerald-500 bg-emerald-50/20 text-emerald-700 shadow-sm shadow-emerald-100/50' : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                    <i class="fa-solid fa-building-columns text-base"></i>
                                    <span>Transfer Bank (Virtual Account)</span>
                                </button>
                                <button type="button" @click="method = 'wallet'; provider = 'GoPay'"
                                        class="py-3 px-4 border-2 rounded-2xl font-bold text-xs flex flex-col items-center gap-2 transition-all cursor-pointer focus:outline-none"
                                        :class="method === 'wallet' ? 'border-emerald-500 bg-emerald-50/20 text-emerald-700 shadow-sm shadow-emerald-100/50' : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                    <i class="fa-solid fa-wallet text-base"></i>
                                    <span>Dompet Digital / QRIS</span>
                                </button>
                            </div>

                            <!-- Bank Sub-Options (BCA, Mandiri, BRI) -->
                            <div x-show="method === 'bank'" class="space-y-2 pt-2" x-transition>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Bank Penerima</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    @foreach(['BCA' => 'Bank BCA', 'Mandiri' => 'Mandiri', 'BRI' => 'Bank BRI'] as $code => $name)
                                        <button type="button" @click="provider = '{{ $code }}'"
                                                class="py-3.5 border-2 rounded-xl text-[11px] font-extrabold flex flex-col items-center justify-center transition-all select-none cursor-pointer focus:outline-none"
                                                :class="provider === '{{ $code }}' ? 'border-emerald-500 bg-emerald-50/30 text-emerald-700 shadow-sm' : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                            <span class="text-sm font-black text-slate-800">{{ $code }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- E-Wallet Sub-Options (GoPay, OVO, DANA) -->
                            <div x-show="method === 'wallet'" class="space-y-2 pt-2" x-cloak x-transition>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Provider E-Wallet</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    @foreach(['GoPay' => 'Gopay / QRIS', 'OVO' => 'OVO Wallet', 'DANA' => 'DANA Wallet'] as $code => $name)
                                        <button type="button" @click="provider = '{{ $code }}'"
                                                class="py-3.5 border-2 rounded-xl text-[11px] font-extrabold flex flex-col items-center justify-center transition-all select-none cursor-pointer focus:outline-none"
                                                :class="provider === '{{ $code }}' ? 'border-emerald-500 bg-emerald-50/30 text-emerald-700 shadow-sm' : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                            <span class="text-sm font-black text-slate-800">{{ $code }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full flex justify-center items-center gap-2 py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-all active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-lock"></i>
                            <span>Selesaikan Pembayaran</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($snapToken) && $snapToken)
    <!-- Midtrans Snap JS -->
    @php
        $isProduction = config('services.midtrans.is_production', false);
        $snapJsUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Function to launch Snap Modal
            function openSnapModal() {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        submitPaymentResult(result, 'success');
                    },
                    onPending: function(result) {
                        submitPaymentResult(result, 'pending');
                    },
                    onError: function(result) {
                        submitPaymentResult(result, 'failed');
                    },
                    onClose: function() {
                        // Stay on the checkout page so they can click the pay button again
                        console.log('Midtrans Snap modal closed');
                    }
                });
            }

            // Pay Button manually triggers Snap Modal
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', openSnapModal);
            }

            function submitPaymentResult(result, status) {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || '{{ csrf_token() }}';

                let vaNumber = null;
                if (result.va_numbers && result.va_numbers.length > 0) {
                    vaNumber = result.va_numbers[0].va_number;
                } else if (result.permata_va_number) {
                    vaNumber = result.permata_va_number;
                } else if (result.biller_code && result.bill_key) {
                    vaNumber = result.bill_key;
                }

                let paymentType = result.payment_type || 'Midtrans Snap';
                if (result.va_numbers && result.va_numbers.length > 0) {
                    const bank = result.va_numbers[0].bank;
                    paymentType = 'Virtual Account (' + bank.toUpperCase() + ')';
                } else if (result.permata_va_number) {
                    paymentType = 'Virtual Account (PERMATA)';
                } else if (result.biller_code && result.bill_key) {
                    paymentType = 'Virtual Account (MANDIRI)';
                }

                fetch('{{ route("payments.complete", $listing->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        transaction_id: result.order_id || '{{ $snapToken }}',
                        payment_type: paymentType,
                        payment_status: status,
                        va_number: vaNumber
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Terjadi kesalahan memproses pembayaran lokal.');
                    }
                })
                .catch(err => {
                    console.error('Error updating transaction:', err);
                    alert('Gagal merekam data pembayaran lokal. Tapi pembayaran Anda di Sandbox berhasil.');
                });
            }
        });
    </script>
@endif
@endsection
