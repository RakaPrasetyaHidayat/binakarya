<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostSitemapController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('sitemap.posts', now()->addHours(6), function () {
            return Post::select(
                'slug',
                'updated_at',
                'published_at',
                'featured_image',
                'featured_image_url',
                'title',
                'is_published'
            )
                ->where('is_published', true)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->get();
        });

        return response()->view('sitemap.posts', ['posts' => $posts])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=21600')
            ->header('X-Robots-Tag', 'noindex');
    }
}
