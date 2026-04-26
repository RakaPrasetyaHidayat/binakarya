<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class PageCustomizerController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    public function index()
    {
        $pages = [
            'homepage' => 'Halaman Beranda',
            'about' => 'Halaman Tentang Kami',
            'contact' => 'Halaman Kontak',
            'services' => 'Halaman Layanan',
        ];

        return view('admin.page-customizer.index', compact('pages'));
    }

    public function show($page)
    {
        $allowedPages = ['homepage', 'about', 'contact', 'services'];

        if (!in_array($page, $allowedPages)) {
            abort(404);
        }

        $pageConfig = $this->getPageConfig($page);
        $currentSettings = [];

        foreach ($pageConfig['sections'] as $section) {
            foreach ($section['inputs'] as $key => $config) {
                $currentSettings[$key] = Setting::get($key);
            }
        }

        return view('admin.page-customizer.editor', compact('page', 'pageConfig', 'currentSettings'));
    }

    public function update(Request $request, $page)
    {
        $allowedPages = ['homepage', 'about', 'contact', 'services'];

        if (!in_array($page, $allowedPages)) {
            abort(404);
        }

        $pageConfig = $this->getPageConfig($page);
        $rules = [];
        $messages = [];

        foreach ($pageConfig['sections'] as $section) {
            foreach ($section['inputs'] as $key => $config) {
                $rules[$key] = $config['validation'] ?? 'nullable|string';
                $messages["{$key}.required"] = "Kolom {$config['label']} wajib diisi";
            }
        }

        $validated = $request->validate($rules, $messages);

        foreach ($pageConfig['sections'] as $section) {
            foreach ($section['inputs'] as $key => $config) {
                if ($config['type'] === 'image' && $request->hasFile($key)) {
                    $oldFile = Setting::get($key);
                    if ($oldFile) {
                        $this->uploader->delete($oldFile);
                    }
                    $validated[$key] = $this->uploader->upload($request->file($key), "pages/{$page}");
                }

                if ($config['type'] === 'checkbox') {
                    $validated[$key] = $request->has($key) ? 1 : 0;
                }
            }
        }

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Halaman berhasil diperbarui!');
    }

    private function getPageConfig($page)
    {
        return match ($page) {
            'homepage' => [
                'title' => 'Halaman Beranda',
                'sections' => [
                    [
                        'title' => 'Section Hero',
                        'icon' => 'sparkles-outline',
                        'inputs' => [
                            'hero_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'hero_title' => ['label' => 'Judul', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'hero_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                            'hero_image' => ['label' => 'Gambar Hero', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                            'hero_button_text' => ['label' => 'Teks Button CTA', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                        ],
                    ],
                    [
                        'title' => 'Section Tentang Kami',
                        'icon' => 'information-circle-outline',
                        'inputs' => [
                            'about_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_title' => ['label' => 'Judul Section', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_profile' => ['label' => 'Deskripsi Singkat', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Layanan',
                        'icon' => 'briefcase-outline',
                        'inputs' => [
                            'service_img_1' => ['label' => 'Gambar Layanan 1', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                            'service_img_2' => ['label' => 'Gambar Layanan 2', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                            'service_img_3' => ['label' => 'Gambar Layanan 3', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                            'service_img_4' => ['label' => 'Gambar Layanan 4', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                        ],
                    ],
                    [
                        'title' => 'Section Call-To-Action',
                        'icon' => 'megaphone-outline',
                        'inputs' => [
                            'cta_title' => ['label' => 'Judul', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'cta_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:500'],
                            'cta_button_text' => ['label' => 'Teks Button', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                            'cta_button_link' => ['label' => 'Link Button', 'type' => 'text', 'validation' => 'nullable|string|max:500'],
                        ],
                    ],
                    [
                        'title' => 'Section Katalog Buku',
                        'icon' => 'book-outline',
                        'inputs' => [
                            'books_header_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'books_header_title' => ['label' => 'Judul Section', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'books_header_description' => ['label' => 'Deskripsi Section', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Blog & Artikel',
                        'icon' => 'newspaper-outline',
                        'inputs' => [
                            'blog_header_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'blog_header_title' => ['label' => 'Judul Section', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'blog_header_description' => ['label' => 'Deskripsi Section', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Kutipan',
                        'icon' => 'quote-outline',
                        'inputs' => [
                            'quote_text' => ['label' => 'Teks Kutipan', 'type' => 'textarea', 'validation' => 'nullable|string|max:500'],
                            'quote_author' => ['label' => 'Sumber/Penulis', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                        ],
                    ],
                ],
            ],
            'about' => [
                'title' => 'Halaman Tentang Kami',
                'sections' => [
                    [
                        'title' => 'Section Header',
                        'icon' => 'text-outline',
                        'inputs' => [
                            'about_header_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_header_title' => ['label' => 'Judul Halaman', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_header_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Visi',
                        'icon' => 'eye-outline',
                        'inputs' => [
                            'about_vision_title' => ['label' => 'Judul Visi', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_vision' => ['label' => 'Konten Visi', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Misi',
                        'icon' => 'target-outline',
                        'inputs' => [
                            'about_mission_title' => ['label' => 'Judul Misi', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_mission' => ['label' => 'Konten Misi', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Section Alasan Memilih Kami',
                        'icon' => 'star-outline',
                        'inputs' => [
                            'about_values_title' => ['label' => 'Judul Nilai', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'about_value_1_title' => ['label' => 'Nilai 1 - Judul', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                            'about_value_1_desc' => ['label' => 'Nilai 1 - Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:300'],
                            'about_value_2_title' => ['label' => 'Nilai 2 - Judul', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                            'about_value_2_desc' => ['label' => 'Nilai 2 - Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:300'],
                            'about_value_3_title' => ['label' => 'Nilai 3 - Judul', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                            'about_value_3_desc' => ['label' => 'Nilai 3 - Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:300'],
                            'about_value_4_title' => ['label' => 'Nilai 4 - Judul', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                            'about_value_4_desc' => ['label' => 'Nilai 4 - Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:300'],
                        ],
                    ],
                    [
                        'title' => 'Section Founder Yayasan',
                        'icon' => 'person-outline',
                        'inputs' => [
                            'founder_photo' => ['label' => 'Foto Founder', 'type' => 'image', 'validation' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                            'founder_name' => ['label' => 'Nama Founder', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'founder_bio' => ['label' => 'Penjelasan Founder', 'type' => 'textarea', 'validation' => 'nullable|string|max:2000'],
                        ],
                    ],
                ],
            ],
            'contact' => [
                'title' => 'Halaman Kontak',
                'sections' => [
                    [
                        'title' => 'Section Header',
                        'icon' => 'text-outline',
                        'inputs' => [
                            'contact_header_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'contact_header_title' => ['label' => 'Judul Halaman', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'contact_header_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:500'],
                        ],
                    ],
                    [
                        'title' => 'Section Info Kontak',
                        'icon' => 'information-circle-outline',
                        'inputs' => [
                            'contact_show_address' => ['label' => 'Tampilkan Alamat', 'type' => 'checkbox', 'validation' => 'nullable|boolean'],
                            'contact_show_email' => ['label' => 'Tampilkan Email', 'type' => 'checkbox', 'validation' => 'nullable|boolean'],
                            'contact_show_phone' => ['label' => 'Tampilkan WhatsApp', 'type' => 'checkbox', 'validation' => 'nullable|boolean'],
                            'contact_show_map' => ['label' => 'Tampilkan Google Maps', 'type' => 'checkbox', 'validation' => 'nullable|boolean'],
                        ],
                    ],
                    [
                        'title' => 'Section Peta Google',
                        'icon' => 'map-outline',
                        'inputs' => [
                            'contact_map_embed' => ['label' => 'Embed Google Maps', 'type' => 'textarea', 'validation' => 'nullable|string|max:2000'],
                        ],
                    ],
                    [
                        'title' => 'Pengaturan Form Kontak',
                        'icon' => 'document-text-outline',
                        'inputs' => [
                            'contact_form_title' => ['label' => 'Judul Form', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'contact_form_description' => ['label' => 'Deskripsi Form', 'type' => 'textarea', 'validation' => 'nullable|string|max:500'],
                            'contact_button_text' => ['label' => 'Teks Button', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                        ],
                    ],
                ],
            ],
            'services' => [
                'title' => 'Halaman Layanan',
                'sections' => [
                    [
                        'title' => 'Section Header',
                        'icon' => 'text-outline',
                        'inputs' => [
                            'services_header_tagline' => ['label' => 'Tagline', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'services_header_title' => ['label' => 'Judul Halaman', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'services_header_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:1000'],
                        ],
                    ],
                    [
                        'title' => 'Pengaturan Layout',
                        'icon' => 'grid-outline',
                        'inputs' => [
                            'services_layout_grid_columns' => ['label' => 'Jumlah Kolom', 'type' => 'select', 'options' => ['1' => '1 Kolom', '2' => '2 Kolom', '3' => '3 Kolom'], 'validation' => 'nullable|in:1,2,3'],
                            'services_layout_show_excerpt' => ['label' => 'Tampilkan Deskripsi Singkat', 'type' => 'checkbox', 'validation' => 'nullable|boolean'],
                        ],
                    ],
                    [
                        'title' => 'Section Call-To-Action',
                        'icon' => 'megaphone-outline',
                        'inputs' => [
                            'services_cta_title' => ['label' => 'Judul', 'type' => 'text', 'validation' => 'nullable|string|max:255'],
                            'services_cta_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'validation' => 'nullable|string|max:500'],
                            'services_cta_button_text' => ['label' => 'Teks Button', 'type' => 'text', 'validation' => 'nullable|string|max:100'],
                        ],
                    ],
                ],
            ],
        };
    }
}
