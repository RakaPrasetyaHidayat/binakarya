@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')

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
    html.dark .tox-tbtn:hover { background-color: rgb(55, 65, 81) !important; }
    html.dark .tox-tbtn--enabled { background-color: rgb(55, 65, 81) !important; }
    html.dark .tox-split-button:hover { box-shadow: 0 0 0 1px rgb(55, 65, 81) inset !important; }
    html.dark .tox-collection--list .tox-collection__item--active { background-color: rgb(55, 65, 81) !important; }
    html.dark .tox-collection--list .tox-collection__item--enabled { background-color: rgb(55, 65, 81) !important; color: rgb(59, 130, 246) !important; }
    html.dark .tox-collection--toolbar .tox-collection__item--enabled { background-color: rgb(55, 65, 81) !important; }
    html.dark .tox-collection--toolbar .tox-collection__item--active { background-color: rgb(55, 65, 81) !important; }
    html.dark .tox-menu { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
    html.dark .tox-collection__item-label { color: rgb(209, 213, 219) !important; }
    html.dark .tox-collection__item-accessory { color: rgb(107, 114, 128) !important; }
    html.dark .tox-collection__item--active .tox-collection__item-label { color: rgb(255, 255, 255) !important; }
    html.dark .tox-dialog { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
    html.dark .tox-dialog__header { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
    html.dark .tox-dialog__title { color: rgb(243, 244, 246) !important; }
    html.dark .tox-dialog__body { background-color: rgb(31, 41, 55) !important; }
    html.dark .tox-dialog__footer { background-color: rgb(31, 41, 55) !important; border-color: rgb(55, 65, 81) !important; }
    html.dark .tox-label { color: rgb(209, 213, 219) !important; }
    html.dark .tox-textfield { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; color: rgb(243, 244, 246) !important; }
    html.dark .tox-textfield:focus { border-color: rgb(59, 130, 246) !important; }
    html.dark .tox-listboxfield .tox-listbox--select { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; color: rgb(243, 244, 246) !important; }
    html.dark .tox-button--naked { color: rgb(209, 213, 219) !important; }
    html.dark .tox-button--naked:hover { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; }
    html.dark .tox-button--secondary { background-color: rgb(55, 65, 81) !important; border-color: rgb(75, 85, 99) !important; color: rgb(243, 244, 246) !important; }
    html.dark .tox-button--secondary:hover { background-color: rgb(75, 85, 99) !important; }
    html.dark .tox-collection__group-heading { background-color: rgb(55, 65, 81) !important; color: rgb(156, 163, 175) !important; }
    html.dark .tox-collection__item-caret svg { fill: rgb(209, 213, 219) !important; }
    html.dark .tox-tbtn svg { fill: rgb(209, 213, 219) !important; }
    html.dark .tox-tbtn--enabled svg { fill: rgb(59, 130, 246) !important; }
    html.dark .tox-tbtn:hover svg { fill: rgb(255, 255, 255) !important; }
    html.dark .tox-swatches__picker-btn svg { fill: rgb(209, 213, 219) !important; }
</style>
@endsection

@section('content')
<div class="max-w-3xl" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.services.index') }}" :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700'" class="transition">← Kembali</a>
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan' }}</h2>
    </div>

    <form method="POST"
          action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
          :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'"
          class="rounded-xl shadow-sm border p-6 space-y-5 transition-colors">
        @csrf
        @if(isset($service)) @method('PUT') @endif

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}"
                   :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-2">Icon <span class="text-gray-500 text-xs">(Emoji atau Unicode)</span></label>
                <div class="flex gap-2 items-center">
                    <input type="text" name="icon" id="icon-input" value="{{ old('icon', $service->icon ?? '') }}"
                           placeholder="🎯"
                           :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                           class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 text-center text-2xl transition @error('icon') border-red-400 @enderror">
                    <button type="button" :class="darkMode ? 'bg-slate-700 hover:bg-gray-700 text-gray-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'" class="px-3 py-2.5 rounded-lg text-sm font-medium transition" id="icon-picker-btn" title="Pilih Icon">
                        🎨
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Gunakan emoji, Font Awesome icons, atau Ionic icons</p>
                @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $service->order ?? 0) }}" min="0"
                       :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('order') border-red-400 @enderror">
                @error('order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Ringkasan</label>
            <textarea name="excerpt" rows="2"
                      :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $service->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Konten Detail</label>
            <textarea name="body" id="tinymce-body" class="hidden">{{ old('body', $service->body ?? '') }}</textarea>
            <div id="editor-body" class="@error('body') border-red-400 @enderror rounded-lg overflow-hidden" style="min-height: 400px;"></div>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                       :class="darkMode ? 'bg-slate-700 border-slate-600' : ''"
                       class="w-4 h-4 text-blue-600 rounded">
                <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-medium">Aktif</span>
            </label>
        </div>

        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="flex gap-3 pt-2 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                {{ isset($service) ? 'Simpan Perubahan' : 'Tambah Layanan' }}
            </button>
            <a href="{{ route('admin.services.index') }}" 
               :class="darkMode ? 'border-slate-600 text-gray-300 hover:bg-slate-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
               class="border px-6 py-2.5 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Icon Picker Modal -->
<div id="icon-picker-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl max-h-96 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pilih Icon</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" id="close-picker">✕</button>
        </div>
        
        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-2">Cari (emoji, Font Awesome, Ionic Icons):</label>
            <input type="text" id="icon-search" placeholder="Cari icon..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div id="icon-picker-content" class="overflow-y-auto flex-1 grid grid-cols-6 gap-2 p-4 bg-gray-50 rounded-lg">
            <!-- Icons akan dimuat di sini -->
        </div>

        <div class="mt-4 text-xs text-gray-500">
            <p><strong>Tips:</strong> Anda bisa copy-paste emoji dari <a href="https://ionic.io/ionicons" target="_blank" class="text-blue-600 hover:underline">Ionic Icons</a> atau situs emoji lainnya</p>
        </div>
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

    // TinyMCE init
    tinymce.init({
        selector: '#editor-body',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(document.getElementById('tinymce-body').value);
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
        table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
        table_default_attributes: { 'border': '1' },
        link_default_target: '_blank',
        link_assume_external_targets: 'https',
        resize: true,
        min_height: 400,
        max_height: 800,
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('tinymce-body').value = tinymce.get('editor-body').getContent();
    });

    // Icon picker
    const iconInput = document.getElementById('icon-input');
    const pickerBtn = document.getElementById('icon-picker-btn');
    const pickerModal = document.getElementById('icon-picker-modal');
    const closeBtn = document.getElementById('close-picker');
    const pickerContent = document.getElementById('icon-picker-content');
    const searchInput = document.getElementById('icon-search');

    const ionicIconsList = [
        'academic-cap', 'accessibility', 'add', 'airplane', 'alarm', 'albums', 'alert-circle', 'analytics',
        'archive', 'at', 'award', 'balloon', 'bar-chart', 'barbell', 'basket', 'beaker', 'bed', 'beer',
        'bicycle', 'bluetooth', 'boat', 'book', 'bookmark', 'briefcase', 'brush', 'build', 'bulb', 'bus',
        'business', 'cafe', 'calculator', 'calendar', 'call', 'camera', 'car', 'card', 'cart', 'cash',
        'chatbubble', 'checkbox', 'checkmark-circle', 'chevron-forward', 'cloud-upload', 'code-working',
        'cog', 'color-palette', 'compass', 'construct', 'contract', 'cube', 'cut', 'desktop', 'diamond',
        'document-text', 'earth', 'easel', 'education', 'egg', 'extension-puzzle', 'eye', 'file-tray-full',
        'film', 'filter', 'finger-print', 'fitness', 'flag', 'flask', 'folder', 'football', 'funnel',
        'game-controller', 'gift', 'git-branch', 'git-commit', 'git-compare', 'git-merge', 'git-pull-request',
        'glasses', 'globe', 'grid', 'hammer', 'hand-left', 'happy', 'headset', 'heart', 'help-circle',
        'home', 'hourglass', 'ice-cream', 'image', 'infinite', 'information-circle', 'journal', 'key',
        'keypad', 'language', 'laptop', 'layers', 'leaf', 'library', 'list', 'locate', 'location',
        'lock-closed', 'log-in', 'magnet', 'mail', 'male', 'man', 'map', 'medal', 'medical', 'megaphone',
        'menu', 'mic', 'moon', 'musical-notes', 'newspaper', 'notifications', 'nutrition', 'options',
        'paper-plane', 'partly-sunny', 'pause', 'paw', 'pencil', 'people', 'person', 'phone-portrait',
        'pie-chart', 'pin', 'pint', 'pizza', 'planet', 'play', 'podium', 'power', 'pricetag', 'print',
        'pulse', 'push', 'qr-code', 'radio', 'rainy', 'reader', 'receipt', 'recording', 'refresh',
        'remove', 'reorder-three', 'repeat', 'resize', 'restaurant', 'rocket', 'rose', 'save',
        'scan', 'school', 'search', 'send', 'settings', 'shapes', 'share', 'shield-checkmark',
        'shirt', 'shuffle', 'skull', 'snow', 'speedometer', 'star', 'stats-chart', 'stopwatch',
        'sunny', 'swimmer', 'tablet-landscape', 'tag', 'telescope', 'terminal', 'thermometer',
        'thumbs-up', 'thunderstorm', 'ticket', 'time', 'timer', 'today', 'trash', 'trophy',
        'tv', 'umbrella', 'videocam', 'wallet', 'watch', 'water', 'wifi', 'wine', 'woman'
    ];

    function loadIconPicker() {
        const query = searchInput.value.toLowerCase();
        const filtered = ionicIconsList.filter(icon => icon.includes(query));
        
        pickerContent.innerHTML = filtered.map(icon => `
            <button type="button" class="p-3 hover:bg-blue-50 rounded-lg flex flex-col items-center gap-2 border border-transparent hover:border-blue-100 transition" data-icon="${icon}">
                <ion-icon name="${icon}" class="text-3xl text-gray-700"></ion-icon>
                <span class="text-[10px] text-gray-400 truncate w-full text-center">${icon}</span>
            </button>
        `).join('');

        pickerContent.querySelectorAll('[data-icon]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                iconInput.value = this.dataset.icon;
                pickerModal.classList.add('hidden');
                iconInput.focus();
            });
        });
    }

    pickerBtn.addEventListener('click', function(e) {
        e.preventDefault();
        pickerModal.classList.remove('hidden');
        searchInput.focus();
        loadIconPicker();
    });

    closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        pickerModal.classList.add('hidden');
    });

    searchInput.addEventListener('input', function() {
        loadIconPicker();
    });

    pickerModal.addEventListener('click', function(e) {
        if (e.target === this) {
            pickerModal.classList.add('hidden');
        }
    });
});
</script>

<style>
#icon-picker-modal { animation: fadeIn 0.2s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
#icon-picker-content button:hover { transform: scale(1.2); }
</style>
@endsection
