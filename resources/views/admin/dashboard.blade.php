@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - KosinAja')
@section('header_title', 'Ringkasan Analitik Sistem')

@section('content')
<div class="space-y-6">
    <!-- Quick Analytics Stats Card Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-5">
        
        <!-- Total Users -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-3 sm:p-5 shadow-sm flex items-center space-x-2 sm:space-x-4">
            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base sm:text-xl flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block truncate">Renter</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800 leading-none block mt-0.5 sm:mt-1">{{ $totalUsers }}</span>
                <span class="text-[9px] text-slate-400 hidden sm:block mt-1">Akun pencari kos aktif</span>
            </div>
        </div>

        <!-- Total Owners -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-3 sm:p-5 shadow-sm flex items-center space-x-2 sm:space-x-4">
            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-base sm:text-xl flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-house-chimney-user"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block truncate">Owner</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800 leading-none block mt-0.5 sm:mt-1">{{ $totalOwners }}</span>
                <span class="text-[9px] text-slate-400 hidden sm:block mt-1">Akun penyedia kamar</span>
            </div>
        </div>

        <!-- Total Listings & Verified -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-3 sm:p-5 shadow-sm flex items-center space-x-2 sm:space-x-4">
            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base sm:text-xl flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block truncate">Properti</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800 leading-none block mt-0.5 sm:mt-1">{{ $totalListings }}</span>
                <span class="text-[9px] text-emerald-600 font-bold block mt-0.5 sm:mt-1"><i class="fa-solid fa-circle-check mr-0.5"></i>{{ $verifiedListings }} Verif</span>
            </div>
        </div>

        <!-- Total Escrow Revenue -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-3 sm:p-5 shadow-sm flex items-center space-x-2 sm:space-x-4">
            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base sm:text-xl flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block truncate">Dana Rekber</span>
                <span class="text-sm sm:text-xl font-extrabold text-slate-800 leading-none block mt-0.5 sm:mt-1 truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="text-[9px] text-indigo-500 hidden sm:block font-bold mt-1"><i class="fa-solid fa-lock mr-0.5"></i>Held in Escrow</span>
            </div>
        </div>

        <!-- Profit Platform (5% Commission) -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-3 sm:p-5 shadow-sm flex items-center space-x-2 sm:space-x-4">
            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base sm:text-xl flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block truncate">Bagi Hasil (5%)</span>
                <span class="text-sm sm:text-xl font-extrabold text-slate-800 leading-none block mt-0.5 sm:mt-1 truncate">Rp {{ number_format($platformCommission, 0, ',', '.') }}</span>
                <span class="text-[9px] text-rose-500 hidden sm:block font-bold mt-1"><i class="fa-solid fa-circle-dollar-to-slot mr-0.5"></i>Profit KosinAja</span>
            </div>
        </div>
        
    </div>

    <!-- Secondary Analytics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <!-- Successful Transactions count -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Pembayaran Sukses</span>
                <span class="text-base font-extrabold text-slate-800 leading-none block mt-0.5">{{ $successfulPaymentsCount }} Transaksi</span>
            </div>
        </div>

        <!-- Verified Users ratio -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Pengguna Terverifikasi</span>
                <span class="text-base font-extrabold text-slate-800 leading-none block mt-0.5">{{ $verifiedUsersCount }} Terverifikasi</span>
            </div>
        </div>

        <!-- Resolution rate -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-square-poll-vertical"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Penyelesaian Aduan</span>
                <span class="text-base font-extrabold text-slate-800 leading-none block mt-0.5">{{ $reportResolutionRate }}% Selesai</span>
            </div>
        </div>
        
    </div>

    <!-- Sub Stats Warning Badges -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <a href="{{ route('admin.verifications.index') }}" class="p-4 rounded-2xl bg-amber-50/50 hover:bg-amber-50 border border-amber-200/50 flex items-center justify-between transition-colors shadow-sm">
            <div class="flex items-center space-x-3 text-amber-800">
                <i class="fa-solid fa-id-card-clip text-xl animate-pulse"></i>
                <div>
                    <h4 class="font-bold text-slate-800 text-xs">Menunggu Verifikasi KTP</h4>
                    <p class="text-[10px] text-slate-400">Total berkas baru pemilik kos terdaftar yang siap diulas</p>
                </div>
            </div>
            <span class="w-6 h-6 rounded-full bg-amber-500 text-slate-900 text-xs font-extrabold flex items-center justify-center shadow-sm">{{ $pendingVerifications }}</span>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="p-4 rounded-2xl bg-red-50/50 hover:bg-red-50 border border-red-200/50 flex items-center justify-between transition-colors shadow-sm">
            <div class="flex items-center space-x-3 text-red-800">
                <i class="fa-solid fa-triangle-exclamation text-xl animate-bounce"></i>
                <div>
                    <h4 class="font-bold text-slate-800 text-xs">Laporan Keluhan Penyewa</h4>
                    <p class="text-[10px] text-slate-400">Total pengaduan renter terhadap indikasi penipuan kos</p>
                </div>
            </div>
            <span class="w-6 h-6 rounded-full bg-red-500 text-white text-xs font-extrabold flex items-center justify-center shadow-sm">{{ $pendingReports }}</span>
        </a>
    </div>

    <!-- Interactive Analytics Charts (Line & Pie/Doughnut) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Revenue Trend Line Chart (Takes 2 columns on large screens) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Tren Pendapatan Escrow</h3>
                        <p class="text-[11px] text-slate-400">Total transaksi sewa kos sukses held in Rekber (6 Bulan Terakhir)</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100 flex items-center">
                        <i class="fa-solid fa-arrow-trend-up mr-1 text-xs"></i> 6 Bulan Terakhir
                    </span>
                </div>
                <div class="h-[280px] w-full relative">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sebaran Kategori & Peran Pengguna (Takes 1 column) -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Kategori & Pengguna</h3>
                        <p class="text-[11px] text-slate-400">Distribusi gender kos & pendaftaran akun</p>
                    </div>
                </div>
                
                <!-- Toggle Tab for Pie/Doughnut Charts -->
                <div x-data="{ tab: 'gender' }" class="space-y-4">
                    <div class="flex p-0.5 rounded-lg bg-slate-100 text-xs font-semibold">
                        <button @click="tab = 'gender'" :class="tab === 'gender' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-grow py-1.5 rounded-md transition-all text-center">Kategori Kos</button>
                        <button @click="tab = 'roles'" :class="tab === 'roles' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-grow py-1.5 rounded-md transition-all text-center">Peran Pengguna</button>
                    </div>

                    <div x-show="tab === 'gender'" class="h-[220px] w-full relative flex items-center justify-center">
                        <canvas id="kosGenderChart"></canvas>
                    </div>
                    
                    <div x-show="tab === 'roles'" class="h-[220px] w-full relative flex items-center justify-center" x-cloak>
                        <canvas id="userRolesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Queues Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Queue 1: Verifications Queue -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Antrean Verifikasi KTP Owner</h3>
                    <p class="text-[11px] text-slate-400">Owner menunggu peninjauan identitas KTP & Video</p>
                </div>
                <a href="{{ route('admin.verifications.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-[9px] uppercase font-bold tracking-wider bg-slate-50/30">
                            <th class="py-2.5 px-4">Nama Pengguna</th>
                            <th class="py-2.5 px-4 text-center">Berkas</th>
                            <th class="py-2.5 px-4 text-right">Aksi Audit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                        @forelse($recentVerifications as $verification)
                            <tr class="hover:bg-slate-50/20 transition-colors">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-800">{{ $verification->user->name }}</p>
                                    <p class="text-[9px] text-slate-400 truncate max-w-[150px]">{{ $verification->user->email }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-1.5 text-xs text-slate-400">
                                        <i class="fa-regular fa-image text-emerald-500" title="Ada KTP"></i>
                                        <i class="fa-regular fa-face-smile text-emerald-500" title="Ada Selfie"></i>
                                        @if($verification->verification_video)
                                            <i class="fa-solid fa-video text-emerald-500" title="Ada Video Rekaman"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                         <!-- Approve form -->
                                         <form action="{{ route('admin.verifications.approve', $verification->id) }}" method="POST" class="inline confirm-form"
                                               data-confirm-title="Setujui Verifikasi KTP?"
                                               data-confirm-text="Apakah Anda yakin berkas identitas pemilik kos {{ $verification->user->name }} sudah benar dan absah? Akun owner ini akan berstatus TERVERIFIKASI."
                                               data-confirm-button="Ya, Setujui"
                                               data-confirm-color="#10b981"
                                               data-confirm-icon="success">
                                             @csrf
                                             <button type="submit" class="px-2 py-1.5 rounded bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white text-[10px] font-bold border border-emerald-100 transition-all flex items-center shadow-sm">
                                                 <i class="fa-solid fa-check mr-1"></i>Setuju
                                             </button>
                                         </form>
                                        
                                        <!-- Reject popup sheet -->
                                        <a href="{{ route('admin.verifications.index') }}" class="px-2 py-1.5 rounded bg-red-50 hover:bg-red-500 text-red-600 hover:text-white text-[10px] font-bold border border-red-100 transition-all flex items-center">
                                            <i class="fa-solid fa-xmark mr-1"></i>Tolak
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    <p class="text-xs font-medium">Antrean verifikasi kosong</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Queue 2: Recent Reports Queue -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Aduan Kasus Terkini</h3>
                    <p class="text-[11px] text-slate-400">Keluhan aktif dari penyewa seputar manipulasi kos</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-[9px] uppercase font-bold tracking-wider bg-slate-50/30">
                            <th class="py-2.5 px-4">Info Kos & Kasus</th>
                            <th class="py-2.5 px-4">Pelapor</th>
                            <th class="py-2.5 px-4 text-right">Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                        @forelse($recentReports as $report)
                            <tr class="hover:bg-slate-50/20 transition-colors">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-800 truncate max-w-[180px]">{{ $report->listing->title }}</p>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-50 text-red-600 border border-red-100 mt-0.5">
                                        {{ $report->reason }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-500">
                                    {{ $report->reporter->name }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                         <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="inline confirm-form"
                                               data-confirm-title="Tindak & Suspend Kos?"
                                               data-confirm-text="Apakah Anda yakin ingin membekukan (suspend) properti kos '{{ $report->listing->title }}' atas aduan renter?"
                                               data-confirm-button="Ya, Suspend"
                                               data-confirm-color="#ef4444"
                                               data-confirm-icon="warning">
                                             @csrf
                                             <button type="submit" class="px-2 py-1.5 rounded bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold shadow-sm transition-all" title="Bekukan iklan & suspend kos">
                                                 Suspend
                                             </button>
                                         </form>
                                         
                                         <form action="{{ route('admin.reports.dismiss', $report->id) }}" method="POST" class="inline confirm-form"
                                               data-confirm-title="Abaikan Laporan?"
                                               data-confirm-text="Apakah Anda yakin ingin mengabaikan aduan renter terhadap properti kos '{{ $report->listing->title }}'?"
                                               data-confirm-button="Ya, Abaikan"
                                               data-confirm-color="#64748b"
                                               data-confirm-icon="question">
                                             @csrf
                                             <button type="submit" class="px-2 py-1.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] font-bold transition-all" title="Abaikan komplain">
                                                 Abaikan
                                             </button>
                                         </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    <p class="text-xs font-medium">Tidak ada laporan keluhan pending</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Line Chart: Tren Pendapatan
    const ctxRevenue = document.getElementById('revenueTrendChart').getContext('2d');
    
    // Create soft emerald gradient
    const gradientEmerald = ctxRevenue.createLinearGradient(0, 0, 0, 300);
    gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: @json($revenueMonths),
            datasets: [{
                label: 'Pendapatan Escrow (Rp)',
                data: @json($revenueData),
                borderColor: '#10b981', // Emerald-500
                borderWidth: 3,
                backgroundColor: gradientEmerald,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#10b981',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#e2e8f0',
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Instrument Sans',
                            size: 10
                        },
                        color: '#64748b'
                    }
                },
                y: {
                    grid: {
                        color: '#f1f5f9',
                        drawTicks: false
                    },
                    ticks: {
                        font: {
                            family: 'Instrument Sans',
                            size: 10
                        },
                        color: '#64748b',
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact', compactDisplay: 'short' }).format(value);
                        }
                    },
                    border: {
                        dash: [5, 5]
                    }
                }
            }
        }
    });

    // 2. Pie Chart: Kategori Kos (Gender Type)
    const ctxGender = document.getElementById('kosGenderChart').getContext('2d');
    const genderLabels = Object.keys(@json($genderCounts));
    const genderValues = Object.values(@json($genderCounts));
    
    const genderChart = new Chart(ctxGender, {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: genderValues,
                backgroundColor: [
                    '#0284c7', // sky-600 for Putra
                    '#ec4899', // pink-500 for Putri
                    '#10b981'  // emerald-500 for Campur
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: 'Instrument Sans',
                            size: 11,
                            weight: '600'
                        },
                        color: '#334155'
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const val = context.parsed;
                            const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${val} Properti (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 3. Doughnut Chart: User Roles
    const ctxRoles = document.getElementById('userRolesChart').getContext('2d');
    const rolesLabels = Object.keys(@json($roleCounts));
    const rolesValues = Object.values(@json($roleCounts));

    const rolesChart = new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: rolesLabels,
            datasets: [{
                data: rolesValues,
                backgroundColor: [
                    '#38bdf8', // sky-400 for Renter
                    '#059669', // emerald-600 for Owner
                    '#6366f1'  // indigo-500 for Admin
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                cutout: '65%',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: 'Instrument Sans',
                            size: 11,
                            weight: '600'
                        },
                        color: '#334155'
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const val = context.parsed;
                            const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${val} Akun (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
