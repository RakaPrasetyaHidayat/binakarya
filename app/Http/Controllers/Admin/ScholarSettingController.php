<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScholarSettingController extends Controller
{
    public function index()
    {
        $settings = ScholarSetting::first();
        $publications = [];
        
        if ($settings && $settings->is_active && $settings->api_key) {
            $publications = $this->fetchPublications($settings);
        }
        
        return view('admin.scholar-settings.index', compact('settings', 'publications'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string',
            'author_id' => 'nullable|string',
            'institution' => 'nullable|string',
            'auto_sync' => 'boolean',
            'sync_interval' => 'integer|min:1|max:168',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['auto_sync'] = $request->has('auto_sync');

        $settings = ScholarSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            ScholarSetting::create($validated);
        }

        return redirect()->route('admin.scholar-settings.index')->with('success', 'Pengaturan Google Scholar berhasil disimpan.');
    }

    public function sync()
    {
        $settings = ScholarSetting::first();
        
        if (!$settings || !$settings->is_active) {
            return redirect()->back()->with('error', 'Google Scholar tidak aktif.');
        }

        $publications = $this->fetchPublications($settings);
        
        $settings->update(['last_sync' => now()]);

        return redirect()->back()->with('success', 'Sinkronisasi berhasil. ' . count($publications) . ' publikasi ditemukan.');
    }

    private function fetchPublications($settings)
    {
        // Placeholder for Google Scholar API integration
        // Actual implementation would use Google Scholar API or scraping
        return [];
    }
}
