<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    /**
     * Daftar semua subscriber.
     */
    public function index(Request $request)
    {
        $query = Subscriber::latest();

        if ($search = $request->input('q')) {
            $query->where('email', 'like', "%{$search}%");
        }

        $subscribers = $query->paginate(20)->withQueryString();
        $total       = Subscriber::count();

        return view('admin.subscribers.index', compact('subscribers', 'total'));
    }

    /**
     * Hapus satu subscriber.
     */
    public function destroy(Subscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('success', "Subscriber '{$email}' berhasil dihapus.");
    }

    /**
     * Kirim broadcast email ke semua subscriber.
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'subject.required' => 'Subjek email wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        $subscribers = Subscriber::all();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'Belum ada subscriber yang terdaftar.');
        }

        $mailSetting = MailSetting::getActive();
        $sent        = 0;
        $failed      = 0;

        $htmlBody = $this->buildBroadcastHtml(
            $request->subject,
            $request->message
        );

        foreach ($subscribers as $subscriber) {
            try {
                if ($mailSetting && $mailSetting->is_active && $mailSetting->driver === 'mailketing' && $mailSetting->mailketing_api_key) {
                    $response = Http::asForm()->timeout(15)
                        ->post('https://api.mailketing.co.id/api/v1/send', [
                            'api_token'  => trim($mailSetting->mailketing_api_key),
                            'from_name'  => $mailSetting->from_name  ?? 'Bina Karya Cendekia',
                            'from_email' => $mailSetting->mailketing_sender_email ?? $mailSetting->from_address ?? 'bkcpublishing@gmail.com',
                            'recipient'  => $subscriber->email,
                            'subject'    => $request->subject,
                            'content'    => $htmlBody,
                        ]);

                    $response->successful() ? $sent++ : $failed++;
                } else {
                    Mail::html($htmlBody, function ($msg) use ($subscriber, $request) {
                        $msg->to($subscriber->email)
                            ->subject($request->subject);
                    });
                    $sent++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Broadcast gagal ke {$subscriber->email}: " . $e->getMessage());
            }
        }

        $message = "Broadcast selesai: {$sent} email terkirim";
        if ($failed > 0) {
            $message .= ", {$failed} gagal";
        }
        $message .= '.';

        return back()->with('success', $message);
    }

    /**
     * Build HTML email untuk broadcast.
     */
    private function buildBroadcastHtml(string $subject, string $rawMessage): string
    {
        // Konversi newline ke paragraf HTML
        $paragraphs = array_filter(array_map('trim', explode("\n", $rawMessage)));
        $bodyHtml   = implode('', array_map(fn ($p) => "<p style=\"margin:0 0 14px;font-size:15px;color:#1e293b;line-height:1.7;\">{$p}</p>", $paragraphs));

        return '<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 16px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">

      <!-- Header -->
      <tr>
        <td style="background:linear-gradient(135deg,#2563eb,#1e40af);padding:28px 32px;">
          <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">Bina Karya Cendekia</h1>
          <p style="margin:6px 0 0;color:#bfdbfe;font-size:13px;">Informasi Terbaru untuk Anda</p>
        </td>
      </tr>

      <!-- Subject -->
      <tr>
        <td style="padding:28px 32px 0;">
          <h2 style="margin:0 0 20px;font-size:18px;color:#0f172a;font-weight:700;">' . htmlspecialchars($subject) . '</h2>
          ' . $bodyHtml . '
        </td>
      </tr>

      <!-- Divider -->
      <tr><td style="padding:0 32px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:20px 0;"></td></tr>

      <!-- CTA -->
      <tr>
        <td style="padding:0 32px 28px;text-align:center;">
          <a href="https://binakaryacendekia.id" style="display:inline-block;background:#2563eb;color:#ffffff;font-size:14px;font-weight:600;padding:12px 28px;border-radius:8px;text-decoration:none;">
            Kunjungi Website Kami
          </a>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;text-align:center;">
          <p style="margin:0 0 6px;font-size:12px;color:#64748b;">
            Anda menerima email ini karena berlangganan newsletter Bina Karya Cendekia.
          </p>
          <p style="margin:0;font-size:11px;color:#94a3b8;">
            &copy; ' . date('Y') . ' Bina Karya Cendekia. Semua hak dilindungi.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>';
    }
}
