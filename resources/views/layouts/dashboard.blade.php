<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - KosinAja')</title>

    <!-- Tailwind CSS (compiled via Vite) & Bunny Fonts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons & FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Leaflet JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- SweetAlert2 CDN for premium interactive alerts and dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Premium Snappy UI Jump-free & Scroll-restoration Safeguard -->
    <script>
        if (window.location.hash) {
            history.replaceState("", document.title, window.location.pathname + window.location.search);
        }
        window.scrollTo(0, 0);
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', sans-serif; }
        
        /* Hide all scrollbars globally across the entire admin panel */
        * {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        *::-webkit-scrollbar {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Sidebar backdrop (Mobile) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden" x-cloak></div>

    <!-- Sidebar Container -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-400 flex flex-col transition-transform transform lg:translate-x-0 shrink-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo Header -->
        <div class="h-16 px-6 flex items-center border-b border-slate-800">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
                    <i class="fa-solid fa-house-circle-check text-sm"></i>
                </div>
                <span class="text-lg font-bold text-white tracking-wide">KosinAja</span>
            </a>
            <span class="ml-2 text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded-full font-bold uppercase">{{ auth()->user()->role }}</span>
        </div>

        <!-- Sidebar Navigation List -->
        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->role === 'admin')
                <!-- ADMIN ROUTES -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line text-base"></i>
                    <span>Ringkasan Analitik</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users text-base"></i>
                    <span>Kelola Pengguna</span>
                </a>
                <a href="{{ route('admin.verifications.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.verifications.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-id-card-clip text-base"></i>
                    <span>Verifikasi KTP Owner</span>
                    @php
                        $pendingVerifications = \App\Models\OwnerVerification::where('status', 'pending')->count();
                    @endphp
                    @if($pendingVerifications > 0)
                        <span class="ml-auto w-5 h-5 bg-emerald-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center">{{ $pendingVerifications }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.listings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.listings.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-list-check text-base"></i>
                    <span>Moderasi Kos</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <span>Laporan Masuk</span>
                    @php
                        $pendingReports = \App\Models\Report::where('status', 'pending')->count();
                    @endphp
                    @if($pendingReports > 0)
                        <span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $pendingReports }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-receipt text-base"></i>
                    <span>Log Transaksi</span>
                    @php
                        $adminPendingPayments = \App\Models\Payment::whereIn('payment_status', ['pending', 'success'])->count();
                    @endphp
                    @if($adminPendingPayments > 0)
                        <span class="ml-auto w-5 h-5 bg-amber-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse" title="Transaksi pending/sukses baru">{{ $adminPendingPayments }}</span>
                    @endif
                </a>
                <a href="{{ route('chats.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('chats.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-regular fa-comments text-base"></i>
                    <span>Obrolan Chat</span>
                    @php
                        $unreadChats = \App\Models\Chat::where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    @if($unreadChats > 0)
                        <span class="ml-auto w-5 h-5 bg-emerald-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadChats }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.tracking') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.tracking') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-magnifying-glass-location text-base"></i>
                    <span>Lacak Transaksi</span>
                </a>
                <a href="{{ route('admin.earnings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.earnings') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie text-base"></i>
                    <span>Penghasilan Platform</span>
                </a>
                <a href="{{ route('admin.security.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.security.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                    <span>Pusat Keamanan</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.settings.index') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders text-base"></i>
                    <span>Kebijakan & Aturan</span>
                </a>
                <a href="{{ route('admin.settings.attributes') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.settings.attributes') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags text-base"></i>
                    <span>Fasilitas & Tipe Kos</span>
                </a>
            @else
                <!-- OWNER ROUTES -->
                <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-house-chimney text-base"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.listings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.listings.index') || request()->routeIs('owner.listings.edit') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-list text-base"></i>
                    <span>Kos Saya</span>
                </a>
                <a href="{{ route('owner.listings.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.listings.create') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-circle-plus text-base"></i>
                    <span>Tambah Kos</span>
                </a>
                <a href="{{ route('owner.verification.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.verification.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-address-card text-base"></i>
                    <span>Verifikasi Akun</span>
                    @if(auth()->user()->is_verified)
                        <i class="fa-solid fa-circle-check text-emerald-400 ml-auto"></i>
                    @endif
                </a>
                <a href="{{ route('chats.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('chats.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-regular fa-comments text-base"></i>
                    <span>Obrolan Chat</span>
                    @php
                        $unreadChats = \App\Models\Chat::where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    @if($unreadChats > 0)
                        <span class="ml-auto w-5 h-5 bg-emerald-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadChats }}</span>
                    @endif
                </a>
                <a href="{{ route('owner.payments') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.payments') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-wallet text-base"></i>
                    <span>Uang Masuk</span>
                    @php
                        $ownerPaymentsCount = \App\Models\Payment::whereHas('listing', function ($q) {
                            $q->where('owner_id', auth()->id());
                        })->whereIn('payment_status', ['pending', 'success'])->count();
                    @endphp
                    @if($ownerPaymentsCount > 0)
                        <span class="ml-auto w-5 h-5 bg-amber-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse" title="Transaksi pending/sukses baru">{{ $ownerPaymentsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('owner.reports') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('owner.reports') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-flag text-base"></i>
                    <span>Komplain Penyewa</span>
                    @php
                        $ownerPendingReports = \App\Models\Report::where('status', 'pending')
                            ->whereHas('listing', function ($q) {
                                $q->where('owner_id', auth()->id());
                            })->count();
                    @endphp
                    @if($ownerPendingReports > 0)
                        <span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $ownerPendingReports }}</span>
                    @endif
                </a>
            @endif
        </nav>

        <!-- User profile footer inside Sidebar -->
        <div class="p-4 border-t border-slate-800 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-white text-sm font-bold overflow-hidden">
                @if(auth()->user()->avatar)
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="flex-grow min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </aside>

    <!-- Main Right Content Wrapper -->
    <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden lg:pl-64">
        
        <!-- Header Bar -->
        <header class="h-14 sm:h-16 bg-white border-b border-slate-100 px-4 sm:px-6 flex items-center justify-between shadow-sm flex-shrink-0">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-emerald-600">
                <i class="fa-solid fa-bars-staggered text-xl"></i>
            </button>
            
            <h1 class="text-base font-semibold text-slate-800">
                @yield('header_title', 'Dashboard')
            </h1>

            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-emerald-600 font-medium flex items-center transition-colors">
                    <i class="fa-solid fa-globe mr-1.5"></i>Lihat Web
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg border border-red-100 hover:bg-red-50 text-red-600 text-xs font-semibold flex items-center transition-all">
                        <i class="fa-solid fa-right-from-bracket mr-1.5"></i>Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow p-4 sm:p-6 overflow-x-hidden">
            <!-- Session Messages -->
            @if(session('success') || session('warning') || session('status'))
                <div class="mb-6" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="p-4 rounded-xl flex items-center justify-between shadow-sm border {{ session('warning') ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid {{ session('warning') ? 'fa-triangle-exclamation' : 'fa-circle-check' }} text-lg"></i>
                            <span class="text-sm font-medium">{{ session('success') ?? session('warning') ?? session('status') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();

        // Premium SweetAlert2 Confirm Dialog for Forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('confirm-form') || form.hasAttribute('data-confirm')) {
                e.preventDefault();
                const title = form.getAttribute('data-confirm-title') || 'Apakah Anda yakin?';
                const text = form.getAttribute('data-confirm-text') || 'Tindakan ini tidak dapat dibatalkan.';
                const confirmText = form.getAttribute('data-confirm-button') || 'Ya, Lanjutkan';
                const cancelText = form.getAttribute('data-cancel-button') || 'Batal';
                const confirmColor = form.getAttribute('data-confirm-color') || '#10b981'; // Emerald/green
                const icon = form.getAttribute('data-confirm-icon') || 'warning';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#64748b', // Slate
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                        title: 'text-base font-bold text-slate-800',
                        htmlContainer: 'text-xs text-slate-500 leading-relaxed mt-2',
                        confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-500/10',
                        cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-bold transition-all ml-2'
                    },
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // Trigger SweetAlert2 on session messages
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#10b981',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold'
                }
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                title: 'Perhatian!',
                text: "{{ session('warning') }}",
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold'
                }
            });
        @endif

        @if(session('error') || $errors->any())
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                text: "{{ session('error') ?? $errors->first() }}",
                icon: 'error',
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold'
                }
            });
        @endif
    </script>
</body>
</html>
