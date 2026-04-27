@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Add User Form --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 transition-colors duration-300">
        <h2 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Tambah Pengguna</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Role</label>
                <select name="role" class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="contributor">Contributor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
                <ion-icon name="person-add-outline" class="text-lg"></ion-icon>
                Tambah Pengguna
            </button>
        </form>
    </div>

    {{-- User List --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors duration-300">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white text-lg">Daftar Pengguna</h2>
            <span class="px-2 py-1 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 text-[10px] font-bold uppercase rounded-lg">Total: {{ $users->total() }}</span>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-slate-800">
            @foreach($users as $user)
            <div x-data="{ editModal: false }" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-slate-100 text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-500">{{ $user->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-gray-100 text-gray-600 dark:bg-slate-700/40 dark:text-gray-400' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center gap-1">
                    {{-- Edit Button --}}
                    <button @click="editModal = true" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Edit">
                        <ion-icon name="create-outline" class="text-lg"></ion-icon>
                    </button>

                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                            <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Edit Modal --}}
                <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="editModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="editModal = false" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="editModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Pengguna</h3>
                                <button @click="editModal = false" class="text-gray-400 hover:text-gray-500">
                                    <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Nama</label>
                                    <input type="text" name="name" value="{{ $user->name }}" required
                                           class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" required
                                           class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Role</label>
                                    <select name="role" class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                        <option value="contributor" {{ $user->role === 'contributor' ? 'selected' : '' }}>Contributor</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
                                    <p class="text-xs text-gray-500 mb-3 italic">Kosongkan password jika tidak ingin mengubahnya.</p>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Password Baru</label>
                                            <input type="password" name="password" 
                                                   class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                                            <input type="password" name="password_confirmation" 
                                                   class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" @click="editModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Batal</button>
                                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg shadow-lg shadow-primary-500/20 transition-all">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800 transition-colors">{{ $users->links() }}</div>
    </div>
</div>
@endsection
