@extends('layouts.app')
@section('title', 'Daftar sebagai Pemilik Kos (Owner) - KosinAja')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50">
        <!-- Brand Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white mx-auto shadow-md shadow-teal-100">
                <i class="fa-solid fa-house-user text-xl"></i>
            </div>
            <h2 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">Kemitraan Owner Kos</h2>
            <p class="mt-2 text-sm text-slate-500">
                Gabung bersama KosinAja, kelola properti kos Anda secara profesional, dapatkan penyewa terverifikasi, dan nikmati sistem anti-scam.
            </p>
        </div>

        <!-- Benefits checklist -->
        <div class="bg-teal-50/50 rounded-xl p-4 border border-teal-100/50 space-y-2">
            <h3 class="text-xs font-bold text-teal-800 uppercase tracking-wider">Keuntungan Bermitra:</h3>
            <ul class="text-xs text-slate-600 space-y-1">
                <li><i class="fa-solid fa-circle-check text-teal-600 mr-2"></i>Lencana **✅ Verified Kos** setelah verifikasi KTP</li>
                <li><i class="fa-solid fa-circle-check text-teal-600 mr-2"></i>Prioritas listing tampil di urutan teratas pencarian</li>
                <li><i class="fa-solid fa-circle-check text-teal-600 mr-2"></i>Sistem pembayaran aman Rekening Bersama</li>
            </ul>
        </div>

        <form class="mt-6 space-y-4" action="{{ route('register.owner') }}" method="POST">
            @csrf
            
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap Pemilik (Sesuai KTP)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-slate-400"
                        placeholder="Nama Lengkap Pemilik">
                </div>
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email Bisnis</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-slate-400"
                        placeholder="owner@emailproperti.com">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp Aktif</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input id="phone" name="phone" type="tel" autocomplete="tel" required value="{{ old('phone') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-slate-400"
                        placeholder="0812xxxxxxxx">
                </div>
                @error('phone')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Passwords -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-slate-400"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-slate-400"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- Policy check -->
            <p class="text-[11px] text-slate-500 leading-normal">
                Dengan mendaftar sebagai Owner, Anda berkomitmen untuk menyajikan data kos yang akurat, jujur, bebas dari manipulasi, serta siap melakukan **Verifikasi Identitas Resmi**.
            </p>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-md shadow-teal-100 transition-all">
                    Daftar Sebagai Owner
                </button>
            </div>
        </form>

        <!-- Toggle Auth Links -->
        <div class="border-t border-slate-100 pt-6 text-center">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
