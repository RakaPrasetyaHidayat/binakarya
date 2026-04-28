@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Artikel' : 'Tambah Artikel')

@section('styles')
@include('admin.partials.tinymce-styles')
@endsection

@section('content')
<div class="max-w-5xl" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.posts.index') }}"
           :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700'"
           class="transition text-sm font-medium">← Kembali</a>
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">
            {{ isset($post) ? 'Edit Artikel' : 'Tambah Artikel Baru' }}
        </h2>
    </div>

    @if(auth()->user()->isContributor())
    <div :class="darkMode ? 'bg-blue-900/20 border-blue-800 text-blue-400' : 'bg-blue-50 border-blue-200 text-blue-800'"
         class="mb-6 p-4 rounded-lg text-sm border transition-colors">
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

        {{-- Judul --}}
        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">
                Judul <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}"
                   :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kategori + Gambar Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Kategori --}}
            <div x-data="{
                addingCategory: false,
                newCategoryName: '',
                localCategories: @js($categories),
                async submitQuickCategory() {
                    if (!this.newCategoryName.trim()) return;
                    try {
                        const r = await fetch('{{ route('admin.categories.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({ name: this.newCategoryName, type: 'blog' })
                        });
                        const j = await r.json();
                        if (j.success) {
                            this.localCategories.push(j.category);
                            this.$nextTick(() => { this.$refs.categorySelect.value = j.category.id; });
                            this.newCategoryName = '';
                            this.addingCategory = false;
                        }
                    } catch(e) { alert('Gagal menambah kategori'); }
                }
            }">
                <div class="flex items-center justify-between mb-1.5">
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium">Kategori</label>
                    <button type="button" @click="addingCategory = !addingCategory"
                            class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 hover:underline">+ Tambah Baru</button>
                </div>
                <div x-show="addingCategory" x-transition class="mb-3 flex items-center gap-2">
                    <input type="text" x-model="newCategoryName" placeholder="Kategori blog baru..."
                           class="flex-1 text-xs border rounded-lg px-3 py-2 bg-transparent border-blue-300 dark:border-blue-500/50"
                           @keydown.enter.prevent="submitQuickCategory()">
                    <button type="button" @click="submitQuickCategory()"
                            class="bg-blue-600 text-white text-[10px] font-bold px-3 py-2 rounded-lg">Simpan</button>
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

            {{-- Gambar Utama --}}
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
        </div>

        {{-- Ringkasan --}}
        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">
                Ringkasan <span class="text-gray-500 text-xs">(max 500 karakter)</span>
            </label>
            <textarea name="excerpt" rows="2" maxlength="500"
                      :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Konten (TinyMCE) --}}
        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">
                Konten <span class="text-red-500">*</span>
                <span class="text-gray-500 text-xs font-normal">(min 50 karakter — mendukung teks biasa & HTML)</span>
            </label>
            <textarea name="body" id="body-editor"
                      class="@error('body') border-red-400 @enderror">{{ old('body', $post->body ?? '') }}</textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- SEO --}}
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="border-t pt-5">
            <h3 :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-semibold mb-4">SEO</h3>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">
                        Meta Title <span class="text-gray-500 text-xs">(60 karakter optimal)</span>
                    </label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" maxlength="60"
                           :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('meta_title') border-red-400 @enderror">
                    @error('meta_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">
                        Meta Description <span class="text-gray-500 text-xs">(160 karakter optimal)</span>
                    </label>
                    <textarea name="meta_description" rows="2" maxlength="160"
                              :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                              class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Publikasi (admin only) --}}
        @if(auth()->user()->isAdmin())
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="border-t pt-5">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
                       :class="darkMode ? 'bg-slate-700 border-slate-600' : ''"
                       class="w-4 h-4 text-blue-600 rounded">
                <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-medium">Publikasikan Sekarang</span>
                <span class="text-xs text-gray-500">(Hanya admin)</span>
            </label>
        </div>
        @endif

        {{-- Tombol --}}
        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="flex gap-3 pt-2 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                {{ isset($post) ? 'Simpan Perubahan' : 'Tambah Artikel' }}
            </button>
            <a href="{{ route('admin.posts.index') }}"
               :class="darkMode ? 'border-slate-600 text-gray-300 hover:bg-slate-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
               class="border px-6 py-2.5 rounded-lg text-sm transition">Batal</a>
        </div>
    </form>
</div>

@include('admin.partials.tinymce-init', [
    'editors' => [
        ['selector' => '#body-editor', 'height' => 550, 'toolbar' => 'full'],
    ]
])
@endsection
