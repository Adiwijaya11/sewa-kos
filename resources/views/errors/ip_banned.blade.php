<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditangguhkan - KosinAja Security Shield</title>
    <!-- Tailwind CSS & FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, #0f172a, #020617);
        }
        .font-mono-custom {
            font-family: 'JetBrains Mono', monospace;
        }
        .glowing-bg {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 60%);
        }
        .glowing-border {
            box-shadow: 0 0 25px -5px rgba(239, 68, 68, 0.2);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background glowing blob -->
    <div class="absolute w-[600px] h-[600px] rounded-full glowing-bg -top-40 -left-40 z-0 pointer-events-none"></div>
    <div class="absolute w-[500px] h-[500px] rounded-full glowing-bg -bottom-20 -right-20 z-0 pointer-events-none"></div>

    <!-- Container Card -->
    <div class="max-w-xl w-full bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl relative z-10 glowing-border">
        
        <!-- Header: Lock / Shield Icon -->
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center text-red-500 text-3xl mb-6 relative animate-pulse shadow-[0_0_30px_rgba(239,68,68,0.15)]">
                <i class="fa-solid fa-shield-halved"></i>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-900 flex items-center justify-center text-[8px] text-white">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            
            <span class="text-[10px] uppercase font-bold tracking-widest bg-red-500/10 text-red-400 border border-red-500/20 px-3 py-1 rounded-full mb-3">
                KosinAja Security Shield
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-none mb-3">
                Akses Browser Anda Ditangguhkan
            </h1>
            <p class="text-sm text-slate-400 max-w-md">
                Sistem firewall kami mendeteksi aktivitas mencurigakan atau alamat IP Anda telah dibatasi oleh Administrator platform.
            </p>
        </div>

        <!-- Security Metadata -->
        <div class="mt-8 space-y-4">
            <!-- IP Address Row -->
            <div class="bg-slate-950/80 rounded-2xl border border-slate-800/80 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Alamat IP Anda</span>
                    <span class="font-mono-custom text-emerald-400 font-bold text-base sm:text-lg select-all">{{ $ip }}</span>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $ip }}'); alert('IP berhasil disalin!');" class="self-start sm:self-center px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 active:scale-95 transition-all text-xs font-semibold text-slate-300 flex items-center gap-1.5 border border-slate-700/50">
                    <i class="fa-regular fa-copy"></i> Salin IP
                </button>
            </div>

            <!-- Block Information Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-950/40 rounded-2xl border border-slate-800/50 p-4">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Alasan Penangguhan</span>
                    <p class="text-xs text-slate-300 font-medium leading-relaxed">
                        {{ $reason }}
                    </p>
                </div>
                <div class="bg-slate-950/40 rounded-2xl border border-slate-800/50 p-4">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Waktu Kejadian</span>
                    <p class="text-xs text-slate-300 font-medium font-mono-custom mt-0.5">
                        {{ $banned_at }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom Warning/Disclaimer -->
        <div class="mt-8 pt-6 border-t border-slate-800/60 text-center">
            <p class="text-xs text-slate-500 leading-relaxed max-w-sm mx-auto mb-6">
                ID Keamanan: <span class="font-mono-custom text-slate-400">KOS-FW-{{ md5($ip) }}</span>. Jika Anda merasa pemblokiran ini adalah kesalahan, silakan hubungi tim IT Security kami.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="mailto:security@kosinaja.com?subject=Banned%20IP%20Appeal%20[{{ $ip }}]&body=Halo%20Admin,%20IP%20saya%20terblokir:%20{{ $ip }}.%20Mohon%20bantuan%20tinjauan%20kembali." 
                   class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 transition-all text-xs font-bold text-slate-900 shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-1.5">
                    <i class="fa-regular fa-envelope"></i> Ajukan Banding Keamanan
                </a>
                <a href="https://kosinaja.com" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 active:scale-95 transition-all text-xs font-bold text-slate-300 flex items-center justify-center gap-1.5 border border-slate-700/50">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Small decorative watermark -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-[10px] text-slate-600 font-bold uppercase tracking-widest z-0 pointer-events-none select-none">
        KosinAja Security Operations Center &copy; 2026
    </div>
</body>
</html>
