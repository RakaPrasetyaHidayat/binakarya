<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookSitemapController extends Controller
{
    public function index()
    {
        $books = Cache::remember('sitemap.books', now()->addHours(6), function () {
            return Book::select(
                'slug',
                'updated_at',
                'cover_image',
                'cover_url',
                'title',
                'is_published'
            )
                ->where('is_published', true)
                ->orderByDesc('updated_at')
                ->get();
        });

        return response()->view('sitemap.books', ['books' => $books])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=21600')
            ->header('X-Robots-Tag', 'noindex');
    }
}
