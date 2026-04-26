@extends('layouts.admin')

@section('title', $pageConfig['title'])

@section('styles')
<style>
.tox-tinymce{border-radius:.5rem!important}
html.dark .tox,html.dark .tox-editor-header,html.dark .tox-statusbar{background-color:rgb(31,41,55)!important;border-color:rgb(55,65,81)!important}
html.dark .tox-toolbar__primary{background-color:rgb(31,41,55)!important;background-image:none!important}
html.dark .tox-statusbar__text-container{color:rgb(156,163,175)!important}
html.dark .tox-edit-area__iframe{background-color:rgb(31,41,55)!important}
html.dark .tox-mbtn__select-label,html.dark .tox-tbtn{color:rgb(209,213,219)!important}
html.dark .tox-tbtn:hover,html.dark .tox-tbtn--enabled{background-color:rgb(55,65,81)!important}
html.dark .tox-menu,html.dark .tox-dialog,html.dark .tox-dialog__header,html.dark .tox-dialog__body,html.dark .tox-dialog__footer{background-color:rgb(31,41,55)!important;border-color:rgb(55,65,81)!important}
html.dark .tox-dialog__title{color:rgb(243,244,246)!important}
html.dark .tox-label{color:rgb(209,213,219)!important}
html.dark .tox-textfield,html.dark .tox-listboxfield .tox-listbox--select{background-color:rgb(55,65,81)!important;border-color:rgb(75,85,99)!important;color:rgb(243,244,246)!important}
html.dark .tox-button--secondary{background-color:rgb(55,65,81)!important;border-color:rgb(75,85,99)!important;color:rgb(243,244,246)!important}
</style>
@endsection

@section('content')
<div class="max-w-6xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.page-customizer.index') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium text-sm">
                    ← Kembali
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pageConfig['title'] }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kustomisasi konten dan layout halaman</p>
        </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 px-6 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
    @endif

    <form action="{{ route('admin.page-customizer.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        @foreach($pageConfig['sections'] as $section)
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-700 px-8 py-6 border-b border-gray-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        @if(isset($section['icon']))
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $section['title'] }}</h2>
                        </div>
                </div>

                <div class="p-8 space-y-6">
                    @foreach($section['inputs'] as $fieldName => $fieldConfig)
                        @php
                            $fieldValue = old($fieldName, $currentSettings[$fieldName] ?? '');
                        @endphp

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $fieldConfig['label'] }}
                                @if(strpos($fieldConfig['validation'] ?? '', 'required') !== false)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @switch($fieldConfig['type'])
                                @case('text')
                                    <input type="text"
                                           name="{{ $fieldName }}"
                                           value="{{ $fieldValue }}"
                                           class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error($fieldName) border-red-500 @enderror"
                                           placeholder="Masukkan teks">
                                    @break

                                @case('textarea')
                                    <textarea name="{{ $fieldName }}" id="pc-{{ $fieldName }}"
                                              rows="5"
                                              class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none @error($fieldName) border-red-500 @enderror"
                                              placeholder="Masukkan teks">{{ $fieldValue }}</textarea>
                                    @break

                                @case('image')
                                    <div class="space-y-3">
                                        @if($fieldValue)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $fieldValue) }}"
                                                     alt="{{ $fieldConfig['label'] }}"
                                                     class="w-full h-64 object-cover rounded-xl border border-gray-200 dark:border-slate-700">
                                                <div class="absolute top-3 right-3 bg-black/50 text-white px-3 py-1.5 rounded-lg text-xs font-medium">
                                                    Gambar Saat Ini
                                                </div>
                                        @endif
                                        <div class="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-xl p-6 text-center hover:border-primary-500 transition cursor-pointer"
                                             onclick="document.getElementById('{{ $fieldName }}').click()">
                                            <input type="file"
                                                   id="{{ $fieldName }}"
                                                   name="{{ $fieldName }}"
                                                   accept="image/*"
                                                   class="hidden"
                                                   onchange="previewImage(this, '{{ $fieldName }}-preview')">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Klik untuk upload atau drag & drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500">JPG, PNG atau WebP (Max 2MB)</p>
                                        </div>
                                        <div id="{{ $fieldName }}-preview"></div>
                                    @break

                                @case('checkbox')
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox"
                                               name="{{ $fieldName }}"
                                               value="1"
                                               {{ $fieldValue ? 'checked' : '' }}
                                               class="w-5 h-5 text-primary-600 rounded focus:ring-2 focus:ring-primary-500 dark:bg-slate-800 dark:border-slate-600">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Aktifkan fitur ini</span>
                                    </label>
                                    @break

                                @case('select')
                                    <select name="{{ $fieldName }}"
                                            class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error($fieldName) border-red-500 @enderror">
                                        <option value="">Pilih opsi</option>
                                        @if(isset($fieldConfig['options']))
                                            @foreach($fieldConfig['options'] as $val => $label)
                                                <option value="{{ $val }}" {{ $fieldValue == $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break
                            @endswitch

                            @error($fieldName)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
        @endforeach

        <div class="flex justify-between gap-4">
            <a href="{{ route('admin.page-customizer.index') }}"
               class="px-6 py-3 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-full h-64 object-cover rounded-xl border border-gray-200 dark:border-slate-700 mt-3">
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Preview gambar baru</p>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.querySelectorAll('[id$="-preview"]').forEach(preview => {
    const fileInputId = preview.id.replace('-preview', '');
    const fileInput = document.getElementById(fileInputId);
    const dropZone = fileInput.parentElement;

    dropZone.addEventListener('dragover', () => dropZone.classList.add('border-primary-500', 'bg-primary-50'));
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-primary-500', 'bg-primary-50'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary-500', 'bg-primary-50');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            previewImage(fileInput, preview.id);
        }
    });
});
</script>
@endsection

@php
$tinymceApiKey = \App\Models\Setting::get('tinymce_api_key', '');
$tinymceSrc = $tinymceApiKey 
    ? 'https://cdn.tiny.cloud/1/' . $tinymceApiKey . '/tinymce/7/tinymce.min.js' 
    : 'https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js';
@endphp

@section('scripts')
<script src="{{ $tinymceSrc }}" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    
    document.querySelectorAll('textarea[id^="pc-"]').forEach(function(textarea) {
        const editorId = textarea.id;
        
        tinymce.init({
            selector: '#' + editorId,
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline | ' +
                     'alignleft aligncenter alignright | ' +
                     'bullist numlist | link image | removeformat',
            skin: isDark ? 'oxide-dark' : 'oxide',
            content_css: isDark ? 'dark' : 'default',
            branding: false,
            promotion: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
        });
    });
});
</script>
@endsection
