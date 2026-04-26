<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function store(ContactRequest $request)
    {
        Contact::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        // Integrasi API Mailketing (Notifikasi Email Asinkron/Terisolasi)
        try {
            $mailSetting = \App\Models\MailSetting::getActive();
            
            if ($mailSetting && $mailSetting->is_active && $mailSetting->mailketing_api_key) {
                $recipient = $mailSetting->recipient_email ?: $mailSetting->from_address;
                $senderEmail = $mailSetting->mailketing_sender_email ?: $mailSetting->from_address;
                $senderName = $mailSetting->from_name ?: 'Notifikasi Website';
                
                $response = Http::withToken($mailSetting->mailketing_api_key)
                    ->timeout(10)
                    ->post('https://api.mailketing.co.id/api/v1/send', [
                        'sender' => $senderEmail,
                        'sender_name' => $senderName,
                        'recipient' => $recipient,
                        'subject' => 'Kontak Baru dari ' . $request->name,
                        'body_html' => '
                            <h2>Pesan Masuk Baru</h2>
                            <p><strong>Nama:</strong> ' . htmlspecialchars($request->name) . '</p>
                            <p><strong>Email:</strong> ' . htmlspecialchars($request->email) . '</p>
                            <p><strong>Subjek:</strong> ' . htmlspecialchars($request->subject ?? 'Tidak Ada Subjek') . '</p>
                            <p><strong>Pesan:</strong></p>
                            <blockquote style="background:#f9f9f9;padding:15px;border-left:5px solid #2563eb;">
                                ' . nl2br(htmlspecialchars($request->message)) . '
                            </blockquote>
                            <br><hr>
                            <small>IP Pengirim: ' . $request->ip() . ' | Waktu: ' . now()->format('d M Y H:i:s') . '</small>
                        '
                    ]);
                
                if (!$response->successful()) {
                    Log::warning('Mailketing API response tidak sukses: ' . $response->body());
                }
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Koneksi Mailketing gagal (timeout): ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('API Mailketing Gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
