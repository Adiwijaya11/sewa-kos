@extends('layouts.app')
@section('title', 'Riwayat Transaksi & Booking Saya - KosinAja')

@section('content')

{{-- Cancel Confirmation Modal --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden animate-modal">
        <div id="cancelModalHeader" class="bg-gradient-to-r from-red-500 to-rose-600 p-6 text-white text-center">
            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i id="cancelModalIcon" class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h2 id="cancelModalTitle" class="text-base font-black">Batalkan Booking?</h2>
            <p id="cancelModalSubtitle" class="text-xs text-red-100 mt-1">Tindakan ini tidak dapat dibatalkan</p>
        </div>

        <div class="p-6 space-y-4">
            {{-- Fee Warning --}}
            <div id="cancelFeeBox" class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center space-y-1">
                <p id="cancelFeeLabel" class="text-xs text-amber-700 font-medium">Biaya pembatalan (0.5% dari nilai booking)</p>
                <p id="cancelFeeDisplay" class="text-xl font-black text-amber-600">Rp 0</p>
                <p id="cancelFeeNote" class="text-[10px] text-amber-500">akan ditagihkan kepada Anda</p>
            </div>

            {{-- Reason Input --}}
            <div id="cancelReasonBox">
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Alasan Pembatalan</label>
                <select id="cancelReason" class="w-full border border-slate-200 rounded-xl text-xs px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    <option value="Dibatalkan oleh penyewa">Saya tidak jadi menyewa</option>
                    <option value="Ingin ganti kos lain">Ingin pilih kos yang lain</option>
                    <option value="Kondisi keuangan">Kondisi keuangan berubah</option>
                    <option value="Kos sudah tidak tersedia">Kos sudah tidak tersedia</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div id="disputeReasonBox" class="hidden">
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Alasan Ketidaksesuaian</label>
                <select id="disputeReason" class="w-full border border-slate-200 rounded-xl text-xs px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="Kamar tidak sesuai foto/deskripsi">Kamar tidak sesuai foto/deskripsi</option>
                    <option value="Fasilitas tidak tersedia saat survei">Fasilitas tidak tersedia saat survei</option>
                    <option value="Lokasi tidak sesuai">Lokasi tidak sesuai informasi</option>
                    <option value="Kondisi kamar buruk/kotor">Kondisi kamar buruk/kotor</option>
                    <option value="Harga berbeda dari yang ditawarkan">Harga berbeda dari yang ditawarkan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <button onclick="closeCancelModal()"
                    class="flex-1 border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-bold px-4 py-3 rounded-xl transition-all">
                    Tidak, Kembali
                </button>
                <form id="cancelForm" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="reason" id="cancelReasonInput">
                    <button type="submit" id="cancelSubmitBtn"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-black px-4 py-3 rounded-xl shadow-md shadow-red-100 transition-all">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Riwayat Transaksi & Booking</h1>
                <p class="text-xs text-slate-400 mt-1">Pantau status booking aman Rekber KosinAja dan kelola pembayaran kos Anda.</p>
            </div>
            <a href="{{ route('search') }}" class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-600 shadow-sm transition-all">
                <i class="fa-solid fa-house-chimney text-emerald-500"></i>
                <span>Cari Hunian Kos</span>
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('warning'))
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium rounded-2xl p-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 shrink-0"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-xs font-medium rounded-2xl p-4">
                <i class="fa-solid fa-circle-xmark text-red-500 mt-0.5 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-2xl p-4">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Bookings List --}}
        <div class="space-y-4 max-h-[550px] overflow-y-auto pr-2 custom-scrollbar">
            @forelse($payments as $payment)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                    {{-- Card Body --}}
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">

                        {{-- Thumbnail --}}
                        <div class="w-full sm:w-20 h-40 sm:h-20 rounded-2xl overflow-hidden bg-slate-100 border border-slate-50 shrink-0">
                            @if($payment->listing->images->count() > 0)
                                <img src="{{ asset($payment->listing->images->first()->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fa-solid fa-image text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 space-y-1.5">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block truncate">{{ $payment->transaction_id }}</span>
                            <h3 class="text-sm font-extrabold text-slate-800 leading-snug truncate">
                                <a href="{{ route('listings.show', $payment->listing->slug) }}" class="hover:text-emerald-600 transition-colors">
                                    {{ $payment->listing->title }}
                                </a>
                            </h3>
                            <div class="flex flex-wrap gap-x-3 gap-y-0.5">
                                <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-user-tie text-[9px]"></i>
                                    {{ $payment->listing->owner->name }}
                                </p>
                                <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar text-[9px]"></i>
                                    {{ $payment->created_at->translatedFormat('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            {{-- Cancelled reason / fee info --}}
                            @if($payment->payment_status === 'cancelled' && $payment->cancellation_fee > 0)
                                <div class="flex items-center gap-1.5 bg-red-50 border border-red-100 rounded-lg px-2.5 py-1.5 w-fit">
                                    <i class="fa-solid fa-circle-dollar-to-slot text-red-400 text-[10px]"></i>
                                    <span class="text-[10px] font-bold text-red-600">Denda: Rp {{ number_format($payment->cancellation_fee, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            {{-- VA Number info --}}
                            @if($payment->payment_status === 'pending' && $payment->va_number)
                                <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 rounded-lg px-2.5 py-1.5 w-fit mt-1">
                                    <i class="fa-solid fa-building-columns text-amber-500 text-[10px]"></i>
                                    <span class="text-[10px] font-bold text-amber-700">VA / Kode Bayar: <code class="bg-white px-1.5 py-0.5 rounded border border-amber-200">{{ $payment->va_number }}</code></span>
                                </div>
                            @endif
                        </div>

                        {{-- Price + Status + Actions --}}
                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 shrink-0">

                            {{-- Price --}}
                            <div class="text-left sm:text-right">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total</span>
                                <span class="text-sm sm:text-base font-black text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 bg-slate-50 border border-slate-100 rounded-md px-2 py-0.5 block w-fit mt-0.5">
                                    {{ $payment->payment_type ?? 'Metode belum dipilih' }}
                                </span>
                            </div>

                            {{-- Status Badge --}}
                            @if($payment->payment_status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 border border-emerald-200 text-[10px] font-bold text-emerald-800 whitespace-nowrap">
                                    <i class="fa-solid fa-house-circle-check text-emerald-600"></i> Sudah Check-In
                                </span>
                            @elseif($payment->payment_status === 'success')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-[10px] font-bold text-emerald-600 whitespace-nowrap">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i> Lunas
                                </span>
                            @elseif($payment->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-100 text-[10px] font-bold text-amber-600 whitespace-nowrap">
                                    <i class="fa-solid fa-clock text-amber-500"></i> Menunggu
                                </span>
                            @elseif($payment->payment_status === 'cancelled')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-500 whitespace-nowrap">
                                    <i class="fa-solid fa-ban text-slate-400"></i> Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-100 text-[10px] font-bold text-red-600 whitespace-nowrap">
                                    <i class="fa-solid fa-circle-xmark text-red-500"></i> Gagal
                                </span>
                            @endif

                        </div>
                    </div>

                    {{-- Action Bar (bottom) --}}
                    <div class="border-t border-slate-50 px-4 sm:px-5 py-3 flex flex-wrap items-center gap-2 bg-slate-50/50">

                        @if($payment->payment_status === 'pending')
                            @if(str_contains($payment->transaction_id, 'TRX-MANUAL-'))
                                <a href="{{ route('payments.instruction', $payment->id) }}"
                                   class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                    <span>Instruksi Bayar</span>
                                </a>
                            @else
                                <a href="{{ route('payments.checkout', $payment->listing_id) }}"
                                   class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Bayar Sekarang</span>
                                </a>
                            @endif

                            {{-- Cancel button --}}
                            <button
                                onclick="openCancelModal('{{ route('payments.cancel', $payment->id) }}', {{ $payment->amount }})"
                                class="flex items-center gap-1.5 border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold px-4 py-2.5 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Batalkan Booking</span>
                            </button>

                        @elseif($payment->payment_status === 'success')
                            <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-lock"></i> Dana Ditahan Rekber KosinAja
                            </span>
                            <div class="ml-auto flex items-center gap-2">
                                {{-- Confirm Check-in Button --}}
                                <form action="{{ route('payments.confirm_checkin', $payment->id) }}" method="POST" class="inline confirm-form"
                                      data-confirm-title="Konfirmasi Masuk Kos?"
                                      data-confirm-text="Apakah Anda yakin sudah melakukan survei dan resmi masuk/menempati kamar kos ini? Setelah dikonfirmasi, dana sewa Anda akan segera dicairkan ke Pemilik Kos."
                                      data-confirm-button="Ya, Konfirmasi Masuk"
                                      data-confirm-color="#10b981"
                                      data-confirm-icon="success">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-3 py-2.5 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all cursor-pointer">
                                        <i class="fa-solid fa-house-circle-check"></i>
                                        <span>Konfirmasi Masuk Kos</span>
                                    </button>
                                </form>

                                {{-- Dispute button (kamar tidak sesuai) --}}
                                <button
                                    onclick="openDisputeModal('{{ route('payments.cancel', $payment->id) }}', {{ $payment->amount }})"
                                    class="flex items-center gap-1.5 border border-orange-200 bg-orange-50 hover:bg-orange-100 text-orange-600 text-[11px] font-bold px-3 py-2.5 rounded-xl transition-all cursor-pointer">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>Kamar Tidak Sesuai</span>
                                </button>
                                <a href="{{ route('chats.conversation', $payment->listing->owner_id) }}"
                                   class="flex items-center gap-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-[11px] font-bold text-slate-600 px-3 py-2 rounded-xl transition-all">
                                    <i class="fa-regular fa-comments text-emerald-500"></i>
                                    <span>Chat Owner</span>
                                </a>
                            </div>

                        @elseif($payment->payment_status === 'completed')
                            @php
                                $startDate = $payment->checked_in_at ?? $payment->updated_at;
                                $expiryDate = $startDate->copy()->addDays(30);
                                $canExtend = now()->greaterThanOrEqualTo($expiryDate->copy()->subDay());
                                $daysRemaining = round(now()->diffInDays($expiryDate, false));
                            @endphp

                            <span class="text-[10px] text-emerald-700 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-house-circle-check"></i> Dana telah dicairkan ke Owner &bull; Masuk Kos pada {{ $payment->checked_in_at ? $payment->checked_in_at->translatedFormat('d M Y') : '' }}
                            </span>
                            <div class="ml-auto flex items-center gap-2">
                                @if($canExtend)
                                    <a href="{{ route('payments.checkout', $payment->listing_id) }}"
                                       class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-3 py-2 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all cursor-pointer">
                                        <i class="fa-solid fa-rotate text-[10px]"></i>
                                        <span>Perpanjang Sewa Kos</span>
                                    </a>
                                @endif
                                <a href="{{ route('chats.conversation', $payment->listing->owner_id) }}"
                                   class="flex items-center gap-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-[11px] font-bold text-slate-600 px-4 py-2 rounded-xl transition-all">
                                    <i class="fa-regular fa-comments text-emerald-500"></i>
                                    <span>Chat Owner</span>
                                </a>
                            </div>

                            @if($canExtend)
                                <div class="w-full mt-3 bg-emerald-50 border border-emerald-100/50 rounded-2xl p-4 flex items-start gap-3 text-emerald-850">
                                    <i class="fa-solid fa-clock text-emerald-600 mt-0.5 shrink-0 text-sm animate-pulse"></i>
                                    <div>
                                        <h4 class="text-xs font-bold text-emerald-800">Masa Sewa Berakhir</h4>
                                        <p class="text-[10px] text-slate-500 leading-relaxed mt-1">
                                            @if($daysRemaining >= 0)
                                                Masa sewa kamar Kos ini akan berakhir dalam waktu <strong>{{ $daysRemaining }} hari</strong> lagi (pada tanggal {{ $expiryDate->translatedFormat('d F Y') }}). Silakan lakukan perpanjangan sewa secepatnya untuk menjamin kamar tetap menjadi milik Anda.
                                            @else
                                                Masa sewa kamar Kos ini sudah berakhir pada tanggal <strong>{{ $expiryDate->translatedFormat('d F Y') }}</strong>. Silakan perpanjang sekarang agar status sewa Anda tetap aktif.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                        @elseif($payment->payment_status === 'cancelled')
                            <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                <i class="fa-solid fa-circle-info"></i>
                                {{ $payment->cancellation_reason ?? 'Booking dibatalkan' }}
                                @if($payment->cancelled_at)
                                    &bull; {{ $payment->cancelled_at->translatedFormat('d M Y') }}
                                @endif
                            </span>

                        @else
                            <a href="{{ route('chats.conversation', $payment->listing->owner_id) }}"
                               class="flex items-center gap-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-[11px] font-bold text-slate-600 px-4 py-2 rounded-xl transition-all">
                                <i class="fa-regular fa-comments text-emerald-500"></i>
                                <span>Chat Owner</span>
                            </a>
                        @endif

                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-slate-200 p-8 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto">
                        <i class="fa-solid fa-receipt text-3xl"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-slate-700">Belum ada riwayat transaksi</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto">Anda belum pernah melakukan booking aman atau mengajukan deposit pembayaran kos.</p>
                    </div>
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-xs font-bold text-white shadow-md shadow-emerald-100 transition-all">
                        <span>Cari & Temukan Kos Terbaik</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endforelse
        </div>



    </div>
</div>

<style>
@keyframes modal-in {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-modal {
    animation: modal-in 0.25s cubic-bezier(0.34,1.56,0.64,1) both;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
function openCancelModal(url, amount) {
    // Pending cancellation: 0.5% fee
    const fee = Math.round(amount * 0.005);
    document.getElementById('cancelFeeDisplay').textContent = 'Rp ' + fee.toLocaleString('id-ID');
    document.getElementById('cancelFeeBox').classList.remove('hidden');
    document.getElementById('cancelFeeLabel').textContent = 'Biaya pembatalan (0.5% dari nilai booking)';
    document.getElementById('cancelFeeNote').textContent = 'akan ditagihkan kepada Anda';
    document.getElementById('cancelFeeBox').className = 'bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center space-y-1';
    document.getElementById('cancelFeeDisplay').className = 'text-xl font-black text-amber-600';
    // Modal style: merah
    document.getElementById('cancelModalHeader').className = 'bg-gradient-to-r from-red-500 to-rose-600 p-6 text-white text-center';
    document.getElementById('cancelModalIcon').className = 'fa-solid fa-triangle-exclamation text-2xl';
    document.getElementById('cancelModalTitle').textContent = 'Batalkan Booking?';
    document.getElementById('cancelModalSubtitle').textContent = 'Tindakan ini tidak dapat dibatalkan';
    document.getElementById('cancelModalSubtitle').className = 'text-xs text-red-100 mt-1';
    // Show/hide reason boxes
    document.getElementById('cancelReasonBox').classList.remove('hidden');
    document.getElementById('disputeReasonBox').classList.add('hidden');

    document.getElementById('cancelForm').action = url;
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openDisputeModal(url, amount) {
    // Room dispute: 0% fee, refund requested
    document.getElementById('cancelFeeBox').classList.remove('hidden');
    document.getElementById('cancelFeeLabel').textContent = 'Biaya pembatalan';
    document.getElementById('cancelFeeDisplay').textContent = 'GRATIS (Rp 0)';
    document.getElementById('cancelFeeDisplay').className = 'text-xl font-black text-emerald-600';
    document.getElementById('cancelFeeNote').textContent = 'Tidak ada denda — proses refund akan diajukan';
    document.getElementById('cancelFeeBox').className = 'bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-center space-y-1';
    // Modal style: orange
    document.getElementById('cancelModalHeader').className = 'bg-gradient-to-r from-orange-500 to-amber-600 p-6 text-white text-center';
    document.getElementById('cancelModalIcon').className = 'fa-solid fa-circle-exclamation text-2xl';
    document.getElementById('cancelModalTitle').textContent = 'Laporkan Ketidaksesuaian?';
    document.getElementById('cancelModalSubtitle').textContent = 'Ajukan pembatalan karena kamar tidak sesuai';
    document.getElementById('cancelModalSubtitle').className = 'text-xs text-orange-100 mt-1';
    // Show/hide reason boxes
    document.getElementById('cancelReasonBox').classList.add('hidden');
    document.getElementById('disputeReasonBox').classList.remove('hidden');

    document.getElementById('cancelForm').action = url;
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('cancelForm').addEventListener('submit', function() {
    const reasonBox = document.getElementById('cancelReasonBox');
    if (!reasonBox.classList.contains('hidden')) {
        document.getElementById('cancelReasonInput').value = document.getElementById('cancelReason').value;
    } else {
        document.getElementById('cancelReasonInput').value = document.getElementById('disputeReason').value;
    }
});

// Close modal on backdrop click
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

// Audio synthesis helper for premium transaction chime (Web Audio API)
function playTransactionChime() {
    try {
        const context = new (window.AudioContext || window.webkitAudioContext)();
        const now = context.currentTime;
        
        // Note 1
        const osc1 = context.createOscillator();
        const gain1 = context.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now); // D5
        osc1.frequency.exponentialRampToValueAtTime(880.00, now + 0.1); // A5
        gain1.gain.setValueAtTime(0.12, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
        osc1.connect(gain1);
        gain1.connect(context.destination);
        osc1.start(now);
        osc1.stop(now + 0.35);
        
        // Note 2
        const osc2 = context.createOscillator();
        const gain2 = context.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880.00, now + 0.08); // A5
        osc2.frequency.exponentialRampToValueAtTime(1174.66, now + 0.22); // D6
        gain2.gain.setValueAtTime(0.12, now + 0.08);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
        osc2.connect(gain2);
        gain2.connect(context.destination);
        osc2.start(now + 0.08);
        osc2.stop(now + 0.45);
    } catch (e) {
        console.warn("Audio Context blocked or unsupported:", e);
    }
}

// Play on load (or fallback on first user interaction)
window.addEventListener('load', () => {
    setTimeout(playTransactionChime, 300);
    
    const playOnFirstInteraction = () => {
        playTransactionChime();
        document.removeEventListener('click', playOnFirstInteraction);
        document.removeEventListener('keydown', playOnFirstInteraction);
    };
    document.addEventListener('click', playOnFirstInteraction);
    document.addEventListener('keydown', playOnFirstInteraction);
});
</script>

@endsection
