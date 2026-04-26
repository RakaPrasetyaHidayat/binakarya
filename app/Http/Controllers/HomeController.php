<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $latestBooks = Book::with('category')->where('is_published', true)->latest()->limit(6)->get();
        $latestPosts = Post::published()->with(['user', 'category'])->latest('published_at')->limit(3)->get();
        $services = Service::where('is_active', true)->orderBy('order')->limit(4)->get();

        try {
            $testimonials = Testimonial::active()->limit(6)->get();
        } catch (\Exception) {
            $testimonials = collect();
        }

        return view('public.home', compact('latestBooks', 'latestPosts', 'services', 'testimonials'));
    }
}
