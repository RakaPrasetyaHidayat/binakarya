@extends('layouts.admin')

@section('title', isset($teamMember) ? 'Edit Anggota' : 'Tambah Anggota')

@section('styles')
@include('admin.partials.tinymce-styles')
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">
            {{ isset($teamMember) ? 'Edit Anggota' : 'Tambah Anggota Baru' }}
        </h2>
        <p class="text-gray-600 dark:text-slate-400 transition-colors">Kelola informasi anggota tim</p>
    </div>

    <form action="{{ isset($teamMember) ? route('admin.team-members.update', $teamMember) : route('admin.team-members.store') }}" 
          method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 space-y-6 transition-colors">
        @csrf
        @if(isset($teamMember)) @method('PUT') @endif

        {{-- Photo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Foto</label>
            @if(isset($teamMember) && $teamMember->photo_url)
                <div class="mb-3">
                    <img src="{{ $teamMember->photo_url }}" alt="Current" class="w-24 h-24 rounded-full object-cover">
                </div>
            @endif
            <input type="file" name="photo" accept="image/*" 
                   class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-300 transition-all cursor-pointer">
            <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
        </div>

        {{-- Name & Position --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $teamMember->name ?? '') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Jabatan *</label>
                <input type="text" name="position" value="{{ old('position', $teamMember->position ?? '') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
        </div>

        {{-- Role --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Peran/Spesialisasi</label>
            <input type="text" name="role" value="{{ old('role', $teamMember->role ?? '') }}" placeholder="Contoh: Founder, Ketua, Anggota"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
        </div>

        {{-- Bio --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Bio</label>
            <textarea name="bio" id="bio-editor">{{ old('bio', $teamMember->bio ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">LinkedIn URL</label>
            <input type="url" name="linkedin" value="{{ old('linkedin', $teamMember->linkedin ?? '') }}" placeholder="https://linkedin.com/in/username"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
        </div>

        {{-- Contact --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $teamMember->email ?? '') }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $teamMember->phone ?? '') }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
        </div>

        {{-- Order & Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Urutan Tampil</label>
                <input type="number" name="order" value="{{ old('order', $teamMember->order ?? 0) }}" min="0"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teamMember->is_active ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Tampilkan di website</span>
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-slate-800">
            <a href="{{ route('admin.team-members.index') }}" class="px-6 py-3 text-gray-600 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition">Batal</a>
            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all">
                {{ isset($teamMember) ? 'Simpan Perubahan' : 'Tambah Anggota' }}
            </button>
        </div>
    </form>
</div>

@include('admin.partials.tinymce-init', [
    'editors' => [
        ['selector' => '#bio-editor', 'height' => 280, 'toolbar' => 'minimal'],
    ]
])
@endsection
