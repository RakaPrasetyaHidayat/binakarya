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
        // Support subdirectories like pages/homepage — check top-level key
        $topLevel = explode('/', $directory)[0];

        if (!isset(self::ALLOWED_MIMES[$topLevel])) {
            throw ValidationException::withMessages([
                'file' => 'Invalid upload directory.'
            ]);
        }

        $maxSize = self::MAX_FILE_SIZES[$topLevel] ?? 2048;
        if ($file->getSize() > $maxSize * 1024) {
            throw ValidationException::withMessages([
                'file' => "File size must not exceed {$maxSize}KB."
            ]);
        }

        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES[$topLevel])) {
            throw ValidationException::withMessages([
                'file' => 'Invalid file type.'
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS[$topLevel])) {
            throw ValidationException::withMessages([
                'file' => 'Invalid file extension.'
            ]);
        }

        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs($directory, $filename, 'public');

        if (!$path) {
            throw ValidationException::withMessages([
                'file' => 'Failed to upload file. Please try again.'
            ]);
        }

        return $directory . '/' . $filename;
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

