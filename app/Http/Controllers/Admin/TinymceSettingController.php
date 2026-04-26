<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class TinymceSettingController extends Controller
{
    public function index()
    {
        $apiKey = Setting::get('tinymce_api_key', '');
        return view('admin.tinymce-settings.index', compact('apiKey'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tinymce_api_key' => 'nullable|string|max:255',
        ]);

        Setting::set('tinymce_api_key', $validated['tinymce_api_key']);

        return redirect()->route('admin.tinymce-settings.index')->with('success', 'Pengaturan TinyMCE berhasil disimpan.');
    }
}

