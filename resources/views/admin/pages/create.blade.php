@extends('layouts.admin')

@section('title', 'Buat Halaman Baru')

@section('styles')
@include('admin.partials.tinymce-styles')
@endsection

@section('content')
<script nonce="{{ $cspNonce ?? '' }}">
function pageBuilderForm(initialMode, initialBlocksRaw) {
    let parsedBlocks = [];
    try {
        parsedBlocks = typeof initialBlocksRaw === 'string' ? JSON.parse(initialBlocksRaw || '[]') : (initialBlocksRaw || []);
    } catch (_) {
        parsedBlocks = [];
    }
    if (!Array.isArray(parsedBlocks)) {
        parsedBlocks = [];
    }

    return {
        contentMode: initialMode === 'builder' ? 'builder' : 'classic',
        availableTypes: ['hero', 'text', 'image', 'cta', 'html'],
        blocks: parsedBlocks.map((block) => ({
            type: block && block.type ? block.type : 'text',
            data: block && block.data ? block.data : {},
        })),
        addBlock(type) {
            const defaults = {
                hero: { title: '', subtitle: '', background_image: '' },
                text: { content: '' },
                image: { url: '', alt: '', caption: '' },
                cta: { title: '', description: '', button_label: '', button_url: '' },
                html: { html: '' },
            };
            this.blocks.push({ type, data: defaults[type] || {} });
        },
        removeBlock(index) {
            this.blocks.splice(index, 1);
        },
        moveBlock(index, offset) {
            const target = index + offset;
            if (target < 0 || target >= this.blocks.length) return;
            const current = this.blocks[index];
            this.blocks[index] = this.blocks[target];
            this.blocks[target] = current;
        },
        beforeSubmit() {
            if (this.$refs.contentBlocksInput) {
                this.$refs.contentBlocksInput.value = JSON.stringify(this.blocks);
            }
            if (window.tinymce && tinymce.get('tinymce-content')) {
                document.getElementById('tinymce-content').value = tinymce.get('tinymce-content').getContent();
            }
        }
    };
}
</script>

