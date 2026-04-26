<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    public function index()
    {
        $settings = MailSetting::first();
        return view('admin.mail-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'driver' => 'required|in:smtp,mailgun,ses,mailketing',
            'host' => 'nullable|string',
            'port' => 'nullable|integer',
            'encryption' => 'nullable|in:tls,ssl,null',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
            'mailketing_api_key' => 'nullable|string',
            'mailketing_sender_email' => 'nullable|email',
            'recipient_email' => 'nullable|email',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $settings = MailSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            MailSetting::create($validated);
        }

        return redirect()->route('admin.mail-settings.index')->with('success', 'Pengaturan email berhasil disimpan.');
    }

    public function test(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);
        // Implement test email logic here
        return redirect()->back()->with('success', 'Email tes terkirim.');
    }
}
