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

        // Integrasi Pengiriman Email (Notifikasi)
        try {
            $mailSetting = \App\Models\MailSetting::getActive();
            
            if ($mailSetting && $mailSetting->is_active) {
                $recipient = $mailSetting->recipient_email ?: $mailSetting->from_address;
                $senderName = $mailSetting->from_name ?: 'Notifikasi Website';
                
                if ($mailSetting->driver === 'mailketing' && $mailSetting->mailketing_api_key) {
                    // --- FLOW MAILKETING ---
                    $senderEmail = $mailSetting->mailketing_sender_email ?: $mailSetting->from_address;
                    
                    $response = Http::asForm()->timeout(15)
                        ->post('https://api.mailketing.co.id/api/v1/send', [
                            'api_token' => trim($mailSetting->mailketing_api_key),
                            'from_name' => $senderName,
                            'from_email' => $senderEmail,
                            'recipient' => $recipient,
                            'subject' => 'Kontak Baru: ' . $request->name,
                            'content' => '
                                <div style="font-family:sans-serif;line-height:1.6;color:#333;">
                                    <h2 style="color:#2563eb;border-bottom:1px solid #eee;padding-bottom:10px;">Pesan Masuk Baru</h2>
                                    <p><strong>Nama:</strong> ' . htmlspecialchars($request->name) . '</p>
                                    <p><strong>Email:</strong> ' . htmlspecialchars($request->email) . '</p>
                                    <p><strong>Subjek:</strong> ' . htmlspecialchars($request->subject ?? 'Tidak Ada Subjek') . '</p>
                                    <p><strong>Pesan:</strong></p>
                                    <div style="background:#f8fafc;padding:20px;border-radius:8px;border-left:4px solid #2563eb;margin:15px 0;">
                                        ' . nl2br(htmlspecialchars($request->message)) . '
                                    </div>
                                    <hr style="border:0;border-top:1px solid #eee;margin:20px 0;">
                                    <p style="font-size:12px;color:#64748b;">
                                        Dikirim dari Formulir Kontak Website<br>
                                        IP: ' . $request->ip() . ' | ' . now()->format('d M Y H:i:s') . '
                                    </p>
                                </div>
                            '
                        ]);
                    
                    $result = $response->json();
                    if (!$response->successful() || (isset($result['status']) && $result['status'] !== 'success')) {
                        Log::error('Mailketing Gagal:', ['response' => $response->body()]);
                    }
                } else {
                    // --- FLOW SMTP / LARAVEL DEFAULT ---
                    \Illuminate\Support\Facades\Mail::html('
                        <h2>Pesan Masuk Baru</h2>
                        <p><strong>Nama:</strong> ' . htmlspecialchars($request->name) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($request->email) . '</p>
                        <p><strong>Pesan:</strong></p>
                        <p>' . nl2br(htmlspecialchars($request->message)) . '</p>
                    ', function ($message) use ($request, $recipient, $senderName) {
                        $message->to($recipient)
                                ->subject('Kontak Baru: ' . $request->name);
                    });
                }
            }
        } catch (\Exception $e) {
            Log::error('Kritis - Gagal mengirim notifikasi email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
