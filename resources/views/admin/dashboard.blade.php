@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-slate-800 hover:shadow-soft-lg transition-all duration-300 group">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Buku</p>
                <p class="text-3xl sm:text-4xl font-black text-primary-600 dark:text-primary-500 tracking-tight">{{ $stats['books'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-slate-800 hover:shadow-soft-lg transition-all duration-300 group">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Artikel</p>
                <p class="text-3xl sm:text-4xl font-black text-accent-600 dark:text-accent-500 tracking-tight">{{ $stats['posts'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-slate-800 hover:shadow-soft-lg transition-all duration-300 group">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Pesan Baru</p>
                <p class="text-3xl sm:text-4xl font-black text-orange-500 dark:text-orange-400 tracking-tight">{{ $stats['contacts'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 transition-colors duration-300">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
        <h2 class="font-bold text-gray-900 dark:text-white text-lg">Pesan Kontak Terbaru</h2>
        <a href="{{ route('admin.contacts.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest hover:underline">Lihat Semua</a>
    </div>
    <div class="divide-y divide-gray-50 dark:divide-slate-800">
        @forelse($recentContacts as $contact)
        <div class="px-6 py-5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors group">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <p class="font-semibold text-gray-900 dark:text-slate-100">{{ $contact->name }}</p>
                    @if(!$contact->is_read)
                        <span class="px-2 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-[10px] font-bold uppercase rounded-full">Baru</span>
                    @endif
                </div>
                <p class="text-xs text-gray-600 dark:text-slate-400 font-medium">{{ $contact->email }} · {{ $contact->created_at->diffForHumans() }}</p>
                <p class="text-sm text-gray-600 dark:text-slate-400 mt-2 line-clamp-1 italic">"{{ Str::limit($contact->subject ?? $contact->message, 80) }}"</p>
            </div>
            <a href="{{ route('admin.contacts.show', $contact) }}" class="ml-4 p-2 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-xl hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-all shadow-sm">
                <ion-icon name="eye" class="text-lg"></ion-icon>
            </a>
        </div>
        @empty
        <div class="px-6 py-16 text-center text-gray-400 dark:text-slate-500">
            <ion-icon name="mail-open" class="text-5xl mb-3 opacity-20"></ion-icon>
            <p class="font-medium">Belum ada pesan masuk.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@php use Illuminate\Support\Str; @endphp
