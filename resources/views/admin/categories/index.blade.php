@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Add Category Form --}}
    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="rounded-xl shadow-sm border p-6 transition-colors">
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-base font-semibold mb-4">Tambah Kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Tipe</label>
                <select name="type" :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition">
                    <option value="book">Buku</option>
                    <option value="blog">Blog</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Tambah
            </button>
        </form>
    </div>

    {{-- Category List --}}
    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="rounded-xl shadow-sm border overflow-hidden transition-colors">
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="px-6 py-4 border-b">
            <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-base font-semibold">Daftar Kategori</h2>
        </div>
        <div :class="darkMode ? 'divide-gray-800' : 'divide-gray-50'" class="divide-y transition-colors">
            @forelse($categories as $category)
            <div x-data="{ editing: false, newName: '{{ $category->name }}' }" 
                 :class="darkMode ? 'hover:bg-slate-700' : 'hover:bg-gray-50'" 
                 class="px-6 py-4 flex items-center justify-between transition-colors">
                <div class="flex-grow mr-4">
                    <div x-show="!editing">
                        <p :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="font-medium text-sm">{{ $category->name }}</p>
                        <p :class="darkMode ? 'text-gray-400' : 'text-gray-500'" class="text-xs">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $category->type === 'book' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                {{ $category->type === 'book' ? 'Katalog Buku' : 'Blog & Artikel' }}
                            </span>
                            · {{ $category->books_count + $category->posts_count }} item
                        </p>
                    </div>
                    <div x-show="editing" x-cloak>
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="name" x-model="newName" 
                                   class="flex-grow border rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 bg-transparent"
                                   :class="darkMode ? 'border-slate-600 text-white' : 'border-gray-300 text-gray-900'">
                            <button type="submit" class="text-green-600 hover:text-green-700 text-xs font-bold">Simpan</button>
                            <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-500 text-xs font-bold">Batal</button>
                        </form>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0" x-show="!editing">
                    <button @click="editing = true" class="text-primary-600 dark:text-primary-400 hover:underline text-xs font-medium">Edit</button>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 dark:text-red-400 hover:underline text-xs font-medium">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div :class="darkMode ? 'text-gray-500' : 'text-gray-400'" class="px-6 py-8 text-center text-sm">Belum ada kategori.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
