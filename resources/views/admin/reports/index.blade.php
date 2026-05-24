@extends('layouts.dashboard')

@section('title', 'Laporan Masuk - KosinAja')
@section('header_title', 'Kelola Laporan Keluhan')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Daftar Komplain & Laporan Pengguna</h2>
        <p class="text-xs text-slate-400">Tinjau laporan dugaan scam, deskripsi palsu, atau keluhan penyewa terhadap properti kos</p>
    </div>

    <!-- Reports Card Board -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/50">
                        <th class="py-3 px-5">Kos Bersangkutan</th>
                        <th class="py-3 px-5">Pemilik (Owner)</th>
                        <th class="py-3 px-5">Pelapor (Renter)</th>
                        <th class="py-3 px-5">Keluhan</th>
                        <th class="py-3 px-5">Rincian Deskripsi</th>
                        <th class="py-3 px-5">Tanggal Masuk</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Moderasi Kasus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            
                            <!-- Kos Bersangkutan -->
                            <td class="py-4 px-5">
                                <div class="max-w-[180px]">
                                    <a href="{{ route('listings.show', $report->listing->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-emerald-600 truncate block">
                                        {{ $report->listing->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i>{{ $report->listing->city }}</span>
                                </div>
                            </td>
                            
                            <!-- Pemilik (Owner) -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-semibold text-slate-800 flex items-center">
                                        {{ $report->listing->owner->name }}
                                        @if($report->listing->owner->is_verified)
                                            <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $report->listing->owner->email }}</p>
                                </div>
                            </td>
                            
                            <!-- Pelapor -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $report->reporter->name }}</p>
                                    <a href="{{ route('chats.conversation', $report->reporter->id) }}" class="text-[9px] text-emerald-600 hover:underline font-bold mt-0.5 block flex items-center">
                                        <i class="fa-regular fa-comment mr-1"></i>Chat Renter
                                    </a>
                                </div>
                            </td>
                            
                            <!-- Keluhan -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-100">
                                    {{ $report->reason }}
                                </span>
                            </td>
                            
                            <!-- Rincian Deskripsi -->
                            <td class="py-4 px-5">
                                <div class="max-w-[200px] whitespace-pre-wrap leading-relaxed text-slate-500" title="{{ $report->description }}">
                                    {{ Str::limit($report->description, 100) }}
                                </div>
                            </td>
                            
                            <!-- Tanggal Masuk -->
                            <td class="py-4 px-5 font-medium text-slate-500">
                                {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </td>
                            
                            <!-- Status -->
                            <td class="py-4 px-5">
                                @if($report->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-100">
                                        <i class="fa-solid fa-circle-exclamation mr-1.5 animate-pulse"></i>Pending Audit
                                    </span>
                                @elseif($report->status === 'resolved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100" title="Kasus diselesaikan. Properti kos otomatis disuspended.">
                                        <i class="fa-solid fa-circle-check mr-1 text-xs"></i>Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-50 text-slate-600 border border-slate-100">
                                        <i class="fa-solid fa-circle-xmark mr-1 text-xs"></i>Dismissed
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Moderasi Kasus -->
                            <td class="py-4 px-5 text-right">
                                @if($report->status === 'pending')
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <!-- Resolve (Suspend listing) -->
                                        <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="inline confirm-form"
                                              data-confirm-title="Tindak & Suspend Kos?"
                                              data-confirm-text="Apakah Anda yakin ingin menyelesaikan laporan ini dan otomatis membekukan properti kos '{{ $report->listing->title }}'? Properti kos ini tidak akan tayang lagi."
                                              data-confirm-button="Ya, Suspend Properti"
                                              data-confirm-color="#ef4444"
                                              data-confirm-icon="warning">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold shadow-sm transition-all" title="Selesaikan laporan dan otomatis bekukan kos">
                                                Tindak / Suspend
                                            </button>
                                        </form>
                                        
                                        <!-- Dismiss -->
                                        <form action="{{ route('admin.reports.dismiss', $report->id) }}" method="POST" class="inline confirm-form"
                                              data-confirm-title="Abaikan Laporan Kasus?"
                                              data-confirm-text="Apakah Anda yakin ingin mengabaikan aduan dari pelapor {{ $report->reporter->name }} terhadap properti kos '{{ $report->listing->title }}'?"
                                              data-confirm-button="Ya, Abaikan"
                                              data-confirm-color="#64748b"
                                              data-confirm-icon="question">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] font-bold transition-all" title="Abaikan komplain">
                                                Abaikan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-semibold italic">Kasus Closed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-flag text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Laporan Aktif</p>
                                <p class="text-xs text-slate-400 mt-1">Platform aman. Tidak ada laporan sengketa atau scam dari pengguna.</p>
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
