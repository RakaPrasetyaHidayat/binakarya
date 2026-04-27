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
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $stats = [
                'books'    => Book::count(),
                'posts'    => Post::count(),
                'contacts' => Contact::where('is_read', false)->count(),
            ];
            $recentContacts = Contact::latest()->take(5)->get();
        } else {
            // Contributor stats
            $stats = [
                'books'    => 0, // Contributor might not manage books
                'posts'    => Post::where('user_id', $user->id)->count(),
                'contacts' => 0,
            ];
            $recentContacts = collect();
        }

        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }
}
