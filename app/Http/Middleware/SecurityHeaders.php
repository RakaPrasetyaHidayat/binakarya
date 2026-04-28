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
        
        // Basic security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        // HSTS (Strict-Transport-Security) - only for HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy (CSP)
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'nonce-{$nonce}' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com http://127.0.0.1:5173 localhost:5173; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net http://127.0.0.1:5173 localhost:5173; ";
        $csp .= "img-src 'self' data: https://images.unsplash.com https://ui-avatars.com https://*.basecamp.com http://127.0.0.1:5173 localhost:5173; ";
        $csp .= "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net http://127.0.0.1:5173 localhost:5173; ";
        $csp .= "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://www.w3.org blob:; ";
        $csp .= "connect-src 'self' ws://127.0.0.1:5173 http://127.0.0.1:5173 ws://localhost:5173 http://localhost:5173 https://unpkg.com https://*.unpkg.com https://cdn.jsdelivr.net https://*.jsdelivr.net; ";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Ngrok specific (for development)
        $response->headers->set('ngrok-skip-browser-warning', 'true');
        
        return $response;
    }
}
