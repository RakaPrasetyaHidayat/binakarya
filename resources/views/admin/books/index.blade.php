@extends('layouts.admin')

@section('title', 'Manajemen Buku')

@section('content')
<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">Daftar Buku</h2>
        <a href="{{ route('admin.books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
            + Tambah Buku
        </a>
    </div>

    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="rounded-xl shadow-sm border overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
            <thead :class="darkMode ? 'bg-slate-700 text-gray-400' : 'bg-gray-50 text-gray-600'" class="text-xs uppercase transition-colors">
                <tr>
                    <th class="px-4 py-3 text-left">Cover</th>
                    <th class="px-4 py-3 text-left">Judul / Penulis</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Tahun</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody :class="darkMode ? 'divide-gray-800' : 'divide-gray-50'" class="divide-y transition-colors">
                @forelse($books as $book)
                <tr :class="darkMode ? 'hover:bg-slate-700' : 'hover:bg-gray-50'" class="transition-colors">
                    <td class="px-4 py-3">
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" alt="" class="w-10 h-14 object-cover rounded">
                        @else
                            <div :class="darkMode ? 'bg-slate-700 text-gray-600' : 'bg-gray-100 text-gray-300'" class="w-10 h-14 rounded flex items-center justify-center text-xs">N/A</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <p :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="font-medium">{{ Str::limit($book->title, 50) }}</p>
                        <p :class="darkMode ? 'text-gray-400' : 'text-gray-500'" class="text-xs">{{ $book->author }}</p>
                    </td>
                    <td :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="px-4 py-3">{{ $book->category?->name ?? '-' }}</td>
                    <td :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="px-4 py-3">{{ $book->published_year ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $book->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400' }}">
                            {{ $book->is_published ? 'Aktif' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 dark:text-red-400 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" class="px-4 py-8 text-center">Belum ada buku.</td></tr>
                @endforelse
            </tbody>
            </table>
        </div>
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="px-4 py-3 border-t transition-colors">{{ $books->links() }}</div>
    </div>
</div>
@endsection

@php use Illuminate\Support\Str; @endphp
