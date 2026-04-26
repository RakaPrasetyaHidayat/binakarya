<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CmsSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Hero Section
            'hero_tagline'                => 'DEDIKASI ILMIAH, PUBLIKASI BERINTEGRITAS, LITERASI TANPA BATAS',
            'hero_title'                  => 'Bina Karya Cendekia',
            'hero_description'            => 'Menerbitkan karya-karya ilmiah berkualitas dan mendukung pengembangan literasi di Indonesia.',

            // About Section
            'about_title'                 => 'Tentang Kami',
            'about_content_1'             => 'Bina Karya Cendekia adalah lembaga penerbitan dan pengembangan ilmu pengetahuan yang berdedikasi untuk memajukan literasi.',
            'about_content_2'             => 'Kami berkomitmen untuk menerbitkan karya-karya yang memberikan kontribusi nyata bagi perkembangan ilmu pengetahuan Indonesia.',
            'benefit_1_title'             => 'Penerbitan Berkualitas',
            'benefit_1_desc'              => 'Setiap karya melalui proses review ketat untuk menjamin kualitas dan integritas ilmiah.',
            'benefit_2_title'             => 'Distribusi Luas',
            'benefit_2_desc'              => 'Karya Anda akan menjangkau pembaca di seluruh Indonesia dan mancanegara.',

            // Quote Section
            'quote_text'                  => 'Kurasi adalah bentuk kreasi baru. Kami percaya bahwa setiap karya ilmiah berhak mendapatkan perhatian dan distribusi yang layak.',
            'quote_author'                => 'Bina Karya Cendekia Foundation',

            // Services Section
            'services_header_tagline'     => 'Layanan Kami',
            'services_header_title'       => 'Solusi Penerbitan & Pengembangan',
            'services_header_description' => 'Kami menyediakan layanan lengkap untuk mendukung pengembangan ilmu pengetahuan dan publikasi berkualitas.',
            'services_layout_grid_columns' => '3',
            'services_layout_show_excerpt' => '1',
            'services_cta_title'          => 'Tidak Menemukan yang Anda Butuhkan?',
            'services_cta_description'    => 'Hubungi tim kami untuk diskusi lebih lanjut mengenai kebutuhan Anda.',
            'services_cta_button_text'    => 'Konsultasikan',

            // Books Section
            'books_header_tagline'        => 'Katalog Buku',
            'books_header_title'          => 'Publikasi Terbaru',
            'books_header_description'    => 'Temukan koleksi buku ilmiah berkualitas dari para penulis terbaik Indonesia.',

            // Blog Section
            'blog_header_tagline'         => 'Blog & Artikel',
            'blog_header_title'           => 'Artikel Terbaru',
            'blog_header_description'     => 'Baca artikel dan insight terbaru dari tim Bina Karya Cendekia.',

            // Contact Page
            'contact_header_tagline'      => 'Hubungi Kami',
            'contact_header_title'        => 'Kami Siap Membantu',
            'contact_header_description'  => 'Tim kami selalu siap membantu Anda dalam setiap kebutuhan penerbitan.',
            'contact_form_title'          => 'Kirim Pesan',
            'contact_form_description'    => 'Isi formulir berikut dan kami akan membalas sesegera mungkin.',
            'contact_button_text'         => 'Kirim Pesan',
            'contact_cta_title'           => 'Butuh Bantuan Cepat?',
            'contact_cta_description'     => 'Hubungi kami langsung melalui WhatsApp atau Email.',
            'contact_cta_email_text'      => 'Email Kami',
            'contact_cta_whatsapp_text'   => 'Chat WhatsApp',
            'contact_show_address'        => '1',
            'contact_show_email'          => '1',
            'contact_show_phone'          => '1',
            'contact_show_map'            => '0',
            'contact_map_embed'           => '',

            // Social Media
            'facebook'                    => '',
            'instagram'                   => '',
            'youtube'                     => '',
            'tiktok'                      => '',

            // SEO & Meta
            'site_author'                 => 'Bina Karya Cendekia',
            'og_image'                    => '',
            'google_analytics_id'         => '',

            // Footer
            'footer_svg_opacity'          => '0.05',
        ];

        $added = 0;
        foreach ($defaults as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::create(['key' => $key, 'value' => $value]);
                $added++;
            }
        }

        $this->command->info("CMS Settings seeded: {$added} new settings added.");
    }
}
