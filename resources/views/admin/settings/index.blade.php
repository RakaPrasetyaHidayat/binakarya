@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div x-data="{ tab: 'general', darkMode: localStorage.getItem('darkMode') === 'true' }" class="max-w-4xl">
    {{-- Tab Navigation --}}
    <div :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="flex gap-1.5 mb-6 p-1 rounded-xl shadow-sm border overflow-x-auto transition-colors">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🌐 Umum</button>
        <button @click="tab = 'hero'" :class="tab === 'hero' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🦸 Hero</button>
        <button @click="tab = 'about'" :class="tab === 'about' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">�� Tentang</button>
        <button @click="tab = 'services'" :class="tab === 'services' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">⚙️ Layanan</button>
        <button @click="tab = 'books'" :class="tab === 'books' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">📚 Buku</button>
        <button @click="tab = 'blog'" :class="tab === 'blog' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">✍️ Blog</button>
        <button @click="tab = 'sections'" :class="tab === 'sections' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🎯 CTA &amp; Quote</button>
        <button @click="tab = 'contact'" :class="tab === 'contact' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">📞 Kontak</button>
        <button @click="tab = 'contact_page'" :class="tab === 'contact_page' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🗺️ Hal. Kontak</button>
        <button @click="tab = 'seo'" :class="tab === 'seo' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🔍 SEO</button>
        <button @click="tab = 'integration'" :class="tab === 'integration' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🔌 Integrasi</button>
        <button @click="tab = 'editor'" :class="tab === 'editor' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">📝 Editor</button>
        <button @click="tab = 'footer'" :class="tab === 'footer' ? 'bg-blue-600 text-white shadow-md' : (darkMode ? 'text-gray-400 hover:bg-slate-700' : 'text-gray-500 hover:bg-gray-50')" class="px-4 py-2 rounded-lg text-xs font-semibold transition flex-shrink-0">🏁 Footer</button>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Tab: General --}}
        <div x-show="tab === 'general'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Identitas Website</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Website <span class="text-red-500">*</span></label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings->get('site_name', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                            <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings->get('site_tagline', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Footer</label>
                        <textarea name="site_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">{{ old('site_description', $settings->get('site_description', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Utama</label>
                        @if($settings->get('logo'))
                            <img src="{{ asset('storage/' . $settings->get('logo')) }}" alt="Logo" class="h-12 mb-3 bg-gray-50 p-2 rounded">
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Hero Section --}}
        <div x-show="tab === 'hero'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Pengaturan Hero Utama</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Hero (Tagline Kecil)</label>
                        <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $settings->get('hero_tagline', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Hero (H1)</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $settings->get('hero_title', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Hero</label>
                        <textarea name="hero_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">{{ old('hero_description', $settings->get('hero_description', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Hero</label>
                        @if($settings->get('hero_image'))
                            <img src="{{ asset('storage/' . $settings->get('hero_image')) }}" alt="Hero" class="h-32 mb-3 rounded-xl shadow-sm">
                        @endif
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: About Section --}}
        <div x-show="tab === 'about'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Section "Siapa Kami?"</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kecil</label>
                        <input type="text" name="about_title" value="{{ old('about_title', $settings->get('about_title', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profil/Deskripsi Singkat</label>
                        <textarea name="about_profile" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('about_profile', $settings->get('about_profile', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten Paragraf 1</label>
                        <textarea name="about_content_1" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('about_content_1', $settings->get('about_content_1', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten Paragraf 2</label>
                        <textarea name="about_content_2" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('about_content_2', $settings->get('about_content_2', '')) }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3">Keunggulan 1</h3>
                            <input type="text" name="benefit_1_title" value="{{ old('benefit_1_title', $settings->get('benefit_1_title', '')) }}" placeholder="Judul" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm mb-2">
                            <input type="text" name="benefit_1_desc" value="{{ old('benefit_1_desc', $settings->get('benefit_1_desc', '')) }}" placeholder="Keterangan singkat" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3">Keunggulan 2</h3>
                            <input type="text" name="benefit_2_title" value="{{ old('benefit_2_title', $settings->get('benefit_2_title', '')) }}" placeholder="Judul" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm mb-2">
                            <input type="text" name="benefit_2_desc" value="{{ old('benefit_2_desc', $settings->get('benefit_2_desc', '')) }}" placeholder="Keterangan singkat" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Galeri Foto (Grid 4 Gambar)</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @for($i=1; $i<=4; $i++)
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Foto {{ $i }}</label>
                                @if($settings->get('about_img_'.$i))
                                    <img src="{{ asset('storage/' . $settings->get('about_img_'.$i)) }}" class="w-full h-20 object-cover rounded mb-2">
                                @endif
                                <input type="file" name="about_img_{{ $i }}" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-50">
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Homepage Other Sections --}}
        <div x-show="tab === 'sections'" class="space-y-6">
            {{-- Quote Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Section Kutipan (Quote)</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Kutipan</label>
                        <textarea name="quote_text" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kutipan inspiratif...">{{ old('quote_text', $settings->get('quote_text', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Kutipan</label>
                        <input type="text" name="quote_author" value="{{ old('quote_author', $settings->get('quote_author', '')) }}" placeholder="— Nama Sumber" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Service Images --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Gambar Section Layanan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Layanan {{ $i }}</label>
                            @if($settings->get('service_img_'.$i))
                                <img src="{{ asset('storage/' . $settings->get('service_img_'.$i)) }}" class="w-full h-28 object-cover rounded-xl mb-2 border border-gray-200">
                            @endif
                            <input type="file" name="service_img_{{ $i }}" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                        </div>
                    @endfor
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Section CTA (Hubungi Kami)</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul CTA</label>
                        <input type="text" name="cta_title" value="{{ old('cta_title', $settings->get('cta_title', '')) }}" placeholder="Hubungi Kami" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi CTA</label>
                        <textarea name="cta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Deskripsi singkat ajakan...">{{ old('cta_description', $settings->get('cta_description', '')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol CTA</label>
                        <input type="text" name="cta_button_text" value="{{ old('cta_button_text', $settings->get('cta_button_text', '')) }}" placeholder="Konsultasikan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Footer Text --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Teks Footer</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Footer</label>
                        <textarea name="footer_text" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Teks pendek yang tampil di footer...">{{ old('footer_text', $settings->get('footer_text', '')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Services Section --}}
        <div x-show="tab === 'services'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">⚙️ Section Layanan</h2>
                <p class="text-xs text-gray-500 mb-5">Kontrol header dan tampilan section Layanan di homepage.</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline (Teks Kecil)</label>
                            <input type="text" name="services_header_tagline" value="{{ old('services_header_tagline', $settings->get('services_header_tagline', 'Layanan Kami')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Layanan Kami">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Section</label>
                            <input type="text" name="services_header_title" value="{{ old('services_header_title', $settings->get('services_header_title', 'Solusi dan Layanan')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Solusi dan Layanan">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Section</label>
                        <textarea name="services_header_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Deskripsi layanan...">{{ old('services_header_description', $settings->get('services_header_description', '')) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kolom Grid</label>
                            <select name="services_layout_grid_columns" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="2" {{ $settings->get('services_layout_grid_columns','3') == '2' ? 'selected' : '' }}>2 Kolom</option>
                                <option value="3" {{ $settings->get('services_layout_grid_columns','3') == '3' ? 'selected' : '' }}>3 Kolom</option>
                                <option value="4" {{ $settings->get('services_layout_grid_columns','3') == '4' ? 'selected' : '' }}>4 Kolom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tampilkan Excerpt</label>
                            <select name="services_layout_show_excerpt" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="1" {{ $settings->get('services_layout_show_excerpt','1') ? 'selected' : '' }}>Ya, tampilkan</option>
                                <option value="0" {{ !$settings->get('services_layout_show_excerpt','1') ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">CTA Bawah Section Layanan</h2>
                <p class="text-xs text-gray-500 mb-5">Tombol ajakan di bawah daftar layanan.</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul CTA Layanan</label>
                            <input type="text" name="services_cta_title" value="{{ old('services_cta_title', $settings->get('services_cta_title', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol CTA</label>
                            <input type="text" name="services_cta_button_text" value="{{ old('services_cta_button_text', $settings->get('services_cta_button_text', 'Konsultasikan')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi CTA</label>
                        <textarea name="services_cta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">{{ old('services_cta_description', $settings->get('services_cta_description', '')) }}</textarea>
                    </div>
                </div>
            </div>
            {{-- Service Images --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Gambar Dekorasi Section Layanan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Layanan {{ $i }}</label>
                            @if($settings->get('service_img_'.$i))
                                <img src="{{ asset('storage/' . $settings->get('service_img_'.$i)) }}" class="w-full h-28 object-cover rounded-xl mb-2 border border-gray-200">
                            @endif
                            <input type="file" name="service_img_{{ $i }}" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Tab: Books Section --}}
        <div x-show="tab === 'books'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">📚 Section Katalog Buku</h2>
                <p class="text-xs text-gray-500 mb-5">Kontrol teks header section Buku di homepage.</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline (Teks Kecil)</label>
                            <input type="text" name="books_header_tagline" value="{{ old('books_header_tagline', $settings->get('books_header_tagline', 'Katalog Buku')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Katalog Buku">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Section</label>
                            <input type="text" name="books_header_title" value="{{ old('books_header_title', $settings->get('books_header_title', 'Publikasi Terbaru')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Publikasi Terbaru">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Section</label>
                        <textarea name="books_header_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Temukan koleksi buku kami...">{{ old('books_header_description', $settings->get('books_header_description', '')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Blog Section --}}
        <div x-show="tab === 'blog'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">✍️ Section Blog & Artikel</h2>
                <p class="text-xs text-gray-500 mb-5">Kontrol teks header section Blog di homepage.</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline (Teks Kecil)</label>
                            <input type="text" name="blog_header_tagline" value="{{ old('blog_header_tagline', $settings->get('blog_header_tagline', 'Blog & Artikel')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Blog & Artikel">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Section</label>
                            <input type="text" name="blog_header_title" value="{{ old('blog_header_title', $settings->get('blog_header_title', 'Artikel Terbaru')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Artikel Terbaru">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Section</label>
                        <textarea name="blog_header_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Baca artikel terbaru...">{{ old('blog_header_description', $settings->get('blog_header_description', '')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Contact --}}
        <div x-show="tab === 'contact'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Kontak & Sosial Media</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp (628...)</label>
                        <input type="text" name="wa_number" value="{{ old('wa_number', $settings->get('wa_number', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email', $settings->get('email', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Kantor</label>
                        <textarea name="address" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('address', $settings->get('address', '')) }}</textarea>
                    </div>
                    <div class="pt-4 md:col-span-2 border-t border-gray-50">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Media Sosial</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="blue-label text-xs">Facebook URL</label>
                                <input type="url" name="facebook" value="{{ old('facebook', $settings->get('facebook', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>
                            <div>
                                <label class="blue-label text-xs">Instagram URL</label>
                                <input type="url" name="instagram" value="{{ old('instagram', $settings->get('instagram', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>
                            <div>
                                <label class="blue-label text-xs">TikTok URL</label>
                                <input type="url" name="tiktok" value="{{ old('tiktok', $settings->get('tiktok', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>
                            <div>
                                <label class="blue-label text-xs">YouTube URL</label>
                                <input type="url" name="youtube" value="{{ old('youtube', $settings->get('youtube', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Contact Page Content (Halaman Kontak) --}}
        <div x-show="tab === 'contact_page'" class="space-y-6">
            {{-- Header Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">🗺️ Header Halaman Kontak</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline (Teks Kecil)</label>
                            <input type="text" name="contact_header_tagline" value="{{ old('contact_header_tagline', $settings->get('contact_header_tagline', 'Hubungi Kami')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Hubungi Kami">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman (H1)</label>
                            <input type="text" name="contact_header_title" value="{{ old('contact_header_title', $settings->get('contact_header_title', 'Kontak')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Kontak">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Header</label>
                        <textarea name="contact_header_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Kami siap membantu...">{{ old('contact_header_description', $settings->get('contact_header_description', '')) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Form Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">📝 Form Pesan</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Form</label>
                            <input type="text" name="contact_form_title" value="{{ old('contact_form_title', $settings->get('contact_form_title', 'Kirim Pesan')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Kirim Pesan">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol Kirim</label>
                            <input type="text" name="contact_button_text" value="{{ old('contact_button_text', $settings->get('contact_button_text', 'Kirim Pesan')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Kirim Pesan">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Form</label>
                        <textarea name="contact_form_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('contact_form_description', $settings->get('contact_form_description', '')) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">🚀 CTA Bawah Halaman Kontak</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul CTA</label>
                        <input type="text" name="contact_cta_title" value="{{ old('contact_cta_title', $settings->get('contact_cta_title', 'Siap untuk Berkolaborasi?')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Siap untuk Berkolaborasi?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi CTA</label>
                        <textarea name="contact_cta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('contact_cta_description', $settings->get('contact_cta_description', '')) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol Email</label>
                            <input type="text" name="contact_cta_email_text" value="{{ old('contact_cta_email_text', $settings->get('contact_cta_email_text', 'Kirim Email')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol WhatsApp</label>
                            <input type="text" name="contact_cta_whatsapp_text" value="{{ old('contact_cta_whatsapp_text', $settings->get('contact_cta_whatsapp_text', 'Hubungi WhatsApp')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visibility Toggles --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">👁️ Visibilitas Elemen Kontak</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <input type="hidden" name="contact_show_address" value="0">
                        <input type="checkbox" name="contact_show_address" value="1" {{ $settings->get('contact_show_address', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Tampilkan Alamat</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <input type="hidden" name="contact_show_email" value="0">
                        <input type="checkbox" name="contact_show_email" value="1" {{ $settings->get('contact_show_email', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Tampilkan Email</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <input type="hidden" name="contact_show_phone" value="0">
                        <input type="checkbox" name="contact_show_phone" value="1" {{ $settings->get('contact_show_phone', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Tampilkan WhatsApp/Telepon</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <input type="hidden" name="contact_show_map" value="0">
                        <input type="checkbox" name="contact_show_map" value="1" {{ $settings->get('contact_show_map', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Tampilkan Peta</span>
                    </label>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Embed Google Maps (opsional, overrides default map)</label>
                    <textarea name="contact_map_embed" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono" placeholder="&lt;iframe src=&quot;...&quot;&gt;&lt;/iframe&gt;">{{ old('contact_map_embed', $settings->get('contact_map_embed', '')) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Paste iframe embed dari Google Maps. Kosongkan untuk menggunakan peta default.</p>
                </div>
            </div>
        </div>

        </div>

        {{-- Tab: SEO --}}
        <div x-show="tab === 'seo'" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">🔍 Pengaturan SEO</h2>
                <p class="text-xs text-gray-500 mb-5">Optimasi mesin pencari dan social sharing.</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penulis / Author</label>
                            <input type="text" name="site_author" value="{{ old('site_author', $settings->get('site_author', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Bina Karya Cendekia">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                            <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings->get('google_analytics_id', '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="G-XXXXXXXXXX">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar OG (Open Graph / Social Sharing)</label>
                        @if($settings->get('og_image'))
                            <img src="{{ asset('storage/' . $settings->get('og_image')) }}" class="h-20 rounded mb-2 border">
                        @endif
                        <input type="file" name="og_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                        <p class="text-xs text-gray-400 mt-1">Gambar yang tampil saat link dibagikan di WhatsApp, Facebook, dll. Ukuran ideal: 1200×630px.</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-xs font-semibold text-blue-800 mb-2">✅ SEO yang sudah aktif otomatis:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Meta title & description di setiap halaman</li>
                            <li>• Schema.org markup (JSON-LD) untuk buku</li>
                            <li>• Citation meta untuk buku ilmiah</li>
                            <li>• Sitemap XML otomatis</li>
                            <li>• Canonical URL</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Integration --}}
        <div x-show="tab === 'integration'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Pengaturan Mailketing API</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Token Mailketing</label>
                        <input type="password" name="mailketing_token" value="{{ old('mailketing_token', $settings->get('mailketing_token', '')) }}" placeholder="Masukkan token..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Pengirim (Sender Email)</label>
                            <input type="email" name="mailketing_sender_email" value="{{ old('mailketing_sender_email', $settings->get('mailketing_sender_email', '')) }}" placeholder="Cth: no-reply@domain.com" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penerima Notifikasi (Email Admin)</label>
                        <input type="email" name="mailketing_recipient" value="{{ old('mailketing_recipient', $settings->get('mailketing_recipient', '')) }}" placeholder="Email tujuan untuk menerima notif..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Editor --}}
        <div x-show="tab === 'editor'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-1">📝 Pengaturan Editor</h2>
                <p class="text-xs text-gray-500 mb-5">Konfigurasi TinyMCE WYSIWYG Editor untuk blog.</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">TinyMCE API Key</label>
                        <input type="text" name="tinymce_api_key" value="{{ old('tinymce_api_key', $settings->get('tinymce_api_key', '')) }}" placeholder="Masukkan API Key dari TinyMCE Cloud..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Dapatkan API Key gratis di <a href="https://www.tiny.cloud/" target="_blank" class="text-blue-600 hover:underline">tiny.cloud</a>. Kosongkan untuk menggunakan mode community (terbatas).</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-xs font-semibold text-blue-800 mb-2">ℹ️ Informasi:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Dengan API Key: Anda mendapatkan fitur premium TinyMCE (plugins lengkap, cloud-based)</li>
                            <li>• Tanpa API Key: Editor akan menggunakan TinyMCE Community (fitur dasar, self-hosted)</li>
                            <li>• API Key gratis tersedia untuk penggunaan non-komersial</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Footer Design --}}
        <div x-show="tab === 'footer'" class="space-y-6">
            {{-- Footer Appearance --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-5">Desain Footer</h2>
                <div class="space-y-6">
                    {{-- Background Opacity Control --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Opasitas Background SVG</label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="footer_svg_opacity" value="{{ old('footer_svg_opacity', $settings->get('footer_svg_opacity', '10')) }}" min="0" max="100" step="5" class="flex-grow h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" id="opacitySlider">
                            <span class="text-sm font-semibold text-blue-600 min-w-[3rem] text-right" id="opacityValue">10%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Atur kejelasan background dari 0% (transparan) hingga 100% (opaque). Rekomendasi: 5-15% untuk light mode, 8-20% untuk dark mode.</p>
                    </div>

                    {{-- Footer Assets Info --}}
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Aset yang Digunakan</h3>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex gap-3 items-start">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-gray-700">
                                    <p class="font-medium mb-1">Footer Background SVG</p>
                                    <p class="text-gray-600">Lokasi: <code class="bg-white px-2 py-1 rounded text-xs text-blue-600">/public/image/footerBackground.svg</code></p>
                                    <p class="text-gray-600 mt-1">File ini adalah aset dekoratif yang memberikan aksen visual profesional pada bagian footer website. SVG akan ditampilkan di belakang konten footer dengan opasitas yang dapat diatur.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Preview --}}
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Preview Footer</h3>
                        <div class="rounded-lg overflow-hidden border border-gray-200">
                            <div class="relative bg-white dark:bg-slate-900 aspect-video flex items-center justify-center" style="opacity-preview-demo">
                                <div class="absolute inset-0 opacity-10 dark:opacity-10 overflow-hidden">
                                    <img src="{{ asset('image/footerBackground.svg') }}" alt="SVG Background" class="w-full h-full object-cover" />
                                </div>
                                <div class="relative z-10 text-center">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Preview Footer dengan SVG Background</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical Info --}}
                    <div class="pt-4 border-t border-gray-100 bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Informasi Teknis</h3>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>✓ Mode responsif untuk desktop, tablet, dan mobile</li>
                            <li>✓ Kompatibel dengan light mode dan dark mode</li>
                            <li>✓ Tidak mengganggu readability konten footer</li>
                            <li>✓ Performance optimized dengan SVG format</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4">
            <p class="text-xs text-gray-500">Pastikan semua data sudah benar sebelum menyimpan.</p>
            <button type="submit" class="bg-blue-600 text-white px-10 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition transform">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
    // Opacity slider handler
    const opacitySlider = document.getElementById('opacitySlider');
    const opacityValue = document.getElementById('opacityValue');
    
    if (opacitySlider) {
        opacitySlider.addEventListener('input', function() {
            opacityValue.textContent = this.value + '%';
        });
    }
</script>
@endsection
