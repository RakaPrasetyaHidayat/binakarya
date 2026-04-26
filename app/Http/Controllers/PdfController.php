<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfController extends Controller
{
    public function show($filename): StreamedResponse
    {
        // Sanitize filename to prevent directory traversal
        $filename = str_replace(['../', '..\\'], '', $filename);
        
        if (!Storage::disk('public')->exists($filename)) {
            abort(404, 'PDF tidak ditemukan');
        }

        $path = Storage::disk('public')->path($filename);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        ]);
    }
}
