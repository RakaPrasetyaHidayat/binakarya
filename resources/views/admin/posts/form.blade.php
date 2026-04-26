@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Artikel' : 'Tambah Artikel')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .tox-tinymce {
        border-radius: 0.5rem !important;
    }
    html.dark .tox {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-editor-header {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-toolbar__primary {
        background-color: rgb(31, 41, 55) !important;
        background-image: none !important;
    }
    html.dark .tox-statusbar {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-statusbar__text-container {
        color: rgb(156, 163, 175) !important;
    }
    html.dark .tox-edit-area__iframe {
        background-color: rgb(31, 41, 55) !important;
    }
    html.dark .tox-mbtn__select-label,
    html.dark .tox-tbtn {
        color: rgb(209, 213, 219) !important;
    }
    html.dark .tox-tbtn:hover {
        background-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-tbtn--enabled {
        background-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-split-button:hover {
        box-shadow: 0 0 0 1px rgb(55, 65, 81) inset !important;
    }
    html.dark .tox-collection--list .tox-collection__item--active {
        background-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-collection--list .tox-collection__item--enabled {
        background-color: rgb(55, 65, 81) !important;
        color: rgb(59, 130, 246) !important;
    }
    html.dark .tox-collection--toolbar .tox-collection__item--enabled {
        background-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-collection--toolbar .tox-collection__item--active {
        background-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-menu {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-collection__item-label {
        color: rgb(209, 213, 219) !important;
    }
    html.dark .tox-collection__item-accessory {
        color: rgb(107, 114, 128) !important;
    }
    html.dark .tox-collection__item--active .tox-collection__item-label {
        color: rgb(255, 255, 255) !important;
    }
    html.dark .tox-dialog {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-dialog__header {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-dialog__title {
        color: rgb(243, 244, 246) !important;
    }
    html.dark .tox-dialog__body {
        background-color: rgb(31, 41, 55) !important;
    }
    html.dark .tox-dialog__footer {
        background-color: rgb(31, 41, 55) !important;
        border-color: rgb(55, 65, 81) !important;
    }
    html.dark .tox-label {
        color: rgb(209, 213, 219) !important;
    }
    html.dark .tox-textfield {
        background-color: rgb(55, 65, 81) !important;
        border-color: rgb(75, 85, 99) !important;
        color: rgb(243, 244, 246) !important;
    }
    html.dark .tox-textfield:focus {
        border-color: rgb(59, 130, 246) !important;
    }
    html.dark .tox-listboxfield .tox-listbox--select {
        background-color: rgb(55, 65, 81) !important;
        border-color: rgb(75, 85, 99) !important;
        color: rgb(243, 244, 246) !important;
    }
    html.dark .tox-button--naked {
        color: rgb(209, 213, 219) !important;
    }
    html.dark .tox-button--naked:hover {
        background-color: rgb(55, 65, 81) !important;
        border-color: rgb(75, 85, 99) !important;
    }
    html.dark .tox-button--secondary {
        background-color: rgb(55, 65, 81) !important;
        border-color: rgb(75, 85, 99) !important;
        color: rgb(243, 244, 246) !important;
    }
    html.dark .tox-button--secondary:hover {
        background-color: rgb(75, 85, 99) !important;
    }
    html.dark .tox-collection__group-heading {
        background-color: rgb(55, 65, 81) !important;
        color: rgb(156, 163, 175) !important;
    }
    html.dark .tox-collection__item-caret svg {
        fill: rgb(209, 213, 219) !important;
    }
    html.dark .tox-tbtn svg {
        fill: rgb(209, 213, 219) !important;
    }
    html.dark .tox-tbtn--enabled svg {
        fill: rgb(59, 130, 246) !important;
    }
    html.dark .tox-tbtn:hover svg {
        fill: rgb(255, 255, 255) !important;
    }
    html.dark .tox-swatches__picker-btn svg {
        fill: rgb(209, 213, 219) !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.posts.index') }}" :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700'" class="transition">← Kembali</a>
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">{{ isset($post) ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h2>
    </div>

    @if(auth()->user()->isContributor())
    <div :class="darkMode ? 'bg-blue-900/20 border-blue-800 text-blue-400' : 'bg-blue-50 border-blue-200 text-blue-800'" class="mb-6 p-4 rounded-lg text-sm border transition-colors">
        <strong>📝 Catatan untuk Kontributor:</strong> Artikel Anda akan masuk ke status draft. Admin akan meninjaunya sebelum dipublikasikan.
    </div>
    @endif

    <form method="POST"
          action="{{ isset($post) ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          enctype="multipart/form-data"
          :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'"
          class="rounded-xl shadow-sm border p-6 space-y-5 transition-colors">
        @csrf
        @if(isset($post)) @method('PUT') @endif

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}"
                   :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div x-data="{ 
                addingCategory: false, 
                newCategoryName: '', 
                localCategories: @js($categories),
                async submitQuickCategory() {
                    if(!this.newCategoryName.trim()) return;
                    try {
                        const response = await fetch('{{ route('admin.categories.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ name: this.newCategoryName, type: 'blog' })
                        });
                        const result = await response.json();
                        if(result.success) {
                            this.localCategories.push(result.category);
                            this.$nextTick(() => {
                                const select = this.$refs.categorySelect;
                                select.value = result.category.id;
                            });
                            this.newCategoryName = '';
                            this.addingCategory = false;
                        }
                    } catch(e) {
                        alert('Gagal menambah kategori');
                    }
                }
            }">
                <div class="flex items-center justify-between mb-1.5">
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium">Kategori</label>
                    <button type="button" @click="addingCategory = !addingCategory" class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        + Tambah Baru
                    </button>
                </div>

                <div x-show="addingCategory" x-transition class="mb-3 flex items-center gap-2">
                    <input type="text" x-model="newCategoryName" placeholder="Kategori blog baru..." 
                           class="flex-1 text-xs border rounded-lg px-3 py-2 bg-transparent border-blue-300 dark:border-blue-500/50" 
                           @keydown.enter.prevent="submitQuickCategory()">
                    <button type="button" @click="submitQuickCategory()" class="bg-blue-600 text-white text-[10px] font-bold px-3 py-2 rounded-lg">Simpan</button>
                </div>

                <select name="category_id" x-ref="categorySelect" 
                        :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('category_id') border-red-400 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <template x-for="cat in localCategories" :key="cat.id">
                        <option :value="cat.id" :selected="cat.id == {{ old('category_id', $post->category_id ?? '0') }}" x-text="cat.name"></option>
                    </template>
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Gambar Utama</label>
                @if(isset($post) && $post->featured_image_url)
                    <img src="{{ $post->featured_image_url }}" alt="" class="w-24 h-16 object-cover rounded mb-2">
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       :class="darkMode ? 'file:bg-blue-900 file:text-blue-300 hover:file:bg-blue-800' : 'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium transition">
                @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Gambar Detail Artikel</label>
                @if(isset($post) && $post->detail_image_url)
                    <img src="{{ $post->detail_image_url }}" alt="" class="w-24 h-16 object-cover rounded mb-2">
                @endif
                <input type="file" name="detail_image" accept="image/*"
                       :class="darkMode ? 'file:bg-blue-900 file:text-blue-300 hover:file:bg-blue-800' : 'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium transition">
                @error('detail_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Ringkasan <span class="text-gray-500 text-xs">(max 500 karakter)</span></label>
            <textarea name="excerpt" rows="2" maxlength="500"
                      :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Konten <span class="text-red-500">*</span> <span class="text-gray-500 text-xs">(min 50 karakter)</span></label>
            <textarea name="body" id="tinymce-editor" class="hidden">{{ old('body', $post->body ?? '') }}</textarea>
            <div id="editor-container" class="@error('body') border-red-400 @enderror rounded-lg overflow-hidden" style="min-height: 400px;"></div>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="border-t pt-5">
            <h3 :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-semibold mb-4">SEO</h3>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Meta Title <span class="text-gray-500 text-xs">(60 karakter optimal)</span></label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" maxlength="60"
                           :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('meta_title') border-red-400 @enderror">
                    @error('meta_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Meta Description <span class="text-gray-500 text-xs">(160 karakter optimal)</span></label>
                    <textarea name="meta_description" rows="2" maxlength="160"
                              :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                              class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="border-t pt-5">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
                           :class="darkMode ? 'bg-slate-700 border-slate-600' : ''"
                           class="w-4 h-4 text-blue-600 rounded">
                    <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-medium">Publikasikan Sekarang</span>
                </label>
                <span class="text-xs text-gray-500">(Hanya admin yang bisa publikasi)</span>
            </div>
        </div>
        @endif

        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="flex gap-3 pt-2 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                {{ isset($post) ? 'Simpan Perubahan' : 'Tambah Artikel' }}
            </button>
            <a href="{{ route('admin.posts.index') }}" 
               :class="darkMode ? 'border-slate-600 text-gray-300 hover:bg-slate-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
               class="border px-6 py-2.5 rounded-lg text-sm transition">
                Batal
            </a>
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
        selector: '#editor-container',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(document.getElementById('tinymce-editor').value);
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
        images_upload_url: false,
        automatic_uploads: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        entity_encoding: 'raw',
        valid_elements: '*[*]',
        extended_valid_elements: 'script[src|async|defer|type|charset],style[type],link[rel|href|type]',
        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
        font_family_formats: 'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva,sans-serif;',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre;',
        codesample_languages: [
            { text: 'HTML/XML', value: 'markup' },
            { text: 'JavaScript', value: 'javascript' },
            { text: 'CSS', value: 'css' },
            { text: 'PHP', value: 'php' },
            { text: 'Python', value: 'python' },
            { text: 'Java', value: 'java' },
            { text: 'C', value: 'c' },
            { text: 'C++', value: 'cpp' },
            { text: 'SQL', value: 'sql' },
            { text: 'Bash', value: 'bash' }
        ],
        table_default_styles: {
            'border-collapse': 'collapse',
            'width': '100%'
        },
        table_default_attributes: {
            'border': '1'
        },
        link_default_target: '_blank',
        link_assume_external_targets: 'https',
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: 'quickimage quicktable | hr pagebreak',
        contextmenu: 'link image table codesample | cell row column deletetable',
        paste_data_images: false,
        paste_as_text: false,
        paste_merge_formats: true,
        smart_paste: true,
        browser_spellcheck: true,
        resize: true,
        min_height: 400,
        max_height: 800,
    });

    // Sync TinyMCE content to textarea on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const content = tinymce.get('editor-container').getContent();
        document.getElementById('tinymce-editor').value = content;
    });
});
</script>
@endsection

