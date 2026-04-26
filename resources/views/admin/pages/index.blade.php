@extends('layouts.admin')

@section('title', 'Manajemen Halaman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">📄 Halaman Statis</h2>
            <p class="text-gray-600 dark:text-slate-400 transition-colors">Kelola konten halaman tambahan (Visi Misi, Program, dll)</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
            <ion-icon name="add-outline" class="text-lg"></ion-icon>
            Buat Halaman
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Judul Halaman</th>
                        <th class="px-6 py-4 font-semibold">URL / Slug</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Terakhir Diubah</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($pages as $page)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 dark:text-slate-200">{{ $page->title }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded text-primary-600 dark:text-primary-400">/p/{{ $page->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($page->is_published)
                                <span class="px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-bold uppercase tracking-wider">Publik</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-[10px] font-bold uppercase tracking-wider">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-500">
                            {{ $page->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-primary-600 transition" title="Lihat">
                                    <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="p-2 text-gray-400 hover:text-blue-500 transition" title="Edit">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition" title="Hapus">
                                        <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-400 dark:text-slate-500">
                            <ion-icon name="document-text-outline" class="text-5xl mb-3 opacity-20"></ion-icon>
                            <p class="font-medium text-lg">Belum ada halaman.</p>
                            <p class="text-sm">Klik tombool "Buat Halaman" untuk mulai menulis.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-800">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
