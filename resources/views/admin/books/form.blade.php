@extends('layouts.admin')

@section('title', isset($book) ? 'Edit Buku' : 'Tambah Buku')

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
                <textarea name="abstract" id="tinymce-abstract" class="hidden">{{ old('abstract',$book->abstract??'') }}</textarea>
                <div id="editor-abstract" class="@error('abstract') border-red-400 @enderror rounded-lg overflow-hidden" style="min-height:300px"></div>
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

@php
$tinymceApiKey=\App\Models\Setting::get('tinymce_api_key','');
$tinymceSrc=$tinymceApiKey?'https://cdn.tiny.cloud/1/'.$tinymceApiKey.'/tinymce/7/tinymce.min.js':'https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js';
@endphp

<script src="{{ $tinymceSrc }}" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const isDark=document.documentElement.classList.contains('dark');
    tinymce.init({
        selector:'#editor-abstract',
        setup:function(editor){editor.on('init',function(){editor.setContent(document.getElementById('tinymce-abstract').value);});},
        height:400,menubar:true,
        plugins:['advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','code','fullscreen','insertdatetime','media','table','help','wordcount','codesample','emoticons','pagebreak','nonbreaking','save','directionality'],
        toolbar:'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table codesample | forecolor backcolor emoticons charmap | removeformat | help',
        toolbar_mode:'sliding',
        content_style:isDark?'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.6;color:#e5e7eb;background-color:#1f2937;}h1,h2,h3,h4,h5,h6{color:#f3f4f6;}blockquote{border-left:4px solid #7c3aed;background:rgba(124,58,237,0.1);padding:1rem 1.5rem;margin:1rem 0;}a{color:#60a5fa;}img{max-width:100%;height:auto;border-radius:0.5rem;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #4b5563;padding:0.5rem;}th{background-color:#374151;}':'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.6;color:#374151;}blockquote{border-left:4px solid #2563eb;background:rgba(37,99,235,0.08);padding:1rem 1.5rem;margin:1rem 0;}img{max-width:100%;height:auto;border-radius:0.5rem;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #e5e7eb;padding:0.5rem;}th{background-color:#f9fafb;}',
        skin:isDark?'oxide-dark':'oxide',content_css:isDark?'dark':'default',branding:false,promotion:false,
        images_upload_url:false,automatic_uploads:false,relative_urls:false,remove_script_host:false,convert_urls:true,entity_encoding:'raw',valid_elements:'*[*]',
        fontsize_formats:'8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
        font_family_formats:'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva,sans-serif;',
        block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre;',
        codesample_languages:[{text:'HTML/XML',value:'markup'},{text:'JavaScript',value:'javascript'},{text:'CSS',value:'css'},{text:'PHP',value:'php'},{text:'Python',value:'python'},{text:'Java',value:'java'},{text:'C',value:'c'},{text:'C++',value:'cpp'},{text:'SQL',value:'sql'},{text:'Bash',value:'bash'}],
        table_default_styles:{'border-collapse':'collapse','width':'100%'},table_default_attributes:{'border':'1'},
        link_default_target:'_blank',link_assume_external_targets:'https',resize:true,min_height:300,max_height:600,
    });
    document.querySelector('form').addEventListener('submit',function(){
        document.getElementById('tinymce-abstract').value=tinymce.get('editor-abstract').getContent();
    });
});
</script>
@endsection
