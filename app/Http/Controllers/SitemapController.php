<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    const LAST_STATIC_UPDATE = '2026-04-25T00:00:00Z';

    public function index()
    {
        return response()->view('sitemaps.index')
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function sitemapIndex()
    {
        return response()->view('sitemaps.sitemap-index')
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function pages()
    {
        return response()->view('sitemaps.pages', ['lastStaticUpdate' => self::LAST_STATIC_UPDATE])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=604800')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function books()
    {
        $books = Cache::remember('sitemap_books', now()->addHours(24), function () {
            return Book::where('is_published', true)
                ->orderByDesc('updated_at')
                ->get(['id', 'slug', 'updated_at', 'created_at', 'cover_image', 'cover_url', 'title']);
        });

        return response()->view('sitemaps.books', ['books' => $books])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function blog()
    {
        $posts = Cache::remember('sitemap_blog', now()->addHours(24), function () {
            return Post::published()
                ->orderByDesc('published_at')
                ->get(['id', 'slug', 'updated_at', 'published_at', 'featured_image', 'featured_image_url', 'title']);
        });

        return response()->view('sitemaps.blog', ['posts' => $posts])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function services()
    {
        $services = Cache::remember('sitemap_services', now()->addHours(24), function () {
            return Service::where('is_active', true)
                ->orderByDesc('updated_at')
                ->get(['id', 'slug', 'updated_at', 'created_at']);
        });

        return response()->view('sitemaps.services', ['services' => $services])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function news()
    {
        $posts = Cache::remember('sitemap_news', now()->addHours(24), function () {
            return Post::published()
                ->where('published_at', '>=', now()->subDays(7))
                ->orderByDesc('published_at')
                ->get(['id', 'slug', 'updated_at', 'published_at', 'title']);
        });

        return response()->view('sitemaps.news', ['posts' => $posts])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('X-Robots-Tag', 'index, follow');
    }
}
