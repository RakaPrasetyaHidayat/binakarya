<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Post;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'books'    => Book::count(),
            'posts'    => Post::count(),
            'contacts' => Contact::where('is_read', false)->count(),
        ];
        $recentContacts = Contact::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }
}
