<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KosinAja - Hunian Kos Modern, Aman & Terpercaya')</title>
    
    <!-- Tailwind CSS (compiled via Vite) & Bunny Fonts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons & FontAwesome (for beautiful badges and symbols) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js CDN for robust dynamic client-side interactions -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Leaflet.js Mapping for elegant interactive map listings -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- SweetAlert2 CDN for premium interactive alerts and dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex flex-col min-h-screen selection:bg-emerald-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-md shadow-emerald-200">
                            <i class="fa-solid fa-house-circle-check text-lg"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">KosinAja</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden lg:flex space-x-8 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-600 transition-colors {{ request()->routeIs('home') ? 'text-emerald-600 font-semibold' : '' }}">Home</a>
                    <a href="{{ route('search') }}" class="text-slate-600 hover:text-emerald-600 transition-colors {{ request()->routeIs('search') ? 'text-emerald-600 font-semibold' : '' }}">Cari Kos</a>
                    <a href="{{ route('about') }}" class="text-slate-600 hover:text-emerald-600 transition-colors {{ request()->routeIs('about') ? 'text-emerald-600 font-semibold' : '' }}">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="text-slate-600 hover:text-emerald-600 transition-colors {{ request()->routeIs('contact') ? 'text-emerald-600 font-semibold' : '' }}">Kontak</a>
                </nav>

                <!-- Auth Buttons (Desktop) -->
                <div class="hidden lg:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-1 text-sm font-medium text-slate-700 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        @elseif(auth()->user()->role === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-1 text-sm font-medium text-slate-700 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>Dashboard Owner</span>
                            </a>
                        @else
                            <a href="{{ route('listings.favorites') }}" class="text-slate-600 hover:text-emerald-600 transition-colors relative mr-3" title="Favorit Saya">
                                <i class="fa-regular fa-heart text-xl"></i>
                                @php
                                    $favCount = \App\Models\Favorite::where('user_id', auth()->id())->count();
                                @endphp
                                @if($favCount > 0)
                                    <span class="absolute -top-1 -right-1.5 w-4 h-4 bg-emerald-500 rounded-full text-[9px] text-white flex items-center justify-center font-bold">{{ $favCount }}</span>
                                @endif
                            </a>

                            <a href="{{ route('payments.history') }}" class="text-slate-600 hover:text-emerald-600 transition-colors relative mr-3" title="Transaksi & Booking Saya">
                                <i class="fa-solid fa-receipt text-xl"></i>
                                @php
                                    $pendingCount = \App\Models\Payment::where('user_id', auth()->id())->where('payment_status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="absolute -top-1 -right-1.5 w-4 h-4 bg-amber-500 rounded-full text-[9px] text-white flex items-center justify-center font-bold">{{ $pendingCount }}</span>
                                @endif
                            </a>

                            <a href="{{ route('chats.index') }}" class="text-slate-600 hover:text-emerald-600 transition-colors relative">
                                <i class="fa-regular fa-comments text-xl"></i>
                                @php
                                    $unread = \App\Models\Chat::where('receiver_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="absolute -top-1 -right-1.5 w-4 h-4 bg-red-500 rounded-full text-[9px] text-white flex items-center justify-center font-bold">{{ $unread }}</span>
                                @endif
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-slate-600 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-right-from-bracket mr-1"></i>Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 h-10 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center shadow-sm shadow-emerald-100">Daftar User</a>
                        <a href="{{ route('register.owner') }}" class="px-4 h-10 rounded-xl bg-white border border-emerald-600 text-emerald-600 text-sm font-medium hover:bg-emerald-50 transition-colors flex items-center">Daftar Owner</a>
                    @endauth
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="flex lg:hidden">
                    <button @click="mobileMenu = !mobileMenu" type="button" class="text-slate-500 hover:text-emerald-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fa-solid fa-xmark text-xl" x-show="mobileMenu" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" @click.away="mobileMenu = false" x-transition class="lg:hidden border-t border-slate-100 bg-white shadow-lg absolute w-full" x-cloak>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition-all">Home</a>
                <a href="{{ route('search') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition-all">Cari Kos</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition-all">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition-all">Kontak</a>
                
                <div class="border-t border-slate-100 pt-4 pb-2">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:text-emerald-600"><i class="fa-solid fa-gauge-high mr-2"></i>Dashboard Admin</a>
                        @elseif(auth()->user()->role === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:text-emerald-600"><i class="fa-solid fa-circle-user mr-2"></i>Dashboard Owner</a>
                        @else
                            <a href="{{ route('listings.favorites') }}" class="flex items-center justify-between px-3 py-2 text-base font-medium text-slate-700 hover:text-emerald-600">
                                <span><i class="fa-regular fa-heart mr-2"></i>Favorit Saya</span>
                                @php
                                    $favCount = \App\Models\Favorite::where('user_id', auth()->id())->count();
                                @endphp
                                @if($favCount > 0)
                                    <span class="w-5 h-5 bg-emerald-500 rounded-full text-[10px] text-white flex items-center justify-center font-bold">{{ $favCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('payments.history') }}" class="flex items-center justify-between px-3 py-2 text-base font-medium text-slate-700 hover:text-emerald-600">
                                <span><i class="fa-solid fa-receipt mr-2"></i>Transaksi Saya</span>
                                @php
                                    $pendingCount = \App\Models\Payment::where('user_id', auth()->id())->where('payment_status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="w-5 h-5 bg-amber-500 rounded-full text-[10px] text-white flex items-center justify-center font-bold">{{ $pendingCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('chats.index') }}" class="flex items-center justify-between px-3 py-2 text-base font-medium text-slate-700 hover:text-emerald-600">
                                <span><i class="fa-regular fa-comments mr-2"></i>Pesan Chat</span>
                                @php
                                    $unread = \App\Models\Chat::where('receiver_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="w-5 h-5 bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center font-bold">{{ $unread }}</span>
                                @endif
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50"><i class="fa-solid fa-right-from-bracket mr-2"></i>Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 text-center rounded-lg mt-2 mx-3">Daftar User</a>
                        <a href="{{ route('register.owner') }}" class="block px-3 py-2 text-base font-medium text-emerald-600 border border-emerald-600 hover:bg-emerald-50 text-center rounded-lg mt-2 mx-3">Daftar Owner</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <!-- Toast / Success / Warning Messages -->
        @if(session('success') || session('warning') || session('status'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition>
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

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Branding -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-house-circle-check text-sm"></i>
                        </div>
                        <span class="text-lg font-bold text-white">KosinAja</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Platform terpercaya pencarian kos modern dengan sistem anti-scam, jaminan pemilik terverifikasi, dan transaksi aman.
                    </p>
                </div>

                <!-- Fast Links -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('search') }}" class="hover:text-emerald-400 transition-colors">Cari Kos</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-emerald-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <!-- Trusted System -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Layanan Aman</h3>
                    <ul class="space-y-2 text-xs">
                        <li><span class="hover:text-slate-300"><i class="fa-solid fa-circle-check text-emerald-500 mr-2"></i>Pemilik KTP Terverifikasi</span></li>
                        <li><span class="hover:text-slate-300"><i class="fa-solid fa-shield-halved text-emerald-500 mr-2"></i>Sistem Anti-Scam Cerdas</span></li>
                        <li><span class="hover:text-slate-300"><i class="fa-solid fa-credit-card text-emerald-500 mr-2"></i>Rekening Bersama Aman</span></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-xs">
                        <li><i class="fa-solid fa-envelope mr-2"></i>support@kosinaja.com</li>
                        <li><i class="fa-solid fa-phone mr-2"></i>+62 812-3456-7890</li>
                        <li><i class="fa-solid fa-map-location-dot mr-2"></i>Jakarta Selatan, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; 2026 KosinAja. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-slate-400 transition-colors"><i class="fa-brands fa-instagram text-base"></i></a>
                    <a href="#" class="hover:text-slate-400 transition-colors"><i class="fa-brands fa-facebook text-base"></i></a>
                    <a href="#" class="hover:text-slate-400 transition-colors"><i class="fa-brands fa-x-twitter text-base"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize lucide icons throughout
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
