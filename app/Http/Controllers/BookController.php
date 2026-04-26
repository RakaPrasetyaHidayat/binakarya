<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category')->where('is_published', true);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::where('type', 'book')->withCount([
            'books' => function ($query) {
                $query->where('is_published', true);
            }
        ])->get();
        $totalBooks = Book::where('is_published', true)->count();

        return view('public.books.index', compact('books', 'categories', 'totalBooks'));
    }

    public function show(string $slug)
    {
        $book = Book::with('category')->where('slug', $slug)->where('is_published', true)->first();

        if (!$book) {
            $normalizedSlug = preg_replace('/-\d+$/', '', $slug);

            if ($normalizedSlug !== $slug) {
                $book = Book::with('category')->where('slug', $normalizedSlug)->where('is_published', true)->first();
            }
        }

        abort_unless($book !== null, 404);

        if ($book->slug !== $slug) {
            return redirect()->route('books.show', $book->slug);
        }

        // Prepare Book Schema
        $offers = [];
        if ($book->price > 0) {
            $offers = [
                '@type' => 'Offer',
                'price' => $book->price,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock'
            ];

            // Add links if available
            if ($book->shopee_url)
                $offers['url'] = $book->shopee_url;
            elseif ($book->tokopedia_url)
                $offers['url'] = $book->tokopedia_url;
            elseif ($book->custom_url)
                $offers['url'] = $book->custom_url;
        }

        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'author' => [
                '@type' => 'Person',
                'name' => $book->author
            ],
            'description' => $book->abstract,
            'image' => $book->cover_url,
            'isbn' => $book->isbn,
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name', 'Binakarya Cendekia')
            ]
        ];

        if (!empty($offers)) {
            $schemaData['offers'] = $offers;
        }

        if ($book->doi) {
            $schemaData['identifier'] = $book->doi;
        }

        return view('public.books.show', compact('book', 'schemaData'));
    }
}
