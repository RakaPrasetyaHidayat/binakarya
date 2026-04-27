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
        $settings = MailSetting::first();

        if (!$settings) {
            return back()->with('error', 'Silakan simpan pengaturan terlebih dahulu.');
        }

        try {
            if ($settings->driver === 'mailketing' && $settings->mailketing_api_key) {
                $response = \Illuminate\Support\Facades\Http::asForm()->timeout(15)
                    ->post('https://api.mailketing.co.id/api/v1/send', [
                        'api_token' => trim($settings->mailketing_api_key),
                        'from_name' => $settings->from_name,
                        'from_email' => $settings->mailketing_sender_email ?: $settings->from_address,
                        'recipient' => $request->test_email,
                        'subject' => 'Tes Koneksi Mailketing',
                        'content' => 'Halo, ini adalah email percobaan untuk memastikan integrasi Mailketing Anda sudah bekerja dengan benar.'
                    ]);

                $result = $response->json();
                if (!$response->successful() || (isset($result['status']) && $result['status'] !== 'success')) {
                    $error = $result['response'] ?? $response->body();
                    return back()->with('error', 'Mailketing Gagal: ' . $error);
                }
            } else {
                // Implement SMTP test using Mail facade if needed, but primarily focusing on Mailketing
                \Illuminate\Support\Facades\Mail::raw('Halo, ini adalah email percobaan via SMTP.', function ($message) use ($request, $settings) {
                    $message->to($request->test_email)
                            ->subject('Tes Koneksi SMTP');
                });
            }

            return back()->with('success', 'Email tes berhasil dikirim ke ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan Sistem: ' . $e->getMessage());
        }
    }
}
