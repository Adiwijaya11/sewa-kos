@extends('layouts.app')
@section('title', 'Masuk ke KosinAja')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50">
        <!-- Brand Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white mx-auto shadow-md shadow-emerald-100">
                <i class="fa-solid fa-house-circle-check text-xl"></i>
            </div>
            <h2 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="mt-2 text-sm text-slate-500">
                Cari kos impian Anda atau kelola listing kos Anda
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder-slate-400"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Lupa Password?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required
                            class="block w-full pl-10 pr-10 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder-slate-400"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                    class="h-4 w-4 text-emerald-600 border-slate-300 rounded-md focus:ring-emerald-500 focus:ring-offset-0">
                <label for="remember" class="ml-2 block text-xs font-semibold text-slate-600 select-none">
                    Ingat perangkat ini
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md shadow-emerald-100 transition-all">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Toggle Auth Links -->
        <div class="border-t border-slate-100 pt-6 text-center space-y-2">
            <p class="text-xs text-slate-500">
                Belum punya akun KosinAja?
            </p>
            <div class="flex justify-center space-x-4 text-xs font-bold">
                <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700">Daftar Renter (Pencari Kos)</a>
                <span class="text-slate-200">|</span>
                <a href="{{ route('register.owner') }}" class="text-teal-600 hover:text-teal-700">Daftar Owner (Pemilik Kos)</a>
            </div>
        </div>
    </div>
</div>
@endsection