<div class="max-w-5xl mx-auto" x-data="pageBuilderForm(@js(old('content_mode', 'classic')), @js(old('content_blocks', '[]')))">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.pages.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-gray-500 hover:text-primary-600 shadow-soft border border-gray-100 dark:border-slate-800 transition-all">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Buat Halaman Baru</h2>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" class="space-y-6" @submit="beforeSubmit">
        @csrf
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 sm:p-8 transition-colors duration-300 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Judul Halaman <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Misal: Visi dan Misi"
                       class="w-full h-12 rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-base transition-all">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5">URL otomatis: <span class="font-mono">/p/<span x-text="(($el.closest('div').querySelector('input[name=title]')?.value || '').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'')) || 'slug-halaman'"></span></span></p>
            </div>

            <div class="p-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/30">
                <p class="text-xs uppercase tracking-widest font-bold text-gray-500 dark:text-slate-400 mb-3">Mode Editor</p>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                    <button type="button" @click="contentMode='classic'" :class="contentMode==='classic' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300'" class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-700">Classic</button>
                    <button type="button" @click="contentMode='builder'" :class="contentMode==='builder' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300'" class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-700">Builder</button>
                </div>
                <input type="hidden" name="content_mode" :value="contentMode">
            </div>

            <div x-show="contentMode === 'classic'" x-cloak>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Konten Halaman <span class="text-red-500">*</span></label>
                <textarea name="content" id="tinymce-content" class="@error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-show="contentMode === 'builder'" x-cloak class="space-y-4">
                <div class="space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300">Block Builder <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                        <template x-for="type in availableTypes" :key="type">
                            <button type="button" @click="addBlock(type)" class="text-xs px-2 py-1 rounded bg-primary-600 text-white font-semibold">
                                + <span x-text="type"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="content_blocks" x-ref="contentBlocksInput">
                @error('content_blocks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                <template x-if="blocks.length === 0">
                    <div class="text-sm text-gray-500 dark:text-slate-400 border border-dashed border-gray-300 dark:border-slate-700 rounded-xl p-4">
                        Belum ada block. Klik tombol tipe block untuk mulai.
                    </div>
                </template>

                <template x-for="(block, index) in blocks" :key="index">
                    <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 space-y-3 bg-white dark:bg-slate-800/40">
                        <div class="space-y-2 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
                            <p class="font-bold text-sm text-gray-700 dark:text-slate-200 uppercase" x-text="'#' + (index + 1) + ' - ' + block.type"></p>
                            <div class="grid grid-cols-3 sm:flex gap-2">
                                <button type="button" @click="moveBlock(index, -1)" class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-slate-600">↑</button>
                                <button type="button" @click="moveBlock(index, 1)" class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-slate-600">↓</button>
                                <button type="button" @click="removeBlock(index)" class="text-xs px-2 py-1 rounded bg-red-600 text-white">Hapus</button>
                            </div>
                        </div>

                        <div x-show="block.type==='hero'" class="grid sm:grid-cols-2 gap-3">
                            <input x-model="block.data.title" type="text" placeholder="Judul hero" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.subtitle" type="text" placeholder="Subjudul hero" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.background_image" type="url" placeholder="URL background image" class="sm:col-span-2 rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                        </div>

                        <div x-show="block.type==='text'" class="space-y-2">
                            <textarea x-model="block.data.content" rows="4" placeholder="Konten HTML/Text block" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm"></textarea>
                        </div>

                        <div x-show="block.type==='image'" class="grid sm:grid-cols-2 gap-3">
                            <input x-model="block.data.url" type="url" placeholder="URL gambar" class="sm:col-span-2 rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.alt" type="text" placeholder="Alt text" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.caption" type="text" placeholder="Caption" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                        </div>

                        <div x-show="block.type==='cta'" class="grid sm:grid-cols-2 gap-3">
                            <input x-model="block.data.title" type="text" placeholder="Judul CTA" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.button_label" type="text" placeholder="Label tombol" class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <input x-model="block.data.button_url" type="url" placeholder="URL tombol" class="sm:col-span-2 rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                            <textarea x-model="block.data.description" rows="2" placeholder="Deskripsi CTA" class="sm:col-span-2 rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm"></textarea>
                        </div>

                        <div x-show="block.type==='html'" class="space-y-2">
                            <textarea x-model="block.data.html" rows="5" placeholder="Custom HTML block" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm font-mono"></textarea>
                        </div>
                    </div>
                </template>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Custom CSS</label>
                        <textarea name="custom_css" rows="6" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm font-mono" placeholder="/* custom css */">{{ old('custom_css') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Custom JS</label>
                        @can('useCustomJs', \App\Models\Page::class)
                        <textarea name="custom_js" rows="6" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm font-mono" placeholder="// custom js">{{ old('custom_js') }}</textarea>
                        @else
                        <textarea rows="6" disabled class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-400 text-sm font-mono" placeholder="// custom js"></textarea>
                        <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">Custom JS hanya tersedia untuk role super admin.</p>
                        @endcan
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Meta Description (Opsional)</label>
                <textarea name="meta_description" rows="2" placeholder="Penjelasan singkat untuk SEO (max 160 karakter)"
                          class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all">{{ old('meta_description') }}</textarea>
                @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5">Disarankan 120-160 karakter agar snippet SEO optimal.</p>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-800">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded text-primary-600 focus:ring-primary-500 dark:bg-slate-800 dark:border-slate-700">
                <label for="is_published" class="text-sm font-medium text-gray-700 dark:text-slate-300 cursor-pointer">Publikasikan Halaman (Agar bisa dilihat publik)</label>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">Batal</a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
                    <ion-icon name="cloud-upload-outline" class="text-lg"></ion-icon>
                    Simpan Halaman
                </button>
            </div>
        </div>
    </form>
</div>

@include('admin.partials.tinymce-init', [
    'editors' => [
        ['selector' => '#tinymce-content', 'height' => 500, 'toolbar' => 'full'],
    ]
])
@endsection
