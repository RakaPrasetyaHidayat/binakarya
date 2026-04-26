@extends('layouts.admin')

@section('title', 'Detail Audit Log')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.audit-logs.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition">
        ← Kembali ke Audit Logs
    </a>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2 transition-colors">Detail Audit Log</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Summary Card --}}
        <div class="bg-white dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600 p-6 transition-colors">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide transition-colors">User</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $log->user?->name ?? 'Sistem' }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $log->user?->email }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide transition-colors">Aksi</div>
                    <div class="mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ match($log->action) {
                                'create' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                'update' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                'delete' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                'login' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                'logout' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400'
                            } }}">
                            {{ $log->action_label }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide transition-colors">Waktu</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $log->created_at->format('d M Y') }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide transition-colors">IP Address</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1 font-mono">{{ $log->ip_address }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $log->user_agent }}</div>
                </div>
            </div>

            @if($log->description)
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-600 transition-colors">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide transition-colors">Deskripsi</div>
                <p class="text-gray-900 dark:text-white mt-2">{{ $log->description }}</p>
            </div>
            @endif
        </div>

        {{-- Changes --}}
        @if(($log->old_values || $log->new_values) && $log->action !== 'login' && $log->action !== 'logout')
        <div class="bg-white dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600 p-6 transition-colors">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors">Perubahan Detail</h3>
            
            {{-- Old Values --}}
            @if($log->old_values)
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Data Sebelumnya</h4>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 transition-colors">
                    <dl class="space-y-2">
                        @foreach($log->old_values as $key => $value)
                            <div class="flex justify-between items-start gap-4">
                                <dt class="text-sm font-medium text-gray-700 dark:text-gray-400">{{ ucfirst($key) }}</dt>
                                <dd class="text-sm text-gray-600 dark:text-gray-300 break-all text-right">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
            @endif

            {{-- New Values --}}
            @if($log->new_values)
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Data Sekarang</h4>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 transition-colors">
                    <dl class="space-y-2">
                        @foreach($log->new_values as $key => $value)
                            <div class="flex justify-between items-start gap-4">
                                <dt class="text-sm font-medium text-gray-700 dark:text-gray-400">{{ ucfirst($key) }}</dt>
                                <dd class="text-sm text-gray-600 dark:text-gray-300 break-all text-right">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-6">
        {{-- Model Info --}}
        <div class="bg-white dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600 p-6 transition-colors">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Data Target</h4>
            @if($log->model_type)
                <div class="space-y-2">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Model</div>
                        <div class="text-sm font-mono text-gray-900 dark:text-white break-all">{{ class_basename($log->model_type) }}</div>
                    </div>
                    @if($log->model_id)
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">ID</div>
                        <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $log->model_id }}</div>
                    </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400">Tidak ada target model</p>
            @endif
        </div>

        {{-- Raw Data --}}
        <div class="bg-white dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600 p-6 transition-colors">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Info Teknis</h4>
            <div class="space-y-3">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Log ID</div>
                    <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $log->id }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">User Agent</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 break-all font-mono">{{ $log->user_agent }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Created At</div>
                    <div class="text-sm text-gray-900 dark:text-white">{{ $log->created_at }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
