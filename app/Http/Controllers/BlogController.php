<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with(['user', 'category']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Pagination: 6 articles per page
        $posts = $query->latest('published_at')->paginate(6)->withQueryString();
        $categories = Category::where('type', 'blog')->get();

        return view('public.blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::published()->with(['user', 'category'])->where('slug', $slug)->firstOrFail();

        // Get related articles (same category, max 3)
        $relatedPosts = $post->relatedPosts(3);

        // Prepare ScholarlyArticle Schema
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'ScholarlyArticle',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->featured_image_url,
            'author' => [
                '@type' => 'Person',
                'name' => $post->user?->name ?? 'Author'
            ],
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name', 'Binakarya Cendekia'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('storage/' . \App\Models\Setting::get('logo', ''))
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug)
            ]
        ];

        return view('public.blog.show', compact('post', 'relatedPosts', 'schemaData'));
    }
}
