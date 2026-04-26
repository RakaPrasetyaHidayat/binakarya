@extends('layouts.admin')

@section('title', 'Google Scholar Integration')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Google Scholar Integration</h2>
        <p class="text-gray-600 dark:text-slate-400 transition-colors">Sinkronisasi publikasi dengan Google Scholar</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.scholar-settings.update') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 space-y-6 transition-colors">
        @csrf
        @method('PUT')

        {{-- Status --}}
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
            <div>
                <div class="font-semibold text-gray-900 dark:text-white">Aktifkan Google Scholar</div>
                <div class="text-sm text-gray-500 dark:text-slate-400">Nyalakan untuk menampilkan publikasi</div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
            </label>
        </div>

        {{-- API Configuration --}}
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Google Scholar API Key</label>
                <input type="text" name="api_key" value="{{ old('api_key', $settings->api_key ?? '') }}" placeholder="AIzaSy..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">Dapatkan dari Google Cloud Console</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Author ID</label>
                    <input type="text" name="author_id" value="{{ old('author_id', $settings->author_id ?? '') }}" placeholder="Scholar Profile ID"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Institution</label>
                    <input type="text" name="institution" value="{{ old('institution', $settings->institution ?? '') }}" placeholder="Nama Institusi"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        {{-- Auto Sync --}}
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
            <div>
                <div class="font-semibold text-gray-900 dark:text-white">Sinkronisasi Otomatis</div>
                <div class="text-sm text-gray-500 dark:text-slate-400">Update data secara berkala</div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="auto_sync" value="1" {{ old('auto_sync', $settings->auto_sync ?? false) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
            </label>
        </div>

        {{-- Sync Interval --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Interval Sinkronisasi (jam)</label>
            <input type="number" name="sync_interval" value="{{ old('sync_interval', $settings->sync_interval ?? 24) }}" min="1" max="168"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
        </div>

        @if($settings && $settings->last_sync)
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
            <div class="flex items-center gap-3">
                <span class="text-blue-600 dark:text-blue-400">🕐</span>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    Terakhir sinkron: {{ $settings->last_sync->format('d M Y H:i') }}
                </div>
            </div>
        </div>
        @endif

        {{-- Buttons --}}
        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-slate-800">
            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all">
                Simpan Pengaturan
            </button>
            
            @if($settings && $settings->is_active)
            <a href="{{ route('admin.scholar-settings.sync') }}" onclick="event.preventDefault(); document.getElementById('sync-form').submit();" 
               class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-all flex items-center gap-2">
                <span>🔄</span> Sinkron Sekarang
            </a>
            <form id="sync-form" action="{{ route('admin.scholar-settings.sync') }}" method="POST" class="hidden">
                @csrf
            </form>
            @endif
        </div>
    </form>

    {{-- Publications Preview --}}
    @if(!empty($publications))
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 transition-colors">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Preview Publikasi ({{ count($publications) }})</h3>
        <div class="space-y-3">
            @foreach($publications as $pub)
            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                <div class="font-medium text-gray-900 dark:text-white">{{ $pub['title'] ?? 'Untitled' }}</div>
                <div class="text-sm text-gray-500 dark:text-slate-400">{{ $pub['authors'] ?? '' }} ({{ $pub['year'] ?? '' }})</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
