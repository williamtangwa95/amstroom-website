<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LogVisitors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get path and exclude asset requests, health checks
        $path = $request->path();
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|webp|woff|woff2|ttf|eot)$/i', $path) || $request->is('up')) {
            return $next($request);
        }

        // Get IP and geolocate (with caching)
        $ip = $request->ip();
        $geo = Cache::remember("geoip:{$ip}", 86400, function () use ($ip) {
            // Check for local loopbacks or private IPs
            if ($ip === '127.0.0.1' || $ip === '::1' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return [
                    'country' => 'Localhost',
                    'city' => 'Localhost',
                ];
            }

            try {
                // Low timeout of 1 second so request isn't blocked on network issue
                $response = Http::timeout(1)->get("http://ip-api.com/json/{$ip}");
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'city' => $data['city'] ?? 'Unknown',
                    ];
                }
            } catch (\Exception $e) {
                // Fail silently and return Unknown
            }

            return [
                'country' => 'Unknown',
                'city' => 'Unknown',
            ];
        });

        // Parse User Agent
        $userAgent = $request->header('User-Agent') ?? '';
        
        // Browser parsing
        $browser = 'Unknown';
        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
            if (preg_match('/Edge/i', $userAgent) || preg_match('/Edg/i', $userAgent)) {
                $browser = 'Edge';
            } elseif (preg_match('/OPR/i', $userAgent)) {
                $browser = 'Opera';
            }
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent) || preg_match('/OPR/i', $userAgent)) {
            $browser = 'Opera';
        }

        // OS Platform parsing
        $platform = 'Unknown';
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
            if (preg_match('/android/i', $userAgent)) {
                $platform = 'Android';
            }
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        }

        // Device Type parsing
        $deviceType = 'desktop';
        if (preg_match('/tablet|ipad/i', $userAgent)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/mobile|iphone|ipod|android/i', $userAgent)) {
            $deviceType = 'mobile';
        }

        // Create Visitor Log
        VisitorLog::create([
            'ip_address' => $ip,
            'country' => $geo['country'],
            'city' => $geo['city'],
            'device_type' => $deviceType,
            'platform' => $platform,
            'browser' => $browser,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => Auth::id(),
        ]);

        return $next($request);
    }
}
