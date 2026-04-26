<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('order')->paginate(6);
        return view('public.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->with('plans')->firstOrFail();
        
        // Get 3 published books as reference
        $publishedBooks = Book::where('is_published', true)
            ->whereNotNull('cover_image')
            ->latest()
            ->take(3)
            ->get();
        
        // Get other services
        $otherServices = Service::where('id', '!=', $service->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        
        return view('public.services.show', compact('service', 'publishedBooks', 'otherServices'));
    }
}

