@extends('layouts.dashboard')

@section('title', 'Pusat Keamanan Platform - KosinAja')
@section('header_title', 'Pusat Keamanan & Audit Firewall')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pusat Operasi Keamanan KosinAja</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Pantau integritas platform, kelola akses firewall, dan audit log peristiwa keamanan secara real-time</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Proteksi Firewall Aktif</span>
        </div>
    </div>

    <!-- Security Statistics Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Shield Status Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-lg flex-shrink-0 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Proteksi</span>
                <span class="text-base font-extrabold text-slate-800 block mt-0.5">Aktif & Aman</span>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-slate-900 text-5xl font-black"><i class="fa-solid fa-shield-halved"></i></div>
        </div>

        <!-- Banned IPs Count Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-500 text-lg flex-shrink-0 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IP Diblokir</span>
                <span class="text-lg font-extrabold text-slate-800 block mt-0.5">{{ $totalBannedIps }} Alamat IP</span>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-slate-900 text-5xl font-black"><i class="fa-solid fa-user-slash"></i></div>
        </div>

        <!-- Total Security Logs Count Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 text-lg flex-shrink-0 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Insiden Tercatat</span>
                <span class="text-lg font-extrabold text-slate-800 block mt-0.5">{{ $totalSecurityEvents }} Peristiwa</span>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-slate-900 text-5xl font-black"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>

        <!-- System Risk Status Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4 relative overflow-hidden group hover:shadow-md transition-all">
            @php
                $isSecured = $riskStatus === 'Aman';
            @endphp
            <div class="w-12 h-12 rounded-xl {{ $isSecured ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-amber-50 border-amber-100 text-amber-500' }} flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-105 transition-transform">
                <i class="fa-solid {{ $isSecured ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Level Risiko</span>
                <span class="text-base font-extrabold {{ $isSecured ? 'text-emerald-600' : 'text-amber-500' }} block mt-0.5">{{ $riskStatus }}</span>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-slate-900 text-5xl font-black"><i class="fa-solid fa-chart-pie"></i></div>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        <!-- Left Side: Firewall IP Blacklisting Management (Column 5) -->
        <div class="xl:col-span-5 space-y-6">
            
            <!-- Quick Blacklist Form -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-1 flex items-center"><i class="fa-solid fa-ban text-red-500 mr-2"></i> Blokir Alamat IP Baru</h3>
                <p class="text-[11px] text-slate-400 font-medium mb-4">Batasi langsung alamat IP penyusup, spammer, atau bot mencurigakan</p>
                
                <form action="{{ route('admin.security.blacklist') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="ip_address" class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Alamat IP</label>
                        <input type="text" name="ip_address" id="ip_address" required placeholder="Contoh: 192.168.1.1" 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all bg-slate-50/50 text-slate-700 font-mono">
                    </div>
                    <div>
                        <label for="reason" class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Alasan Pemblokiran</label>
                        <input type="text" name="reason" id="reason" placeholder="Contoh: Bruteforce login / Spammer booking" 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all bg-slate-50/50 text-slate-700">
                    </div>
                    <button type="submit" class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center justify-center space-x-1.5 shadow-md">
                        <i class="fa-solid fa-lock"></i>
                        <span>Terapkan Blokir Firewall</span>
                    </button>
                </form>
            </div>

            <!-- Blacklisted IP Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center"><i class="fa-solid fa-user-slash text-red-500 mr-2"></i> Daftar Hitam IP Aktif</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Daftar semua IP yang saat ini ditolak aksesnya oleh sistem firewall</p>
                </div>
                
                <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 text-[9px] uppercase font-bold tracking-wider">
                                <th class="py-2.5 px-5">IP Address</th>
                                <th class="py-2.5 px-5">Alasan & Moderator</th>
                                <th class="py-2.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-xs">
                            @forelse($blacklistedIps as $banned)
                                <tr class="hover:bg-slate-50/20 transition-colors">
                                    <td class="py-3.5 px-5 font-mono font-bold text-slate-700 select-all">
                                        {{ $banned->ip_address }}
                                    </td>
                                    <td class="py-3.5 px-5">
                                        <p class="text-slate-700 font-semibold leading-tight">{{ $banned->reason ?? 'Tidak ada alasan' }}</p>
                                        <p class="text-[9px] text-slate-400 mt-0.5">Oleh: {{ $banned->bannedBy->name ?? 'Sistem' }} | {{ $banned->created_at->timezone('Asia/Jakarta')->format('d/m/y H:i') }}</p>
                                    </td>
                                    <td class="py-3.5 px-5 text-right">
                                        <form action="{{ route('admin.security.unblacklist', $banned->ip_address) }}" method="POST" class="inline confirm-form"
                                              data-confirm-title="Hapus Pemblokiran IP?"
                                              data-confirm-text="Apakah Anda yakin ingin memulihkan akses untuk alamat IP '{{ $banned->ip_address }}'?"
                                              data-confirm-button="Ya, Pulihkan Akses"
                                              data-confirm-color="#10b981"
                                              data-confirm-icon="question">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-7 h-7 rounded-lg border border-emerald-100 hover:bg-emerald-50 text-emerald-600 flex items-center justify-center transition-colors shadow-sm" title="Pulihkan Akses IP (Unban)">
                                                <i class="fa-solid fa-unlock-keyhole text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-10 text-center text-slate-400">
                                        <i class="fa-solid fa-shield text-2xl text-slate-200 mb-2"></i>
                                        <p class="text-[11px] font-bold text-slate-600">Tidak ada IP terblokir</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Platform bersih dan tidak memiliki pembatasan IP</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Side: Security Audit Log (Column 7) -->
        <div class="xl:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
                
                <div class="p-5 border-b border-slate-50 flex items-center justify-between flex-shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i> Log Peristiwa & Audit Keamanan</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Jejak audit sistem real-time untuk pemantauan brute-force, suspend, dan login</p>
                    </div>
                    <div class="px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-mono">
                        Realtime Live Feed
                    </div>
                </div>

                <!-- Audit Log Scrollable Area -->
                <div class="overflow-y-auto max-h-[580px] custom-scrollbar flex-grow">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="sticky top-0 bg-slate-900 text-slate-200 text-[9px] uppercase font-bold tracking-wider z-10 shadow-sm">
                                <th class="py-3 px-5">Risiko & Peristiwa</th>
                                <th class="py-3 px-5">Deskripsi Audit</th>
                                <th class="py-3 px-5">Alamat IP</th>
                                <th class="py-3 px-5">Waktu Kejadian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                            @forelse($securityLogs as $log)
                                <tr class="hover:bg-slate-50/20 transition-colors">
                                    <!-- Risiko & Peristiwa -->
                                    <td class="py-3.5 px-5">
                                        <div>
                                            @if($log->risk_level === 'critical')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-red-500 text-white animate-pulse border border-red-600">
                                                    KRITIS
                                                </span>
                                            @elseif($log->risk_level === 'high')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                                    TINGGI
                                                </span>
                                            @elseif($log->risk_level === 'medium')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    SEDANG
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    RENDAH
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 font-bold text-[9px] uppercase tracking-wide text-slate-400">
                                            @php
                                                $eventTypeTranslations = [
                                                    'login_failed' => 'LOGIN GAGAL',
                                                    'login_success' => 'LOGIN BERHASIL',
                                                    'permanent_suspension' => 'PENANGGUHAN PERMANEN',
                                                    'temporary_suspension' => 'PENANGGUHAN SEMENTARA',
                                                    'ip_banned' => 'IP DIBLOKIR',
                                                    'ip_unbanned' => 'BLOKIR IP DILEPAS',
                                                    'listing_suspended' => 'KOS DITANGGUHKAN',
                                                    'manual_audit' => 'AUDIT MANUAL'
                                                ];
                                                $translatedEvent = $eventTypeTranslations[$log->event_type] ?? str_replace('_', ' ', $log->event_type);
                                            @endphp
                                            {{ $translatedEvent }}
                                        </div>
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="py-3.5 px-5">
                                        <p class="font-medium text-slate-800 leading-normal max-w-[280px]">
                                            {{ $log->description }}
                                        </p>
                                        @if($log->user)
                                            <p class="text-[9px] text-slate-400 mt-1"><i class="fa-solid fa-user-shield mr-1"></i>Oleh: {{ $log->user->name }} (UID: #{{ $log->user->id }})</p>
                                        @endif
                                    </td>

                                    <!-- IP -->
                                    <td class="py-3.5 px-5 font-mono text-slate-500 font-semibold select-all">
                                        {{ $log->ip_address }}
                                    </td>

                                    <!-- Waktu -->
                                    <td class="py-3.5 px-5 text-slate-400 font-medium">
                                        <p class="text-[10px] font-semibold text-slate-500">{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</p>
                                        <p class="text-[9px] font-mono mt-0.5">{{ $log->created_at->timezone('Asia/Jakarta')->format('H:i:s') }} WIB</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center text-slate-400">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100 shadow-inner">
                                            <i class="fa-solid fa-shield-halved text-2xl"></i>
                                        </div>
                                        <p class="font-bold text-slate-700 text-sm">Belum Ada Audit Peristiwa</p>
                                        <p class="text-xs text-slate-400 mt-1">Kejadian atau audit terkait keamanan belum tercatat oleh sistem filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
