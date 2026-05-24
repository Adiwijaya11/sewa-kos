@extends('layouts.app')
@section('title', 'Lupa Kata Sandi - KosinAja')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50">
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 mx-auto">
                <i class="fa-solid fa-key text-xl"></i>
            </div>
            <h2 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
            <p class="mt-2 text-sm text-slate-500">
                Masukkan email Anda yang terdaftar, kami akan mengirimkan link untuk mengatur ulang password Anda.
            </p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-xs font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            
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

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md transition-all">
                    Kirim Link Reset
                </button>
            </div>
        </form>

        <div class="border-t border-slate-100 pt-6 text-center">
            <p class="text-xs">
                <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-emerald-600 transition-colors"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Halaman Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection
