<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    /**
     * Detect PHP-level upload errors that bypass hasFile().
     */
    private function validateUploadTransportErrors(): void
    {
        $fileFields = [
            'cover_image' => 'cover image',
            'pdf_file' => 'file PDF',
            'preview_file' => 'file preview',
        ];

        $messages = [];

        foreach ($fileFields as $field => $label) {
            $rawUpload = $_FILES[$field] ?? null;
            if (!$rawUpload) {
                continue;
            }

            $errorCode = $rawUpload['error'] ?? UPLOAD_ERR_OK;
            if ($errorCode === UPLOAD_ERR_OK || $errorCode === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $messages[$field] = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Upload {$label} gagal: ukuran file melebihi batas server.",
                UPLOAD_ERR_PARTIAL => "Upload {$label} gagal: file hanya terunggah sebagian.",
                UPLOAD_ERR_NO_TMP_DIR => "Upload {$label} gagal: folder temporary server tidak tersedia.",
                UPLOAD_ERR_CANT_WRITE => "Upload {$label} gagal: server tidak dapat menyimpan file ke disk.",
                UPLOAD_ERR_EXTENSION => "Upload {$label} gagal: diblokir oleh ekstensi PHP server.",
                default => "Upload {$label} gagal dengan kode error: {$errorCode}.",
            };
        }

        if (!empty($messages)) {
            throw ValidationException::withMessages($messages);
        }
    }

    /**
     * Ensure uploaded files are persisted in DB and storage.
     */
    private function assertUploadedFilesPersisted(Book $book, array $uploadedFiles): void
    {
        $book->refresh();

        $mapping = [
            'cover' => 'cover_image',
            'pdf' => 'pdf_file',
            'preview' => 'preview_file',
        ];

        foreach ($mapping as $type => $column) {
            if (!isset($uploadedFiles[$type])) {
                continue;
            }

            $path = $uploadedFiles[$type];
            if (($book->{$column} ?? null) !== $path) {
                throw new \RuntimeException("Upload {$type} gagal: path file tidak tersimpan ke database.");
            }

            if (!Storage::disk('public')->exists($path)) {
                throw new \RuntimeException("Upload {$type} gagal: file tidak ditemukan di storage.");
            }
        }
    }

    public function index()
    {
        $this->authorize('viewAny', Book::class);
        $books = Book::with('category')->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $this->authorize('create', Book::class);
        $categories = Category::where('type', 'book')->get();
        return view('admin.books.form', compact('categories'));
    }

    public function store(BookRequest $request)
    {
        $this->authorize('create', Book::class);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }
        $this->validateUploadTransportErrors();

        $data = $request->except(['cover_image', 'pdf_file', 'preview_file']);
        if (isset($data['abstract'])) {
            $data['abstract'] = clean($data['abstract']);
        }
        if (isset($data['wa_number'])) {
            $normalizedWa = preg_replace('/\D+/', '', (string) $data['wa_number']);
            $data['wa_number'] = $normalizedWa !== '' ? $normalizedWa : null;
        }
        $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);

        $uploadedFiles = [];

        try {
            \Log::info('Storing new book', [
                'title' => $request->input('title'),
                'has_cover' => $request->hasFile('cover_image'),
                'has_pdf' => $request->hasFile('pdf_file'),
                'has_preview' => $request->hasFile('preview_file')
            ]);

            // Upload all files first, track them for rollback if needed
            if ($request->hasFile('cover_image')) {
                $coverPath = $this->uploader->upload($request->file('cover_image'), 'covers');
                $data['cover_image'] = $coverPath;
                $uploadedFiles['cover'] = $coverPath;
                \Log::info('Cover uploaded: ' . $coverPath);
            }

            if ($request->hasFile('pdf_file')) {
                $pdfPath = $this->uploader->upload($request->file('pdf_file'), 'pdfs');
                $data['pdf_file'] = $pdfPath;
                $uploadedFiles['pdf'] = $pdfPath;
                \Log::info('PDF uploaded: ' . $pdfPath);
            }

            if ($request->hasFile('preview_file')) {
                $previewPath = $this->uploader->upload($request->file('preview_file'), 'previews');
                $data['preview_file'] = $previewPath;
                $uploadedFiles['preview'] = $previewPath;
                \Log::info('Preview uploaded: ' . $previewPath);
            }

            // Create book record
            $book = Book::create($data);
            $this->assertUploadedFilesPersisted($book, $uploadedFiles);
            \Log::info('Book created', ['id' => $book->id, 'title' => $book->title]);

            return redirect()
                ->route('admin.books.index')
                ->with('success', "Buku '{$book->title}' berhasil ditambahkan dengan file.");
        } catch (\Exception $e) {
            // Clean up uploaded files on error
            foreach ($uploadedFiles as $type => $path) {
                try {
                    $this->uploader->delete($path);
                    \Log::info("Cleaned up $type file: " . $path);
                } catch (\Exception $deleteEx) {
                    \Log::warning("Failed to delete $type file during error rollback: " . $deleteEx->getMessage());
                }
            }

            \Log::error('Error creating book: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'uploaded_files' => $uploadedFiles,
                'request_data' => $request->except(['cover_image', 'pdf_file', 'preview_file'])
            ]);
            return back()->withInput()->with('error', 'Gagal menyimpan buku: ' . $e->getMessage());
        }
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $categories = Category::where('type', 'book')->get();
        return view('admin.books.form', compact('book', 'categories'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }
        $this->validateUploadTransportErrors();

        $data = $request->except(['cover_image', 'pdf_file', 'preview_file']);
        if (isset($data['abstract'])) {
            $data['abstract'] = clean($data['abstract']);
        }
        if (isset($data['wa_number'])) {
            $normalizedWa = preg_replace('/\D+/', '', (string) $data['wa_number']);
            $data['wa_number'] = $normalizedWa !== '' ? $normalizedWa : null;
        }
        $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);

        $uploadedFiles = [];
        $oldFiles = [
            'cover' => $book->cover_image,
            'pdf' => $book->pdf_file,
            'preview' => $book->preview_file
        ];

        try {
            \Log::info('Updating book ID: ' . $book->id, [
                'has_cover' => $request->hasFile('cover_image'),
                'has_pdf' => $request->hasFile('pdf_file'),
                'has_preview' => $request->hasFile('preview_file'),
                'preview_url' => $request->input('preview_url')
            ]);

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image && !preg_match('/^https?:\/\//i', $book->cover_image)) {
                    $this->uploader->delete($book->cover_image);
                    \Log::info('Deleted old cover: ' . $book->cover_image);
                }
                $coverPath = $this->uploader->upload($request->file('cover_image'), 'covers');
                $data['cover_image'] = $coverPath;
                $uploadedFiles['cover'] = $coverPath;
                \Log::info('New cover uploaded: ' . $coverPath);
            }

            if ($request->hasFile('pdf_file')) {
                if ($book->pdf_file) {
                    $this->uploader->delete($book->pdf_file);
                    \Log::info('Deleted old PDF: ' . $book->pdf_file);
                }
                $pdfPath = $this->uploader->upload($request->file('pdf_file'), 'pdfs');
                $data['pdf_file'] = $pdfPath;
                $uploadedFiles['pdf'] = $pdfPath;
                \Log::info('New PDF uploaded: ' . $pdfPath);
            }

            if ($request->hasFile('preview_file')) {
                if ($book->preview_file) {
                    $this->uploader->delete($book->preview_file);
                    \Log::info('Deleted old preview: ' . $book->preview_file);
                }
                $previewPath = $this->uploader->upload($request->file('preview_file'), 'previews');
                $data['preview_file'] = $previewPath;
                $uploadedFiles['preview'] = $previewPath;
                \Log::info('New preview uploaded: ' . $previewPath);
            }

            $book->update($data);
            $this->assertUploadedFilesPersisted($book, $uploadedFiles);
            \Log::info('Book updated successfully', ['id' => $book->id]);
            
            return redirect()
                ->route('admin.books.index')
                ->with('success', 'Buku berhasil diperbarui dengan file.');
        } catch (\Exception $e) {
            // Clean up newly uploaded files on error
            foreach ($uploadedFiles as $type => $path) {
                try {
                    $this->uploader->delete($path);
                    \Log::info("Cleaned up $type file during update error: " . $path);
                } catch (\Exception $deleteEx) {
                    \Log::warning("Failed to delete $type file during update error: " . $deleteEx->getMessage());
                }
            }

            \Log::error('Error updating book: ' . $e->getMessage(), [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'uploaded_files' => $uploadedFiles,
                'request_data' => $request->except(['pdf_file', 'cover_image', 'preview_file']),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui buku: ' . $e->getMessage());
        }
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $this->uploader->delete($book->cover_image);
            $this->uploader->delete($book->pdf_file);
            $this->uploader->delete($book->preview_file);
            $bookTitle = $book->title;
            $book->delete();
            
            return back()->with('success', "Buku '{$bookTitle}' berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Error deleting book: ' . $e->getMessage(), [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Gagal menghapus buku. Silakan coba lagi.');
        }
    }
}
