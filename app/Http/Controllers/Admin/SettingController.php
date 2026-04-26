<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:255',
            'site_tagline'     => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'wa_number'        => 'nullable|string|max:20',
            'email'            => 'nullable|email',
            'address'          => 'nullable|string|max:500',
            'facebook'         => 'nullable|url',
            'instagram'        => 'nullable|url',
            'youtube'          => 'nullable|url',
            'tiktok'           => 'nullable|url',
            'footer_text'      => 'nullable|string|max:500',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'hero_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'about_img_1'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'about_img_2'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'about_img_3'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'about_img_4'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_img_1'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_img_2'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_img_3'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_img_4'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'about_profile'    => 'nullable|string|max:1000',
            'mailketing_token'        => 'nullable|string|max:1000',
            'mailketing_sender_email' => 'nullable|email',
            'mailketing_recipient'    => 'nullable|email',
            'tinymce_api_key'         => 'nullable|string|max:500',
        ]);

        // Handle Images
        $imageFields = ['logo', 'hero_image', 'about_img_1', 'about_img_2', 'about_img_3', 'about_img_4', 'service_img_1', 'service_img_2', 'service_img_3', 'service_img_4'];
        foreach ($imageFields as $imgField) {
            if ($request->hasFile($imgField)) {
                $old = Setting::get($imgField);
                if ($old) $this->uploader->delete($old);
                Setting::set($imgField, $this->uploader->upload($request->file($imgField), 'site'));
            }
        }

        // Handle Regular Fields
        $fields = [
            // Identity
            'site_name', 'site_tagline', 'site_description', 'site_author',
            'wa_number', 'email', 'phone', 'address',
            // Social
            'facebook', 'instagram', 'youtube', 'tiktok',
            // Footer
            'footer_text', 'footer_svg_opacity',
            // Hero
            'hero_tagline', 'hero_title', 'hero_description',
            // About
            'about_title', 'about_profile', 'about_vision', 'about_mission',
            'about_content_1', 'about_content_2',
            'benefit_1_title', 'benefit_1_desc',
            'benefit_2_title', 'benefit_2_desc',
            // Quote
            'quote_text', 'quote_author',
            // CTA (bottom section)
            'cta_title', 'cta_description', 'cta_button_text',
            // Services Section
            'services_header_tagline', 'services_header_title', 'services_header_description',
            'services_layout_grid_columns', 'services_layout_show_excerpt',
            'services_cta_title', 'services_cta_description', 'services_cta_button_text',
            // Books Section
            'books_header_tagline', 'books_header_title', 'books_header_description',
            // Blog Section
            'blog_header_tagline', 'blog_header_title', 'blog_header_description',
            // SEO
            'og_image', 'google_analytics_id',
            // Contact Page
            'contact_header_tagline', 'contact_header_title', 'contact_header_description',
            'contact_form_title', 'contact_form_description', 'contact_button_text',
            'contact_cta_title', 'contact_cta_description',
            'contact_cta_email_text', 'contact_cta_whatsapp_text',
            'contact_show_address', 'contact_show_email', 'contact_show_phone',
            'contact_show_map', 'contact_map_embed',
            // Integrations
            'mailketing_token', 'mailketing_sender_email', 'mailketing_recipient',
            'tinymce_api_key',
        ];

        $htmlFields = [
            'site_description', 'hero_description', 'about_profile',
            'about_content_1', 'about_content_2', 'about_vision', 'about_mission',
            'footer_text', 'benefit_1_desc', 'benefit_2_desc',
            'services_header_description', 'books_header_description', 'blog_header_description',
            'contact_header_description', 'contact_form_description', 'contact_cta_description',
            'contact_map_embed',
        ];

        $checkboxFields = [
            'contact_show_address', 'contact_show_email', 'contact_show_phone', 'contact_show_map',
            'services_layout_show_excerpt'
        ];

        foreach ($fields as $field) {
            if (in_array($field, $checkboxFields)) {
                Setting::set($field, $request->has($field) ? '1' : '0');
                continue;
            }

            if ($request->has($field)) {
                $value = $request->input($field);
                if (in_array($field, $htmlFields)) {
                    $value = clean($value);
                }
                Setting::set($field, $value ?? '');
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
