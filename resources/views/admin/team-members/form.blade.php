@extends('layouts.admin')

@section('title', isset($teamMember) ? 'Edit Anggota' : 'Tambah Anggota')

@section('styles')
<style>
.tox-tinymce { border-radius: 0.5rem !important; }
html.dark .tox { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
html.dark .tox-editor-header { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
html.dark .tox-toolbar__primary { background-color: rgb(31, 41, 55) !important; background-image: none !important; }
html.dark .tox-statusbar { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
html.dark .tox-statusbar__text-container { color: rgb(156, 163, 175) !important; }
html.dark .tox-edit-area__iframe { background-color: rgb(31, 41, 55) !important; }
html.dark .tox-mbtn__select-label, html.dark .tox-tbtn { color: rgb(209, 213, 219) !important; }
html.dark .tox-tbtn:hover, html.dark .tox-tbtn--enabled { background-color: rgb(55, 65, 81) !important; }
html.dark .tox-menu, html.dark .tox-dialog, html.dark .tox-dialog__header, html.dark .tox-dialog__body, html.dark .tox-dialog__footer { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
html.dark .tox-dialog__title { color: rgb(243, 244, 246) !important; }
html.dark .tox-label { color: rgb(209, 213, 219) !important; }
html.dark .tox-textfield, html.dark .tox-listboxfield .tox-listbox--select { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; color: rgb(243, 244, 246) !important; }
html.dark .tox-button--secondary { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; color: rgb(243, 244, 246) !important; }
</style>
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
            <textarea name="bio" id="tinymce-bio" class="hidden">{{ old('bio', $teamMember->bio ?? '') }}</textarea>
            <div id="editor-bio" class="rounded-xl overflow-hidden" style="min-height: 250px;"></div>
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

@php
$tinymceApiKey = \App\Models\Setting::get('tinymce_api_key', '');
$tinymceSrc = $tinymceApiKey 
    ? 'https://cdn.tiny.cloud/1/' . $tinymceApiKey . '/tinymce/7/tinymce.min.js' 
    : 'https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js';
@endphp

<script src="{{ $tinymceSrc }}" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    
    tinymce.init({
        selector: '#editor-bio',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(document.getElementById('tinymce-bio').value);
            });
        },
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'charmap',
            'anchor', 'searchreplace', 'visualblocks', 'code',
            'insertdatetime', 'table', 'help', 'wordcount',
            'emoticons'
        ],
        toolbar: 'undo redo | blocks | bold italic underline | ' +
                 'alignleft aligncenter alignright | ' +
                 'bullist numlist | link | removeformat',
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        entity_encoding: 'raw',
        valid_elements: '*[*]',
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('tinymce-bio').value = tinymce.get('editor-bio').getContent();
    });
});
</script>
@endsection
