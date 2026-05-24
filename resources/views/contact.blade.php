@extends('layouts.app')
@section('title', 'Hubungi Kami - KosinAja')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Hubungi Tim Layanan</h1>
        <p class="text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
            Apakah Anda memiliki pertanyaan mengenai verifikasi akun, keamanan sistem booking, atau ingin menyampaikan keluhan penyewa? Kami di sini untuk mendengarkan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mx-auto text-lg"><i class="fa-solid fa-envelope"></i></div>
            <h3 class="text-sm font-bold text-slate-800">Email Hubungan</h3>
            <p class="text-xs text-slate-400">support@kosinaja.com</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mx-auto text-lg"><i class="fa-solid fa-phone"></i></div>
            <h3 class="text-sm font-bold text-slate-800">Telepon Resmi</h3>
            <p class="text-xs text-slate-400">+62 812-3456-7890</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mx-auto text-lg"><i class="fa-solid fa-map-location-dot"></i></div>
            <h3 class="text-sm font-bold text-slate-800">Kantor Operasional</h3>
            <p class="text-xs text-slate-400">Jakarta Selatan, Indonesia</p>
        </div>
    </div>
</div>
@endsection
