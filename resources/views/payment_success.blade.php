@extends('layouts.app')
@section('title', 'Bukti Booking Berhasil - KosinAja')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
        <!-- Success Icon -->
        <div class="w-16 h-16 rounded-full bg-emerald-50 border-4 border-white shadow-md shadow-emerald-100 flex items-center justify-center text-emerald-500 text-3xl mx-auto">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Booking Aman Berhasil!</h1>
            <p class="text-xs text-slate-400">Pembayaran booking deposit Anda berhasil diproteksi oleh Rekening Bersama KosinAja.</p>
        </div>

        <!-- Receipt Box -->
        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 text-left text-xs space-y-3.5">
            <div class="flex justify-between">
                <span class="text-slate-400">ID Transaksi:</span>
                <span class="font-bold text-slate-800 tracking-wider">{{ $payment->transaction_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Kos Terpesan:</span>
                <span class="font-bold text-slate-800 truncate max-w-[200px]" title="{{ $payment->listing->title }}">{{ $payment->listing->title }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Pemilik Kos:</span>
                <span class="font-bold text-slate-800">{{ $payment->listing->owner->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Jumlah Terbayar:</span>
                <span class="font-bold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Status Dana:</span>
                <span class="font-bold text-emerald-600 flex items-center"><i class="fa-solid fa-lock mr-1 text-[10px]"></i>Ditahan (Aman)</span>
            </div>
        </div>

        <!-- Safe next steps -->
        <div class="text-xs text-slate-500 leading-relaxed text-left bg-emerald-50/50 p-4 border border-emerald-100/50 rounded-xl">
            <h4 class="font-bold text-emerald-800 flex items-center mb-1"><i class="fa-solid fa-circle-info mr-1.5"></i>Langkah Aman Selanjutnya:</h4>
            <ol class="list-decimal pl-4 space-y-1">
                <li>Buka menu **Obrolan Chat** untuk berkoordinasi jadwal check-in dengan pemilik kos.</li>
                <li>Datang ke lokasi kos untuk survey serah terima kunci.</li>
                <li>Setelah semuanya cocok, laporkan check-in di menu riwayat transaksi Anda.</li>
            </ol>
        </div>

        <!-- Action Links -->
        <div class="pt-4 space-y-2.5">
            <a href="{{ route('chats.conversation', $payment->listing->owner_id) }}" 
               class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-xs font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all">
                <i class="fa-regular fa-comments mr-2 text-sm"></i>Hubungi Owner Sekarang
            </a>
            
            <a href="{{ route('home') }}" class="block text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
