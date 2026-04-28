@extends('layouts.admin')

@section('title', isset($book) ? 'Edit Buku' : 'Tambah Buku')

@section('styles')
@include('admin.partials.tinymce-styles')
@endsection

@section('content')
<div class="max-w-3xl" x-data="{darkMode:localStorage.getItem('darkMode')==='true'}">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.books.index') }}" :class="darkMode?'text-gray-400 hover:text-gray-300':'text-gray-600 hover:text-gray-900'" class="text-sm font-medium transition">← Kembali</a>
        <h2 :class="darkMode?'text-gray-100':'text-gray-900'" class="text-xl font-bold">{{ isset($book)?'Edit Buku':'Tambah Buku Baru' }}</h2>
    </div>

    <form method="POST" action="{{ isset($book)?route('admin.books.update',$book):route('admin.books.store') }}" enctype="multipart/form-data" :class="darkMode?'bg-slate-800 border-slate-700':'bg-white border-gray-200'" class="rounded-xl shadow-sm border p-4 sm:p-6 space-y-6 transition-colors">
        @csrf @if(isset($book)) @method('PUT') @endif

        <div class="space-y-4">
            <h3 :class="darkMode?'text-gray-300':'text-gray-900'" class="text-sm font-bold uppercase tracking-wide">Informasi Dasar</h3>
            <div>
                <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title',$book->title??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all @error('title') border-red-400 @enderror" placeholder="Masukkan judul buku">
                @error('title')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author',$book->author??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all @error('author') border-red-400 @enderror" placeholder="Nama penulis">
                    @error('author')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div x-data="{addingCategory:false,newCategoryName:'',localCategories:@js($categories),async submitQuickCategory(){if(!this.newCategoryName.trim())return;try{const r=await fetch('{{ route('admin.categories.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({name:this.newCategoryName,type:'book'})});const j=await r.json();if(j.success){this.localCategories.push(j.category);this.$nextTick(()=>{this.$refs.categorySelect.value=j.category.id});this.newCategoryName='';this.addingCategory=false;}}catch(e){alert('Gagal menambah kategori');}}}">
                    <div class="flex items-center justify-between mb-2">
                        <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium">Kategori</label>
                        <button type="button" @click="addingCategory=!addingCategory" class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 hover:underline">+ Tambah Baru</button>
                    </div>
                    <div x-show="addingCategory" x-transition class="mb-3 flex items-center gap-2">
                        <input type="text" x-model="newCategoryName" placeholder="Nama kategori baru..." class="flex-1 text-xs border rounded-lg px-3 py-2 bg-transparent border-blue-300 dark:border-blue-500/50" @keydown.enter.prevent="submitQuickCategory()">
                        <button type="button" @click="submitQuickCategory()" class="bg-blue-600 text-white text-[10px] font-bold px-3 py-2 rounded-lg">Simpan</button>
                    </div>
                    <select name="category_id" x-ref="categorySelect" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        <template x-for="cat in localCategories" :key="cat.id">
                            <option :value="cat.id" :selected="cat.id=={{ old('category_id',$book->category_id??'0') }}" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>
        </div>

        <div :class="darkMode?'border-slate-700':''" class="border-t pt-6">
            <h3 :class="darkMode?'text-gray-300':'text-gray-900'" class="text-sm font-bold uppercase tracking-wide mb-4">Metadata & Detail</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">ISBN</label><input type="text" name="isbn" value="{{ old('isbn',$book->isbn??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all" placeholder="978-602-xxxx-x"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">DOI</label><input type="text" name="doi" value="{{ old('doi',$book->doi??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" placeholder="10.xxxx/xxxxx" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Tahun Terbit</label><input type="number" name="published_year" value="{{ old('published_year',$book->published_year??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" min="1900" max="{{ date('Y') }}" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Harga (Rp)</label><input type="number" name="price" value="{{ old('price',$book->price??'') }}" min="0" step="1000" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
            <div class="mt-4">
                <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Abstrak</label>
                <textarea name="abstract" id="abstract-editor" class="w-full @error('abstract') border-red-400 @enderror">{{ old('abstract', $book->abstract ?? '') }}</textarea>
                @error('abstract')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
            <div class="mt-4">
                <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Kata Kunci</label>
                <input type="text" name="keywords" value="{{ old('keywords',$book->keywords??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all" placeholder="Contoh: pendidikan, teknologi (pisahkan dengan koma)">
                <p class="text-xs text-gray-500 mt-1.5">Digunakan untuk optimasi Google Scholar dan pencarian.</p>
            </div>

        <div :class="darkMode?'border-slate-700':''" class="border-t pt-6">
            <h3 :class="darkMode?'text-gray-300':'text-gray-900'" class="text-sm font-bold uppercase tracking-wide mb-4">File & Media</h3>
            <div class="space-y-6">
                <div>
                    <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-3">Cover Buku</label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <div class="w-32 aspect-[3/4] rounded-xl overflow-hidden border-2 border-dashed transition-colors flex items-center justify-center p-1" :class="darkMode?'border-slate-700 bg-slate-800':'border-gray-200 bg-gray-50'">
                            @if(isset($book)&&$book->cover_url)<img src="{{ $book->cover_url }}" alt="" class="w-full h-full object-cover rounded-lg shadow-sm">@else<div class="flex flex-col items-center text-center px-2"><ion-icon name="image-outline" class="text-2xl text-gray-400 mb-1"></ion-icon><span class="text-[8px] text-gray-500 uppercase font-bold tracking-tighter">Belum Ada Cover</span></div>@endif
                        </div>
                        <div class="flex-1 w-full space-y-3">
                            <input type="file" name="cover_image" accept="image/*" :class="darkMode?'file:bg-blue-900 file:text-blue-300 hover:file:bg-blue-800':'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'" class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold transition-all cursor-pointer">
                            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-3 rounded-lg border border-blue-100/50 dark:border-blue-900/20"><p class="text-[10px] text-blue-700 dark:text-blue-400 leading-relaxed font-medium"><strong>Panduan:</strong> Gunakan rasio potret (3:4) untuk hasil terbaik.</p></div>
                    </div>
                    @error('cover_image')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">File Utama PDF (Full Version)</label>
                    @if(isset($book)&&$book->pdf_file)<div class="mb-2 flex items-center gap-2"><p class="text-xs text-green-600 dark:text-green-400 font-medium">✓ File PDF tersedia</p><a href="{{ $book->pdf_url }}" target="_blank" class="text-[10px] text-blue-600 dark:text-blue-400 hover:underline">Lihat File</a></div>@endif
                    <input type="file" name="pdf_file" accept=".pdf" :class="darkMode?'file:bg-blue-900 file:text-blue-300 hover:file:bg-blue-800':'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'" class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium transition-all cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1.5">File utama PDF buku (Maks 50MB).</p>
                    @error('pdf_file')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
                    <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">File Pratinjau / Preview (PDF atau Gambar)</label>
                    @if(isset($book)&&$book->preview_file)<div class="mb-3 flex items-start gap-3"><p class="text-xs text-green-600 dark:text-green-400 font-medium">✓ File preview tersedia</p><a href="{{ $book->preview_url }}" target="_blank" class="text-[10px] text-blue-600 dark:text-blue-400 hover:underline">Lihat File</a></div>@endif
                    <input type="file" name="preview_file" accept=".pdf,.png,.jpg,.jpeg" :class="darkMode?'file:bg-blue-900 file:text-blue-300 hover:file:bg-blue-800':'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'" class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium transition-all cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1.5">Versi cuplikan dalam format PDF atau gambar.</p>
                    @error('preview_file')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">URL Preview Eksternal <span class="text-xs font-normal text-gray-500 ml-1">(Google Drive, Scribd, dll.)</span></label>
                    <input type="url" name="preview_url" value="{{ old('preview_url',isset($book)?($book->getRawOriginal('preview_url')??''):'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all" placeholder="https://drive.google.com/file/d/.../preview">
                    <p class="text-xs text-gray-500 mt-1.5">Prioritas: File upload > URL eksternal.</p>
                </div>
        </div>

        <div :class="darkMode?'border-slate-700':''" class="border-t pt-6">
            <h3 :class="darkMode?'text-gray-300':'text-gray-900'" class="text-sm font-bold uppercase tracking-wide mb-4">Tombol Pembelian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Nomor WhatsApp</label><input type="text" name="wa_number" value="{{ old('wa_number',$book->wa_number??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" placeholder="628xxxxxxxxxx" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">URL Shopee</label><input type="url" name="shopee_url" value="{{ old('shopee_url',$book->shopee_url??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">URL Tokopedia</label><input type="url" name="tokopedia_url" value="{{ old('tokopedia_url',$book->tokopedia_url??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Custom URL</label><input type="url" name="custom_url" value="{{ old('custom_url',$book->custom_url??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
                <div><label :class="darkMode?'text-gray-300':'text-gray-700'" class="block text-sm font-medium mb-2">Label Custom URL</label><input type="text" name="custom_url_label" value="{{ old('custom_url_label',$book->custom_url_label??'') }}" :class="darkMode?'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600':'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'" placeholder="Beli di Lazada" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"></div>
        </div>

        <div :class="darkMode?'border-slate-700 hover:bg-slate-700':'hover:bg-gray-50'" class="border-t pt-6">
            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg transition-colors">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published',$book->is_published??true)?'checked':'' }} :class="darkMode?'bg-slate-700 border-slate-600':''" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                <span :class="darkMode?'text-gray-300':'text-gray-700'" class="text-sm font-medium">Publikasikan sekarang</span>
            </label>
        </div>

        <div :class="darkMode?'border-slate-700':''" class="flex flex-col-reverse sm:flex-row gap-3 border-t pt-6">
            <a href="{{ route('admin.books.index') }}" :class="darkMode?'border-slate-600 text-gray-300 hover:bg-slate-700':'border-gray-300 text-gray-700 hover:bg-gray-50'" class="flex items-center justify-center gap-2 px-6 py-2.5 border font-medium rounded-lg transition-colors text-sm order-2 sm:order-1">Batal</a>
            <button type="submit" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm order-1 sm:order-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ isset($book)?'Simpan Perubahan':'Tambah Buku' }}
            </button>
        </div>
    </form>
</div>

@include('admin.partials.tinymce-init', [
    'editors' => [
        ['selector' => '#abstract-editor', 'height' => 320, 'toolbar' => 'minimal'],
    ]
])
@endsection
