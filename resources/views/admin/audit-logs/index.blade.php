@extends('layouts.admin')

@section('title', 'Audit Logs - Admin')

@section('content')
<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">📋 Audit Logs</h2>
        <p class="text-gray-600 dark:text-gray-400 transition-colors">Track semua aktivitas perubahan data dan login admin</p>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 transition-colors duration-300">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Cari deskripsi atau IP..." value="{{ request()->get('search') }}"
                   class="px-4 py-2 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl text-sm transition-all focus:ring-2 focus:ring-primary-500">
            
            <select name="action" class="px-4 py-2 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl text-sm transition-all focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Aksi</option>
                <option value="create" {{ request()->get('action') === 'create' ? 'selected' : '' }}>Dibuat</option>
                <option value="update" {{ request()->get('action') === 'update' ? 'selected' : '' }}>Diubah</option>
                <option value="delete" {{ request()->get('action') === 'delete' ? 'selected' : '' }}>Dihapus</option>
                <option value="login" {{ request()->get('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request()->get('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            </select>

            <input type="date" name="from_date" value="{{ request()->get('from_date') }}"
                   class="px-4 py-2 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl text-sm transition-all focus:ring-2 focus:ring-primary-500">
            
            <input type="date" name="to_date" value="{{ request()->get('to_date') }}"
                   class="px-4 py-2 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-xl text-sm transition-all focus:ring-2 focus:ring-primary-500">
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl text-sm font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-500/20">
                Cari Logs
            </button>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-gray-300 dark:hover:bg-slate-700 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Audit Logs Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-soft transition-colors duration-300">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 transition-colors">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">Waktu</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">User</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 text-center">Aksi</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">Model</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">IP Address</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 transition-colors">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-slate-400">
                        {{ $log->created_at->format('d M, H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900 dark:text-slate-200">{{ $log->user?->name ?? 'Sistem' }}</div>
                        <div class="text-[10px] text-gray-400 dark:text-slate-500 font-medium tracking-tight">{{ $log->user?->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                            {{ match($log->action) {
                                'create' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                'update' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                'delete' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                'login' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                'logout' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                                default => 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-400'
                            } }}">
                            {{ $log->action_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-700 dark:text-slate-300 truncate max-w-[120px]">{{ $log->model_type ? class_basename($log->model_type) : '—' }}</div>
                        @if($log->model_id)
                            <div class="text-[10px] text-gray-400 dark:text-slate-500">ID: #{{ $log->model_id }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-slate-500">
                        {{ $log->ip_address }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.audit-logs.show', $log) }}" 
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                            Detail <ion-icon name="arrow-forward-outline"></ion-icon>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-slate-500">
                        <ion-icon name="clipboard-outline" class="text-4xl mb-2 opacity-20"></ion-icon>
                        <p class="font-medium">Tidak ada audit logs ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
