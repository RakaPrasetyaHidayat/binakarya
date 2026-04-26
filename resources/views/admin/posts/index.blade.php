@extends('layouts.admin')

@section('title', 'Manajemen Artikel')

@section('content')
<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">Daftar Artikel</h2>
        <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
            + Tambah Artikel
        </a>
    </div>

    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="rounded-xl shadow-sm border overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
            <thead :class="darkMode ? 'bg-slate-700 text-gray-400' : 'bg-gray-50 text-gray-600'" class="text-xs uppercase transition-colors">
                <tr>
                    <th class="px-4 py-3 text-left">Judul</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Penulis</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody :class="darkMode ? 'divide-gray-800' : 'divide-gray-50'" class="divide-y transition-colors">
                @forelse($posts as $post)
                <tr :class="darkMode ? 'hover:bg-slate-700' : 'hover:bg-gray-50'" class="transition-colors">
                    <td class="px-4 py-3">
                        <p :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="font-medium">{{ Str::limit($post->title, 50) }}</p>
                    </td>
                    <td :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="px-4 py-3">{{ $post->category?->name ?? '-' }}</td>
                    <td :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="px-4 py-3">{{ $post->user->name }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $post->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400' }}">
                            {{ $post->is_published ? 'Publik' : 'Draft' }}
                        </span>
                    </td>
                    <td :class="darkMode ? 'text-gray-500' : 'text-gray-500'" class="px-4 py-3 text-xs">{{ $post->published_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 dark:text-red-400 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" class="px-4 py-8 text-center">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
            </table>
        </div>
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="px-4 py-3 border-t transition-colors">{{ $posts->links() }}</div>
    </div>
</div>
@endsection

@php use Illuminate\Support\Str; @endphp
