@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="rounded-xl shadow-sm border overflow-hidden transition-colors">
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="px-6 py-4 border-b">
            <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="font-semibold">Pesan Masuk</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
            <thead :class="darkMode ? 'bg-slate-700 text-gray-400' : 'bg-gray-50 text-gray-600'" class="text-xs uppercase transition-colors">
                <tr>
                    <th class="px-4 py-3 text-left">Pengirim</th>
                    <th class="px-4 py-3 text-left">Subjek</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody :class="darkMode ? 'divide-gray-800' : 'divide-gray-50'" class="divide-y transition-colors">
                @forelse($contacts as $contact)
                <tr :class="darkMode ? 'hover:bg-slate-700' : 'hover:bg-gray-50' + (!$contact->is_read ? ' font-medium' : '')" class="transition-colors">
                    <td class="px-4 py-3">
                        <p :class="darkMode ? 'text-gray-100' : 'text-gray-800'">{{ $contact->name }}</p>
                        <p :class="darkMode ? 'text-gray-400' : 'text-gray-500'" class="text-xs">{{ $contact->email }}</p>
                    </td>
                    <td :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="px-4 py-3">{{ Str::limit($contact->subject ?? $contact->message, 50) }}</td>
                    <td :class="darkMode ? 'text-gray-500' : 'text-gray-500'" class="px-4 py-3 text-xs">{{ $contact->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $contact->is_read ? 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                            {{ $contact->is_read ? 'Dibaca' : 'Baru' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Lihat</a>
                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 dark:text-red-400 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" class="px-4 py-8 text-center">Belum ada pesan masuk.</td></tr>
                @endforelse
            </tbody>
            </table>
        </div>
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="px-4 py-3 border-t transition-colors">{{ $contacts->links() }}</div>
    </div>
</div>
@endsection

@php use Illuminate\Support\Str; @endphp
