<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md bg-white/75 dark:bg-zinc-950/75 border-b border-zinc-200/50 dark:border-zinc-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo & Brand -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-5.5 h-5.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-zinc-900 to-zinc-700 dark:from-white dark:to-zinc-300 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">
                        Sewa<span class="text-emerald-500 dark:text-emerald-400">Kos</span>
                    </span>
                </a>
            </div>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex space-x-1">
                <a href="#search" class="px-4 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-100/50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                    Cari Kos
                </a>
                <a href="#featured" class="px-4 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-100/50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                    Rekomendasi
                </a>
                <a href="#why-us" class="px-4 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-100/50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                    Keunggulan
                </a>
                <a href="#newsletter" class="px-4 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-100/50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                    Hubungi Kami
                </a>
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden md:flex items-center gap-4">
                
                <!-- Dark Mode Toggle Button -->
                <button id="theme-toggle" type="button" aria-label="Toggle theme" class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors duration-200">
                    <!-- Moon Icon (shows in light mode) -->
                    <svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <!-- Sun Icon (shows in dark mode) -->
                    <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464a1 1 0 10-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-all duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-semibold text-zinc-700 dark:text-zinc-200 hover:text-emerald-500 dark:hover:text-emerald-400 transition-colors duration-200">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:scale-[1.02] hover:from-emerald-600 hover:to-teal-700 transition-all duration-300">
                                Daftar Kos
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Toggle Button (Hamburger) -->
            <div class="flex items-center md:hidden gap-3">
                <!-- Mobile Theme Toggle -->
                <button id="theme-toggle-mobile" type="button" aria-label="Toggle theme" class="p-2 rounded-lg border border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                    <svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464a1 1 0 10-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <button id="mobile-menu-button" type="button" class="p-2 rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
        </div>
    </div>

    <!-- Mobile Drawer Overlay Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white/95 dark:bg-zinc-950/95 border-b border-zinc-200 dark:border-zinc-800 absolute top-20 left-0 right-0 shadow-lg backdrop-blur-lg">
        <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
            <a href="#search" class="block px-4 py-3 rounded-xl text-base font-semibold text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                Cari Kos
            </a>
            <a href="#featured" class="block px-4 py-3 rounded-xl text-base font-semibold text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                Rekomendasi
            </a>
            <a href="#why-us" class="block px-4 py-3 rounded-xl text-base font-semibold text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                Keunggulan
            </a>
            <a href="#newsletter" class="block px-4 py-3 rounded-xl text-base font-semibold text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                Hubungi Kami
            </a>
            
            <div class="pt-4 pb-2 border-t border-zinc-200 dark:border-zinc-800 mt-2 px-4 flex flex-col gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center py-3 rounded-xl border border-zinc-200 dark:border-zinc-800 font-semibold text-zinc-700 dark:text-zinc-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center py-3 rounded-xl border border-zinc-200 dark:border-zinc-800 font-semibold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center py-3 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl text-white font-semibold shadow-md shadow-emerald-500/10">
                                Daftar Kos
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</header>
