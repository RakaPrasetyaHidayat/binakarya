@extends('layouts.admin')

@section('title', 'Subscriber Newsletter')

@section('content')

{{-- Stats + Broadcast Form --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Stat Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-slate-800 flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
            <ion-icon name="mail-outline" class="text-3xl text-primary-600 dark:text-primary-400"></ion-icon>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Subscriber</p>
            <p class="text-4xl font-black text-primary-600 dark:text-primary-400">{{ $total }}</p>
        </div>
    </div>

    {{-- Broadcast Form --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white text-base mb-4 flex items-center gap-2">
            <ion-icon name="send-outline" class="text-primary-600 text-lg"></ion-icon>
            Kirim Notifikasi ke Semua Subscriber
        </h2>

        <form method="POST" action="{{ route('admin.subscribers.broadcast') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Subjek Email</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="Contoh: Informasi Terbaru dari Bina Karya Cendekia"
                           class="w-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition @error('subject') border-red-400 @enderror"
                           required>
                    @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Isi Pesan</label>
                    <textarea name="message" rows="4"
                              placeholder="Tulis pesan yang ingin dikirimkan ke semua subscriber..."
                              class="w-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition resize-none @error('message') border-red-400 @enderror"
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <p class="text-xs text-gray-400 dark:text-slate-500">
                        Email akan dikirim ke <strong class="text-gray-700 dark:text-gray-300">{{ $total }} subscriber</strong>
                    </p>
                    <button type="submit"
                            :disabled="loading"
                            class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-md text-sm active:scale-95">
                        <span x-show="!loading" class="flex items-center gap-2">
                            <ion-icon name="send-outline" class="text-base"></ion-icon>
                            Kirim Sekarang
                        </span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Subscriber List --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h2 class="font-bold text-gray-900 dark:text-white text-base">Daftar Subscriber</h2>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari email..."
                   class="border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition w-48">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('admin.subscribers.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if($subscribers->isEmpty())
        <div class="px-6 py-16 text-center text-gray-400 dark:text-slate-500">
            <ion-icon name="mail-open-outline" class="text-5xl mb-3 opacity-20"></ion-icon>
            <p class="font-medium">Belum ada subscriber yang terdaftar.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-800">
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">IP Address</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="text-right px-6 py-3 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @foreach($subscribers as $i => $subscriber)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-gray-400 dark:text-slate-500 text-xs">
                                {{ $subscribers->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-primary-600 dark:text-primary-400">
                                            {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                        </span>
                                    </div>
                                    <a href="mailto:{{ $subscriber->email }}"
                                       class="font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition">
                                        {{ $subscriber->email }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-slate-400 text-xs font-mono">
                                {{ $subscriber->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-slate-400 text-xs">
                                <span title="{{ $subscriber->created_at->format('d M Y H:i') }}">
                                    {{ $subscriber->created_at->format('d M Y') }}
                                </span>
                                <span class="block text-gray-400 dark:text-slate-500">{{ $subscriber->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}"
                                      onsubmit="return confirm('Hapus subscriber {{ $subscriber->email }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition"
                                            title="Hapus">
                                        <ion-icon name="trash-outline" class="text-base"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($subscribers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800">
                {{ $subscribers->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
