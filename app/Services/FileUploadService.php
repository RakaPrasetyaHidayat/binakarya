<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    private const ALLOWED_MIMES = [
        'covers' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        'pdfs' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text'],
        'posts' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        'site' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/svg+xml'],
        'pages' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        'previews' => ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text'],
    ];

    private const ALLOWED_EXTENSIONS = [
        'covers' => ['jpg', 'jpeg', 'png', 'webp'],
        'pdfs' => ['pdf', 'doc', 'docx', 'odt'],
        'posts' => ['jpg', 'jpeg', 'png', 'webp'],
        'site' => ['jpg', 'jpeg', 'png', 'webp', 'svg'],
        'pages' => ['jpg', 'jpeg', 'png', 'webp'],
        'previews' => ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx', 'odt'],
    ];

    private const MAX_FILE_SIZES = [
        'covers' => 2048,
        'pdfs' => 51200,
        'posts' => 2048,
        'site' => 2048,
        'pages' => 2048,
        'previews' => 10240,
    ];

    /**
     * Upload file dengan security validation
     */
    public function upload(UploadedFile $file, string $directory): string
    {
        $topLevel = explode('/', $directory)[0];

        \Log::info('Starting file upload', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'directory' => $directory,
            'top_level' => $topLevel
        ]);

        if (!isset(self::ALLOWED_MIMES[$topLevel])) {
            \Log::warning('Invalid upload directory', ['directory' => $directory]);
            throw ValidationException::withMessages([
                'file' => "Upload directory '{$directory}' tidak diizinkan."
            ]);
        }

        $maxSize = self::MAX_FILE_SIZES[$topLevel] ?? 2048;
        $fileSizeKB = $file->getSize() / 1024;

        if ($fileSizeKB > $maxSize) {
            \Log::warning('File too large', [
                'size_kb' => $fileSizeKB,
                'max_size_kb' => $maxSize,
                'file' => $file->getClientOriginalName()
            ]);
            throw ValidationException::withMessages([
                'file' => "Ukuran file maksimal {$maxSize}KB. File Anda: " . round($fileSizeKB, 2) . "KB"
            ]);
        }

        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIMES[$topLevel])) {
            \Log::warning('Invalid MIME type', [
                'mime_type' => $mimeType,
                'allowed' => self::ALLOWED_MIMES[$topLevel],
                'file' => $file->getClientOriginalName()
            ]);
            throw ValidationException::withMessages([
                'file' => "Tipe file '{$mimeType}' tidak diizinkan. Tipe yang diizinkan: " . implode(', ', self::ALLOWED_MIMES[$topLevel])
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS[$topLevel])) {
            \Log::warning('Invalid file extension', [
                'extension' => $extension,
                'allowed' => self::ALLOWED_EXTENSIONS[$topLevel],
                'file' => $file->getClientOriginalName()
            ]);
            throw ValidationException::withMessages([
                'file' => "Ekstensi file '.$extension' tidak diizinkan. Ekstensi yang diizinkan: " . implode(', ', self::ALLOWED_EXTENSIONS[$topLevel])
            ]);
        }

        try {
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                \Log::error('File storage returned false', [
                    'directory' => $directory,
                    'filename' => $filename
                ]);
                throw new \Exception('File storage operation returned false');
            }

            $fullPath = $directory . '/' . $filename;
            
            if (!Storage::disk('public')->exists($fullPath)) {
                \Log::error('File not found after upload', [
                    'path' => $fullPath,
                    'storage_root' => storage_path('app/public')
                ]);
                throw new \Exception('File tidak tersimpan dengan benar. Verifikasi gagal.');
            }

            \Log::info('File uploaded successfully', [
                'path' => $fullPath,
                'size_kb' => $fileSizeKB,
                'original_name' => $file->getClientOriginalName()
            ]);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'directory' => $directory,
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Delete file safely
     */
    public function delete(?string $path): void
    {
        if (!$path)
            return;

        // Prevent directory traversal
        if (str_contains($path, '..') || str_contains($path, '~')) {
            \Log::warning('Attempted to delete invalid file path: ' . $path);
            return;
        }

        $topLevel = explode('/', $path)[0];
        if (!isset(self::ALLOWED_MIMES[$topLevel])) {
            \Log::warning('Attempted to delete file outside allowed directories: ' . $path);
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete file: ' . $path, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get file URL secara aman
     */
    public function getUrl(?string $path): ?string
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}

