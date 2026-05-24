@extends('layouts.dashboard')

@section('title', 'Kelola Fasilitas & Tipe Penghuni - KosinAja')
@section('header_title', 'Kelola Atribut Kos')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Manajemen Atribut Properti Kos</h2>
        <p class="text-xs text-slate-400 mt-0.5">Kelola daftar fasilitas penunjang serta jenis tipe penghuni yang tersedia di platform KosinAja</p>
    </div>

    <!-- Dynamic Customizations: Facilities & Resident Types -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left: Manage Facilities -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                <h3 class="text-sm font-black text-slate-800 flex items-center">
                    <i class="fa-solid fa-bath text-emerald-500 mr-2"></i>Kelola Fasilitas Kos
                </h3>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                    {{ $facilities->count() }} Fasilitas
                </span>
            </div>

            <!-- List of existing facilities -->
            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1.5 space-y-2">
                @foreach($facilities as $facility)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/50 transition-colors">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-white border border-slate-200/60 flex items-center justify-center text-slate-500 text-xs font-semibold">
                                <i class="fa-solid fa-{{ $facility->icon ?? 'circle' }}"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 capitalize">{{ $facility->name }}</span>
                        </div>
                        
                        <form action="{{ route('admin.settings.facilities.destroy', $facility->id) }}" method="POST" class="confirm-form inline"
                              data-confirm-title="Hapus Fasilitas?"
                              data-confirm-text="Apakah Anda yakin ingin menghapus fasilitas '{{ $facility->name }}'? Ini akan melepas fasilitas dari semua properti kos."
                              data-confirm-button="Ya, Hapus"
                              data-confirm-color="#ef4444"
                              data-confirm-icon="warning">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1 cursor-pointer" title="Hapus Fasilitas">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Form: Add Facility -->
            <form action="{{ route('admin.settings.facilities.store') }}" method="POST" class="space-y-4 pt-4 border-t border-slate-50">
                @csrf
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tambah Fasilitas Baru</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="fac_name" class="text-[9px] font-bold text-slate-500 block mb-1">Nama Fasilitas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="fac_name" placeholder="Contoh: AC, WiFi" required
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                    </div>
                    <div>
                        <label for="fac_icon" class="text-[9px] font-bold text-slate-500 block mb-1">Icon (FontAwesome) <span class="text-red-500">*</span></label>
                        <input type="text" name="icon" id="fac_icon" placeholder="Contoh: wind, wifi, tv" required
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 block"><i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i>Gunakan suffix class FontAwesome (misal `wind` untuk AC, `wifi` untuk internet).</span>
                <button type="submit" class="w-full h-9 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-md shadow-emerald-500/5 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Fasilitas</span>
                </button>
            </form>
        </div>

        <!-- Right: Manage Resident Types -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                <h3 class="text-sm font-black text-slate-800 flex items-center">
                    <i class="fa-solid fa-person-half-dress text-indigo-500 mr-2"></i>Kelola Tipe Penghuni
                </h3>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold">
                    {{ count($genderTypes) }} Tipe
                </span>
            </div>

            <!-- List of existing resident types -->
            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1.5 space-y-2">
                @foreach($genderTypes as $type)
                    @php
                        $isDefault = in_array($type, ['putra', 'putri', 'campur']);
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/50 transition-colors">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-white border border-slate-200/60 flex items-center justify-center text-slate-500 text-xs font-semibold">
                                <i class="fa-solid fa-circle-user {{ $type === 'putra' ? 'text-sky-500' : ($type === 'putri' ? 'text-pink-500' : 'text-purple-500') }}"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 capitalize">{{ $type }}</span>
                        </div>
                        
                        @if(!$isDefault)
                            <form action="{{ route('admin.settings.gender-types.destroy', $type) }}" method="POST" class="confirm-form inline"
                                  data-confirm-title="Hapus Tipe?"
                                  data-confirm-text="Apakah Anda yakin ingin menghapus tipe penghuni '{{ $type }}'?"
                                  data-confirm-button="Ya, Hapus"
                                  data-confirm-color="#ef4444"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1 cursor-pointer" title="Hapus Tipe">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-200/60 px-2 py-0.5 rounded">Sistem</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Form: Add Resident Type -->
            <form action="{{ route('admin.settings.gender-types.store') }}" method="POST" class="space-y-4 pt-4 border-t border-slate-50">
                @csrf
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tambah Tipe Penghuni Baru</h4>
                <div>
                    <label for="res_name" class="text-[9px] font-bold text-slate-500 block mb-1">Nama Tipe Penghuni <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="res_name" placeholder="Contoh: Pasutri, Karyawan, Mahasiswa" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition bg-slate-50/30 text-slate-700 font-semibold">
                </div>
                <span class="text-[9px] text-slate-400 block"><i class="fa-solid fa-lightbulb text-indigo-500 mr-1"></i>Gunakan nama yang ringkas, menarik, dan informatif (misal `Pasutri`, `Karyawan`).</span>
                <button type="submit" class="w-full h-9 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-md shadow-indigo-500/5 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Tipe</span>
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection
