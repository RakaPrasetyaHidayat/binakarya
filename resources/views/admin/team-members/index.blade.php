@extends('layouts.admin')

@section('title', 'Struktur Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Struktur Anggota</h2>
            <p class="text-gray-600 dark:text-slate-400 transition-colors">Kelola anggota tim dan struktur organisasi</p>
        </div>
        <a href="{{ route('admin.team-members.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
            <span class="text-lg">+</span> Tambah Anggota
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Foto</th>
                        <th class="px-6 py-4 font-semibold">Nama & Jabatan</th>
                        <th class="px-6 py-4 font-semibold">Kontak</th>
                        <th class="px-6 py-4 font-semibold text-center">Urutan</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            @if($member->photo_url)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-lg">👤</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-slate-200">{{ $member->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">{{ $member->position }}</div>
                            @if($member->role)
                                <div class="text-xs text-primary-600 dark:text-primary-400 mt-1">{{ $member->role }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($member->email)
                                <div class="text-sm text-gray-600 dark:text-slate-400">{{ $member->email }}</div>
                            @endif
                            @if($member->phone)
                                <div class="text-sm text-gray-500 dark:text-slate-500">{{ $member->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 px-3 py-1 rounded-full text-sm font-medium">{{ $member->order }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($member->is_active)
                                <span class="px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-bold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.team-members.edit', $member) }}" class="p-2 text-gray-400 hover:text-blue-500 transition" title="Edit">
                                    <span class="text-lg">✏️</span>
                                </a>
                                <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition" title="Hapus">
                                        <span class="text-lg">🗑️</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-400 dark:text-slate-500">
                            <div class="text-5xl mb-3 opacity-30">👥</div>
                            <p class="font-medium text-lg">Belum ada anggota</p>
                            <p class="text-sm">Tambahkan anggota tim untuk ditampilkan di website</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
