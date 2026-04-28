<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    /**
     * Convert empty string fields to null before validation,
     * so nullable rules work correctly and empty inputs clear the DB value.
     */
    protected function prepareForValidation(): void
    {
        $nullableFields = [
            'category_id', 'isbn', 'doi', 'published_year', 'abstract',
            'keywords', 'edition', 'preview_url', 'wa_number',
            'shopee_url', 'tokopedia_url', 'custom_url', 'custom_url_label', 'price',
        ];

        $merge = [];
        foreach ($nullableFields as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $merge[$field] = null;
            }
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|min:3|max:255',
            'category_id'      => 'nullable|integer|exists:categories,id',
            'author'           => 'required|string|min:3|max:255',
            'isbn'             => 'nullable|string|regex:/^[0-9\-X]{10,20}$/|max:30',
            'doi'              => 'nullable|string|regex:/^10\.\d{4,}/|max:255',
            'published_year'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'abstract'         => 'nullable|string|min:10|max:5000',
            'keywords'         => 'nullable|string|max:1000',
            'edition'          => 'nullable|string|max:100',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'pdf_file'         => 'nullable|mimes:pdf|max:51200',
            'preview_file'     => 'nullable|mimes:pdf,png,jpg,jpeg|max:10240',
            'preview_url'      => 'nullable|url|max:500',
            'wa_number'        => 'nullable|regex:/^[0-9+\-\s()]{7,25}$/|max:25',
            'shopee_url'       => 'nullable|url|max:500',
            'tokopedia_url'    => 'nullable|url|max:500',
            'custom_url'       => 'nullable|url|max:500',
            'custom_url_label' => 'nullable|string|min:2|max:100',
            'price'            => 'nullable|numeric|min:0|max:999999999.99',
            'is_published'     => 'boolean',
        ];
    }
}
