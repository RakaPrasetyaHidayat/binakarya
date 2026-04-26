@extends('layouts.admin')

@section('title', 'Pengaturan TinyMCE')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition">← Kembali</a>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Pengaturan TinyMCE Editor</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">API Key TinyMCE</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Masukkan API Key dari <a href="https://www.tiny.cloud/" target="_blank" class="text-primary-600 hover:underline">TinyMCE Cloud</a> untuk menggunakan editor premium. 
                Jika dikosongkan, akan menggunakan versi gratis (self-hosted).
            </p>
        </div>

        <form method="POST" action="{{ route('admin.tinymce-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="tinymce_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    TinyMCE API Key
                </label>
                <input 
                    type="text" 
                    id="tinymce_api_key" 
                    name="tinymce_api_key" 
                    value="{{ old('tinymce_api_key', $apiKey) }}"
                    placeholder="Contoh: your-api-key-here"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                >
                @error('tinymce_api_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Dapatkan API Key gratis di <a href="https://www.tiny.cloud/auth/signup/" target="_blank" class="text-primary-600 hover:underline">tiny.cloud</a>
                </p>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">ℹ️ Informasi</h4>
        <ul class="text-xs text-blue-800 dark:text-blue-400 space-y-1 list-disc list-inside">
            <li>API Key ini digunakan untuk mengaktifkan TinyMCE Cloud pada semua form editor di admin panel.</li>
            <li>Tanpa API Key, TinyMCE akan berjalan dalam mode self-hosted (gratis).</li>
            <li>Pengaturan ini terpisah dari pengaturan Mailketing.</li>
        </ul>
    </div>
</div>
@endsection

