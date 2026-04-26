<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Services\FileUploadService;

class BookController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

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
        // Validate authenticated user
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        $data = $request->except(['cover_image', 'pdf_file', 'preview_file']);
        if (isset($data['abstract'])) {
            $data['abstract'] = clean($data['abstract']);
        }
        $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);

        try {
            // Log for debugging
            \Log::info('Storing new book', [
                'title' => $request->input('title'),
                'has_cover' => $request->hasFile('cover_image'),
                'has_pdf' => $request->hasFile('pdf_file'),
                'has_preview' => $request->hasFile('preview_file')
            ]);

            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $this->uploader->upload($request->file('cover_image'), 'covers');
            }
            if ($request->hasFile('pdf_file')) {
                $data['pdf_file'] = $this->uploader->upload($request->file('pdf_file'), 'pdfs');
            }
            if ($request->hasFile('preview_file')) {
                $data['preview_file'] = $this->uploader->upload($request->file('preview_file'), 'previews');
            }

            $book = Book::create($data);
            
            return redirect()
                ->route('admin.books.index')
                ->with('success', "Buku '{$book->title}' berhasil ditambahkan.");
        } catch (\Exception $e) {
            \Log::error('Error creating book: ' . $e->getMessage(), [
                'request' => $request->except(['cover_image', 'pdf_file', 'preview_file'])
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

        $data = $request->except(['cover_image', 'pdf_file', 'preview_file']);
        if (isset($data['abstract'])) {
            $data['abstract'] = clean($data['abstract']);
        }
        $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);

        try {
            // Log for debugging
            \Log::info('Updating book ID: ' . $book->id, [
                'has_cover' => $request->hasFile('cover_image'),
                'has_pdf' => $request->hasFile('pdf_file'),
                'has_preview' => $request->hasFile('preview_file'),
                'preview_url' => $request->input('preview_url')
            ]);

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image && !preg_match('/^https?:\/\//i', $book->cover_image)) {
                    $this->uploader->delete($book->cover_image);
                }
                $data['cover_image'] = $this->uploader->upload($request->file('cover_image'), 'covers');
            }

            if ($request->hasFile('pdf_file')) {
                $this->uploader->delete($book->pdf_file);
                $data['pdf_file'] = $this->uploader->upload($request->file('pdf_file'), 'pdfs');
            }

            if ($request->hasFile('preview_file')) {
                $this->uploader->delete($book->preview_file);
                $data['preview_file'] = $this->uploader->upload($request->file('preview_file'), 'previews');
            }

            $book->update($data);
            
            return redirect()
                ->route('admin.books.index')
                ->with('success', 'Buku berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating book: ' . $e->getMessage(), [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'request_data' => $request->except(['pdf_file', 'cover_image']),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui buku. Silakan coba lagi.');
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
