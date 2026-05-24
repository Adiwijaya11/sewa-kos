<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpBlacklist;
use Symfony\Component\HttpFoundation\Response;

class BlockBannedIPs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // Check if current IP is blacklisted
        $banned = IpBlacklist::where('ip_address', $ip)->first();

        if ($banned) {
            // Render a premium block page
            return response()->view('errors.ip_banned', [
                'ip' => $ip,
                'reason' => $banned->reason ?? 'Pelanggaran ketentuan penggunaan platform atau aktivitas spam terdeteksi.',
                'banned_at' => $banned->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB'
            ], 403);
        }

        return $next($request);
    }
}
