<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
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

        try {
            $mailSetting = \App\Models\MailSetting::getActive();

            if ($mailSetting && $mailSetting->is_active) {
                $recipient   = $mailSetting->recipient_email ?: $mailSetting->from_address ?: 'bkcpublishing@gmail.com';
                $senderEmail = $mailSetting->mailketing_sender_email ?: $mailSetting->from_address ?: 'bkcpublishing@gmail.com';
                $fromName    = $request->name;
                $emailSubject = 'New Contact Message from ' . $request->name;

                // Siapkan semua variabel sebelum masuk ke string HTML
                $name        = htmlspecialchars($request->name);
                $email       = htmlspecialchars($request->email);
                $subjectText = htmlspecialchars($request->subject ?? '-');
                $messageText = nl2br(htmlspecialchars($request->message));
                $time        = now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';
                $ip          = $request->ip();

                // Bangun URL reply terpisah agar tidak ada interpolasi di dalam atribut HTML
                $replyUrl = 'mailto:' . rawurlencode($request->email)
                    . '?subject=' . rawurlencode('Re: ' . ($request->subject ?? 'Pesan dari ' . $request->name));

                $emailContent = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;">'

                    // Header
                    . '<div style="background:linear-gradient(135deg,#2563eb,#1e40af);padding:28px 32px;">'
                    . '<h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">&#128236; Pesan Baru Masuk</h1>'
                    . '<p style="margin:6px 0 0;color:#bfdbfe;font-size:13px;">Bina Karya Cendekia &mdash; Form Kontak Website</p>'
                    . '</div>'

                    // Body
                    . '<div style="padding:28px 32px;">'

                    // Tabel info pengirim
                    . '<table style="width:100%;border-collapse:collapse;margin-bottom:20px;background:#f8fafc;border-radius:8px;">'
                    . '<tr style="border-bottom:1px solid #e2e8f0;">'
                    . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;width:110px;">Nama</td>'
                    . '<td style="padding:12px 16px;font-size:14px;color:#1e293b;font-weight:700;">' . $name . '</td>'
                    . '</tr>'
                    . '<tr style="border-bottom:1px solid #e2e8f0;">'
                    . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;">Email</td>'
                    . '<td style="padding:12px 16px;font-size:14px;"><a href="mailto:' . $email . '" style="color:#2563eb;text-decoration:none;">' . $email . '</a></td>'
                    . '</tr>'
                    . '<tr style="border-bottom:1px solid #e2e8f0;">'
                    . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;">Subjek</td>'
                    . '<td style="padding:12px 16px;font-size:14px;color:#1e293b;">' . $subjectText . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;">Waktu</td>'
                    . '<td style="padding:12px 16px;font-size:14px;color:#1e293b;">' . $time . '</td>'
                    . '</tr>'
                    . '</table>'

                    // Isi pesan
                    . '<p style="font-size:13px;font-weight:600;color:#64748b;margin:0 0 8px;">Isi Pesan:</p>'
                    . '<div style="background:#f0f7ff;padding:20px;border-radius:8px;border-left:4px solid #2563eb;font-size:14px;color:#1e293b;line-height:1.7;">'
                    . $messageText
                    . '</div>'

                    // Tombol balas
                    . '<div style="margin-top:24px;text-align:center;">'
                    . '<a href="' . $replyUrl . '" style="display:inline-block;background:#2563eb;color:#ffffff;padding:12px 28px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;">Balas Pesan Ini</a>'
                    . '</div>'

                    . '</div>'

                    // Footer
                    . '<div style="background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;text-align:center;">'
                    . '<p style="margin:0;font-size:11px;color:#94a3b8;">Email otomatis dari website Bina Karya Cendekia &bull; IP: ' . $ip . '</p>'
                    . '</div>'

                    . '</div>';

                if ($mailSetting->driver === 'mailketing' && $mailSetting->mailketing_api_key) {
                    $response = Http::asForm()->timeout(15)
                        ->post('https://api.mailketing.co.id/api/v1/send', [
                            'api_token'  => trim($mailSetting->mailketing_api_key),
                            'from_name'  => $fromName,
                            'from_email' => $senderEmail,
                            'recipient'  => $recipient,
                            'reply_to'   => $request->email,
                            'subject'    => $emailSubject,
                            'content'    => $emailContent,
                        ]);

                    $result = $response->json();
                    if (!$response->successful() || (isset($result['status']) && $result['status'] !== 'success')) {
                        Log::error('Mailketing Gagal:', ['response' => $response->body()]);
                    }
                } else {
                    \Illuminate\Support\Facades\Mail::html($emailContent, function ($msg) use ($request, $recipient, $fromName, $emailSubject) {
                        $msg->to($recipient)
                            ->replyTo($request->email, $fromName)
                            ->subject($emailSubject);
                    });
                }
            }
        } catch (\Exception $e) {
            Log::error('Kritis - Gagal mengirim notifikasi email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
