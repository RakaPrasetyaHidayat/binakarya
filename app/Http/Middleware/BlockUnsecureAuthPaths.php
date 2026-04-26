<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockUnsecureAuthPaths extends Closure
{
    public function handle(Request $request, Closure $next)
    {
        $blockedPaths = [
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/dashboard',
        ];

        foreach ($blockedPaths as $path) {
            if ($request->is($path) || $request->is($path . '*')) {
                if (auth()->check()) {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
