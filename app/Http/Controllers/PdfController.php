<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfController extends Controller
{
    public function show($filename): StreamedResponse
    {
        // Prevent directory traversal — strip any path components
        $filename = basename(str_replace(['../', '..\\ ', "\0"], '', $filename));

        // Only allow alphanumeric, dash, underscore, dot in filename
        if (!preg_match('/^[\w\-\.]+$/', $filename)) {
            abort(400, 'Nama file tidak valid');
        }

        // Only serve PDF and common document types
        $allowedExtensions = ['pdf', 'doc', 'docx'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            abort(403, 'Tipe file tidak diizinkan');
        }

        if (!Storage::disk('public')->exists($filename)) {
            abort(404, 'File tidak ditemukan');
        }

        $path = Storage::disk('public')->path($filename);

        // Verify the resolved path is still within storage/app/public
        $storagePath = realpath(Storage::disk('public')->path(''));
        $realPath    = realpath($path);
        if (!$realPath || !str_starts_with($realPath, $storagePath)) {
            abort(403, 'Akses ditolak');
        }

        $mimeMap = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return response()->file($path, [
            'Content-Type'              => $mimeMap[$ext] ?? 'application/octet-stream',
            'Content-Disposition'       => 'inline; filename="' . $filename . '"',
            'Cache-Control'             => 'private, max-age=3600',
            'X-Content-Type-Options'    => 'nosniff',
        ]);
    }
}
