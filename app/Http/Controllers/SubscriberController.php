<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email'   => 'required|email|max:150',
            'consent' => 'accepted',
        ], [
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'consent.accepted' => 'Anda harus menyetujui ketentuan untuk berlangganan.',
        ]);

        $already = Subscriber::where('email', $request->email)->exists();

        if ($already) {
            return back()->with('subscribe_info', 'Email ini sudah terdaftar sebagai subscriber.');
        }

        Subscriber::create([
            'email'      => $request->email,
            'ip_address' => $request->ip(),
        ]);

        // Kirim notifikasi ke admin
        $this->sendNotification($request->email);

        return back()->with('subscribe_success', 'Terima kasih! Anda berhasil berlangganan informasi terbaru kami.');
    }

    private function sendNotification(string $subscriberEmail): void
    {
        try {
            $mailSetting = MailSetting::getActive();

            if (!$mailSetting || !$mailSetting->is_active) {
                return;
            }

            $recipient   = $mailSetting->recipient_email ?: $mailSetting->from_address ?: 'bkcpublishing@gmail.com';
            $senderEmail = $mailSetting->mailketing_sender_email ?: $mailSetting->from_address ?: 'bkcpublishing@gmail.com';
            $senderName  = $mailSetting->from_name ?: 'Bina Karya Cendekia';
            $time        = now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';
            $totalCount  = Subscriber::count();

            $emailContent = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;">'

                // Header
                . '<div style="background:linear-gradient(135deg,#2563eb,#1e40af);padding:24px 32px;">'
                . '<h1 style="margin:0;color:#ffffff;font-size:18px;font-weight:700;">&#128236; Subscriber Baru!</h1>'
                . '<p style="margin:6px 0 0;color:#bfdbfe;font-size:13px;">Bina Karya Cendekia &mdash; Newsletter</p>'
                . '</div>'

                // Body
                . '<div style="padding:28px 32px;">'
                . '<p style="font-size:14px;color:#1e293b;margin:0 0 20px;">Ada subscriber baru yang mendaftar untuk menerima informasi terbaru dari website Anda.</p>'

                . '<table style="width:100%;border-collapse:collapse;background:#f8fafc;border-radius:8px;">'
                . '<tr style="border-bottom:1px solid #e2e8f0;">'
                . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;width:140px;">Email</td>'
                . '<td style="padding:12px 16px;font-size:14px;"><a href="mailto:' . htmlspecialchars($subscriberEmail) . '" style="color:#2563eb;text-decoration:none;">' . htmlspecialchars($subscriberEmail) . '</a></td>'
                . '</tr>'
                . '<tr style="border-bottom:1px solid #e2e8f0;">'
                . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;">Waktu Daftar</td>'
                . '<td style="padding:12px 16px;font-size:14px;color:#1e293b;">' . $time . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;font-size:13px;color:#64748b;font-weight:600;">Total Subscriber</td>'
                . '<td style="padding:12px 16px;font-size:14px;color:#1e293b;font-weight:700;">' . $totalCount . ' orang</td>'
                . '</tr>'
                . '</table>'

                . '</div>'

                // Footer
                . '<div style="background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;text-align:center;">'
                . '<p style="margin:0;font-size:11px;color:#94a3b8;">Notifikasi otomatis dari website Bina Karya Cendekia</p>'
                . '</div>'

                . '</div>';

            if ($mailSetting->driver === 'mailketing' && $mailSetting->mailketing_api_key) {
                $response = Http::asForm()->timeout(15)
                    ->post('https://api.mailketing.co.id/api/v1/send', [
                        'api_token'  => trim($mailSetting->mailketing_api_key),
                        'from_name'  => $senderName,
                        'from_email' => $senderEmail,
                        'recipient'  => $recipient,
                        'subject'    => 'Subscriber Baru: ' . $subscriberEmail,
                        'content'    => $emailContent,
                    ]);

                if (!$response->successful()) {
                    Log::error('Mailketing subscriber notification failed', ['response' => $response->body()]);
                }
            } else {
                \Illuminate\Support\Facades\Mail::html($emailContent, function ($msg) use ($recipient, $senderName, $subscriberEmail) {
                    $msg->to($recipient)
                        ->subject('Subscriber Baru: ' . $subscriberEmail);
                });
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim notifikasi subscriber: ' . $e->getMessage());
        }
    }
}
