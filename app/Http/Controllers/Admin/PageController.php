<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');

        // Sanitize HTML content to prevent XSS
        if (isset($validated['content'])) {
            $validated['content'] = clean($validated['content']);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');

        // Sanitize HTML content to prevent XSS
        if (isset($validated['content'])) {
            $validated['content'] = clean($validated['content']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
