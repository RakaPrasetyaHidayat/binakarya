@extends('layouts.admin')

@section('title', 'Edit Halaman')

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
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.pages.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-gray-500 hover:text-primary-600 shadow-soft border border-gray-100 dark:border-slate-800 transition-all">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Edit Halaman: {{ $page->title }}</h2>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 sm:p-8 transition-colors duration-300">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Judul Halaman <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" required placeholder="Misal: Visi dan Misi"
                           class="w-full h-12 rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-base transition-all">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Konten Halaman <span class="text-red-500">*</span></label>
                    <textarea name="content" id="tinymce-content" class="hidden">{{ old('content', $page->content) }}</textarea>
                    <div id="editor-content" class="@error('content') border-red-500 @enderror rounded-xl overflow-hidden" style="min-height: 400px;"></div>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Meta Description (Opsional)</label>
                    <textarea name="meta_description" rows="2" placeholder="Penjelasan singkat untuk SEO (max 160 karakter)"
                              class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-800">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                           class="w-5 h-5 rounded text-primary-600 focus:ring-primary-500 dark:bg-slate-800 dark:border-slate-700">
                    <label for="is_published" class="text-sm font-medium text-gray-700 dark:text-slate-300 cursor-pointer">Publikasikan Halaman (Agar bisa dilihat publik)</label>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">Batal</a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
                    <ion-icon name="cloud-upload-outline" class="text-lg"></ion-icon>
                    Simpan Perubahan
                </button>
            </div>
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
        selector: '#editor-content',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(document.getElementById('tinymce-content').value);
            });
        },
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'codesample',
            'emoticons', 'pagebreak', 'nonbreaking', 'save', 'directionality'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | link image media table codesample | ' +
                 'forecolor backcolor emoticons charmap | ' +
                 'removeformat | help',
        toolbar_mode: 'sliding',
        content_style: isDark 
            ? 'body { font-family: Inter, sans-serif; font-size: 16px; line-height: 1.6; color: #e5e7eb; background-color: #1f2937; }'
            + ' h1,h2,h3,h4,h5,h6 { color: #f3f4f6; }'
            + ' blockquote { border-left: 4px solid #7c3aed; background: rgba(124,58,237,0.1); padding: 1rem 1.5rem; margin: 1rem 0; }'
            + ' a { color: #60a5fa; }'
            + ' img { max-width: 100%; height: auto; border-radius: 0.5rem; }'
            + ' table { border-collapse: collapse; width: 100%; }'
            + ' th, td { border: 1px solid #4b5563; padding: 0.5rem; }'
            + ' th { background-color: #374151; }'
            : 'body { font-family: Inter, sans-serif; font-size: 16px; line-height: 1.6; color: #374151; }'
            + ' blockquote { border-left: 4px solid #2563eb; background: rgba(37,99,235,0.08); padding: 1rem 1.5rem; margin: 1rem 0; }'
            + ' img { max-width: 100%; height: auto; border-radius: 0.5rem; }'
            + ' table { border-collapse: collapse; width: 100%; }'
            + ' th, td { border: 1px solid #e5e7eb; padding: 0.5rem; }'
            + ' th { background-color: #f9fafb; }',
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        entity_encoding: 'raw',
        valid_elements: '*[*]',
        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
        font_family_formats: 'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva,sans-serif;',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre;',
        link_default_target: '_blank',
        link_assume_external_targets: 'https',
        resize: true,
        min_height: 400,
        max_height: 800,
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('tinymce-content').value = tinymce.get('editor-content').getContent();
    });
});
</script>
@endsection
