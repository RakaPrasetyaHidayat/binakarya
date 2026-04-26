@extends('layouts.admin')

@section('title', 'Pengaturan Email')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Pengaturan Email</h2>
        <p class="text-gray-600 dark:text-slate-400 transition-colors">Konfigurasi SMTP dan integrasi Mailketing</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.mail-settings.update') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 space-y-6 transition-colors">
        @csrf
        @method('PUT')

        {{-- Status --}}
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
            <div>
                <div class="font-semibold text-gray-900 dark:text-white">Aktifkan Pengiriman Email</div>
                <div class="text-sm text-gray-500 dark:text-slate-400">Nyalakan untuk mengaktifkan fitur email</div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
            </label>
        </div>

        {{-- Driver Selection --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Email Driver</label>
            <select name="driver" id="driverSelect" onchange="toggleDriverFields()"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <option value="smtp" {{ old('driver', $settings->driver ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP (Gmail, Outlook, dll)</option>
                <option value="mailketing" {{ old('driver', $settings->driver ?? '') == 'mailketing' ? 'selected' : '' }}>Mailketing</option>
            </select>
        </div>

        {{-- SMTP Settings --}}
        <div id="smtpFields" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">SMTP Host</label>
                    <input type="text" name="host" value="{{ old('host', $settings->host ?? '') }}" placeholder="smtp.gmail.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Port</label>
                    <input type="number" name="port" value="{{ old('port', $settings->port ?? 587) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Encryption</label>
                <select name="encryption"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    <option value="tls" {{ old('encryption', $settings->encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ old('encryption', $settings->encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $settings->username ?? '') }}" placeholder="email@domain.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah</p>
                </div>
            </div>
        </div>

        {{-- Mailketing Settings --}}
        <div id="mailketingFields" class="space-y-4 hidden">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <span class="text-blue-600 dark:text-blue-400 text-xl">📧</span>
                    <div>
                        <div class="font-semibold text-blue-800 dark:text-blue-300">Mailketing Integration</div>
                        <div class="text-sm text-blue-600 dark:text-blue-400">Masukkan API Key dari dashboard Mailketing Anda</div>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Mailketing API Key</label>
                <input type="text" name="mailketing_api_key" value="{{ old('mailketing_api_key', $settings->mailketing_api_key ?? '') }}" placeholder="mk_live_xxxxxxxxxx"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Sender Email</label>
                <input type="email" name="mailketing_sender_email" value="{{ old('mailketing_sender_email', $settings->mailketing_sender_email ?? '') }}" placeholder="noreply@domain.com"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Email Penerima Notifikasi</label>
                <input type="email" name="recipient_email" value="{{ old('recipient_email', $settings->recipient_email ?? '') }}" placeholder="admin@domain.com"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Email yang akan menerima notifikasi pesan kontak</p>
            </div>
        </div>

        {{-- From Settings --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-slate-800">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">From Email *</label>
                <input type="email" name="from_address" value="{{ old('from_address', $settings->from_address ?? '') }}" required placeholder="noreply@binakaryacendekia.id"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">From Name *</label>
                <input type="text" name="from_name" value="{{ old('from_name', $settings->from_name ?? '') }}" required placeholder="Bina Karya Cendekia"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-slate-800">
            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all">
                Simpan Pengaturan
            </button>
        </div>
    </form>

    {{-- Test Email --}}
    @if($settings && $settings->is_active)
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 transition-colors">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Tes Pengiriman Email</h3>
        <form action="{{ route('admin.mail-settings.test') }}" method="POST" class="flex gap-4">
            @csrf
            <input type="email" name="test_email" placeholder="email@example.com" required
                   class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-all">
                Kirim Tes
            </button>
        </form>
    </div>
    @endif
</div>

<script>
function toggleDriverFields() {
    const driver = document.getElementById('driverSelect').value;
    const smtpFields = document.getElementById('smtpFields');
    const mailketingFields = document.getElementById('mailketingFields');
    
    if (driver === 'mailketing') {
        smtpFields.classList.add('hidden');
        mailketingFields.classList.remove('hidden');
    } else {
        smtpFields.classList.remove('hidden');
        mailketingFields.classList.add('hidden');
    }
}

toggleDriverFields();
</script>
@endsection
