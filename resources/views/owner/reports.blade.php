@extends('layouts.dashboard')

@section('title', 'Komplain Penyewa - KosinAja')
@section('header_title', 'Komplain & Laporan Penyewa')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Daftar Komplain & Laporan Masuk</h2>
        <p class="text-xs text-slate-400">Daftar keluhan seputar fasilitas atau deskripsi kos yang diajukan calon penyewa</p>
    </div>

    <!-- Security Resolution Alert -->
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm flex items-start space-x-3.5">
        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-amber-600 flex-shrink-0">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
        </div>
        <div>
            <h4 class="font-bold text-slate-800 text-xs">Peringatan Kepatuhan KosinAja</h4>
            <p class="text-[11px] text-slate-500 leading-relaxed mt-1">
                Demi mempertahankan komunitas kos 100% bebas dari scam, setiap laporan masuk dipantau langsung oleh Admin. 
                Jika terdapat keluhan yang valid (seperti lokasi palsu, fasilitas tidak sesuai, atau indikasi scam), properti kos Anda dapat <span class="font-semibold text-red-600">Ditangguhkan (Suspended)</span> secara otomatis demi melindungi pengguna. 
                Kami merekomendasikan Anda untuk segera menghubungi penyewa bersangkutan melalui fitur chat untuk menyelesaikan komplain secepatnya.
            </p>
        </div>
    </div>

    <!-- Reports Card Board -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/55">
                        <th class="py-3 px-5">Kos Bersangkutan</th>
                        <th class="py-3 px-5">Pelapor</th>
                        <th class="py-3 px-5">Kategori Keluhan</th>
                        <th class="py-3 px-5">Rincian Deskripsi</th>
                        <th class="py-3 px-5">Tanggal Masuk</th>
                        <th class="py-3 px-5">Status Laporan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <div class="max-w-[180px]">
                                    <a href="{{ route('listings.show', $report->listing->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-emerald-600 truncate block">
                                        {{ $report->listing->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i>{{ $report->listing->city }}</span>
                                </div>
                            </td>
                            
                            <td class="py-4 px-5">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-[10px] overflow-hidden">
                                        @if($report->reporter->avatar)
                                            <img src="{{ asset($report->reporter->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            {{ Str::upper(Str::substr($report->reporter->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 leading-none">{{ $report->reporter->name }}</p>
                                        <a href="{{ route('chats.conversation', $report->reporter->id) }}" class="text-[9px] text-emerald-600 hover:underline font-bold mt-0.5 block flex items-center">
                                            <i class="fa-regular fa-comment mr-1"></i>Hubungi Pelapor
                                        </a>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-100">
                                    {{ $report->reason }}
                                </span>
                            </td>
                            
                            <td class="py-4 px-5">
                                <div class="max-w-[250px] whitespace-pre-wrap leading-relaxed text-slate-500" title="{{ $report->description }}">
                                    {{ Str::limit($report->description, 100) }}
                                </div>
                            </td>
                            
                            <td class="py-4 px-5 font-medium text-slate-500">
                                {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </td>
                            
                            <td class="py-4 px-5">
                                @if($report->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-100">
                                        <i class="fa-solid fa-circle-exclamation mr-1.5 animate-pulse"></i>Pending Audit
                                    </span>
                                @elseif($report->status === 'resolved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="fa-solid fa-circle-check mr-1.5"></i>Resolved (Closed)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-50 text-slate-600 border border-slate-100">
                                        <i class="fa-solid fa-circle-xmark mr-1.5"></i>Dismissed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-flag text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Komplain Masuk</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Selamat! Properti kos Anda aman dan tidak memiliki laporan keluhan aktif dari calon penyewa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($reports->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
