<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): mixed
    {
        $nonce = base64_encode(random_bytes(16));
        view()->share('cspNonce', $nonce);

        $response = $next($request);

        $isDev    = app()->environment('local', 'development');

        // ── Basic Security Headers ──────────────────────────────────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // ── HSTS (HTTPS only) ───────────────────────────────────────────────
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // ── Content Security Policy ─────────────────────────────────────────
        $devSources = $isDev
            ? ' http://127.0.0.1:5173 ws://127.0.0.1:5173 http://localhost:5173 ws://localhost:5173'
            : '';

        $trustedScripts = "https://cdn.jsdelivr.net https://unpkg.com https://*.unpkg.com https://cdn.tiny.cloud";
        $trustedStyles  = "https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com https://*.unpkg.com https://cdn.tiny.cloud";
        $trustedFonts   = "https://fonts.gstatic.com https://cdn.jsdelivr.net https://unpkg.com https://*.unpkg.com https://cdn.tiny.cloud";

        // Alpine.js membutuhkan unsafe-eval untuk mengevaluasi x-data expressions
        // Diperlukan di semua halaman yang menggunakan Alpine.js
        $evalSource = " 'unsafe-eval'";

        $csp  = "default-src 'self'; ";
        $csp .= "script-src 'self' 'nonce-{$nonce}'{$evalSource} {$trustedScripts}{$devSources}; ";
        $csp .= "style-src 'self' 'unsafe-inline' {$trustedStyles}{$devSources}; ";
        $csp .= "img-src 'self' data: blob: https://ui-avatars.com https://cdn.tiny.cloud https://unpkg.com https://*.unpkg.com https://cdn.jsdelivr.net{$devSources}; ";
        $csp .= "font-src 'self' data: {$trustedFonts}{$devSources}; ";
        $csp .= "frame-src 'self' https://www.youtube.com https://player.vimeo.com blob:; ";
        $csp .= "connect-src 'self' https://cdn.jsdelivr.net https://*.jsdelivr.net https://unpkg.com https://*.unpkg.com https://cdn.tiny.cloud https://api.mailketing.co.id{$devSources}; ";
        $csp .= "worker-src 'self' blob:; ";
        $csp .= "object-src 'none'; ";
        $csp .= "base-uri 'self'; ";
        $csp .= "form-action 'self'; ";
        $csp .= "upgrade-insecure-requests;";

        $response->headers->set('Content-Security-Policy', $csp);

        if ($isDev) {
            $response->headers->set('ngrok-skip-browser-warning', 'true');
        }

        return $response;
    }
}
